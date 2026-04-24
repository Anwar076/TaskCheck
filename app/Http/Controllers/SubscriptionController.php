<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\Billing\MollieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use RuntimeException;

class SubscriptionController extends Controller
{
    public function __construct(
        private readonly MollieService $mollieService
    ) {
    }

    /**
     * Show subscription plans
     */
    public function choosePlan(): View
    {
        $company = Auth::user()->company;
        if ($company) {
            $this->syncPendingPaymentStatus($company);
            $company->refresh();
        }
        $trialDaysRemaining = $company ? $company->trialDaysRemaining() : 0;
        $currentPlan = $company ? $company->subscription_plan : null;
        $trialExpired = $company ? $company->trialExpired() : false;

        return view('subscription.choose-plan', [
            'plans' => Company::PLANS,
            'trialDaysRemaining' => $trialDaysRemaining,
            'currentPlan' => $currentPlan,
            'trialExpired' => $trialExpired,
            'company' => $company,
        ]);
    }

    /**
     * Show subscription details
     */
    public function show(): View|RedirectResponse
    {
        $company = Auth::user()->company;
        
        if (!$company) {
            return redirect()->route('subscription.choose-plan');
        }

        $this->syncPendingPaymentStatus($company);
        $company->refresh();

        $nextBillingDate = null;
        $daysUntilNextBilling = null;
        $pendingPlanDetails = null;

        if ($company->hasActiveSubscription() && $company->mollie_customer_id && $company->mollie_subscription_id) {
            try {
                $subscription = $this->mollieService->getSubscription(
                    (string) $company->mollie_customer_id,
                    (string) $company->mollie_subscription_id
                );

                $nextPaymentDate = (string) ($subscription['nextPaymentDate'] ?? '');
                if ($nextPaymentDate !== '') {
                    $nextBillingDate = Carbon::parse($nextPaymentDate)->startOfDay();
                    $daysUntilNextBilling = max(0, now()->startOfDay()->diffInDays($nextBillingDate, false));
                }
            } catch (\Throwable $e) {
                report($e);
            }
        }

        if ($company->pending_subscription_plan && isset(Company::PLANS[$company->pending_subscription_plan])) {
            $pendingPlanDetails = Company::PLANS[$company->pending_subscription_plan];
        }

        return view('subscription.show', [
            'company' => $company,
            'planDetails' => $company->getPlanDetails(),
            'nextBillingDate' => $nextBillingDate,
            'daysUntilNextBilling' => $daysUntilNextBilling,
            'pendingPlanDetails' => $pendingPlanDetails,
        ]);
    }

    public function activate(Request $request): RedirectResponse
    {
        $request->validate([
            'plan' => ['required', 'in:starter,professional,enterprise,custom'],
        ]);

        $company = Auth::user()->company;

        if (!$company) {
            return redirect()->route('subscription.choose-plan')
                ->with('error', 'Organisatie niet gevonden.');
        }

        if ($request->plan === 'custom') {
            return redirect()->route('subscription.choose-plan')
                ->with('warning', 'Custom abonnementen worden handmatig geactiveerd. Neem contact op met support.');
        }

        $plan = Company::PLANS[$request->plan];
        $billingEmail = (string) Auth::user()->email;
        $isStarterTestOverride = $this->shouldUseStarterTestOverride($billingEmail, (string) $request->plan);
        $amountValue = $isStarterTestOverride
            ? '1.00'
            : number_format((float) $plan['price_monthly'], 2, '.', '');
        $subscriptionInterval = $this->resolveSubscriptionInterval($billingEmail, (string) $request->plan);

        try {
            $webhookUrl = $this->resolveWebhookUrl();

            if ($company->hasActiveSubscription()) {
                if ($company->subscription_plan === $request->plan && !$company->pending_subscription_plan) {
                    return redirect()->route('subscription.show')
                        ->with('success', 'Dit is al je huidige abonnement.');
                }

                if ($company->mollie_customer_id && $company->mollie_subscription_id) {
                    $this->mollieService->updateSubscription(
                        (string) $company->mollie_customer_id,
                        (string) $company->mollie_subscription_id,
                        [
                            'amount' => [
                                'currency' => 'EUR',
                                'value' => $amountValue,
                            ],
                            'description' => "TaskCheck {$plan['name']} abonnement",
                            'interval' => $subscriptionInterval,
                            'metadata' => [
                                'company_id' => $company->id,
                                'plan' => $request->plan,
                                'interval' => $subscriptionInterval,
                            ],
                        ]
                    );

                    $company->update([
                        'pending_subscription_plan' => $request->plan,
                    ]);

                    return redirect()->route('subscription.show')
                        ->with('success', 'Planwijziging ingepland. Je nieuwe plan gaat in bij de volgende facturatie.');
                }
            }

            if (!$company->mollie_customer_id) {
                $customer = $this->mollieService->createCustomer(
                    $company->name,
                    $billingEmail
                );

                $company->update([
                    'mollie_customer_id' => $customer['id'] ?? null,
                ]);
            }

            $paymentPayload = [
                'amount' => [
                    'currency' => 'EUR',
                    'value' => $amountValue,
                ],
                'description' => "TaskCheck {$plan['name']} abonnement",
                'method' => 'ideal',
                'redirectUrl' => route('subscription.payment-return'),
                'webhookUrl' => $webhookUrl,
                'sequenceType' => 'first',
                'customerId' => $company->mollie_customer_id,
                'metadata' => [
                    'company_id' => $company->id,
                    'plan' => $request->plan,
                    'interval' => $subscriptionInterval,
                ],
            ];

            $payment = $this->createFirstPaymentWithFallback(
                $company,
                $paymentPayload,
                $billingEmail,
                $isStarterTestOverride
            );

            $checkoutUrl = data_get($payment, '_links.checkout.href');
            $paymentId = trim((string) ($payment['id'] ?? ''));

            if (!$checkoutUrl || $paymentId === '') {
                throw new RuntimeException('Checkout URL of payment-id ontbreekt in Mollie response.');
            }

            $company->update([
                'pending_subscription_plan' => $request->plan,
                'mollie_payment_id' => $paymentId,
            ]);
        } catch (\Throwable $e) {
            report($e);
            return redirect()->route('subscription.choose-plan')
                ->with('error', 'Mollie checkout kon niet worden gestart: '.$e->getMessage());
        }

        return redirect()->away($checkoutUrl);
    }

    /**
     * Cancel subscription
     */
    public function cancel(): RedirectResponse
    {
        $company = Auth::user()->company;

        if (!$company) {
            return redirect()->route('subscription.choose-plan');
        }

        try {
            $accessUntil = $company->subscription_ends_at?->copy();

            if ($company->mollie_customer_id && $company->mollie_subscription_id) {
                try {
                    $subscription = $this->mollieService->getSubscription(
                        (string) $company->mollie_customer_id,
                        (string) $company->mollie_subscription_id
                    );
                    $nextPaymentDate = trim((string) ($subscription['nextPaymentDate'] ?? ''));
                    if ($nextPaymentDate !== '') {
                        $accessUntil = Carbon::parse($nextPaymentDate)->endOfDay();
                    }
                } catch (\Throwable $ignored) {
                    // If fetch fails, we still continue cancellation.
                }

                $this->mollieService->cancelSubscription(
                    $company->mollie_customer_id,
                    $company->mollie_subscription_id
                );
            }

            // Defensive cleanup: cancel any other active/pending customer subscriptions as well.
            // This prevents new charges from orphaned or duplicate recurring subscriptions.
            if ($company->mollie_customer_id) {
                $subscriptions = $this->mollieService->getCustomerSubscriptions((string) $company->mollie_customer_id);
                foreach ($subscriptions as $subscription) {
                    $subscriptionId = trim((string) ($subscription['id'] ?? ''));
                    $status = strtolower(trim((string) ($subscription['status'] ?? '')));
                    if ($subscriptionId === '' || in_array($status, ['canceled', 'completed'], true)) {
                        continue;
                    }

                    try {
                        $this->mollieService->cancelSubscription((string) $company->mollie_customer_id, $subscriptionId);
                    } catch (\Throwable $ignored) {
                        // Keep cancellation resilient if one of the old subscriptions no longer exists.
                    }
                }

                // Also cancel recent in-progress customer payments to prevent
                // additional debits after explicit cancellation.
                $payments = $this->mollieService->getRecentCustomerPayments((string) $company->mollie_customer_id, 50);
                foreach ($payments as $payment) {
                    $paymentId = trim((string) ($payment['id'] ?? ''));
                    $status = strtolower(trim((string) ($payment['status'] ?? '')));

                    if ($paymentId === '' || !in_array($status, ['open', 'pending', 'authorized'], true)) {
                        continue;
                    }

                    try {
                        $this->mollieService->cancelPayment($paymentId);
                    } catch (\Throwable $ignored) {
                        // Some pending methods cannot be cancelled anymore by API.
                        // We continue cancellation flow and keep subscription stopped.
                    }
                }
            }

            $company->update([
                'subscription_status' => 'cancelled',
                'mollie_subscription_id' => null,
                'subscription_ends_at' => $accessUntil ?? now(),
                'pending_subscription_plan' => null,
                'mollie_payment_id' => null,
            ]);
        } catch (\Throwable $e) {
            return redirect()->route('subscription.show')
                ->with('error', 'Opzeggen via Mollie is mislukt: '.$e->getMessage());
        }

        return redirect()->route('subscription.show')
            ->with('success', 'Je abonnement is opgezegd. Het blijft actief tot het einde van de factureringsperiode.');
    }

    public function paymentReturn(): RedirectResponse
    {
        $company = Auth::user()->company;
        if (!$company) {
            return redirect()->route('subscription.choose-plan');
        }

        $this->syncPendingPaymentStatus($company);
        $company->refresh();

        if ($company->hasActiveSubscription()) {
            return redirect()->route('subscription.show')
                ->with('success', 'Betaling bevestigd. Je abonnement is nu actief.');
        }

        if (!$company->mollie_payment_id) {
            return redirect()->route('subscription.show')
                ->with('success', 'Betaling gestart. Zodra Mollie bevestigt, wordt je abonnement actief.');
        }

        try {
            $payment = $this->mollieService->getPayment($company->mollie_payment_id);
            $status = $payment['status'] ?? null;

            if ($status === 'paid') {
                if ($this->shouldIgnorePaidActivation($company, (string) $company->mollie_payment_id)) {
                    $company->update([
                        'mollie_payment_id' => null,
                        'pending_subscription_plan' => null,
                    ]);

                    return redirect()->route('subscription.show')
                        ->with('warning', 'Betaling ontvangen voor een geannuleerd abonnement. Het abonnement blijft opgezegd.');
                }
                $this->finalizePaidPayment($company, $payment);

                return redirect()->route('subscription.show')
                    ->with('success', 'Betaling bevestigd. Je abonnement is nu actief.');
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('subscription.show')
            ->with('success', 'Betaling gestart. Zodra Mollie bevestigt, wordt je abonnement actief.');
    }

    public function mollieWebhook(Request $request)
    {
        $paymentId = trim((string) $request->input('id', ''));
        if ($paymentId === '') {
            return response('missing id', 400);
        }

        try {
            $payment = $this->mollieService->getPayment($paymentId);
            $status = $payment['status'] ?? null;

            $company = Company::where('mollie_payment_id', $paymentId)->first();
            if (!$company) {
                $companyId = (int) data_get($payment, 'metadata.company_id', 0);
                if ($companyId > 0) {
                    $company = Company::find($companyId);
                }
            }

            if (!$company) {
                return response('ok', 200);
            }

            if ($status === 'paid') {
                if ($this->shouldIgnorePaidActivation($company, $paymentId)) {
                    return response('ok', 200);
                }
                $this->finalizePaidPayment($company, $payment);
            }
        } catch (\Throwable $e) {
            $message = strtolower($e->getMessage());
            if (
                str_contains($message, 'wrong mode is used')
                || (str_contains($message, 'mollie api fout (404)') && str_contains($message, 'payment'))
            ) {
                // Ignore stale webhook events from another mode (test/live) so webhook endpoint stays healthy.
                return response('ok', 200);
            }
            report($e);
            return response('error', 500);
        }

        return response('ok', 200);
    }

    private function resolveWebhookUrl(): string
    {
        $configuredWebhook = (string) config('services.mollie.webhook_url');
        if ($configuredWebhook !== '') {
            return $configuredWebhook;
        }

        $defaultWebhook = route('subscription.mollie.webhook');
        $host = strtolower((string) parse_url($defaultWebhook, PHP_URL_HOST));

        if (in_array($host, ['localhost', '127.0.0.1'], true)) {
            throw new RuntimeException(
                'Mollie webhook is lokaal niet bereikbaar. Zet MOLLIE_WEBHOOK_URL in je .env naar een publieke URL (bijv. via ngrok/cloudflared).'
            );
        }

        return $defaultWebhook;
    }

    private function createFirstPaymentWithFallback(
        Company $company,
        array $paymentPayload,
        string $billingEmail,
        bool $requireRecurring = false
    ): array
    {
        $payloadWithoutMethod = $paymentPayload;
        unset($payloadWithoutMethod['method']);

        $attempts = [$paymentPayload, $payloadWithoutMethod];
        $lastException = null;

        foreach ($attempts as $attemptPayload) {
            try {
                return $this->mollieService->createFirstPayment($attemptPayload);
            } catch (RuntimeException $e) {
                $lastException = $e;
                $message = strtolower($e->getMessage());

                $noSuitableMethods =
                    str_contains($message, 'no suitable payment methods found')
                    || str_contains($message, 'does not accept recurring payments');

                if ($noSuitableMethods) {
                    if ($requireRecurring) {
                        throw new RuntimeException(
                            'Recurring betaling kon niet worden gestart. Activeer in Mollie een recurring-geschikte methode (zoals SEPA Incasso) voor dit live-profiel.'
                        );
                    }

                    // Fallback to a one-time iDEAL checkout flow when recurring-first is not accepted
                    // by the current Mollie profile/method setup.
                    $oneTimePayload = $attemptPayload;
                    unset($oneTimePayload['method'], $oneTimePayload['sequenceType'], $oneTimePayload['customerId']);

                    try {
                        return $this->mollieService->createFirstPayment($oneTimePayload);
                    } catch (RuntimeException $oneTimeException) {
                        $lastException = $oneTimeException;
                    }
                }

                $customerModeMismatch =
                    str_contains($message, 'customer')
                    && (
                        str_contains($message, 'not found')
                        || str_contains($message, 'wrong mode')
                        || str_contains($message, 'resource does not exist')
                    );

                if ($customerModeMismatch) {
                    $customer = $this->mollieService->createCustomer($company->name, $billingEmail);
                    $newCustomerId = trim((string) ($customer['id'] ?? ''));
                    if ($newCustomerId === '') {
                        throw new RuntimeException('Kon geen nieuwe Mollie klant aanmaken.');
                    }

                    $company->update(['mollie_customer_id' => $newCustomerId]);

                    // Retry both variants once with the new customer id.
                    $retryWithMethod = $paymentPayload;
                    $retryWithMethod['customerId'] = $newCustomerId;
                    $retryWithoutMethod = $retryWithMethod;
                    unset($retryWithoutMethod['method']);

                    foreach ([$retryWithMethod, $retryWithoutMethod] as $retryPayload) {
                        try {
                            return $this->mollieService->createFirstPayment($retryPayload);
                        } catch (RuntimeException $retryException) {
                            $lastException = $retryException;
                        }
                    }
                }
            }
        }

        throw $lastException ?? new RuntimeException('Mollie checkout kon niet worden gestart.');
    }

    private function finalizePaidPayment(Company $company, array $payment): void
    {
        $plan = $this->resolvePlanFromPayment($company, $payment);
        if (!$plan || !isset(Company::PLANS[$plan])) {
            throw new RuntimeException('Kon abonnement niet activeren: ongeldig plan in betaalmetadata.');
        }

        $company->activateSubscription($plan);

        $updateData = [
            'mollie_payment_id' => null,
            'pending_subscription_plan' => null,
        ];

        if ($company->mollie_customer_id && !$company->mollie_subscription_id) {
            try {
                $billingEmail = (string) optional($company->users()->orderBy('id')->first())->email;
                $fallbackAmount = $this->shouldUseStarterTestOverride($billingEmail, $plan)
                    ? '1.00'
                    : number_format((float) Company::PLANS[$plan]['price_monthly'], 2, '.', '');
                $amountValue = (string) data_get($payment, 'amount.value', $fallbackAmount);
                $interval = (string) data_get($payment, 'metadata.interval', $this->resolveSubscriptionInterval($billingEmail, $plan));

                $subscription = $this->createRecurringSubscription($company, $plan, $amountValue, $interval);

                $updateData['mollie_subscription_id'] = $subscription['id'] ?? null;
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $company->update($updateData);
    }

    private function resolvePlanFromPayment(Company $company, array $payment): ?string
    {
        $candidatePlan = $company->pending_subscription_plan ?: data_get($payment, 'metadata.plan');
        if (is_string($candidatePlan) && isset(Company::PLANS[$candidatePlan])) {
            return $candidatePlan;
        }

        $paidAmount = (string) data_get($payment, 'amount.value', '');
        if ($paidAmount !== '') {
            foreach (Company::PLANS as $planKey => $planConfig) {
                if ($planKey === 'custom') {
                    continue;
                }

                $expectedAmount = number_format((float) ($planConfig['price_monthly'] ?? 0), 2, '.', '');
                if ($expectedAmount === $paidAmount) {
                    return $planKey;
                }
            }
        }

        return null;
    }

    private function syncPendingPaymentStatus(Company $company): void
    {
        // If a subscription is explicitly cancelled and there is no ongoing
        // checkout/plan-change flow, never auto-reactivate from old paid payments.
        if (
            $company->subscription_status === 'cancelled'
            && !$company->mollie_payment_id
            && !$company->pending_subscription_plan
        ) {
            return;
        }

        if ($company->hasActiveSubscription()) {
            $this->ensureRecurringSubscriptionExists($company);
            return;
        }

        if ($company->mollie_payment_id) {
            try {
                $payment = $this->mollieService->getPayment((string) $company->mollie_payment_id);
                $status = $payment['status'] ?? null;

                if ($status === 'paid') {
                    if ($this->shouldIgnorePaidActivation($company, (string) $company->mollie_payment_id)) {
                        $company->update([
                            'mollie_payment_id' => null,
                            'pending_subscription_plan' => null,
                        ]);
                        return;
                    }
                    $this->finalizePaidPayment($company, $payment);
                    return;
                }

                if (in_array($status, ['failed', 'canceled', 'expired'], true)) {
                    $company->update([
                        'mollie_payment_id' => null,
                        'pending_subscription_plan' => null,
                    ]);
                }
            } catch (\Throwable $e) {
                $message = strtolower($e->getMessage());
                if (
                    str_contains($message, 'wrong mode is used')
                    || (str_contains($message, 'mollie api fout (404)') && str_contains($message, 'payment'))
                ) {
                    // Existing payment was created with another API mode (test/live).
                    // Clear stale references so a fresh checkout can be started.
                    $company->update([
                        'mollie_payment_id' => null,
                        'pending_subscription_plan' => null,
                    ]);
                }
                report($e);
            }
        }

        if (!$company->mollie_customer_id) {
            return;
        }

        // Only reconcile historical customer payments when we are in an
        // explicit activation flow (pending plan or payment id present).
        if (!$company->mollie_payment_id && !$company->pending_subscription_plan) {
            return;
        }

        try {
            $payments = $this->mollieService->getRecentCustomerPayments($company->mollie_customer_id, 10);
            $paidPayment = collect($payments)->first(function (array $payment) {
                return ($payment['status'] ?? null) === 'paid';
            });

            if (!$paidPayment) {
                return;
            }

            $paidPaymentId = trim((string) ($paidPayment['id'] ?? ''));
            if ($this->shouldIgnorePaidActivation($company, $paidPaymentId)) {
                $company->update([
                    'mollie_payment_id' => null,
                    'pending_subscription_plan' => null,
                ]);
                return;
            }

            $this->finalizePaidPayment($company, $paidPayment);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function shouldIgnorePaidActivation(Company $company, string $paymentId): bool
    {
        if ($company->subscription_status !== 'cancelled') {
            return false;
        }

        // A paid webhook for an old/parallel payment should never reactivate
        // a company that explicitly cancelled and has no active checkout flow.
        if ($company->pending_subscription_plan) {
            return false;
        }

        if (!$company->mollie_payment_id) {
            return true;
        }

        return trim((string) $company->mollie_payment_id) !== trim($paymentId);
    }

    private function shouldUseStarterTestOverride(string $email, string $plan): bool
    {
        return strtolower(trim($email)) === 'anwar@brancom.nl' && $plan === 'starter';
    }

    private function resolveSubscriptionInterval(string $email, string $plan): string
    {
        return $this->shouldUseStarterTestOverride($email, $plan) ? '1 day' : '1 month';
    }

    private function createRecurringSubscription(Company $company, string $plan, string $amountValue, string $interval): array
    {
        return $this->mollieService->createSubscription((string) $company->mollie_customer_id, [
            'amount' => [
                'currency' => 'EUR',
                'value' => $amountValue,
            ],
            'interval' => $interval,
            'description' => "TaskCheck {$plan} abonnement",
            'webhookUrl' => $this->resolveWebhookUrl(),
            'metadata' => [
                'company_id' => $company->id,
                'plan' => $plan,
                'interval' => $interval,
            ],
        ]);
    }

    private function ensureRecurringSubscriptionExists(Company $company): void
    {
        if (!$company->hasActiveSubscription() || $company->mollie_subscription_id || !$company->mollie_customer_id) {
            return;
        }

        $plan = (string) $company->subscription_plan;
        if (!isset(Company::PLANS[$plan])) {
            return;
        }

        try {
            $billingEmail = (string) optional($company->users()->orderBy('id')->first())->email;
            $amountValue = $this->shouldUseStarterTestOverride($billingEmail, $plan)
                ? '1.00'
                : number_format((float) Company::PLANS[$plan]['price_monthly'], 2, '.', '');
            $interval = $this->resolveSubscriptionInterval($billingEmail, $plan);

            $subscription = $this->createRecurringSubscription($company, $plan, $amountValue, $interval);
            $subscriptionId = trim((string) ($subscription['id'] ?? ''));

            if ($subscriptionId !== '') {
                $company->update(['mollie_subscription_id' => $subscriptionId]);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Billing\Invoice;
use App\Models\Organisation\Company;
use App\Services\Billing\InvoiceService;
use App\Services\Billing\MollieService;
use App\Services\Billing\RecurringSubscriptionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    private const VAT_RATE = 21.00;

    public function __construct(
        private readonly MollieService $mollieService,
        private readonly InvoiceService $invoiceService,
        private readonly RecurringSubscriptionService $recurringSubscriptionService,
    ) {}

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
            'plans' => Company::publicPlans(),
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

        if (! $company) {
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

        if ($company->pending_subscription_plan && Company::plan($company->pending_subscription_plan)) {
            $pendingPlanDetails = Company::plan($company->pending_subscription_plan);
        }

        $planDetails = array_replace([
            'name' => $company->getPlanDisplayName(),
            'price_monthly' => 0,
        ], $company->getPlanDetails());

        return view('subscription.show', [
            'company' => $company,
            'planDetails' => $planDetails,
            'nextBillingDate' => $nextBillingDate,
            'daysUntilNextBilling' => $daysUntilNextBilling,
            'pendingPlanDetails' => $pendingPlanDetails,
            'invoices' => $company->invoices()->latest('paid_at')->limit(20)->get(),
        ]);
    }

    public function activate(Request $request): RedirectResponse
    {
        $request->validate([
            'plan' => ['required', Rule::in(array_keys(Company::plans()))],
        ]);

        $company = Auth::user()->company;

        if (! $company) {
            return redirect()->route('subscription.choose-plan')
                ->with('error', 'Organisatie niet gevonden.');
        }

        $plan = $request->plan === $company->subscription_plan
            ? $company->getPlanDetails()
            : Company::plan($request->plan);
        $billingEmail = (string) Auth::user()->email;
        $isStarterTestOverride = $this->shouldUseStarterTestOverride($billingEmail, (string) $request->plan);
        $amountValue = $isStarterTestOverride
            ? '1.00'
            : $this->calculateGrossAmount((float) $plan['billing_amount']);
        $subscriptionInterval = $this->resolveSubscriptionInterval($billingEmail, (string) $request->plan);
        if ($company->isManagedAccount() && $request->plan === $company->subscription_plan) {
            $subscriptionInterval = Company::billingPeriod($company->billing_period ?: 'monthly')['mollie_interval'];
        }

        try {
            $webhookUrl = $this->resolveWebhookUrl();

            if ($company->hasActiveSubscription()) {
                if ($company->subscription_plan === $request->plan && ! $company->pending_subscription_plan) {
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

            if (! $company->mollie_customer_id) {
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

            if (! $checkoutUrl || $paymentId === '') {
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

        if (! $company) {
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
                } catch (\Throwable $e) {
                    report($e); // Log instead of silently ignoring — important for debugging billing issues.
                }

                try {
                    $this->mollieService->cancelSubscription(
                        $company->mollie_customer_id,
                        $company->mollie_subscription_id
                    );
                } catch (\Throwable $e) {
                    // Log the failure — if the primary cancel fails we still attempt the full cleanup below.
                    report($e);
                }
            }

            // Defensive cleanup: cancel ALL active/pending subscriptions for this customer.
            // This is critical to catch duplicate subscriptions created by race conditions.
            if ($company->mollie_customer_id) {
                try {
                    $subscriptions = $this->mollieService->getCustomerSubscriptions((string) $company->mollie_customer_id);
                    foreach ($subscriptions as $subscription) {
                        $subscriptionId = trim((string) ($subscription['id'] ?? ''));
                        $status = strtolower(trim((string) ($subscription['status'] ?? '')));
                        if ($subscriptionId === '' || in_array($status, ['canceled', 'completed'], true)) {
                            continue;
                        }

                        try {
                            $this->mollieService->cancelSubscription((string) $company->mollie_customer_id, $subscriptionId);
                        } catch (\Throwable $e) {
                            report($e); // Log every failed cancel so we can audit missed subscriptions.
                        }
                    }
                } catch (\Throwable $e) {
                    report($e);
                }

                // Cancel any open/pending payments to prevent additional debits after cancellation.
                try {
                    $payments = $this->mollieService->getRecentCustomerPayments((string) $company->mollie_customer_id, 50);
                    foreach ($payments as $payment) {
                        $openPaymentId = trim((string) ($payment['id'] ?? ''));
                        $status = strtolower(trim((string) ($payment['status'] ?? '')));

                        if ($openPaymentId === '' || ! in_array($status, ['open', 'pending', 'authorized'], true)) {
                            continue;
                        }

                        try {
                            $this->mollieService->cancelPayment($openPaymentId);
                        } catch (\Throwable $e) {
                            report($e); // Some payment methods cannot be cancelled via API — log it.
                        }
                    }
                } catch (\Throwable $e) {
                    report($e);
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
            report($e);

            return redirect()->route('subscription.show')
                ->with('error', 'Opzeggen via Mollie is mislukt: '.$e->getMessage());
        }

        return redirect()->route('subscription.show')
            ->with('success', 'Je abonnement is opgezegd. Het blijft actief tot het einde van de factureringsperiode.');
    }

    public function paymentReturn(): RedirectResponse
    {
        $company = Auth::user()->company;
        if (! $company) {
            return redirect()->route('subscription.choose-plan');
        }

        $this->syncPendingPaymentStatus($company);
        $company->refresh();

        if ($company->hasActiveSubscription()) {
            return redirect()->route('subscription.show')
                ->with('success', 'Betaling bevestigd. Je abonnement is nu actief.');
        }

        if (! $company->mollie_payment_id) {
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
                $this->invoiceService->sendReceipt($company, $payment);
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
            if (! $company) {
                $companyId = (int) data_get($payment, 'metadata.company_id', 0);
                if ($companyId > 0) {
                    $company = Company::find($companyId);
                }
            }

            if (! $company) {
                return response('ok', 200);
            }

            if ($status === 'paid') {
                if ($this->shouldIgnorePaidActivation($company, $paymentId)) {
                    return response('ok', 200);
                }
                $this->invoiceService->sendReceipt($company, $payment);
                if (! $this->isActivationPayment($company, $paymentId, $payment)) {
                    // Recurring charge for already-active subscription: extend the access window.
                    $this->recurringSubscriptionService->extendPaidAccess($company);

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

    public function downloadInvoice(Invoice $invoice): Response
    {
        $user = Auth::user();
        if (
            ! $user
            || (! $user->isSuperAdmin() && (int) $user->company_id !== (int) $invoice->company_id)
        ) {
            abort(403);
        }

        $pdf = $this->invoiceService->renderPdf($invoice);

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="'.$invoice->invoice_number.'.pdf"');
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
    ): array {
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
        $paymentId = trim((string) ($payment['id'] ?? ''));

        // Prevent double activation when webhook and paymentReturn fire simultaneously
        // for the same Mollie payment (race condition → duplicate subscriptions).
        $lock = Cache::lock("finalize_payment:{$company->id}:{$paymentId}", 60);
        if (! $lock->get()) {
            return;
        }

        try {
            // Re-fetch a fresh company state inside the lock to avoid acting on stale data.
            $company->refresh();

            // If the company was already activated for this payment, skip.
            if ($company->hasActiveSubscription() && ! $company->pending_subscription_plan && ! $company->mollie_payment_id) {
                return;
            }

            $plan = $this->resolvePlanFromPayment($company, $payment);
            if (! $plan || ! Company::plan($plan)) {
                throw new RuntimeException('Kon abonnement niet activeren: ongeldig plan in betaalmetadata.');
            }

            $interval = (string) data_get($payment, 'metadata.interval', $this->resolveSubscriptionInterval('', $plan));
            $firstPaidAt = filled($payment['paidAt'] ?? null) ? Carbon::parse($payment['paidAt']) : now();
            $company->activateSubscription($plan, $this->intervalMonths($interval));

            $updateData = [
                'mollie_payment_id' => null,
                'pending_subscription_plan' => null,
                'billing_period' => $this->billingPeriodFromInterval($interval),
                'billing_start_date' => $firstPaidAt->toDateString(),
            ];

            if ($company->mollie_customer_id && ! $company->mollie_subscription_id) {
                try {
                    $billingEmail = (string) optional($company->users()->orderBy('id')->first())->email;
                    $fallbackAmount = $this->shouldUseStarterTestOverride($billingEmail, $plan)
                        ? '1.00'
                        : $this->calculateGrossAmount((float) Company::plan($plan)['billing_amount']);
                    $amountValue = (string) data_get($payment, 'amount.value', $fallbackAmount);
                    $interval = (string) data_get($payment, 'metadata.interval', $this->resolveSubscriptionInterval($billingEmail, $plan));
                    $nextChargeDate = $this->addInterval($firstPaidAt->copy()->startOfDay(), $interval)->toDateString();

                    $subscription = $this->createRecurringSubscription($company, $plan, $amountValue, $interval, $nextChargeDate);

                    $updateData['mollie_subscription_id'] = $subscription['id'] ?? null;
                } catch (\Throwable $e) {
                    report($e);
                }
            }

            $company->update($updateData);
        } finally {
            $lock->release();
        }
    }

    private function resolvePlanFromPayment(Company $company, array $payment): ?string
    {
        $candidatePlan = $company->pending_subscription_plan ?: data_get($payment, 'metadata.plan');
        if (is_string($candidatePlan) && Company::plan($candidatePlan)) {
            return $candidatePlan;
        }

        $paidAmount = (string) data_get($payment, 'amount.value', '');
        if ($paidAmount !== '') {
            foreach (Company::plans() as $planKey => $planConfig) {
                if ($planKey === 'custom') {
                    continue;
                }

                $expectedAmount = $this->calculateGrossAmount((float) ($planConfig['billing_amount'] ?? 0));
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
            && ! $company->mollie_payment_id
            && ! $company->pending_subscription_plan
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

                // Payment is still in progress — stop here and wait for the webhook.
                // Do NOT scan historical payments: an old paid payment would otherwise
                // incorrectly reactivate a subscription for a checkout that was never completed.
                if (in_array($status, ['open', 'pending', 'authorized'], true)) {
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

                // On API error: don't fall through to the historical scan — too risky.
                return;
            }
        }

        if (! $company->mollie_customer_id) {
            return;
        }

        // Only reconcile historical customer payments when we are in an
        // explicit activation flow (pending plan or payment id present).
        if (! $company->mollie_payment_id && ! $company->pending_subscription_plan) {
            return;
        }

        try {
            $payments = $this->mollieService->getRecentCustomerPayments($company->mollie_customer_id, 10);
            $paidPayment = collect($payments)->first(function (array $payment) {
                return ($payment['status'] ?? null) === 'paid';
            });

            if (! $paidPayment) {
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

        // There is an active checkout in progress: only allow activation from
        // the exact payment that was created for this checkout, not from any
        // older historical paid payments still in the customer's Mollie history.
        if ($company->pending_subscription_plan || $company->mollie_payment_id) {
            $storedId = trim((string) $company->mollie_payment_id);

            // If this payment is the one we created for the current checkout, allow it.
            if ($storedId !== '' && $storedId === trim($paymentId)) {
                return false;
            }

            // Any other "paid" payment found in the customer history is a historical
            // payment and must never reactivate a new cancelled+pending checkout.
            return true;
        }

        // No active checkout: block all activation for cancelled companies.
        return true;
    }

    /**
     * Test accounts: €1,00 per day instead of the real monthly price.
     * Add an email here to enable test-mode billing for that account.
     */
    private const TEST_BILLING_EMAILS = [
        'anwar@brancom.nl',
    ];

    private function shouldUseStarterTestOverride(string $email, string $plan): bool
    {
        return in_array(strtolower(trim($email)), self::TEST_BILLING_EMAILS, true);
    }

    private function resolveSubscriptionInterval(string $email, string $plan): string
    {
        if ($this->shouldUseStarterTestOverride($email, $plan)) {
            return '1 day';
        }

        $period = (string) (Company::plan($plan)['billing_period'] ?? 'monthly');

        return Company::billingPeriod($period)['mollie_interval'];
    }

    private function calculateGrossAmount(float $basePrice): string
    {
        $gross = $basePrice * (1 + (self::VAT_RATE / 100));

        return number_format($gross, 2, '.', '');
    }

    private function billingPeriodFromInterval(string $interval): string
    {
        return match ($interval) {
            '3 months' => 'quarterly',
            '6 months' => 'semiannual',
            '12 months' => 'annual',
            default => 'monthly',
        };
    }

    private function intervalMonths(string $interval): int
    {
        return match ($interval) {
            '3 months' => 3,
            '6 months' => 6,
            '12 months' => 12,
            default => 1,
        };
    }

    private function addInterval(Carbon $date, string $interval): Carbon
    {
        return match ($interval) {
            '1 day' => $date->addDay(),
            '3 months' => $date->addMonthsNoOverflow(3),
            '6 months' => $date->addMonthsNoOverflow(6),
            '12 months' => $date->addYearNoOverflow(),
            default => $date->addMonthNoOverflow(),
        };
    }

    private function createRecurringSubscription(Company $company, string $plan, string $amountValue, string $interval, ?string $startDate = null): array
    {
        $existingSubscriptionId = $this->findExistingReusableSubscriptionId($company);
        if ($existingSubscriptionId !== null) {
            return ['id' => $existingSubscriptionId];
        }

        $payload = [
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
        ];
        if ($startDate) {
            $payload['startDate'] = $startDate;
        }

        return $this->mollieService->createSubscription((string) $company->mollie_customer_id, $payload);
    }

    private function findExistingReusableSubscriptionId(Company $company): ?string
    {
        if (! $company->mollie_customer_id) {
            return null;
        }

        try {
            $subscriptions = $this->mollieService->getCustomerSubscriptions((string) $company->mollie_customer_id);
            foreach ($subscriptions as $subscription) {
                $subscriptionId = trim((string) ($subscription['id'] ?? ''));
                $status = strtolower(trim((string) ($subscription['status'] ?? '')));
                if ($subscriptionId === '') {
                    continue;
                }

                if (in_array($status, ['active', 'pending', 'suspended'], true)) {
                    return $subscriptionId;
                }
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return null;
    }

    private function isActivationPayment(Company $company, string $paymentId, array $payment): bool
    {
        $storedPaymentId = trim((string) $company->mollie_payment_id);
        if ($storedPaymentId !== '' && $storedPaymentId === trim($paymentId)) {
            return true;
        }

        if ($company->pending_subscription_plan) {
            return true;
        }

        if (! $company->hasActiveSubscription() && strtolower((string) data_get($payment, 'sequenceType', '')) === 'first') {
            return true;
        }

        // Recurring charges of already active subscriptions should not
        // re-run activation logic.
        return false;
    }

    private function ensureRecurringSubscriptionExists(Company $company): void
    {
        if (! $company->hasActiveSubscription() || $company->mollie_subscription_id || ! $company->mollie_customer_id) {
            return;
        }

        $plan = (string) $company->subscription_plan;
        if (! Company::plan($plan)) {
            return;
        }

        try {
            $billingEmail = (string) optional($company->users()->orderBy('id')->first())->email;
            $amountValue = $this->shouldUseStarterTestOverride($billingEmail, $plan)
                ? '1.00'
                : $this->calculateGrossAmount((float) Company::plan($plan)['billing_amount']);
            $interval = $this->resolveSubscriptionInterval($billingEmail, $plan);

            $nextChargeDate = $this->addInterval(now()->startOfDay(), $interval)->toDateString();
            $subscription = $this->createRecurringSubscription($company, $plan, $amountValue, $interval, $nextChargeDate);
            $subscriptionId = trim((string) ($subscription['id'] ?? ''));

            if ($subscriptionId !== '') {
                $company->update(['mollie_subscription_id' => $subscriptionId]);
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }

    /**
     * Send a billing receipt/invoice e-mail once per paid payment.
     */
}

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

        return view('subscription.show', [
            'company' => $company,
            'planDetails' => $company->getPlanDetails(),
            'nextBillingDate' => $nextBillingDate,
            'daysUntilNextBilling' => $daysUntilNextBilling,
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
        $amountValue = number_format((float) $plan['price_monthly'], 2, '.', '');

        try {
            $webhookUrl = $this->resolveWebhookUrl();

            if (!$company->mollie_customer_id) {
                $customer = $this->mollieService->createCustomer(
                    $company->name,
                    Auth::user()->email
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
                    'interval' => '1 month',
                ],
            ];

            try {
                $payment = $this->mollieService->createFirstPayment($paymentPayload);
            } catch (RuntimeException $e) {
                $message = strtolower($e->getMessage());
                $recurringMethodError =
                    str_contains($message, 'does not accept recurring payments')
                    || str_contains($message, 'does not support recurring');

                if (!$recurringMethodError) {
                    throw $e;
                }

                // Fallback: let Mollie choose an allowed method for recurring setup.
                unset($paymentPayload['method']);
                $payment = $this->mollieService->createFirstPayment($paymentPayload);
            }

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
            if ($company->mollie_customer_id && $company->mollie_subscription_id) {
                $this->mollieService->cancelSubscription(
                    $company->mollie_customer_id,
                    $company->mollie_subscription_id
                );
            }

            $company->update([
                'subscription_status' => 'cancelled',
                'mollie_subscription_id' => null,
                'subscription_ends_at' => now(),
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
                $this->finalizePaidPayment($company, $payment);
            }
        } catch (\Throwable $e) {
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
                $subscription = $this->mollieService->createSubscription($company->mollie_customer_id, [
                    'amount' => [
                        'currency' => 'EUR',
                        'value' => number_format((float) Company::PLANS[$plan]['price_monthly'], 2, '.', ''),
                    ],
                    'interval' => '1 month',
                    'description' => "TaskCheck {$plan} abonnement",
                    'webhookUrl' => $this->resolveWebhookUrl(),
                    'metadata' => [
                        'company_id' => $company->id,
                        'plan' => $plan,
                    ],
                ]);

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
        if ($company->hasActiveSubscription()) {
            return;
        }

        if ($company->mollie_payment_id) {
            try {
                $payment = $this->mollieService->getPayment((string) $company->mollie_payment_id);
                $status = $payment['status'] ?? null;

                if ($status === 'paid') {
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

        try {
            $payments = $this->mollieService->getRecentCustomerPayments($company->mollie_customer_id, 10);
            $paidPayment = collect($payments)->first(function (array $payment) {
                return ($payment['status'] ?? null) === 'paid';
            });

            if (!$paidPayment) {
                return;
            }

            $this->finalizePaidPayment($company, $paidPayment);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}


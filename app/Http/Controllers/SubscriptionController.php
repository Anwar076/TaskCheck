<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\Billing\MollieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        return view('subscription.show', [
            'company' => $company,
            'planDetails' => $company->getPlanDetails(),
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

            $payment = $this->mollieService->createFirstPayment([
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
            ]);

            $checkoutUrl = data_get($payment, '_links.checkout.href');
            $paymentId = $payment['id'] ?? null;

            if (!$checkoutUrl || !$paymentId) {
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

        if (!$company->mollie_payment_id) {
            if ($company->hasActiveSubscription()) {
                return redirect()->route('subscription.show')
                    ->with('success', 'Je betaling is bevestigd. Je abonnement is actief.');
            }

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
        $paymentId = (string) $request->input('id', '');
        if ($paymentId === '') {
            return response('missing id', 400);
        }

        $company = Company::where('mollie_payment_id', $paymentId)->first();
        if (!$company) {
            return response('ok', 200);
        }

        try {
            $payment = $this->mollieService->getPayment($paymentId);
            $status = $payment['status'] ?? null;

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
        $plan = $company->pending_subscription_plan ?: data_get($payment, 'metadata.plan');
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
}


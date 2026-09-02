<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Billing\SubscriptionPlan;
use App\Models\Organisation\Company;
use App\Services\Billing\MollieService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CompanySubscriptionController extends Controller
{
    public function subscriptions()
    {
        $counts = Company::query()
            ->selectRaw('subscription_plan, COUNT(*) as company_count')
            ->groupBy('subscription_plan')
            ->pluck('company_count', 'subscription_plan');

        $plans = collect(Company::plans())->map(function (array $plan, string $key) use ($counts) {
            return array_merge($plan, [
                'key' => $key,
                'company_count' => (int) ($counts[$key] ?? 0),
            ]);
        });

        return view('super-admin.subscriptions.index', compact('plans'));
    }

    public function showSubscription(string $plan)
    {
        abort_unless(Company::plan($plan), 404);

        $companies = Company::query()
            ->where('subscription_plan', $plan)
            ->with(['users' => fn ($query) => $query->with('company')->orderBy('name')])
            ->withCount(['users', 'locations'])
            ->orderBy('name')
            ->get();

        return view('super-admin.subscriptions.show', [
            'planKey' => $plan,
            'plan' => Company::plan($plan),
            'companies' => $companies,
        ]);
    }

    public function createSubscriptionPlan()
    {
        return view('super-admin.subscriptions.create');
    }

    public function storeSubscriptionPlan(Request $request): RedirectResponse
    {
        $validated = $this->normalizeSubscriptionPrice($this->validateSubscriptionPlan($request));
        $baseKey = Str::slug($validated['name'], '_') ?: 'abonnement';
        $key = $baseKey;
        $suffix = 2;

        while (array_key_exists($key, Company::plans())) {
            $key = $baseKey.'_'.$suffix++;
        }

        SubscriptionPlan::query()->create(array_merge($validated, [
            'plan_key' => $key,
            'is_public' => false,
            'features' => $validated['features'] ?? [],
        ]));

        return redirect()->route('super-admin.subscriptions.show', $key)->with('success', 'Nieuw abonnement is aangemaakt.');
    }

    public function updateSubscriptionPlan(Request $request, string $plan): RedirectResponse
    {
        abort_unless(Company::plan($plan), 404);
        abort_if($plan === 'custom', 422, 'Maatwerkabonnementen worden per klant beheerd.');

        $validated = $this->normalizeSubscriptionPrice($this->validateSubscriptionPlan($request));

        SubscriptionPlan::query()->updateOrCreate(['plan_key' => $plan], $validated);

        Company::query()->where('subscription_plan', $plan)->where('subscription_plan', '!=', 'custom')->update([
            'max_users' => $validated['max_users'],
            'max_locations' => $validated['max_locations'],
            'max_storage_gb' => $validated['max_storage_gb'],
        ]);

        return redirect()->route('super-admin.subscriptions.show', $plan)->with('success', 'Abonnement is bijgewerkt.');
    }

    private function validateSubscriptionPlan(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'billing_period' => ['required', Rule::in(array_keys(Company::BILLING_PERIODS))],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'trial_duration_value' => ['required', 'integer', 'min:1', 'max:365'],
            'trial_duration_unit' => ['required', Rule::in(['days', 'weeks', 'months'])],
            'max_users' => ['required', 'integer', 'min:-1'],
            'max_locations' => ['required', 'integer', 'min:-1'],
            'max_storage_gb' => ['required', 'integer', 'min:-1'],
            'features' => ['nullable', 'array'],
            'features.*' => ['string', Rule::in(array_keys(Company::PLAN_FEATURES))],
        ]);

        $validated['features'] = $validated['features'] ?? [];

        return $validated;
    }

    private function normalizeSubscriptionPrice(array $validated): array
    {
        $period = $validated['billing_period'];
        $price = $validated['price'];
        unset($validated['billing_period'], $validated['price']);

        $validated['billing_period'] = $period;
        $validated['billing_amount'] = $price;
        $validated['price_monthly'] = $period === 'monthly' ? $price : 0;
        $validated['price_annual'] = $period === 'annual' ? $price : 0;

        return $validated;
    }

    public function updateCompanySubscription(Request $request, Company $company, MollieService $mollieService): RedirectResponse
    {
        $validated = $request->validate([
            'subscription_plan' => ['required', Rule::in(array_keys(Company::plans()))],
            'subscription_status' => ['required', Rule::in(['trial', 'active', 'cancelled', 'expired'])],
            'billing_required' => ['nullable', 'boolean'],
            'billing_period' => ['required', Rule::in(array_keys(Company::BILLING_PERIODS))],
            'billing_start_date' => ['nullable', 'date'],
            'trial_ends_at' => ['nullable', 'date'],
            'subscription_ends_at' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'custom_subscription_name' => ['nullable', 'required_if:subscription_plan,custom', 'string', 'max:100'],
            'custom_monthly_price' => ['nullable', 'required_if:subscription_plan,custom', 'numeric', 'min:0', 'max:999999.99'],
            'custom_max_users' => ['nullable', 'required_if:subscription_plan,custom', 'integer', 'min:-1'],
            'custom_max_locations' => ['nullable', 'required_if:subscription_plan,custom', 'integer', 'min:-1'],
            'custom_max_storage_gb' => ['nullable', 'required_if:subscription_plan,custom', 'integer', 'min:-1'],
        ]);

        $billingRequired = (bool) ($validated['billing_required'] ?? false);
        $plan = $validated['subscription_plan'];
        $planConfig = Company::plan($plan) ?? Company::plan('starter');
        $endDate = ! empty($validated['subscription_ends_at'])
            ? Carbon::parse($validated['subscription_ends_at'])->endOfDay()
            : null;
        $trialEndsAt = ! empty($validated['trial_ends_at']) ? Carbon::parse($validated['trial_ends_at'])->endOfDay() : null;
        $billingStartDate = ! empty($validated['billing_start_date'])
            ? Carbon::parse($validated['billing_start_date'])->startOfDay()
            : $trialEndsAt?->copy()->startOfDay();
        $subscriptionStatus = $validated['subscription_status'];

        if (! $billingRequired && $endDate) {
            $subscriptionStatus = $endDate->isPast() ? 'expired' : 'active';
            $billingStartDate = null;
        }

        if ($billingRequired
            && $subscriptionStatus === 'active'
            && $trialEndsAt?->isFuture()
            && ! $company->mollie_subscription_id
            && ! $endDate) {
            $subscriptionStatus = 'trial';
        }

        if (! $billingRequired && ! $endDate && $validated['subscription_status'] === 'active') {
            return redirect()->back()->withErrors([
                'subscription_ends_at' => 'Bij gratis toegang is een einddatum verplicht voor actieve status.',
            ])->withInput();
        }

        $isCustom = $plan === 'custom';
        $mollieSubscriptionId = $company->mollie_subscription_id;
        if ($company->mollie_customer_id && $company->mollie_subscription_id) {
            try {
                if (! $billingRequired || in_array($subscriptionStatus, ['cancelled', 'expired'], true)) {
                    $mollieService->cancelSubscription((string) $company->mollie_customer_id, (string) $company->mollie_subscription_id);
                    $mollieSubscriptionId = null;
                } else {
                    $netAmount = (float) ($isCustom ? $validated['custom_monthly_price'] : ($planConfig['billing_amount'] ?? $planConfig['price_monthly'] ?? 0));
                    $molliePayload = [
                        'amount' => ['currency' => 'EUR', 'value' => number_format($netAmount * 1.21, 2, '.', '')],
                        'interval' => Company::billingPeriod($validated['billing_period'])['mollie_interval'],
                        'description' => 'TaskCheck '.($isCustom ? $validated['custom_subscription_name'] : ($planConfig['name'] ?? $plan)).' abonnement',
                        'metadata' => ['company_id' => $company->id, 'plan' => $plan, 'interval' => Company::billingPeriod($validated['billing_period'])['mollie_interval']],
                    ];
                    if ($billingStartDate && $billingStartDate->isFuture()) {
                        $molliePayload['startDate'] = $billingStartDate->toDateString();
                    }
                    $mollieService->updateSubscription((string) $company->mollie_customer_id, (string) $company->mollie_subscription_id, $molliePayload);
                }
            } catch (\Throwable $exception) {
                report($exception);

                return back()->withInput()->with('error', 'De wijzigingen zijn niet opgeslagen, omdat Mollie het abonnement niet kon bijwerken: '.$exception->getMessage());
            }
        }

        $company->update([
            'subscription_plan' => $plan,
            'custom_subscription_name' => $isCustom ? $validated['custom_subscription_name'] : null,
            'custom_monthly_price' => $isCustom ? $validated['custom_monthly_price'] : null,
            'subscription_status' => $subscriptionStatus,
            'billing_required' => $billingRequired,
            'billing_period' => $validated['billing_period'],
            'billing_start_date' => $billingStartDate,
            'trial_ends_at' => $trialEndsAt,
            'subscription_ends_at' => $endDate,
            'mollie_subscription_id' => $mollieSubscriptionId,
            'is_active' => (bool) ($validated['is_active'] ?? true),
            'max_users' => $isCustom ? $validated['custom_max_users'] : ($planConfig['max_users'] ?? $company->max_users),
            'max_locations' => $isCustom ? $validated['custom_max_locations'] : ($planConfig['max_locations'] ?? $company->max_locations),
            'max_storage_gb' => $isCustom ? $validated['custom_max_storage_gb'] : ($planConfig['max_storage_gb'] ?? $company->max_storage_gb),
        ]);

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'billing'])
            ->with('success', "Abonnement van {$company->name} is bijgewerkt.");
    }
}

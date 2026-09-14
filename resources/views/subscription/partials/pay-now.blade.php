@php
    $payPlan = $company->getPlanDetails();
    $payName = $company->getPlanDisplayName();
    $payNet = (float) ($payPlan['billing_amount'] ?? $payPlan['price_monthly'] ?? 0);
    $payPeriod = \App\Models\Organisation\Company::billingPeriod($company->billing_period ?: ($payPlan['billing_period'] ?? 'monthly'));
@endphp
<div class="rounded-2xl border border-blue-200 bg-blue-50/80 p-5 sm:p-6">
    <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Jouw abonnement</p>
    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $payName }}</p>
    @if($payNet > 0)
        <p class="mt-1 text-slate-600">
            €{{ number_format($payNet, 2, ',', '.') }} excl. btw
            <span class="text-slate-400">/ {{ $payPeriod['suffix'] }}</span>
            <span class="block text-sm text-slate-500 mt-0.5">€{{ number_format($payNet * 1.21, 2, ',', '.') }} incl. 21% btw bij afrekenen</span>
        </p>
    @endif
    <p class="mt-3 text-sm text-slate-600">Dit plan is door TaskCheck voor jullie ingesteld. Je hoeft geen Starter of Professional te kiezen — betaal hieronder dit abonnement. Daarna lopen de volgende incasso’s automatisch.</p>
    <form method="POST" action="{{ url('/subscription/activate') }}" class="mt-4" id="pay-now">
        @csrf
        <input type="hidden" name="plan" value="{{ $company->subscription_plan }}">
        @error('plan')
            <p class="mb-3 text-sm font-medium text-red-700">{{ $message }}</p>
        @enderror
        <button type="submit" class="inline-flex min-h-[44px] w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-lg transition-colors hover:bg-blue-700 sm:w-auto">
            Betaal je abonnement nu
        </button>
    </form>
</div>

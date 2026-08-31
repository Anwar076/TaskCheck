@extends('layouts.super-admin')

@section('page-title', $plan['name'])

@section('breadcrumbs')
    <span class="text-slate-400">/</span><a href="{{ route('super-admin.subscriptions.index') }}" class="font-medium text-slate-500 hover:text-blue-700">Abonnementen</a><span class="text-slate-400">/</span><span class="font-semibold text-slate-900">{{ $plan['name'] }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <section class="rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white shadow-sm">
        <a href="{{ route('super-admin.subscriptions.index') }}" class="text-sm font-semibold text-blue-100 hover:text-white">← Alle abonnementen</a>
        <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"><div><p class="text-sm text-blue-100">Abonnement</p><h1 class="mt-1 text-3xl font-bold">{{ $plan['name'] }}</h1><p class="mt-2 text-blue-100">{{ $planKey === 'custom' ? 'Voorwaarden worden per klant ingesteld.' : '€ '.number_format($plan['price_monthly'], 2, ',', '.').' per maand, excl. btw' }}</p></div><span class="rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold ring-1 ring-white/20">{{ $companies->count() }} {{ $companies->count() === 1 ? 'klant' : 'klanten' }}</span></div>
    </section>

    <section class="grid gap-4 sm:grid-cols-3">
        @foreach([['Gebruikers', $plan['max_users']], ['Locaties', $plan['max_locations']], ['Opslag (GB)', $plan['max_storage_gb']]] as [$label, $value])
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"><p class="text-sm text-slate-500">{{ $label }}</p><p class="mt-2 text-2xl font-bold text-slate-900">{{ $value === -1 ? 'Onbeperkt' : $value }}</p></div>
        @endforeach
    </section>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4"><h2 class="font-semibold text-slate-900">Gekoppelde klanten</h2><p class="mt-1 text-xs text-slate-500">Open een klant om het abonnement te wijzigen.</p></div>
        <div class="divide-y divide-slate-100">
            @forelse($companies as $company)
                @php $details = $company->getPlanDetails(); @endphp
                <a href="{{ route('super-admin.companies.show', ['company' => $company, 'section' => 'billing']) }}" class="flex flex-col gap-3 px-5 py-4 hover:bg-slate-50 sm:flex-row sm:items-center">
                    <div class="min-w-0 flex-1"><p class="truncate font-semibold text-slate-900">{{ $company->name }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $details['name'] ?? $plan['name'] }} · {{ $company->users_count }} gebruikers · {{ $company->locations_count }} locaties</p></div>
                    @if($planKey === 'custom')<div class="text-sm font-semibold text-slate-800">€ {{ number_format((float) $company->custom_monthly_price, 2, ',', '.') }} / maand</div>@endif
                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $company->subscription_status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ ucfirst($company->subscription_status) }}</span>
                    <span class="text-sm font-semibold text-blue-700">Bekijken →</span>
                </a>
            @empty
                <div class="px-6 py-12 text-center text-sm text-slate-500">Er zijn nog geen klanten aan dit abonnement gekoppeld.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection

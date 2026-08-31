@extends('layouts.super-admin')

@section('page-title', 'Abonnementen')

@section('breadcrumbs')
    <span class="text-slate-400">/</span>
    <span class="font-semibold text-slate-900">Abonnementen</span>
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
        <div><p class="text-sm font-semibold text-blue-600">Platformbeheer</p><h1 class="mt-1 text-2xl font-bold text-slate-900">Abonnementen</h1><p class="mt-1 text-sm text-slate-500">Bekijk de voorwaarden en klanten per abonnement.</p></div>
    </div>

    <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        @foreach($plans as $plan)
            <a href="{{ route('super-admin.subscriptions.show', $plan['key']) }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-300 hover:shadow-md">
                <div class="flex items-start justify-between gap-3"><div><h2 class="text-lg font-bold text-slate-900 group-hover:text-blue-700">{{ $plan['name'] }}</h2><p class="mt-1 text-sm text-slate-500">{{ $plan['key'] === 'custom' ? 'Prijs per klant' : '€ '.number_format($plan['price_monthly'], 2, ',', '.').' per maand' }}</p></div><span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700">{{ $plan['company_count'] }} {{ $plan['company_count'] === 1 ? 'klant' : 'klanten' }}</span></div>
                <dl class="mt-5 grid grid-cols-3 gap-2 border-t border-slate-100 pt-4 text-center"><div><dt class="text-[11px] text-slate-500">Gebruikers</dt><dd class="mt-1 text-sm font-semibold">{{ $plan['max_users'] === -1 ? '∞' : $plan['max_users'] }}</dd></div><div><dt class="text-[11px] text-slate-500">Locaties</dt><dd class="mt-1 text-sm font-semibold">{{ $plan['max_locations'] === -1 ? '∞' : $plan['max_locations'] }}</dd></div><div><dt class="text-[11px] text-slate-500">Opslag</dt><dd class="mt-1 text-sm font-semibold">{{ $plan['max_storage_gb'] === -1 ? '∞' : $plan['max_storage_gb'].' GB' }}</dd></div></dl>
                <span class="mt-5 inline-flex text-sm font-semibold text-blue-700">Details bekijken →</span>
            </a>
        @endforeach
    </section>
</div>
@endsection

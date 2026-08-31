@extends('layouts.super-admin')

@section('page-title', $plan['name'])

@section('breadcrumbs')
    <span class="text-slate-400">/</span><a href="{{ route('super-admin.subscriptions.index') }}" class="font-medium text-slate-500 hover:text-blue-700">Abonnementen</a><span class="text-slate-400">/</span><span class="font-semibold text-slate-900">{{ $plan['name'] }}</span>
@endsection

@section('content')
<div class="space-y-6">
    <section class="rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 p-6 text-white shadow-sm">
        <a href="{{ route('super-admin.subscriptions.index') }}" class="text-sm font-semibold text-blue-100 hover:text-white">← Alle abonnementen</a>
        @php $periodDetails = \App\Models\Organisation\Company::billingPeriod($plan['billing_period'] ?? 'monthly'); @endphp
        <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between"><div><p class="text-sm text-blue-100">Abonnement</p><h1 class="mt-1 text-3xl font-bold">{{ $plan['name'] }}</h1><p class="mt-2 text-blue-100">{{ $planKey === 'custom' ? 'Voorwaarden worden per klant ingesteld.' : '€ '.number_format($plan['billing_amount'], 2, ',', '.').' per '.$periodDetails['suffix'].', excl. btw' }}</p></div><span class="rounded-xl bg-white/15 px-4 py-2 text-sm font-semibold ring-1 ring-white/20">{{ $companies->count() }} {{ $companies->count() === 1 ? 'klant' : 'klanten' }}</span></div>
    </section>

    @if($planKey !== 'custom')
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div><h2 class="text-lg font-semibold text-slate-900">Abonnement bewerken</h2><p class="mt-1 text-sm text-slate-500">Wijzigingen gelden voor nieuwe én bestaande klanten met dit pakket.</p></div>
            @if($errors->any())<div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <form method="POST" action="{{ route('super-admin.subscriptions.update', $planKey) }}" class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">@csrf @method('PUT')
                <div><label class="mb-1 block text-sm font-medium text-slate-700">Naam</label><input name="name" required value="{{ old('name', $plan['name']) }}" class="w-full rounded-xl border-slate-300 text-sm"></div>
                @php $billingPeriod = old('billing_period', $plan['billing_period'] ?? 'monthly'); @endphp
                <div><label class="mb-1 block text-sm font-medium text-slate-700">Wanneer wordt er betaald?</label><select name="billing_period" class="w-full rounded-xl border-slate-300 text-sm">@foreach(\App\Models\Organisation\Company::BILLING_PERIODS as $periodKey => $period)<option value="{{ $periodKey }}" @selected($billingPeriod === $periodKey)>{{ $period['label'] }}</option>@endforeach</select></div>
                <div><label class="mb-1 block text-sm font-medium text-slate-700">Prijs per betaalmoment excl. btw</label><input name="price" type="number" min="0" step="0.01" required value="{{ old('price', $plan['billing_amount']) }}" class="w-full rounded-xl border-slate-300 text-sm"></div>
                <div><label class="mb-1 block text-sm font-medium text-slate-700">Proefperiode</label><div class="grid grid-cols-[1fr_1.3fr] gap-2"><input name="trial_duration_value" type="number" min="1" max="365" required value="{{ old('trial_duration_value', $plan['trial_duration_value'] ?? 14) }}" class="w-full rounded-xl border-slate-300 text-sm"><select name="trial_duration_unit" class="w-full rounded-xl border-slate-300 text-sm"><option value="days" @selected(old('trial_duration_unit', $plan['trial_duration_unit'] ?? 'days') === 'days')>Dagen</option><option value="weeks" @selected(old('trial_duration_unit', $plan['trial_duration_unit'] ?? 'days') === 'weeks')>Weken</option><option value="months" @selected(old('trial_duration_unit', $plan['trial_duration_unit'] ?? 'days') === 'months')>Maanden</option></select></div></div>
                <fieldset class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 sm:col-span-2 lg:col-span-3"><legend class="px-1 text-sm font-semibold text-slate-900">Vaste projectvereisten</legend><div class="mt-2 grid gap-2 sm:grid-cols-2">@foreach(\App\Models\Organisation\Company::CORE_FEATURES as $featureLabel)<div class="flex items-center gap-2 text-sm text-slate-700"><span class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-600 text-xs font-bold text-white">✓</span><span>{{ $featureLabel }}</span></div>@endforeach</div></fieldset>
                <div class="border-t border-slate-200 pt-4 sm:col-span-2 lg:col-span-3"><h3 class="text-sm font-semibold text-slate-900">Capaciteitsvereisten</h3><p class="mt-1 text-xs text-slate-500">Gebruik -1 voor onbeperkt.</p></div>
                <div><label class="mb-1 block text-sm font-medium text-slate-700">Max. gebruikers</label><input name="max_users" type="number" min="-1" required value="{{ old('max_users', $plan['max_users']) }}" class="w-full rounded-xl border-slate-300 text-sm"></div>
                <div><label class="mb-1 block text-sm font-medium text-slate-700">Max. locaties</label><input name="max_locations" type="number" min="-1" required value="{{ old('max_locations', $plan['max_locations']) }}" class="w-full rounded-xl border-slate-300 text-sm"></div>
                <div><label class="mb-1 block text-sm font-medium text-slate-700">Max. opslag (GB)</label><input name="max_storage_gb" type="number" min="-1" required value="{{ old('max_storage_gb', $plan['max_storage_gb']) }}" class="w-full rounded-xl border-slate-300 text-sm"></div>
                <fieldset class="border-t border-slate-200 pt-4 sm:col-span-2 lg:col-span-3"><legend class="text-sm font-semibold text-slate-900">Optionele onderdelen</legend><p class="mt-1 text-xs text-slate-500">Aanvullende modules die je per pakket kunt activeren.</p><div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">@foreach(\App\Models\Organisation\Company::PLAN_FEATURES as $featureKey => $featureLabel)<label class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 text-sm text-slate-700"><input type="checkbox" name="features[]" value="{{ $featureKey }}" class="rounded border-slate-300 text-blue-600" @checked(in_array($featureKey, old('features', $plan['features'] ?? []), true))><span>{{ $featureLabel }}</span></label>@endforeach</div></fieldset>
                <p class="text-xs text-slate-500 sm:col-span-2">Gebruik -1 voor onbeperkt. De aangepaste prijs wordt gebruikt bij nieuwe activaties en toekomstige planwijzigingen.</p>
                <div class="flex justify-end"><button type="submit" class="w-full rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 lg:w-auto">Wijzigingen opslaan</button></div>
            </form>
        </section>
    @else
        <section class="rounded-2xl border border-blue-200 bg-blue-50 p-5"><h2 class="font-semibold text-slate-900">Maatwerk per klant</h2><p class="mt-1 text-sm text-slate-600">Naam, prijs en limieten wijzig je bij de betreffende klant hieronder.</p></section>
    @endif

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

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4"><h2 class="font-semibold text-slate-900">Gebruikers binnen dit pakket</h2><p class="mt-1 text-xs text-slate-500">Alle beheerders en medewerkers van de gekoppelde klanten.</p></div>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm"><thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500"><tr><th class="px-5 py-3">Gebruiker</th><th class="px-4 py-3">Bedrijf</th><th class="px-4 py-3">Rol</th><th class="px-4 py-3">Status</th><th class="px-5 py-3 text-right">Klant openen</th></tr></thead><tbody class="divide-y divide-slate-100">
                @forelse($companies->flatMap->users as $user)
                    <tr><td class="px-5 py-3"><p class="font-medium text-slate-900">{{ $user->name }}</p><p class="text-xs text-slate-500">{{ $user->email }}</p></td><td class="px-4 py-3 text-slate-700">{{ $user->company->name ?? '—' }}</td><td class="px-4 py-3 text-slate-600">{{ $user->role === 'admin' ? 'Beheerder' : 'Medewerker' }}</td><td class="px-4 py-3"><span class="rounded-full px-2 py-1 text-xs font-semibold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $user->is_active ? 'Actief' : 'Inactief' }}</span></td><td class="px-5 py-3 text-right"><a href="{{ route('super-admin.companies.show', ['company' => $user->company_id, 'section' => 'users']) }}" class="font-semibold text-blue-700">Bekijken →</a></td></tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-slate-500">Nog geen gebruikers binnen dit abonnement.</td></tr>
                @endforelse
            </tbody></table>
        </div>
    </section>
</div>
@endsection

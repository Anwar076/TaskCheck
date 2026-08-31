@extends('layouts.super-admin')

@section('page-title', $company->name)

@section('breadcrumbs')
    <span class="text-slate-400">/</span>
    <a href="{{ route('super-admin.dashboard', ['tab' => 'companies']) }}" class="font-medium text-slate-500 hover:text-blue-700">Bedrijven</a>
    <span class="text-slate-400">/</span>
    <span class="truncate font-semibold text-slate-900">{{ $company->name }}</span>
@endsection

@section('content')
@php
    $status = $company->isOnTrial() ? 'trial' : ($company->subscription_status ?: 'onbekend');
    $statusStyles = match($status) {
        'active' => 'bg-emerald-100 text-emerald-700 ring-emerald-200',
        'trial' => 'bg-blue-100 text-blue-700 ring-blue-200',
        'cancelled' => 'bg-amber-100 text-amber-800 ring-amber-200',
        'expired' => 'bg-red-100 text-red-700 ring-red-200',
        default => 'bg-slate-100 text-slate-600 ring-slate-200',
    };
    $statusLabel = match($status) {
        'active' => 'Actief',
        'trial' => 'Proefperiode',
        'cancelled' => 'Geannuleerd',
        'expired' => 'Verlopen',
        default => ucfirst($status),
    };
    $plan = $company->getPlanDetails();
    $companySection = request('section', 'overview');
@endphp

<div class="space-y-6">
    <section class="overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-sm">
        <div class="p-5 sm:p-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div class="flex min-w-0 items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-white/15 text-xl font-bold ring-1 ring-white/20">
                        {{ strtoupper(mb_substr($company->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="truncate text-2xl font-bold sm:text-3xl">{{ $company->name }}</h1>
                            <span class="inline-flex rounded-full bg-white/15 px-2.5 py-1 text-xs font-semibold ring-1 ring-white/20">
                                {{ $plan['name'] ?? ucfirst($company->subscription_plan ?: 'Geen plan') }}
                            </span>
                        </div>
                        <p class="mt-1 text-sm text-blue-100">Bedrijfsprofiel, gebruik, abonnement en recente activiteit.</p>
                        <div class="mt-3 flex flex-wrap items-center gap-2 text-xs text-blue-100">
                            <span>Aangemaakt {{ $company->created_at?->format('d-m-Y') }}</span><span class="text-blue-300">•</span>
                            <span>Laatste activiteit: {{ $lastActivityAt ? \Carbon\Carbon::parse($lastActivityAt)->diffForHumans() : 'nog niet' }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="button" data-open-dialog="duplicate-company" class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-3 py-2 text-sm font-semibold text-white ring-1 ring-white/20 hover:bg-white/20">Bedrijf dupliceren</button>
                    @if((int) auth()->user()->company_id !== (int) $company->id)
                        <button type="button" data-open-dialog="delete-company" class="inline-flex items-center gap-2 rounded-xl bg-red-950/25 px-3 py-2 text-sm font-semibold text-white ring-1 ring-red-100/30 hover:bg-red-950/40">Bedrijf verwijderen</button>
                    @endif
                    <span class="inline-flex items-center gap-1.5 rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-800">
                        <span class="h-2 w-2 rounded-full {{ $company->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                        {{ $company->is_active ? 'Toegang actief' : 'Toegang geblokkeerd' }}
                    </span>
                    <a href="{{ route('super-admin.dashboard', ['tab' => 'companies']) }}" class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-3 py-2 text-sm font-semibold text-white ring-1 ring-white/20 hover:bg-white/20">
                        <span aria-hidden="true">←</span> Bedrijven
                    </a>
                </div>
            </div>
        </div>
    </section>

    @php
        $primaryCompanySections = ['overview' => ['dashboard','Overzicht'], 'users' => ['users','Gebruikers'], 'lists' => ['templates','Takenlijsten'], 'billing' => ['invoices','Abonnement & facturen']];
        $moreCompanySections = ['reporting' => ['invoices','Rapportages'], 'identity' => ['subscriptions','Microsoft SSO'], 'activity' => ['usage','Activiteit'], 'settings' => ['companies','Bedrijfsgegevens']];
        $moreSectionActive = array_key_exists($companySection, $moreCompanySections);
    @endphp
    <nav class="relative flex flex-wrap gap-1 rounded-2xl border border-slate-200 bg-white p-1.5 shadow-sm" aria-label="Klantonderdelen">
        @foreach($primaryCompanySections as $key => [$icon, $label])
            <a href="{{ route('super-admin.companies.show', ['company' => $company, 'section' => $key]) }}" class="inline-flex items-center gap-2 whitespace-nowrap rounded-xl px-3.5 py-2 text-sm font-semibold {{ $companySection === $key ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-700' }}"><x-super-admin-icon :name="$icon" class="h-4 w-4" />{{ $label }}</a>
        @endforeach
        <details class="group relative ml-auto">
            <summary class="inline-flex cursor-pointer list-none items-center gap-2 whitespace-nowrap rounded-xl px-3.5 py-2 text-sm font-semibold transition [&::-webkit-details-marker]:hidden {{ $moreSectionActive ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-700' }}"><span class="text-lg leading-none">•••</span> Meer <x-super-admin-icon name="chevron" class="h-3.5 w-3.5 transition-transform group-open:rotate-180" /></summary>
            <div class="absolute right-0 top-full z-30 mt-2 w-56 overflow-hidden rounded-xl border border-slate-200 bg-white p-1.5 shadow-xl">
                @foreach($moreCompanySections as $key => [$icon, $label])
                    <a href="{{ route('super-admin.companies.show', ['company' => $company, 'section' => $key]) }}" class="flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-semibold {{ $companySection === $key ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-blue-700' }}"><x-super-admin-icon :name="$icon" class="h-4 w-4" />{{ $label }}</a>
                @endforeach
            </div>
        </details>
    </nav>

    <section class="{{ $companySection === 'overview' ? 'grid' : 'hidden' }} grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-6">
        @foreach([
            ['Gebruikers', $metrics['users'], $metrics['active_users'].' actief'],
            ['Locaties', $metrics['locations'], 'actieve locaties'],
            ['Takenlijsten', $metrics['lists'], $metrics['active_lists'].' actief'],
            ['Taken', $metrics['tasks'], 'digitale taken'],
            ['Inzendingen', $metrics['submissions'], $metrics['submissions_30d'].' in 30 dagen'],
            ['Afronding', $metrics['completion_rate'].'%', $metrics['completed_submissions'].' afgerond'],
        ] as [$label, $value, $sub])
            <article class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <p class="text-xs font-medium text-slate-500">{{ $label }}</p>
                <p class="mt-1 text-2xl font-bold tracking-tight text-slate-900">{{ $value }}</p>
                <p class="mt-1 text-xs text-slate-400">{{ $sub }}</p>
            </article>
        @endforeach
    </section>

    <div class="{{ $companySection === 'overview' ? 'grid' : 'hidden' }} grid-cols-1 items-start gap-6 xl:grid-cols-[minmax(0,1fr)_22rem]">
        <div class="min-w-0 space-y-6">
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div>
                        <h2 class="font-semibold text-slate-900">Recente gebruikers</h2>
                        <p class="text-xs text-slate-500">Admins en medewerkers van deze organisatie.</p>
                    </div>
                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">{{ $metrics['admins'] }} admins · {{ $metrics['employees'] }} medewerkers</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($recentUsers as $user)
                        <div class="flex items-center gap-3 px-5 py-3.5">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-xs font-bold text-blue-700">{{ strtoupper(mb_substr($user->name, 0, 2)) }}</div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ $user->name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ $user->email }}{{ $user->location ? ' · '.$user->location->name : '' }}</p>
                            </div>
                            <span class="rounded-full px-2 py-1 text-[11px] font-semibold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $user->is_active ? ($user->role === 'employee' ? 'Medewerker' : 'Beheerder') : 'Inactief' }}</span>
                        </div>
                    @empty
                        <p class="px-5 py-8 text-center text-sm text-slate-500">Nog geen gebruikers.</p>
                    @endforelse
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-semibold text-slate-900">Recente takenlijsten</h2>
                        <p class="text-xs text-slate-500">Laatste lijsten inclusief omvang en gebruik.</p>
                    </div>
                    <a href="{{ route('super-admin.companies.lists.ai-import', $company) }}" class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                        <x-super-admin-icon name="templates" class="h-4 w-4" /> Importeren met AI
                    </a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($recentLists as $list)
                        <div class="flex flex-col gap-2 px-5 py-3.5 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-900">{{ $list->title }}</p>
                                <p class="text-xs text-slate-500">{{ ucfirst($list->schedule_type ?: 'eenmalig') }} · {{ $list->tasks_count }} taken</p>
                            </div>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="rounded-full bg-blue-50 px-2.5 py-1 font-semibold text-blue-700">{{ $list->submissions_count }} inzendingen</span>
                                <span class="rounded-full px-2.5 py-1 font-semibold {{ $list->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $list->is_active ? 'Actief' : 'Inactief' }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="px-5 py-8 text-center text-sm text-slate-500">Nog geen takenlijsten.</p>
                    @endforelse
                </div>
            </section>

            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-5 py-4">
                    <h2 class="font-semibold text-slate-900">Recente facturen</h2>
                    <p class="text-xs text-slate-500">De laatste betalingen van deze organisatie.</p>
                </div>
                @if($recentInvoices->isEmpty())
                    <p class="px-5 py-8 text-center text-sm text-slate-500">Nog geen facturen beschikbaar.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-[680px] w-full text-sm">
                            <thead class="bg-slate-50 text-left text-xs text-slate-500">
                                <tr><th class="px-5 py-3">Factuurnummer</th><th class="px-4 py-3">Datum</th><th class="px-4 py-3">Omschrijving</th><th class="px-4 py-3 text-right">Bedrag</th><th class="px-5 py-3"></th></tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($recentInvoices as $invoice)
                                    <tr>
                                        <td class="px-5 py-3 font-medium text-slate-900">{{ $invoice->invoice_number }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $invoice->paid_at?->format('d-m-Y') ?? '—' }}</td>
                                        <td class="px-4 py-3 text-slate-600">{{ $invoice->description ?: 'TaskCheck abonnement' }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-slate-900">{{ $invoice->currency }} {{ number_format((float) $invoice->amount, 2, ',', '.') }}</td>
                                        <td class="px-5 py-3 text-right"><a target="_blank" href="{{ route('subscription.invoices.download', $invoice) }}" class="font-semibold text-blue-700 hover:text-blue-900">PDF</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>

        <aside class="space-y-6 xl:sticky xl:top-24">
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3"><div><h2 class="font-semibold text-slate-900">Klantstatus</h2><p class="mt-0.5 text-xs text-slate-500">Abonnement en platformtoegang.</p></div><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $statusStyles }}">{{ $statusLabel }}</span></div>
                <dl class="mt-4 space-y-3 text-sm"><div class="flex justify-between gap-4"><dt class="text-slate-500">Plan</dt><dd class="font-semibold text-slate-900">{{ $plan['name'] ?? 'Geen plan' }}</dd></div><div class="flex justify-between gap-4"><dt class="text-slate-500">Facturatie</dt><dd class="text-right font-medium text-slate-800">{{ $company->billing_required ? 'Maandelijks' : 'Gratis toegang' }}</dd></div><div class="flex justify-between gap-4"><dt class="text-slate-500">Toegang</dt><dd class="font-medium {{ $company->is_active ? 'text-emerald-700' : 'text-red-700' }}">{{ $company->is_active ? 'Actief' : 'Geblokkeerd' }}</dd></div></dl>
                <a href="{{ route('super-admin.companies.show', ['company' => $company, 'section' => 'billing']) }}" class="mt-4 flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700"><x-super-admin-icon name="invoices" class="h-4 w-4" />Abonnement beheren</a>
            </section>
            {{--
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="font-semibold text-slate-900">Abonnement beheren</h2>
                        <p class="text-xs text-slate-500">Plan, toegang en facturatie.</p>
                    </div>
                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $statusStyles }}">{{ $statusLabel }}</span>
                </div>

                @if($errors->any())
                    <div class="mt-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                        <ul class="list-disc space-y-1 pl-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('super-admin.companies.subscription.update', $company) }}" class="mt-5 space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Abonnement</label>
                        <select name="subscription_plan" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach(\App\Models\Organisation\Company::PLANS as $planKey => $planData)
                                <option value="{{ $planKey }}" @selected(old('subscription_plan', $company->subscription_plan) === $planKey)>{{ $planData['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Abonnementsstatus</label>
                        <select name="subscription_status" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                            @foreach(['trial' => 'Proefperiode', 'active' => 'Actief', 'cancelled' => 'Geannuleerd', 'expired' => 'Verlopen'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('subscription_status', $status) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Einddatum gratis toegang</label>
                        <input type="date" name="subscription_ends_at" value="{{ old('subscription_ends_at', optional($company->subscription_ends_at)->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-slate-500">Niet nodig wanneer maandelijkse betaling aanstaat.</p>
                    </div>
                    <div class="space-y-2 rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <label class="flex items-start gap-2 text-sm text-slate-700"><input type="checkbox" name="billing_required" value="1" class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500" @checked(old('billing_required', $company->billing_required))><span><strong class="font-medium text-slate-900">Maandelijkse betaling</strong><br><span class="text-xs text-slate-500">Facturatie en abonnement blijven doorlopen.</span></span></label>
                        <label class="flex items-start gap-2 border-t border-slate-200 pt-2 text-sm text-slate-700"><input type="checkbox" name="is_active" value="1" class="mt-0.5 rounded border-slate-300 text-blue-600 focus:ring-blue-500" @checked(old('is_active', $company->is_active))><span><strong class="font-medium text-slate-900">Platformtoegang actief</strong><br><span class="text-xs text-slate-500">Gebruikers van dit bedrijf kunnen inloggen.</span></span></label>
                    </div>
                    <button class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Wijzigingen opslaan</button>
                </form>
            </section> --}}

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="font-semibold text-slate-900">Bedrijfsgegevens</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    @foreach([
                        ['E-mail', $company->email],
                        ['Telefoon', $company->phone],
                        ['Website', $company->website],
                        ['Adres', $company->address],
                        ['Type', $company->company_type ? ucfirst($company->company_type) : null],
                    ] as [$label, $value])
                        <div class="flex items-start justify-between gap-4"><dt class="text-slate-500">{{ $label }}</dt><dd class="max-w-[65%] break-words text-right font-medium text-slate-800">{{ $value ?: '—' }}</dd></div>
                    @endforeach
                </dl>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="font-semibold text-slate-900">Verbruik en limieten</h2>
                <div class="mt-4 space-y-4">
                    @php
                        $limits = [
                            ['Opslag', $metrics['storage_gb'], $company->max_storage_gb, 'GB'],
                            ['Gebruikers', $metrics['users'], $company->max_users, ''],
                            ['Locaties', $metrics['locations'], $company->max_locations, ''],
                        ];
                    @endphp
                    @foreach($limits as [$label, $used, $limit, $unit])
                        @php $unlimited = (int) $limit === -1; $percentage = $unlimited || !$limit ? 0 : min(100, (int) round(($used / $limit) * 100)); @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs"><span class="font-medium text-slate-600">{{ $label }}</span><span class="text-slate-500">{{ number_format((float) $used, $unit ? 2 : 0, ',', '.') }}{{ $unit ? ' '.$unit : '' }} / {{ $unlimited ? 'Onbeperkt' : $limit.($unit ? ' '.$unit : '') }}</span></div>
                            <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full {{ $percentage >= 90 ? 'bg-red-500' : ($percentage >= 70 ? 'bg-amber-500' : 'bg-blue-500') }}" style="width: {{ $unlimited ? 0 : $percentage }}%"></div></div>
                        </div>
                    @endforeach
                    <div class="border-t border-slate-100 pt-3">
                        <div class="flex items-center justify-between"><span class="text-sm text-slate-600">AI-verbruik</span><strong class="text-sm text-slate-900">{{ number_format($aiTokens, 0, ',', '.') }} tokens</strong></div>
                        @if($aiUsageByFeature->isNotEmpty())
                            <div class="mt-2 space-y-1">@foreach($aiUsageByFeature->take(4) as $usage)<div class="flex justify-between text-xs text-slate-500"><span>{{ str_replace('_', ' ', $usage->feature) }}</span><span>{{ number_format((int) $usage->tokens, 0, ',', '.') }}</span></div>@endforeach</div>
                        @endif
                    </div>
                </div>
            </section>
        </aside>
    </div>

    @if($companySection === 'users')
        @include('super-admin.companies._users')
    @endif

    @if($companySection === 'lists')
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between"><div><h2 class="font-semibold text-slate-900">Alle takenlijsten</h2><p class="text-xs text-slate-500">{{ $companyLists->count() }} lijsten gekoppeld aan deze klant.</p></div><a href="{{ route('super-admin.companies.lists.ai-import', $company) }}" class="rounded-xl bg-blue-600 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-blue-700">✦ Importeren met AI</a></div>
            <div class="divide-y divide-slate-100">@forelse($companyLists as $list)<div class="flex items-center gap-3 px-5 py-4"><div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold text-slate-900">{{ $list->title }}</p><p class="text-xs text-slate-500">{{ $list->tasks_count }} taken · {{ $list->submissions_count }} inzendingen · {{ ucfirst($list->schedule_type ?: 'eenmalig') }}</p></div><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $list->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $list->is_active ? 'Actief' : 'Inactief' }}</span></div>@empty<div class="px-6 py-12 text-center"><p class="font-semibold text-slate-800">Nog geen takenlijsten</p><p class="mt-1 text-sm text-slate-500">Importeer de eerste documenten voor deze klant.</p><a href="{{ route('super-admin.companies.lists.ai-import', $company) }}" class="mt-4 inline-flex rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white">Eerste lijst importeren</a></div>@endforelse</div>
        </section>
    @endif

    @if($companySection === 'billing')
        <section class="grid grid-cols-1 gap-6 xl:grid-cols-3"><div class="xl:col-span-2 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"><div class="border-b border-slate-100 px-5 py-4"><h2 class="font-semibold text-slate-900">Factuurhistorie</h2></div><div class="divide-y divide-slate-100">@forelse($companyInvoices as $invoice)<div class="flex items-center gap-4 px-5 py-4"><div class="min-w-0 flex-1"><p class="font-semibold text-slate-900">{{ $invoice->invoice_number }}</p><p class="text-xs text-slate-500">{{ $invoice->paid_at?->format('d-m-Y') ?? 'Nog niet betaald' }} · {{ $invoice->description ?: 'TaskCheck abonnement' }}</p></div><strong class="text-sm">{{ $invoice->currency }} {{ number_format((float)$invoice->amount, 2, ',', '.') }}</strong><a target="_blank" href="{{ route('subscription.invoices.download', $invoice) }}" class="text-sm font-semibold text-blue-700">PDF</a></div>@empty<div class="px-6 py-12 text-center text-sm text-slate-500">Nog geen facturen voor deze klant.</div>@endforelse</div></div><div>@include('super-admin.companies._subscription-form')</div></section>
    @endif

    @if($companySection === 'reporting')
        @include('super-admin.companies._reporting')
    @endif

    @if($companySection === 'settings')
        <form method="POST" action="{{ route('super-admin.companies.profile.update', $company) }}" class="mx-auto max-w-3xl space-y-5 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">@csrf @method('PUT')
            <div><h2 class="text-lg font-semibold text-slate-900">Bedrijfsgegevens wijzigen</h2><p class="text-sm text-slate-500">Deze gegevens worden gebruikt voor klantcontact en facturatie.</p></div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2"><div class="sm:col-span-2"><label class="mb-1 block text-sm font-medium text-slate-700">Bedrijfsnaam</label><input name="name" value="{{ old('name',$company->name) }}" required class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"></div><div><label class="mb-1 block text-sm font-medium text-slate-700">E-mail</label><input name="email" type="email" value="{{ old('email',$company->email) }}" class="w-full rounded-xl border-slate-300 text-sm"></div><div><label class="mb-1 block text-sm font-medium text-slate-700">Telefoon</label><input name="phone" value="{{ old('phone',$company->phone) }}" class="w-full rounded-xl border-slate-300 text-sm"></div><div><label class="mb-1 block text-sm font-medium text-slate-700">Website</label><input name="website" type="url" value="{{ old('website',$company->website) }}" class="w-full rounded-xl border-slate-300 text-sm"></div><div><label class="mb-1 block text-sm font-medium text-slate-700">Type</label><select name="company_type" class="w-full rounded-xl border-slate-300 text-sm"><option value="">Niet ingesteld</option><option value="horeca" @selected($company->company_type==='horeca')>Horeca</option><option value="cleaning" @selected($company->company_type==='cleaning')>Schoonmaak</option><option value="other" @selected($company->company_type==='other')>Anders</option></select></div><div class="sm:col-span-2"><label class="mb-1 block text-sm font-medium text-slate-700">Adres</label><input name="address" value="{{ old('address',$company->address) }}" class="w-full rounded-xl border-slate-300 text-sm"></div></div>
            <div class="flex justify-end"><button class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Bedrijfsgegevens opslaan</button></div>
        </form>
    @endif

    @if($companySection === 'identity')
        @include('super-admin.companies._identity')
    @endif

    @if($companySection === 'activity')
        <section class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            @foreach([
                ['Nieuwe gebruikers', $recentUsers, fn($item) => $item->name, fn($item) => $item->created_at?->diffForHumans()],
                ['Nieuwe takenlijsten', $recentLists, fn($item) => $item->title, fn($item) => $item->created_at?->diffForHumans()],
                ['Recente facturen', $recentInvoices, fn($item) => $item->invoice_number, fn($item) => $item->paid_at?->diffForHumans() ?? 'Nog niet betaald'],
            ] as [$heading, $items, $titleFn, $dateFn])
                <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"><div class="border-b border-slate-100 px-5 py-4"><h2 class="font-semibold text-slate-900">{{ $heading }}</h2></div><div class="divide-y divide-slate-100">@forelse($items as $item)<div class="px-5 py-3"><p class="truncate text-sm font-semibold text-slate-800">{{ $titleFn($item) }}</p><p class="mt-0.5 text-xs text-slate-500">{{ $dateFn($item) }}</p></div>@empty<div class="px-5 py-8 text-center text-sm text-slate-500">Nog geen activiteit.</div>@endforelse</div></div>
            @endforeach
        </section>
    @endif
</div>
<dialog id="duplicate-company" class="w-[calc(100%-2rem)] max-w-2xl rounded-2xl p-0 shadow-2xl backdrop:bg-slate-950/50">
    <form method="POST" action="{{ route('super-admin.companies.duplicate', $company) }}">@csrf
        <div class="border-b border-slate-200 px-6 py-5"><h2 class="text-lg font-semibold text-slate-900">{{ $company->name }} dupliceren</h2><p class="mt-1 text-sm text-slate-500">Maak een schone franchiseomgeving met een nieuwe beheerder en een proefperiode van 14 dagen. Inzendingen, medewerkers, facturen, betalingen en SSO-instellingen gaan nooit mee.</p></div>
        <div class="space-y-5 px-6 py-5">
            <div class="grid gap-4 sm:grid-cols-2"><div><label class="mb-1 block text-sm font-medium text-slate-700">Naam nieuw bedrijf</label><input name="company_name" required value="{{ old('company_name', $company->name.' - kopie') }}" class="w-full rounded-xl border-slate-300 text-sm"></div><div><label class="mb-1 block text-sm font-medium text-slate-700">Abonnement</label><select name="subscription_plan" class="w-full rounded-xl border-slate-300 text-sm">@foreach(\App\Models\Organisation\Company::plans() as $key => $planOption)<option value="{{ $key }}" @selected($key === $company->subscription_plan)>{{ $planOption['name'] }}</option>@endforeach</select></div><div><label class="mb-1 block text-sm font-medium text-slate-700">Naam nieuwe beheerder</label><input name="admin_name" required value="{{ old('admin_name') }}" class="w-full rounded-xl border-slate-300 text-sm"></div><div><label class="mb-1 block text-sm font-medium text-slate-700">E-mail nieuwe beheerder</label><input type="email" name="admin_email" required value="{{ old('admin_email') }}" class="w-full rounded-xl border-slate-300 text-sm"></div></div>
            <fieldset><legend class="text-sm font-semibold text-slate-800">Account activeren</legend><div class="mt-2 grid gap-3 sm:grid-cols-2"><label class="flex cursor-pointer gap-3 rounded-xl border border-slate-200 p-3"><input type="radio" name="account_setup" value="invite" @checked(old('account_setup', 'invite') === 'invite') class="mt-0.5 border-slate-300 text-blue-600"><span><strong class="block text-sm text-slate-800">Uitnodiging per e-mail</strong><span class="text-xs text-slate-500">De beheerder kiest zelf een wachtwoord.</span></span></label><label class="flex cursor-pointer gap-3 rounded-xl border border-slate-200 p-3"><input type="radio" name="account_setup" value="password" @checked(old('account_setup') === 'password') class="mt-0.5 border-slate-300 text-blue-600"><span><strong class="block text-sm text-slate-800">Wachtwoord direct instellen</strong><span class="text-xs text-slate-500">Er wordt geen e-mail verstuurd.</span></span></label></div></fieldset>
            <div id="duplicate-password-fields" class="hidden rounded-xl border border-blue-100 bg-blue-50/60 p-4"><label class="mb-1 block text-sm font-medium text-slate-700">Wachtwoord nieuwe beheerder</label><div class="flex flex-col gap-2 sm:flex-row"><input id="duplicate-admin-password" type="text" name="admin_password" minlength="12" autocomplete="new-password" value="{{ old('admin_password') }}" class="min-w-0 flex-1 rounded-xl border-slate-300 font-mono text-sm" placeholder="Minimaal 12 tekens"><button id="generate-duplicate-admin-password" type="button" class="rounded-xl border border-blue-200 bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 hover:bg-blue-100">Genereer veilig wachtwoord</button></div><p class="mt-2 text-xs text-slate-500">Kopieer dit wachtwoord en deel het via een veilig kanaal. TaskCheck mailt het niet.</p></div>
            <fieldset><legend class="text-sm font-semibold text-slate-800">Onderdelen meenemen</legend><div class="mt-3 grid gap-3 sm:grid-cols-2">
                <label class="flex gap-3 rounded-xl border border-slate-200 p-3"><input type="checkbox" name="copy_lists" value="1" checked class="mt-0.5 rounded border-slate-300 text-blue-600"><span><strong class="block text-sm text-slate-800">Takenlijsten en taken</strong><span class="text-xs text-slate-500">Inclusief planning, bewijsvereisten en algemene roltoewijzingen.</span></span></label>
                <label class="flex gap-3 rounded-xl border border-slate-200 p-3"><input type="checkbox" name="copy_locations" value="1" checked class="mt-0.5 rounded border-slate-300 text-blue-600"><span><strong class="block text-sm text-slate-800">Locaties</strong><span class="text-xs text-slate-500">Lijsten blijven aan de overeenkomstige gekopieerde locatie gekoppeld.</span></span></label>
                <label class="flex gap-3 rounded-xl border border-slate-200 p-3"><input type="checkbox" name="copy_settings" value="1" checked class="mt-0.5 rounded border-slate-300 text-blue-600"><span><strong class="block text-sm text-slate-800">Werkwijze en afdelingen</strong><span class="text-xs text-slate-500">Werkuren, afdelingen en algemene organisatie-inrichting.</span></span></label>
                <label class="flex gap-3 rounded-xl border border-amber-200 bg-amber-50 p-3"><input type="checkbox" name="copy_reporting" value="1" class="mt-0.5 rounded border-amber-300 text-blue-600"><span><strong class="block text-sm text-slate-800">Rapportageontvangers</strong><span class="text-xs text-amber-800">Niet standaard: controleer eerst of de oude ontvangers rapporten mogen krijgen.</span></span></label>
            </div></fieldset>
        </div>
        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 px-6 py-4 sm:flex-row sm:justify-end"><button type="button" data-close-dialog class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Annuleren</button><button class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700" onclick="return confirm('Nieuw bedrijf en beheerdersaccount aanmaken?')">Bedrijf dupliceren</button></div>
    </form>
</dialog>
@if((int) auth()->user()->company_id !== (int) $company->id)
<dialog id="delete-company" class="w-[calc(100%-2rem)] max-w-lg rounded-2xl p-0 shadow-2xl backdrop:bg-slate-950/60">
    <form method="POST" action="{{ route('super-admin.companies.destroy', $company) }}">
        @csrf
        @method('DELETE')
        <div class="border-b border-red-100 bg-red-50 px-6 py-5">
            <h2 class="text-lg font-semibold text-red-900">{{ $company->name }} definitief verwijderen?</h2>
            <p class="mt-1 text-sm text-red-700">Deze actie kan niet ongedaan worden gemaakt.</p>
        </div>
        <div class="space-y-4 px-6 py-5">
            <p class="text-sm leading-relaxed text-slate-600">Het bedrijf en alle onderliggende gebruikers, locaties, takenlijsten, inzendingen, bewijsbestanden en facturen worden verwijderd. Een gekoppeld Mollie-abonnement wordt eerst stopgezet.</p>
            <div>
                <label for="delete-company-confirmation" class="mb-1.5 block text-sm font-medium text-slate-700">Typ <strong>{{ $company->name }}</strong> ter bevestiging</label>
                <input id="delete-company-confirmation" name="confirmation_name" required autocomplete="off" class="w-full rounded-xl border-slate-300 text-sm focus:border-red-500 focus:ring-red-500">
            </div>
        </div>
        <div class="flex flex-col-reverse gap-3 border-t border-slate-200 px-6 py-4 sm:flex-row sm:justify-end">
            <button type="button" data-close-dialog class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700">Annuleren</button>
            <button class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-red-700">Bedrijf definitief verwijderen</button>
        </div>
    </form>
</dialog>
@endif
@push('scripts')
<script>
const subscriptionPlan = document.getElementById('subscription-plan');
const customSubscriptionFields = document.getElementById('custom-subscription-fields');
const toggleCustomSubscriptionFields = () => customSubscriptionFields?.classList.toggle('hidden', subscriptionPlan?.value !== 'custom');
subscriptionPlan?.addEventListener('change', toggleCustomSubscriptionFields);
toggleCustomSubscriptionFields();
document.querySelectorAll('[data-confirm-submit]').forEach((button) => button.addEventListener('click', (event) => { if (!confirm(button.dataset.confirmSubmit)) event.preventDefault(); }));
document.querySelectorAll('[data-open-dialog]').forEach((button) => button.addEventListener('click', () => document.getElementById(button.dataset.openDialog)?.showModal()));
document.querySelectorAll('[data-close-dialog]').forEach((button) => button.addEventListener('click', () => button.closest('dialog')?.close()));
document.querySelectorAll('dialog').forEach((dialog) => dialog.addEventListener('click', (event) => { if (event.target === dialog) dialog.close(); }));
const userSearch = document.getElementById('company-user-search');
userSearch?.addEventListener('input', () => {
    const query = userSearch.value.toLocaleLowerCase('nl').trim();
    let visible = 0;
    document.querySelectorAll('[data-user-row]').forEach((row) => {
        const matches = row.dataset.userSearch.includes(query);
        row.style.display = matches ? '' : 'none';
        if (matches) visible++;
    });
    document.getElementById('company-user-empty-search')?.classList.toggle('hidden', visible !== 0);
});
document.getElementById('generate-company-user-password')?.addEventListener('click', () => {
    const alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
    const values = new Uint32Array(18);
    crypto.getRandomValues(values);
    document.getElementById('new-company-user-password').value = Array.from(values, value => alphabet[value % alphabet.length]).join('');
});
const duplicatePasswordFields = document.getElementById('duplicate-password-fields');
const duplicatePasswordInput = document.getElementById('duplicate-admin-password');
const syncDuplicateAccountSetup = () => {
    const usesPassword = document.querySelector('input[name="account_setup"]:checked')?.value === 'password';
    duplicatePasswordFields?.classList.toggle('hidden', !usesPassword);
    if (duplicatePasswordInput) duplicatePasswordInput.required = usesPassword;
};
document.querySelectorAll('input[name="account_setup"]').forEach(input => input.addEventListener('change', syncDuplicateAccountSetup));
syncDuplicateAccountSetup();
document.getElementById('generate-duplicate-admin-password')?.addEventListener('click', () => {
    const alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
    const values = new Uint32Array(20);
    crypto.getRandomValues(values);
    duplicatePasswordInput.value = Array.from(values, value => alphabet[value % alphabet.length]).join('');
    duplicatePasswordInput.select();
});
</script>
@endpush
@endsection

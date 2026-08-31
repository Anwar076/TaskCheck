@extends('layouts.super-admin')

@section('page-title', 'Super Admin Dashboard')

@section('content')
<div class="space-y-6">
    @if(!request()->has('tab'))
    <div class="rounded-2xl bg-gradient-to-r from-blue-600 to-blue-700 p-5 text-white shadow-sm sm:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold">Platformoverzicht</h1>
                <p class="mt-1 text-blue-100/90">Alle bedrijven, gebruikers, lijsten en inzendingen op 1 plek.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('super-admin.dashboard', ['tab' => 'usage']) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                    Gebruik
                </a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'monitoring']) }}" class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-sm font-semibold text-white hover:bg-white/20">
                    Monitoring
                </a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'templates']) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white hover:bg-blue-500">
                    Templates beheren
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-slate-500">Bedrijven</p>
            <p class="text-2xl font-bold text-slate-900">{{ $totals['companies'] }}</p>
            <p class="mt-1 text-xs text-slate-400">klantomgevingen</p>
        </div>
        <a href="{{ route('super-admin.dashboard', ['tab' => 'users']) }}" class="block bg-white border border-slate-200 rounded-xl p-4 shadow-sm transition hover:border-blue-200 hover:shadow-md">
            <p class="text-xs text-slate-500">Gebruikers</p>
            <p class="text-2xl font-bold text-slate-900">{{ $totals['users'] }}</p>
            <p class="mt-1 text-xs text-slate-400">{{ $totals['admins'] }} admins · {{ $totals['employees'] }} medewerkers</p>
        </a>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-slate-500">Takenlijsten</p>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($totals['task_lists'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-400">{{ number_format($totals['tasks'], 0, ',', '.') }} taken</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-slate-500">Inzendingen</p>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($totals['submissions'], 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-slate-400">{{ $totals['locations'] }} locaties · {{ number_format($totals['storage_gb'], 2, ',', '.') }} GB</p>
        </div>
    </div>
    @endif

    <section data-tab-panel="overview" class="sa-tab-panel space-y-4 {{ $activeDashboardTab !== 'overview' ? 'hidden' : '' }}">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <a href="{{ route('super-admin.dashboard', ['tab' => 'companies']) }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15m-14.25 0v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                    </div>
                    <span class="text-slate-400 transition group-hover:translate-x-1 group-hover:text-blue-600">→</span>
                </div>
                <h2 class="mt-4 font-semibold text-slate-900">Bedrijven beheren</h2>
                <p class="mt-1 text-sm text-slate-500">Bekijk abonnementen, gebruik en bedrijfsdetails van {{ $totals['companies'] }} organisaties.</p>
            </a>
            <a href="{{ route('super-admin.dashboard', ['tab' => 'monitoring']) }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l3-3 4.5 4.5 6-7.5 3 3.75M4.5 19.5h15a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5h-15A1.5 1.5 0 003 6v12a1.5 1.5 0 001.5 1.5z"/></svg>
                    </div>
                    <span class="text-slate-400 transition group-hover:translate-x-1 group-hover:text-blue-600">→</span>
                </div>
                <h2 class="mt-4 font-semibold text-slate-900">Platformgezondheid</h2>
                <p class="mt-1 text-sm text-slate-500">{{ count($recentErrors) }} recente fouten en {{ $tickets->where('status', 'open')->count() }} openstaande incidenttickets.</p>
            </a>
            <a href="{{ route('super-admin.templates.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625A1.125 1.125 0 004.5 3.375v17.25c0 .621.504 1.125 1.125 1.125h12.75a1.125 1.125 0 001.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <span class="text-slate-400 transition group-hover:translate-x-1 group-hover:text-blue-600">→</span>
                </div>
                <h2 class="mt-4 font-semibold text-slate-900">Globale templates</h2>
                <p class="mt-1 text-sm text-slate-500">Bouw, importeer en publiceer centrale checklists voor alle branches.</p>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="font-semibold text-slate-900">Abonnementenverdeling</h2>
                <div class="mt-4 space-y-3">
                    @forelse($plans as $planName => $count)
                        @php $share = $totals['companies'] > 0 ? max(4, round(($count / $totals['companies']) * 100)) : 0; @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm"><span class="text-slate-600">{{ ucfirst(str_replace('_', ' ', $planName)) }}</span><strong class="text-slate-900">{{ $count }}</strong></div>
                            <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-blue-500" style="width: {{ $share }}%"></div></div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Nog geen bedrijven.</p>
                    @endforelse
                </div>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="font-semibold text-slate-900">Snel naar</h2>
                <div class="mt-4 grid grid-cols-1 gap-2 sm:grid-cols-2">
                    @foreach([
                        ['Platformbericht versturen', route('super-admin.dashboard', ['tab' => 'communications'])],
                        ['Gebruiksanalyse bekijken', route('super-admin.dashboard', ['tab' => 'usage'])],
                        ['Facturen exporteren', route('super-admin.dashboard', ['tab' => 'invoices'])],
                        ['AI-template importeren', route('super-admin.templates.ai-import')],
                    ] as [$label, $url])
                        <a href="{{ $url }}" class="flex items-center justify-between rounded-xl border border-slate-200 px-3 py-3 text-sm font-medium text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"><span>{{ $label }}</span><span>→</span></a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section data-tab-panel="users" class="sa-tab-panel space-y-4 {{ $activeDashboardTab !== 'users' ? 'hidden' : '' }}">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Gebruikers</h2>
            <p class="text-sm text-slate-500">Alle beheerders en medewerkers van alle bedrijven.</p>
        </div>

        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
            @foreach([
                ['Totaal', $totals['users']],
                ['Beheerders', $totals['admins']],
                ['Medewerkers', $totals['employees']],
                ['Actief', $users->where('is_active', true)->count()],
            ] as [$label, $value])
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs uppercase tracking-wide text-slate-500">{{ $label }}</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="mb-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-slate-900">Gebruikersoverzicht</h3>
                <input type="search" data-table-search="users" class="w-full rounded-xl border-slate-300 text-sm sm:w-80" placeholder="Zoek naam, e-mail, bedrijf of rol…">
            </div>
            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="min-w-[900px] w-full text-sm">
                    <thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-500">
                        <tr><th class="px-4 py-3">Gebruiker</th><th class="px-4 py-3">Bedrijf</th><th class="px-4 py-3">Rol</th><th class="px-4 py-3">Locatie</th><th class="px-4 py-3">Status</th><th class="px-4 py-3 text-right">Actie</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/60" data-search-row="users" data-search-text="{{ strtolower($user->name.' '.$user->email.' '.($user->company?->name ?? '').' '.$user->role.' '.($user->location?->name ?? '')) }}">
                                <td class="px-4 py-3"><p class="font-semibold text-slate-900">{{ $user->name }}</p><p class="text-xs text-slate-500">{{ $user->email }}</p></td>
                                <td class="px-4 py-3 text-slate-700">{{ $user->company?->name ?? '—' }}</td>
                                <td class="px-4 py-3">{{ $user->role === 'employee' ? 'Medewerker' : 'Beheerder' }}</td>
                                <td class="px-4 py-3 text-slate-600">{{ $user->location?->name ?? '—' }}</td>
                                <td class="px-4 py-3"><span class="inline-flex rounded-full px-2 py-1 text-xs font-semibold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $user->is_active ? 'Actief' : 'Inactief' }}</span></td>
                                <td class="px-4 py-3 text-right">
                                    @if($user->company)
                                        <a href="{{ route('super-admin.companies.show', ['company' => $user->company, 'section' => 'users']) }}" class="inline-flex rounded-lg border border-blue-200 bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700 hover:bg-blue-100">Beheren</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-4 py-8 text-center text-slate-500">Geen gebruikers gevonden.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p class="mt-3 hidden text-sm text-slate-500" data-table-empty="users">Geen gebruikers gevonden voor deze zoekopdracht.</p>
        </div>
    </section>

    <section data-tab-panel="communications" class="sa-tab-panel space-y-4 {{ $activeDashboardTab !== 'communications' ? 'hidden' : '' }}">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Communicatie</h2>
            <p class="text-sm text-slate-500">Bereik klanten per e-mail of rechtstreeks in TaskCheck.</p>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm lg:order-1">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 7.5v9A2.25 2.25 0 0119.5 18.75h-15A2.25 2.25 0 012.25 16.5v-9m19.5 0A2.25 2.25 0 0019.5 5.25h-15A2.25 2.25 0 002.25 7.5m19.5 0l-8.69 5.214a2.25 2.25 0 01-2.12 0L2.25 7.5"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Bulk mail naar alle bedrijven</h2>
                        <p class="text-sm text-slate-500">Stuur in een keer een update naar alle bedrijfscontacten.</p>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('super-admin.communications.broadcast-mail') }}" class="space-y-3" id="broadcast-mail-form">
                @csrf
                <label class="block text-sm font-medium text-slate-700">Onderwerp</label>
                <input
                    name="subject"
                    value="{{ old('subject') }}"
                    class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-400 focus:ring-blue-400"
                    placeholder="Onderwerp"
                    required
                >
                <label class="block text-sm font-medium text-slate-700">Bericht</label>
                <textarea
                    name="message"
                    rows="5"
                    class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-400 focus:ring-blue-400"
                    placeholder="Bericht naar alle bedrijven..."
                    required
                >{{ old('message') }}</textarea>
                <div class="rounded-xl border border-blue-100 bg-blue-50 px-3 py-2 text-sm text-blue-800"><strong id="mail-recipient-count">{{ $communicationCounts['active_companies'] }}</strong> bedrijfscontacten ontvangen dit bericht.</div>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-1">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2">
                        <input type="checkbox" name="include_inactive" value="1" @checked(old('include_inactive'))>
                        Ook inactieve bedrijven mailen
                    </label>
                    <div class="flex flex-wrap gap-2">
                    <button type="button" data-preview-form="broadcast-mail-form" data-preview-kind="E-mail" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Voorbeeld</button>
                    <button name="send_mode" value="test" class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-2.5 text-sm font-semibold text-blue-700 hover:bg-blue-100">Test naar mij</button>
                    <button name="send_mode" value="send" data-confirm-send="Weet je zeker dat je deze e-mail naar alle geselecteerde bedrijfscontacten wilt versturen?" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 text-white px-5 py-2.5 text-sm font-semibold hover:bg-blue-700 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.77 59.77 0 0 1 3.27 20.875L6 12Zm0 0h7.5"/>
                        </svg>
                        Verstuur bulkmail
                    </button>
                    </div>
                </div>
            </form>
            <p class="text-xs text-slate-500 mt-2">Per bedrijf wordt de bedrijfsmail gebruikt, of anders de eerste actieve admin e-mail.</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm lg:col-span-2 lg:order-3" id="mail-tracklinks">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m7.07-7.07l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Mail tracklinks</h2>
                        <p class="text-sm text-slate-500">Tracking op de achtergrond; in je mail toon je gewoon <strong>taskcheck.nl</strong> als linktekst (kant-en-klare HTML hieronder).</p>
                    </div>
                </div>
            </div>

            @if($errors->any() && old('name'))
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('super-admin.marketing-links.store') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3 mb-6">
                @csrf
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Campagnenaam</label>
                    <input name="name" value="{{ old('name') }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-amber-400 focus:ring-amber-400" placeholder="Bijv. Nieuwsbrief juni 2026" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Code (optioneel)</label>
                    <input name="code" value="{{ old('code') }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-amber-400 focus:ring-amber-400" placeholder="juni-mail-2026" pattern="[a-z0-9]+(-[a-z0-9]+)*">
                    <p class="text-xs text-slate-500 mt-1">Alleen kleine letters, cijfers, streepjes.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Doel-URL</label>
                    <input name="destination_url" type="url" value="{{ old('destination_url', config('services.marketing_link.default_destination')) }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-amber-400 focus:ring-amber-400" placeholder="https://taskcheck.nl">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-amber-600 text-white px-4 py-2.5 text-sm font-semibold hover:bg-amber-700 shadow-sm">
                        Tracklink aanmaken
                    </button>
                </div>
            </form>

            @if($marketingLinks->isEmpty())
                <p class="text-sm text-slate-500 rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center">Nog geen tracklinks. Maak er een aan en plak de URL in je mail naar taskcheck.nl.</p>
            @else
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="min-w-[820px] w-full text-sm">
                        <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                            <tr>
                                <th class="px-4 py-3">Campagne</th>
                                <th class="px-4 py-3">Voor in je mail</th>
                                <th class="px-4 py-3 text-right">Kliks</th>
                                <th class="px-4 py-3 text-right">Uniek</th>
                                <th class="px-4 py-3">Laatste klik</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($marketingLinks as $link)
                                <tr class="{{ $link->is_active ? '' : 'opacity-50 bg-slate-50' }}">
                                    <td class="px-4 py-3 align-top">
                                        <p class="font-semibold text-slate-900">{{ $link->name }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">→ {{ $link->destination_url }}</p>
                                        @unless($link->is_active)
                                            <span class="inline-block mt-1 text-xs font-medium text-slate-500">Gedeactiveerd</span>
                                        @endunless
                                    </td>
                                    <td class="px-4 py-3 align-top max-w-md">
                                        <p class="text-sm mb-2">
                                            Lezer ziet:
                                            <a href="{{ $link->tracking_url }}" class="font-semibold text-blue-700 underline" target="_blank" rel="noopener">{{ $link->mail_link_text }}</a>
                                        </p>
                                        <div class="flex flex-wrap gap-2 mb-2">
                                            <button type="button" class="rounded-lg border border-blue-200 bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-800 hover:bg-blue-100" data-copy-text="{{ $link->mail_link_html }}">Kopieer HTML</button>
                                            <button type="button" class="rounded-lg border border-slate-200 px-2.5 py-1 text-xs font-medium text-slate-700 hover:bg-slate-50" data-copy-target="track-url-{{ $link->id }}">Kopieer track-URL</button>
                                        </div>
                                        <code class="text-xs break-all text-slate-500 block" id="track-url-{{ $link->id }}" title="Alleen nodig als je zelf een hyperlink maakt">{{ $link->tracking_url }}</code>
                                    </td>
                                    <td class="px-4 py-3 text-right align-top font-bold text-slate-900 tabular-nums">{{ number_format($link->clicks_count, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right align-top font-semibold text-slate-700 tabular-nums">{{ number_format($link->unique_clicks_count, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 align-top text-slate-600 whitespace-nowrap">
                                        {{ $link->last_clicked_at?->timezone(config('app.timezone'))->format('d-m-Y H:i') ?? '—' }}
                                    </td>
                                    <td class="px-4 py-3 align-top text-right">
                                        @if($link->is_active)
                                            <form method="POST" action="{{ route('super-admin.marketing-links.destroy', $link) }}" onsubmit="return confirm('Tracklink deactiveren? De URL werkt dan niet meer.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-800">Uitzetten</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <p class="text-xs text-slate-500 mt-3">
                In Gmail/Outlook: tekst selecteren → link invoegen → plak de <strong>track-URL</strong> als adres en zet de weergavetekst op <strong>taskcheck.nl</strong>.
                Of gebruik <strong>Kopieer HTML</strong> in een HTML-mail. De bezoeker ziet alleen taskcheck.nl; klikken worden wel geteld.
                <code class="text-blue-700">APP_URL</code> moet op je live domein staan (bijv. https://taskcheck.nl).
            </p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm lg:order-2">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3h6m5.25 9H5.25A2.25 2.25 0 0 1 3 18V6A2.25 2.25 0 0 1 5.25 3.75h13.5A2.25 2.25 0 0 1 21 6v12a2.25 2.25 0 0 1-2.25 2.25Z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">In-app melding</h2>
                        <p class="text-sm text-slate-500">Toon een bericht in TaskCheck, gericht aan de juiste gebruikers.</p>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('super-admin.communications.broadcast-notification') }}" class="space-y-3" id="broadcast-notification-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Titel</label>
                        <input
                            name="title"
                            value="{{ old('title') }}"
                            class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-400 focus:ring-blue-400"
                            placeholder="Bijv. Nieuwe update binnenkort"
                            required
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Type melding</label>
                        <select name="severity" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-400 focus:ring-blue-400">
                            <option value="info" @selected(old('severity', 'info') === 'info')>Info</option>
                            <option value="success" @selected(old('severity') === 'success')>Succes</option>
                            <option value="warning" @selected(old('severity') === 'warning')>Waarschuwing</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Bericht</label>
                    <textarea
                        name="message"
                        rows="4"
                        class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-400 focus:ring-blue-400"
                        placeholder="Schrijf hier de platformupdate..."
                        required
                    >{{ old('message') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Doelgroep</label>
                        <select name="audience" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-400 focus:ring-blue-400">
                            <option value="all" @selected(old('audience', 'all') === 'all')>Iedereen</option>
                            <option value="admins" @selected(old('audience') === 'admins')>Alleen admins</option>
                            <option value="employees" @selected(old('audience') === 'employees')>Alleen medewerkers</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center gap-2 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2">
                            <input type="checkbox" name="include_inactive" value="1" @checked(old('include_inactive'))>
                            Inclusief inactieve gebruikers
                        </label>
                    </div>
                </div>

                <div class="rounded-xl border border-blue-100 bg-blue-50 px-3 py-2 text-sm text-blue-800"><strong id="notification-recipient-count">{{ $communicationCounts['active_users'] }}</strong> gebruikers ontvangen deze melding.</div>
                <div class="flex flex-wrap justify-end gap-2">
                    <button type="button" data-preview-form="broadcast-notification-form" data-preview-kind="In-app melding" class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Voorbeeld</button>
                    <button data-confirm-send="Weet je zeker dat je deze melding naar de geselecteerde doelgroep wilt versturen?" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 text-white px-5 py-2.5 text-sm font-semibold hover:bg-blue-700 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.77 59.77 0 0 1 3.27 20.875L6 12Zm0 0h7.5"/>
                        </svg>
                        Verstuur melding
                    </button>
                </div>
            </form>

            <div class="mt-5 border-t border-slate-100 pt-4">
                <h3 class="text-sm font-semibold text-slate-900 mb-2">Laatst verstuurde meldingen</h3>
                <div class="space-y-2 max-h-56 overflow-auto">
                    @forelse(($recentAnnouncements ?? collect()) as $announcement)
                        @php
                            $audienceLabel = match($announcement['audience'] ?? 'all') {
                                'admins' => 'Admins',
                                'employees' => 'Medewerkers',
                                default => 'Iedereen',
                            };
                            $severityClass = match($announcement['severity'] ?? 'info') {
                                'warning' => 'bg-amber-100 text-amber-700',
                                'success' => 'bg-emerald-100 text-emerald-700',
                                default => 'bg-blue-100 text-blue-700',
                            };
                        @endphp
                        <div class="rounded-xl border border-slate-200 p-3 bg-slate-50/60">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $announcement['title'] }}</p>
                                    <p class="text-xs text-slate-600 mt-0.5">{{ $announcement['message'] }}</p>
                                </div>
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $severityClass }}">
                                    {{ strtoupper($announcement['severity']) }}
                                </span>
                            </div>
                            <p class="text-[11px] text-slate-500 mt-2">
                                {{ $audienceLabel }} · {{ (int) ($announcement['recipients'] ?? 0) }} ontvangers ·
                                {{ optional($announcement['sent_at'] ?? null)?->timezone('Europe/Amsterdam')?->format('d-m-Y H:i') }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Nog geen platformmeldingen verstuurd.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm lg:col-span-2 lg:order-4">
            <div class="mb-4"><h2 class="text-lg font-semibold text-slate-900">Verzendgeschiedenis</h2><p class="text-sm text-slate-500">Wat is verstuurd, door wie en naar hoeveel ontvangers.</p></div>
            <div class="divide-y divide-slate-100 rounded-xl border border-slate-200">
                @forelse($broadcastHistory as $broadcast)
                    <div class="flex flex-col gap-2 px-4 py-3 sm:flex-row sm:items-center"><span class="inline-flex w-fit rounded-full px-2.5 py-1 text-xs font-semibold {{ $broadcast->channel === 'email' ? 'bg-blue-50 text-blue-700' : 'bg-indigo-50 text-indigo-700' }}">{{ $broadcast->channel === 'email' ? 'E-mail' : 'In-app' }}</span><div class="min-w-0 flex-1"><p class="truncate text-sm font-semibold text-slate-900">{{ $broadcast->subject ?: $broadcast->title }}</p><p class="text-xs text-slate-500">{{ $broadcast->sent_at?->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }} · {{ $broadcast->sender?->name ?: 'Superadmin' }}</p></div><div class="text-xs text-slate-600"><strong>{{ $broadcast->recipients_count }}</strong> ontvangen @if($broadcast->failed_count)· <span class="text-red-600">{{ $broadcast->failed_count }} mislukt</span>@endif</div></div>
                @empty
                    <div class="px-6 py-10 text-center"><p class="font-semibold text-slate-800">Nog geen verzendhistorie</p><p class="mt-1 text-sm text-slate-500">Nieuwe e-mails en meldingen worden hier automatisch vastgelegd.</p></div>
                @endforelse
            </div>
        </div>
    </div>
    </section>

    <section data-tab-panel="companies" class="sa-tab-panel space-y-4 {{ $activeDashboardTab !== 'companies' ? 'hidden' : '' }}">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Bedrijven</h2>
            <p class="text-sm text-slate-500">Abonnementen, status en gebruik per organisatie beheren.</p>
        </div>
        <a href="{{ route('super-admin.companies.create') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700"><span class="text-lg leading-none">+</span> Nieuw bedrijf</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Totaal bedrijven</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $totals['companies'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Actieve locaties</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $totals['locations'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Totaal gebruikers</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $totals['users'] }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-slate-500">Opslag totaal</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($totals['storage_gb'], 2, ',', '.') }} GB</p>
        </div>
    </div>

    <div class="space-y-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-3">
                <h2 class="text-lg font-semibold text-slate-900">Bedrijven overzicht</h2>
                <input type="search" data-table-search="companies" class="w-full rounded-xl border-slate-300 text-sm sm:w-72" placeholder="Zoek bedrijf, plan of status…">
            </div>
            <div class="space-y-3 md:hidden">
                @forelse($companies as $company)
                    <article class="rounded-xl border border-slate-200 bg-white p-4" data-search-row="companies" data-search-text="{{ strtolower($company->name.' '.$company->subscription_plan.' '.$company->subscription_status) }}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="truncate font-semibold text-slate-900">{{ $company->name }}</h3>
                                <p class="mt-0.5 text-xs text-slate-500">{{ ucfirst($company->subscription_plan ?? 'geen plan') }} · {{ $company->total_users }} gebruikers · {{ (int) $company->active_locations }} locaties</p>
                            </div>
                            <span class="inline-flex shrink-0 rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $company->subscription_status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $company->subscription_status ?? '—' }}</span>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-2 text-xs">
                            <div class="rounded-lg bg-slate-50 p-2.5"><span class="block text-slate-500">Facturatie</span><strong class="mt-0.5 block text-slate-800">{{ $company->billing_mode_label }}</strong></div>
                            <div class="rounded-lg bg-slate-50 p-2.5"><span class="block text-slate-500">Opslag</span><strong class="mt-0.5 block text-slate-800">{{ number_format((float) $company->storage_used_gb, 2, ',', '.') }} GB</strong></div>
                        </div>
                        <div class="mt-3 flex items-center gap-2">
                            <a href="{{ route('super-admin.companies.show', ['company' => $company, 'section' => 'settings']) }}" aria-label="{{ $company->name }} bewerken" title="Bedrijf bewerken" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.121 2.121 0 0 1 3 3L8.25 18.1 4 19.25l1.15-4.25L16.862 3.487Z"/><path stroke-linecap="round" d="m14.75 5.6 3 3"/></svg>
                                Bewerken
                            </a>
                            @if((int) auth()->user()->company_id !== (int) $company->id)
                                <form method="POST" action="{{ route('super-admin.companies.destroy', $company) }}" data-delete-company data-company-name="{{ $company->name }}">@csrf @method('DELETE')<input type="hidden" name="confirmation_name"><button aria-label="{{ $company->name }} verwijderen" title="Bedrijf verwijderen" class="flex h-10 w-10 items-center justify-center rounded-xl border border-red-200 bg-red-50 text-red-600 hover:bg-red-100"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V4h6v3m-8 0 1 13h8l1-13M10 11v5m4-5v5"/></svg></button></form>
                            @endif
                        </div>
                    </article>
                @empty
                    <p class="rounded-xl border border-dashed border-slate-200 p-6 text-center text-sm text-slate-500">Geen bedrijven gevonden.</p>
                @endforelse
            </div>
            <div class="hidden overflow-x-auto rounded-xl border border-slate-100 md:block">
                <table class="min-w-[1040px] w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b bg-slate-50">
                            <th class="py-3 px-3 pr-4">Bedrijf</th>
                            <th class="py-3 pr-4">Plan</th>
                            <th class="py-3 pr-4">Status</th>
                            <th class="py-3 pr-4">Facturatie</th>
                            <th class="py-3 pr-4">Einddatum</th>
                            <th class="py-3 pr-4">Users</th>
                            <th class="py-3 pr-4">Opslag (GB)</th>
                            <th class="py-3 pr-4">AI tokens</th>
                            <th class="py-3 pr-4">Locaties</th>
                            <th class="py-3 px-3">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                            <tr class="border-b border-slate-100 hover:bg-slate-50/60 transition-colors" data-search-row="companies" data-search-text="{{ strtolower($company->name.' '.$company->subscription_plan.' '.$company->subscription_status) }}">
                                <td class="py-3 px-3 pr-4 font-medium text-slate-900">{{ $company->name }}</td>
                                <td class="py-3 pr-4">{{ ucfirst($company->subscription_plan ?? 'geen') }}</td>
                                <td class="py-3 pr-4">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $company->subscription_status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $company->subscription_status ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-3 pr-4">{{ $company->billing_mode_label }}</td>
                                <td class="py-3 pr-4">{{ optional($company->subscription_ends_at)->format('d-m-Y') ?? 'onbeperkt' }}</td>
                                <td class="py-3 pr-4">{{ $company->total_users }} <span class="text-xs text-slate-500">(A: {{ $company->admin_users }}, M: {{ $company->employee_users }})</span></td>
                                <td class="py-3 pr-4">{{ number_format((float) $company->storage_used_gb, 2, ',', '.') }}</td>
                                <td class="py-3 pr-4">
                                    @if($aiUsage['enabled'])
                                        {{ number_format((int) ($aiUsage['by_company'][$company->id] ?? 0), 0, ',', '.') }}
                                    @else
                                        n.v.t.
                                    @endif
                                </td>
                                <td class="py-3 pr-4">{{ (int) $company->active_locations }}</td>
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-1.5">
                                        <a href="{{ route('super-admin.companies.show', ['company' => $company, 'section' => 'settings']) }}" aria-label="{{ $company->name }} bewerken" title="Bedrijf bewerken" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50 text-blue-700 hover:bg-blue-100">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.121 2.121 0 0 1 3 3L8.25 18.1 4 19.25l1.15-4.25L16.862 3.487Z"/><path stroke-linecap="round" d="m14.75 5.6 3 3"/></svg>
                                        </a>
                                        @if((int) auth()->user()->company_id !== (int) $company->id)
                                            <form method="POST" action="{{ route('super-admin.companies.destroy', $company) }}" data-delete-company data-company-name="{{ $company->name }}">@csrf @method('DELETE')<input type="hidden" name="confirmation_name"><button aria-label="{{ $company->name }} verwijderen" title="Bedrijf verwijderen" class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M9 7V4h6v3m-8 0 1 13h8l1-13M10 11v5m4-5v5"/></svg></button></form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="py-8 text-center text-slate-500">Geen bedrijven gevonden.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900 mb-2">Abonnementen verdeling</h2>
                <div class="space-y-1 text-sm">
                    @forelse($plans as $plan => $count)
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">{{ ucfirst(str_replace('_', ' ', $plan)) }}</span>
                            <span class="font-semibold text-slate-900">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="text-slate-500">Geen data</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
                <h2 class="text-base font-semibold text-slate-900 mb-2">AI gebruik</h2>
                @if($aiUsage['enabled'])
                    <p class="text-sm text-slate-600">Bron: <span class="font-medium">{{ $aiUsage['source_table'] }}</span></p>
                    <p class="text-xl font-bold text-slate-900 mt-1">{{ number_format($aiUsage['total_tokens'], 0, ',', '.') }} tokens</p>
                @else
                    <p class="text-sm text-slate-500">Nog geen AI token tracking tabel gevonden.</p>
                @endif
            </div>
        </div>
    </div>
    </section>

    <section data-tab-panel="usage" class="sa-tab-panel space-y-4 {{ $activeDashboardTab !== 'usage' ? 'hidden' : '' }}">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Gebruik per bedrijf</h2>
            <p class="text-sm text-slate-500">Zie of bedrijven lijsten hebben, toewijzingen doen en daadwerkelijk inzendingen maken.</p>
        </div>
    </div>

    @php
        $usageSummary = $usageOverview['summary'] ?? [];
        $usageCompanies = $usageOverview['companies'] ?? collect();
        $engagementFilters = [
            'all' => 'Alle',
            'power' => 'Zwaar gebruik',
            'active' => 'Actief',
            'low' => 'Weinig actief',
            'not_started' => 'Nog geen gebruik',
            'dormant' => 'Slapend',
            'inactive' => 'Geen lijsten',
        ];
        $badgeColors = [
            'slate' => 'bg-slate-100 text-slate-700 ring-slate-200',
            'amber' => 'bg-amber-100 text-amber-800 ring-amber-200',
            'orange' => 'bg-orange-100 text-orange-800 ring-orange-200',
            'violet' => 'bg-blue-100 text-blue-800 ring-blue-200',
            'blue' => 'bg-blue-100 text-blue-800 ring-blue-200',
            'emerald' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        ];
    @endphp

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-6">
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 shadow-sm">
            <p class="text-xs font-medium text-emerald-700">Actief / zwaar</p>
            <p class="mt-1 text-2xl font-bold text-emerald-900">{{ ($usageSummary['active'] ?? 0) }}</p>
        </div>
        <div class="rounded-2xl border border-blue-200 bg-blue-50/80 p-4 shadow-sm">
            <p class="text-xs font-medium text-blue-700">Weinig actief</p>
            <p class="mt-1 text-2xl font-bold text-blue-900">{{ $usageSummary['low'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-amber-200 bg-amber-50/80 p-4 shadow-sm">
            <p class="text-xs font-medium text-amber-800">Nog geen gebruik</p>
            <p class="mt-1 text-2xl font-bold text-amber-900">{{ $usageSummary['not_started'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-orange-200 bg-orange-50/80 p-4 shadow-sm">
            <p class="text-xs font-medium text-orange-800">Slapend (30d+)</p>
            <p class="mt-1 text-2xl font-bold text-orange-900">{{ $usageSummary['dormant'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium text-slate-500">Geen lijsten</p>
            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $usageSummary['inactive'] ?? 0 }}</p>
        </div>
        <div class="rounded-2xl border border-blue-200 bg-blue-50/80 p-4 shadow-sm">
            <p class="text-xs font-medium text-blue-700">Zwaar gebruik</p>
            <p class="mt-1 text-2xl font-bold text-blue-900">{{ $usageSummary['power'] ?? 0 }}</p>
        </div>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach($engagementFilters as $key => $label)
            <a href="{{ route('super-admin.dashboard', ['tab' => 'usage', 'usage_filter' => $key]) }}"
               class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold ring-1 transition {{ ($usageFilter ?? 'all') === $key ? 'bg-blue-600 text-white ring-blue-700' : 'bg-white text-slate-600 ring-slate-200 hover:ring-blue-300 hover:text-blue-800' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
    <input type="search" data-table-search="usage" class="w-full rounded-xl border-slate-300 text-sm sm:max-w-sm" placeholder="Zoek klant in gebruiksoverzicht…">

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-[1120px] w-full text-sm">
            <thead>
                <tr class="border-b border-slate-100 bg-slate-50 text-left text-slate-500">
                    <th class="py-3 px-3 pr-4 font-medium">Bedrijf</th>
                    <th class="py-3 pr-4 font-medium">Status</th>
                    <th class="py-3 pr-4 font-medium">Plan</th>
                    <th class="py-3 pr-4 font-medium">Lijsten</th>
                    <th class="py-3 pr-4 font-medium">Toegewezen</th>
                    <th class="py-3 pr-4 font-medium">Taken</th>
                    <th class="py-3 pr-4 font-medium">Inzendingen</th>
                    <th class="py-3 pr-4 font-medium">7 dagen</th>
                    <th class="py-3 pr-4 font-medium">30 dagen</th>
                    <th class="py-3 pr-4 font-medium">Medewerkers (30d)</th>
                    <th class="py-3 px-3 font-medium">Laatste activiteit</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usageCompanies as $company)
                    @php $u = $company->usage ?? []; @endphp
                    <tr class="border-b border-slate-50 hover:bg-slate-50/70 transition-colors" data-search-row="usage" data-search-text="{{ strtolower($company->name.' '.$company->subscription_plan.' '.($u['engagement_label'] ?? '')) }}">
                        <td class="py-3 px-3 pr-4">
                            <p class="font-semibold text-slate-900">{{ $company->name }}</p>
                            <p class="text-xs text-slate-500">{{ $company->total_users }} gebruikers · sinds {{ $company->created_at?->format('d-m-Y') }}</p>
                        </td>
                        <td class="py-3 pr-4">
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 {{ $badgeColors[$u['engagement_color'] ?? 'slate'] ?? $badgeColors['slate'] }}">
                                {{ $u['engagement_label'] ?? '—' }}
                            </span>
                        </td>
                        <td class="py-3 pr-4">
                            <span class="text-slate-700">{{ ucfirst($company->subscription_plan ?? 'geen') }}</span>
                            @if($company->subscription_status === 'trial' && $company->trial_ends_at)
                                <p class="text-[11px] text-amber-600">Trial t/m {{ $company->trial_ends_at->format('d-m') }}</p>
                            @endif
                        </td>
                        <td class="py-3 pr-4">
                            <span class="font-medium text-slate-900">{{ $u['active_lists_count'] ?? 0 }}</span>
                            <span class="text-slate-400">/</span>
                            <span class="text-slate-600">{{ $u['task_lists_count'] ?? 0 }}</span>
                            <p class="text-[11px] text-slate-500">actief / totaal</p>
                        </td>
                        <td class="py-3 pr-4">
                            {{ $u['assigned_lists_count'] ?? 0 }}
                            <span class="text-xs text-slate-400">({{ $u['assignments_count'] ?? 0 }} toew.)</span>
                        </td>
                        <td class="py-3 pr-4">{{ number_format($u['tasks_count'] ?? 0, 0, ',', '.') }}</td>
                        <td class="py-3 pr-4 font-medium">{{ number_format($u['submissions_total'] ?? 0, 0, ',', '.') }}</td>
                        <td class="py-3 pr-4 {{ ($u['submissions_7d'] ?? 0) > 0 ? 'font-semibold text-emerald-700' : 'text-slate-500' }}">
                            {{ $u['submissions_7d'] ?? 0 }}
                        </td>
                        <td class="py-3 pr-4">{{ $u['submissions_30d'] ?? 0 }}</td>
                        <td class="py-3 pr-4">{{ $u['active_users_30d'] ?? 0 }} <span class="text-xs text-slate-400">/ {{ $company->employee_users ?? 0 }}</span></td>
                        <td class="py-3 px-3 whitespace-nowrap text-slate-600">
                            @if(!empty($u['last_activity_at']))
                                {{ $u['last_activity_at']->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }}
                                <p class="text-[11px] text-slate-400">{{ $u['last_activity_at']->diffForHumans() }}</p>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="py-10 text-center text-slate-500">Geen bedrijven voor dit filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-xs text-slate-600 leading-relaxed">
        <p class="font-semibold text-slate-800">Legenda</p>
        <ul class="mt-2 grid gap-1 sm:grid-cols-2 lg:grid-cols-3">
            <li><strong>Geen lijsten</strong> — nog geen hoofdlijsten aangemaakt</li>
            <li><strong>Nog geen gebruik</strong> — lijsten bestaan, maar nog geen inzendingen</li>
            <li><strong>Slapend</strong> — wel historie, maar geen activiteit in 30 dagen</li>
            <li><strong>Weinig actief</strong> — 1–4 inzendingen in 30 dagen</li>
            <li><strong>Actief</strong> — 5+ inzendingen in 30 dagen of 2+ in 7 dagen</li>
            <li><strong>Zwaar gebruik</strong> — 20+ inzendingen in 30 dagen of 10+ in 7 dagen</li>
        </ul>
    </div>
    </section>

    <section data-tab-panel="monitoring" class="sa-tab-panel space-y-4 {{ $activeDashboardTab !== 'monitoring' ? 'hidden' : '' }}">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Monitoring</h2>
            <p class="text-sm text-slate-500">Platformbelasting, e-mailalerts bij drempels, errors en incident tickets.</p>
        </div>
        <form method="POST" action="{{ route('super-admin.platform-alerts.test') }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-800 hover:bg-blue-100">
                Test alert-mail nu
            </button>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Platformbelasting (live)</h3>
                <p class="text-xs text-slate-500">Actief = sessie in de laatste {{ $platformHealth['metrics']['session_window_minutes'] }} minuten. Inzendingen = bijgewerkt in de laatste {{ $platformHealth['metrics']['submissions_activity_window_minutes'] }} min (min. {{ config('platform_alerts.submissions_min_active_users') }} gebruikers voor alert). E-mail bij overschrijding drempel (max. 1× per {{ config('platform_alerts.cooldown_minutes') }} min per type).</p>
            </div>
            <p class="text-xs text-slate-400">Laatste check: {{ \Carbon\Carbon::parse($platformHealth['metrics']['checked_at'])->timezone(config('app.timezone'))->format('d-m-Y H:i:s') }}</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-3">
            @foreach($platformHealth['alerts'] as $alert)
                <div class="rounded-xl border p-4 {{ $alert['exceeded'] ? 'border-red-300 bg-red-50' : 'border-slate-200 bg-slate-50' }}">
                    <p class="text-xs font-medium text-slate-500">{{ $alert['label'] }}</p>
                    <p class="text-2xl font-bold mt-1 {{ $alert['exceeded'] ? 'text-red-700' : 'text-slate-900' }}">{{ number_format($alert['value'], 0, ',', '.') }}</p>
                    <p class="text-xs mt-1 {{ $alert['exceeded'] ? 'text-red-600 font-semibold' : 'text-slate-500' }}">
                        Drempel: {{ number_format($alert['threshold'], 0, ',', '.') }}
                        @if($alert['exceeded']) · alert @endif
                    </p>
                </div>
            @endforeach
        </div>
        <p class="text-xs text-slate-500 mt-3">
            Openstaande inzendingen (totaal, niet voor alerts): {{ number_format($platformHealth['metrics']['submissions_in_progress_total'] ?? 0, 0, ',', '.') }}
        </p>
        @if($recentPlatformAlerts->isNotEmpty())
            <div class="mt-4 border-t border-slate-100 pt-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Recent verstuurde alert-mails</p>
                <ul class="space-y-1 text-sm text-slate-600">
                    @foreach($recentPlatformAlerts as $log)
                        <li>
                            {{ config('platform_alerts.labels.'.$log->alert_key, $log->alert_key) }}:
                            {{ number_format($log->metric_value, 0, ',', '.') }} / {{ number_format($log->threshold, 0, ',', '.') }}
                            · {{ $log->sent_at->timezone(config('app.timezone'))->format('d-m-Y H:i') }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        @php
            $platformAlertRecipients = collect(explode(',', (string) config('platform_alerts.recipients')))
                ->map(fn ($email) => trim($email))
                ->filter()
                ->values();
            if ($platformAlertRecipients->isEmpty()) {
                $platformAlertRecipients = collect(config('app.super_admin_emails', []))->filter()->values();
            }
            $platformAlertRecipientsLabel = $platformAlertRecipients->isNotEmpty()
                ? $platformAlertRecipients->implode(', ')
                : 'niet geconfigureerd';
        @endphp
        <p class="text-xs text-slate-500 mt-4">
            Ontvangers: {{ $platformAlertRecipientsLabel }}.
            Productie: zet cron op <code class="text-blue-700">* * * * * php artisan schedule:run</code>.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <div class="flex items-center justify-between gap-3 mb-3">
                <div><h2 class="text-lg font-semibold text-slate-900">Gegroepeerde fouten</h2><p class="text-xs text-slate-500">Identieke fouten zijn samengevoegd; details staan in het ticket.</p></div>
                <span class="text-xs text-slate-500">Realtime update elke 8s</span>
            </div>
            <div class="mb-3 flex gap-2"><input id="sa-error-search" type="search" class="min-w-0 flex-1 rounded-xl border-slate-300 text-sm" placeholder="Zoek in fouten…"><select id="sa-error-level" class="rounded-xl border-slate-300 text-sm"><option value="">Alle niveaus</option><option value="ERROR">Error</option><option value="CRITICAL">Kritiek</option></select></div>
            <div id="sa-errors-list" class="space-y-2 max-h-96 overflow-auto">
                @forelse($recentErrors as $error)
                    <div class="sa-error-card rounded-xl border border-red-200 bg-red-50 p-3" data-error-level="{{ $error['level'] }}" data-error-text="{{ strtolower($error['message']) }}">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs text-red-700 font-semibold">{{ $error['level'] }} · {{ $error['count'] ?? 1 }}× · laatst {{ $error['last_seen'] ?? 'onbekend' }}</p>
                                <p class="text-sm text-slate-900 mt-1 break-words line-clamp-3">{{ $error['message'] }}</p>
                                <p class="mt-1 text-[11px] text-slate-500">Eerste keer: {{ $error['first_seen'] ?? 'onbekend' }} @if($error['company_id'] ?? null)· Klant #{{ $error['company_id'] }}@endif</p>
                            </div>
                            <button
                                class="shrink-0 rounded bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700 sa-ticket-btn"
                                data-fingerprint="{{ $error['fingerprint'] }}"
                                data-title="Automatisch error ticket"
                                data-message="{{ $error['message'] }}"
                                data-context="{{ $error['raw'] }}"
                                data-company-id="{{ $error['company_id'] ?? '' }}"
                                data-occurred="{{ $error['timestamp'] ?? '' }}"
                                data-request-url="{{ $error['request_url'] ?? '' }}"
                                data-http-method="{{ $error['http_method'] ?? '' }}"
                                data-user-agent="{{ $error['user_agent'] ?? '' }}"
                                data-device-type="{{ $error['device_type'] ?? '' }}"
                            >Ticket</button>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Geen recente fouten gevonden.</p>
                @endforelse
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <h2 class="text-lg font-semibold text-slate-900 mb-3">Incident tickets</h2>
            @php
                $activeTickets = $tickets->filter(fn($ticket) => $ticket->status !== 'ignored');
                $archivedTickets = $tickets->filter(fn($ticket) => $ticket->status === 'ignored');
            @endphp
            <div class="mb-3 inline-flex items-center rounded-xl border border-slate-200 bg-slate-50 p-1">
                <button type="button" class="sa-incident-tab-btn active rounded-lg px-3 py-1.5 text-xs font-semibold" data-incident-tab-target="active">Actief</button>
                <button type="button" class="sa-incident-tab-btn rounded-lg px-3 py-1.5 text-xs font-semibold" data-incident-tab-target="archive">Archief</button>
            </div>
            <div id="sa-incidents-active-list" class="sa-incident-tab-panel space-y-2 max-h-96 overflow-auto">
                @forelse($activeTickets as $ticket)
                    <div class="rounded-lg border border-slate-200 p-3" data-incident-ticket-id="{{ $ticket->id }}">
                        <p class="text-xs text-slate-500">#{{ $ticket->id }} · {{ $ticket->status }} · {{ optional($ticket->error_occurred_at ?? $ticket->created_at)->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }}</p>
                        <p class="text-sm font-medium text-slate-900 mt-1">{{ $ticket->title }}</p>
                        <p class="text-xs text-slate-600 mt-1 break-all">{{ $ticket->error_message }}</p>
                        <div class="mt-2 flex items-center gap-2">
                            <button type="button" class="rounded bg-slate-800 px-2 py-1 text-xs text-white hover:bg-slate-900 sa-ticket-open" data-ticket-id="{{ $ticket->id }}">Open</button>
                            <form method="POST" action="{{ route('super-admin.incidents.status.update', $ticket) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="{{ $ticket->status === 'resolved' ? 'open' : 'resolved' }}">
                                <button type="submit" class="rounded {{ $ticket->status === 'resolved' ? 'bg-amber-600 hover:bg-amber-700' : 'bg-emerald-600 hover:bg-emerald-700' }} px-2 py-1 text-xs text-white">
                                    {{ $ticket->status === 'resolved' ? 'Heropen' : 'Afronden' }}
                                </button>
                            </form>
                            @if($ticket->ai_analyzed_at)
                                <span class="text-[11px] text-emerald-700">AI geanalyseerd</span>
                            @endif
                            <form method="POST" action="{{ route('super-admin.incidents.status.update', $ticket) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="ignored">
                                <button type="submit" class="rounded bg-slate-500 px-2 py-1 text-xs text-white hover:bg-slate-600">
                                    Archiveer
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Nog geen tickets.</p>
                @endforelse
            </div>
            <div id="sa-incidents-archive-list" class="sa-incident-tab-panel hidden space-y-2 max-h-96 overflow-auto">
                @forelse($archivedTickets as $ticket)
                    <div class="rounded-lg border border-slate-200 p-3 bg-slate-50/80" data-incident-ticket-id="{{ $ticket->id }}">
                        <p class="text-xs text-slate-500">#{{ $ticket->id }} · gearchiveerd · {{ optional($ticket->error_occurred_at ?? $ticket->created_at)->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }}</p>
                        <p class="text-sm font-medium text-slate-900 mt-1">{{ $ticket->title }}</p>
                        <p class="text-xs text-slate-600 mt-1 break-all">{{ $ticket->error_message }}</p>
                        <div class="mt-2 flex items-center gap-2">
                            <button type="button" class="rounded bg-slate-800 px-2 py-1 text-xs text-white hover:bg-slate-900 sa-ticket-open" data-ticket-id="{{ $ticket->id }}">Open</button>
                            <form method="POST" action="{{ route('super-admin.incidents.status.update', $ticket) }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="open">
                                <button type="submit" class="rounded bg-amber-600 px-2 py-1 text-xs text-white hover:bg-amber-700">
                                    Herstel
                                </button>
                            </form>
                            @if($ticket->ai_analyzed_at)
                                <span class="text-[11px] text-emerald-700">AI geanalyseerd</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Geen gearchiveerde tickets.</p>
                @endforelse
            </div>
        </div>
    </div>
    </section>

    <section data-tab-panel="invoices" class="sa-tab-panel space-y-4 {{ $activeDashboardTab !== 'invoices' ? 'hidden' : '' }}">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Facturen</h2>
            <p class="text-sm text-slate-500">Laatste facturen over alle bedrijven, inclusief export.</p>
        </div>
    </div>
    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex flex-col gap-3 mb-3 sm:flex-row sm:items-center sm:justify-between">
            <div><h2 class="text-lg font-semibold text-slate-900">Facturen (alle bedrijven)</h2><input type="search" data-table-search="invoices" class="mt-2 w-full rounded-xl border-slate-300 text-sm sm:w-72" placeholder="Zoek factuur of bedrijf…"></div>
                            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs text-slate-500">Laatste 100 facturen</span>
                <a href="{{ route('super-admin.invoices.export.csv') }}"
                   class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700">
                    Export CSV (Excel)
                </a>
            </div>
        </div>
        @if(($invoices ?? collect())->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-[760px] w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-500 border-b">
                            <th class="py-2 pr-4">Factuurnr</th>
                            <th class="py-2 pr-4">Bedrijf</th>
                            <th class="py-2 pr-4">Datum</th>
                            <th class="py-2 pr-4">Omschrijving</th>
                            <th class="py-2 pr-4">Bedrag</th>
                            <th class="py-2">Actie</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr class="border-b border-slate-100" data-search-row="invoices" data-search-text="{{ strtolower($invoice->invoice_number.' '.($invoice->company?->name ?? '').' '.$invoice->description) }}">
                                <td class="py-2 pr-4 font-medium text-slate-900">{{ $invoice->invoice_number }}</td>
                                <td class="py-2 pr-4">{{ $invoice->company?->name ?? '-' }}</td>
                                <td class="py-2 pr-4">{{ optional($invoice->paid_at)->timezone('Europe/Amsterdam')->format('d-m-Y H:i') }}</td>
                                <td class="py-2 pr-4">{{ $invoice->description ?: 'TaskCheck abonnement' }}</td>
                                <td class="py-2 pr-4">{{ $invoice->currency }} {{ number_format((float) $invoice->amount, 2, ',', '.') }}</td>
                                <td class="py-2">
                                    <a href="{{ route('subscription.invoices.download', $invoice) }}" target="_blank"
                                       class="inline-flex items-center px-3 py-1.5 rounded-lg bg-blue-600 text-white text-xs font-semibold hover:bg-blue-700">
                                        PDF
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center">
                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white text-slate-400 shadow-sm ring-1 ring-slate-200">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14.25l6-6m4.5-3.493V21H4.5V4.757A48.108 48.108 0 0112 4.125c2.57 0 5.086.21 7.5.632zM9 17.25h6M9 10.5h.008v.008H9V10.5zm6 3.75h.008v.008H15v-.008z"/></svg>
                </div>
                <p class="mt-3 text-sm font-semibold text-slate-800">Nog geen facturen</p>
                <p class="mt-1 max-w-md text-xs text-slate-500">Betaalde abonnementen verschijnen hier automatisch en kunnen daarna als CSV of PDF worden geëxporteerd.</p>
            </div>
        @endif
    </div>
    </section>

    <section data-tab-panel="templates" class="sa-tab-panel space-y-4 {{ $activeDashboardTab !== 'templates' ? 'hidden' : '' }}">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Templates</h2>
            <p class="text-sm text-slate-500">Beheer globale templates en publicatie-acties.</p>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between gap-3 mb-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Global templates beheer</h2>
                    <p class="text-sm text-slate-500">Beheer centrale templates en publiceer updates naar bedrijven.</p>
                </div>
                <a href="{{ route('super-admin.templates.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                    Open templates
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Gebruik</p>
                    <p class="mt-1 text-sm text-slate-700">Maak of wijzig globale templates voor horeca, schoonmaak en andere branches.</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-slate-500">Publiceren</p>
                    <p class="mt-1 text-sm text-slate-700">Publiceer wijzigingen zodat gekoppelde bedrijfstemplates direct kunnen synchroniseren.</p>
                </div>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900 mb-3">Snelle acties</h3>
            <div class="space-y-2">
                <a href="{{ route('super-admin.templates.create') }}" class="flex items-center justify-between rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Nieuwe template
                    <span aria-hidden="true">+</span>
                </a>
                <a href="{{ route('super-admin.templates.index') }}" class="flex items-center justify-between rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                    Alle templates
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>
    </section>
</div>

<div id="sa-ticket-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-slate-900/60" data-ticket-close></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="w-full max-w-3xl rounded-2xl bg-white shadow-2xl border border-slate-200">
            <div class="flex items-center justify-between border-b border-slate-200 px-4 py-3">
                <h3 class="text-base font-semibold text-slate-900">Ticket details</h3>
                <button type="button" class="rounded p-1 text-slate-500 hover:bg-slate-100" data-ticket-close>✕</button>
            </div>
            <div class="p-4 space-y-3 max-h-[70vh] overflow-auto">
                <div id="sa-ticket-meta" class="text-sm text-slate-700"></div>
                <div id="sa-ticket-error" class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-slate-900 break-all"></div>
                <div id="sa-ticket-context" class="rounded-lg border border-slate-200 bg-slate-50 p-3 text-xs text-slate-700 whitespace-pre-wrap break-all"></div>
                <div>
                    <div class="flex items-center justify-between">
                        <h4 class="text-sm font-semibold text-slate-900">AI analyse</h4>
                        <button type="button" id="sa-ticket-ai-btn" class="rounded bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white hover:bg-blue-700">AI analyseer</button>
                    </div>
                    <div id="sa-ticket-ai-result" class="mt-2 rounded-lg border border-blue-200 bg-blue-50 p-3 text-sm text-slate-900 whitespace-pre-wrap break-all"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="sa-message-preview" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="sa-preview-title">
    <button type="button" class="absolute inset-0 bg-slate-900/60" data-preview-close aria-label="Sluiten"></button>
    <div class="absolute inset-0 flex items-center justify-center p-4"><div class="w-full max-w-xl overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl"><div class="flex items-center justify-between border-b border-slate-100 px-5 py-4"><div><p id="sa-preview-kind" class="text-xs font-semibold text-blue-600"></p><h3 id="sa-preview-title" class="text-lg font-semibold text-slate-900"></h3></div><button type="button" data-preview-close class="rounded-lg p-2 text-slate-500 hover:bg-slate-100">✕</button></div><div class="p-5"><div id="sa-preview-message" class="whitespace-pre-wrap rounded-xl bg-slate-50 p-4 text-sm leading-6 text-slate-700"></div><p class="mt-3 text-xs text-slate-500">Dit is een inhoudsvoorbeeld. De uiteindelijke e-mail gebruikt de TaskCheck-huisstijl.</p></div></div></div>
</div>

@push('scripts')
<style>
    .sa-tab-btn {
        color: #475569;
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all .2s ease;
    }
    .sa-tab-btn:hover {
        color: #1d4ed8;
        background-color: #eff6ff;
        border-color: #93c5fd;
    }
    .sa-tab-btn.active {
        color: #ffffff;
        background-color: #2563eb;
        border-color: #2563eb;
        box-shadow: 0 8px 16px rgba(37, 99, 235, 0.18);
    }
    .sa-incident-tab-btn {
        color: #475569;
        background-color: transparent;
        transition: all .2s ease;
    }
    .sa-incident-tab-btn.active {
        color: #ffffff;
        background-color: #334155;
    }
</style>
<script>
(() => {
    document.querySelectorAll('[data-delete-company]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const companyName = form.dataset.companyName ?? '';
            const confirmation = window.prompt(`Typ "${companyName}" om dit bedrijf en alle onderliggende gebruikers definitief te verwijderen.`);
            if (confirmation !== companyName) {
                event.preventDefault();
                if (confirmation !== null) window.alert('De bedrijfsnaam komt niet exact overeen. Het bedrijf is niet verwijderd.');
                return;
            }
            form.querySelector('input[name="confirmation_name"]').value = confirmation;
        });
    });

    const communicationCounts = @json($communicationCounts ?? []);
    const previewModal = document.getElementById('sa-message-preview');
    document.querySelectorAll('[data-preview-close]').forEach((button) => button.addEventListener('click', () => previewModal?.classList.add('hidden')));
    document.querySelectorAll('[data-preview-form]').forEach((button) => button.addEventListener('click', () => {
        const form = document.getElementById(button.dataset.previewForm);
        if (!form || !previewModal) return;
        document.getElementById('sa-preview-kind').textContent = button.dataset.previewKind || 'Voorbeeld';
        document.getElementById('sa-preview-title').textContent = form.querySelector('[name="subject"], [name="title"]')?.value || 'Nog geen titel ingevuld';
        document.getElementById('sa-preview-message').textContent = form.querySelector('[name="message"]')?.value || 'Nog geen bericht ingevuld.';
        previewModal.classList.remove('hidden');
    }));
    document.querySelectorAll('[data-confirm-send]').forEach((button) => button.addEventListener('click', (event) => {
        if (!window.confirm(button.dataset.confirmSend)) event.preventDefault();
    }));

    const mailForm = document.getElementById('broadcast-mail-form');
    const notificationForm = document.getElementById('broadcast-notification-form');
    const bindDraft = (form, key) => {
        if (!form) return;
        const fields = [...form.querySelectorAll('input[name], textarea[name], select[name]')].filter((field) => !['_token', 'send_mode'].includes(field.name));
        try {
            const draft = JSON.parse(localStorage.getItem(key) || '{}');
            fields.forEach((field) => { if (draft[field.name] === undefined) return; field.type === 'checkbox' ? field.checked = !!draft[field.name] : field.value = draft[field.name]; });
        } catch (_) {}
        const save = () => { const draft = {}; fields.forEach((field) => draft[field.name] = field.type === 'checkbox' ? field.checked : field.value); localStorage.setItem(key, JSON.stringify(draft)); };
        fields.forEach((field) => field.addEventListener('input', save));
        fields.forEach((field) => field.addEventListener('change', save));
    };
    bindDraft(mailForm, 'taskcheck-superadmin-mail-draft');
    bindDraft(notificationForm, 'taskcheck-superadmin-notification-draft');

    const updateMailCount = () => {
        const includeInactive = mailForm?.querySelector('[name="include_inactive"]')?.checked;
        const count = includeInactive ? communicationCounts.all_companies : communicationCounts.active_companies;
        const element = document.getElementById('mail-recipient-count'); if (element) element.textContent = count ?? 0;
    };
    const updateNotificationCount = () => {
        const audience = notificationForm?.querySelector('[name="audience"]')?.value || 'all';
        const inactive = notificationForm?.querySelector('[name="include_inactive"]')?.checked;
        const key = audience === 'admins' ? (inactive ? 'all_admins' : 'active_admins') : audience === 'employees' ? (inactive ? 'all_employees' : 'active_employees') : (inactive ? 'all_users' : 'active_users');
        const element = document.getElementById('notification-recipient-count'); if (element) element.textContent = communicationCounts[key] ?? 0;
    };
    mailForm?.querySelector('[name="include_inactive"]')?.addEventListener('change', updateMailCount);
    notificationForm?.querySelector('[name="audience"]')?.addEventListener('change', updateNotificationCount);
    notificationForm?.querySelector('[name="include_inactive"]')?.addEventListener('change', updateNotificationCount);
    updateMailCount(); updateNotificationCount();

    document.querySelectorAll('[data-table-search]').forEach((input) => input.addEventListener('input', () => {
        const group = input.dataset.tableSearch;
        const term = input.value.trim().toLowerCase();
        let visibleRows = 0;
        document.querySelectorAll(`[data-search-row="${group}"]`).forEach((row) => {
            const hidden = Boolean(term && !row.dataset.searchText.includes(term));
            row.classList.toggle('hidden', hidden);
            if (!hidden) visibleRows++;
        });
        document.querySelector(`[data-table-empty="${group}"]`)?.classList.toggle('hidden', visibleRows > 0);
    }));
    const tabPanels = Array.from(document.querySelectorAll('.sa-tab-panel'));
    const tabFromQuery = new URLSearchParams(window.location.search).get('tab');
    const allowedTabs = new Set(['overview', 'communications', 'companies', 'users', 'usage', 'monitoring', 'invoices', 'templates']);
    const serverTab = @json($activeDashboardTab);
    const initialTab = (tabFromQuery && allowedTabs.has(tabFromQuery))
        ? tabFromQuery
        : (allowedTabs.has(serverTab) ? serverTab : 'overview');

    const activateTab = (name) => {
        tabPanels.forEach((panel) => {
            const isActive = panel.dataset.tabPanel === name;
            panel.classList.toggle('hidden', !isActive);
        });
    };

    if (tabPanels.length) {
        activateTab(initialTab);
    }

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const feedUrl = @json(route('super-admin.errors.feed'));
    const ticketUrl = @json(route('super-admin.incidents.store'));
    const ticketShowUrlTemplate = @json(route('super-admin.incidents.show', ['incident' => '__ID__']));
    const ticketAnalyzeUrlTemplate = @json(route('super-admin.incidents.analyze', ['incident' => '__ID__']));
    const ticketStatusUrlTemplate = @json(route('super-admin.incidents.status.update', ['incident' => '__ID__']));
    const listEl = document.getElementById('sa-errors-list');
    const modalEl = document.getElementById('sa-ticket-modal');
    const ticketMetaEl = document.getElementById('sa-ticket-meta');
    const ticketErrorEl = document.getElementById('sa-ticket-error');
    const ticketContextEl = document.getElementById('sa-ticket-context');
    const ticketAiResultEl = document.getElementById('sa-ticket-ai-result');
    const ticketAiBtn = document.getElementById('sa-ticket-ai-btn');
    const incidentsListEl = document.getElementById('sa-incidents-active-list');
    let currentTicketId = null;

    const incidentTabButtons = Array.from(document.querySelectorAll('.sa-incident-tab-btn'));
    const incidentPanels = {
        active: document.getElementById('sa-incidents-active-list'),
        archive: document.getElementById('sa-incidents-archive-list'),
    };

    const activateIncidentTab = (tabName) => {
        incidentTabButtons.forEach((button) => {
            const isActive = button.dataset.incidentTabTarget === tabName;
            button.classList.toggle('active', isActive);
        });
        Object.entries(incidentPanels).forEach(([name, panel]) => {
            if (!panel) return;
            panel.classList.toggle('hidden', name !== tabName);
        });
    };

    if (incidentTabButtons.length) {
        incidentTabButtons.forEach((button) => {
            button.addEventListener('click', () => activateIncidentTab(button.dataset.incidentTabTarget));
        });
        activateIncidentTab('active');
    }

    const safeText = (value) => String(value ?? '')
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');

    const ticketShowUrl = (id) => ticketShowUrlTemplate.replace('__ID__', String(id));
    const ticketAnalyzeUrl = (id) => ticketAnalyzeUrlTemplate.replace('__ID__', String(id));
    const ticketStatusUrl = (id) => ticketStatusUrlTemplate.replace('__ID__', String(id));

    const renderIncidentTicketCard = (ticket, opts = {}) => {
        if (!ticket) return '';
        const ticketId = Number(ticket.id || 0);
        if (!ticketId) return '';

        const dateText = opts.dateText || new Date().toLocaleString('nl-NL', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
        }).replace(',', '');

        const escapedTitle = safeText(ticket.title || 'Incident ticket');
        const escapedMessage = safeText(ticket.error_message || '');
        const escapedStatus = safeText(ticket.status || 'open');

        return `
            <div class="rounded-lg border border-slate-200 p-3">
                <p class="text-xs text-slate-500">#${ticketId} · ${escapedStatus} · ${dateText}</p>
                <p class="text-sm font-medium text-slate-900 mt-1">${escapedTitle}</p>
                <p class="text-xs text-slate-600 mt-1 break-all">${escapedMessage}</p>
                <div class="mt-2 flex items-center gap-2">
                    <button type="button" class="rounded bg-slate-800 px-2 py-1 text-xs text-white hover:bg-slate-900 sa-ticket-open" data-ticket-id="${ticketId}">Open</button>
                    <form method="POST" action="${safeText(ticketStatusUrl(ticketId))}">
                        <input type="hidden" name="_token" value="${safeText(csrf || '')}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="status" value="resolved">
                        <button type="submit" class="rounded bg-emerald-600 px-2 py-1 text-xs text-white hover:bg-emerald-700">Afronden</button>
                    </form>
                    <form method="POST" action="${safeText(ticketStatusUrl(ticketId))}">
                        <input type="hidden" name="_token" value="${safeText(csrf || '')}">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="status" value="ignored">
                        <button type="submit" class="rounded bg-slate-500 px-2 py-1 text-xs text-white hover:bg-slate-600">Archiveer</button>
                    </form>
                    <span class="text-[11px] text-emerald-700">Net aangemaakt</span>
                </div>
            </div>
        `;
    };

    const prependIncidentTicket = (ticket, opts = {}) => {
        if (!incidentsListEl) return;

        const ticketId = Number(ticket?.id || 0);
        if (!ticketId) return;
        if (incidentsListEl.querySelector(`.sa-ticket-open[data-ticket-id="${ticketId}"]`)) {
            return;
        }

        const emptyState = incidentsListEl.querySelector('p.text-sm.text-slate-500');
        if (emptyState && emptyState.textContent?.includes('Nog geen tickets')) {
            emptyState.remove();
        }

        incidentsListEl.insertAdjacentHTML('afterbegin', renderIncidentTicketCard(ticket, opts));
        bindTicketButtons();
    };

    const openTicketModal = () => {
        modalEl.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };
    const closeTicketModal = () => {
        modalEl.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };
    document.querySelectorAll('[data-ticket-close]').forEach((el) => {
        el.addEventListener('click', closeTicketModal);
    });

    const bindTicketButtons = () => {
        document.querySelectorAll('.sa-ticket-btn').forEach((btn) => {
            btn.onclick = async () => {
                if (btn.dataset.sent === '1') return;
                const payload = {
                    fingerprint: btn.dataset.fingerprint,
                    title: btn.dataset.title || 'Automatisch error ticket',
                    error_message: btn.dataset.message || '',
                    context: btn.dataset.context || '',
                    error_occurred_at: btn.dataset.occurred || null,
                    company_id: btn.dataset.companyId ? Number(btn.dataset.companyId) : null,
                    request_url: btn.dataset.requestUrl || window.location.href,
                    http_method: btn.dataset.httpMethod || null,
                    user_agent: btn.dataset.userAgent || navigator.userAgent,
                    device_type: btn.dataset.deviceType || null,
                };
                const res = await fetch(ticketUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                });
                const data = await res.json();
                btn.dataset.sent = '1';
                btn.textContent = data.created ? 'Aangemaakt' : 'Bestond al';
                btn.classList.remove('bg-red-600', 'hover:bg-red-700');
                btn.classList.add('bg-slate-500');

                if (data.ticket) {
                    prependIncidentTicket(data.ticket, {
                        dateText: new Date().toLocaleString('nl-NL', {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit',
                        }).replace(',', ''),
                    });
                }
            };
        });

        document.querySelectorAll('.sa-ticket-open').forEach((btn) => {
            btn.onclick = async () => {
                const id = btn.dataset.ticketId;
                if (!id) return;
                currentTicketId = id;
                ticketMetaEl.textContent = 'Laden...';
                ticketErrorEl.textContent = '';
                ticketContextEl.textContent = '';
                ticketAiResultEl.textContent = '';
                openTicketModal();
                try {
                    const res = await fetch(ticketShowUrl(id), { headers: { 'Accept': 'application/json' } });
                    if (!res.ok) throw new Error('Kon ticket niet laden');
                    const data = await res.json();
                    const t = data.ticket;
                    const d = data.display || {};
                    ticketMetaEl.innerHTML = `
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div><span class="font-semibold">Ticket:</span> #${safeText(t.id)}</div>
                            <div><span class="font-semibold">Status:</span> ${safeText(t.status)}</div>
                            <div><span class="font-semibold">Tijd:</span> ${safeText(d.occurred_at ?? d.created_at ?? t.error_occurred_at ?? t.created_at)}</div>
                            <div><span class="font-semibold">Bedrijf:</span> ${safeText(t.company?.name ?? 'onbekend')}</div>
                            <div><span class="font-semibold">URL:</span> <span class="break-all">${safeText(t.request_url ?? 'onbekend')}</span></div>
                            <div><span class="font-semibold">Methode:</span> ${safeText(t.http_method ?? '-')}</div>
                            <div><span class="font-semibold">Device:</span> ${safeText(t.device_type ?? '-')}</div>
                            <div><span class="font-semibold">IP:</span> ${safeText(t.ip_address ?? '-')}</div>
                            <div class="sm:col-span-2"><span class="font-semibold">User agent:</span> <span class="break-all">${safeText(t.user_agent ?? '-')}</span></div>
                        </div>
                    `;
                    ticketErrorEl.textContent = t.error_message || '';
                    ticketContextEl.textContent = t.context || 'Geen extra context';
                    ticketAiResultEl.textContent = t.ai_analysis || 'Nog geen AI analyse. Klik op "AI analyseer".';
                } catch (error) {
                    ticketMetaEl.textContent = 'Ticket laden mislukt.';
                }
            };
        });
    };

    const renderErrors = (errors) => {
        if (!Array.isArray(errors) || errors.length === 0) {
            listEl.innerHTML = '<p class="text-sm text-slate-500">Geen recente fouten gevonden.</p>';
            return;
        }
        listEl.innerHTML = errors.map((error) => `
            <div class="sa-error-card rounded-xl border border-red-200 bg-red-50 p-3" data-error-level="${safeText(error.level || 'ERROR')}" data-error-text="${safeText((error.message || '').toLowerCase())}">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs text-red-700 font-semibold">${safeText(error.level)} · ${error.count || 1}× · laatst ${safeText(error.last_seen || 'onbekend')}</p>
                        <p class="text-sm text-slate-900 mt-1 break-words line-clamp-3">${safeText(error.message || '')}</p>
                        <p class="mt-1 text-[11px] text-slate-500">Eerste keer: ${safeText(error.first_seen || 'onbekend')}</p>
                    </div>
                    <button
                        class="shrink-0 rounded bg-red-600 px-2 py-1 text-xs text-white hover:bg-red-700 sa-ticket-btn"
                        data-fingerprint="${error.fingerprint}"
                        data-title="Automatisch error ticket"
                        data-message="${safeText(error.message || '')}"
                        data-context="${safeText(error.raw || '')}"
                        data-company-id="${error.company_id ?? ''}"
                        data-occurred="${error.timestamp ?? ''}"
                        data-request-url="${safeText(error.request_url || '')}"
                        data-http-method="${safeText(error.http_method || '')}"
                        data-user-agent="${safeText(error.user_agent || '')}"
                        data-device-type="${safeText(error.device_type || '')}"
                    >Ticket</button>
                </div>
            </div>
        `).join('');
        bindTicketButtons();
    };

    const filterErrors = () => {
        const term = (document.getElementById('sa-error-search')?.value || '').toLowerCase();
        const level = document.getElementById('sa-error-level')?.value || '';
        document.querySelectorAll('.sa-error-card').forEach((card) => {
            card.classList.toggle('hidden', !!((term && !card.dataset.errorText.includes(term)) || (level && card.dataset.errorLevel !== level)));
        });
    };
    document.getElementById('sa-error-search')?.addEventListener('input', filterErrors);
    document.getElementById('sa-error-level')?.addEventListener('change', filterErrors);

    const refresh = async () => {
        try {
            const res = await fetch(feedUrl, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) return;
            const data = await res.json();
            renderErrors(data.errors || []);
        } catch (e) {
            // noop
        }
    };

    ticketAiBtn?.addEventListener('click', async () => {
        if (!currentTicketId) return;
        ticketAiBtn.disabled = true;
        ticketAiBtn.textContent = 'Analyseren...';
        try {
            const res = await fetch(ticketAnalyzeUrl(currentTicketId), {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (!res.ok || !data.success) {
                ticketAiResultEl.textContent = data.message || 'AI analyse mislukt.';
            } else {
                ticketAiResultEl.textContent = data.analysis || 'Geen analyse teruggekregen.';
            }
        } catch (e) {
            ticketAiResultEl.textContent = 'AI analyse mislukt door netwerkfout.';
        } finally {
            ticketAiBtn.disabled = false;
            ticketAiBtn.textContent = 'AI analyseer';
        }
    });

    bindTicketButtons();
    setInterval(refresh, 8000);

    const copyFeedback = (btn, okLabel = 'Gekopieerd') => {
        const prev = btn.textContent;
        btn.textContent = okLabel;
        setTimeout(() => { btn.textContent = prev; }, 1500);
    };

    document.querySelectorAll('[data-copy-target]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const id = btn.getAttribute('data-copy-target');
            const el = id ? document.getElementById(id) : null;
            const text = el?.textContent?.trim() ?? '';
            if (!text) return;
            try {
                await navigator.clipboard.writeText(text);
                copyFeedback(btn);
            } catch {
                window.prompt('Kopieer deze URL:', text);
            }
        });
    });

    document.querySelectorAll('[data-copy-text]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const text = btn.getAttribute('data-copy-text') ?? '';
            if (!text) return;
            try {
                await navigator.clipboard.writeText(text);
                copyFeedback(btn);
            } catch {
                window.prompt('Kopieer deze HTML:', text);
            }
        });
    });
})();
</script>
@endpush
@endsection

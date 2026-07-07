@extends('layouts.super-admin')

@section('page-title', 'Super Admin Dashboard')

@section('content')
<div class="space-y-6">
    <div class="rounded-2xl bg-gradient-to-r from-violet-900 via-slate-900 to-slate-800 p-5 text-white shadow-lg sm:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold">Platformoverzicht</h1>
                <p class="mt-1 text-violet-100/90">Alle bedrijven, gebruikers, lijsten en inzendingen op 1 plek.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('super-admin.dashboard', ['tab' => 'usage']) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white hover:bg-emerald-500">
                    Gebruik
                </a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'monitoring']) }}" class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-3 py-2 text-sm font-semibold text-white hover:bg-white/20">
                    Monitoring
                </a>
                <a href="{{ route('super-admin.dashboard', ['tab' => 'templates']) }}" class="inline-flex items-center gap-2 rounded-lg bg-violet-600 px-3 py-2 text-sm font-semibold text-white hover:bg-violet-500">
                    Templates beheren
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-3 xl:grid-cols-9">
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-slate-500">Bedrijven</p>
            <p class="text-2xl font-bold text-slate-900">{{ $totals['companies'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-slate-500">Gebruikers</p>
            <p class="text-2xl font-bold text-slate-900">{{ $totals['users'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-slate-500">Admins</p>
            <p class="text-2xl font-bold text-slate-900">{{ $totals['admins'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-slate-500">Medewerkers</p>
            <p class="text-2xl font-bold text-slate-900">{{ $totals['employees'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-slate-500">Actieve locaties</p>
            <p class="text-2xl font-bold text-slate-900">{{ $totals['locations'] }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-slate-500">Opslag totaal (GB)</p>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($totals['storage_gb'], 2, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-slate-500">Takenlijsten</p>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($totals['task_lists'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-slate-500">Taken (items)</p>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($totals['tasks'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
            <p class="text-xs text-slate-500">Inzendingen</p>
            <p class="text-2xl font-bold text-slate-900">{{ number_format($totals['submissions'], 0, ',', '.') }}</p>
        </div>
    </div>

    <section data-tab-panel="communications" class="sa-tab-panel space-y-4 {{ $activeDashboardTab !== 'communications' ? 'hidden' : '' }}">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Communicatie</h2>
            <p class="text-sm text-slate-500">Stuur updates en maak nieuwe bedrijven met admin account.</p>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm lg:col-span-3">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center">
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
            <form method="POST" action="{{ route('super-admin.communications.broadcast-mail') }}" class="space-y-3">
                @csrf
                <label class="block text-sm font-medium text-slate-700">Onderwerp</label>
                <input
                    name="subject"
                    value="{{ old('subject') }}"
                    class="w-full rounded-xl border-slate-300 text-sm focus:border-violet-400 focus:ring-violet-400"
                    placeholder="Onderwerp"
                    required
                >
                <label class="block text-sm font-medium text-slate-700">Bericht</label>
                <textarea
                    name="message"
                    rows="5"
                    class="w-full rounded-xl border-slate-300 text-sm focus:border-violet-400 focus:ring-violet-400"
                    placeholder="Bericht naar alle bedrijven..."
                    required
                >{{ old('message') }}</textarea>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-1">
                    <label class="inline-flex items-center gap-2 text-sm text-slate-700 bg-slate-50 border border-slate-200 rounded-lg px-3 py-2">
                        <input type="checkbox" name="include_inactive" value="1" @checked(old('include_inactive'))>
                        Ook inactieve bedrijven mailen
                    </label>
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-violet-700 text-white px-5 py-2.5 text-sm font-semibold hover:bg-violet-800 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.77 59.77 0 0 1 3.27 20.875L6 12Zm0 0h7.5"/>
                        </svg>
                        Verstuur bulkmail
                    </button>
                </div>
            </form>
            <p class="text-xs text-slate-500 mt-2">Per bedrijf wordt de bedrijfsmail gebruikt, of anders de eerste actieve admin e-mail.</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm lg:col-span-3" id="mail-tracklinks">
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
                    <table class="min-w-full text-sm">
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
                                            <a href="{{ $link->tracking_url }}" class="font-semibold text-violet-700 underline" target="_blank" rel="noopener">{{ $link->mail_link_text }}</a>
                                        </p>
                                        <div class="flex flex-wrap gap-2 mb-2">
                                            <button type="button" class="rounded-lg border border-violet-200 bg-violet-50 px-2.5 py-1 text-xs font-semibold text-violet-800 hover:bg-violet-100" data-copy-text="{{ $link->mail_link_html }}">Kopieer HTML</button>
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
                <code class="text-violet-700">APP_URL</code> moet op je live domein staan (bijv. https://taskcheck.nl).
            </p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm lg:col-span-3">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3M9 12a3 3 0 100-6 3 3 0 000 6Zm7.5 8.25a7.5 7.5 0 10-15 0h15Z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Nieuw bedrijf + admin account</h2>
                    <p class="text-sm text-slate-500">Maak snel een organisatie aan met direct toegankelijke admin login.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('super-admin.companies.store') }}" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3">
                @csrf
                <input name="company_name" value="{{ old('company_name') }}" class="rounded-xl border-slate-300 text-sm focus:border-emerald-400 focus:ring-emerald-400" placeholder="Bedrijfsnaam" required>
                <input name="admin_name" value="{{ old('admin_name') }}" class="rounded-xl border-slate-300 text-sm focus:border-emerald-400 focus:ring-emerald-400" placeholder="Admin naam" required>
                <input name="admin_email" value="{{ old('admin_email') }}" type="email" class="rounded-xl border-slate-300 text-sm focus:border-emerald-400 focus:ring-emerald-400" placeholder="Admin e-mail" required>
                <input name="admin_password" type="text" class="rounded-xl border-slate-300 text-sm focus:border-emerald-400 focus:ring-emerald-400" placeholder="Tijdelijk wachtwoord (min 8)" required>
                <select name="subscription_plan" class="rounded-xl border-slate-300 text-sm focus:border-emerald-400 focus:ring-emerald-400" required>
                    @foreach(\App\Models\Organisation\Company::PLANS as $planKey => $plan)
                        <option value="{{ $planKey }}" @selected(old('subscription_plan') === $planKey)>{{ ucfirst($planKey) }}</option>
                    @endforeach
                </select>
                <label class="inline-flex items-center gap-2 rounded-xl border border-slate-300 px-3 py-2 text-sm bg-slate-50">
                    <input type="checkbox" name="billing_required" value="1" @checked(old('billing_required', true))>
                    Bedrijf moet maandelijks betalen
                </label>
                <input name="access_end_date" type="date" value="{{ old('access_end_date') }}" class="rounded-xl border-slate-300 text-sm focus:border-emerald-400 focus:ring-emerald-400">
                <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-violet-700 text-white px-4 py-2 text-sm font-semibold hover:bg-violet-800 shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Aanmaken
                </button>
                <input name="company_phone" value="{{ old('company_phone') }}" class="rounded-xl border-slate-300 text-sm focus:border-emerald-400 focus:ring-emerald-400" placeholder="Telefoon (optioneel)">
                <input name="company_address" value="{{ old('company_address') }}" class="rounded-xl border-slate-300 text-sm focus:border-emerald-400 focus:ring-emerald-400" placeholder="Adres (optioneel)">
                <input name="company_website" value="{{ old('company_website') }}" class="rounded-xl border-slate-300 text-sm focus:border-emerald-400 focus:ring-emerald-400" placeholder="Website (optioneel)">
            </form>
            <p class="text-xs text-slate-500 mt-2">Bij niet-betalen is einddatum verplicht. Bij betalen loopt het door (maandelijks factureren via je normale billing-flow).</p>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm lg:col-span-3">
            <div class="flex items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3h6m5.25 9H5.25A2.25 2.25 0 0 1 3 18V6A2.25 2.25 0 0 1 5.25 3.75h13.5A2.25 2.25 0 0 1 21 6v12a2.25 2.25 0 0 1-2.25 2.25Z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Meldingenconsole</h2>
                        <p class="text-sm text-slate-500">Stuur in-app platformmeldingen over updates, storingen en fixes.</p>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('super-admin.communications.broadcast-notification') }}" class="space-y-3">
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

                <div class="flex justify-end">
                    <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-700 text-white px-5 py-2.5 text-sm font-semibold hover:bg-blue-800 shadow-sm">
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
    </div>
    </section>

    <section data-tab-panel="companies" class="sa-tab-panel space-y-4 {{ $activeDashboardTab !== 'companies' ? 'hidden' : '' }}">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Bedrijven</h2>
            <p class="text-sm text-slate-500">Abonnementen, status en gebruik per organisatie beheren.</p>
        </div>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-slate-900">Bedrijven overzicht</h2>
                <span class="text-xs text-slate-500">Laatste updates eerst</span>
            </div>
            <div class="overflow-x-auto rounded-xl border border-slate-100">
                <table class="min-w-full text-sm">
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
                            <tr class="border-b border-slate-100 hover:bg-slate-50/60 transition-colors">
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
                                    <details>
                                        <summary class="cursor-pointer inline-flex items-center rounded-lg bg-violet-50 px-2.5 py-1.5 text-xs font-semibold text-violet-700 hover:bg-violet-100">Beheer</summary>
                                        <form method="POST" action="{{ route('super-admin.companies.subscription.update', $company) }}" class="mt-2 space-y-2 min-w-[240px] rounded-xl border border-slate-200 bg-white p-3 shadow-sm">
                                            @csrf
                                            @method('PUT')
                                            <select name="subscription_plan" class="w-full rounded-lg border-slate-300 text-xs">
                                                @foreach(\App\Models\Organisation\Company::PLANS as $planKey => $plan)
                                                    <option value="{{ $planKey }}" @selected($company->subscription_plan === $planKey)>{{ ucfirst($planKey) }}</option>
                                                @endforeach
                                            </select>
                                            <select name="subscription_status" class="w-full rounded-lg border-slate-300 text-xs">
                                                @foreach(['trial','active','cancelled','expired'] as $status)
                                                    <option value="{{ $status }}" @selected($company->subscription_status === $status)>{{ $status }}</option>
                                                @endforeach
                                            </select>
                                            <label class="inline-flex items-center gap-2 text-xs"><input type="checkbox" name="billing_required" value="1" @checked($company->billing_required)> betalen</label>
                                            <input type="date" name="subscription_ends_at" value="{{ optional($company->subscription_ends_at)->format('Y-m-d') }}" class="w-full rounded-lg border-slate-300 text-xs">
                                            <label class="inline-flex items-center gap-2 text-xs"><input type="checkbox" name="is_active" value="1" @checked($company->is_active)> actief</label>
                                            <button class="w-full rounded-lg bg-slate-800 text-white text-xs py-2 font-semibold hover:bg-slate-900">Opslaan</button>
                                        </form>
                                    </details>
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

        <div class="space-y-4">
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
            'violet' => 'bg-violet-100 text-violet-800 ring-violet-200',
            'blue' => 'bg-blue-100 text-blue-800 ring-blue-200',
            'emerald' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
        ];
    @endphp

    <div class="grid grid-cols-2 gap-3 lg:grid-cols-6">
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4 shadow-sm">
            <p class="text-xs font-medium text-emerald-700">Actief / zwaar</p>
            <p class="mt-1 text-2xl font-bold text-emerald-900">{{ ($usageSummary['active'] ?? 0) }}</p>
        </div>
        <div class="rounded-2xl border border-violet-200 bg-violet-50/80 p-4 shadow-sm">
            <p class="text-xs font-medium text-violet-700">Weinig actief</p>
            <p class="mt-1 text-2xl font-bold text-violet-900">{{ $usageSummary['low'] ?? 0 }}</p>
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
               class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold ring-1 transition {{ ($usageFilter ?? 'all') === $key ? 'bg-violet-700 text-white ring-violet-700' : 'bg-white text-slate-600 ring-slate-200 hover:ring-violet-300 hover:text-violet-800' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="min-w-full text-sm">
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
                    <tr class="border-b border-slate-50 hover:bg-slate-50/70 transition-colors">
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
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-4 py-2 text-sm font-semibold text-violet-800 hover:bg-violet-100">
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
            Productie: zet cron op <code class="text-violet-700">* * * * * php artisan schedule:run</code>.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-slate-900">Recente errors</h2>
                <span class="text-xs text-slate-500">Realtime update elke 8s</span>
            </div>
            <div id="sa-errors-list" class="space-y-2 max-h-96 overflow-auto">
                @forelse($recentErrors as $error)
                    <div class="rounded-lg border border-red-200 bg-red-50 p-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-xs text-red-700 font-semibold">{{ $error['level'] }} · {{ $error['timestamp'] ?? 'onbekend' }}</p>
                                <p class="text-sm text-slate-900 mt-1 break-all">{{ $error['message'] }}</p>
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
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-slate-900">Facturen (alle bedrijven)</h2>
            <div class="flex items-center gap-2">
                <span class="text-xs text-slate-500">Laatste 100 facturen</span>
                <a href="{{ route('super-admin.invoices.export.csv') }}"
                   class="inline-flex items-center px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700">
                    Export CSV (Excel)
                </a>
            </div>
        </div>
        @if(($invoices ?? collect())->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
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
                            <tr class="border-b border-slate-100">
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
            <p class="text-sm text-slate-500">Nog geen facturen gevonden.</p>
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
                <a href="{{ route('super-admin.templates.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-violet-700 px-4 py-2 text-sm font-semibold text-white hover:bg-violet-800">
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
                        <button type="button" id="sa-ticket-ai-btn" class="rounded bg-violet-700 px-3 py-1.5 text-xs font-semibold text-white hover:bg-violet-800">AI analyseer</button>
                    </div>
                    <div id="sa-ticket-ai-result" class="mt-2 rounded-lg border border-violet-200 bg-violet-50 p-3 text-sm text-slate-900 whitespace-pre-wrap break-all"></div>
                </div>
            </div>
        </div>
    </div>
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
        color: #5b21b6;
        background-color: #f5f3ff;
        border-color: #c4b5fd;
    }
    .sa-tab-btn.active {
        color: #ffffff;
        background-color: #6d28d9;
        border-color: #6d28d9;
        box-shadow: 0 8px 16px rgba(109, 40, 217, 0.2);
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
    const tabPanels = Array.from(document.querySelectorAll('.sa-tab-panel'));
    const tabFromQuery = new URLSearchParams(window.location.search).get('tab');
    const allowedTabs = new Set(['communications', 'companies', 'usage', 'monitoring', 'invoices', 'templates']);
    const serverTab = @json($activeDashboardTab);
    const initialTab = (tabFromQuery && allowedTabs.has(tabFromQuery))
        ? tabFromQuery
        : (allowedTabs.has(serverTab) ? serverTab : 'communications');

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
            <div class="rounded-lg border border-red-200 bg-red-50 p-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs text-red-700 font-semibold">${error.level} · ${error.timestamp ?? 'onbekend'}</p>
                        <p class="text-sm text-slate-900 mt-1 break-all">${(error.message || '').replace(/</g, '&lt;')}</p>
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


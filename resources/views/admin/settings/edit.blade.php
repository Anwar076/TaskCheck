@extends('layouts.admin')

@section('page-title', 'Organisatie-instellingen')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <span class="text-slate-900 font-semibold truncate">Instellingen</span>
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-semibold truncate">Organisatie</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        @php
            $savedDepartments = collect(old('departments', $company->departments ?? []))
                ->filter(fn($item) => is_string($item) && trim($item) !== '')
                ->values()
                ->all();
            $workingHours = $company->normalizedWorkingHours();
            $reportingEnabled = old('reporting_enabled', $company->reporting_enabled) ? true : false;
            $reportingFrequency = old('reporting_frequency', $company->reporting_frequency ?? \App\Models\Organisation\Company::REPORTING_FREQUENCY_DAILY);
            $reportingTime = old('reporting_send_time', $company->reporting_send_time ? substr((string) $company->reporting_send_time, 0, 5) : '09:00');
            $reportingWeeklyDay = (int) old('reporting_weekly_day', $company->reporting_weekly_day ?? 1);
        @endphp
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Organisatie-instellingen</h1>
                        <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Beheer de gegevens van uw bedrijf of organisatie</p>
                    </div>
                </div>
            </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 pb-6 pt-4 sm:px-8 sm:pb-8 sm:pt-5">
                @include('admin.settings.tabs', ['activeTab' => 'settings'])

                <div class="mb-6 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    <strong>Belangrijk:</strong> Organisatienaam, adres, telefoon en e-mail zijn verplicht voor correcte facturen.
                </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-8" data-onboarding-target="org-form">
        @csrf
        @method('PUT')

        <!-- Logo & Naam + contact (onboarding highlight) -->
        <div class="scroll-mt-28 rounded-xl" data-onboarding-target="org-profile">
        <!-- Logo & Naam Sectie -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Organisatieprofiel
            </h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Logo upload -->
                <div>
                    <x-form-label help="Upload het logo van je organisatie. Dit kan op facturen en in de app verschijnen.">Logo</x-form-label>
                    <div class="flex flex-col items-start gap-4">
                        <label
                            for="logo"
                            id="logo-dropzone"
                            class="group flex w-full max-w-md cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50/80 px-6 py-8 text-center transition hover:border-blue-300 hover:bg-blue-50/60 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100"
                        >
                            <input type="file" name="logo" id="logo" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" class="sr-only">

                            <div class="relative mb-4 flex h-24 w-24 items-center justify-center overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                                <div class="text-center p-4 {{ $company->logo_path ? 'hidden' : '' }}" id="logo-placeholder">
                                    <svg class="mx-auto h-10 w-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <img src="{{ $company->logo_path ? Storage::url($company->logo_path) : '' }}" alt="Logo" class="h-full w-full object-contain p-2 {{ $company->logo_path ? '' : 'hidden' }}" id="logo-preview">
                            </div>

                            <span class="text-sm font-semibold text-slate-900">Klik om een logo te kiezen</span>
                            <span class="mt-1 text-sm text-slate-500">of sleep een afbeelding hierheen</span>
                            <span class="mt-3 inline-flex items-center rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-500 ring-1 ring-slate-200">
                                PNG, JPG, GIF of WEBP · max. 2MB
                            </span>
                        </label>
                        @if($company->logo_path)
                        <input type="hidden" name="remove_logo" value="0" id="remove_logo">
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button" id="remove_logo_button" class="inline-flex items-center rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 transition-colors hover:bg-red-100">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span data-logo-remove-button-text>Verwijderen</span>
                            </button>
                            <p id="logo-remove-status" class="hidden text-xs font-medium text-red-600">Logo wordt verwijderd zodra je opslaat.</p>
                        </div>
                        @endif
                        <p class="text-xs text-gray-500">Aanbevolen formaat: 256×256px. Vierkante logo’s tonen het mooist.</p>
                        @error('logo')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <!-- Naam & Domain -->
                <div class="space-y-6">
                    <div>
                        <x-form-label for="name" help="De officiële naam van je bedrijf. Deze staat op facturen en in de app.">Organisatienaam <span class="text-red-500">*</span></x-form-label>
                        <input type="text" name="name" id="name" required
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            value="{{ old('name', $company->name) }}" placeholder="bijv. JAYAS Organisatie">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        </div>

        <div class="scroll-mt-28 rounded-xl" data-onboarding-target="org-contact">
        <!-- Contactgegevens Sectie -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Contactgegevens
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <x-form-label for="address" help="Het vestigingsadres van je organisatie. Verplicht voor correcte facturatie.">Adres <span class="text-red-500">*</span></x-form-label>
                    <input type="text" name="address" id="address" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        value="{{ old('address', $company->address) }}" placeholder="Straat, nummer, postcode, plaats">
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <x-form-label for="phone" help="Het contacttelefoonnummer van je organisatie. Alleen cijfers, maximaal 15 tekens.">Telefoon <span class="text-red-500">*</span></x-form-label>
                    <input type="text" name="phone" id="phone" required
                        inputmode="numeric" pattern="[0-9]*" maxlength="15"
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        value="{{ old('phone', preg_replace('/\D+/', '', (string) $company->phone)) }}" placeholder="Bijv. 0612345678">
                    <p class="mt-1 text-xs text-gray-500">Alleen cijfers, maximaal 15 tekens.</p>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <x-form-label for="email" help="Het algemene e-mailadres van je organisatie voor facturen en contact.">E-mail <span class="text-red-500">*</span></x-form-label>
                    <input type="email" name="email" id="email" required
                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        value="{{ old('email', $company->email) }}" placeholder="info@bedrijf.nl">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        </div>

        <!-- Afdelingen Sectie -->
        <div class="border-b border-gray-200 pb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Afdelingen
            </h2>
            <div>
                <x-form-label for="departments_text" help="Voeg afdelingen toe zoals Keuken of Schoonmaak. Je kiest ze later bij het aanmaken van gebruikers.">Welke afdelingen zijn er binnen jouw bedrijf?</x-form-label>
                <div>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <input type="text" id="department_input"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Bijv. Operaties">
                        <button type="button" id="add_department_btn" class="inline-flex items-center justify-center w-full sm:w-auto min-w-[140px] px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors min-h-[44px] touch-manipulation whitespace-nowrap">
                            Voeg toe
                        </button>
                    </div>

                    <div id="departments_tags" class="mt-3 flex flex-wrap gap-2">
                        @foreach($savedDepartments as $department)
                            <span class="department-tag inline-flex items-center gap-2 rounded-full bg-blue-50 text-blue-800 px-3 py-1 text-sm" data-value="{{ $department }}">
                                {{ $department }}
                                <button type="button" class="remove-department text-blue-600 hover:text-blue-800" aria-label="Verwijderen">&times;</button>
                            </span>
                        @endforeach
                    </div>

                    <div id="departments_inputs">
                        @foreach($savedDepartments as $department)
                            <input type="hidden" name="departments[]" value="{{ $department }}">
                        @endforeach
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500">Voeg afdelingen één voor één toe. Deze lijst wordt gebruikt bij gebruiker toevoegen/bewerken.</p>
                @error('departments_text')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('departments')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Werktijden Sectie -->
        <div class="scroll-mt-28 rounded-xl border-b border-gray-200 pb-6" data-onboarding-target="org-working-hours">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/>
                </svg>
                Werktijden agenda
            </h2>
            <p class="mb-4 text-sm text-gray-500">Deze tijden bepalen welke uren zichtbaar zijn in de agenda. Standaard is elke dag 06:00 tot 21:00.</p>

            @php
                $calendarTimeMode = old('calendar_time_mode', $company->calendar_time_mode ?? \App\Models\Organisation\Company::CALENDAR_TIME_MODE_WORKING_HOURS);
            @endphp
            <div class="mb-4 grid gap-3 sm:grid-cols-2">
                <label class="flex cursor-pointer gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition-colors hover:border-blue-200">
                    <input type="radio"
                           name="calendar_time_mode"
                           value="{{ \App\Models\Organisation\Company::CALENDAR_TIME_MODE_WORKING_HOURS }}"
                           @checked($calendarTimeMode === \App\Models\Organisation\Company::CALENDAR_TIME_MODE_WORKING_HOURS)
                           class="mt-1 border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span>
                        <span class="block text-sm font-semibold text-slate-900">Op basis van werktijden</span>
                        <span class="mt-1 block text-xs leading-5 text-slate-500">Toon de agenda van de vroegste start tot de laatste eindtijd in de week.</span>
                    </span>
                </label>
                <label class="flex cursor-pointer gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition-colors hover:border-blue-200">
                    <input type="radio"
                           name="calendar_time_mode"
                           value="{{ \App\Models\Organisation\Company::CALENDAR_TIME_MODE_24_HOURS }}"
                           @checked($calendarTimeMode === \App\Models\Organisation\Company::CALENDAR_TIME_MODE_24_HOURS)
                           class="mt-1 border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span>
                        <span class="block text-sm font-semibold text-slate-900">24 uur tonen</span>
                        <span class="mt-1 block text-xs leading-5 text-slate-500">Toon altijd 00:00 tot 24:00; vrije tijd blijft grijs gemarkeerd.</span>
                    </span>
                </label>
            </div>
            @error('calendar_time_mode')
                <p class="mb-4 text-sm text-red-600">{{ $message }}</p>
            @enderror

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                <div class="grid grid-cols-[1fr_7rem_7rem_5rem] gap-3 border-b border-slate-100 bg-slate-50 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-slate-500">
                    <span>Dag</span>
                    <span>Start</span>
                    <span>Eind</span>
                    <span class="text-right">Actief</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach(\App\Models\Organisation\Company::WEEKDAYS as $dayKey => $dayLabel)
                        @php
                            $dayHours = $workingHours[$dayKey] ?? \App\Models\Organisation\Company::defaultWorkingHours()[$dayKey];
                            $enabledValue = old("working_hours.$dayKey.enabled", $dayHours['enabled'] ? '1' : '0');
                        @endphp
                        <div class="grid grid-cols-1 gap-3 px-4 py-3 sm:grid-cols-[1fr_7rem_7rem_5rem] sm:items-center">
                            <div class="font-medium text-slate-800">{{ $dayLabel }}</div>
                            <div>
                                <label for="working-hours-{{ $dayKey }}-start" class="mb-1 block text-xs font-medium text-slate-500 sm:hidden">Start</label>
                                <input type="time"
                                       id="working-hours-{{ $dayKey }}-start"
                                       name="working_hours[{{ $dayKey }}][start]"
                                       value="{{ old("working_hours.$dayKey.start", $dayHours['start']) }}"
                                       class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error("working_hours.$dayKey.start")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="working-hours-{{ $dayKey }}-end" class="mb-1 block text-xs font-medium text-slate-500 sm:hidden">Eind</label>
                                <input type="time"
                                       id="working-hours-{{ $dayKey }}-end"
                                       name="working_hours[{{ $dayKey }}][end]"
                                       value="{{ old("working_hours.$dayKey.end", $dayHours['end']) }}"
                                       class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error("working_hours.$dayKey.end")
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <label class="flex items-center justify-between gap-3 sm:justify-end">
                                <span class="text-sm text-slate-600 sm:hidden">Dag tonen</span>
                                <input type="hidden" name="working_hours[{{ $dayKey }}][enabled]" value="0">
                                <input type="checkbox"
                                       name="working_hours[{{ $dayKey }}][enabled]"
                                       value="1"
                                       @checked((string) $enabledValue === '1')
                                       class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="scroll-mt-28 rounded-xl border-b border-gray-200 pb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-3 11H6a2 2 0 01-2-2V7a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2z"/>
                </svg>
                Rapportage via e-mail
            </h2>
            <p class="mb-4 text-sm text-gray-500">Kies of je een samenvatting wilt ontvangen per e-mail, dagelijks of wekelijks op een vast tijdstip.</p>

            <div class="space-y-4">
                <label class="inline-flex w-full items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                    <input type="hidden" name="reporting_enabled" value="0">
                    <input id="reporting-enabled" type="checkbox" name="reporting_enabled" value="1" @checked($reportingEnabled) class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="text-sm font-medium text-slate-800">Rapportage e-mails ontvangen</span>
                </label>

                <div id="reporting-settings-fields" class="rounded-xl border border-slate-200 bg-white p-4 md:p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <x-form-label for="reporting_frequency">Frequentie</x-form-label>
                        <select id="reporting_frequency" name="reporting_frequency" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="{{ \App\Models\Organisation\Company::REPORTING_FREQUENCY_DAILY }}" @selected($reportingFrequency === \App\Models\Organisation\Company::REPORTING_FREQUENCY_DAILY)>Dagelijks</option>
                            <option value="{{ \App\Models\Organisation\Company::REPORTING_FREQUENCY_WEEKLY }}" @selected($reportingFrequency === \App\Models\Organisation\Company::REPORTING_FREQUENCY_WEEKLY)>Wekelijks</option>
                        </select>
                        @error('reporting_frequency')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <x-form-label for="reporting_send_time">Tijdstip</x-form-label>
                        <input type="time" id="reporting_send_time" name="reporting_send_time" value="{{ $reportingTime }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Tijdzone: Nederland (Europe/Amsterdam)</p>
                        @error('reporting_send_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="reporting-weekly-day-wrap" class="md:pt-0">
                        <x-form-label for="reporting_weekly_day">Dag (wekelijks)</x-form-label>
                        <select id="reporting_weekly_day" name="reporting_weekly_day" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="1" @selected($reportingWeeklyDay === 1)>Maandag</option>
                            <option value="2" @selected($reportingWeeklyDay === 2)>Dinsdag</option>
                            <option value="3" @selected($reportingWeeklyDay === 3)>Woensdag</option>
                            <option value="4" @selected($reportingWeeklyDay === 4)>Donderdag</option>
                            <option value="5" @selected($reportingWeeklyDay === 5)>Vrijdag</option>
                            <option value="6" @selected($reportingWeeklyDay === 6)>Zaterdag</option>
                            <option value="7" @selected($reportingWeeklyDay === 7)>Zondag</option>
                        </select>
                        @error('reporting_weekly_day')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Opslaan -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4">
            <p class="text-sm text-gray-500">Wijzigingen worden direct opgeslagen voor alle gebruikers van uw organisatie.</p>
            <button type="submit" data-onboarding-target="org-save" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-colors min-h-[44px] touch-manipulation">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Opslaan
            </button>
        </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logo-preview');
    const logoPlaceholder = document.getElementById('logo-placeholder');
    const logoDropzone = document.getElementById('logo-dropzone');
    const removeLogoInput = document.getElementById('remove_logo');
    const removeLogoButton = document.getElementById('remove_logo_button');
    const removeLogoStatus = document.getElementById('logo-remove-status');
    const removeLogoButtonText = document.querySelector('[data-logo-remove-button-text]');

    function showLogoPreview(file) {
        if (!file || !file.type.startsWith('image/')) return;

        if (removeLogoInput) {
            removeLogoInput.value = '0';
        }
        if (removeLogoStatus) {
            removeLogoStatus.classList.add('hidden');
        }
        if (removeLogoButtonText) {
            removeLogoButtonText.textContent = 'Verwijderen';
        }
        if (removeLogoButton) {
            removeLogoButton.classList.remove('border-red-300', 'bg-red-100');
        }
        if (logoDropzone) {
            logoDropzone.classList.remove('border-red-200', 'bg-red-50/50');
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            if (logoPreview) {
                logoPreview.src = e.target.result;
                logoPreview.classList.remove('hidden');
            }
            if (logoPlaceholder) {
                logoPlaceholder.classList.add('hidden');
            }
        };
        reader.readAsDataURL(file);
    }

    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            showLogoPreview(file);
        });
    }

    if (logoDropzone && logoInput) {
        ['dragenter', 'dragover'].forEach(function(eventName) {
            logoDropzone.addEventListener(eventName, function(event) {
                event.preventDefault();
                logoDropzone.classList.add('border-blue-500', 'bg-blue-50', 'ring-4', 'ring-blue-100');
            });
        });

        ['dragleave', 'drop'].forEach(function(eventName) {
            logoDropzone.addEventListener(eventName, function(event) {
                event.preventDefault();
                logoDropzone.classList.remove('border-blue-500', 'bg-blue-50', 'ring-4', 'ring-blue-100');
            });
        });

        logoDropzone.addEventListener('drop', function(event) {
            const file = event.dataTransfer.files[0];
            if (!file) return;

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            logoInput.files = dataTransfer.files;
            logoInput.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }

    if (removeLogoButton) {
        removeLogoButton.addEventListener('click', function() {
            if (removeLogoInput) {
                removeLogoInput.value = '1';
            }
            if (logoInput) {
                logoInput.value = '';
            }
            if (logoPreview) {
                logoPreview.src = '';
                logoPreview.classList.add('hidden');
            }
            if (logoPlaceholder) {
                logoPlaceholder.classList.remove('hidden');
            }
            if (removeLogoStatus) {
                removeLogoStatus.classList.remove('hidden');
            }
            if (removeLogoButtonText) {
                removeLogoButtonText.textContent = 'Verwijderd na opslaan';
            }
            removeLogoButton.classList.add('border-red-300', 'bg-red-100');
            if (logoDropzone) {
                logoDropzone.classList.add('border-red-200', 'bg-red-50/50');
            }
        });
    }

    const phoneInput = document.getElementById('phone');
    if (phoneInput) {
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D+/g, '').slice(0, 15);
        });
    }

    const departmentInput = document.getElementById('department_input');
    const addDepartmentBtn = document.getElementById('add_department_btn');
    const tagsContainer = document.getElementById('departments_tags');
    const inputsContainer = document.getElementById('departments_inputs');

    function getDepartments() {
        return Array.from(inputsContainer.querySelectorAll('input[name="departments[]"]'))
            .map(input => input.value.trim())
            .filter(Boolean);
    }

    function syncDepartmentHiddenInputs(departments) {
        inputsContainer.innerHTML = '';
        departments.forEach(dep => {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'departments[]';
            hidden.value = dep;
            inputsContainer.appendChild(hidden);
        });
    }

    function renderDepartmentTags(departments) {
        tagsContainer.innerHTML = '';
        departments.forEach(dep => {
            const tag = document.createElement('span');
            tag.className = 'department-tag inline-flex items-center gap-2 rounded-full bg-blue-50 text-blue-800 px-3 py-1 text-sm';
            tag.dataset.value = dep;
            tag.innerHTML = `${dep}<button type="button" class="remove-department text-blue-600 hover:text-blue-800" aria-label="Verwijderen">&times;</button>`;
            tagsContainer.appendChild(tag);
        });
    }

    function addDepartment() {
        if (!departmentInput || !inputsContainer || !tagsContainer) return;
        const value = departmentInput.value.trim();
        if (!value) return;

        const current = getDepartments();
        if (current.includes(value)) {
            departmentInput.value = '';
            return;
        }

        const next = [...current, value];
        syncDepartmentHiddenInputs(next);
        renderDepartmentTags(next);
        departmentInput.value = '';
        departmentInput.focus();
    }

    if (addDepartmentBtn) {
        addDepartmentBtn.addEventListener('click', addDepartment);
    }

    if (departmentInput) {
        departmentInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addDepartment();
            }
        });
    }

    if (tagsContainer) {
        tagsContainer.addEventListener('click', function(e) {
            const btn = e.target.closest('.remove-department');
            if (!btn) return;
            const tag = btn.closest('.department-tag');
            if (!tag) return;
            const value = tag.dataset.value;
            const next = getDepartments().filter(dep => dep !== value);
            syncDepartmentHiddenInputs(next);
            renderDepartmentTags(next);
        });
    }

    const reportingEnabled = document.getElementById('reporting-enabled');
    const reportingFields = document.getElementById('reporting-settings-fields');
    const reportingFrequency = document.getElementById('reporting_frequency');
    const reportingWeeklyDayWrap = document.getElementById('reporting-weekly-day-wrap');

    function syncReportingUi() {
        const enabled = Boolean(reportingEnabled?.checked);
        const weekly = reportingFrequency?.value === 'weekly';

        if (reportingFields) {
            reportingFields.classList.toggle('opacity-50', !enabled);
            reportingFields.classList.toggle('pointer-events-none', !enabled);
        }

        if (reportingWeeklyDayWrap) {
            reportingWeeklyDayWrap.classList.toggle('hidden', !weekly);
        }
    }

    reportingEnabled?.addEventListener('change', syncReportingUi);
    reportingFrequency?.addEventListener('change', syncReportingUi);
    syncReportingUi();
});
</script>
@endsection

@extends('layouts.admin')

@section('page-title', 'Gebruiker toevoegen')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <a href="{{ route('admin.users.index') }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors">Gebruikers</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-semibold truncate">Toevoegen</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

        {{-- Hero --}}
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Nieuwe gebruiker</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Voeg een medewerker of beheerder toe</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Naar overzicht
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            {{-- Basisgegevens + uitnodiging (onboarding) --}}
            <div class="scroll-mt-28 space-y-6" data-onboarding-target="user-basics">
            {{-- Basisgegevens --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Basisgegevens</h2>
                    <p class="text-slate-600 text-sm mt-0.5">Naam en e-mailadres van de gebruiker</p>
                </div>
                <div class="p-4 sm:p-6 space-y-5">
                    <div class="rounded-xl border border-blue-100 bg-blue-50 p-4 text-sm">
                        <p class="font-semibold text-blue-900">Huidige planlimieten ({{ ucfirst($roleLimits['plan']) }})</p>
                        <div class="mt-1 text-blue-800 space-y-1">
                            <p>Admins: {{ $roleLimits['admin']['current'] }} / {{ $roleLimits['admin']['max'] }}</p>
                            <p>Medewerkers: {{ $roleLimits['employee']['current'] }} / {{ $roleLimits['employee']['max'] }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <x-form-label for="name" help="De volledige naam van de medewerker of beheerder zoals die in de app wordt getoond.">Volledige naam <span class="text-red-500">*</span></x-form-label>
                            <input type="text" name="name" id="name" required
                                   class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   value="{{ old('name') }}" placeholder="Jan Jansen">
                            @error('name')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <x-form-label for="email" help="Het loginadres van de gebruiker. Hier ontvangt hij ook meldingen.">E-mailadres <span class="text-red-500">*</span></x-form-label>
                            <input type="email" name="email" id="email" required
                                   class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   value="{{ old('email') }}" placeholder="jan@voorbeeld.nl">
                            @error('email')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Uitnodiging --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Inloggen</h2>
                    <p class="text-slate-600 text-sm mt-0.5">De gebruiker stelt zelf een wachtwoord in</p>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex gap-3 p-4 rounded-xl bg-blue-50 border border-blue-100">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-blue-900">Uitnodiging per e-mail</h3>
                            <p class="mt-1 text-sm text-blue-800">
                                Na het aanmaken ontvangt de gebruiker een e-mail met een link om een wachtwoord in te stellen. Je hoeft geen wachtwoord te bedenken of door te geven.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            </div>

            {{-- Rol en afdeling --}}
            <div class="scroll-mt-28 bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6" data-onboarding-target="user-role">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Rol en afdeling</h2>
                    <p class="text-slate-600 text-sm mt-0.5">Bepaal de rechten en afdeling van de gebruiker</p>
                </div>
                <div class="p-4 sm:p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <x-form-label for="role" help="Medewerkers voeren taken uit. Beheerders kunnen lijsten, gebruikers en instellingen beheren.">Rol <span class="text-red-500">*</span></x-form-label>
                            <select name="role" id="role" required
                                    class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="employee" {{ old('role', 'employee') === 'employee' ? 'selected' : '' }}>Medewerker</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Beheerder</option>
                            </select>
                            @error('role')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <x-form-label for="department" help="Koppel de gebruiker aan een afdeling voor overzicht en toewijzing.">Afdeling</x-form-label>
                            <select name="department" id="department"
                                    class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Geen afdeling</option>
                                @foreach(($departments ?? []) as $department)
                                    <option value="{{ $department }}" {{ old('department') === $department ? 'selected' : '' }}>
                                        {{ $department }}
                                    </option>
                                @endforeach
                            </select>
                            @if(empty($departments ?? []))
                                <p class="mt-1.5 text-xs text-slate-500">Nog geen afdelingen ingesteld. Voeg ze toe bij instellingen.</p>
                            @endif
                            @error('department')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <x-form-label for="location_id" help="Optioneel: koppel de gebruiker aan een specifieke vestiging.">Locatie</x-form-label>
                            <select name="location_id" id="location_id"
                                    class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Geen locatie</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ (string) old('location_id') === (string) $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Extra opties --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Extra opties</h2>
                    <p class="text-slate-600 text-sm mt-0.5">Telefoonnummer en accountstatus</p>
                </div>
                <div class="p-4 sm:p-6 space-y-5">
                    <div>
                        <x-form-label for="phone" help="Optioneel contactnummer van de medewerker.">Telefoonnummer</x-form-label>
                        <input type="tel" name="phone" id="phone"
                               class="block w-full sm:max-w-xs px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="{{ old('phone') }}" placeholder="+31 6 12345678">
                        @error('phone')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                        <label for="is_active" class="flex items-center gap-1.5 text-sm text-slate-700">
                            <span>Account is actief (gebruiker kan inloggen)</span>
                            <x-field-help>Uitgeschakelde accounts kunnen niet inloggen totdat je dit weer aanzet.</x-field-help>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Rol-informatie --}}
            <div class="bg-blue-50 rounded-xl p-4 sm:p-5 border border-blue-100 mb-6">
                <div class="flex gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900">Rolrechten</h3>
                        <ul class="mt-2 text-sm text-blue-800 space-y-1">
                            <li><strong>Medewerker:</strong> Kan toegewezen taken bekijken, inzendingen voltooien en notificaties ontvangen</li>
                            <li><strong>Beheerder:</strong> Kan taken, lijsten, gebruikers beheren en inzendingen beoordelen</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Acties --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-200 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Annuleren
                </a>
                <button type="submit" data-onboarding-target="user-save" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Gebruiker aanmaken
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.employee')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 sm:py-10 lg:py-12">
    <div>
        <div class="mb-8 sm:mb-10">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">Instellingen</h1>
            <p class="mt-2 text-base sm:text-lg text-gray-600">Beheer je accountgegevens en beveiliging</p>
        </div>

        <div class="grid gap-8 xl:grid-cols-2 xl:items-start">
            {{-- Profielgegevens --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100 px-6 sm:px-8 py-5 sm:py-6">
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Profielgegevens
                    </h2>
                    <p class="mt-2 text-base text-gray-600">Pas je naam en e-mailadres aan</p>
                </div>
                <form method="POST" action="{{ route('employee.settings.update-profile') }}" class="p-6 sm:p-8 space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-base font-medium text-gray-700 mb-2">Naam <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required
                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base px-4 py-3"
                            value="{{ old('name', $user->name) }}" placeholder="Jouw naam" autocomplete="name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-base font-medium text-gray-700 mb-2">E-mailadres <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" required
                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base px-4 py-3"
                            value="{{ old('email', $user->email) }}" placeholder="naam@voorbeeld.nl" autocomplete="username">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                            <p class="mt-2 text-sm text-amber-700">Je e-mailadres is nog niet geverifieerd.</p>
                        @endif
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-semibold rounded-xl text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Profiel opslaan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Wachtwoord wijzigen --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-cyan-50 to-blue-50 border-b border-gray-100 px-6 sm:px-8 py-5 sm:py-6">
                    <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Wachtwoord wijzigen
                    </h2>
                    <p class="mt-2 text-base text-gray-600">Gebruik een sterk wachtwoord om je account te beveiligen</p>
                </div>
                <form method="POST" action="{{ route('employee.settings.update-password') }}" class="p-6 sm:p-8 space-y-6">
                    @csrf

                    <div>
                        <label for="current_password" class="block text-base font-medium text-gray-700 mb-2">Huidig wachtwoord <span class="text-red-500">*</span></label>
                        <input type="password" name="current_password" id="current_password" required
                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base px-4 py-3"
                            placeholder="••••••••" autocomplete="current-password">
                        @error('current_password', 'updatePassword')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-base font-medium text-gray-700 mb-2">Nieuw wachtwoord <span class="text-red-500">*</span></label>
                        <input type="password" name="password" id="password" required
                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base px-4 py-3"
                            placeholder="Minimaal 8 tekens" autocomplete="new-password">
                        @error('password', 'updatePassword')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-base font-medium text-gray-700 mb-2">Bevestig nieuw wachtwoord <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-base px-4 py-3"
                            placeholder="••••••••" autocomplete="new-password">
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-semibold rounded-xl text-white bg-cyan-600 hover:bg-cyan-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Wachtwoord wijzigen
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

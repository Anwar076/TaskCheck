@extends('layouts.admin')

@section('page-title', 'Gebruiker bewerken')

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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Gebruiker bewerken</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Wijzig gegevens van {{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Bekijken
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                                Naar overzicht
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            {{-- Basisgegevens --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Basisgegevens</h2>
                    <p class="text-slate-600 text-sm mt-0.5">Naam en e-mailadres van de gebruiker</p>
                </div>
                <div class="p-4 sm:p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Volledige naam <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" required
                                   class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   value="{{ old('name', $user->name) }}" placeholder="Jan Jansen">
                            @error('name')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">E-mailadres <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" required
                                   class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   value="{{ old('email', $user->email) }}" placeholder="jan@voorbeeld.nl">
                            @error('email')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Wachtwoord --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Wachtwoord wijzigen</h2>
                    <p class="text-slate-600 text-sm mt-0.5">Laat leeg om het huidige wachtwoord te behouden</p>
                </div>
                <div class="p-4 sm:p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">Nieuw wachtwoord</label>
                            <input type="password" name="password" id="password"
                                   class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Minimaal 8 tekens">
                            <p class="mt-1.5 text-sm text-slate-500">Alleen invullen als je het wachtwoord wilt wijzigen</p>
                            @error('password')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">Nieuw wachtwoord bevestigen</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Herhaal het nieuwe wachtwoord">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Rol en afdeling --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Rol en afdeling</h2>
                    <p class="text-slate-600 text-sm mt-0.5">Bepaal de rechten en afdeling van de gebruiker</p>
                </div>
                <div class="p-4 sm:p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="role" class="block text-sm font-medium text-slate-700 mb-1.5">Rol <span class="text-red-500">*</span></label>
                            <select name="role" id="role" required
                                    class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="employee" {{ old('role', $user->role) === 'employee' ? 'selected' : '' }}>Medewerker</option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Beheerder</option>
                            </select>
                            @error('role')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="department" class="block text-sm font-medium text-slate-700 mb-1.5">Afdeling</label>
                            <input type="text" name="department" id="department"
                                   class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   value="{{ old('department', $user->department) }}" placeholder="Bijv. Schoonmaak, Operaties">
                            @error('department')
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
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">Telefoonnummer</label>
                        <input type="tel" name="phone" id="phone"
                               class="block w-full sm:max-w-xs px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="{{ old('phone', $user->phone) }}" placeholder="+31 6 12345678">
                        @error('phone')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}
                               class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-slate-300 rounded">
                        <label for="is_active" class="text-sm text-slate-700">
                            Account is actief (gebruiker kan inloggen)
                        </label>
                    </div>
                </div>
            </div>

            {{-- Huidige gegevens --}}
            <div class="bg-slate-50 rounded-xl p-4 sm:p-5 border border-slate-200 mb-6">
                <h3 class="text-sm font-semibold text-slate-900 mb-3">Huidige gegevens</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="font-medium text-slate-500">Lid sinds</span>
                        <p class="text-slate-900 mt-0.5">{{ $user->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                    <div>
                        <span class="font-medium text-slate-500">Laatst bijgewerkt</span>
                        <p class="text-slate-900 mt-0.5">{{ $user->updated_at->translatedFormat('d M Y') }}</p>
                    </div>
                    @if($user->role === 'employee')
                        <div>
                            <span class="font-medium text-slate-500">Totaal inzendingen</span>
                            <p class="text-slate-900 mt-0.5">{{ $user->submissions()->count() }}</p>
                        </div>
                        <div>
                            <span class="font-medium text-slate-500">Taaktoewijzingen</span>
                            <p class="text-slate-900 mt-0.5">{{ $user->taskAssignments()->count() }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($user->role === 'admin')
                <div class="bg-amber-50 rounded-xl p-4 sm:p-5 border border-amber-200 mb-6">
                    <div class="flex gap-3">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-amber-900">Beheerdersaccount</h3>
                            <p class="mt-1 text-sm text-amber-800">
                                Deze gebruiker heeft beheerdersrechten. Het wijzigen naar medewerker verwijdert de toegang tot beheerfuncties.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Acties --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mb-8">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 border border-slate-200 text-slate-700 text-sm font-medium rounded-xl hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Annuleren
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Wijzigingen opslaan
                </button>
            </div>
        </form>

        {{-- Gevarenzone --}}
        @if($user->id !== auth()->id())
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-red-200 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 border-b border-red-200 bg-red-50/50">
                    <h3 class="text-lg font-semibold text-red-900">Gevarenzone</h3>
                    <p class="text-sm text-red-700 mt-0.5">Onomkeerbare acties</p>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900">Gebruikersaccount verwijderen</h4>
                            <p class="mt-1 text-sm text-slate-600">
                                Verwijder dit account en alle bijbehorende gegevens permanent. Deze actie kan niet ongedaan worden gemaakt.
                            </p>
                        </div>
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="flex-shrink-0" onsubmit="return confirm('Weet je zeker dat je dit gebruikersaccount permanent wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                Gebruiker verwijderen
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

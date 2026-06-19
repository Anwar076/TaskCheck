@extends('layouts.admin')

@section('page-title', 'Locatie bewerken')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <a href="{{ route('admin.locations.index') }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors">Locaties</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-semibold truncate">{{ $location->name }}</span>
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-semibold truncate">Bewerken</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.75-5.625-6.75-11.25a6.75 6.75 0 1113.5 0C18.75 15.375 12 21 12 21z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/></svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Locatie bewerken</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Werk de gegevens van {{ $location->name }} bij</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.locations.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Naar overzicht
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <form method="POST" action="{{ route('admin.locations.update', $location) }}" class="p-6 sm:p-8 space-y-5">
        @csrf
        @method('PUT')

        <div>
            <x-form-label for="name" help="Bijv. Hoofdvestiging of Filiaal Amsterdam. Deze naam zie je bij lijsten en gebruikers.">Naam locatie</x-form-label>
            <input id="name" name="name" type="text" value="{{ old('name', $location->name) }}" required class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm">
            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <x-form-label for="address" help="Het fysieke adres van deze vestiging.">Adres</x-form-label>
            <input id="address" name="address" type="text" value="{{ old('address', $location->address) }}" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm">
            @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <x-form-label for="notes" help="Extra informatie over deze locatie, alleen zichtbaar voor beheerders.">Notities</x-form-label>
            <textarea id="notes" name="notes" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm">{{ old('notes', $location->notes) }}</textarea>
            @error('notes')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>

        <label class="flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $location->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-blue-600">
            <span>Actief</span>
            <x-field-help>Alleen actieve locaties kun je koppelen aan lijsten en gebruikers.</x-field-help>
        </label>

        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-2">
            <a href="{{ route('admin.locations.index') }}" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Annuleren</a>
            <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Opslaan</button>
        </div>
    </form>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('page-title', 'Lijst bewerken')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <a href="{{ route('admin.lists.index') }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors">Takenlijsten</a>
    <span class="text-slate-400">/</span>
    <a href="{{ route('admin.lists.show', $list) }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors truncate">{{ $list->title }}</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-semibold truncate">Bewerken</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8">

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
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Takenlijst bewerken</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5 truncate max-w-md">{{ $list->title }}</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.lists.show', $list) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Bekijk lijst
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.lists.update', $list) }}">
            @csrf
            @method('PUT')

            {{-- Basisgegevens --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Basisgegevens</h2>
                    <p class="text-slate-600 text-sm mt-0.5">Titel en beschrijving van de takenlijst</p>
                </div>
                <div class="p-4 sm:p-6 space-y-5">
                    <div>
                        <x-form-label for="title" help="Een duidelijke naam voor de checklist, bijv. Dagelijkse keukencontrole.">Titel <span class="text-red-500">*</span></x-form-label>
                        <input type="text" name="title" id="title" required
                               class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               value="{{ old('title', $list->title) }}"
                               placeholder="Bijv. Dagelijkse kantoorcontrole">
                        @error('title')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <x-form-label for="description" help="Optioneel: leg uit wanneer en door wie deze lijst wordt gebruikt.">Beschrijving</x-form-label>
                        <textarea name="description" id="description" rows="3"
                                  class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Beschrijf waarvoor deze takenlijst dient...">{{ old('description', $list->description) }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Instellingen --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Instellingen</h2>
                    <p class="text-slate-600 text-sm mt-0.5">Categorie, prioriteit en planning</p>
                </div>
                <div class="p-4 sm:p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <x-form-label for="compliance_framework">Normenkader of kwaliteitsstandaard</x-form-label>
                            <input type="text" name="compliance_framework" id="compliance_framework" value="{{ old('compliance_framework', $list->compliance_framework) }}" placeholder="Bijv. Hygiënecode, VCA, ISO 9001 of eigen standaard" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <x-form-label for="policy_reference">Beleids- of documentreferentie</x-form-label>
                            <input type="text" name="policy_reference" id="policy_reference" value="{{ old('policy_reference', $list->policy_reference) }}" placeholder="Bijv. Kwaliteitshandboek v3.2, hoofdstuk 6" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <x-form-label for="category" help="Groepeer lijsten, bijv. Schoonmaak, HACCP of Veiligheid.">Categorie</x-form-label>
                            <input type="text" name="category" id="category"
                                   class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   value="{{ old('category', $list->category) }}"
                                   placeholder="Bijv. Schoonmaak, Veiligheid">
                            @error('category')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <x-form-label for="priority" help="Hoe belangrijk taken uit deze lijst zijn voor je team.">Prioriteit <span class="text-red-500">*</span></x-form-label>
                            <select name="priority" id="priority" required
                                    class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="low" {{ old('priority', $list->priority) === 'low' ? 'selected' : '' }}>Laag</option>
                                <option value="medium" {{ old('priority', $list->priority) === 'medium' ? 'selected' : '' }}>Normaal</option>
                                <option value="high" {{ old('priority', $list->priority) === 'high' ? 'selected' : '' }}>Hoog</option>
                                <option value="urgent" {{ old('priority', $list->priority) === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <x-form-label for="location_id" help="Koppel de lijst aan één locatie of laat leeg voor alle locaties.">Locatie</x-form-label>
                            <select name="location_id" id="location_id"
                                    class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Alle locaties / algemeen</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ (string) old('location_id', $list->location_id) === (string) $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    @include('admin.lists.partials.list-form-schedule', ['list' => $list])

                    @include('admin.lists.partials.list-edit-time-slots', [
                        'list' => $list,
                        'timeSlots' => $timeSlots ?? [],
                        'defaultTimeSlot' => $defaultTimeSlot ?? null,
                    ])
                </div>
            </div>

            {{-- Extra opties --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Extra opties</h2>
                </div>
                <div class="p-4 sm:p-6 space-y-4">
                    <label for="requires_review_edit" class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 p-4 cursor-pointer">
                        <span>
                            <span class="block text-sm font-medium text-slate-800">Moet deze takenlijst gecontroleerd worden?</span>
                            <span class="block mt-0.5 text-sm text-slate-500">Na invullen verschijnt de inzending in Werkcontroles.</span>
                        </span>
                        <input type="hidden" name="requires_review" value="0">
                        <span class="relative inline-flex h-6 w-11 flex-shrink-0">
                            <input id="requires_review_edit" type="checkbox" name="requires_review" value="1" {{ old('requires_review', $list->requires_review) ? 'checked' : '' }} class="peer absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0">
                            <span class="h-6 w-11 rounded-full bg-slate-300 transition-colors peer-checked:bg-blue-600 peer-focus-visible:ring-2 peer-focus-visible:ring-blue-500 peer-focus-visible:ring-offset-2"></span>
                            <span class="pointer-events-none absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow transition-transform peer-checked:translate-x-5"></span>
                        </span>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="requires_signature" value="1" {{ old('requires_signature', $list->requires_signature) ? 'checked' : '' }}
                                   class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="flex items-center gap-1.5 text-sm text-slate-700">
                                <span>Digitale handtekening vereist bij afronding</span>
                                <x-field-help>De medewerker moet tekenen wanneer de lijst is afgerond.</x-field-help>
                            </span>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $list->is_active) ? 'checked' : '' }}
                                   class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="flex items-center gap-1.5 text-sm text-slate-700">
                                <span>Actief — medewerkers kunnen deze lijst zien en uitvoeren</span>
                                <x-field-help>Alleen actieve lijsten zijn zichtbaar voor medewerkers.</x-field-help>
                            </span>
                        </label>
                </div>
            </div>

            {{-- Acties --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <a href="{{ route('admin.lists.show', $list) }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Annuleren
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Wijzigingen opslaan
                </button>
            </div>
        </form>

        {{-- Info --}}
        <div class="mt-8 p-4 sm:p-6 bg-blue-50 border border-blue-100 rounded-xl">
            <h3 class="text-sm font-semibold text-blue-900 mb-2">Na het opslaan</h3>
            <p class="text-sm text-blue-800">
                Je kunt daarna taken toevoegen of bewerken, aan medewerkers toewijzen en inzendingen bekijken. Wijzigingen aan de planning beïnvloeden wanneer de lijst verschijnt; bestaande taken blijven behouden.
            </p>
        </div>
    </div>
</div>

@endsection

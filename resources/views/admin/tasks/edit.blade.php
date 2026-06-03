@extends('layouts.admin')

@section('page-title', 'Taak bewerken')

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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white truncate">Taak bewerken</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5 truncate">
                                    "{{ $task->title }}" in {{ $task->taskList->title }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap justify-end gap-2 sm:gap-3 sm:flex-shrink-0">
                            <a href="{{ route('admin.lists.show', $task->taskList) }}" 
                               class="inline-flex items-center justify-center gap-2 px-4 h-11 sm:h-12 w-40 sm:w-44 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors touch-manipulation">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18.75"/>
                                </svg>
                                Terug naar lijst
                            </a>
                            <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" class="inline"
                                  onsubmit="return confirm('Weet je zeker dat je deze taak wilt verwijderen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center justify-center gap-2 px-4 h-11 sm:h-12 w-40 sm:w-44 bg-red-600/90 text-white text-sm font-medium rounded-xl hover:bg-red-700 transition-colors touch-manipulation">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Taak verwijderen
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <form method="POST" action="{{ route('admin.tasks.update', $task) }}" class="divide-y divide-slate-100">
            @csrf
            @method('PUT')

            {{-- Basic Information --}}
            <div class="p-6 sm:p-8 bg-blue-50/40">
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900">Basisinformatie</h2>
                    </div>
                    <p class="text-slate-600 ml-11 text-sm">Wijzig de basisgegevens van de taak.</p>
                </div>
                <div class="space-y-5">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-slate-700 mb-2">Taaktitel <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" required 
                               class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white min-h-[44px] hover:border-slate-300" 
                               value="{{ old('title', $task->title) }}" 
                               placeholder="bijv. Alle prullenbakken legen">
                        @error('title')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Omschrijving</label>
                        <textarea name="description" id="description" rows="3" 
                                  class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white resize-none hover:border-slate-300" 
                                  placeholder="Geef een duidelijke omschrijving van wat er gedaan moet worden…">{{ old('description', $task->description) }}</textarea>
                        @error('description')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instructions" class="block text-sm font-semibold text-slate-700 mb-2">Gedetailleerde instructies</label>
                        <textarea name="instructions" id="instructions" rows="4" 
                                  class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white resize-none hover:border-slate-300" 
                                  placeholder="Geef stap-voor-stap instructies voor het uitvoeren van deze taak…">{{ old('instructions', $task->instructions) }}</textarea>
                        <p class="mt-1.5 text-sm text-slate-500">Deze instructies worden getoond aan medewerkers bij het uitvoeren van de taak.</p>
                        @error('instructions')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            @if($task->taskList->hasWeeklyStructure())
            {{-- Day Selection --}}
            <div class="p-6 sm:p-8 bg-gradient-to-r from-indigo-50/50 to-purple-50/50">
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900">Dagtoewijzing</h2>
                    </div>
                    <p class="text-slate-600 ml-11 text-sm">Kies op welke dagen van de week deze taak beschikbaar is (optioneel).</p>
                </div>
                @php
                    $listAvailableDays = $task->taskList->getShowOnDays();
                    if (empty($listAvailableDays)) {
                        $listAvailableDays = ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'];
                    }
                @endphp
                <div class="grid grid-cols-4 sm:grid-cols-7 gap-2 sm:gap-3">
                    @php
                        $weekdays = ['monday' => 'Ma', 'tuesday' => 'Di', 'wednesday' => 'Wo', 'thursday' => 'Do', 'friday' => 'Vr', 'saturday' => 'Za', 'sunday' => 'Zo'];
                        $selectedDays = old('weekdays', $task->weekday ? [$task->weekday] : []);
                    @endphp
                    @foreach($weekdays as $dayKey => $dayLabel)
                    @php $isAvailableOnList = in_array($dayKey, $listAvailableDays); $isSelected = in_array($dayKey, $selectedDays); @endphp
                    <div class="group relative">
                        <label class="flex flex-col items-center justify-center p-3 sm:p-4 border-2 rounded-xl transition-all min-h-[60px] sm:min-h-[72px] touch-manipulation day-label
                            @if(!$isAvailableOnList) border-slate-200 bg-slate-100 cursor-not-allowed opacity-60
                            @elseif($isSelected) border-blue-500 bg-blue-50 cursor-pointer
                            @else border-slate-200 bg-white hover:border-blue-300 hover:bg-blue-50/50 cursor-pointer
                            @endif">
                            @if($isAvailableOnList)
                            <input type="checkbox" name="weekdays[]" value="{{ $dayKey }}" class="sr-only day-checkbox" {{ $isSelected ? 'checked' : '' }}>
                            @endif
                            <span class="w-8 h-8 rounded-lg flex items-center justify-center mb-1.5 text-white font-bold text-xs transition-colors
                                @if(!$isAvailableOnList) bg-slate-300
                                @elseif($isSelected) bg-blue-500
                                @else bg-slate-400 group-hover:bg-blue-400
                                @endif">{{ $dayLabel }}</span>
                            <span class="text-xs font-semibold
                                @if(!$isAvailableOnList) text-slate-400
                                @elseif($isSelected) text-blue-700
                                @else text-slate-600 group-hover:text-blue-700
                                @endif">{{ $dayLabel }}</span>
                            @if(!$isAvailableOnList)
                            <span class="text-[10px] text-slate-400 mt-0.5">Niet beschikbaar</span>
                            @endif
                        </label>
                    </div>
                    @endforeach
                </div>
                <p class="mt-4 p-3 text-sm text-slate-600 bg-white border border-slate-200 rounded-xl">
                    <strong>Optioneel:</strong> Selecteer specifieke dagen, of laat leeg voor een algemene taak voor de hele lijst.
                </p>
            </div>
            @endif

            {{-- Task Settings --}}
            <div class="p-6 sm:p-8 bg-gradient-to-r from-purple-50/50 to-pink-50/50">
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900">Taakconfiguratie</h2>
                    </div>
                    <p class="text-slate-600 ml-11 text-sm">Bewijsvereisten en tijdslot instellen.</p>
                </div>
                <div>
                    <label for="required_proof_type" class="block text-sm font-semibold text-slate-700 mb-2">Bewijstype <span class="text-red-500">*</span></label>
                    <select name="required_proof_type" id="required_proof_type" required 
                            class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white min-h-[44px] hover:border-slate-300">
                        <option value="none" {{ old('required_proof_type', $task->required_proof_type) === 'none' ? 'selected' : '' }}>Geen bewijs vereist</option>
                        <option value="photo" {{ old('required_proof_type', $task->required_proof_type) === 'photo' ? 'selected' : '' }}>Foto vereist</option>
                        <option value="video" {{ old('required_proof_type', $task->required_proof_type) === 'video' ? 'selected' : '' }}>Video vereist</option>
                        <option value="text" {{ old('required_proof_type', $task->required_proof_type) === 'text' ? 'selected' : '' }}>Tekstnotitie vereist</option>
                        <option value="file" {{ old('required_proof_type', $task->required_proof_type) === 'file' ? 'selected' : '' }}>Bestand upload vereist</option>
                        <option value="any" {{ old('required_proof_type', $task->required_proof_type) === 'any' ? 'selected' : '' }}>Elk bewijstype</option>
                    </select>
                    <p class="mt-1.5 text-sm text-slate-500">Wat voor bewijs moeten medewerkers leveren om deze taak te voltooien.</p>
                    @error('required_proof_type')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @php
                    $metricRules = is_array($task->validation_rules) ? $task->validation_rules : [];
                    $oldMetricType = old('metric_type', $metricRules['metric'] ?? '');
                    $oldMetricUnit = old('metric_unit', $metricRules['unit'] ?? '');
                    $oldMetricMin = old('metric_min', $metricRules['min'] ?? '');
                    $oldMetricMax = old('metric_max', $metricRules['max'] ?? '');
                    $oldMetricComparison = old('metric_comparison', $metricRules['comparison'] ?? 'lte');
                @endphp
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <h4 class="text-base font-semibold text-slate-900 mb-4">Meting (temperatuur / pH) - optioneel</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="metric_type" class="block text-sm font-semibold text-slate-700 mb-2">Type meting</label>
                            <select id="metric_type" name="metric_type" class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white min-h-[44px] hover:border-slate-300">
                                <option value="">Geen meting</option>
                                <option value="temperature" {{ $oldMetricType === 'temperature' ? 'selected' : '' }}>Temperatuur</option>
                                <option value="ph" {{ $oldMetricType === 'ph' ? 'selected' : '' }}>pH</option>
                            </select>
                        </div>
                        <div>
                            <label for="metric_unit" class="block text-sm font-semibold text-slate-700 mb-2">Eenheid</label>
                            <input type="text" id="metric_unit" name="metric_unit" value="{{ $oldMetricUnit }}" placeholder="bijv. °C of pH" class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white min-h-[44px] hover:border-slate-300">
                            @error('metric_unit')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="metric_min" class="block text-sm font-semibold text-slate-700 mb-2">Minimum norm (optioneel)</label>
                            <input type="number" step="0.1" id="metric_min" name="metric_min" value="{{ $oldMetricMin }}" class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white min-h-[44px] hover:border-slate-300">
                            @error('metric_min')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="metric_max" class="block text-sm font-semibold text-slate-700 mb-2">Maximum norm (optioneel)</label>
                            <input type="number" step="0.1" id="metric_max" name="metric_max" value="{{ $oldMetricMax }}" class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white min-h-[44px] hover:border-slate-300">
                            @error('metric_max')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="metric_comparison" class="block text-sm font-semibold text-slate-700 mb-2">Bij maximum: vergelijking</label>
                        <select id="metric_comparison" name="metric_comparison" class="block w-full md:w-72 px-4 py-3 border border-slate-200 rounded-xl text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white min-h-[44px] hover:border-slate-300">
                            <option value="lte" {{ $oldMetricComparison === 'lte' ? 'selected' : '' }}>Waarde mag maximaal gelijk zijn (<= max)</option>
                            <option value="lt" {{ $oldMetricComparison === 'lt' ? 'selected' : '' }}>Waarde moet lager zijn (< max)</option>
                        </select>
                    </div>
                    <p class="mt-3 text-sm text-slate-500">Hiermee stel je de norm in die je later in de submission-review terugziet.</p>
                </div>
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <h4 class="text-base font-semibold text-slate-900 mb-4">Tijdslot (optioneel)</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-semibold text-slate-700 mb-2">Starttijd</label>
                            <input type="time" name="start_time" id="start_time" 
                                   class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white min-h-[44px] hover:border-slate-300" 
                                   value="{{ old('start_time', $task->start_time ? \Carbon\Carbon::parse($task->start_time)->format('H:i') : '') }}">
                            @error('start_time')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="end_time" class="block text-sm font-semibold text-slate-700 mb-2">Eindtijd</label>
                            <input type="time" name="end_time" id="end_time" 
                                   class="block w-full px-4 py-3 border border-slate-200 rounded-xl text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white min-h-[44px] hover:border-slate-300" 
                                   value="{{ old('end_time', $task->end_time ? \Carbon\Carbon::parse($task->end_time)->format('H:i') : '') }}">
                            @error('end_time')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="mt-3 text-sm text-slate-500">Geef een tijdslot op wanneer deze taak uitgevoerd moet worden (bijv. 10:30–10:45).</p>
                </div>
            </div>

            {{-- Task Options --}}
            <div class="p-6 sm:p-8 bg-gradient-to-r from-emerald-50/50 to-teal-50/50">
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900">Taakopties</h2>
                    </div>
                    <p class="text-slate-600 ml-11 text-sm">Configureer aanvullende taakvereisten.</p>
                </div>
                <div class="space-y-4">
                    <label for="is_required" class="block cursor-pointer">
                        <div class="relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 rounded-xl p-5 hover:border-emerald-300 hover:shadow-md transition-all duration-300">
                            <div class="flex items-start space-x-4">
                                <div class="flex items-center h-6 mt-1">
                                    <input type="checkbox" name="is_required" id="is_required" value="1" {{ old('is_required', $task->is_required) ? 'checked' : '' }}
                                           class="h-5 w-5 text-emerald-600 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-colors duration-200 touch-manipulation">
                                </div>
                                <div class="flex-1">
                                    <span class="font-bold text-slate-900 text-base block">Verplichte taak</span>
                                    <p class="text-slate-600 mt-1.5 text-sm">Deze taak moet voltooid zijn voordat de medewerker kan indienen.</p>
                                </div>
                            </div>
                        </div>
                    </label>
                    <label for="requires_signature" class="block cursor-pointer">
                        <div class="relative bg-white/80 backdrop-blur-sm border-2 border-slate-200 rounded-xl p-5 hover:border-emerald-300 hover:shadow-md transition-all duration-300">
                            <div class="flex items-start space-x-4">
                                <div class="flex items-center h-6 mt-1">
                                    <input type="checkbox" name="requires_signature" id="requires_signature" value="1" {{ old('requires_signature', $task->requires_signature) ? 'checked' : '' }}
                                           class="h-5 w-5 text-emerald-600 border-2 border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-colors duration-200 touch-manipulation">
                                </div>
                                <div class="flex-1">
                                    <span class="font-bold text-slate-900 text-base block">Handtekening vereist</span>
                                    <p class="text-slate-600 mt-1.5 text-sm">Medewerker moet een handtekening zetten bij het voltooien van deze taak.</p>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Checklist --}}
            <div class="p-6 sm:p-8 bg-gradient-to-r from-cyan-50/50 to-blue-50/50">
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <h2 class="text-lg sm:text-xl font-bold text-slate-900">Checklist items</h2>
                    </div>
                    <p class="text-slate-600 ml-11 text-sm">Voeg items toe die medewerkers moeten afvinken (optioneel).</p>
                </div>
                <div id="checklist-container" class="space-y-3 min-h-[60px] bg-white/50 backdrop-blur-sm rounded-xl border-2 border-dashed border-slate-300 p-4">
                    <div class="text-center text-slate-500 py-8" id="empty-checklist-message">
                        <svg class="w-12 h-12 mx-auto mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        <p class="text-sm text-slate-600">Nog geen checklist items. Klik op de knop hieronder om items toe te voegen.</p>
                    </div>
                </div>
                <button type="button" id="add-checklist-item" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-all min-h-[44px] touch-manipulation shadow-lg hover:shadow-xl active:scale-[0.98]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Checklist item toevoegen
                </button>
            </div>

            {{-- Submit --}}
            <div class="p-6 sm:p-8 bg-slate-50 border-t border-slate-200">
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <a href="{{ route('admin.lists.show', $task->taskList) }}" 
                       class="inline-flex items-center justify-center px-6 py-3 rounded-xl text-sm font-semibold text-slate-700 bg-white border-2 border-slate-300 hover:bg-slate-50 hover:border-slate-400 transition-all min-h-[44px] touch-manipulation">
                        Annuleren
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-8 py-3 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all min-h-[44px] touch-manipulation shadow-lg hover:shadow-xl active:scale-[0.98]">
                        Taak bijwerken
                    </button>
                </div>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Day checkbox styling
    document.querySelectorAll('.day-checkbox').forEach(checkbox => {
        const label = checkbox.closest('label');
        if (!label) return;

        function updateDayStyle() {
            const spans = label.querySelectorAll('span');
            const iconSpan = spans[0];
            const textSpan = spans[1];
            if (checkbox.checked) {
                label.classList.add('border-blue-500', 'bg-blue-50');
                label.classList.remove('border-slate-200');
                if (iconSpan) { iconSpan.classList.remove('bg-slate-400'); iconSpan.classList.add('bg-blue-500'); }
                if (textSpan) { textSpan.classList.remove('text-slate-600'); textSpan.classList.add('text-blue-700'); }
            } else {
                label.classList.remove('border-blue-500', 'bg-blue-50');
                label.classList.add('border-slate-200');
                if (iconSpan) { iconSpan.classList.remove('bg-blue-500'); iconSpan.classList.add('bg-slate-400'); }
                if (textSpan) { textSpan.classList.remove('text-blue-700'); textSpan.classList.add('text-slate-600'); }
            }
        }

        updateDayStyle();
        checkbox.addEventListener('change', updateDayStyle);
    });

    // Checklist
    let checklistItemCount = 0;
    const checklistContainer = document.getElementById('checklist-container');
    const addChecklistBtn = document.getElementById('add-checklist-item');

    function createChecklistItem(value = '') {
        checklistItemCount++;
        const itemId = 'checklist-item-' + checklistItemCount;
        const escapedValue = (value + '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        const emptyMsg = document.getElementById('empty-checklist-message');
        if (emptyMsg) emptyMsg.style.display = 'none';
        const itemDiv = document.createElement('div');
        itemDiv.className = 'flex items-center gap-3 checklist-item bg-white/80 border border-slate-200 rounded-xl p-3 hover:border-cyan-300 transition-all';
        itemDiv.id = itemId;
        itemDiv.innerHTML = '<div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center"><span class="text-white font-bold text-sm">' + checklistItemCount + '</span></div><input type="text" name="checklist_items[]" class="flex-1 px-4 py-3 border border-slate-200 rounded-xl text-base focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white min-h-[44px]" placeholder="bijv. Controleer de staat van apparatuur" value="' + escapedValue + '"><button type="button" onclick="removeChecklistItem(\'' + itemId + '\')" class="flex-shrink-0 w-10 h-10 bg-red-50 hover:bg-red-100 text-red-600 rounded-xl flex items-center justify-center transition-all min-h-[44px] touch-manipulation"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>';
        checklistContainer.appendChild(itemDiv);
        itemDiv.querySelector('input').focus();
    }

    window.removeChecklistItem = function(itemId) {
        const item = document.getElementById(itemId);
        if (item) {
            item.remove();
            document.querySelectorAll('.checklist-item').forEach((el, i) => {
                const num = el.querySelector('.text-white');
                if (num) num.textContent = i + 1;
            });
            const emptyMsg = document.getElementById('empty-checklist-message');
            if (document.querySelectorAll('.checklist-item').length === 0 && emptyMsg) {
                emptyMsg.style.display = 'block';
            }
        }
    };

    addChecklistBtn.addEventListener('click', () => createChecklistItem());

    @if(old('checklist_items'))
        @foreach(old('checklist_items') as $item)
            createChecklistItem(@json($item));
        @endforeach
    @elseif($task->checklist_items)
        @foreach($task->checklist_items as $item)
            createChecklistItem(@json($item));
        @endforeach
    @endif
});
</script>
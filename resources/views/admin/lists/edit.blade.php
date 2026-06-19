@extends('layouts.admin')

@section('page-title', 'Lijst bewerken')

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
                    <div>
                        <x-form-label for="schedule_type" help="Bepaal hoe vaak deze lijst beschikbaar is voor medewerkers.">Herhalingen <span class="text-red-500">*</span></x-form-label>
                        <select name="schedule_type" id="schedule_type" required
                                class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                onchange="toggleScheduleConfig()">
                            <option value="once" {{ old('schedule_type', $list->schedule_type) === 'once' ? 'selected' : '' }}>Eenmalig</option>
                            <option value="daily" {{ old('schedule_type', $list->schedule_type) === 'daily' ? 'selected' : '' }}>Dagelijks (elke dag)</option>
                            <option value="weekly" {{ old('schedule_type', $list->schedule_type) === 'weekly' ? 'selected' : '' }}>Wekelijks (vaste dagen)</option>
                            <option value="monthly" {{ old('schedule_type', $list->schedule_type) === 'monthly' ? 'selected' : '' }}>Maandelijks</option>
                            <option value="custom" {{ old('schedule_type', $list->schedule_type) === 'custom' ? 'selected' : '' }}>Aangepast</option>
                        </select>
                        @error('schedule_type')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Planning configuratie --}}
                    @php $scheduleCfg = is_array($list->schedule_config) ? $list->schedule_config : []; @endphp
                    <div id="schedule-config" style="display: none;">
                        <div id="daily-config" class="hidden">
                            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                                <p class="text-sm text-emerald-800">Deze lijst is elke dag beschikbaar. Je kunt later per taak specifieke dagen instellen.</p>
                            </div>
                        </div>
                        <div id="weekly-config" class="hidden space-y-4">
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
                                <p class="text-sm text-blue-800">Kies op welke dagen deze lijst actief is.</p>
                            </div>
                            <label class="block text-sm font-medium text-slate-700">Dagen van de week</label>
                            <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
                                @php
                                    $weekdaysList = ['monday' => 'Ma', 'tuesday' => 'Di', 'wednesday' => 'Wo', 'thursday' => 'Do', 'friday' => 'Vr', 'saturday' => 'Za', 'sunday' => 'Zo'];
                                    $selectedDays = old('selected_days', $list->getShowOnDays());
                                @endphp
                                @foreach($weekdaysList as $dayKey => $dayLabel)
                                <label class="weekday-label flex flex-col items-center justify-center p-3 border-2 border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors has-[:checked]:bg-blue-600 has-[:checked]:border-blue-600 has-[:checked]:text-white">
                                    <input type="checkbox" name="selected_days[]" value="{{ $dayKey }}"
                                           class="hidden weekday-checkbox"
                                           {{ in_array($dayKey, $selectedDays) ? 'checked' : '' }}>
                                    <span class="text-sm font-medium">{{ $dayLabel }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div id="monthly-config" class="hidden">
                            @php $scheduleCfg = is_array($list->schedule_config) ? $list->schedule_config : []; @endphp
                            <x-form-label for="day_of_month" help="Op welke dag van de maand deze lijst actief is.">Dag van de maand</x-form-label>
                            <select name="schedule_config[day_of_month]" id="day_of_month"
                                    class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ old('schedule_config.day_of_month', $scheduleCfg['day_of_month'] ?? 1) == $i ? 'selected' : '' }}>
                                        {{ $i }}e
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div id="custom-config" class="hidden space-y-4">
                            @php $cfg = $scheduleCfg; @endphp
                            <div>
                                <x-form-label for="custom_type" help="Kies hoe je aangepaste planning wilt instellen.">Type planning</x-form-label>
                                <select name="schedule_config[type]" id="custom_type"
                                        class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500"
                                        onchange="toggleCustomType()">
                                    <option value="specific_days" {{ old('schedule_config.type', $cfg['type'] ?? '') === 'specific_days' ? 'selected' : '' }}>Specifieke dagen</option>
                                    <option value="interval" {{ old('schedule_config.type', $cfg['type'] ?? '') === 'interval' ? 'selected' : '' }}>Elke X dagen</option>
                                    <option value="date_range" {{ old('schedule_config.type', $cfg['type'] ?? '') === 'date_range' ? 'selected' : '' }}>Periode</option>
                                </select>
                            </div>
                            <div id="custom-specific-days" class="hidden">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Selecteer dagen</label>
                                <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
                                    @php $customSelectedDays = old('schedule_config.days', $cfg['days'] ?? $cfg['show_on_days'] ?? []); @endphp
                                    @foreach($weekdaysList as $dayKey => $dayLabel)
                                    <label class="flex items-center justify-center p-2 border-2 border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 has-[:checked]:bg-blue-600 has-[:checked]:border-blue-600 has-[:checked]:text-white">
                                        <input type="checkbox" name="schedule_config[days][]" value="{{ $dayKey }}"
                                               class="hidden custom-day-checkbox"
                                               {{ in_array($dayKey, $customSelectedDays) ? 'checked' : '' }}>
                                        <span class="text-sm font-medium">{{ $dayLabel }}</span>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            <div id="custom-interval" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-form-label for="interval_days" help="De lijst keert terug na dit aantal dagen.">Elke X dagen</x-form-label>
                                    <input type="number" name="schedule_config[interval_days]" id="interval_days" min="1" max="365"
                                           class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm"
                                           value="{{ old('schedule_config.interval_days', $cfg['interval_days'] ?? 1) }}">
                                </div>
                                <div>
                                    <x-form-label for="interval_start" help="Vanaf welke datum de herhaling begint.">Startdatum</x-form-label>
                                    <input type="date" name="schedule_config[start_date]" id="interval_start"
                                           class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm"
                                           value="{{ old('schedule_config.start_date', $cfg['start_date'] ?? '') }}">
                                </div>
                            </div>
                            <div id="custom-date-range" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-form-label for="range_start" help="Eerste dag waarop de lijst actief is.">Startdatum</x-form-label>
                                    <input type="date" name="schedule_config[start_date]" id="range_start"
                                           class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm"
                                           value="{{ old('schedule_config.start_date', $cfg['start_date'] ?? '') }}">
                                </div>
                                <div>
                                    <x-form-label for="range_end" help="Laatste dag waarop de lijst actief is.">Einddatum</x-form-label>
                                    <input type="date" name="schedule_config[end_date]" id="range_end"
                                           class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm"
                                           value="{{ old('schedule_config.end_date', $cfg['end_date'] ?? '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

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

<script>
function toggleScheduleConfig() {
    const t = document.getElementById('schedule_type').value;
    const config = document.getElementById('schedule-config');
    const daily = document.getElementById('daily-config');
    const weekly = document.getElementById('weekly-config');
    const monthly = document.getElementById('monthly-config');
    const custom = document.getElementById('custom-config');

    [daily, weekly, monthly, custom].forEach(el => el.classList.add('hidden'));
    config.style.display = 'none';

    if (t === 'daily') {
        config.style.display = 'block';
        daily.classList.remove('hidden');
    } else if (t === 'weekly') {
        config.style.display = 'block';
        weekly.classList.remove('hidden');
    } else if (t === 'monthly') {
        config.style.display = 'block';
        monthly.classList.remove('hidden');
    } else if (t === 'custom') {
        config.style.display = 'block';
        custom.classList.remove('hidden');
        toggleCustomType();
    }
}

function toggleCustomType() {
    const t = document.getElementById('custom_type').value;
    const specific = document.getElementById('custom-specific-days');
    const interval = document.getElementById('custom-interval');
    const range = document.getElementById('custom-date-range');

    [specific, interval, range].forEach(el => el.classList.add('hidden'));
    if (t === 'specific_days') specific.classList.remove('hidden');
    else if (t === 'interval') interval.classList.remove('hidden');
    else if (t === 'date_range') range.classList.remove('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    toggleScheduleConfig();
});
</script>
@endsection

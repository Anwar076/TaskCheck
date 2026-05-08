@extends('layouts.admin')

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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Nieuwe takenlijst</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Maak een takenlijst of checklist voor je team</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.lists.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18.75"/>
                            </svg>
                            Terug naar overzicht
                        </a>
                        @if((auth()->user()->company?->subscription_plan ?? 'starter') !== 'starter')
                            <a href="{{ route('admin.lists.ai-import') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-700 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                AI Importer
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.lists.store') }}">
            @csrf

            {{-- Basisgegevens + AI lijstbouwer --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Basisgegevens</h2>
                        <p class="text-slate-600 text-sm mt-0.5">Titel en beschrijving van de takenlijst</p>
                    </div>
                    <!-- <div class="inline-flex items-center gap-2 rounded-full bg-slate-50 border border-slate-200 px-3 py-1">
                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-semibold">AI</span>
                        <span class="text-xs text-slate-700 font-medium">Lijst laten bedenken met AI</span>
                    </div> -->
                </div>
                <div class="p-4 sm:p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2 space-y-4">
                            <div>
                                <label for="title" class="block text-sm font-medium text-slate-700 mb-1.5">Titel <span class="text-red-500">*</span></label>
                                <input type="text" name="title" id="title" required
                                       class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('title', request('title')) }}"
                                       placeholder="Bijv. Dagelijkse keukencontrole">
                                @error('title')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">Beschrijving</label>
                                <textarea name="description" id="description" rows="3"
                                          class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Beschrijf waarvoor deze takenlijst dient...">{{ old('description', request('description')) }}</textarea>
                                @error('description')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="category" class="block text-sm font-medium text-slate-700 mb-1.5">Categorie</label>
                                <input type="text" name="category" id="category"
                                       class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       value="{{ old('category') }}"
                                       placeholder="Bijv. Schoonmaak, Veiligheid">
                                @error('category')
                                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <!-- <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">AI lijstbouwer</label>
                                <p class="text-xs text-slate-500 mb-2">
                                    Typ kort wat voor lijst je nodig hebt of upload een foto van een papieren checklist. De AI stelt een lijst en taken voor.
                                </p>
                                <textarea id="ai-list-prompt" rows="3"
                                          class="block w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                          placeholder="Bijv. Dagelijkse schoonmaaklijst voor de restaurantkeuken, inclusief ramen, vloeren en werkbladen."></textarea>
                            </div>
                            <div class="space-y-1">
                                <label for="ai-source-file" class="block text-xs font-medium text-slate-700">Foto van checklist (optioneel)</label>
                                <input type="file"
                                       id="ai-source-file"
                                       accept="image/jpeg,image/png,image/webp,application/pdf"
                                       class="block w-full text-xs text-slate-600 file:text-xs file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="text-[11px] text-slate-400 mt-0.5">Ondersteund: foto (jpg, png, webp). PDF/Word volgt later.</p>
                            </div>
                            <button type="button"
                                    id="ai-generate-list-button"
                                    class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-xs font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                <span>AI lijstvoorstel maken</span>
                            </button>
                            <div id="ai-tasks-preview" class="hidden mt-2 border border-dashed border-slate-200 rounded-xl p-2.5 bg-slate-50/60 max-h-48 overflow-auto">
                                <p class="text-[11px] font-semibold text-slate-700 mb-1.5">Voorgestelde taken (alleen ter inspiratie, worden niet automatisch aangemaakt):</p>
                                <ul id="ai-tasks-preview-list" class="space-y-1 text-[11px] text-slate-700"></ul>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>

            {{-- Hidden field to carry AI-taken mee naar backend --}}
            <input type="hidden" name="ai_tasks" id="ai-tasks-json" value="">

            {{-- Template --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Template <span class="font-normal text-slate-600">(optioneel)</span></h2>
                    <p class="text-slate-600 text-sm mt-0.5">Start met een bestaand template of maak een lege lijst</p>
                </div>
                <div class="p-4 sm:p-6">
                    <label for="template_id" class="block text-sm font-medium text-slate-700 mb-1.5">Template kiezen</label>
                    <select name="template_id" id="template_id"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">— Lijst van scratch aanmaken —</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}" {{ old('template_id', request('template_id')) == $template->id ? 'selected' : '' }}>
                                {{ $template->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('template_id')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                            <label for="priority" class="block text-sm font-medium text-slate-700 mb-1.5">Prioriteit <span class="text-red-500">*</span></label>
                            <select name="priority" id="priority" required
                                    class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Laag</option>
                                <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Normaal</option>
                                <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>Hoog</option>
                                <option value="urgent" {{ old('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priority')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="location_id" class="block text-sm font-medium text-slate-700 mb-1.5">Locatie</label>
                            <select name="location_id" id="location_id"
                                    class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Alle locaties / algemeen</option>
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
                    <div>
                        <label for="schedule_type" class="block text-sm font-medium text-slate-700 mb-1.5">Herhalingen <span class="text-red-500">*</span></label>
                        <select name="schedule_type" id="schedule_type" required
                                class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                onchange="toggleScheduleConfig()">
                            <option value="" disabled {{ old('schedule_type') ? '' : 'selected' }}>Kies herhaling</option>
                            <option value="once" {{ old('schedule_type') === 'once' ? 'selected' : '' }}>Eenmalig</option>
                            <option value="daily" {{ old('schedule_type') === 'daily' ? 'selected' : '' }}>Dagelijks (elke dag)</option>
                            <option value="weekly" {{ old('schedule_type') === 'weekly' ? 'selected' : '' }}>Wekelijks (vaste dagen)</option>
                            <option value="monthly" {{ old('schedule_type') === 'monthly' ? 'selected' : '' }}>Maandelijks</option>
                            <option value="custom" {{ old('schedule_type') === 'custom' ? 'selected' : '' }}>Aangepast</option>
                        </select>
                        @error('schedule_type')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Planning configuratie --}}
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
                                    $selectedDays = old('selected_days', []);
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
                            <label for="day_of_month" class="block text-sm font-medium text-slate-700 mb-1.5">Dag van de maand</label>
                            <select name="schedule_config[day_of_month]" id="day_of_month"
                                    class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{ $i }}" {{ old('schedule_config.day_of_month', 1) == $i ? 'selected' : '' }}>
                                        {{ $i }}{{ $i == 1 ? 'e' : 'e' }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div id="custom-config" class="hidden space-y-4">
                            <div>
                                <label for="custom_type" class="block text-sm font-medium text-slate-700 mb-1.5">Type planning</label>
                                <select name="schedule_config[type]" id="custom_type"
                                        class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500"
                                        onchange="toggleCustomType()">
                                    <option value="specific_days" {{ old('schedule_config.type') === 'specific_days' ? 'selected' : '' }}>Specifieke dagen</option>
                                    <option value="interval" {{ old('schedule_config.type') === 'interval' ? 'selected' : '' }}>Elke X dagen</option>
                                    <option value="date_range" {{ old('schedule_config.type') === 'date_range' ? 'selected' : '' }}>Periode</option>
                                </select>
                            </div>
                            <div id="custom-specific-days" class="hidden">
                                <label class="block text-sm font-medium text-slate-700 mb-2">Selecteer dagen</label>
                                <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
                                    @php $customSelectedDays = old('schedule_config.days', []); @endphp
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
                                    <label for="interval_days" class="block text-sm font-medium text-slate-700 mb-1.5">Elke X dagen</label>
                                    <input type="number" name="schedule_config[interval_days]" id="interval_days" min="1" max="365"
                                           class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm"
                                           value="{{ old('schedule_config.interval_days', 1) }}">
                                </div>
                                <div>
                                    <label for="interval_start" class="block text-sm font-medium text-slate-700 mb-1.5">Startdatum</label>
                                    <input type="date" name="schedule_config[start_date]" id="interval_start"
                                           class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm"
                                           value="{{ old('schedule_config.start_date') }}">
                                </div>
                            </div>
                            <div id="custom-date-range" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="range_start" class="block text-sm font-medium text-slate-700 mb-1.5">Startdatum</label>
                                    <input type="date" name="schedule_config[start_date]" id="range_start"
                                           class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm"
                                           value="{{ old('schedule_config.start_date') }}">
                                </div>
                                <div>
                                    <label for="range_end" class="block text-sm font-medium text-slate-700 mb-1.5">Einddatum</label>
                                    <input type="date" name="schedule_config[end_date]" id="range_end"
                                           class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm"
                                           value="{{ old('schedule_config.end_date') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Extra opties --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Extra opties</h2>
                </div>
                <div class="p-4 sm:p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-slate-700 mb-1.5">Uiterste datum</label>
                            <input type="datetime-local" name="due_date" id="due_date"
                                   class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500"
                                   value="{{ old('due_date') }}">
                            @error('due_date')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="parent_list_id" class="block text-sm font-medium text-slate-700 mb-1.5">Bovenliggende lijst</label>
                            <select name="parent_list_id" id="parent_list_id"
                                    class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
                                <option value="">Geen — dit is een hoofdlijst</option>
                                @foreach($parentLists as $parentList)
                                    <option value="{{ $parentList->id }}" {{ old('parent_list_id') == $parentList->id ? 'selected' : '' }}>
                                        {{ $parentList->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_list_id')
                                <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="space-y-4 pt-2">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="requires_signature" value="1" {{ old('requires_signature') ? 'checked' : '' }}
                                   class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-slate-700">Digitale handtekening vereist bij afronding</span>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="mt-1 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-slate-700">Actief — medewerkers kunnen deze lijst zien en uitvoeren</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Acties --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <a href="{{ route('admin.lists.index') }}"
                   class="inline-flex items-center justify-center gap-2 px-5 py-2.5 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Annuleren
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Lijst aanmaken
                </button>
            </div>
        </form>

        {{-- Info --}}
        <div class="mt-8 p-4 sm:p-6 bg-blue-50 border border-blue-100 rounded-xl">
            <h3 class="text-sm font-semibold text-blue-900 mb-2">Wat gebeurt er daarna?</h3>
            <p class="text-sm text-blue-800">
                Na het aanmaken kun je taken toevoegen, aan medewerkers toewijzen en de lijst bewerken. Bij een template worden de taken automatisch overgenomen.
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

    const aiButton = document.getElementById('ai-generate-list-button');
    const aiPrompt = document.getElementById('ai-list-prompt');
    const aiFileInput = document.getElementById('ai-source-file');
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const categoryInput = document.getElementById('category');
    const tasksPreview = document.getElementById('ai-tasks-preview');
    const tasksPreviewList = document.getElementById('ai-tasks-preview-list');
    const aiTasksJsonInput = document.getElementById('ai-tasks-json');

    if (aiButton) {
        aiButton.addEventListener('click', async function () {
            const prompt = aiPrompt ? aiPrompt.value.trim() : '';
            const file = aiFileInput && aiFileInput.files.length > 0 ? aiFileInput.files[0] : null;

            if (!prompt && !file) {
                alert('Typ een korte beschrijving of kies een bestand voor de AI.');
                if (aiPrompt) aiPrompt.focus();
                return;
            }

            const formData = new FormData();
            if (prompt) formData.append('prompt', prompt);
            if (file) formData.append('source_file', file);

            aiButton.disabled = true;
            aiButton.classList.add('opacity-70', 'cursor-wait');
            const span = aiButton.querySelector('span');
            const originalLabel = span ? span.textContent : '';
            if (span) span.textContent = 'AI is bezig...';

            try {
                const response = await fetch('{{ route('admin.lists.ai-generate') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData,
                });

                let result = null;
                try {
                    result = await response.json();
                } catch (parseError) {
                    console.error('AI lijst parse error', parseError);
                }

                if (!response.ok) {
                    console.error('AI lijst response', response, result);

                    // Toon server-boodschap als die er is
                    if (result && typeof result.message === 'string') {
                        alert(result.message);
                        return;
                    }

                    // Laravel validation errors (422) hebben vaak errors-object
                    if (result && result.errors) {
                        const firstField = Object.keys(result.errors)[0];
                        const firstMsg = result.errors[firstField][0] || null;
                        if (firstMsg) {
                            alert(firstMsg);
                            return;
                        }
                    }

                    alert('AI kon geen lijstvoorstel maken. Probeer het later opnieuw.');
                    return;
                }

                if (!result || !result.success) {
                    alert((result && result.message) || 'AI kon geen lijstvoorstel maken.');
                    return;
                }

                const data = result.data || {};

                if (data.title && !titleInput.value) {
                    titleInput.value = data.title;
                }
                if (data.description && !descriptionInput.value) {
                    descriptionInput.value = data.description;
                }
                if (data.category && !categoryInput.value) {
                    categoryInput.value = data.category;
                }

                if (Array.isArray(data.tasks) && data.tasks.length > 0 && tasksPreview && tasksPreviewList) {
                    // Sla de ruwe taken op in verborgen veld zodat backend ze kan aanmaken
                    if (aiTasksJsonInput) {
                        aiTasksJsonInput.value = JSON.stringify(data.tasks);
                    }

                    tasksPreviewList.innerHTML = '';
                    data.tasks.forEach((task, index) => {
                        const li = document.createElement('li');
                        const title = typeof task.title === 'string' ? task.title : '';
                        const desc = typeof task.description === 'string' ? task.description : '';
                        li.textContent = `${index + 1}. ${title}${desc ? ' — ' + desc : ''}`;
                        tasksPreviewList.appendChild(li);
                    });
                    tasksPreview.classList.remove('hidden');
                }

            } catch (e) {
                console.error('AI list generate exception', e);
                alert('Er ging iets mis bij het aanroepen van de AI.');
            } finally {
                aiButton.disabled = false;
                aiButton.classList.remove('opacity-70', 'cursor-wait');
                if (span) span.textContent = originalLabel || 'AI lijstvoorstel maken';
            }
        });
    }
});
</script>
@endsection

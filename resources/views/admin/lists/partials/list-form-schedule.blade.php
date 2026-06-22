@props(['list' => null])

@php
    $rawScheduleType = old('schedule_type', $list?->schedule_type ?? '');
    $scheduleType = $rawScheduleType === 'custom' ? 'weekly' : $rawScheduleType;
    $scheduleCfg = is_array($list?->schedule_config ?? null) ? $list->schedule_config : [];
    $weekdaysList = ['monday' => 'Ma', 'tuesday' => 'Di', 'wednesday' => 'Wo', 'thursday' => 'Do', 'friday' => 'Vr', 'saturday' => 'Za', 'sunday' => 'Zo'];
    $selectedDays = old('selected_days', ($list && in_array($list->schedule_type, ['weekly', 'custom'], true)) ? $list->getShowOnDays() : []);
    $dueDateValue = old('due_date', $list?->due_date?->format('Y-m-d') ?? '');
    $showPlaceholder = $list === null && $scheduleType === '';
@endphp

<div>
    <x-form-label for="schedule_type" help="Bepaal hoe vaak deze lijst beschikbaar is voor medewerkers.">Herhalingen <span class="text-red-500">*</span></x-form-label>
    <select name="schedule_type" id="schedule_type" required
            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            onchange="toggleListScheduleConfig()">
        @if($showPlaceholder)
            <option value="" disabled {{ $scheduleType === '' ? 'selected' : '' }}>Kies herhaling</option>
        @endif
        <option value="once" {{ $scheduleType === 'once' ? 'selected' : '' }}>Eenmalig (één keer invullen)</option>
        <option value="daily" {{ $scheduleType === 'daily' ? 'selected' : '' }}>Dagelijks (elke dag)</option>
        <option value="weekly" {{ $scheduleType === 'weekly' ? 'selected' : '' }}>Wekelijks (vaste dagen)</option>
        <option value="monthly" {{ $scheduleType === 'monthly' ? 'selected' : '' }}>Maandelijks</option>
    </select>
    @error('schedule_type')
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<div id="schedule-config" class="hidden space-y-4">
    <div id="once-config" class="hidden">
        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl mb-4">
            <p class="text-sm text-slate-800">Deze lijst <strong>herhaalt niet</strong>. Een medewerker vult hem één keer in; daarna verdwijnt hij uit zijn taken.</p>
        </div>
        <x-form-label for="due_date" help="Optionele deadline. Na deze datum is de lijst niet meer beschikbaar als hij nog niet is ingevuld.">Uiterste datum (optioneel)</x-form-label>
        <input type="date" name="due_date" id="due_date"
               value="{{ $dueDateValue }}"
               class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
        @error('due_date')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div id="daily-config" class="hidden">
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
            <p class="text-sm text-emerald-800">Deze lijst is elke dag beschikbaar. Je kunt later per taak specifieke dagen instellen.</p>
        </div>
    </div>

    <div id="weekly-config" class="hidden space-y-4">
        <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl">
            <p class="text-sm text-blue-800">Kies op welke dagen deze lijst actief is.</p>
        </div>
        <label class="block text-sm font-medium text-slate-700">Dagen van de week <span class="font-normal text-slate-400">(selecteer zelf)</span></label>
        <div class="grid grid-cols-4 sm:grid-cols-7 gap-2">
            @foreach($weekdaysList as $dayKey => $dayLabel)
                <label class="weekday-label flex flex-col items-center justify-center p-3 border-2 border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors has-[:checked]:bg-blue-600 has-[:checked]:border-blue-600 has-[:checked]:text-white">
                    <input type="checkbox" name="selected_days[]" value="{{ $dayKey }}"
                           class="hidden weekday-checkbox"
                           {{ in_array($dayKey, $selectedDays, true) ? 'checked' : '' }}>
                    <span class="text-sm font-medium">{{ $dayLabel }}</span>
                </label>
            @endforeach
        </div>
        @error('selected_days')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div id="monthly-config" class="hidden">
        <x-form-label for="day_of_month" help="Op welke dag van de maand deze lijst actief is.">Dag van de maand</x-form-label>
        <select name="schedule_config[day_of_month]" id="day_of_month"
                class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500">
            @for($i = 1; $i <= 31; $i++)
                <option value="{{ $i }}" {{ (int) old('schedule_config.day_of_month', $scheduleCfg['day_of_month'] ?? 1) === $i ? 'selected' : '' }}>
                    {{ $i }}e
                </option>
            @endfor
        </select>
        @error('schedule_config.day_of_month')
            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<script>
let listScheduleTypeInitialized = false;

function toggleListScheduleConfig() {
    const type = document.getElementById('schedule_type')?.value;
    const config = document.getElementById('schedule-config');
    const sections = ['once', 'daily', 'weekly', 'monthly'];

    if (listScheduleTypeInitialized && type === 'weekly') {
        document.querySelectorAll('.weekday-checkbox').forEach((input) => {
            input.checked = false;
        });
    }

    sections.forEach((key) => {
        document.getElementById(`${key}-config`)?.classList.add('hidden');
    });

    if (!type || !config) {
        return;
    }

    if (type === 'once' || type === 'daily' || type === 'weekly' || type === 'monthly') {
        config.classList.remove('hidden');
        document.getElementById(`${type}-config`)?.classList.remove('hidden');
    } else {
        config.classList.add('hidden');
    }

    listScheduleTypeInitialized = true;
}

document.addEventListener('DOMContentLoaded', toggleListScheduleConfig);
</script>

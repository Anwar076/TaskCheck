@php
    use App\Services\Admin\ListCalendarService;

    $weekdayLabels = ListCalendarService::WEEKDAY_LABELS_FULL;
    $initialTimeSlots = collect(old('time_slots', []))
        ->filter(fn ($slot) => is_array($slot) && ! empty($slot['weekday']) && ! empty($slot['start_time']))
        ->values()
        ->all();
    $defaultEnabled = old('default_time_slot_enabled', false);
    $defaultStart = old('default_time_slot_start', '09:00');
    $defaultEnd = old('default_time_slot_end', '10:00');
@endphp

<div id="list-form-time-slots" class="border-t border-slate-100 pt-5 mt-5">
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900">Tijdslots in agenda</h3>
        <p class="mt-0.5 text-xs text-slate-500">
            Stel per dag een eigen tijd in, bijv. maandag 08:00–16:00 en dinsdag 11:00–19:00.
            De lijst wordt automatisch op die dagen gepland.
        </p>
    </div>

    {{-- Per dag --}}
    <div class="mb-4">
        <p class="text-xs font-medium text-slate-700">Tijdslot per dag</p>
        <p class="mt-0.5 text-[11px] text-slate-500">Voeg per weekdag het gewenste tijdslot toe.</p>
    </div>

    <div id="list-time-slots-list" class="space-y-2 mb-4">
        @forelse($initialTimeSlots as $index => $slot)
            @php
                $weekday = $slot['weekday'] ?? '';
                $start = isset($slot['start_time']) ? substr($slot['start_time'], 0, 5) : '';
                $end = ! empty($slot['end_time']) ? substr($slot['end_time'], 0, 5) : '';
                $timeLabel = $start . ($end ? ' – ' . $end : '');
            @endphp
            <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5"
                 data-time-slot-row
                 data-slot-index="{{ $index }}">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-900">{{ $weekdayLabels[$weekday] ?? ucfirst($weekday) }}</p>
                    <p class="text-xs text-slate-500">{{ $timeLabel }}</p>
                </div>
                <button type="button"
                        class="shrink-0 rounded-lg px-2.5 py-1 text-xs font-medium text-red-600 hover:bg-red-50"
                        data-remove-time-slot>
                    Verwijderen
                </button>
                <input type="hidden" name="time_slots[{{ $index }}][weekday]" value="{{ $weekday }}" data-slot-weekday>
                <input type="hidden" name="time_slots[{{ $index }}][start_time]" value="{{ $start }}" data-slot-start>
                <input type="hidden" name="time_slots[{{ $index }}][end_time]" value="{{ $end }}" data-slot-end>
            </div>
        @empty
            <p id="list-time-slots-empty" class="rounded-xl border border-dashed border-slate-200 px-3 py-4 text-center text-xs text-slate-400">
                Nog geen tijdslots toegevoegd.
            </p>
        @endforelse
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4 mb-5">
        <p class="mb-3 text-xs font-medium text-slate-700">Tijdslot toevoegen</p>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label for="list-time-slot-weekday" class="mb-1 block text-xs text-slate-500">Weekdag</label>
                <select id="list-time-slot-weekday"
                        class="block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    @foreach($weekdayLabels as $dayKey => $dayLabel)
                        <option value="{{ $dayKey }}">{{ $dayLabel }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="list-time-slot-start" class="mb-1 block text-xs text-slate-500">Start</label>
                <input type="time" id="list-time-slot-start" value="08:00"
                       class="block w-full rounded-lg border border-slate-200 px-2 py-2 text-sm">
            </div>
            <div>
                <label for="list-time-slot-end" class="mb-1 block text-xs text-slate-500">Eind</label>
                <input type="time" id="list-time-slot-end" value="16:00"
                       class="block w-full rounded-lg border border-slate-200 px-2 py-2 text-sm">
            </div>
        </div>
        <p id="list-time-slot-error" class="mt-2 hidden rounded-lg bg-red-50 px-3 py-2 text-xs text-red-700"></p>
        <button type="button"
                id="list-time-slot-add"
                class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tijdslot toevoegen
        </button>
    </div>

    @error('time_slots')
        <p class="mb-4 text-xs text-red-600">{{ $message }}</p>
    @enderror
    @error('time_slots.*.start_time')
        <p class="mb-4 text-xs text-red-600">{{ $message }}</p>
    @enderror
    @error('time_slots.*.end_time')
        <p class="mb-4 text-xs text-red-600">{{ $message }}</p>
    @enderror

    {{-- Standaard fallback --}}
    <div class="rounded-xl border border-blue-100 bg-blue-50/50 p-4">
        <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox"
                   name="default_time_slot_enabled"
                   value="1"
                   id="default-time-slot-enabled"
                   class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                   @checked($defaultEnabled)>
            <span>
                <span class="block text-sm font-medium text-slate-900">Standaardtijd voor overige dagen</span>
                <span class="mt-0.5 block text-xs text-slate-500">
                    Optioneel: geldt voor geplande dagen zonder eigen tijdslot.
                </span>
            </span>
        </label>

        <div id="default-time-slot-fields" class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3 {{ $defaultEnabled ? '' : 'hidden' }}">
            <div>
                <label for="default-time-slot-start" class="mb-1 block text-xs text-slate-500">Start</label>
                <input type="time"
                       name="default_time_slot_start"
                       id="default-time-slot-start"
                       value="{{ $defaultStart }}"
                       class="block w-full rounded-lg border border-slate-200 bg-white px-2 py-2 text-sm">
            </div>
            <div>
                <label for="default-time-slot-end" class="mb-1 block text-xs text-slate-500">Eind</label>
                <input type="time"
                       name="default_time_slot_end"
                       id="default-time-slot-end"
                       value="{{ $defaultEnd }}"
                       class="block w-full rounded-lg border border-slate-200 bg-white px-2 py-2 text-sm">
            </div>
        </div>

        @error('default_time_slot_start')
            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
        @enderror
        @error('default_time_slot_end')
            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const weekdayLabels = @json($weekdayLabels);
    const listEl = document.getElementById('list-time-slots-list');
    const emptyEl = document.getElementById('list-time-slots-empty');
    const addBtn = document.getElementById('list-time-slot-add');
    const weekdayInput = document.getElementById('list-time-slot-weekday');
    const startInput = document.getElementById('list-time-slot-start');
    const endInput = document.getElementById('list-time-slot-end');
    const errorBox = document.getElementById('list-time-slot-error');
    const defaultEnabled = document.getElementById('default-time-slot-enabled');
    const defaultFields = document.getElementById('default-time-slot-fields');

    let slotIndex = listEl?.querySelectorAll('[data-time-slot-row]').length || 0;

    defaultEnabled?.addEventListener('change', () => {
        defaultFields?.classList.toggle('hidden', !defaultEnabled.checked);
    });

    const showError = (message) => {
        if (!errorBox) return;
        errorBox.textContent = message;
        errorBox.classList.remove('hidden');
    };

    const hideError = () => {
        if (!errorBox) return;
        errorBox.classList.add('hidden');
        errorBox.textContent = '';
    };

    const hideEmpty = () => {
        emptyEl?.classList.add('hidden');
    };

    const showEmptyIfNeeded = () => {
        if (!listEl || !emptyEl) return;
        if (listEl.querySelectorAll('[data-time-slot-row]').length === 0) {
            emptyEl.classList.remove('hidden');
        }
    };

    const reindexRows = () => {
        listEl?.querySelectorAll('[data-time-slot-row]').forEach((row, index) => {
            row.dataset.slotIndex = String(index);
            row.querySelector('[data-slot-weekday]')?.setAttribute('name', `time_slots[${index}][weekday]`);
            row.querySelector('[data-slot-start]')?.setAttribute('name', `time_slots[${index}][start_time]`);
            row.querySelector('[data-slot-end]')?.setAttribute('name', `time_slots[${index}][end_time]`);
        });
        slotIndex = listEl?.querySelectorAll('[data-time-slot-row]').length || 0;
    };

    const bindRemove = (btn) => {
        btn.addEventListener('click', () => {
            btn.closest('[data-time-slot-row]')?.remove();
            reindexRows();
            showEmptyIfNeeded();
        });
    };

    listEl?.querySelectorAll('[data-remove-time-slot]').forEach(bindRemove);

    addBtn?.addEventListener('click', () => {
        hideError();

        const weekday = weekdayInput?.value;
        const start = startInput?.value;
        const end = endInput?.value || '';

        if (!weekday || !start) {
            showError('Vul weekdag en starttijd in.');
            return;
        }

        if (end && end <= start) {
            showError('Eindtijd moet na starttijd liggen.');
            return;
        }

        listEl?.querySelectorAll('[data-time-slot-row]').forEach((existingRow) => {
            if (existingRow.querySelector('[data-slot-weekday]')?.value === weekday) {
                existingRow.remove();
            }
        });
        reindexRows();

        hideEmpty();

        const row = document.createElement('div');
        row.className = 'flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5';
        row.dataset.timeSlotRow = '';
        row.dataset.slotIndex = String(slotIndex);

        const label = weekdayLabels[weekday] || weekday;
        const timeLabel = start + (end ? ` – ${end}` : '');

        row.innerHTML = `
            <div class="min-w-0">
                <p class="text-sm font-medium text-slate-900">${label}</p>
                <p class="text-xs text-slate-500">${timeLabel}</p>
            </div>
            <button type="button" class="shrink-0 rounded-lg px-2.5 py-1 text-xs font-medium text-red-600 hover:bg-red-50" data-remove-time-slot>
                Verwijderen
            </button>
            <input type="hidden" name="time_slots[${slotIndex}][weekday]" value="${weekday}" data-slot-weekday>
            <input type="hidden" name="time_slots[${slotIndex}][start_time]" value="${start}" data-slot-start>
            <input type="hidden" name="time_slots[${slotIndex}][end_time]" value="${end}" data-slot-end>
        `;

        listEl?.appendChild(row);
        bindRemove(row.querySelector('[data-remove-time-slot]'));
        reindexRows();
    });
});
</script>

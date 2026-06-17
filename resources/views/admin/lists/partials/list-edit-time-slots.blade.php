@php
    use App\Services\Admin\ListCalendarService;

    $timeSlots = $timeSlots ?? [];
    $defaultTimeSlot = $defaultTimeSlot ?? null;
    $weekdayLabels = ListCalendarService::WEEKDAY_LABELS_FULL;
    $storeUrl = route('admin.lists.schedule-slot', $list);
    $defaultEnabled = old('default_time_slot_enabled', $defaultTimeSlot ? true : false);
    $defaultStart = old('default_time_slot_start', $defaultTimeSlot['start_time'] ?? '09:00');
    $defaultEnd = old('default_time_slot_end', $defaultTimeSlot['end_time'] ?? '10:00');
@endphp

<div id="list-time-slots" class="border-t border-slate-100 pt-5 mt-5">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2 mb-4">
        <div>
            <h3 class="text-sm font-semibold text-slate-900">Tijdslots in agenda</h3>
            <p class="mt-0.5 text-xs text-slate-500">
                Stel een standaardtijd in voor alle geplande dagen, of voeg per dag een afwijkend tijdslot toe.
            </p>
        </div>
        <a href="{{ route('admin.lists.calendar') }}"
           class="shrink-0 text-xs font-medium text-blue-600 hover:text-blue-800">
            Open agenda
        </a>
    </div>

    {{-- Standaard tijdslot --}}
    <div class="mb-4 rounded-xl border border-blue-100 bg-blue-50/50 p-4">
        <label class="flex items-start gap-3 cursor-pointer">
            <input type="checkbox"
                   name="default_time_slot_enabled"
                   value="1"
                   id="default-time-slot-enabled"
                   class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                   @checked($defaultEnabled)>
            <span>
                <span class="block text-sm font-medium text-slate-900">Standaard op vaste tijd in agenda</span>
                <span class="mt-0.5 block text-xs text-slate-500">
                    De lijst verschijnt op dit tijdslot op elke dag waarop ze gepland is (bijv. elke maandag 09:00–10:00).
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

        <p class="mt-3 text-[11px] text-slate-500">
            Sla op via <span class="font-medium">Wijzigingen opslaan</span> onderaan de pagina.
        </p>
    </div>

    {{-- Afwijkende tijdslots per dag --}}
    <div class="mb-3">
        <p class="text-xs font-medium text-slate-700">Afwijkende tijdslots per dag</p>
        <p class="mt-0.5 text-[11px] text-slate-500">Optioneel: overschrijft de standaardtijd op die specifieke dag.</p>
    </div>

    <div id="list-time-slots-list" class="space-y-2 mb-4">
        @forelse($timeSlots as $slot)
            @php
                $weekday = $slot['weekday'] ?? '';
                $start = isset($slot['start_time']) ? substr($slot['start_time'], 0, 5) : '';
                $end = ! empty($slot['end_time']) ? substr($slot['end_time'], 0, 5) : null;
                $timeLabel = $start . ($end ? ' – ' . $end : '');
            @endphp
            <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5"
                 data-time-slot-row
                 data-slot-id="{{ $slot['id'] }}">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-900">{{ $weekdayLabels[$weekday] ?? ucfirst($weekday) }}</p>
                    <p class="text-xs text-slate-500">{{ $timeLabel }}</p>
                </div>
                <button type="button"
                        class="shrink-0 rounded-lg px-2.5 py-1 text-xs font-medium text-red-600 hover:bg-red-50"
                        data-delete-time-slot
                        data-delete-url="{{ route('admin.lists.schedule-slot.destroy', [$list, $slot['id']]) }}">
                    Verwijderen
                </button>
            </div>
        @empty
            <p id="list-time-slots-empty" class="rounded-xl border border-dashed border-slate-200 px-3 py-4 text-center text-xs text-slate-400">
                Geen afwijkende tijdslots.
            </p>
        @endforelse
    </div>

    <div class="rounded-xl border border-slate-200 bg-white p-4">
        <p class="mb-3 text-xs font-medium text-slate-700">Afwijkend tijdslot toevoegen</p>
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
                <input type="time" id="list-time-slot-start" value="09:00"
                       class="block w-full rounded-lg border border-slate-200 px-2 py-2 text-sm">
            </div>
            <div>
                <label for="list-time-slot-end" class="mb-1 block text-xs text-slate-500">Eind</label>
                <input type="time" id="list-time-slot-end" value="10:00"
                       class="block w-full rounded-lg border border-slate-200 px-2 py-2 text-sm">
            </div>
        </div>
        <p id="list-time-slot-error" class="mt-2 hidden rounded-lg bg-red-50 px-3 py-2 text-xs text-red-700"></p>
        <button type="button"
                id="list-time-slot-add"
                data-store-url="{{ $storeUrl }}"
                class="mt-3 inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Tijdslot toevoegen
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    const addBtn = document.getElementById('list-time-slot-add');
    const weekdayInput = document.getElementById('list-time-slot-weekday');
    const startInput = document.getElementById('list-time-slot-start');
    const endInput = document.getElementById('list-time-slot-end');
    const errorBox = document.getElementById('list-time-slot-error');
    const defaultEnabled = document.getElementById('default-time-slot-enabled');
    const defaultFields = document.getElementById('default-time-slot-fields');

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

    addBtn?.addEventListener('click', async () => {
        hideError();

        const payload = {
            weekday: weekdayInput?.value,
            start_time: startInput?.value,
            end_time: endInput?.value || null,
        };

        if (!payload.weekday || !payload.start_time) {
            showError('Vul weekdag en starttijd in.');
            return;
        }

        addBtn.disabled = true;

        try {
            const response = await fetch(addBtn.dataset.storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                showError(data.message || data.errors?.end_time?.[0] || 'Tijdslot toevoegen mislukt.');
                addBtn.disabled = false;
                return;
            }

            window.location.reload();
        } catch {
            showError('Tijdslot toevoegen mislukt. Probeer opnieuw.');
            addBtn.disabled = false;
        }
    });

    document.querySelectorAll('[data-delete-time-slot]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            if (!window.confirm('Weet je zeker dat je dit tijdslot wilt verwijderen?')) {
                return;
            }

            hideError();
            btn.disabled = true;

            try {
                const response = await fetch(btn.dataset.deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    showError(data.message || 'Verwijderen mislukt.');
                    btn.disabled = false;
                    return;
                }

                window.location.reload();
            } catch {
                showError('Verwijderen mislukt. Probeer opnieuw.');
                btn.disabled = false;
            }
        });
    });
});
</script>

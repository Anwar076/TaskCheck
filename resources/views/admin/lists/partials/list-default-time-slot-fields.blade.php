@php
    $defaultEnabled = old('default_time_slot_enabled', $defaultTimeSlot ? true : ($defaultEnabled ?? false));
    $defaultStart = old('default_time_slot_start', $defaultTimeSlot['start_time'] ?? '09:00');
    $defaultEnd = old('default_time_slot_end', $defaultTimeSlot['end_time'] ?? '10:00');
    $saveHint = $saveHint ?? 'Sla op via de knop onderaan de pagina.';
@endphp

<div id="list-default-time-slot" class="border-t border-slate-100 pt-5 mt-5">
    <div class="mb-4">
        <h3 class="text-sm font-semibold text-slate-900">Tijdslot in agenda</h3>
        <p class="mt-0.5 text-xs text-slate-500">
            Optioneel: laat de lijst op een vaste tijd verschijnen in de agenda op elke geplande dag.
        </p>
    </div>

    <div class="rounded-xl border border-blue-100 bg-blue-50/50 p-4">
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
                    Bijv. elke maandag 09:00–10:00 wanneer de lijst op maandag gepland staat.
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

        <p class="mt-3 text-[11px] text-slate-500">{{ $saveHint }}</p>
    </div>
</div>

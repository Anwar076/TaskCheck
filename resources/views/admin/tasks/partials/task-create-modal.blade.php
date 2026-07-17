@php
    $weekdayShort = ['monday' => 'Ma', 'tuesday' => 'Di', 'wednesday' => 'Wo', 'thursday' => 'Do', 'friday' => 'Vr', 'saturday' => 'Za', 'sunday' => 'Zo'];
    $showDayPicker = in_array($list->schedule_type, ['daily', 'weekly', 'custom']);
    $listAvailableDays = $showDayPicker ? $list->getShowOnDays() : [];
@endphp

<div id="task-create-modal"
     class="fixed inset-0 z-[120] hidden"
     role="dialog"
     aria-modal="true"
     aria-labelledby="task-create-modal-title"
     data-task-create-modal
     data-store-url="{{ route('admin.lists.tasks.store', $list) }}"
     data-form-data-url="{{ url('/admin/tasks') }}"
     data-show-day-picker="{{ $showDayPicker ? '1' : '0' }}"
     data-auto-open="{{ request('addTask') ? '1' : '0' }}"
     data-auto-edit-task="{{ request('editTask', '') }}"
     data-preset-weekday="{{ request('weekday', '') }}">
    <div class="absolute inset-0 bg-slate-900/40" data-task-create-close></div>

    <div class="absolute inset-x-0 bottom-0 flex max-h-[92vh] flex-col rounded-t-2xl border border-slate-200 bg-white shadow-2xl sm:inset-x-auto sm:left-1/2 sm:top-1/2 sm:max-h-[85vh] sm:w-full sm:max-w-lg sm:-translate-x-1/2 sm:-translate-y-1/2 sm:rounded-2xl">
        <form id="task-create-form" class="flex min-h-0 flex-1 flex-col">
            <div class="flex shrink-0 items-start justify-between gap-3 border-b border-slate-100 px-4 py-4 sm:px-5">
                <div class="min-w-0">
                    <h3 id="task-create-modal-title" class="text-base font-semibold text-slate-900">Nieuwe taak</h3>
                    <p class="mt-0.5 truncate text-xs text-slate-500">In lijst: {{ $list->title }}</p>
                </div>
                <button type="button" data-task-create-close class="rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600" aria-label="Sluiten">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="min-h-0 flex-1 overflow-y-auto px-4 py-4 sm:px-5">
                <div class="space-y-4">
                    <div>
                        <label for="task-create-title" class="mb-1 block text-xs font-medium text-slate-600">Taaktitel <span class="text-red-500">*</span></label>
                        <input type="text" id="task-create-title" required autocomplete="off"
                               class="block w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                               placeholder="Bijv. Prullenbakken legen">
                    </div>

                    <div>
                        <label for="task-create-proof" class="mb-1 block text-xs font-medium text-slate-600">Bewijstype</label>
                        <select id="task-create-proof" class="block w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <option value="none">Geen bewijs vereist</option>
                            <option value="photo">Foto vereist</option>
                            <option value="video">Video vereist</option>
                            <option value="text">Tekstnotitie vereist</option>
                            <option value="file">Bestand upload vereist</option>
                            <option value="any">Elk bewijstype</option>
                        </select>
                    </div>

                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" id="task-create-required" checked class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span class="text-sm text-slate-700">Verplichte taak</span>
                    </label>

                    <button type="button" id="task-create-expand-toggle"
                            class="flex w-full items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-sm font-medium text-slate-700 hover:bg-slate-100">
                        <span>Meer opties</span>
                        <svg id="task-create-expand-icon" class="h-4 w-4 text-slate-400 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div id="task-create-expanded" class="hidden space-y-4 border-t border-slate-100 pt-4">
                        <div>
                            <label for="task-create-description" class="mb-1 block text-xs font-medium text-slate-600">Omschrijving</label>
                            <textarea id="task-create-description" rows="2"
                                      class="block w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                      placeholder="Korte omschrijving…"></textarea>
                        </div>

                        <div>
                            <label for="task-create-norm-reference" class="mb-1 block text-xs font-medium text-slate-600">Controle- of normreferentie</label>
                            <input type="text" id="task-create-norm-reference" maxlength="255" placeholder="Bijv. Werkprotocol §4.3 of ISO-clausule 8.5" class="block w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                            <p class="mt-1 text-[11px] text-slate-400">Maakt in rapportages herleidbaar waarop dit controlepunt is gebaseerd.</p>
                        </div>

                        <div>
                            <label for="task-create-instructions" class="mb-1 block text-xs font-medium text-slate-600">Instructies</label>
                            <textarea id="task-create-instructions" rows="3"
                                      class="block w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"
                                      placeholder="Stap-voor-stap instructies…"></textarea>
                        </div>

                        @if($showDayPicker)
                            <div>
                                <p class="mb-2 text-xs font-medium text-slate-600">Alleen op deze dagen <span class="font-normal text-slate-400">(leeg = elke actieve dag)</span></p>
                                <div class="grid grid-cols-7 gap-1.5">
                                    @foreach($weekdayShort as $dayKey => $dayLabel)
                                        @php $available = in_array($dayKey, $listAvailableDays); @endphp
                                        <label class="flex flex-col items-center rounded-lg border px-1 py-2 text-center text-[11px] font-medium transition-colors
                                            {{ $available ? 'cursor-pointer border-slate-200 bg-white hover:border-blue-300 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-600 has-[:checked]:text-white' : 'cursor-not-allowed border-slate-100 bg-slate-50 text-slate-300' }}">
                                            @if($available)
                                                <input type="checkbox" class="hidden task-create-weekday" value="{{ $dayKey }}">
                                            @endif
                                            {{ $dayLabel }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" id="task-create-signature" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-slate-700">Handtekening vereist</span>
                        </label>

                        <div class="rounded-xl border border-slate-200 p-3">
                            <p class="mb-3 text-xs font-medium text-slate-700">Meting (optioneel)</p>
                            <div class="grid grid-cols-2 gap-2">
                                <select id="task-create-metric-type" class="rounded-lg border border-slate-200 px-2 py-2 text-sm">
                                    <option value="">Geen meting</option>
                                    <option value="temperature">Temperatuur</option>
                                    <option value="ph">pH</option>
                                </select>
                                <input type="text" id="task-create-metric-unit" placeholder="Eenheid" class="rounded-lg border border-slate-200 px-2 py-2 text-sm">
                                <input type="number" step="0.1" id="task-create-metric-min" placeholder="Min" class="rounded-lg border border-slate-200 px-2 py-2 text-sm">
                                <input type="number" step="0.1" id="task-create-metric-max" placeholder="Max" class="rounded-lg border border-slate-200 px-2 py-2 text-sm">
                            </div>
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between">
                                <p class="text-xs font-medium text-slate-600">Checklist</p>
                                <button type="button" id="task-create-add-checklist" class="text-xs font-medium text-blue-600 hover:text-blue-800">+ Item</button>
                            </div>
                            <div id="task-create-checklist" class="space-y-2"></div>
                        </div>
                    </div>

                    <p id="task-create-error" class="hidden rounded-lg bg-red-50 px-3 py-2 text-xs text-red-700"></p>
                </div>
            </div>

            <div class="flex shrink-0 items-center justify-end gap-2 border-t border-slate-100 px-4 py-3 sm:px-5">
                <button type="button" data-task-create-close class="rounded-xl px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100">Annuleren</button>
                <button type="submit" id="task-create-submit" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-50">
                    Taak toevoegen
                </button>
            </div>
        </form>
    </div>
</div>

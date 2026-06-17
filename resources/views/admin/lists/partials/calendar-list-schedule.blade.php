<div id="calendar-list-schedule"
     class="fixed z-[110] hidden w-[calc(100vw-2rem)] max-w-sm rounded-xl border border-slate-200 bg-white shadow-2xl"
     role="dialog"
     aria-modal="true"
     aria-labelledby="calendar-list-schedule-title">
    <form id="calendar-list-schedule-form" class="flex flex-col">
        <div class="border-b border-slate-100 px-4 py-3">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <h3 id="calendar-list-schedule-title" class="text-sm font-semibold text-slate-900">Lijst plannen</h3>
                    <p id="calendar-list-schedule-meta" class="mt-0.5 truncate text-xs text-slate-500"></p>
                </div>
                <button type="button" data-calendar-schedule-close class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600" aria-label="Sluiten">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div class="space-y-3 px-4 py-3">
            <div id="calendar-list-select-wrap">
                <label for="calendar-list-select" class="mb-1 block text-xs font-medium text-slate-600">Lijst</label>
                <select id="calendar-list-select" required class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"></select>
            </div>

            <div>
                <label for="calendar-list-weekday" class="mb-1 block text-xs font-medium text-slate-600">Weekdag</label>
                <select id="calendar-list-weekday" name="weekday" required class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <option value="monday">Maandag</option>
                    <option value="tuesday">Dinsdag</option>
                    <option value="wednesday">Woensdag</option>
                    <option value="thursday">Donderdag</option>
                    <option value="friday">Vrijdag</option>
                    <option value="saturday">Zaterdag</option>
                    <option value="sunday">Zondag</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label for="calendar-list-start" class="mb-1 block text-xs font-medium text-slate-600">Start</label>
                    <input type="time" id="calendar-list-start" name="start_time" required class="w-full rounded-lg border border-slate-200 px-2 py-2 text-sm">
                </div>
                <div>
                    <label for="calendar-list-end" class="mb-1 block text-xs font-medium text-slate-600">Eind</label>
                    <input type="time" id="calendar-list-end" name="end_time" class="w-full rounded-lg border border-slate-200 px-2 py-2 text-sm">
                </div>
            </div>

            <p id="calendar-list-schedule-error" class="hidden rounded-lg bg-red-50 px-3 py-2 text-xs text-red-700"></p>
        </div>

        <div class="flex items-center justify-between gap-2 border-t border-slate-100 px-4 py-3">
            <div class="flex items-center gap-2">
                <button type="button"
                        id="calendar-list-schedule-delete"
                        class="hidden rounded-lg px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50">
                    Verwijderen
                </button>
                <a id="calendar-list-schedule-manage"
                   href="#"
                   class="hidden text-sm font-medium text-slate-600 hover:text-blue-600">
                    Taken beheren
                </a>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" data-calendar-schedule-close class="rounded-lg px-3 py-1.5 text-sm font-medium text-slate-600 hover:bg-slate-100">Annuleren</button>
                <button type="submit" id="calendar-list-schedule-submit" class="rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50">
                    Koppelen
                </button>
            </div>
        </div>
    </form>
</div>

<div id="calendar-list-schedule-backdrop" class="fixed inset-0 z-[105] hidden bg-slate-900/20"></div>

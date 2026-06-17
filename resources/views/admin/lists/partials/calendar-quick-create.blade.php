<div id="calendar-quick-create"
     class="fixed z-[110] hidden w-[calc(100vw-2rem)] max-w-sm rounded-xl border border-slate-200 bg-white shadow-2xl transition-all duration-200"
     role="dialog"
     aria-modal="true"
     aria-labelledby="calendar-quick-create-title">
    <form id="calendar-quick-create-form" class="flex flex-col">
        <div class="border-b border-slate-100 px-4 py-3">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0">
                    <h3 id="calendar-quick-create-title" class="text-sm font-semibold text-slate-900">Nieuwe taak</h3>
                    <p id="calendar-quick-create-meta" class="mt-0.5 truncate text-xs text-slate-500"></p>
                </div>
                <button type="button" data-calendar-quick-close class="rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600" aria-label="Sluiten">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <div class="space-y-3 px-4 py-3">
            <div id="calendar-quick-list-wrap" class="hidden">
                <label for="calendar-quick-list" class="mb-1 block text-xs font-medium text-slate-600">Taak toevoegen aan lijst</label>
                <select id="calendar-quick-list" name="list_id" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"></select>
            </div>

            <div id="calendar-quick-existing-wrap" class="hidden">
                <label for="calendar-quick-existing-task" class="mb-1 block text-xs font-medium text-slate-600">Bestaande taak koppelen</label>
                <select id="calendar-quick-existing-task" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    <option value="">— Nieuwe taak aanmaken —</option>
                </select>
            </div>

            <div id="calendar-quick-title-wrap">
                <label for="calendar-quick-title" class="mb-1 block text-xs font-medium text-slate-600">Taaknaam</label>
                <input type="text" id="calendar-quick-title" name="title" required maxlength="255" autocomplete="off"
                       placeholder="Wat moet er gebeuren?"
                       class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div id="calendar-quick-time-row" class="grid grid-cols-2 gap-2">
                <div>
                    <label for="calendar-quick-start" class="mb-1 block text-xs font-medium text-slate-600">Start</label>
                    <input type="time" id="calendar-quick-start" name="start_time" class="w-full rounded-lg border border-slate-200 px-2 py-2 text-sm">
                </div>
                <div>
                    <label for="calendar-quick-end" class="mb-1 block text-xs font-medium text-slate-600">Eind</label>
                    <input type="time" id="calendar-quick-end" name="end_time" class="w-full rounded-lg border border-slate-200 px-2 py-2 text-sm">
                </div>
            </div>

            <div id="calendar-quick-extended" class="hidden space-y-3 border-t border-slate-100 pt-3">
                <div>
                    <label for="calendar-quick-description" class="mb-1 block text-xs font-medium text-slate-600">Omschrijving</label>
                    <textarea id="calendar-quick-description" name="description" rows="2" placeholder="Optioneel"
                              class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
                </div>
                <div>
                    <label for="calendar-quick-instructions" class="mb-1 block text-xs font-medium text-slate-600">Instructies</label>
                    <textarea id="calendar-quick-instructions" name="instructions" rows="2" placeholder="Optioneel"
                              class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20"></textarea>
                </div>
                <div>
                    <label for="calendar-quick-proof" class="mb-1 block text-xs font-medium text-slate-600">Bewijs vereist</label>
                    <select id="calendar-quick-proof" name="required_proof_type" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm">
                        <option value="none">Geen</option>
                        <option value="photo">Foto</option>
                        <option value="video">Video</option>
                        <option value="text">Tekst</option>
                        <option value="file">Bestand</option>
                        <option value="any">Elk type</option>
                    </select>
                </div>
                <div class="flex flex-wrap gap-4 text-sm">
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" id="calendar-quick-required" name="is_required" value="1" checked class="rounded border-slate-300 text-blue-600">
                        <span class="text-slate-700">Verplicht</span>
                    </label>
                    <label class="inline-flex items-center gap-2">
                        <input type="checkbox" id="calendar-quick-signature" name="requires_signature" value="1" class="rounded border-slate-300 text-blue-600">
                        <span class="text-slate-700">Handtekening</span>
                    </label>
                </div>
                <a id="calendar-quick-full-link" href="#" class="inline-flex text-xs font-medium text-blue-600 hover:text-blue-800">Volledig formulier openen</a>
            </div>

            <p id="calendar-quick-error" class="hidden rounded-lg bg-red-50 px-3 py-2 text-xs text-red-700"></p>
        </div>

        <div class="flex items-center justify-between gap-2 border-t border-slate-100 px-4 py-3">
            <button type="button" id="calendar-quick-expand" class="text-xs font-medium text-slate-600 hover:text-slate-900">
                Uitgebreid
            </button>
            <div class="flex gap-2">
                <button type="button" data-calendar-quick-close class="rounded-lg px-3 py-1.5 text-sm font-medium text-slate-600 hover:bg-slate-100">Annuleren</button>
                <button type="submit" id="calendar-quick-submit" class="rounded-lg bg-blue-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-50">
                    Opslaan
                </button>
            </div>
        </div>
    </form>
</div>

<div id="calendar-quick-backdrop" class="fixed inset-0 z-[105] hidden bg-slate-900/20"></div>

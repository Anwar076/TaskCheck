@extends('layouts.super-admin')

@section('page-title', 'Global template bewerken')

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-slate-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white truncate">{{ $template->name }}</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Bewerk global template en taken</p>
                            </div>
                        </div>
                        <a href="{{ route('super-admin.templates.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18.75"/></svg>
                            Naar templates
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('super-admin.templates.update', $template) }}" id="templateForm">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Basisgegevens</h2>
                    <p class="text-slate-600 text-sm mt-0.5">Naam, doelgroep en beschrijving van het template</p>
                </div>
                <div class="p-4 sm:p-6 space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Templatenaam <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" required value="{{ old('name', $template->name) }}"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    </div>
                    <div>
                        <label for="target_company_type" class="block text-sm font-medium text-slate-700 mb-1.5">Doelgroep</label>
                        <select id="target_company_type" name="target_company_type" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Alle bedrijven</option>
                            <option value="cleaning" @selected(old('target_company_type', $template->target_company_type) === 'cleaning')>Schoonmaak</option>
                            <option value="horeca" @selected(old('target_company_type', $template->target_company_type) === 'horeca')>Horeca</option>
                            <option value="other" @selected(old('target_company_type', $template->target_company_type) === 'other')>Anders</option>
                        </select>
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">Beschrijving</label>
                        <textarea id="description" name="description" rows="3"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $template->description) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="category" class="block text-sm font-medium text-slate-700 mb-1.5">Categorie</label>
                            <input type="text" id="category" name="category" value="{{ old('category', $template->category) }}"
                                class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="icon" class="block text-sm font-medium text-slate-700 mb-1.5">Icoon</label>
                            <input type="text" id="icon" name="icon" value="{{ old('icon', $template->icon) }}"
                                class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="frequency_label" class="block text-sm font-medium text-slate-700 mb-1.5">Frequentie label</label>
                            <input type="text" id="frequency_label" name="frequency_label" value="{{ old('frequency_label', $template->frequency_label) }}"
                                class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="frequency_type" class="block text-sm font-medium text-slate-700 mb-1.5">Frequentie type</label>
                            <select id="frequency_type" name="frequency_type" class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                @php $frequencyType = old('frequency_type', $template->frequency_type ?? 'none'); @endphp
                                @foreach(['daily' => 'Dagelijks', 'weekly' => 'Wekelijks', 'monthly' => 'Maandelijks', 'quarterly' => 'Per kwartaal', 'per_batch' => 'Per batch', 'per_production' => 'Per productie', 'none' => 'Geen'] as $value => $label)
                                    <option value="{{ $value }}" @selected($frequencyType === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="starter_pack_group" class="block text-sm font-medium text-slate-700 mb-1.5">Starter pack groep</label>
                            <input type="text" id="starter_pack_group" name="starter_pack_group" value="{{ old('starter_pack_group', $template->starter_pack_group) }}"
                                class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label for="khn_reference" class="block text-sm font-medium text-slate-700 mb-1.5">KHN referentie</label>
                            <input type="text" id="khn_reference" name="khn_reference" value="{{ old('khn_reference', $template->khn_reference) }}"
                                class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div>
                        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
                            <input type="checkbox" name="is_starter_pack" value="1" @checked((bool) old('is_starter_pack', $template->is_starter_pack))>
                            Opnemen in Starter Pack
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Template-taken</h2>
                        <p class="text-slate-600 text-sm mt-0.5">Pas taken, bewijs en checklist-items aan</p>
                    </div>
                    <button type="button" onclick="addTask()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Taak toevoegen
                    </button>
                </div>
                <div class="p-4 sm:p-6">
                    <div id="tasks-container" class="space-y-4"></div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <a href="{{ route('super-admin.templates.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-slate-700 bg-white border border-slate-200 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors">
                    Annuleren
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors">
                    Wijzigingen opslaan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let taskIndex = 0;
const inputClass = 'block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent';
const labelClass = 'block text-sm font-medium text-slate-700 mb-1.5';

function esc(s) {
    if (s == null || s === '') return '';
    return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

document.addEventListener('DOMContentLoaded', () => {
    const existingTasks = @json($template->templateTasks ?? []);
    if (existingTasks.length > 0) {
        existingTasks.forEach(t => addTask(t));
    } else {
        addTask();
    }
});

function addTask(existing = null) {
    const i = taskIndex++;
    const t = existing || {};
    const taskId = t.id ?? '';
    const title = esc(t.title ?? '');
    const desc = esc(t.description ?? '');
    const instr = esc(t.instructions ?? '');
    const proofType = t.required_proof_type ?? 'none';
    const isReq = t.is_required !== false;
    const checklistRaw = t.checklist_items;
    const checklist = Array.isArray(checklistRaw)
        ? checklistRaw
        : (checklistRaw && typeof checklistRaw === 'object' ? Object.values(checklistRaw) : []);
    const startTime = t.start_time ?? '';
    const endTime = t.end_time ?? '';

    const container = document.getElementById('tasks-container');
    const div = document.createElement('div');
    div.className = 'task-item bg-slate-50 rounded-xl p-4 sm:p-5 border border-slate-100';

    let checklistHtml = '';
    if (checklist.length > 0) {
        checklist.forEach(item => {
            checklistHtml += `<div class="checklist-item flex gap-2"><input type="text" name="tasks[${i}][checklist_items][]" value="${esc(item)}" class="${inputClass} flex-1"><button type="button" onclick="removeChecklistItem(this)" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors shrink-0">✕</button></div>`;
        });
    } else {
        checklistHtml = `<div class="checklist-item flex gap-2"><input type="text" name="tasks[${i}][checklist_items][]" class="${inputClass} flex-1"><button type="button" onclick="removeChecklistItem(this)" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors shrink-0">✕</button></div>`;
    }

    div.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-900">Taak <span class="task-num">${i + 1}</span></h3>
            <button type="button" onclick="removeTask(this)" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">Verwijderen</button>
        </div>
        ${taskId ? `<input type="hidden" name="tasks[${i}][id]" value="${esc(taskId)}">` : ''}
        <div class="space-y-4">
            <div><label class="${labelClass}">Taaktitel *</label><input type="text" name="tasks[${i}][title]" required value="${title}" class="${inputClass}"></div>
            <div><label class="${labelClass}">Beschrijving</label><textarea name="tasks[${i}][description]" rows="2" class="${inputClass}">${desc}</textarea></div>
            <div><label class="${labelClass}">Instructies</label><textarea name="tasks[${i}][instructions]" rows="2" class="${inputClass}">${instr}</textarea></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="${labelClass}">Bewijstype</label>
                    <select name="tasks[${i}][required_proof_type]" class="${inputClass}">
                        <option value="none" ${proofType === 'none' ? 'selected' : ''}>Geen bewijs vereist</option>
                        <option value="photo" ${proofType === 'photo' ? 'selected' : ''}>Foto vereist</option>
                        <option value="video" ${proofType === 'video' ? 'selected' : ''}>Video vereist</option>
                        <option value="text" ${proofType === 'text' ? 'selected' : ''}>Tekstbeschrijving vereist</option>
                        <option value="file" ${proofType === 'file' ? 'selected' : ''}>Bestand uploaden vereist</option>
                        <option value="any" ${proofType === 'any' ? 'selected' : ''}>Elk bewijstype</option>
                    </select>
                </div>
                <div>
                    <label class="${labelClass}">Verplicht</label>
                    <select name="tasks[${i}][is_required]" class="${inputClass}">
                        <option value="1" ${isReq ? 'selected' : ''}>Verplicht</option>
                        <option value="0" ${!isReq ? 'selected' : ''}>Optioneel</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label class="${labelClass}">Starttijd</label><input type="time" name="tasks[${i}][start_time]" value="${esc(startTime)}" class="${inputClass}"></div>
                <div><label class="${labelClass}">Eindtijd</label><input type="time" name="tasks[${i}][end_time]" value="${esc(endTime)}" class="${inputClass}"></div>
            </div>
            <div>
                <label class="${labelClass}">Checklist-items</label>
                <div class="checklist-container space-y-2">${checklistHtml}</div>
                <button type="button" onclick="addChecklistItem(this)" class="mt-2 text-sm text-blue-700 hover:text-blue-900 font-medium">+ Checklist-item toevoegen</button>
            </div>
        </div>
    `;
    container.appendChild(div);
    updateTaskNumbers();
}

function removeTask(btn) {
    btn.closest('.task-item').remove();
    if (document.getElementById('tasks-container').children.length === 0) addTask();
    else updateTaskNumbers();
}

function updateTaskNumbers() {
    document.querySelectorAll('.task-item').forEach((task, i) => {
        task.querySelector('.task-num').textContent = i + 1;
        [...task.querySelectorAll('[name^="tasks["]')].forEach(el => {
            el.name = el.name.replace(/tasks\[\d+\]/, `tasks[${i}]`);
        });
    });
}

function addChecklistItem(btn) {
    const task = btn.closest('.task-item');
    const i = Array.from(document.querySelectorAll('.task-item')).indexOf(task);
    const container = task.querySelector('.checklist-container');
    const div = document.createElement('div');
    div.className = 'checklist-item flex gap-2';
    div.innerHTML = `<input type="text" name="tasks[${i}][checklist_items][]" class="${inputClass} flex-1"><button type="button" onclick="removeChecklistItem(this)" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors shrink-0">✕</button>`;
    container.appendChild(div);
}

function removeChecklistItem(btn) {
    btn.closest('.checklist-item')?.remove();
}
</script>
@endsection

@extends('layouts.admin')

@section('page-title', 'Template bewerken')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <a href="{{ route('admin.templates.index') }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors">Templates</a>
    <span class="text-slate-400">/</span>
    <a href="{{ route('admin.templates.show', $template) }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors truncate">{{ $template->name }}</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-semibold truncate">Bewerken</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8">

        {{-- Hero --}}
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white truncate">{{ $template->name }}</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Bewerk template en taken</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('admin.templates.show', $template) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Bekijken
                            </a>
                            <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18.75"/></svg>
                                Naar templates
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.templates.update', $template) }}" id="templateForm">
            @csrf
            @method('PUT')

            {{-- Basisgegevens --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Basisgegevens</h2>
                    <p class="text-slate-600 text-sm mt-0.5">Naam en beschrijving van het template</p>
                </div>
                <div class="p-4 sm:p-6 space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Templatenaam <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" required value="{{ old('name', $template->name) }}"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                            placeholder="Bijv. Dagelijkse schoonmaak-checklist">
                        @error('name')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">Beschrijving</label>
                        <textarea id="description" name="description" rows="3"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                            placeholder="Beschrijf waarvoor dit template gebruikt wordt...">{{ old('description', $template->description) }}</textarea>
                        @error('description')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Taken --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Template-taken</h2>
                        <p class="text-slate-600 text-sm mt-0.5">Pas de taken van dit template aan</p>
                    </div>
                    <button type="button" onclick="addTask()"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Taak toevoegen
                    </button>
                </div>
                <div class="p-4 sm:p-6">
                    <div id="tasks-container" class="space-y-4"></div>
                    @error('tasks')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Acties --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                <a href="{{ route('admin.templates.show', $template) }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-slate-700 bg-white border border-slate-200 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors">
                    Annuleren
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
    return String(s)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
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
    const checklist = Array.isArray(t.checklist_items) ? t.checklist_items : [];
    const startTime = t.start_time ?? '';
    const endTime = t.end_time ?? '';
    const metricType = t.validation_rules?.metric ?? '';
    const metricUnit = esc(t.validation_rules?.unit ?? '');
    const metricMin = t.validation_rules?.min ?? '';
    const metricMax = t.validation_rules?.max ?? '';
    const metricComparison = t.validation_rules?.comparison ?? 'lte';

    const container = document.getElementById('tasks-container');
    const div = document.createElement('div');
    div.className = 'task-item bg-slate-50 rounded-xl p-4 sm:p-5 border border-slate-100';
    div.dataset.index = i;

    let checklistHtml = '';
    if (checklist.length > 0) {
        checklist.forEach(item => {
            checklistHtml += `
                <div class="checklist-item flex gap-2">
                    <input type="text" name="tasks[${i}][checklist_items][]" value="${esc(item)}" class="${inputClass} flex-1" placeholder="Checklist-item...">
                    <button type="button" onclick="removeChecklistItem(this)" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            `;
        });
    } else {
        checklistHtml = `
            <div class="checklist-item flex gap-2">
                <input type="text" name="tasks[${i}][checklist_items][]" class="${inputClass} flex-1" placeholder="Checklist-item...">
                <button type="button" onclick="removeChecklistItem(this)" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        `;
    }

    div.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-semibold text-slate-900">Taak <span class="task-num">${i + 1}</span></h3>
            <div class="flex items-center gap-1">
                <button type="button" onclick="moveTask(this, 'up')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-200 rounded-lg transition-colors" title="Omhoog">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                </button>
                <button type="button" onclick="moveTask(this, 'down')" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-200 rounded-lg transition-colors" title="Omlaag">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <button type="button" onclick="removeTask(this)" class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors" title="Verwijderen">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                </button>
            </div>
        </div>
        <div class="space-y-4">
            <div>
                <label class="${labelClass}">Taaktitel <span class="text-red-500">*</span></label>
                <input type="text" name="tasks[${i}][title]" required value="${title}" class="${inputClass}" placeholder="Bijv. Vloeren dweilen">
                ${taskId ? `<input type="hidden" name="tasks[${i}][id]" value="${esc(taskId)}">` : ''}
            </div>
            <div>
                <label class="${labelClass}">Beschrijving</label>
                <textarea name="tasks[${i}][description]" rows="2" class="${inputClass}" placeholder="Optionele omschrijving...">${desc}</textarea>
            </div>
            <div>
                <label class="${labelClass}">Instructies</label>
                <textarea name="tasks[${i}][instructions]" rows="2" class="${inputClass}" placeholder="Gedetailleerde instructies...">${instr}</textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="${labelClass}">Bewijstype</label>
                    <select name="tasks[${i}][required_proof_type]" required class="${inputClass}">
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
                <div>
                    <label class="${labelClass}">Starttijd</label>
                    <input type="time" name="tasks[${i}][start_time]" value="${esc(startTime)}" class="${inputClass}">
                </div>
                <div>
                    <label class="${labelClass}">Eindtijd</label>
                    <input type="time" name="tasks[${i}][end_time]" value="${esc(endTime)}" class="${inputClass}">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label class="${labelClass}">Metingstype</label>
                    <select name="tasks[${i}][metric_type]" class="${inputClass}">
                        <option value="" ${metricType === '' ? 'selected' : ''}>Geen</option>
                        <option value="temperature" ${metricType === 'temperature' ? 'selected' : ''}>Temperatuur</option>
                        <option value="ph" ${metricType === 'ph' ? 'selected' : ''}>pH</option>
                    </select>
                </div>
                <div>
                    <label class="${labelClass}">Eenheid</label>
                    <input type="text" name="tasks[${i}][metric_unit]" value="${metricUnit}" class="${inputClass}" placeholder="°C of pH">
                </div>
                <div>
                    <label class="${labelClass}">Min norm</label>
                    <input type="number" step="0.1" name="tasks[${i}][metric_min]" value="${metricMin}" class="${inputClass}">
                </div>
                <div>
                    <label class="${labelClass}">Max norm</label>
                    <input type="number" step="0.1" name="tasks[${i}][metric_max]" value="${metricMax}" class="${inputClass}">
                </div>
                <div>
                    <label class="${labelClass}">Max vergelijking</label>
                    <select name="tasks[${i}][metric_comparison]" class="${inputClass}">
                        <option value="lte" ${metricComparison === 'lte' ? 'selected' : ''}><= max</option>
                        <option value="lt" ${metricComparison === 'lt' ? 'selected' : ''}>< max</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="${labelClass}">Checklist-items</label>
                <div class="checklist-container space-y-2">${checklistHtml}</div>
                <button type="button" onclick="addChecklistItem(this)" class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium">
                    + Checklist-item toevoegen
                </button>
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

function moveTask(btn, dir) {
    const item = btn.closest('.task-item');
    const container = document.getElementById('tasks-container');
    const items = [...container.querySelectorAll('.task-item')];
    const idx = items.indexOf(item);
    if (dir === 'up' && idx > 0) {
        container.insertBefore(item, items[idx - 1]);
    } else if (dir === 'down' && idx < items.length - 1) {
        container.insertBefore(items[idx + 1], item);
    }
    updateTaskNumbers();
}

function updateTaskNumbers() {
    const tasks = document.querySelectorAll('.task-item');
    tasks.forEach((task, i) => {
        task.querySelector('.task-num').textContent = i + 1;
        [...task.querySelectorAll('[name^="tasks["]')].forEach(el => {
            el.name = el.name.replace(/tasks\[\d+\]/, `tasks[${i}]`);
        });
    });
}

function addChecklistItem(btn) {
    const task = btn.closest('.task-item');
    const tasks = document.querySelectorAll('.task-item');
    const i = Array.from(tasks).indexOf(task);
    const container = task.querySelector('.checklist-container');
    const div = document.createElement('div');
    div.className = 'checklist-item flex gap-2';
    div.innerHTML = `
        <input type="text" name="tasks[${i}][checklist_items][]" class="${inputClass} flex-1" placeholder="Checklist-item...">
        <button type="button" onclick="removeChecklistItem(this)" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
    container.appendChild(div);
}

function removeChecklistItem(btn) {
    btn.closest('.checklist-item').remove();
}
</script>
@endsection

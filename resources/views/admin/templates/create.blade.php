@extends('layouts.admin')

@section('page-title', 'Template aanmaken')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <a href="{{ route('admin.templates.index') }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors">Templates</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-semibold truncate">Aanmaken</span>
@endsection

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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Nieuw template</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Maak een herbruikbaar template voor takenlijsten</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18.75"/></svg>
                            Naar templates
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.templates.store') }}" id="templateForm">
            @csrf

            {{-- Basisgegevens --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Basisgegevens</h2>
                    <p class="text-slate-600 text-sm mt-0.5">Naam en beschrijving van het template</p>
                </div>
                <div class="p-4 sm:p-6 space-y-5">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">Templatenaam <span class="text-red-500">*</span></label>
                        <input type="text" id="name" name="name" required value="{{ old('name') }}"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                            placeholder="Bijv. Dagelijkse schoonmaak-checklist">
                        @error('name')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">Beschrijving</label>
                        <textarea id="description" name="description" rows="3"
                            class="block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                            placeholder="Beschrijf waarvoor dit template gebruikt wordt...">{{ old('description') }}</textarea>
                        @error('description')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Taken --}}
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Template-taken</h2>
                        <p class="text-slate-600 text-sm mt-0.5">Voeg de taken toe die in dit template komen</p>
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
                <a href="{{ route('admin.templates.index') }}"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-slate-700 bg-white border border-slate-200 rounded-xl text-sm font-medium hover:bg-slate-50 transition-colors">
                    Annuleren
                </a>
                <button type="submit"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Template aanmaken
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let taskIndex = 0;
const inputClass = 'block w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent';
const labelClass = 'block text-sm font-medium text-slate-700 mb-1.5';

document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('tasks-container').children.length === 0) addTask();
});

function addTask() {
    const i = taskIndex++;
    const container = document.getElementById('tasks-container');
    const div = document.createElement('div');
    div.className = 'task-item bg-white rounded-xl p-4 sm:p-5 border border-slate-200 shadow-sm';
    div.dataset.index = i;
    div.innerHTML = `
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg bg-blue-600 text-white text-xs font-bold task-num">${i + 1}</span>
                Taak
            </h3>
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
            <div class="bg-slate-50 rounded-xl p-3 sm:p-4 border border-slate-100">
                <label class="${labelClass}">Taaktitel <span class="text-red-500">*</span></label>
                <input type="text" name="tasks[${i}][title]" required class="${inputClass}" placeholder="Bijv. Vloeren dweilen">
            </div>
            <div class="bg-slate-50 rounded-xl p-3 sm:p-4 border border-slate-100">
                <label class="${labelClass}">Beschrijving</label>
                <textarea name="tasks[${i}][description]" rows="2" class="${inputClass}" placeholder="Optionele omschrijving..."></textarea>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 sm:p-4 border border-slate-100">
                <label class="${labelClass}">Instructies</label>
                <textarea name="tasks[${i}][instructions]" rows="2" class="${inputClass}" placeholder="Gedetailleerde instructies..."></textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 rounded-xl p-3 sm:p-4 border border-slate-100">
                <div class="min-w-0">
                    <label class="${labelClass}">Bewijstype</label>
                    <select name="tasks[${i}][required_proof_type]" required class="${inputClass}">
                        <option value="none">Geen bewijs vereist</option>
                        <option value="photo">Foto vereist</option>
                        <option value="video">Video vereist</option>
                        <option value="text">Tekstbeschrijving vereist</option>
                        <option value="file">Bestand uploaden vereist</option>
                        <option value="any">Elk bewijstype</option>
                    </select>
                </div>
                <div class="min-w-0">
                    <label class="${labelClass}">Verplicht</label>
                    <select name="tasks[${i}][is_required]" class="${inputClass}">
                        <option value="1">Verplicht</option>
                        <option value="0">Optioneel</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 rounded-xl p-3 sm:p-4 border border-slate-100">
                <div>
                    <label class="${labelClass}">Starttijd</label>
                    <input type="time" name="tasks[${i}][start_time]" class="${inputClass}">
                </div>
                <div>
                    <label class="${labelClass}">Eindtijd</label>
                    <input type="time" name="tasks[${i}][end_time]" class="${inputClass}">
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 bg-slate-50 rounded-xl p-3 sm:p-4 border border-slate-100">
                <div>
                    <label class="${labelClass}">Metingstype</label>
                    <select name="tasks[${i}][metric_type]" class="${inputClass}">
                        <option value="">Geen</option>
                        <option value="temperature">Temperatuur</option>
                        <option value="ph">pH</option>
                    </select>
                </div>
                <div>
                    <label class="${labelClass}">Eenheid</label>
                    <input type="text" name="tasks[${i}][metric_unit]" class="${inputClass}" placeholder="°C of pH">
                </div>
                <div>
                    <label class="${labelClass}">Min norm</label>
                    <input type="number" step="0.1" name="tasks[${i}][metric_min]" class="${inputClass}">
                </div>
                <div>
                    <label class="${labelClass}">Max norm</label>
                    <input type="number" step="0.1" name="tasks[${i}][metric_max]" class="${inputClass}">
                </div>
                <div>
                    <label class="${labelClass}">Max vergelijking</label>
                    <select name="tasks[${i}][metric_comparison]" class="${inputClass}">
                        <option value="lte"><= max</option>
                        <option value="lt">< max</option>
                    </select>
                </div>
            </div>
            <div class="bg-slate-50 rounded-xl p-3 sm:p-4 border border-slate-100">
                <label class="${labelClass}">Checklist-items</label>
                <div class="checklist-container space-y-2">
                    <div class="checklist-item flex gap-2">
                        <input type="text" name="tasks[${i}][checklist_items][]" class="${inputClass} flex-1" placeholder="Checklist-item...">
                        <button type="button" onclick="removeChecklistItem(this)" class="p-2.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-colors shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
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
    const item = btn.closest('.task-item');
    item.remove();
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

@extends('layouts.admin')

@section('page-title', $template->name)

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <a href="{{ route('admin.templates.index') }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors">Templates</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-semibold truncate">{{ $template->name }}</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-5xl mx-auto px-3 sm:px-6 lg:px-8">

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3" role="alert">
                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-emerald-800 font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3" role="alert">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-red-800 font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Hero --}}
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white truncate">{{ $template->name }}</h1>
                            @if($template->description)
                                <p class="text-blue-100/90 text-sm sm:text-base mt-1 line-clamp-2">{{ $template->description }}</p>
                            @else
                                <p class="text-blue-100/70 text-sm mt-1">Geen beschrijving</p>
                            @endif
                            <div class="mt-3 flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium {{ $template->is_active ? 'bg-emerald-500/30 text-emerald-100' : 'bg-slate-500/30 text-slate-100' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $template->is_active ? 'bg-emerald-300' : 'bg-slate-300' }}"></span>
                                    {{ $template->is_active ? 'Actief' : 'Inactief' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            <a href="{{ route('admin.templates.edit', $template) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-600 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                Bewerken
                            </a>
                            <button type="button" onclick="createListFromTemplate({{ $template->id }})" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-500 text-white text-sm font-semibold rounded-xl hover:bg-emerald-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                Lijst maken
                            </button>
                            <a href="{{ route('admin.templates.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18.75"/></svg>
                                Naar overzicht
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistieken --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $template->templateTasks->count() }}</p>
                        <p class="text-sm text-slate-600">Taken</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-violet-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $template->taskLists->count() }}</p>
                        <p class="text-sm text-slate-600">Gebruikt in lijsten</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $template->templateTasks->where('is_required', true)->count() }}</p>
                        <p class="text-sm text-slate-600">Verplichte taken</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $template->created_at->format('d-m-Y') }}</p>
                        <p class="text-sm text-slate-600">Aangemaakt</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Template taken --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6 sm:mb-8">
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Template-taken</h2>
                        <p class="text-sm text-slate-600">Taken die worden gebruikt bij het maken van een lijst</p>
                    </div>
                </div>
                <a href="{{ route('admin.templates.edit', $template) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                    Taken bewerken
                </a>
            </div>

            <div class="p-4 sm:p-6">
                @if($template->templateTasks->isEmpty())
                    <div class="text-center py-12">
                        <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 mb-2">Nog geen taken</h3>
                        <p class="text-slate-600 text-sm mb-4">Voeg taken toe om het template te gebruiken</p>
                        <a href="{{ route('admin.templates.edit', $template) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Taken toevoegen
                        </a>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($template->templateTasks as $index => $task)
                            <div class="bg-slate-50 rounded-xl p-4 sm:p-5 border border-slate-100 hover:border-slate-200 transition-colors">
                                <div class="flex items-start gap-4">
                                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-slate-900 mb-1">{{ $task->title }}</h3>
                                        @if($task->description)
                                            <p class="text-sm text-slate-600 mb-3">{{ Str::limit($task->description, 120) }}</p>
                                        @endif
                                        @if($task->instructions)
                                            <div class="p-3 bg-white rounded-lg border border-slate-100 mb-3">
                                                <p class="text-sm text-slate-600 whitespace-pre-line">{{ $task->instructions }}</p>
                                            </div>
                                        @endif
                                        <div class="flex flex-wrap gap-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ ucfirst($task->required_proof_type ?? 'none') }}
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium {{ $task->is_required ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-700' }}">
                                                {{ $task->is_required ? 'Verplicht' : 'Optioneel' }}
                                            </span>
                                        </div>
                                        @if($task->checklist_items && count($task->checklist_items) > 0)
                                            <div class="mt-3 flex flex-wrap gap-1.5">
                                                @foreach(array_slice($task->checklist_items, 0, 5) as $item)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-slate-100 text-slate-700">{{ $item }}</span>
                                                @endforeach
                                                @if(count($task->checklist_items) > 5)
                                                    <span class="text-xs text-slate-500">+{{ count($task->checklist_items) - 5 }} meer</span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <a href="{{ route('admin.templates.edit', $template) }}" class="p-2 text-slate-500 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-colors flex-shrink-0" title="Bewerken">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Gebruikt in lijsten --}}
        @if($template->taskLists->count() > 0)
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Gebruikt in lijsten</h2>
                    <p class="text-slate-600 text-sm mt-0.5">Takenlijsten die van dit template zijn gemaakt</p>
                </div>
                <div class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($template->taskLists as $list)
                            <a href="{{ route('admin.lists.show', $list) }}" class="flex items-center justify-between p-4 bg-slate-50 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/50 transition-colors group">
                                <div class="min-w-0">
                                    <h3 class="font-semibold text-slate-900 truncate group-hover:text-blue-700">{{ $list->title }}</h3>
                                    <p class="text-sm text-slate-500 mt-0.5">{{ $list->created_at->locale('nl')->translatedFormat('d M Y') }}</p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 group-hover:text-blue-700 flex-shrink-0">
                                    Bekijken
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Lijst maken modal --}}
<div id="createListModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true" role="dialog">
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeCreateListModal()" aria-hidden="true"></div>
        <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 transition-all">
            <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-5 sm:px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Lijst maken</h3>
                            <p class="text-sm text-blue-100/90">Maak een takenlijst van dit template</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeCreateListModal()" class="rounded-xl p-2 text-white/80 hover:bg-white/20 hover:text-white transition-colors" aria-label="Sluiten">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <form id="createListForm" method="POST" class="px-5 sm:px-6 py-5">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label for="list_title" class="block text-sm font-semibold text-slate-900 mb-1.5">Lijstnaam <span class="text-red-500">*</span></label>
                        <input type="text" id="list_title" name="title" required value="{{ $template->name }} - {{ now()->locale('nl')->translatedFormat('d M Y') }}"
                            class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                    </div>
                    <div>
                        <label for="list_description" class="block text-sm font-semibold text-slate-900 mb-1.5">Beschrijving {{-- <span class="text-slate-400 font-normal">(optioneel)</span> --}}</label>
                        <textarea id="list_description" name="description" rows="3"
                            class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">{{ $template->description }}</textarea>
                    </div>
                </div>
                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="closeCreateListModal()"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 transition-colors">
                        Annuleren
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Lijst maken
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function createListFromTemplate(templateId) {
    const modal = document.getElementById('createListModal');
    const form = document.getElementById('createListForm');
    form.action = '/admin/templates/' + templateId + '/create-list';
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeCreateListModal() {
    document.getElementById('createListModal').classList.add('hidden');
    document.body.style.overflow = '';
}

document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('updated') === '1') {
        window.history.replaceState({}, document.title, window.location.pathname);
        showToast('Template succesvol bijgewerkt!', 'success');
        setTimeout(refreshTemplateContent, 500);
    }

    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            const lastUpdate = localStorage.getItem('template_{{ $template->id }}_updated');
            if (lastUpdate && (Date.now() - parseInt(lastUpdate)) < 300000) {
                refreshTemplateContent();
                localStorage.removeItem('template_{{ $template->id }}_updated');
            }
        }
    });

    window.addEventListener('storage', function(e) {
        if (e.key === 'template_{{ $template->id }}_updated') {
            setTimeout(refreshTemplateContent, 500);
        }
    });
});

async function refreshTemplateContent() {
    try {
        const indicator = document.createElement('div');
        indicator.id = 'refresh-indicator';
        indicator.className = 'fixed top-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-xl shadow-lg z-50 flex items-center gap-2';
        indicator.innerHTML = '<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Vernieuwen...';
        document.body.appendChild(indicator);

        const response = await fetch('/admin/templates/{{ $template->id }}', {
            headers: { 'Accept': 'text/html', 'X-Requested-With': 'XMLHttpRequest' }
        });

        if (response.ok) {
            const doc = new DOMParser().parseFromString(await response.text(), 'text/html');
            const newContent = doc.querySelector('.min-h-screen');
            const currentContent = document.querySelector('.min-h-screen');
            if (newContent && currentContent) {
                currentContent.innerHTML = newContent.innerHTML;
                showToast('Template vernieuwd!', 'success');
            }
        }

        const el = document.getElementById('refresh-indicator');
        if (el) el.remove();
    } catch (err) {
        document.getElementById('refresh-indicator')?.remove();
        window.location.reload();
    }
}

function showToast(message, type) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl shadow-lg z-50';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}
</script>
@endsection

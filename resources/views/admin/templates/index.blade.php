@extends('layouts.admin')

@section('page-title', 'Templates')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <span class="text-slate-900 font-semibold truncate">Templates</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

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
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Takenlijst-templates</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Maak en beheer templates voor snelle aanmaak van takenlijsten</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.templates.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-600 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Nieuw template
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Laden --}}
        <div id="loading-templates" class="text-center py-16" style="{{ isset($initialTemplates) ? 'display: none;' : '' }}">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-2 border-blue-600 border-t-transparent"></div>
            <p class="mt-3 text-sm text-slate-600">Templates laden...</p>
        </div>

        {{-- Fout --}}
        <div id="error-templates" class="bg-white rounded-xl shadow-sm border border-slate-100 p-8 sm:p-10 text-center" style="display: none;">
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="font-semibold text-slate-900">Kon templates niet laden</h3>
            <p class="mt-1 text-sm text-slate-500">Vernieuw de pagina of probeer het opnieuw.</p>
            <button type="button" onclick="loadTemplates()" class="mt-4 inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                Opnieuw proberen
            </button>
        </div>

        {{-- Inhoud --}}
        <div id="templates-content" data-onboarding-target="templates-grid"></div>
    </div>
</div>

{{-- Modal: Lijst aanmaken --}}
<div id="createListModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden overflow-y-auto h-full w-full z-50 flex items-start justify-center py-8 px-4">
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl border border-slate-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-slate-900">Lijst aanmaken van template</h3>
            <button type="button" onclick="closeCreateListModal()" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form id="createListForm" method="POST">
            @csrf
            <div class="mb-4">
                <label for="list_title" class="block text-sm font-medium text-slate-700 mb-1.5">Lijsttitel <span class="text-red-500">*</span></label>
                <input type="text" id="list_title" name="title" required placeholder="bv. Dagelijkse schoonmaak"
                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <div class="mb-6">
                <label for="list_description" class="block text-sm font-medium text-slate-700 mb-1.5">Beschrijving (optioneel)</label>
                <textarea id="list_description" name="description" rows="3" placeholder="Korte omschrijving van de lijst"
                    class="w-full px-3 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeCreateListModal()" class="px-4 py-2.5 text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl text-sm font-medium transition-colors">
                    Annuleren
                </button>
                <button type="submit" id="createListBtn" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Lijst aanmaken
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let isLoading = false;
let currentTemplates = [];
let hasRenderedTemplates = false;
const initialTemplates = @json($initialTemplates ?? null);

document.addEventListener('DOMContentLoaded', () => {
    if (initialTemplates) {
        applyTemplatesResponse(initialTemplates);
    } else {
        loadTemplates();
    }
});

function applyTemplatesResponse(data) {
    const loadingDiv = document.getElementById('loading-templates');
    const errorDiv = document.getElementById('error-templates');
    const contentDiv = document.getElementById('templates-content');

    loadingDiv.style.display = 'none';
    errorDiv.style.display = 'none';
    contentDiv.style.display = 'block';
    renderTemplates(data.data || []);
    hasRenderedTemplates = true;
}

async function loadTemplates() {
    if (isLoading) return;
    isLoading = true;
    const loadingDiv = document.getElementById('loading-templates');
    const errorDiv = document.getElementById('error-templates');
    const contentDiv = document.getElementById('templates-content');
    if (!hasRenderedTemplates) {
        loadingDiv.style.display = 'block';
        contentDiv.style.display = 'none';
        contentDiv.innerHTML = '';
    }
    errorDiv.style.display = 'none';
    try {
        const res = await fetch(`/admin/templates?_=${Date.now()}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const data = await res.json();
        applyTemplatesResponse(data);
    } catch (err) {
        if (hasRenderedTemplates) {
            alert('Er is een fout opgetreden bij het vernieuwen van templates.');
        } else {
            loadingDiv.style.display = 'none';
            errorDiv.style.display = 'block';
        }
    } finally {
        isLoading = false;
    }
}

function renderTemplates(templates) {
    currentTemplates = Array.isArray(templates) ? templates : [];
    const contentDiv = document.getElementById('templates-content');
    if (!templates || templates.length === 0) {
        contentDiv.innerHTML = `
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 p-8 sm:p-12 text-center">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <h3 class="text-lg font-semibold text-slate-900">Geen templates</h3>
                <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto">Maak je eerste template om snel takenlijsten aan te maken.</p>
                <a href="{{ route('admin.templates.create') }}" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Nieuw template
                </a>
            </div>
        `;
        return;
    }
    contentDiv.innerHTML = `
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            ${templates.map((t) => {
                const taskCount = t.template_tasks ? t.template_tasks.length : 0;
                const listCount = t.task_lists ? t.task_lists.length : 0;
                const desc = t.description ? (t.description.length > 80 ? t.description.substring(0, 80) + '…' : t.description) : '';
                return `
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 hover:shadow-md hover:border-slate-200 transition-all overflow-hidden">
                    <a href="/admin/templates/${t.id}" class="block p-5 sm:p-6">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-slate-900 truncate">${escapeHtml(t.name)}</h3>
                                ${desc ? `<p class="mt-1 text-sm text-slate-600 line-clamp-2">${escapeHtml(desc)}</p>` : ''}
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium shrink-0 ${t.is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600'}">
                                ${t.is_active ? 'Actief' : 'Inactief'}
                            </span>
                        </div>
                        <div class="mt-4 flex items-center gap-4 text-sm text-slate-500">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                                ${taskCount} taak${taskCount !== 1 ? 'en' : ''}
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                                ${listCount} lijst${listCount !== 1 ? 'en' : ''}
                            </span>
                        </div>
                    </a>
                    <div class="px-4 sm:px-6 py-4 border-t border-slate-100 bg-gradient-to-r from-slate-50 to-slate-50/60">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <button type="button" data-onboarding-target="template-create-btn" onclick="event.preventDefault();event.stopPropagation();createListFromTemplate(${t.id})" class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 bg-emerald-600 text-white hover:bg-emerald-700 rounded-xl text-sm font-semibold transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                Lijst maken
                            </button>
                            <a href="/admin/templates/${t.id}/edit" class="inline-flex items-center justify-center gap-2 px-3.5 py-2.5 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-sm font-semibold transition-colors shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                Bewerken
                            </a>
                        </div>
                        <div class="mt-2.5 flex items-center justify-between gap-2">
                            <a href="/admin/templates/${t.id}" class="inline-flex items-center gap-1.5 px-3 py-2 text-slate-600 hover:text-slate-900 hover:bg-white rounded-lg text-sm font-medium transition-colors border border-transparent hover:border-slate-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Bekijken
                            </a>
                            <button type="button" onclick="event.preventDefault();event.stopPropagation();deleteTemplate(${t.id})" class="inline-flex items-center gap-1.5 px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg text-sm font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Verwijderen
                        </button>
                        </div>
                    </div>
                </div>
            `}).join('')}
        </div>
    `;
    document.dispatchEvent(new CustomEvent('onboarding:targets-updated'));
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

async function deleteTemplate(templateId) {
    if (!confirm('Weet je zeker dat je dit template wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.')) return;
    try {
        const res = await fetch(`/admin/templates/${templateId}`, {
            method: 'DELETE',
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' }
        });
        if (!res.ok) {
            let body = null;
            try { body = await res.json(); } catch(e) {}
            if (res.status === 422 && body?.message?.includes('being used')) {
                if (confirm('Dit template wordt gebruikt door bestaande lijsten. Wil je het ontkoppelen en het template verwijderen?')) {
                    const r2 = await fetch(`/admin/templates/${templateId}?force=unlink`, {
                        method: 'DELETE',
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' }
                    });
                    if (!r2.ok) throw new Error((await r2.json().catch(() => ({}))).message || 'Verwijderen mislukt');
                    await loadTemplates();
                }
                return;
            }
            throw new Error(body?.message || `HTTP ${res.status}`);
        }
        await loadTemplates();
    } catch (err) {
        if (err.message?.includes('419')) {
            alert('Sessie verlopen. Vernieuw de pagina en probeer opnieuw.');
        } else {
            alert(err.message || 'Verwijderen mislukt. Probeer opnieuw.');
        }
    }
}

function createListFromTemplate(templateId) {
    const template = currentTemplates.find(t => Number(t.id) === Number(templateId));
    const params = new URLSearchParams();
    params.set('template_id', templateId);
    if (template?.name) params.set('title', template.name);
    if (template?.description) params.set('description', template.description);
    window.location.href = `/admin/lists/create?${params.toString()}`;
}

function closeCreateListModal() {
    document.getElementById('createListModal').classList.add('hidden');
    document.getElementById('createListForm').reset();
}

document.getElementById('createListForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const form = this;
    const btn = document.getElementById('createListBtn');
    const orig = btn.innerHTML;
    btn.innerHTML = '<span class="inline-block animate-spin rounded-full h-4 w-4 border-2 border-white border-t-transparent mr-2"></span> Bezig…';
    btn.disabled = true;
    try {
        const res = await fetch(form.action, {
            method: 'POST',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' },
            body: new FormData(form)
        });
        const data = await res.json().catch(() => ({}));
        if (res.ok && data.success) {
            closeCreateListModal();
            if (data.redirect) window.location.href = data.redirect;
        } else {
            alert(data.message || 'Lijst aanmaken mislukt. Probeer opnieuw.');
        }
    } catch (err) {
        alert('Er is iets misgegaan. Probeer opnieuw.');
    } finally {
        btn.innerHTML = orig;
        btn.disabled = false;
    }
});

document.addEventListener('click', e => { if (e.target.id === 'createListModal') closeCreateListModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeCreateListModal(); });
</script>
@endsection

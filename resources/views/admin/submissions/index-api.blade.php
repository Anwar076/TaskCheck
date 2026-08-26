@extends('layouts.admin')

@section('page-title', 'Inzendingen')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <span class="text-slate-900 font-semibold truncate">Inzendingen</span>
@endsection

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Inzendingen</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Bekijk en beheer taakinzendingen van medewerkers</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 pb-6 pt-4 sm:px-8 sm:pb-8 sm:pt-5">
        {{-- Tabs --}}
        <div id="submissions-tabs" class="mb-6 sm:mb-8" style="display: none;">
            <div class="border-b border-slate-200">
                <nav class="flex gap-1" aria-label="Tabs">
                    <button type="button" class="tab-btn -mb-px px-4 py-3 text-sm font-medium border-b-2 border-blue-600 text-blue-600 transition-colors focus:outline-none focus-visible:rounded-md focus-visible:ring-2 focus-visible:ring-blue-500/20" data-tab-value="">
                        Alle inzendingen
                    </button>
                    <button type="button" class="tab-btn -mb-px px-4 py-3 text-sm font-medium border-b-2 border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300 transition-colors focus:outline-none focus-visible:rounded-md focus-visible:ring-2 focus-visible:ring-blue-500/20" data-tab-value="to_review">
                        Te beoordelen
                        <span id="tab-to-review-badge" class="ml-1.5 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 hidden">0</span>
                    </button>
                    <button type="button" class="tab-btn -mb-px px-4 py-3 text-sm font-medium border-b-2 border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300 transition-colors focus:outline-none focus-visible:rounded-md focus-visible:ring-2 focus-visible:ring-blue-500/20" data-tab-value="done">
                        Afgerond
                    </button>
                </nav>
            </div>
        </div>

        {{-- Laden --}}
        <div id="submissions-loading" class="text-center py-16" style="{{ isset($initialSubmissions) ? 'display: none;' : '' }}">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-2 border-blue-600 border-t-transparent"></div>
            <p class="mt-3 text-sm text-slate-600">Inzendingen laden...</p>
        </div>

        {{-- Stats --}}
        <div id="submissions-stats" class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8" style="display: none;">
            <div class="bg-slate-50 rounded-xl border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900" id="total-submissions">0</p>
                        <p class="text-sm text-slate-600">Totaal</p>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 rounded-xl border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900" id="in-progress-submissions">0</p>
                        <p class="text-sm text-slate-600">Bezig</p>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 rounded-xl border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900" id="completed-submissions">0</p>
                        <p class="text-sm text-slate-600">Afgerond</p>
                    </div>
                </div>
            </div>
            <div class="bg-slate-50 rounded-xl border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-violet-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900" id="reviewed-submissions">0</p>
                        <p class="text-sm text-slate-600">Beoordeeld</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Zoeken en filter --}}
        <div id="submissions-filters" class="rounded-xl sm:rounded-2xl border border-slate-100 bg-slate-50 overflow-hidden mb-6 sm:mb-8" style="display: none;">
            <div class="px-4 sm:px-6 py-4 sm:py-5">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex-1 w-full lg:max-w-md">
                        <label for="search-input" class="sr-only">Zoeken</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                            </div>
                            <input type="search" id="search-input" placeholder="Zoek op medewerker, teamlid of lijst..." autocomplete="off"
                                class="block w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <div id="status-filter-wrap" class="flex items-center gap-2">
                            <label for="status-filter" class="text-sm text-slate-600 whitespace-nowrap">Status:</label>
                            <select id="status-filter" class="px-3 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white min-w-[140px]">
                                <option value="">Alle statussen</option>
                                <option value="in_progress">Bezig</option>
                                <option value="completed">Afgerond</option>
                                <option value="reviewed">Beoordeeld</option>
                                <option value="rejected">Afgewezen</option>
                            </select>
                        </div>
                        <button type="button" id="refresh-btn" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" title="Vernieuwen">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div id="submissions-table-wrap" class="rounded-xl sm:rounded-2xl border border-slate-100 overflow-hidden" style="display: none;">
            <div id="submissions-table" class="w-full" style="display: none;">
                <table class="w-full table-fixed divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="w-[24%] px-4 py-2.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Medewerker(s)</th>
                            <th class="w-[20%] px-4 py-2.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Takenlijst</th>
                            <th class="w-[11%] px-4 py-2.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                            <th class="w-[11%] px-4 py-2.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Voortgang</th>
                            <th class="w-[12%] px-4 py-2.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Ingediend</th>
                            <th class="w-[22%] px-4 py-2.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Actie</th>
                        </tr>
                    </thead>
                    <tbody id="submissions-tbody" class="divide-y divide-slate-200">
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Lege staat --}}
        <div id="empty-state" class="rounded-xl sm:rounded-2xl border border-slate-100 bg-slate-50 p-8 sm:p-12 text-center" style="display: none;">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-900" id="empty-state-title">Geen inzendingen</h3>
            <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto" id="empty-state-desc">Er zijn geen inzendingen die voldoen aan je filters.</p>
        </div>

        {{-- Fout --}}
        <div id="error-state" class="rounded-xl border border-slate-100 bg-slate-50 p-8 sm:p-10 text-center" style="display: none;">
            <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="font-semibold text-slate-900">Kon inzendingen niet laden</h3>
            <p class="mt-1 text-sm text-slate-500">Vernieuw de pagina of probeer het opnieuw.</p>
            <button type="button" onclick="loadSubmissions()" class="mt-4 inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                Opnieuw proberen
            </button>
        </div>

        {{-- Paginatie --}}
        <div id="pagination-container" class="mt-6 sm:mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" style="display: none;"></div>
            </div>
        </div>
    </div>
</div>

<script>
const apiBase = "{{ url('/api') }}";
let currentPage = 1;
let hasRenderedSubmissions = false;
let isLoadingSubmissions = false;
const initialSubmissions = @json($initialSubmissions ?? null);

function escapeHtml(t) {
    if (!t) return '';
    const d = document.createElement('div');
    d.textContent = t;
    return d.innerHTML;
}

let currentTab = '';

document.addEventListener('DOMContentLoaded', async () => {
    if (initialSubmissions) {
        applySubmissionsResponse(initialSubmissions);
    } else {
        await loadSubmissions();
    }

    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const refreshBtn = document.getElementById('refresh-btn');
    const tabBtns = document.querySelectorAll('.tab-btn');
    let searchTimeout;

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            currentTab = btn.dataset.tabValue || '';
            currentPage = 1;
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-blue-600', 'text-blue-600');
                b.classList.add('border-transparent', 'text-slate-600');
            });
            btn.classList.remove('border-transparent', 'text-slate-600');
            btn.classList.add('border-blue-600', 'text-blue-600');
            loadSubmissions();
        });
    });

    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => { currentPage = 1; loadSubmissions(); }, 350);
    });
    searchInput.addEventListener('keydown', e => { if (e.key === 'Enter') { currentPage = 1; loadSubmissions(); } });
    statusFilter.addEventListener('change', () => { currentPage = 1; loadSubmissions(); });
    refreshBtn.addEventListener('click', () => loadSubmissions());
});

function applySubmissionsResponse(data) {
    const loadingDiv = document.getElementById('submissions-loading');
    const statsDiv = document.getElementById('submissions-stats');
    const filtersDiv = document.getElementById('submissions-filters');
    const tableWrap = document.getElementById('submissions-table-wrap');
    const tableDiv = document.getElementById('submissions-table');
    const emptyDiv = document.getElementById('empty-state');
    const errorDiv = document.getElementById('error-state');
    const paginationDiv = document.getElementById('pagination-container');
    const tabsDiv = document.getElementById('submissions-tabs');

    const total = data.total ?? 0;
    const items = data.data ?? [];
    const meta = data.meta || {};

    document.getElementById('total-submissions').textContent = total;
    document.getElementById('in-progress-submissions').textContent = meta.in_progress_count ?? items.filter(s => s.status === 'in_progress').length;
    document.getElementById('completed-submissions').textContent = meta.completed_count ?? items.filter(s => s.status === 'completed').length;
    document.getElementById('reviewed-submissions').textContent = meta.reviewed_count ?? items.filter(s => s.status === 'reviewed').length;

    const badge = document.getElementById('tab-to-review-badge');
    if (meta.to_review_count !== undefined) {
        badge.textContent = meta.to_review_count;
        badge.classList.toggle('hidden', meta.to_review_count === 0);
    }

    tabsDiv.style.display = 'block';
    statsDiv.style.display = 'grid';
    filtersDiv.style.display = 'block';
    loadingDiv.style.display = 'none';
    errorDiv.style.display = 'none';
    tableWrap.style.display = 'none';
    tableDiv.style.display = 'none';
    emptyDiv.style.display = 'none';
    paginationDiv.style.display = 'none';

    document.querySelectorAll('.tab-btn').forEach((b, i) => {
        const isActive = (i === 0 && !currentTab) || (i === 1 && currentTab === 'to_review') || (i === 2 && currentTab === 'done');
        b.classList.toggle('border-blue-600', isActive);
        b.classList.toggle('text-blue-600', isActive);
        b.classList.toggle('border-transparent', !isActive);
        b.classList.toggle('text-slate-600', !isActive);
    });

    const statusWrap = document.getElementById('status-filter-wrap');
    const statusFilterEl = document.getElementById('status-filter');
    if (statusWrap) statusWrap.style.opacity = currentTab ? '0.6' : '1';
    if (statusFilterEl) statusFilterEl.disabled = !!currentTab;

    if (items.length > 0) {
        renderSubmissions(items);
        tableWrap.style.display = 'block';
        tableDiv.style.display = 'block';
        if (data.last_page > 1) {
            renderPagination(data);
            paginationDiv.style.display = 'flex';
        }
    } else {
        const emptyTitle = document.getElementById('empty-state-title');
        const emptyDesc = document.getElementById('empty-state-desc');
        if (currentTab === 'to_review') {
            if (emptyTitle) emptyTitle.textContent = 'Geen inzendingen om te beoordelen';
            if (emptyDesc) emptyDesc.textContent = 'Alle inzendingen zijn al beoordeeld of er zijn nog geen voltooide inzendingen.';
        } else if (currentTab === 'done') {
            if (emptyTitle) emptyTitle.textContent = 'Geen afgeronde inzendingen';
            if (emptyDesc) emptyDesc.textContent = 'Er zijn nog geen beoordeelde of afgewezen inzendingen.';
        } else {
            if (emptyTitle) emptyTitle.textContent = 'Geen inzendingen';
            if (emptyDesc) emptyDesc.textContent = 'Er zijn geen inzendingen die voldoen aan je filters.';
        }
        emptyDiv.style.display = 'block';
    }

    hasRenderedSubmissions = true;
}

async function loadSubmissions(page = 1) {
    if (isLoadingSubmissions) return;
    isLoadingSubmissions = true;
    if (page) currentPage = page;
    const loadingDiv = document.getElementById('submissions-loading');
    const statsDiv = document.getElementById('submissions-stats');
    const filtersDiv = document.getElementById('submissions-filters');
    const tableWrap = document.getElementById('submissions-table-wrap');
    const tableDiv = document.getElementById('submissions-table');
    const emptyDiv = document.getElementById('empty-state');
    const errorDiv = document.getElementById('error-state');
    const paginationDiv = document.getElementById('pagination-container');
    const tabsDiv = document.getElementById('submissions-tabs');

    if (!hasRenderedSubmissions) {
        loadingDiv.style.display = 'block';
        statsDiv.style.display = 'none';
        filtersDiv.style.display = 'none';
        tableWrap.style.display = 'none';
        tableDiv.style.display = 'none';
        emptyDiv.style.display = 'none';
        paginationDiv.style.display = 'none';
    }
    errorDiv.style.display = 'none';

    try {
        const params = new URLSearchParams();
        const search = document.getElementById('search-input').value.trim();
        const status = document.getElementById('status-filter').value;
        if (search) params.append('search', search);
        if (currentTab) params.append('tab', currentTab);
        else if (status) params.append('status', status);
        params.append('page', currentPage);

        await fetch('/sanctum/csrf-cookie', { method: 'GET', credentials: 'same-origin' }).catch(() => {});
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const res = await fetch(`${apiBase}/submissions?${params}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfMeta ? csrfMeta.getAttribute('content') : '' },
            credentials: 'same-origin'
        });
        const result = await res.json();

        if (!res.ok || (result.success === false)) {
            throw new Error(result.message || `HTTP ${res.status}`);
        }

        applySubmissionsResponse(result);
    } catch (err) {
        if (hasRenderedSubmissions) {
            alert('Er is een fout opgetreden bij het vernieuwen van inzendingen.');
        } else {
            loadingDiv.style.display = 'none';
            errorDiv.style.display = 'block';
        }
    } finally {
        isLoadingSubmissions = false;
    }
}

function escapeAttr(t) {
    if (!t) return '';
    return String(t).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
}

function renderTeamContributorNames(contributors) {
    const maxVisible = 2;
    const visible = contributors.slice(0, maxVisible);
    const hidden = contributors.slice(maxVisible);

    if (!visible.length) {
        return '';
    }

    const namesHtml = visible.map(c => escapeHtml(c.name)).join(', ');
    if (!hidden.length) {
        return namesHtml;
    }

    const hiddenTitle = hidden.map(c => c.name).join(', ');
    return `${namesHtml} <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-200 text-slate-600 cursor-help" title="${escapeAttr(hiddenTitle)}">+${hidden.length}</span>`;
}

function statusConfig(status) {
    const configs = {
        in_progress: { bg: 'bg-amber-50', text: 'text-amber-800', ring: 'ring-amber-200', dot: 'bg-amber-500', border: 'border-l-amber-400', label: 'Bezig', progress: 'bg-amber-500' },
        completed: { bg: 'bg-emerald-50', text: 'text-emerald-800', ring: 'ring-emerald-200', dot: 'bg-emerald-500', border: 'border-l-emerald-500', label: 'Afgerond', progress: 'bg-emerald-500' },
        reviewed: { bg: 'bg-violet-50', text: 'text-violet-800', ring: 'ring-violet-200', dot: 'bg-violet-500', border: 'border-l-violet-500', label: 'Beoordeeld', progress: 'bg-violet-500' },
        rejected: { bg: 'bg-red-50', text: 'text-red-800', ring: 'ring-red-200', dot: 'bg-red-500', border: 'border-l-red-500', label: 'Afgewezen', progress: 'bg-red-500' },
    };
    return configs[status] || { bg: 'bg-slate-50', text: 'text-slate-800', ring: 'ring-slate-200', dot: 'bg-slate-400', border: 'border-l-slate-300', label: status, progress: 'bg-blue-600' };
}

function formatSubmittedAt(iso) {
    if (!iso) return { text: '—', title: '' };
    const d = new Date(iso);
    const now = new Date();
    const startOfToday = new Date(now.getFullYear(), now.getMonth(), now.getDate());
    const startOfDate = new Date(d.getFullYear(), d.getMonth(), d.getDate());
    const diffDays = Math.round((startOfToday - startOfDate) / 86400000);
    const time = d.toLocaleTimeString('nl-NL', { hour: '2-digit', minute: '2-digit' });
    const title = d.toLocaleDateString('nl-NL', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    let relative;
    if (diffDays === 0) relative = 'Vandaag';
    else if (diffDays === 1) relative = 'Gisteren';
    else if (diffDays < 7) relative = `${diffDays}d`;
    else relative = d.toLocaleDateString('nl-NL', { day: 'numeric', month: 'short' });
    return { text: `${relative} ${time}`, title };
}

function renderSubmissions(items) {
    const tbody = document.getElementById('submissions-tbody');
    tbody.innerHTML = items.map(s => {
        const isTeam = Boolean(s.is_team_submission);
        const contributors = Array.isArray(s.contributors) ? s.contributors : [];
        const userName = s.user ? escapeHtml(s.user.name) : 'Onbekend';
        const userInitial = s.user && s.user.name ? s.user.name.charAt(0).toUpperCase() : 'U';
        const listTitle = s.task_list ? escapeHtml(s.task_list.title) : 'Onbekende lijst';
        const listDepartments = Array.isArray(s.list_departments) ? s.list_departments : [];
        const departmentLabel = listDepartments.length ? escapeHtml(listDepartments.join(', ')) : '';
        const hasDepartment = listDepartments.length > 0;
        const departmentInitial = listDepartments[0] ? escapeHtml(listDepartments[0].charAt(0).toUpperCase()) : 'A';
        const st = statusConfig(s.status);
        const progress = s.progress_percentage ?? (s.submission_tasks && s.submission_tasks.length ? Math.round((s.submission_tasks.filter(t => t.status === 'completed').length / s.submission_tasks.length) * 100) : 0);
        const progressColor = progress >= 100 ? st.progress : (s.status === 'in_progress' ? 'bg-amber-500' : 'bg-blue-600');
        const submittedAt = s.completed_at || s.submitted_at || s.created_at;
        const submittedFmt = formatSubmittedAt(submittedAt);
        const viewUrl = "{{ url('admin/submissions') }}/" + s.id;
        const reviewUrl = "{{ url('admin/submissions') }}/" + s.id;
        const needsReview = s.status === 'completed';
        const actionUrl = needsReview ? reviewUrl : viewUrl;
        const actionLabel = needsReview ? 'Beoordelen' : 'Bekijken';
        const actionClass = needsReview
            ? 'bg-emerald-600 text-white hover:bg-emerald-700'
            : 'bg-blue-50 text-blue-700 hover:bg-blue-100';
        const deviationBadge = s.has_metric_deviation
            ? `<span class="inline-block w-2 h-2 rounded-full bg-red-500 flex-shrink-0" title="Kritieke afwijking"></span>`
            : '';

        const contributorAvatars = contributors.slice(0, 2).map(c => `
            <div class="w-7 h-7 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-[10px] font-bold text-indigo-700" title="${escapeAttr(c.name)}">
                ${escapeHtml((c.initials || c.name?.charAt(0) || '?').toUpperCase())}
            </div>
        `).join('');

        const hiddenContributorNames = contributors.slice(2).map(c => c.name).join(', ');
        const extraContributors = contributors.length > 2
            ? `<div class="w-7 h-7 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center text-[10px] font-bold text-slate-600 cursor-help" title="${escapeAttr(hiddenContributorNames)}">+${contributors.length - 2}</div>`
            : '';

        const teamNamesHtml = contributors.length > 0
            ? renderTeamContributorNames(contributors)
            : escapeHtml(userName);

        const employeeCell = hasDepartment
            ? `<div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-semibold text-emerald-700">${departmentInitial}</span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800 flex-shrink-0">Afdeling</span>
                            <span class="text-sm font-medium text-slate-900 truncate" title="${departmentLabel}">${departmentLabel}</span>
                        </div>
                    </div>
               </div>`
            : isTeam
            ? `<div class="flex items-center gap-2.5 min-w-0">
                    <div class="flex -space-x-2 flex-shrink-0">
                        ${contributorAvatars || `<div class="w-7 h-7 rounded-full bg-indigo-100 flex items-center justify-center"><span class="text-xs font-semibold text-indigo-700">${userInitial}</span></div>`}
                        ${extraContributors}
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5 min-w-0">
                            <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-semibold bg-indigo-100 text-indigo-800 flex-shrink-0">Team</span>
                            <span class="text-sm font-medium text-slate-900 truncate">${teamNamesHtml}</span>
                        </div>
                    </div>
               </div>`
            : `<div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-semibold text-slate-700">${userInitial}</span>
                    </div>
                    <span class="text-sm font-medium text-slate-900 truncate">${userName}</span>
               </div>`;

        return `
        <tr class="group hover:bg-slate-50/80 transition-colors border-l-[3px] ${st.border}">
            <td class="px-4 py-3 align-middle">
                ${employeeCell}
            </td>
            <td class="px-4 py-3 align-middle">
                <div class="flex items-center gap-2 min-w-0">
                    ${deviationBadge}
                    <span class="text-sm font-medium text-slate-900 truncate" title="${listTitle}">${listTitle}</span>
                </div>
            </td>
            <td class="px-4 py-3 align-middle">
                <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md text-xs font-semibold ${st.bg} ${st.text}">
                    <span class="w-1.5 h-1.5 rounded-full ${st.dot}"></span>
                    ${st.label}
                </span>
            </td>
            <td class="px-4 py-3 align-middle">
                <div class="flex items-center gap-2">
                    <div class="flex-1 h-1.5 bg-slate-200 rounded-full overflow-hidden min-w-0">
                        <div class="h-full ${progressColor} rounded-full" style="width:${Math.min(progress, 100)}%"></div>
                    </div>
                    <span class="text-xs font-semibold tabular-nums text-slate-600 w-8 text-right flex-shrink-0">${progress}%</span>
                </div>
            </td>
            <td class="px-4 py-3 align-middle text-sm text-slate-600 truncate" title="${escapeAttr(submittedFmt.title)}">${submittedFmt.text}</td>
            <td class="px-4 py-3 align-middle text-right">
                <div class="inline-flex flex-wrap justify-end gap-1.5">
                    <a href="${actionUrl}" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap transition-colors ${actionClass}">${actionLabel}</a>
                    ${needsReview ? `<button type="button" onclick="approveAllSubmission(${s.id}, this)" class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-xs font-semibold whitespace-nowrap border border-emerald-200 bg-white text-emerald-700 hover:bg-emerald-50 transition-colors">Alles goedkeuren</button>` : ''}
                </div>
            </td>
        </tr>`;
    }).join('');
}

async function approveAllSubmission(id, button) {
    if (!confirm('Weet je zeker dat je alle taken van deze inzending wilt goedkeuren?')) {
        return;
    }

    const originalLabel = button.textContent;
    button.disabled = true;
    button.textContent = 'Bezig...';

    try {
        await fetch('/sanctum/csrf-cookie', { method: 'GET', credentials: 'same-origin' }).catch(() => {});
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const res = await fetch(`{{ url('admin/submissions') }}/${id}/approve-all`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfMeta ? csrfMeta.getAttribute('content') : '',
            },
            credentials: 'same-origin',
        });
        const data = await res.json().catch(() => ({}));

        if (!res.ok || data.success === false) {
            throw new Error(data.message || 'Goedkeuren is niet gelukt.');
        }

        await loadSubmissions(currentPage);
    } catch (err) {
        alert(err.message || 'Goedkeuren is niet gelukt.');
        button.disabled = false;
        button.textContent = originalLabel;
    }
}

function renderPagination(data) {
    const div = document.getElementById('pagination-container');
    const from = data.from ?? 0;
    const to = data.to ?? 0;
    const total = data.total ?? 0;
    const lastPage = data.last_page ?? 1;
    const current = data.current_page ?? 1;

    let navHtml = '';
    if (current > 1) {
        navHtml += `<button type="button" onclick="loadSubmissions(${current - 1})" class="inline-flex items-center gap-1 px-4 py-2 border border-slate-200 rounded-l-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">Vorige</button>`;
    }
    const start = Math.max(1, current - 2);
    const end = Math.min(lastPage, current + 2);
    for (let i = start; i <= end; i++) {
        const active = i === current;
        navHtml += `<button type="button" onclick="loadSubmissions(${i})" class="px-4 py-2 -ml-px text-sm font-medium border border-slate-200 ${active ? 'bg-blue-600 text-white border-blue-600 z-10' : 'text-slate-700 bg-white hover:bg-slate-50'} transition-colors">${i}</button>`;
    }
    if (current < lastPage) {
        navHtml += `<button type="button" onclick="loadSubmissions(${current + 1})" class="inline-flex items-center gap-1 px-4 py-2 -ml-px border border-slate-200 rounded-r-xl text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 transition-colors">Volgende</button>`;
    }

    div.innerHTML = `
        <div class="text-sm text-slate-600">${from} t/m ${to} van ${total} resultaten</div>
        <nav class="relative z-0 inline-flex rounded-xl shadow-sm overflow-hidden">${navHtml}</nav>
    `;
}
</script>
@endsection

@extends('layouts.admin')

@section('page-title', 'Takenlijsten')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <span class="text-slate-900 font-semibold truncate">Takenlijsten</span>
@endsection

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
@php
    $locationOptions = \App\Models\Organisation\Location::where('is_active', true)->orderBy('name')->get(['id', 'name']);
@endphp
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

        {{-- Hero sectie --}}
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">Takenlijsten</h1>
                                <p class="text-blue-100/90 text-sm sm:text-base mt-0.5">Organiseer en beheer je takenlijsten efficiënt</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.lists.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-600 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            Nieuwe lijst maken
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Laden --}}
        <div id="lists-loading" class="text-center py-16" style="{{ isset($initialLists) ? 'display: none;' : '' }}">
            <div class="inline-block animate-spin rounded-full h-10 w-10 border-2 border-blue-600 border-t-transparent"></div>
            <p class="mt-3 text-sm text-slate-600">Laden van takenlijsten...</p>
        </div>

        {{-- Zoeken en filteren (hidden until loaded) --}}
        <div id="lists-stats" style="display: none;">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6 sm:mb-8">
                <div class="px-4 sm:px-6 py-4 sm:py-5">
                    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                        <div class="w-full xl:max-w-md 2xl:max-w-lg">
                            <label for="search-input" class="sr-only">Zoeken</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                                    </svg>
                                </div>
                                <input type="search" id="search-input" placeholder="Zoek op titel of beschrijving..." autocomplete="off" class="block w-full h-11 pl-10 pr-4 border border-slate-200 rounded-xl text-sm placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 xl:flex-nowrap">
                            <div class="flex items-center gap-2 sm:gap-2.5">
                                <label for="status-filter" class="text-sm text-slate-600 whitespace-nowrap">Status:</label>
                                <select id="status-filter" class="h-11 px-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white min-w-[138px]">
                                    <option value="">Alle statussen</option>
                                    <option value="active">Actief</option>
                                    <option value="inactive">Inactief</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-2.5">
                                <label for="location-filter" class="text-sm text-slate-600 whitespace-nowrap">Locatie:</label>
                                <select id="location-filter" class="h-11 px-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white min-w-[170px]">
                                    <option value="">Alle locaties</option>
                                    @foreach($locationOptions as $locationOption)
                                        <option value="{{ $locationOption->id }}">{{ $locationOption->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2 sm:pl-1">
                                <span class="text-sm text-slate-600 whitespace-nowrap">
                                    <span id="total-lists" class="font-semibold text-slate-900">0</span> lijsten
                                </span>
                                <button type="button" id="refresh-btn" class="inline-flex items-center justify-center w-10 h-10 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors shrink-0" title="Vernieuwen">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lijsten grid --}}
        <div id="lists-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6" style="display: none;">
        </div>

        {{-- Lege staat --}}
        <div id="empty-state" class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 p-8 sm:p-12 text-center" style="display: none;">
            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-slate-900">Geen takenlijsten</h3>
            <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto">Begin met het aanmaken van een nieuwe takenlijst om je team te organiseren.</p>
            <a href="{{ route('admin.lists.create') }}" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Nieuwe lijst maken
            </a>
        </div>

        {{-- Paginatie --}}
        <div id="pagination-container" class="mt-6 sm:mt-8" style="display: none;"></div>
    </div>
</div>

<!-- Cache bust: {{ time() }} -->
<script>
// Version: {{ time() }} - Fixed delete functionality - v3.0

// Declare all functions first

// Prevent multiple simultaneous loads
let isLoading = false;
let hasRenderedLists = false;
const initialLists = @json($initialLists ?? null);

function applyListsResponse(lists) {
    const loadingDiv = document.getElementById('lists-loading');
    const statsDiv = document.getElementById('lists-stats');
    const containerDiv = document.getElementById('lists-container');
    const emptyDiv = document.getElementById('empty-state');
    const paginationDiv = document.getElementById('pagination-container');

    document.getElementById('total-lists').textContent = lists.total || 0;
    statsDiv.style.display = 'block';

    if (lists.data && lists.data.length > 0) {
        renderLists(lists.data);
        containerDiv.style.display = 'grid';
        emptyDiv.style.display = 'none';
        if (lists.last_page > 1) {
            renderPagination(lists);
            paginationDiv.style.display = 'block';
        } else {
            paginationDiv.style.display = 'none';
        }
    } else {
        containerDiv.style.display = 'none';
        emptyDiv.style.display = 'block';
        paginationDiv.style.display = 'none';
    }

    loadingDiv.style.display = 'none';
    hasRenderedLists = true;
}

async function loadLists() {
    if (isLoading) {
        return;
    }
    
    isLoading = true;
    
    const loadingDiv = document.getElementById('lists-loading');
    const statsDiv = document.getElementById('lists-stats');
    const containerDiv = document.getElementById('lists-container');
    const emptyDiv = document.getElementById('empty-state');
    const paginationDiv = document.getElementById('pagination-container');

    if (!loadingDiv || !statsDiv || !containerDiv || !emptyDiv || !paginationDiv) {
        isLoading = false;
        return;
    }

    try {
        if (!hasRenderedLists) {
            loadingDiv.style.display = 'block';
            statsDiv.style.display = 'none';
            containerDiv.style.display = 'none';
            emptyDiv.style.display = 'none';
            paginationDiv.style.display = 'none';
        }

        // Get search and filter parameters
        const search = document.getElementById('search-input').value;
        const status = document.getElementById('status-filter').value;
        const locationId = document.getElementById('location-filter').value;
        const pageParam = typeof loadLists.page === 'number' ? loadLists.page : 1;

        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status) params.append('is_active', status === 'active');
        if (locationId) params.append('location_id', locationId);
        if (pageParam > 1) params.append('page', pageParam);
        params.append('_t', Date.now());

        const url = '/admin/lists?' + params.toString();

        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const headers = {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
        }

        const response = await fetch(url, {
            method: 'GET',
            headers: headers,
            credentials: 'same-origin' // Include session cookies for authentication
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const responseText = await response.text();
        
        let lists;
        try {
            lists = JSON.parse(responseText);
        } catch (parseError) {
            throw new Error('Server returned invalid JSON');
        }

        applyListsResponse(lists);

    } catch (error) {
        if (hasRenderedLists) {
            alert('Er is een fout opgetreden bij het vernieuwen van de takenlijsten: ' + error.message);
            return;
        }
        
        // Show error state
        loadingDiv.innerHTML = `
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-8 max-w-md mx-auto text-center">
                <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto">
                    <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                </div>
                <p class="mt-4 font-semibold text-slate-900">Kon takenlijsten niet laden</p>
                <p class="mt-1 text-sm text-slate-500">${escapeHtml(error.message)}</p>
                <button onclick="loadLists()" class="mt-4 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                    Opnieuw proberen
                </button>
            </div>
        `;
        
        // Hide other sections
        statsDiv.style.display = 'none';
        containerDiv.style.display = 'none';
        emptyDiv.style.display = 'none';
        paginationDiv.style.display = 'none';
    } finally {
        isLoading = false;
    }
}

function renderLists(lists) {
    const container = document.getElementById('lists-container');
    
    const listsHtml = lists.map(list => `
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md hover:border-slate-200 transition-all group">
            <a href="/admin/lists/${list.id}" class="block p-5 sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 group-hover:text-blue-600 transition-colors truncate">
                            ${escapeHtml(list.title)}
                        </h3>
                        <p class="text-sm text-slate-600 mt-1 line-clamp-2">${escapeHtml(list.description || 'Geen beschrijving')}</p>
                    </div>
                    <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                        ${renderListStatusBadge(list)}
                        ${list.template ? `<span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-100 text-blue-800">Template</span>` : ''}
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap items-center gap-3 text-sm text-slate-500">
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6.75-5.625-6.75-11.25a6.75 6.75 0 1113.5 0C18.75 15.375 12 21 12 21z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        ${escapeHtml(list.location ? list.location.name : 'Algemeen')}
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                        </svg>
                        ${list.tasks_count || 0} taken
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        ${escapeHtml(list.creator ? list.creator.name : 'Onbekend')}
                    </span>
                </div>
                <p class="mt-3 text-xs text-slate-400">Aangemaakt ${formatDate(list.created_at)}</p>
            </a>
            <div class="px-5 sm:px-6 pb-5 sm:pb-6 pt-0 flex flex-wrap items-center gap-2">
                <a href="/admin/lists/${list.id}" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Bekijken
                </a>
                <a href="/admin/lists/${list.id}/edit" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                    </svg>
                    Bewerken
                </a>
                <button onclick="event.preventDefault();event.stopPropagation();deleteList(${list.id}, this)" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                    </svg>
                    Verwijderen
                </button>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = listsHtml;
}

function renderListStatusBadge(list) {
    if (isListUnscheduled(list)) {
        return `<span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-amber-100 text-amber-800">Ongepland</span>`;
    }

    return `<span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium ${list.is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700'}">
        ${list.is_active ? 'Actief' : 'Inactief'}
    </span>`;
}

function isListUnscheduled(list) {
    const config = list.schedule_config || {};

    if (list.schedule_type === 'weekly') {
        return !Array.isArray(config.show_on_days) || config.show_on_days.length === 0;
    }

    if (list.schedule_type === 'custom') {
        if (config.type === 'specific_days') {
            const days = Array.isArray(config.days) ? config.days : config.show_on_days;
            return !Array.isArray(days) || days.length === 0;
        }

        if (Array.isArray(config.show_on_days)) {
            return config.show_on_days.length === 0;
        }
    }

    return false;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function renderPagination(lists) {
    const paginationDiv = document.getElementById('pagination-container');
    
    let paginationHtml = '';
    const prevPage = lists.current_page - 1;
    const nextPage = lists.current_page + 1;
    
    if (lists.prev_page_url && prevPage >= 1) {
        paginationHtml += `<button type="button" onclick="loadLists.setPage(${prevPage});loadLists();" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-l-xl hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            Vorige
        </button>`;
    }
    
    for (let i = 1; i <= lists.last_page; i++) {
        const isCurrent = i === lists.current_page;
        paginationHtml += `<button type="button" onclick="loadLists.setPage(${i});loadLists();" class="inline-flex items-center justify-center min-w-[2.5rem] px-3 py-2 text-sm font-medium ${isCurrent ? 'bg-blue-600 text-white' : 'text-slate-600 bg-white border border-slate-200 hover:bg-slate-50'} rounded-lg transition-colors">
            ${i}
        </button>`;
    }
    
    if (lists.next_page_url && nextPage <= lists.last_page) {
        paginationHtml += `<button type="button" onclick="loadLists.setPage(${nextPage});loadLists();" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-slate-600 bg-white border border-slate-200 rounded-r-xl hover:bg-slate-50 transition-colors">
            Volgende
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
        </button>`;
    }
    
    paginationDiv.innerHTML = `
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <p class="text-sm text-slate-600">
                ${lists.from} tot ${lists.to} van ${lists.total} resultaten
            </p>
            <nav class="flex items-center gap-1 flex-wrap">
                ${paginationHtml}
            </nav>
        </div>
    `;
}

async function deleteList(listId, buttonElement = null) {
    if (!confirm('Weet je zeker dat je deze takenlijst wilt verwijderen? Deze actie kan niet ongedaan worden gemaakt.')) {
        return;
    }
    
    // Show loading state if button is provided
    let originalText = 'Verwijderen';
    if (buttonElement) {
        originalText = buttonElement.textContent;
        buttonElement.textContent = 'Verwijderen...';
        buttonElement.disabled = true;
    }
    
    try {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const headers = {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
        }

        const response = await fetch(`/admin/lists/${listId}`, {
            method: 'DELETE',
            headers: headers,
            credentials: 'same-origin'
        });

        if (!response.ok) {
            const errorText = await response.text();
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        await response.json().catch(() => ({}));

        // Refresh the list (reset to page 1)
        loadLists.setPage(1);
        await loadLists();
        
    } catch (error) {
        alert('Er is een fout opgetreden bij het verwijderen van de lijst: ' + error.message);
        
        // Restore button state on error
        if (buttonElement) {
            buttonElement.textContent = originalText;
            buttonElement.disabled = false;
        }
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('nl-NL', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}

// Pagination state
loadLists.page = 1;
loadLists.setPage = function(p) { loadLists.page = p; };

// Make functions globally available
window.loadLists = loadLists;
window.deleteList = deleteList;

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeListsPage();
});

function initializeListsPage() {
    if (initialLists) {
        applyListsResponse(initialLists);
    } else {
        loadLists();
    }
    
    // Search functionality
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const locationFilter = document.getElementById('location-filter');
    const refreshBtn = document.getElementById('refresh-btn');
    
        if (searchInput && statusFilter && locationFilter && refreshBtn) {
            let searchTimeout;
            function triggerSearch() {
                loadLists.setPage(1);
                loadLists();
            }
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(triggerSearch, 350);
            });
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    clearTimeout(searchTimeout);
                    triggerSearch();
                }
            });
        
        statusFilter.addEventListener('change', function() {
            loadLists.setPage(1);
            loadLists();
        });
        locationFilter.addEventListener('change', function() {
            loadLists.setPage(1);
            loadLists();
        });
        refreshBtn.addEventListener('click', function() {
            loadLists.setPage(1);
            loadLists();
        });
    }
}
</script>
@endsection

@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Clean Page Header -->
        <div class="mb-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">
                        Takenlijsten
                    </h1>
                    <p class="mt-2 text-gray-600">
                        Organiseer en beheer uw takenlijsten efficiënt
                    </p>
                </div>
                <div class="mt-6 flex md:ml-4 md:mt-0">
                    <a href="{{ route('admin.lists.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nieuwe lijst maken
                    </a>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="lists-loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-sm text-gray-600">Laden van takenlijsten...</p>
        </div>

        <!-- Statistics Cards -->
        <div id="lists-stats" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6 lg:mb-8" style="display: none;">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Totaal aantal lijsten</p>
                        <p id="total-lists" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6 lg:mb-8">
            <div class="bg-gray-50 px-4 lg:px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <div class="flex-1 max-w-lg">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="search-input" placeholder="Zoek lijsten..." 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <select id="status-filter" class="block w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Alle Status</option>
                            <option value="active">Actief</option>
                            <option value="inactive">Inactief</option>
                        </select>
                        <button id="refresh-btn" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Vernieuwen
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lists Grid -->
        <div id="lists-container" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" style="display: none;">
            <!-- Lists will be loaded here via API -->
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="text-center py-12" style="display: none;">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Geen takenlijsten</h3>
            <p class="mt-1 text-sm text-gray-500">Begin door een nieuwe takenlijst te maken.</p>
            <div class="mt-6">
                <a href="{{ route('admin.lists.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                   Nieuwe lijst maken
                </a>
            </div>
        </div>

        <!-- Pagination -->
        <div id="pagination-container" class="mt-8 flex items-center justify-between" style="display: none;">
            <!-- Pagination will be loaded here -->
        </div>
    </div>
</div>

<script>
// Ensure the function is available globally
window.loadLists = loadLists;

// Initialize when DOM is ready AND when page becomes visible
document.addEventListener('DOMContentLoaded', function() {
    initializeListsPage();
});

// Also trigger when page becomes visible (for navigation back)
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        setTimeout(loadLists, 100);
    }
});

// Also trigger on page show (for browser back/forward)
window.addEventListener('pageshow', function(event) {
    setTimeout(loadLists, 100);
});

function initializeListsPage() {
    console.log('Initializing lists page...');
    
    // Load lists immediately
    loadLists();
    
    // Search functionality
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const refreshBtn = document.getElementById('refresh-btn');
    
    if (searchInput && statusFilter && refreshBtn) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadLists();
            }, 500);
        });
        
        statusFilter.addEventListener('change', loadLists);
        refreshBtn.addEventListener('click', loadLists);
        
        console.log('Event listeners attached successfully');
    } else {
        console.error('Could not find required elements');
    }
}

// Prevent multiple simultaneous loads
let isLoading = false;

async function loadLists() {
    if (isLoading) {
        console.log('Already loading, skipping...');
        return;
    }
    
    isLoading = true;
    
    const loadingDiv = document.getElementById('lists-loading');
    const statsDiv = document.getElementById('lists-stats');
    const containerDiv = document.getElementById('lists-container');
    const emptyDiv = document.getElementById('empty-state');
    const paginationDiv = document.getElementById('pagination-container');

    // Check if elements exist
    if (!loadingDiv || !statsDiv || !containerDiv || !emptyDiv || !paginationDiv) {
        console.error('Required DOM elements not found');
        isLoading = false;
        return;
    }

    try {
        console.log('Starting loadLists function...');
        
        loadingDiv.style.display = 'block';
        statsDiv.style.display = 'none';
        containerDiv.style.display = 'none';
        emptyDiv.style.display = 'none';
        paginationDiv.style.display = 'none';

        // Get search and filter parameters
        const search = document.getElementById('search-input').value;
        const status = document.getElementById('status-filter').value;

        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status) params.append('is_active', status === 'active');
        
        // Add cache-busting parameter to ensure fresh data
        params.append('_t', Date.now());

        const url = '/admin/lists?' + params.toString();
        console.log('Fetching URL:', url);

        // Get CSRF token safely
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

        console.log('Response received:', {
            status: response.status,
            statusText: response.statusText,
            ok: response.ok,
            headers: Object.fromEntries(response.headers.entries())
        });

        if (!response.ok) {
            const errorText = await response.text();
            console.error('Response status:', response.status);
            console.error('Response text:', errorText);
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const responseText = await response.text();
        console.log('Raw response text:', responseText.substring(0, 500) + '...');
        
        let lists;
        try {
            lists = JSON.parse(responseText);
            console.log('Parsed JSON successfully:', lists);
        } catch (parseError) {
            console.error('JSON Parse Error:', parseError);
            console.error('Response was not valid JSON:', responseText);
            throw new Error('Server returned invalid JSON');
        }

        // Update stats
        document.getElementById('total-lists').textContent = lists.total || 0;
        statsDiv.style.display = 'grid';

        if (lists.data && lists.data.length > 0) {
            renderLists(lists.data);
            containerDiv.style.display = 'grid';

            // Render pagination if needed
            if (lists.last_page > 1) {
                renderPagination(lists);
                paginationDiv.style.display = 'flex';
            }
        } else {
            emptyDiv.style.display = 'block';
        }

        loadingDiv.style.display = 'none';

    } catch (error) {
        console.error('Failed to load lists:', error);
        
        // Show error state
        loadingDiv.innerHTML = `
            <div class="text-red-600">
                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="mt-2 text-sm">Failed to load task lists</p>
                <p class="mt-1 text-xs text-gray-500">Error: ${error.message}</p>
                <button onclick="loadLists()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Retry
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
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="/admin/lists/${list.id}" class="hover:text-blue-600 transition-colors">
                                ${list.title}
                            </a>
                        </h3>
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">${list.description || 'No description'}</p>
                        
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                ${list.tasks_count || 0} tasks
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                ${list.creator ? list.creator.name : 'Unknown'}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${list.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                            ${list.is_active ? 'Active' : 'Inactive'}
                        </span>
                        ${list.template ? `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Template: ${list.template.name}
                        </span>` : ''}
                    </div>
                </div>
                
                <div class="mt-4 flex items-center justify-between">
                    <div class="text-xs text-gray-500">
                        Created ${formatDate(list.created_at)}
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="/admin/lists/${list.id}" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            View
                        </a>
                        <a href="/admin/lists/${list.id}/edit" class="inline-flex items-center px-3 py-1 border border-gray-300 rounded-md text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Edit
                        </a>
                        <button onclick="deleteList(${list.id})" class="inline-flex items-center px-3 py-1 border border-red-300 rounded-md text-xs font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = listsHtml;
}

function renderPagination(lists) {
    const paginationDiv = document.getElementById('pagination-container');
    
    let paginationHtml = '';
    
    if (lists.prev_page_url) {
        paginationHtml += `<a href="${lists.prev_page_url}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
            Previous
        </a>`;
    }
    
    // Page numbers
    for (let i = 1; i <= lists.last_page; i++) {
        const isCurrent = i === lists.current_page;
        paginationHtml += `<a href="?page=${i}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium ${isCurrent ? 'bg-blue-50 text-blue-600 border-blue-500' : 'text-gray-500 bg-white border border-gray-300'} border-t border-b hover:bg-gray-50">
            ${i}
        </a>`;
    }
    
    if (lists.next_page_url) {
        paginationHtml += `<a href="${lists.next_page_url}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
            Next
        </a>`;
    }
    
    paginationDiv.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing ${lists.from} to ${lists.to} of ${lists.total} results
            </div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                ${paginationHtml}
            </nav>
        </div>
    `;
}

async function deleteList(listId) {
    if (!confirm('Are you sure you want to delete this task list? This action cannot be undone.')) {
        return;
    }
    
    try {
        await ListAPI.deleteList(listId);
        await loadLists(); // Refresh the list
    } catch (error) {
        console.error('Failed to delete list:', error);
    }
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}
</script>
@endsection

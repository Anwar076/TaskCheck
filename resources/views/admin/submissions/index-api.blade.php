@extends('layouts.admin')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Clean Page Header -->
        <div class="mb-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">
                        All Submissions
                    </h1>
                    <p class="mt-2 text-gray-600">
                        Review and manage task submissions from employees
                    </p>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="submissions-loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-sm text-gray-600">Loading submissions...</p>
        </div>

        <!-- Statistics Cards -->
        <div id="submissions-stats" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6 lg:mb-8" style="display: none;">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Submissions</p>
                        <p id="total-submissions" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">In Progress</p>
                        <p id="in-progress-submissions" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p id="completed-submissions" class="text-2xl font-bold text-gray-900">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Reviewed</p>
                        <p id="reviewed-submissions" class="text-2xl font-bold text-gray-900">0</p>
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
                            <input type="text" id="search-input" placeholder="Search submissions..." 
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <select id="status-filter" class="block w-full sm:w-auto px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="reviewed">Reviewed</option>
                            <option value="rejected">Rejected</option>
                        </select>
                        <button id="refresh-btn" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submissions Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div id="submissions-table" style="display: none;">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task List</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="submissions-tbody" class="bg-white divide-y divide-gray-200">
                        <!-- Submissions will be loaded here via API -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="text-center py-12" style="display: none;">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No submissions found</h3>
            <p class="mt-1 text-sm text-gray-500">No submissions match your current filters.</p>
        </div>

        <!-- Pagination -->
        <div id="pagination-container" class="mt-8 flex items-center justify-between" style="display: none;">
            <!-- Pagination will be loaded here -->
        </div>
    </div>
</div>

<script>
// Submission API helper
// Use API routes with web session auth support
const apiBase = "{{ url('/api') }}";
const SubmissionAPI = {
    async loadSubmissions(params = '') {
        const url = `${apiBase}/submissions${params ? '?' + params : ''}`;
        
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin'
        });
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('API Error:', response.status, errorText);
            throw new Error(`HTTP error! status: ${response.status} - ${errorText}`);
        }
        
        return await response.json();
    },
    
    async updateSubmission(id, data) {
        const response = await fetch(`/api/submissions/${id}`, {
            method: 'PUT',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin',
            body: JSON.stringify(data)
        });
        
        return await response.json();
    }
};

document.addEventListener('DOMContentLoaded', async function() {
    await loadSubmissions();
    
    // Search and filter functionality
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');
    const refreshBtn = document.getElementById('refresh-btn');
    
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadSubmissions();
        }, 500);
    });
    
    statusFilter.addEventListener('change', loadSubmissions);
    refreshBtn.addEventListener('click', loadSubmissions);
});

async function loadSubmissions() {
    const loadingDiv = document.getElementById('submissions-loading');
    const statsDiv = document.getElementById('submissions-stats');
    const tableDiv = document.getElementById('submissions-table');
    const emptyDiv = document.getElementById('empty-state');
    const paginationDiv = document.getElementById('pagination-container');
    
    try {
        loadingDiv.style.display = 'block';
        statsDiv.style.display = 'none';
        tableDiv.style.display = 'none';
        emptyDiv.style.display = 'none';
        paginationDiv.style.display = 'none';
        
        // Get search and filter parameters
        const search = document.getElementById('search-input').value;
        const status = document.getElementById('status-filter').value;
        
        const params = new URLSearchParams();
        if (search) params.append('search', search);
        if (status) params.append('status', status);
        
        // Ensure Sanctum CSRF cookie is present for authenticated API requests
        await ensureCsrfCookie();
        
        const result = await SubmissionAPI.loadSubmissions(params.toString());
        
        if (!result.success) {
            throw new Error(result.message || 'Failed to load submissions');
        }
        
        const submissions = result;
        
        // Update stats - calculate these from the data
        const totalSubmissions = submissions.total || 0;
        const inProgress = submissions.data ? submissions.data.filter(s => s.status === 'in_progress').length : 0;
        const completed = submissions.data ? submissions.data.filter(s => s.status === 'completed').length : 0;
        const reviewed = submissions.data ? submissions.data.filter(s => s.status === 'reviewed').length : 0;
        
        document.getElementById('total-submissions').textContent = totalSubmissions;
        document.getElementById('in-progress-submissions').textContent = inProgress;
        document.getElementById('completed-submissions').textContent = completed;
        document.getElementById('reviewed-submissions').textContent = reviewed;
        
        statsDiv.style.display = 'grid';
        
        if (submissions.data && submissions.data.length > 0) {
            renderSubmissions(submissions.data);
            tableDiv.style.display = 'block';
            
            // Render pagination if needed
            if (submissions.last_page > 1) {
                renderPagination(submissions);
                paginationDiv.style.display = 'flex';
            }
        } else {
            emptyDiv.style.display = 'block';
        }
        
        loadingDiv.style.display = 'none';
        
    } catch (error) {
        console.error('Failed to load submissions:', error);
        loadingDiv.innerHTML = `
            <div class="text-red-600">
                <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="mt-2 text-sm">Failed to load submissions</p>
                <button onclick="loadSubmissions()" class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Retry
                </button>
            </div>
        `;
    }
}

// Ensure Sanctum CSRF cookie (required for auth:sanctum protected API routes)
async function ensureCsrfCookie() {
    try {
        // This endpoint sets the XSRF-TOKEN cookie used by Laravel Sanctum
        await fetch('/sanctum/csrf-cookie', {
            method: 'GET',
            credentials: 'same-origin'
        });
    } catch (e) {
        // If this fails, the subsequent request will surface the auth error
        console.warn('Could not fetch csrf-cookie:', e);
    }
}

function renderSubmissions(submissions) {
    const tbody = document.getElementById('submissions-tbody');
    
    const submissionsHtml = submissions.map(submission => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-700">${submission.user ? submission.user.name.charAt(0).toUpperCase() : 'U'}</span>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">${submission.user ? submission.user.name : 'Unknown User'}</div>
                        <div class="text-sm text-gray-500">${submission.user ? submission.user.email : ''}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${submission.task_list ? submission.task_list.title : 'Unknown List'}</div>
                <div class="text-sm text-gray-500">${submission.task_list ? submission.task_list.description : ''}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusColor(submission.status)}">
                    ${getStatusText(submission.status)}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: ${getProgressPercentage(submission)}%"></div>
                    </div>
                    <span class="ml-2 text-sm text-gray-600">${getProgressPercentage(submission)}%</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${submission.submitted_at ? formatDate(submission.submitted_at) : '-'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end space-x-2">
                    <a href="/admin/submissions/${submission.id}" class="text-indigo-600 hover:text-indigo-900">View</a>
                    ${submission.status === 'completed' ? `<button onclick="reviewSubmission(${submission.id})" class="text-green-600 hover:text-green-900">Review</button>` : ''}
                </div>
            </td>
        </tr>
    `).join('');
    
    tbody.innerHTML = submissionsHtml;
}

function renderPagination(submissions) {
    const paginationDiv = document.getElementById('pagination-container');
    
    let paginationHtml = '';
    
    if (submissions.prev_page_url) {
        paginationHtml += `<a href="${submissions.prev_page_url}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
            Previous
        </a>`;
    }
    
    // Page numbers
    for (let i = 1; i <= submissions.last_page; i++) {
        const isCurrent = i === submissions.current_page;
        paginationHtml += `<a href="?page=${i}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium ${isCurrent ? 'bg-blue-50 text-blue-600 border-blue-500' : 'text-gray-500 bg-white border border-gray-300'} border-t border-b hover:bg-gray-50">
            ${i}
        </a>`;
    }
    
    if (submissions.next_page_url) {
        paginationHtml += `<a href="${submissions.next_page_url}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
            Next
        </a>`;
    }
    
    paginationDiv.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing ${submissions.from} to ${submissions.to} of ${submissions.total} results
            </div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                ${paginationHtml}
            </nav>
        </div>
    `;
}

function getStatusColor(status) {
    switch (status) {
        case 'in_progress': return 'bg-yellow-100 text-yellow-800';
        case 'completed': return 'bg-green-100 text-green-800';
        case 'reviewed': return 'bg-purple-100 text-purple-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}

function getStatusText(status) {
    switch (status) {
        case 'in_progress': return 'In Progress';
        case 'completed': return 'Completed';
        case 'reviewed': return 'Reviewed';
        case 'rejected': return 'Rejected';
        default: return status;
    }
}

function getProgressPercentage(submission) {
    // Use the calculated progress from the API
    if (submission.progress_percentage !== undefined) {
        return submission.progress_percentage;
    }
    
    // Fallback calculation
    if (!submission.submission_tasks || submission.submission_tasks.length === 0) {
        return 0;
    }
    
    const completedTasks = submission.submission_tasks.filter(task => task.status === 'completed').length;
    const totalTasks = submission.submission_tasks.length;
    
    return totalTasks > 0 ? Math.round((completedTasks / totalTasks) * 100) : 0;
}

async function reviewSubmission(submissionId) {
    // This would typically redirect to a review page
    window.location.href = `/admin/submissions/${submissionId}/review`;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}
</script>
@endsection

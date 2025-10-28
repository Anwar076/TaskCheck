@extends('layouts.admin')

@section('page-title', 'Templates')

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Task Templates</h1>
        <p class="mt-1 text-sm text-gray-600">Create and manage task templates for quick list creation</p>
    </div>
    <a href="{{ route('admin.templates.create') }}" 
       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        New Template
    </a>
</div>
@endsection

@section('content')
<div class="p-6">
    <!-- Loading state -->
    <div id="loading-templates" class="text-center py-12" style="display: none;">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        <p class="mt-2 text-sm text-gray-600">Loading templates...</p>
    </div>

    <!-- Error state -->
    <div id="error-templates" class="text-center py-12" style="display: none;">
        <div class="text-red-600 mb-4">
            <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h3 class="text-sm font-medium text-gray-900">Failed to load templates</h3>
        <p class="mt-1 text-sm text-gray-500">Please try refreshing the page.</p>
        <button onclick="loadTemplates()" class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Retry
        </button>
    </div>

    <!-- Templates content -->
    <div id="templates-content">
        <!-- Content will be loaded here -->
    </div>
</div>

<!-- Create List Modal -->
<div id="createListModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Create Task List from Template</h3>
            <form id="createListForm" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="list_title" class="block text-sm font-medium text-gray-700 mb-2">List Title</label>
                    <input type="text" 
                           id="list_title" 
                           name="title" 
                           required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="list_description" class="block text-sm font-medium text-gray-700 mb-2">Description (optional)</label>
                    <textarea id="list_description" 
                              name="description" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeCreateListModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            id="createListBtn"
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Create List
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let isLoading = false;

// Load templates on page initialization
document.addEventListener('DOMContentLoaded', function() {
    loadTemplates();
});

// Handle page visibility changes (when user navigates back to tab)
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        loadTemplates();
    }
});

// Handle page show event (when navigating back with browser buttons)
window.addEventListener('pageshow', function(event) {
    // Force reload if page was loaded from cache
    if (event.persisted) {
        loadTemplates();
    }
});

async function loadTemplates() {
    // Prevent multiple simultaneous requests
    if (isLoading) {
        console.log('Templates already loading, skipping...');
        return;
    }
    
    isLoading = true;
    
    const loadingDiv = document.getElementById('loading-templates');
    const errorDiv = document.getElementById('error-templates'); 
    const contentDiv = document.getElementById('templates-content');
    
    // Show loading state
    loadingDiv.style.display = 'block';
    errorDiv.style.display = 'none';
    contentDiv.style.display = 'none';
    
    try {
        console.log('Loading templates...');
        
        // Add cache-busting parameter and proper headers
        const timestamp = Date.now();
        const response = await fetch(`/admin/templates?_=${timestamp}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Cache-Control': 'no-cache, no-store, must-revalidate',
                'Pragma': 'no-cache',
                'Expires': '0'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        console.log('Templates loaded successfully:', data);
        
        // Hide loading and show content
        loadingDiv.style.display = 'none';
        errorDiv.style.display = 'none';
        contentDiv.style.display = 'block';
        
        // Render the templates
        renderTemplates(data.data || []);
        
    } catch (error) {
        console.error('Failed to load templates:', error);
        
        // Show error state
        loadingDiv.style.display = 'none';
        errorDiv.style.display = 'block';
        contentDiv.style.display = 'none';
        
    } finally {
        isLoading = false;
    }
}

function renderTemplates(templates) {
    const contentDiv = document.getElementById('templates-content');
    
    if (!templates || templates.length === 0) {
        // Empty state
        contentDiv.innerHTML = `
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No templates</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating your first task template.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.templates.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        New Template
                    </a>
                </div>
            </div>
        `;
        return;
    }
    
    // Templates grid
    const templatesHtml = `
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            ${templates.map(template => `
                <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900">${escapeHtml(template.name)}</h3>
                            ${template.description ? `<p class="mt-1 text-sm text-gray-600">${escapeHtml(template.description.length > 100 ? template.description.substring(0, 100) + '...' : template.description)}</p>` : ''}
                            <div class="mt-3 flex items-center text-sm text-gray-500">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                ${template.template_tasks ? template.template_tasks.length : 0} ${template.template_tasks && template.template_tasks.length === 1 ? 'task' : 'tasks'}
                            </div>
                            <div class="mt-2 flex items-center text-sm text-gray-500">
                                <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Used in ${template.task_lists ? template.task_lists.length : 0} ${template.task_lists && template.task_lists.length === 1 ? 'list' : 'lists'}
                            </div>
                        </div>
                        <div class="flex items-center space-x-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${template.is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                ${template.is_active ? 'Active' : 'Inactive'}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mt-4 flex items-center justify-between">
                        <div class="flex space-x-2">
                            <a href="/admin/templates/${template.id}" 
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition-colors">
                                View
                            </a>
                            <a href="/admin/templates/${template.id}/edit" 
                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 hover:bg-blue-200 rounded-md transition-colors">
                                Edit
                            </a>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button onclick="createListFromTemplate(${template.id})" 
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 hover:bg-green-200 rounded-md transition-colors">
                                Create List
                            </button>
                            
                            <button onclick="deleteTemplate(${template.id})" 
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 hover:bg-red-200 rounded-md transition-colors">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
    
    contentDiv.innerHTML = templatesHtml;
}

function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

async function deleteTemplate(templateId) {
    if (!confirm('Are you sure you want to delete this template? This action cannot be undone.')) {
        return;
    }
    
    try {
        const response = await fetch(`/admin/templates/${templateId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        // Reload templates after successful deletion
        await loadTemplates();
        
    } catch (error) {
        console.error('Failed to delete template:', error);
        alert('Failed to delete template. Please try again.');
    }
}

function createListFromTemplate(templateId) {
    const modal = document.getElementById('createListModal');
    const form = document.getElementById('createListForm');
    
    form.action = `/admin/templates/${templateId}/create-list`;
    form.reset(); // Clear previous values
    modal.classList.remove('hidden');
}

function closeCreateListModal() {
    document.getElementById('createListModal').classList.add('hidden');
    document.getElementById('createListForm').reset();
}

// Handle create list form submission
document.getElementById('createListForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const submitBtn = document.getElementById('createListBtn');
    const formData = new FormData(form);
    const originalText = submitBtn.textContent;
    
    // Show loading state
    submitBtn.textContent = 'Creating...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: formData
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            closeCreateListModal();
            // Redirect to the new list if provided
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } else {
            alert(data.message || 'Failed to create list from template');
        }
        
    } catch (error) {
        console.error('Failed to create list from template:', error);
        alert('Failed to create list from template. Please try again.');
        
    } finally {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    }
});

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('createListModal');
    if (e.target === modal) {
        closeCreateListModal();
    }
});
</script>
@endsection

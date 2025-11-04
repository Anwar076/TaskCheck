@extends('layouts.admin')

@section('page-title', $template->name)

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $template->name }}</h1>
        <p class="mt-1 text-sm text-gray-600">{{ $template->description ?: 'No description provided' }}</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="{{ route('admin.templates.edit', $template) }}" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit Template
        </a>
        <button onclick="createListFromTemplate({{ $template->id }})" 
                class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create List
        </button>
        <a href="{{ route('admin.templates.index') }}" 
           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Templates
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="p-6">
    <!-- Template Info -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Template Information</h2>
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <div class="flex items-center">
                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        {{ $template->templateTasks->count() }} {{ Str::plural('task', $template->templateTasks->count()) }}
                    </div>
                    <div class="flex items-center">
                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Used in {{ $template->taskLists->count() }} {{ Str::plural('list', $template->taskLists->count()) }}
                    </div>
                    <div class="flex items-center">
                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Created {{ $template->created_at->format('M j, Y') }}
                    </div>
                </div>
            </div>
            <div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                    {{ $template->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
    </div>
    
    <!-- Tasks List -->
    <div class="bg-white rounded-lg border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Template Tasks</h2>
        
        @if($template->templateTasks->isEmpty())
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks</h3>
                <p class="mt-1 text-sm text-gray-500">This template doesn't have any tasks yet.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($template->templateTasks as $index => $task)
                    <div class="border border-gray-200 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-sm font-medium rounded-full mr-3">
                                        {{ $index + 1 }}
                                    </span>
                                    <h3 class="text-base font-medium text-gray-900">{{ $task->title }}</h3>
                                </div>
                                
                                @if($task->description)
                                    <p class="text-sm text-gray-600 ml-9 mb-2">{{ $task->description }}</p>
                                @endif
                                
                                @if($task->instructions)
                                    <div class="ml-9 mb-3">
                                        <h4 class="text-sm font-medium text-gray-700 mb-1">Instructions:</h4>
                                        <p class="text-sm text-gray-600 whitespace-pre-line">{{ $task->instructions }}</p>
                                    </div>
                                @endif
                                
                                <div class="ml-9 mb-3 flex items-center space-x-4 text-xs">
                                    <div class="flex items-center">
                                        <span class="font-medium text-gray-700">Proof Required:</span>
                                        <span class="ml-1 px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                            {{ ucfirst($task->required_proof_type) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="font-medium text-gray-700">Status:</span>
                                        <span class="ml-1 px-2 py-1 {{ $task->is_required ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }} rounded-full">
                                            {{ $task->is_required ? 'Required' : 'Optional' }}
                                        </span>
                                    </div>
                                </div>
                                
                                @if($task->checklist_items && count($task->checklist_items) > 0)
                                    <div class="ml-9">
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Checklist Items:</h4>
                                        <ul class="space-y-1">
                                            @foreach($task->checklist_items as $item)
                                                <li class="flex items-center text-sm text-gray-600">
                                                    <svg class="mr-2 h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"></path>
                                                    </svg>
                                                    {{ $item }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    
    <!-- Used in Lists -->
    @if($template->taskLists->count() > 0)
        <div class="bg-white rounded-lg border border-gray-200 p-6 mt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Used in Lists</h2>
            <div class="space-y-3">
                @foreach($template->taskLists as $list)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ $list->title }}</h3>
                            <p class="text-sm text-gray-500">Created {{ $list->created_at->format('M j, Y') }}</p>
                        </div>
                        <a href="{{ route('admin.lists.show', $list) }}" 
                           class="text-sm text-blue-600 hover:text-blue-800">View List</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
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
                           value="{{ $template->name }} - {{ now()->format('M j, Y') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="list_description" class="block text-sm font-medium text-gray-700 mb-2">Description (optional)</label>
                    <textarea id="list_description" 
                              name="description" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ $template->description }}</textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeCreateListModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        Create List
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
    
    form.action = `/admin/templates/${templateId}/create-list`;
    modal.classList.remove('hidden');
}

function closeCreateListModal() {
    document.getElementById('createListModal').classList.add('hidden');
}

// Auto-refresh functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check if we just came back from editing
    const urlParams = new URLSearchParams(window.location.search);
    const justUpdated = urlParams.get('updated');
    
    if (justUpdated === '1') {
        // Remove the parameter from URL without page reload
        const newUrl = window.location.pathname;
        window.history.replaceState({}, document.title, newUrl);
        
        // Show success message and refresh content
        showSuccessMessage('Template updated successfully!');
        setTimeout(() => {
            refreshTemplateContent();
        }, 500);
    }
    
    // Listen for page visibility changes (when user comes back to tab)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            // Check if template was recently updated (within last 5 minutes)
            const lastUpdate = localStorage.getItem(`template_${{{ $template->id }}}_updated`);
            if (lastUpdate && (Date.now() - parseInt(lastUpdate)) < 300000) { // 5 minutes
                console.log('Template was recently updated, refreshing content...');
                refreshTemplateContent();
                localStorage.removeItem(`template_${{{ $template->id }}}_updated`);
            }
        }
    });
    
    // Listen for storage events (when template is updated in another tab)
    window.addEventListener('storage', function(e) {
        if (e.key === `template_${{{ $template->id }}}_updated`) {
            console.log('Template updated in another tab, refreshing content...');
            setTimeout(() => {
                refreshTemplateContent();
            }, 1000); // Small delay to ensure the update is complete
        }
    });
});

async function refreshTemplateContent() {
    try {
        console.log('Refreshing template content...');
        
        // Show loading indicator
        const loadingIndicator = document.createElement('div');
        loadingIndicator.id = 'refresh-indicator';
        loadingIndicator.className = 'fixed top-4 right-4 bg-blue-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        loadingIndicator.innerHTML = `
            <div class="flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Updating...
            </div>
        `;
        document.body.appendChild(loadingIndicator);
        
        // Fetch updated template data
        const response = await fetch(`/admin/templates/{{ $template->id }}`, {
            method: 'GET',
            headers: {
                'Accept': 'text/html',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            credentials: 'same-origin'
        });
        
        if (response.ok) {
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update the content areas
            const newContent = doc.querySelector('.p-6');
            const currentContent = document.querySelector('.p-6');
            
            if (newContent && currentContent) {
                currentContent.innerHTML = newContent.innerHTML;
                console.log('Template content updated successfully');
                showSuccessMessage('Template refreshed!');
            }
        }
        
        // Remove loading indicator
        const indicator = document.getElementById('refresh-indicator');
        if (indicator) {
            document.body.removeChild(indicator);
        }
        
    } catch (error) {
        console.error('Failed to refresh template content:', error);
        
        // Remove loading indicator
        const indicator = document.getElementById('refresh-indicator');
        if (indicator) {
            document.body.removeChild(indicator);
        }
        
        // Fallback to full page reload
        window.location.reload();
    }
}

function showSuccessMessage(message) {
    // Create success notification
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300';
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}
</script>
@endsection

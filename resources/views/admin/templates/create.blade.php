@extends('layouts.admin')

@section('page-title', 'Create Template')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Create New Template</h1>
        <p class="mt-1 text-sm text-gray-600">Create a reusable template for task lists</p>
    </div>
    <a href="{{ route('admin.templates.index') }}" 
       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Templates
    </a>
</div>
@endsection

@section('content')
<div class="p-6">
    <form method="POST" action="{{ route('admin.templates.store') }}" id="templateForm">
        @csrf
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        
        <!-- Template Info -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Template Information</h2>
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Template Name</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           required 
                           value="{{ old('name') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                           placeholder="e.g., Daily Cleaning Checklist">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (optional)</label>
                    <textarea id="description" 
                              name="description" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Describe what this template is used for...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Tasks Section -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Template Tasks</h2>
                <button type="button" 
                        onclick="addTask()"
                        class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Task
                </button>
            </div>
            
            <div id="tasks-container" class="space-y-4">
                <!-- Tasks will be added here dynamically -->
            </div>
            
            @error('tasks')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end space-x-3 mt-6">
            <a href="{{ route('admin.templates.index') }}" 
               class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                Cancel
            </a>
            <button type="submit" 
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                Create Template
            </button>
        </div>
    </form>
</div>

<script>
let taskIndex = 0;

// Add initial task if none exist
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('tasks-container').children.length === 0) {
        addTask();
    }
    
    // Setup form submission to ensure CSRF token is included
    const form = document.getElementById('templateForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submitting with CSRF token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
        });
    }
});

function addTask() {
    const container = document.getElementById('tasks-container');
    const taskHtml = `
        <div class="task-item bg-gray-50 rounded-lg p-4 border border-gray-200" data-index="${taskIndex}">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-900">Task ${taskIndex + 1}</h3>
                <div class="flex items-center space-x-2">
                    <button type="button" 
                            onclick="moveTask(${taskIndex}, 'up')"
                            class="p-1 text-gray-400 hover:text-gray-600"
                            ${taskIndex === 0 ? 'disabled' : ''}>
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </button>
                    <button type="button" 
                            onclick="moveTask(${taskIndex}, 'down')"
                            class="p-1 text-gray-400 hover:text-gray-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <button type="button" 
                            onclick="removeTask(${taskIndex})"
                            class="p-1 text-red-400 hover:text-red-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Task Title</label>
                    <input type="text" 
                           name="tasks[${taskIndex}][title]" 
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Enter task title...">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (optional)</label>
                    <textarea name="tasks[${taskIndex}][description]" 
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Enter task description..."></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instructions (optional)</label>
                    <textarea name="tasks[${taskIndex}][instructions]" 
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Enter detailed instructions..."></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Proof Type Required</label>
                        <select name="tasks[${taskIndex}][required_proof_type]" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="none">No Proof Required</option>
                            <option value="photo">Photo Required</option>
                            <option value="video">Video Required</option>
                            <option value="text">Text Description Required</option>
                            <option value="file">File Upload Required</option>
                            <option value="any">Any Proof Type</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Task Required</label>
                        <select name="tasks[${taskIndex}][is_required]" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="1">Required</option>
                            <option value="0">Optional</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Checklist Items (optional)</label>
                    <div class="checklist-container">
                        <div class="checklist-item flex items-center space-x-2 mb-2">
                            <input type="text" 
                                   name="tasks[${taskIndex}][checklist_items][]" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Checklist item...">
                            <button type="button" 
                                    onclick="removeChecklistItem(this)"
                                    class="p-1 text-red-400 hover:text-red-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button type="button" 
                            onclick="addChecklistItem(${taskIndex})"
                            class="text-sm text-blue-600 hover:text-blue-800">
                        + Add checklist item
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', taskHtml);
    taskIndex++;
    updateTaskNumbers();
}

function removeTask(index) {
    const taskItem = document.querySelector(`[data-index="${index}"]`);
    if (taskItem) {
        taskItem.remove();
        updateTaskNumbers();
    }
    
    // Ensure at least one task exists
    if (document.getElementById('tasks-container').children.length === 0) {
        addTask();
    }
}

function moveTask(index, direction) {
    const container = document.getElementById('tasks-container');
    const tasks = Array.from(container.children);
    const currentIndex = tasks.findIndex(task => task.dataset.index == index);
    
    if (direction === 'up' && currentIndex > 0) {
        container.insertBefore(tasks[currentIndex], tasks[currentIndex - 1]);
    } else if (direction === 'down' && currentIndex < tasks.length - 1) {
        container.insertBefore(tasks[currentIndex + 1], tasks[currentIndex]);
    }
    
    updateTaskNumbers();
}

function updateTaskNumbers() {
    const tasks = document.querySelectorAll('.task-item');
    tasks.forEach((task, index) => {
        const title = task.querySelector('h3');
        title.textContent = `Task ${index + 1}`;
        
        // Update move up button state
        const moveUpBtn = task.querySelector('button[onclick*="up"]');
        moveUpBtn.disabled = index === 0;
        moveUpBtn.classList.toggle('opacity-50', index === 0);
    });
}

function addChecklistItem(taskIndex) {
    const container = document.querySelector(`[data-index="${taskIndex}"] .checklist-container`);
    const itemHtml = `
        <div class="checklist-item flex items-center space-x-2 mb-2">
            <input type="text" 
                   name="tasks[${taskIndex}][checklist_items][]" 
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Checklist item...">
            <button type="button" 
                    onclick="removeChecklistItem(this)"
                    class="p-1 text-red-400 hover:text-red-600">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    container.insertAdjacentHTML('beforeend', itemHtml);
}

function removeChecklistItem(button) {
    button.closest('.checklist-item').remove();
}
</script>
@endsection

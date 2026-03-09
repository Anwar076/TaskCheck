@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Clean Page Header -->
        <div class="mb-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">
                        Task Lists
                    </h1>
                    <p class="mt-2 text-gray-600">
                        Organize and manage your task lists efficiently
                    </p>
                </div>
                <div class="mt-6 flex md:ml-4 md:mt-0">
                    <a href="{{ route('admin.lists.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New List
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6 lg:mb-8">
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
                        <p class="text-sm font-medium text-gray-600">Total Lists</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $lists->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6 lg:mb-8">
            <div class="bg-gray-50 px-4 lg:px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Search & Filter</h3>
                    <div class="flex items-center space-x-4">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $lists->total() }} lists
                        </span>
                    </div>
                </div>
                
                <!-- Search Form -->
                <form method="GET" action="{{ route('admin.lists.index') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search Input -->
                        <div class="lg:col-span-2">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Lists</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       id="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search by title, description, or category..." 
                                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select name="category" id="category" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Categories</option>
                                <option value="Cleaning" {{ request('category') === 'Cleaning' ? 'selected' : '' }}>Cleaning</option>
                                <option value="Safety" {{ request('category') === 'Safety' ? 'selected' : '' }}>Safety</option>
                                <option value="Maintenance" {{ request('category') === 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                <option value="Quality" {{ request('category') === 'Quality' ? 'selected' : '' }}>Quality</option>
                                <option value="Security" {{ request('category') === 'Security' ? 'selected' : '' }}>Security</option>
                                <option value="Other" {{ request('category') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <!-- Priority Filter -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                            <select name="priority" id="priority" 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Priorities</option>
                                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                            </select>
                        </div>
                    </div>

                    <!-- Filter Actions -->
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex items-center space-x-4">
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search
                            </button>
                            
                            @if(request()->hasAny(['search', 'category', 'priority']))
                                <a href="{{ route('admin.lists.index') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Clear Filters
                                </a>
                            @endif
                        </div>

                        <!-- Active Filters Display -->
                        @if(request()->hasAny(['search', 'category', 'priority']))
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-600">Active filters:</span>
                                @if(request('search'))
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        Search: "{{ request('search') }}"
                                    </span>
                                @endif
                                @if(request('category'))
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                        Category: {{ request('category') }}
                                    </span>
                                @endif
                                @if(request('priority'))
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Priority: {{ ucfirst(request('priority')) }}
                                    </span>
                                @endif
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Lists Container -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-4 lg:px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">All Task Lists</h3>
                    <div class="flex items-center space-x-4">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $lists->total() }} lists
                        </span>
                    </div>
                </div>
            </div>

            @if($lists->count() > 0)
                <!-- Desktop Table View -->
                <div class="hidden lg:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">List Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule & Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($lists as $list)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-start space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                                        <span class="text-white font-bold text-sm">{{ substr($list->title, 0, 1) }}</span>
                                                    </div>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <h4 class="text-base font-semibold text-gray-900 mb-1">{{ $list->title }}</h4>
                                                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit($list->description, 100) }}</p>
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ $list->category ?? 'Uncategorized' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="space-y-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ ucfirst($list->schedule_type) }}
                                                </span>
                                                <span class="block inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                    @if($list->priority === 'urgent') bg-red-100 text-red-800
                                                    @elseif($list->priority === 'high') bg-orange-100 text-orange-800
                                                    @elseif($list->priority === 'medium') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800 @endif">
                                                    {{ ucfirst($list->priority) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="text-gray-600">Tasks</span>
                                                    <span class="font-semibold text-gray-900">{{ $list->tasks_count }}</span>
                                                </div>
                                                <div class="flex items-center justify-between text-sm">
                                                    <span class="text-gray-600">Submissions</span>
                                                    <span class="font-semibold text-gray-900">{{ $list->submissions_count }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                @if($list->is_active) bg-green-100 text-green-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                <span class="w-2 h-2 rounded-full mr-1
                                                    @if($list->is_active) bg-green-500
                                                    @else bg-gray-500 @endif"></span>
                                                {{ $list->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.lists.show', $list) }}" 
                                                   class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                                    View
                                                </a>
                                                <a href="{{ route('admin.lists.edit', $list) }}" 
                                                   class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                                                    Edit
                                                </a>
                                                <form method="POST" action="{{ route('admin.lists.destroy', $list) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this list?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 transition-colors">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden divide-y divide-gray-200">
                    @foreach($lists as $list)
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ substr($list->title, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-base font-semibold text-gray-900 mb-1">{{ $list->title }}</h4>
                                            <p class="text-sm text-gray-600 mb-3">{{ Str::limit($list->description, 100) }}</p>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                            @if($list->is_active) bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $list->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2 mb-3">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $list->category ?? 'Uncategorized' }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ ucfirst($list->schedule_type) }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                            @if($list->priority === 'urgent') bg-red-100 text-red-800
                                            @elseif($list->priority === 'high') bg-orange-100 text-orange-800
                                            @elseif($list->priority === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800 @endif">
                                            {{ ucfirst($list->priority) }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                                        <span class="font-medium">{{ $list->tasks_count }} tasks</span>
                                        <span class="font-medium">{{ $list->submissions_count }} submissions</span>
                                    </div>

                                    <div class="flex space-x-4">
                                        <a href="{{ route('admin.lists.show', $list) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">View</a>
                                        <a href="{{ route('admin.lists.edit', $list) }}" class="text-green-600 hover:text-green-900 text-sm font-medium">Edit</a>
                                        <form method="POST" action="{{ route('admin.lists.destroy', $list) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No task lists yet</h3>
                    <p class="text-gray-600 mb-4">Get started by creating your first task list to organize your workflow.</p>
                    <a href="{{ route('admin.lists.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create your first list
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($lists->hasPages())
            <div class="mt-6 flex justify-center">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-3">
                    {{ $lists->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
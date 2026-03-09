@extends('layouts.admin')

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
                <div class="mt-6 flex md:ml-4 md:mt-0">
                    <!-- Filter Form -->
                    <form method="GET" class="flex items-center space-x-3">
                        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">All Status</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                            </svg>
                            Filter
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-6 lg:mb-8">
            @php
                $totalSubmissions = $submissions->total();
                $inProgress = \App\Models\Submission::where('status', 'in_progress')->count();
                $completed = \App\Models\Submission::where('status', 'completed')->count();
                $reviewed = \App\Models\Submission::where('status', 'reviewed')->count();
            @endphp
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Submissions</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalSubmissions }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">In Progress</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $inProgress }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Completed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $completed }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 lg:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Reviewed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $reviewed }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submissions List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-4 lg:px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Submissions</h3>
                    <div class="flex items-center space-x-4">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $submissions->total() }} submissions
                        </span>
                    </div>
                </div>
            </div>

            @if($submissions->count() > 0)
                <!-- Desktop Table View -->
                <div class="hidden lg:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task List</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Started</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completed</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($submissions as $submission)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                                        <span class="text-white font-bold text-sm">{{ substr($submission->user->name, 0, 2) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-base font-semibold text-gray-900">{{ $submission->user->name }}</div>
                                                    <div class="text-sm text-gray-600">{{ $submission->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-base font-semibold text-gray-900">{{ $submission->taskList->title }}</div>
                                            <div class="text-sm text-gray-600">{{ $submission->taskList->category ?? 'No category' }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                                @if($submission->status === 'completed') bg-yellow-100 text-yellow-800
                                                @elseif($submission->status === 'reviewed') bg-green-100 text-green-800
                                                @elseif($submission->status === 'rejected') bg-red-100 text-red-800
                                                @else bg-blue-100 text-blue-800 @endif">
                                                {{ ucfirst($submission->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 mr-3">
                                                    <span class="text-sm font-semibold text-gray-900">{{ $submission->completion_percentage }}%</span>
                                                </div>
                                                <div class="flex-1 bg-gray-200 rounded-full h-2 max-w-20">
                                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $submission->completion_percentage }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $submission->started_at ? $submission->started_at->format('M j, Y g:i A') : $submission->created_at->format('M j, Y g:i A') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            {{ $submission->completed_at ? $submission->completed_at->format('M j, Y g:i A') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.submissions.show', $submission) }}" 
                                                   class="inline-flex items-center px-3 py-1 border border-gray-300 text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                                    View
                                                </a>
                                                @if($submission->status === 'completed')
                                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                                        Ready for Review
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No submissions</h3>
                                            <p class="text-gray-600">No submissions match your current filter criteria.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Mobile Card View -->
                <div class="lg:hidden divide-y divide-gray-200">
                    @foreach($submissions as $submission)
                        <div class="p-4 hover:bg-gray-50">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-bold text-sm">{{ substr($submission->user->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="text-base font-semibold text-gray-900 mb-1">{{ $submission->user->name }}</h4>
                                            <p class="text-sm text-gray-600 mb-2">{{ $submission->user->email }}</p>
                                            <h5 class="text-sm font-medium text-gray-800 mb-2">{{ $submission->taskList->title }}</h5>
                                        </div>
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                                            @if($submission->status === 'completed') bg-yellow-100 text-yellow-800
                                            @elseif($submission->status === 'reviewed') bg-green-100 text-green-800
                                            @elseif($submission->status === 'rejected') bg-red-100 text-red-800
                                            @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($submission->status) }}
                                        </span>
                                    </div>
                                    
                                    <div class="mt-3 flex items-center justify-between text-sm text-gray-600 mb-3">
                                        <span class="font-medium">{{ $submission->completion_percentage }}% complete</span>
                                        <span class="font-medium">{{ $submission->taskList->category ?? 'No category' }}</span>
                                    </div>

                                    <div class="flex items-center justify-between text-xs text-gray-500 mb-4">
                                        <span>Started: {{ $submission->started_at ? $submission->started_at->format('M j, Y') : $submission->created_at->format('M j, Y') }}</span>
                                        <span>Completed: {{ $submission->completed_at ? $submission->completed_at->format('M j, Y') : '-' }}</span>
                                    </div>

                                    <div class="flex space-x-4">
                                        <a href="{{ route('admin.submissions.show', $submission) }}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">View Details</a>
                                        @if($submission->status === 'completed')
                                            <span class="text-green-600 text-sm font-medium">Ready for Review</span>
                                        @endif
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
                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No submissions</h3>
                    <p class="text-gray-600">No submissions match your current filter criteria.</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($submissions->hasPages())
            <div class="mt-6 flex justify-center">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-4 py-3">
                    {{ $submissions->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

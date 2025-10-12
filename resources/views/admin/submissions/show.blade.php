    @extends('layouts.admin')

@section('content')
        @if($submission->employee_signature)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-2">Checklist Signature</h3>
                <span class="block text-xs text-gray-500 mb-1">Employee's Drawn Signature:</span>
                <img src="{{ $submission->employee_signature }}" alt="Checklist Signature" class="border border-gray-300 rounded bg-white max-w-xs max-h-32">
            </div>
        @endif
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Submission Review</h1>
                <p class="mt-2 text-gray-600">{{ $submission->taskList->title }} by {{ $submission->user->name }}</p>
                <div class="mt-4 flex items-center space-x-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                        @if($submission->status === 'completed') bg-yellow-100 text-yellow-800
                        @elseif($submission->status === 'reviewed') bg-green-100 text-green-800
                        @elseif($submission->status === 'rejected') bg-red-100 text-red-800
                        @else bg-blue-100 text-blue-800 @endif">
                        {{ ucfirst($submission->status) }}
                    </span>
                    <span class="text-sm text-gray-500">
                        Started: {{ $submission->started_at ? $submission->started_at->format('M j, Y g:i A') : $submission->created_at->format('M j, Y g:i A') }}
                    </span>
                    @if($submission->completed_at)
                        <span class="text-sm text-gray-500">
                            Completed: {{ $submission->completed_at->format('M j, Y g:i A') }}
                        </span>
                    @endif
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.submissions.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    ← Back to Submissions
                </a>
            </div>
        </div>
    </div>

    <!-- Tasks Review -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Task Review</h3>
        </div>
        <div class="divide-y divide-gray-200">
            @foreach($submission->submissionTasks as $submissionTask)
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <h4 class="text-lg font-medium text-gray-900">{{ $submissionTask->task->title }}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($submissionTask->status === 'completed') bg-yellow-100 text-yellow-800
                                    @elseif($submissionTask->status === 'approved') bg-green-100 text-green-800
                                    @elseif($submissionTask->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                    {{ ucfirst($submissionTask->status) }}
                                </span>
                            </div>
                            
                            @if($submissionTask->task->description)
                                <p class="mt-2 text-sm text-gray-600">{{ $submissionTask->task->description }}</p>
                            @endif

                            <!-- Checklist Items -->
                            @if($submissionTask->task->checklist_items && count($submissionTask->task->checklist_items) > 0)
                                <div class="mt-4 bg-cyan-50 rounded-lg p-4 border border-cyan-200">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h5 class="text-sm font-semibold text-cyan-900 mb-2">Checklist Items</h5>
                                            <div class="space-y-1">
                                                @php
                                                    $checklistProgress = is_array($submissionTask->checklist_progress) ? $submissionTask->checklist_progress : [];
                                                    $completedCount = 0;
                                                @endphp
                                                @foreach($submissionTask->task->checklist_items as $index => $item)
                                                    @php
                                                        $isChecked = isset($checklistProgress[$index]) && $checklistProgress[$index];
                                                        if ($isChecked) $completedCount++;
                                                    @endphp
                                                    <div class="flex items-center space-x-2 text-sm {{ $isChecked ? 'text-green-800' : 'text-gray-600' }}">
                                                        @if($isChecked)
                                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        @else
                                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                        @endif
                                                        <span class="{{ $isChecked ? 'font-medium' : 'opacity-75' }}">{{ $item }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <p class="text-xs text-cyan-600 mt-2 italic">
                                                {{ $completedCount }}/{{ count($submissionTask->task->checklist_items) }} items checked by employee
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Employee's Proof -->
                            @if($submissionTask->proof_text || $submissionTask->proof_files || $submissionTask->digital_signature)
                                <div class="mt-4 bg-gray-50 rounded-lg p-4">
                                    <h5 class="text-sm font-medium text-gray-900 mb-2">Employee's Proof:</h5>
                                    @if($submissionTask->proof_text)
                                        <p class="text-sm text-gray-700 mb-2">{{ $submissionTask->proof_text }}</p>
                                    @endif
                                    @if($submissionTask->proof_files)
                                        <div class="space-y-1">
                                            @foreach($submissionTask->proof_files as $file)
                                                @php
                                                    $filename = is_array($file) ? (isset($file['path']) ? basename($file['path']) : '') : basename($file);
                                                    $isImage = is_array($file) && isset($file['mime_type']) && strpos($file['mime_type'], 'image/') === 0;
                                                @endphp
                                                <div class="flex flex-col text-sm text-blue-600">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-5L9 2H4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $filename }}
                                                    </div>
                                                    @if($isImage && isset($file['path']))
                                                        <img src="{{ url('storage/' . $file['path']) }}" alt="{{ $filename }}" class="mt-2 max-w-xs max-h-40 rounded shadow border" />
                                                    @endif
                                                    @if(is_array($file) && isset($file['mime_type']) && strpos($file['mime_type'], 'video/') === 0 && isset($file['path']))
                                                        <video controls class="mt-2 max-w-xs max-h-40 rounded shadow border">
                                                            <source src="{{ url('storage/' . $file['path']) }}" type="{{ $file['mime_type'] }}">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($submissionTask->digital_signature)
                                        <div class="mt-3">
                                            <span class="block text-xs text-gray-500 mb-1">Employee Signature:</span>
                                            <img src="{{ $submissionTask->digital_signature }}" alt="Signature" class="border border-gray-300 rounded bg-white max-w-xs max-h-32">
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Manager Comment -->
                            @if($submissionTask->manager_comment || $submissionTask->rejection_reason)
                                <div class="mt-4 bg-blue-50 rounded-lg p-4">
                                    <h5 class="text-sm font-medium text-blue-900 mb-2">Manager Review:</h5>
                                    @if($submissionTask->rejection_reason)
                                        <div class="bg-red-100 border border-red-200 rounded p-3 mb-2">
                                            <p class="text-sm font-medium text-red-800">Rejection Reason:</p>
                                            <p class="text-sm text-red-700">{{ $submissionTask->rejection_reason }}</p>
                                        </div>
                                    @endif
                                    @if($submissionTask->manager_comment)
                                        <p class="text-sm text-blue-800">{{ $submissionTask->manager_comment }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        @if($submissionTask->status === 'completed')
                            <div class="ml-6 flex flex-col space-y-2">
                                <!-- Approve Button -->
                                <form method="POST" action="{{ route('admin.submissions.review', $submission) }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="task_reviews[{{ $submissionTask->task_id }}][status]" value="approved">
                                    <div class="mb-2">
                                        <textarea name="task_reviews[{{ $submissionTask->task_id }}][comment]" 
                                                  placeholder="Optional comment..." 
                                                  rows="2" 
                                                  class="w-full text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"></textarea>
                                    </div>
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                        Approve
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <form method="POST" action="{{ route('admin.submission-tasks.reject', $submissionTask) }}" class="inline">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="rejection_reason" 
                                                  placeholder="Reason for rejection (required)..." 
                                                  rows="2" 
                                                  required
                                                  class="w-full text-sm border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"></textarea>
                                    </div>
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @elseif($submissionTask->status === 'rejected')
                            <div class="ml-6">
                                <form method="POST" action="{{ route('admin.submission-tasks.redo', $submissionTask) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700">
                                        Request Redo
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

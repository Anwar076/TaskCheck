    @extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Enhanced Page Header -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
                <div class="flex justify-between items-start">
                    <div class="flex items-start space-x-4">
                        <!-- Employee Avatar -->
                        <div class="flex-shrink-0">
                            <div class="w-16 h-16 rounded-full bg-white bg-opacity-20 flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">
                                    {{ substr($submission->user->name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        
                        <div>
                            <h1 class="text-3xl font-bold text-white">Submission Review</h1>
                            <p class="mt-2 text-blue-100 text-lg">{{ $submission->taskList->title }}</p>
                            <p class="text-blue-200">by {{ $submission->user->name }} • {{ $submission->user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <!-- Status Badge -->
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-sm
                            @if($submission->status === 'completed') bg-yellow-100 text-yellow-800 border border-yellow-200
                            @elseif($submission->status === 'reviewed') bg-green-100 text-green-800 border border-green-200
                            @elseif($submission->status === 'rejected') bg-red-100 text-red-800 border border-red-200
                            @else bg-blue-100 text-blue-800 border border-blue-200 @endif">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                @if($submission->status === 'completed')
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                @elseif($submission->status === 'reviewed')
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                @elseif($submission->status === 'rejected')
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                @else
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                @endif
                            </svg>
                            {{ ucfirst($submission->status) }}
                        </span>
                        
                        <a href="{{ route('admin.submissions.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white bg-opacity-20 hover:bg-opacity-30 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back to Submissions
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Timeline Info -->
            <div class="px-8 py-6 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-8">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Started</p>
                                <p class="text-sm text-gray-600">{{ $submission->started_at ? $submission->started_at->format('M j, Y g:i A') : $submission->created_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($submission->completed_at)
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Completed</p>
                                    <p class="text-sm text-gray-600">{{ $submission->completed_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                        @endif
                        
                        @php
                            $totalTasks = $submission->submissionTasks->count();
                            $completedTasks = $submission->submissionTasks->where('status', 'completed')->count();
                            $approvedTasks = $submission->submissionTasks->where('status', 'approved')->count();
                            $progress = $totalTasks > 0 ? round(($completedTasks + $approvedTasks) / $totalTasks * 100) : 0;
                        @endphp
                        
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Progress</p>
                                <p class="text-sm text-gray-600">{{ $completedTasks + $approvedTasks }}/{{ $totalTasks }} tasks ({{ $progress }}%)</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="w-48">
                        <div class="bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Signature Section -->
        @if($submission->employee_signature)
            <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-6 mb-8">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Employee Signature</h3>
                        <p class="text-sm text-gray-600 mb-4">Digital signature provided by employee</p>
                        <div class="bg-gray-50 rounded-lg p-4 inline-block">
                            <img src="{{ $submission->employee_signature }}" 
                                 alt="Employee Signature" 
                                 class="border border-gray-300 rounded bg-white max-w-xs max-h-32 shadow-sm">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Enhanced Tasks Review -->
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-8 py-6 border-b border-gray-200">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900">Task Review</h3>
                    <span class="text-sm text-gray-500">• {{ $submission->submissionTasks->count() }} tasks total</span>
                </div>
            </div>
            
            <div class="divide-y divide-gray-100">
                @foreach($submission->submissionTasks as $index => $submissionTask)
                    <div class="p-8 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- Task Header -->
                                <div class="flex items-start space-x-4 mb-6">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-sm">
                                            <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-3 mb-2">
                                            <h4 class="text-xl font-bold text-gray-900">{{ $submissionTask->task->title }}</h4>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold shadow-sm
                                                @if($submissionTask->status === 'completed') bg-yellow-100 text-yellow-800 border border-yellow-200
                                                @elseif($submissionTask->status === 'approved') bg-green-100 text-green-800 border border-green-200
                                                @elseif($submissionTask->status === 'rejected') bg-red-100 text-red-800 border border-red-200
                                                @elseif($submissionTask->status === 'redo_requested') bg-orange-100 text-orange-800 border border-orange-200
                                                @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    @if($submissionTask->status === 'completed')
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    @elseif($submissionTask->status === 'approved')
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    @elseif($submissionTask->status === 'rejected')
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    @elseif($submissionTask->status === 'redo_requested')
                                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                                                    @else
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    @endif
                                                </svg>
                                                {{ $submissionTask->status === 'redo_requested' ? 'Redo Requested' : ucfirst(str_replace('_', ' ', $submissionTask->status)) }}
                                            </span>
                                        </div>
                                        
                                        @if($submissionTask->task->description)
                                            <p class="text-gray-600 leading-relaxed">{{ $submissionTask->task->description }}</p>
                                        @endif
                                        
                                        @if($submissionTask->task->instructions)
                                            <div class="mt-3 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                                <div class="flex items-start space-x-2">
                                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <div>
                                                        <p class="text-sm font-medium text-blue-900">Instructions</p>
                                                        <p class="text-sm text-blue-800 mt-1 whitespace-pre-line">{{ $submissionTask->task->instructions }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Enhanced Checklist Items -->
                                @if($submissionTask->task->checklist_items && count($submissionTask->task->checklist_items) > 0)
                                    @php
                                        $checklistProgress = is_array($submissionTask->checklist_progress) ? $submissionTask->checklist_progress : [];
                                        $completedCount = 0;
                                    @endphp
                                    
                                    <div class="mt-6">
                                        <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-6 border border-emerald-200 shadow-sm">
                                            <div class="flex items-center justify-between mb-4">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                                        </svg>
                                                    </div>
                                                    <h5 class="text-lg font-bold text-emerald-900">Checklist Items</h5>
                                                </div>
                                                
                                                @php
                                                    foreach($submissionTask->task->checklist_items as $index => $item) {
                                                        if (isset($checklistProgress[$index]) && $checklistProgress[$index]) {
                                                            $completedCount++;
                                                        }
                                                    }
                                                    $progressPercent = count($submissionTask->task->checklist_items) > 0 ? round(($completedCount / count($submissionTask->task->checklist_items)) * 100) : 0;
                                                @endphp
                                                
                                                <div class="text-right">
                                                    <div class="text-2xl font-bold text-emerald-900">{{ $completedCount }}/{{ count($submissionTask->task->checklist_items) }}</div>
                                                    <div class="text-sm text-emerald-700">{{ $progressPercent }}% completed</div>
                                                </div>
                                            </div>
                                            
                                            <!-- Progress bar -->
                                            <div class="mb-4">
                                                <div class="bg-emerald-200 rounded-full h-2">
                                                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-2 rounded-full transition-all duration-300" 
                                                         style="width: {{ $progressPercent }}%"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="space-y-3">
                                                @foreach($submissionTask->task->checklist_items as $index => $item)
                                                    @php
                                                        $isChecked = isset($checklistProgress[$index]) && $checklistProgress[$index];
                                                    @endphp
                                                    <div class="flex items-center space-x-3 p-3 rounded-lg transition-colors {{ $isChecked ? 'bg-white bg-opacity-60' : 'bg-emerald-100 bg-opacity-50' }}">
                                                        <div class="flex-shrink-0">
                                                            @if($isChecked)
                                                                <div class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                    </svg>
                                                                </div>
                                                            @else
                                                                <div class="w-6 h-6 border-2 border-gray-300 rounded-full"></div>
                                                            @endif
                                                        </div>
                                                        <span class="text-sm {{ $isChecked ? 'text-emerald-900 font-medium' : 'text-gray-600' }}">
                                                            {{ $item }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Enhanced Employee's Proof -->
                                @if($submissionTask->proof_text || $submissionTask->proof_files || $submissionTask->digital_signature)
                                    <div class="mt-6">
                                        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-200 shadow-sm">
                                            <div class="flex items-center space-x-3 mb-4">
                                                <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                </div>
                                                <h5 class="text-lg font-bold text-purple-900">Employee's Proof</h5>
                                            </div>
                                            
                                            @if($submissionTask->proof_text)
                                                <div class="mb-4 p-4 bg-white bg-opacity-60 rounded-lg">
                                                    <p class="text-sm font-medium text-purple-900 mb-2">Description:</p>
                                                    <p class="text-gray-700 leading-relaxed">{{ $submissionTask->proof_text }}</p>
                                                </div>
                                            @endif
                                            
                                            @if($submissionTask->proof_files)
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                                    @foreach($submissionTask->proof_files as $file)
                                                        @php
                                                            $filename = is_array($file) ? (isset($file['path']) ? basename($file['path']) : '') : basename($file);
                                                            $isImage = is_array($file) && isset($file['mime_type']) && strpos($file['mime_type'], 'image/') === 0;
                                                            $isVideo = is_array($file) && isset($file['mime_type']) && strpos($file['mime_type'], 'video/') === 0;
                                                        @endphp
                                                        <div class="bg-white bg-opacity-60 rounded-lg p-4">
                                                            <div class="flex items-center space-x-2 mb-3">
                                                                <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                                    @if($isImage)
                                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                                    @elseif($isVideo)
                                                                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                                                                    @else
                                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-5L9 2H4z" clip-rule="evenodd"/>
                                                                    @endif
                                                                </svg>
                                                                <span class="text-sm font-medium text-purple-900">{{ $filename }}</span>
                                                            </div>
                                                            
                                                            @if($isImage && isset($file['path']))
                                                                <img src="{{ url('storage/' . $file['path']) }}" 
                                                                     alt="{{ $filename }}" 
                                                                     class="w-full h-48 object-cover rounded-lg shadow-sm border border-gray-200 cursor-pointer hover:shadow-md transition-shadow" 
                                                                     onclick="openImageModal('{{ url('storage/' . $file['path']) }}', '{{ $filename }}')" />
                                                            @elseif($isVideo && isset($file['path']))
                                                                <video controls class="w-full h-48 rounded-lg shadow-sm border border-gray-200">
                                                                    <source src="{{ url('storage/' . $file['path']) }}" type="{{ $file['mime_type'] }}">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                            @else
                                                                <div class="flex items-center justify-center h-24 bg-gray-100 rounded-lg">
                                                                    <span class="text-gray-500 text-sm">File attachment</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            
                                            @if($submissionTask->digital_signature)
                                                <div class="bg-white bg-opacity-60 rounded-lg p-4">
                                                    <p class="text-sm font-medium text-purple-900 mb-3">Digital Signature:</p>
                                                    <div class="bg-gray-50 rounded-lg p-4 inline-block">
                                                        <img src="{{ $submissionTask->digital_signature }}" 
                                                             alt="Digital Signature" 
                                                             class="border border-gray-300 rounded bg-white max-w-xs max-h-32 shadow-sm">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Enhanced Manager Review -->
                                @if($submissionTask->manager_comment || $submissionTask->rejection_reason)
                                    <div class="mt-6">
                                        <div class="bg-gradient-to-r from-slate-50 to-gray-50 rounded-xl p-6 border border-slate-200 shadow-sm">
                                            <div class="flex items-center space-x-3 mb-4">
                                                <div class="w-8 h-8 bg-slate-500 rounded-lg flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                </div>
                                                <h5 class="text-lg font-bold text-slate-900">Manager Review</h5>
                                            </div>
                                            
                                            @if($submissionTask->rejection_reason)
                                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                                    <div class="flex items-start space-x-3">
                                                        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <div>
                                                            <p class="font-semibold text-red-900 mb-1">Rejection Reason</p>
                                                            <p class="text-red-800 leading-relaxed">{{ $submissionTask->rejection_reason }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($submissionTask->manager_comment)
                                                <div class="bg-white bg-opacity-60 rounded-lg p-4">
                                                    <p class="font-semibold text-slate-900 mb-2">Manager Comment</p>
                                                    <p class="text-slate-700 leading-relaxed">{{ $submissionTask->manager_comment }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Enhanced Action Buttons -->
                            @if($submissionTask->status === 'completed')
                                <div class="ml-8 flex-shrink-0 w-80">
                                    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl p-6 border border-gray-200 shadow-sm">
                                        <h6 class="text-lg font-bold text-gray-900 mb-4 text-center">Review Actions</h6>
                                        
                                        <!-- Approve Form -->
                                        <form method="POST" action="{{ route('admin.submission-tasks.approve', $submissionTask) }}" class="mb-4" id="approve-form-{{ $submissionTask->id }}">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Approval Comment (Optional)</label>
                                                <textarea name="manager_comment" 
                                                          placeholder="Add your feedback or comments..." 
                                                          rows="3" 
                                                          class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm resize-none"></textarea>
                                            </div>
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 transform hover:scale-105" id="approve-btn-{{ $submissionTask->id }}">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Approve Task
                                            </button>
                                        </form>

                                        <!-- Reject Form -->
                                        <form method="POST" action="{{ route('admin.submission-tasks.reject', $submissionTask) }}" id="reject-form-{{ $submissionTask->id }}">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason (Required)</label>
                                                <textarea name="rejection_reason" 
                                                          placeholder="Please explain why this task is being rejected..." 
                                                          rows="3" 
                                                          required
                                                          class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 shadow-sm resize-none"></textarea>
                                            </div>
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 transform hover:scale-105" id="reject-btn-{{ $submissionTask->id }}">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Reject Task
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @elseif($submissionTask->status === 'rejected')
                                <div class="ml-8 flex-shrink-0 w-80">
                                    <div class="bg-gradient-to-br from-red-50 to-rose-50 rounded-xl p-6 border border-red-200 shadow-sm">
                                        <div class="text-center mb-4">
                                            <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </div>
                                            <h6 class="text-lg font-bold text-red-900 mb-2">Task Rejected</h6>
                                            <p class="text-sm text-red-700 mb-4">This task has been rejected and is waiting for further action.</p>
                                        </div>
                                        
                                        <form method="POST" action="{{ route('admin.submission-tasks.redo', $submissionTask) }}" id="redo-form-{{ $submissionTask->id }}">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Redo Reason (Optional)</label>
                                                <textarea name="redo_reason" 
                                                          placeholder="Explain why the employee should redo this task..." 
                                                          rows="2" 
                                                          class="w-full text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 shadow-sm resize-none"></textarea>
                                            </div>
                                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 transform hover:scale-105" id="redo-btn-{{ $submissionTask->id }}">
                                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                Request Employee Redo
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @elseif($submissionTask->status === 'approved')
                                <div class="ml-8 flex-shrink-0 w-80">
                                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl p-6 border border-green-200 shadow-sm">
                                        <div class="text-center">
                                            <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </div>
                                            <h6 class="text-lg font-bold text-green-900 mb-2">Task Approved</h6>
                                            <p class="text-sm text-green-700">This task has been reviewed and approved.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($submissionTask->status === 'redo_requested')
                                <div class="ml-8 flex-shrink-0 w-80">
                                    <div class="bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl p-6 border border-orange-200 shadow-sm">
                                        <div class="text-center">
                                            <div class="w-16 h-16 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                            </div>
                                            <h6 class="text-lg font-bold text-orange-900 mb-2">Redo Requested</h6>
                                            <p class="text-sm text-orange-700">Employee has been asked to redo this task.</p>
                                            @if($submissionTask->redo_reason)
                                                <div class="mt-4 p-3 bg-orange-100 rounded-lg">
                                                    <p class="text-sm text-orange-800"><strong>Reason:</strong> {{ $submissionTask->redo_reason }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Image Modal for full-size viewing -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-4xl max-h-full overflow-hidden shadow-2xl">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900"></h3>
            <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-4">
            <img id="modalImage" src="" alt="" class="max-w-full max-h-96 mx-auto rounded-lg shadow-sm">
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc, title) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('imageModal').classList.add('flex');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.getElementById('imageModal').classList.remove('flex');
}

// Close modal when clicking outside
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Auto-refresh functionality after form submissions
document.addEventListener('DOMContentLoaded', function() {
    // Handle approve form submissions
    document.querySelectorAll('[id^="approve-form-"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const taskId = form.id.replace('approve-form-', '');
            const submitBtn = document.getElementById('approve-btn-' + taskId);
            
            // Show loading state
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Processing...
            `;
            submitBtn.disabled = true;
            
            // Submit form via fetch
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (response.ok || response.status === 302) {
                    // Show success message
                    showNotification('Task approved successfully!', 'success');
                    // Reload page after short delay to show new status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    return response.text().then(text => {
                        console.error('Error response:', text);
                        throw new Error('Network response was not ok: ' + response.status);
                    });
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showNotification('Error approving task. Please try again.', 'error');
                // Reset button
                submitBtn.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Approve Task
                `;
                submitBtn.disabled = false;
            });
        });
    });
    
    // Handle reject form submissions
    document.querySelectorAll('[id^="reject-form-"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const taskId = form.id.replace('reject-form-', '');
            const submitBtn = document.getElementById('reject-btn-' + taskId);
            
            // Check if rejection reason is provided
            const rejectionReason = form.querySelector('textarea[name="rejection_reason"]').value.trim();
            if (!rejectionReason) {
                showNotification('Rejection reason is required', 'error');
                return;
            }
            
            // Show loading state
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Processing...
            `;
            submitBtn.disabled = true;
            
            // Submit form via fetch
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Reject response status:', response.status);
                if (response.ok || response.status === 302) {
                    // Show success message
                    showNotification('Task rejected successfully! Employee will be notified.', 'success');
                    // Reload page after short delay to show new status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    return response.text().then(text => {
                        console.error('Reject error response:', text);
                        throw new Error('Network response was not ok: ' + response.status);
                    });
                }
            })
            .catch(error => {
                console.error('Reject fetch error:', error);
                showNotification('Error rejecting task. Please try again.', 'error');
                // Reset button
                submitBtn.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reject Task
                `;
                submitBtn.disabled = false;
            });
        });
    });
    
    // Handle redo form submissions
    document.querySelectorAll('[id^="redo-form-"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const taskId = form.id.replace('redo-form-', '');
            const submitBtn = document.getElementById('redo-btn-' + taskId);
            
            // Show loading state
            submitBtn.innerHTML = `
                <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Processing...
            `;
            submitBtn.disabled = true;
            
            // Submit form via fetch
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Redo response status:', response.status);
                if (response.ok || response.status === 302) {
                    // Show success message
                    showNotification('Redo request sent successfully! Employee can now redo this task.', 'success');
                    // Reload page after short delay to show new status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    return response.text().then(text => {
                        console.error('Redo error response:', text);
                        throw new Error('Network response was not ok: ' + response.status);
                    });
                }
            })
            .catch(error => {
                console.error('Redo fetch error:', error);
                showNotification('Error requesting redo. Please try again.', 'error');
                // Reset button
                submitBtn.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Request Employee Redo
                `;
                submitBtn.disabled = false;
            });
        });
    });
});

// Notification function
function showNotification(message, type = 'success') {
    // Remove existing notification if any
    const existingNotification = document.querySelector('.notification-toast');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-5 right-5 z-50 max-w-sm p-4 rounded-lg shadow-lg transform transition-all duration-300 ease-out ${
        type === 'success' 
            ? 'bg-green-500 text-white' 
            : 'bg-red-500 text-white'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                ${type === 'success' 
                    ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                    : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                }
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <div class="ml-auto pl-3">
                <div class="flex">
                    <button type="button" onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" class="rounded-md p-1.5 hover:bg-black hover:bg-opacity-10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}
</script>

@endsection

@extends('layouts.employee')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Clean Header Section -->
    <div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 sm:gap-6">
            
            {{-- Titel + info --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3 sm:gap-4 mb-2 sm:mb-3">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 md:w-12 md:h-12 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>

                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 leading-tight break-words">
                            {{ $submission->taskList->title }}
                        </h1>
                        <p class="mt-1 text-xs sm:text-sm lg:text-base text-gray-600 font-medium">
                            Started {{ $submission->started_at->format('M j, Y g:i A') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Progress indicator --}}
            <div class="flex flex-row md:flex-col items-center justify-between md:items-end gap-2 md:gap-1">
                @php
                    $completedTasks = $submission->submissionTasks->where('status', 'completed')->count();
                    $totalTasks = $submission->submissionTasks->count();
                    $progressPercent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                @endphp

                <div class="w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 relative">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" stroke="#e5e7eb" stroke-width="6" fill="none" />
                        <circle cx="50" cy="50" r="40"
                            stroke="#3b82f6"
                            stroke-width="6"
                            fill="none"
                            stroke-linecap="round"
                            stroke-dasharray="{{ 2 * 3.14159 * 40 }}"
                            stroke-dashoffset="{{ 2 * 3.14159 * 40 * (1 - ($progressPercent / 100)) }}"
                            class="transition-all duration-1000 ease-out">
                        </circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <div class="text-sm sm:text-base lg:text-lg font-bold text-gray-900">
                            {{ $progressPercent }}%
                        </div>
                    </div>
                </div>

                <p class="text-[11px] sm:text-xs lg:text-sm text-gray-500">
                        {{ $completedTasks }}/{{ $totalTasks }} tasks
                    </p>
                </div>
            </div>
        </div>
    </div>


    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-6 sm:py-8">

        <!-- Tasks -->
        <div class="space-y-6">
            @foreach($submission->submissionTasks as $index => $submissionTask)
                @php $task = $submissionTask->task; @endphp
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden task-card"
                     data-task-id="{{ $task->id }}"
                     data-required="{{ $task->is_required ? '1' : '0' }}"
                     data-status="{{ $submissionTask->status }}">
                    <!-- Task Header -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100 p-4 sm:p-6">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3 sm:mr-4">
                                    @if($submissionTask->status === 'completed')
                                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-green-500 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                            <span class="text-sm font-bold text-blue-700">{{ $index + 1 }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start gap-2 mb-2">
                                        <h3 class="text-lg sm:text-xl font-bold {{ $submissionTask->status === 'completed' ? 'text-green-900' : 'text-gray-900' }}">
                                            {{ $task->title }}
                                        </h3>
                                        @if($task->instructions)
                                            <button type="button" 
                                                    onclick="toggleInstructions(event, 'task-{{ $submissionTask->id }}')"
                                                    class="flex-shrink-0 p-1 rounded-full hover:bg-gray-200 transition-colors"
                                                    title="Klik voor gedetailleerde instructies">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                    @if($task->description)
                                        <p class="text-sm sm:text-base {{ $submissionTask->status === 'completed' ? 'text-green-700' : 'text-gray-600' }}">
                                            {{ $task->description }}
                                        </p>
                                    @endif
                                    
                                    @if($task->instructions)
                                        <!-- Collapsible Instructions -->
                                        <div id="instructions-task-{{ $submissionTask->id }}" class="hidden mt-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 mr-2">
                                                    <svg class="w-4 h-4 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <h4 class="text-sm font-semibold text-blue-900 mb-1">Gedetailleerde Instructies</h4>
                                                    <p class="text-sm text-blue-700 whitespace-pre-line leading-relaxed">{{ $task->instructions }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2 md:justify-end">
                                @if($task->is_required)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                        Required
                                    </span>
                                @endif
                                @if($task->requires_signature)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                        </svg>
                                        Signature
                                    </span>
                                @endif
                                @if($submissionTask->status === 'completed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Completed
                                    </span>
                                @elseif($submissionTask->status === 'rejected')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        Rejected
                                    </span>
                                @elseif($submissionTask->status === 'redo_requested')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Redo Requested
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($submissionTask->status === 'rejected' || $submissionTask->redo_requested)
                        <!-- Rejection/Redo Information -->
                        <div class="bg-red-50 border-l-4 border-red-400 px-4 sm:px-6 py-4 sm:py-6">
                            <div class="flex flex-col sm:flex-row items-start gap-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 text-sm sm:text-base">
                                    @if($submissionTask->status === 'rejected')
                                        <h4 class="text-base sm:text-lg font-semibold text-red-900 mb-2">Task Rejected</h4>
                                        @if($submissionTask->rejection_reason)
                                            <p class="text-red-800 mb-2">
                                                <strong>Reason:</strong> {{ $submissionTask->rejection_reason }}
                                            </p>
                                        @endif
                                        <p class="text-red-700">
                                            This task was rejected on {{ $submissionTask->rejected_at ? $submissionTask->rejected_at->format('M j, Y g:i A') : 'unknown date' }}. 
                                            <strong>You cannot edit this task until your manager requests a redo.</strong>
                                        </p>
                                    @elseif($submissionTask->status === 'redo_requested')
                                        <h4 class="text-base sm:text-lg font-semibold text-orange-900 mb-2">Redo Requested</h4>
                                        @if($submissionTask->redo_reason)
                                            <p class="text-orange-800 mb-2">
                                                <strong>Redo Reason:</strong> {{ $submissionTask->redo_reason }}
                                            </p>
                                        @endif
                                        <p class="text-orange-700">
                                            Please redo this task with the feedback provided.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($submissionTask->status === 'pending' || $submissionTask->status === 'redo_requested')
                        <!-- Task Completion Form -->
                        <div class="p-4 sm:p-6">

                            @if($task->checklist_items && count($task->checklist_items) > 0)
                                <div class="mb-6 p-4 bg-cyan-50 rounded-xl border border-cyan-200">
                                    <div class="flex flex-col sm:flex-row items-start gap-3">
                                        <div class="flex-shrink-0">
                                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-semibold text-cyan-900 mb-3">Checklist</h4>
                                            <div class="space-y-2">
                                                @foreach($task->checklist_items as $index => $item)
                                                    <label class="flex items-start space-x-3 p-2 rounded-lg hover:bg-cyan-100 cursor-pointer transition-colors">
                                                        <input type="checkbox" 
                                                               data-task-id="{{ $task->id }}"
                                                               data-item-index="{{ $index }}"
                                                               class="checklist-checkbox mt-0.5 w-4 h-4 text-cyan-600 border-2 border-cyan-300 rounded focus:ring-cyan-500 focus:ring-2">
                                                        <span class="text-sm text-cyan-800 flex-1">{{ $item }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            <p class="text-xs text-cyan-600 mt-3 italic">Check off items as you complete them. These are for guidance only.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('employee.submissions.tasks.complete', [$submission, $task]) }}" enctype="multipart/form-data" class="space-y-6" id="task-form-{{ $task->id }}">
                                @csrf
                                
                                <!-- Hidden field for checklist progress -->
                                <input type="hidden" name="checklist_progress" id="checklist-progress-{{ $task->id }}" value="">

                                <!-- Text Proof -->
                                @if(in_array($task->required_proof_type, ['text', 'any']) || $task->required_proof_type === 'none')
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Notes/Comments
                                            @if($task->required_proof_type === 'text') <span class="text-red-500">*</span> @endif
                                        </label>
                                        <textarea name="proof_text" rows="4" class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base" 
                                            placeholder="Add any notes or comments about completing this task..."
                                            {{ $task->required_proof_type === 'text' ? 'required' : '' }}></textarea>
                                    </div>
                                @endif

                                <!-- File/Photo/Video Proof -->
                                @if(in_array($task->required_proof_type, ['photo', 'video', 'file', 'any']))
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Proof Files
                                            @if($task->required_proof_type !== 'any') <span class="text-red-500">*</span> @endif
                                        </label>
                                        
                                        <!-- Camera/Upload Options -->
                                        <div class="mb-4">
                                            <div class="flex flex-col gap-3">
                                                {{-- DESKTOP: losse knoppen voor foto / video / upload --}}
                                                <div class="hidden md:flex md:flex-row md:flex-wrap md:gap-3">
                                                    @if($task->required_proof_type === 'photo' || $task->required_proof_type === 'any')
                                                        <button type="button" 
                                                                class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm"
                                                                onclick="takePhoto('{{ $submissionTask->id }}')">
                                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            </svg>
                                                            Make Photo
                                                        </button>
                                                    @endif
                                                    
                                                    @if($task->required_proof_type === 'video' || $task->required_proof_type === 'any')
                                                        <button type="button" 
                                                                class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm"
                                                                onclick="takeVideo('{{ $submissionTask->id }}')">
                                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                            </svg>
                                                            Make Video
                                                        </button>
                                                    @endif

                                                    <button type="button" 
                                                            class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm"
                                                            onclick="uploadFile('{{ $submissionTask->id }}')">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                        </svg>
                                                        Upload File
                                                    </button>
                                                </div>

                                                {{-- MOBIEL / TABLET: één grote Upload / Camera knop --}}
                                                <button type="button" 
                                                        class="md:hidden w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm"
                                                        onclick="uploadFile('{{ $submissionTask->id }}')">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                    </svg>
                                                    Upload / Camera
                                                </button>
                                            </div>
                                        </div>

                                        
                                        <!-- Hidden file input -->
                                        <input type="file" id="file-input-{{ $submissionTask->id }}" name="proof_files[]" multiple 
                                            class="hidden"
                                            @if($task->required_proof_type === 'photo') accept="image/*" @endif
                                            @if($task->required_proof_type === 'video') accept="video/*" @endif
                                            onchange="handleFileSelect(this, '{{ $submissionTask->id }}')"
                                            {{ in_array($task->required_proof_type, ['photo', 'video', 'file']) ? 'required' : '' }}>
                                        
                                        <!-- Preview area -->
                                        <div id="preview-area-{{ $submissionTask->id }}" class="mt-4 space-y-2">
                                            <!-- Preview items will be added here -->
                                        </div>
                                        
                                        <p class="mt-2 text-xs sm:text-sm text-gray-500">
                                            @if($task->required_proof_type === 'photo')
                                                Take photos or upload images as proof. Max 5MB per file.
                                            @elseif($task->required_proof_type === 'video')
                                                Create videos or upload videos as proof. Max 10MB per file.
                                            @else
                                                Upload files as proof. Max 10MB per file.
                                            @endif
                                        </p>
                                    </div>
                                @endif

                                <!-- Digital Signature for Individual Task -->
                                @if($task->requires_signature)
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Signature <span class="text-red-500">*</span></label>
                                        <div class="mt-2 w-full max-w-full overflow-x-auto">
                                            <canvas id="signature-pad-task-{{ $submissionTask->id }}" class="border border-gray-300 rounded-xl bg-white shadow-sm w-full max-w-sm" width="350" height="120"></canvas>
                                        </div>
                                        <input type="hidden" name="digital_signature" id="signature-input-task-{{ $submissionTask->id }}" required>
                                        <div class="flex flex-col sm:flex-row gap-2 mt-3">
                                            <button type="button" class="w-full sm:w-auto px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-medium transition-colors" onclick="clearSignaturePad('task-{{ $submissionTask->id }}')">Clear Signature</button>
                                        </div>
                                        <p class="mt-2 text-xs sm:text-sm text-gray-500">Draw your signature above. This will be saved as proof of completion.</p>
                                    </div>
                                @endif

                                <div class="flex flex-col sm:flex-row justify-end">
                                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-5 sm:px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 mt-2 sm:mt-0">
                                        @if($task->requires_signature)
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            Sign & Complete
                                        @else
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Mark as Complete
                                        @endif
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Completed Task Display -->
                        <div class="px-4 sm:px-6 py-4 sm:py-6 bg-green-50 border-l-4 border-green-400">
                            <div class="flex flex-col sm:flex-row items-start gap-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 text-sm sm:text-base">
                                    <div class="text-sm sm:text-base font-semibold text-green-900 mb-2">
                                        Completed: {{ $submissionTask->completed_at->format('M j, Y g:i A') }}
                                    </div>
                                    
                                    @if($task->checklist_items && count($task->checklist_items) > 0)
                                        <div class="mb-4">
                                            <strong class="text-sm text-green-800">Checklist Progress:</strong>
                                            <div class="mt-2 bg-white p-3 rounded-lg border border-green-200">
                                                @php
                                                    $checklistProgress = is_array($submissionTask->checklist_progress) ? $submissionTask->checklist_progress : [];
                                                    $completedCount = 0;
                                                @endphp
                                                <div class="space-y-1">
                                                    @foreach($task->checklist_items as $index => $item)
                                                        @php
                                                            $isChecked = isset($checklistProgress[$index]) && $checklistProgress[$index];
                                                            if ($isChecked) $completedCount++;
                                                        @endphp
                                                        <div class="flex items-center space-x-2 text-xs sm:text-sm {{ $isChecked ? 'text-green-700' : 'text-gray-500' }}">
                                                            @if($isChecked)
                                                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            @else
                                                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            @endif
                                                            <span class="{{ $isChecked ? 'font-medium' : 'line-through' }}">{{ $item }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <p class="text-xs text-green-600 mt-2 font-medium">
                                                    {{ $completedCount }}/{{ count($task->checklist_items) }} items completed
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($submissionTask->proof_text)
                                        <div class="mb-4">
                                            <strong class="text-sm text-green-800">Notes:</strong>
                                            <p class="text-sm text-green-700 mt-1 bg-white p-3 rounded-lg border border-green-200">{{ $submissionTask->proof_text }}</p>
                                        </div>
                                    @endif

                                    @if($submissionTask->proof_files && count($submissionTask->proof_files) > 0)
                                        <div>
                                            <strong class="text-sm text-green-800">Uploaded Files:</strong>
                                            <div class="mt-2 space-y-2">
                                                @foreach($submissionTask->proof_files as $file)
                                                    <div class="bg-white p-3 rounded-lg border border-green-200">
                                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-sm text-green-700">
                                                            <div class="flex items-center">
                                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                                </svg>
                                                                {{ $file['original_name'] }} ({{ number_format($file['size'] / 1024, 1) }} KB)
                                                            </div>
                                                        </div>
                                                        @if(isset($file['mime_type']) && strpos($file['mime_type'], 'image/') === 0)
                                                            <div class="mt-2">
                                                                <img src="{{ url('storage/' . $file['path']) }}" alt="{{ $file['original_name'] }}" class="max-w-full sm:max-w-xs max-h-40 rounded shadow border" />
                                                            </div>
                                                        @endif
                                                        @if(isset($file['mime_type']) && strpos($file['mime_type'], 'video/') === 0)
                                                            <div class="mt-2">
                                                                <video controls class="max-w-full sm:max-w-xs max-h-40 rounded shadow border">
                                                                    <source src="{{ url('storage/' . $file['path']) }}" type="{{ $file['mime_type'] }}">
                                                                    Your browser does not support the video tag.
                                                                </video>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Final Submission -->
        @php
            $allRequiredCompleted = $submission->submissionTasks
                ->filter(fn($st) => $st->task->is_required)
                ->every(fn($st) => $st->status === 'completed');
        @endphp

        <!-- Final Submission - Always Visible -->
       {{-- Final Submission - Always Visible --}}
        @if($submission->status === 'in_progress')
            <div class="mt-6 sm:mt-8 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden" id="final-submission-section">
                @if($allRequiredCompleted)
                    {{-- All Required Tasks Completed - Form Enabled --}}
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100 px-4 sm:px-6 py-4 sm:py-6">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 bg-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900 break-words">🎉 Klaar om in te dienen!</h3>
                                <p class="mt-1 text-sm sm:text-base text-gray-600">
                                    Alle verplichte taken zijn voltooid. Je kunt nu de checklist indienen voor review.
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Tasks Still Pending - Form Disabled --}}
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-100 px-4 sm:px-6 py-4 sm:py-6">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 sm:w-10 sm:h-10 bg-amber-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900 break-words">Checklist Indienen</h3>
                                <p class="mt-1 text-sm sm:text-base text-amber-700 font-medium">
                                    Voltooi eerst alle verplichte taken om de checklist in te kunnen dienen.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="px-4 sm:px-6 py-4 sm:py-6">
                    <form method="POST"
                        action="{{ route('employee.submissions.complete', $submission) }}"
                        class="space-y-6"
                        id="final-submission-form">
                        @csrf
                        
                        @if($submission->taskList->requires_signature)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Digitale Handtekening 
                                    <span class="text-red-500">*</span>
                                    @if(!$allRequiredCompleted)
                                        <span class="text-amber-600">(beschikbaar na voltooiing taken)</span>
                                    @endif
                                </label>

                                {{-- Canvas wrapper zodat hij niet uit het scherm loopt --}}
                                <div class="w-full overflow-x-auto">
                                    <div class="inline-block">
                                        <canvas id="signature-pad-final" 
                                                class="border border-gray-300 rounded-xl bg-white mt-1 shadow-sm w-[280px] xs:w-[320px] sm:w-[350px] @if(!$allRequiredCompleted) opacity-50 @endif" 
                                                width="350" 
                                                height="120"
                                                @if(!$allRequiredCompleted) style="pointer-events: none;" @endif></canvas>
                                    </div>
                                </div>

                                <input type="hidden" name="employee_signature" id="signature-input-final" @if($allRequiredCompleted) required @endif>

                                <div class="mt-3 flex flex-col sm:flex-row gap-2">
                                    <button type="button" 
                                            class="w-full sm:w-auto px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-medium transition-colors @if(!$allRequiredCompleted) opacity-50 cursor-not-allowed @endif" 
                                            onclick="clearSignaturePadFinal()"
                                            @if(!$allRequiredCompleted) disabled @endif>
                                        Handtekening Wissen
                                    </button>
                                </div>

                                <p class="mt-2 text-xs sm:text-sm text-gray-500">
                                    @if($allRequiredCompleted)
                                        Teken je handtekening hierboven. Deze wordt opgeslagen als bewijs van voltooiing.
                                    @else
                                        Handtekening wordt beschikbaar nadat alle verplichte taken zijn voltooid.
                                    @endif
                                </p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Aanvullende Opmerkingen (Optioneel)
                                @if(!$allRequiredCompleted)
                                    <span class="text-amber-600">(beschikbaar na voltooiing taken)</span>
                                @endif
                            </label>
                            <textarea name="notes" 
                                    rows="4" 
                                    class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base @if(!$allRequiredCompleted) opacity-50 bg-gray-50 @endif" 
                                    placeholder="@if($allRequiredCompleted)Eventuele aanvullende opmerkingen over deze checklist...@else Dit veld wordt beschikbaar nadat alle verplichte taken zijn voltooid.@endif"
                                    @if(!$allRequiredCompleted) disabled @endif></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-end">
                            <a href="{{ route('employee.dashboard') }}" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-5 sm:px-6 py-3 border border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Opslaan & Later Verder
                            </a>
                            
                            @if($allRequiredCompleted)
                                <button type="submit" 
                                        class="w-full sm:w-auto inline-flex items-center justify-center px-7 sm:px-8 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                                        id="submit-checklist-btn">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Checklist Indienen
                                </button>
                            @else
                                <div class="w-full sm:w-auto inline-flex items-center justify-center px-7 sm:px-8 py-3 border border-transparent text-sm font-semibold rounded-xl text-gray-400 bg-gray-200 cursor-not-allowed"
                                    title="Voltooi eerst alle verplichte taken">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Checklist Indienen (Vergrendeld)
                                </div>
                                <p class="text-xs sm:text-sm text-amber-600 text-center mt-2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                    Voltooi alle verplichte taken om in te kunnen dienen
                                </p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        @elseif(!$allRequiredCompleted)
            <div class="mt-6 sm:mt-8 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-amber-50 border-l-4 border-amber-400 px-4 sm:px-6 py-4 sm:py-6">
                    <div class="flex flex-col sm:flex-row items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base sm:text-lg font-semibold text-amber-900 mb-2 break-words">Complete Required Tasks</h3>
                            <p class="text-sm sm:text-base text-amber-800">
                                Please complete all required tasks before submitting this checklist.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
<!-- SignaturePad lib (één keer laden voor alle handtekeningen) -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<!-- CRITICAL CAMERA & UPLOAD FUNCTIONS (single source of truth) -->
<script>
// === GLOBAL CAMERA / UPLOAD API ===

function takePhoto(taskId) {
    navigator.mediaDevices.getUserMedia({ video: true, audio: false })
        .then(stream => createCameraModal('photo', taskId, stream))
        .catch(err => alert('Camera toegang mislukt: ' + err.message));
}

function takeVideo(taskId) {
    navigator.mediaDevices.getUserMedia({ video: true, audio: true })
        .then(stream => createCameraModal('video', taskId, stream))
        .catch(err => alert('Camera toegang mislukt: ' + err.message));
}

function uploadFile(taskId) {
    const fileInput = document.getElementById('file-input-' + taskId);
    if (fileInput) fileInput.click();
}

function createCameraModal(type, taskId, stream) {
    const existing = document.getElementById('camera-modal');
    if (existing) existing.remove();

    const modal = document.createElement('div');
    modal.id = 'camera-modal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-[9999] px-3';

    const isVideo = type === 'video';
    const startText = isVideo ? 'Start Opname' : 'Maak Foto';

    modal.innerHTML = `
        <div class="bg-white rounded-xl p-4 sm:p-6 w-full max-w-[520px]">
            <h3 class="text-base sm:text-lg font-semibold mb-3 text-gray-900">${isVideo ? 'Video opnemen' : 'Foto maken'}</h3>
            <video id="cam-prev-${taskId}" autoplay playsinline muted class="w-full h-56 sm:h-64 bg-black rounded-lg"></video>
            <div id="rec-status-${taskId}" class="hidden mt-2 text-red-600 font-semibold text-sm">🔴 Opname bezig...</div>
            <div class="flex flex-col sm:flex-row gap-2 justify-end mt-4">
                <button id="cap-btn-${taskId}" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm sm:text-base">${startText}</button>
                <button id="stop-btn-${taskId}" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-amber-600 text-white hover:bg-amber-700 hidden text-sm sm:text-base">Stop Opname</button>
                <button id="close-btn-${taskId}" class="w-full sm:w-auto px-4 py-2 rounded-lg bg-gray-600 text-white hover:bg-gray-700 text-sm sm:text-base">Sluiten</button>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    const video = document.getElementById(`cam-prev-${taskId}`);
    video.srcObject = stream;

    let mediaRecorder = null;
    let chunks = [];

    if (isVideo) {
        try {
            mediaRecorder = new MediaRecorder(stream);
            mediaRecorder.ondataavailable = (e) => { if (e.data.size > 0) chunks.push(e.data); };
            mediaRecorder.onstop = () => {
                const blob = new Blob(chunks, { type: 'video/webm' });
                chunks = [];
                addMediaToTask(blob, `video_${Date.now()}.webm`, taskId, 'video');
                closeCamera(taskId);
            };
        } catch (e) {
            alert('Video opnemen wordt niet ondersteund in deze browser.');
            closeCamera(taskId);
            return;
        }
    }

    document.getElementById(`cap-btn-${taskId}`).addEventListener('click', () => {
        if (type === 'photo') {
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0);
            canvas.toBlob((blob) => {
                addMediaToTask(blob, `photo_${Date.now()}.jpg`, taskId, 'image');
                closeCamera(taskId);
            }, 'image/jpeg', 0.9);
        } else {
            mediaRecorder.start();
            document.getElementById(`cap-btn-${taskId}`).classList.add('hidden');
            document.getElementById(`stop-btn-${taskId}`).classList.remove('hidden');
            document.getElementById(`rec-status-${taskId}`).classList.remove('hidden');
        }
    });

    if (isVideo) {
        document.getElementById(`stop-btn-${taskId}`).addEventListener('click', () => {
            if (mediaRecorder && mediaRecorder.state === 'recording') mediaRecorder.stop();
        });
    }

    document.getElementById(`close-btn-${taskId}`).addEventListener('click', () => closeCamera(taskId));
    modal.addEventListener('click', (e) => { if (e.target === modal) closeCamera(taskId); });

    window['__cam_stream_' + taskId] = stream;
}

function closeCamera(taskId) {
    const stream = window['__cam_stream_' + taskId];
    if (stream) {
        stream.getTracks().forEach(t => t.stop());
        delete window['__cam_stream_' + taskId];
    }
    const modal = document.getElementById('camera-modal');
    if (modal) modal.remove();
}

function addMediaToTask(blob, filename, taskId, mediaType) {
    const fileInput = document.getElementById('file-input-' + taskId);
    if (!fileInput) {
        alert('File input niet gevonden voor task ' + taskId);
        return;
    }

    const file = new File([blob], filename, {
        type: blob.type || (mediaType === 'image' ? 'image/jpeg' : 'video/webm')
    });

    const dt = new DataTransfer();
    for (let i = 0; i < fileInput.files.length; i++) dt.items.add(fileInput.files[i]);
    dt.items.add(file);
    fileInput.files = dt.files;

    updateMediaPreview(taskId, file);
}

function handleFileSelect(input, taskId) {
    const previewArea = document.getElementById('preview-area-' + taskId);
    if (!previewArea) return;
    previewArea.innerHTML = '';

    Array.from(input.files).forEach(file => updateMediaPreview(taskId, file));
}

function updateMediaPreview(taskId, file) {
    const previewArea = document.getElementById('preview-area-' + taskId);
    if (!previewArea) return;

    const url = URL.createObjectURL(file);
    const isImg = file.type.startsWith('image/');
    const isVid = file.type.startsWith('video/');

    const row = document.createElement('div');
    row.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border flex-col sm:flex-row gap-3 sm:gap-0';

    let mediaHtml = `
        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
    `;

    if (isImg) {
        mediaHtml = `<img src="${url}" alt="Preview" class="w-16 h-16 object-cover rounded-lg">`;
    } else if (isVid) {
        mediaHtml = `<video src="${url}" class="w-16 h-16 object-cover rounded-lg" muted></video>`;
    }

    row.innerHTML = `
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            ${mediaHtml}
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
                <p class="text-xs text-gray-500">${Math.round(file.size/1024)} KB • ${isImg ? 'Foto' : (isVid ? 'Video' : 'Bestand')}</p>
            </div>
        </div>
        <button type="button" class="text-red-600 hover:text-red-800 self-end sm:self-center" onclick="removePreviewItem(this, '${taskId}', '${file.name}')">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    previewArea.appendChild(row);
}

function removePreviewItem(btn, taskId, fileName) {
    const row = btn.closest('div');
    if (row) row.remove();

    const fileInput = document.getElementById('file-input-' + taskId);
    if (!fileInput) return;

    const dt = new DataTransfer();
    Array.from(fileInput.files).forEach(f => {
        if (f.name !== fileName) dt.items.add(f);
    });
    fileInput.files = dt.files;
}
</script>

<script>
// Custom error class
class ValidationError extends Error {
    constructor(message, errors = {}) {
        super(message);
        this.name = 'ValidationError';
        this.errors = errors;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('checklist-js v4 loaded');

    if (!window.signaturePads) window.signaturePads = {};

    // Init per-task signature pads
    document.querySelectorAll('canvas[id^="signature-pad-task-"]').forEach(canvas => {
        if (typeof SignaturePad === 'undefined') return;
        const key = 'task-' + canvas.id.replace('signature-pad-task-', '');
        const pad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255,255,255,0)',
            penColor: 'rgb(0, 0, 0)',
            minWidth: 1,
            maxWidth: 3
        });
        window.signaturePads[key] = pad;
    });

    // Final signature pad (indien aanwezig)
    setupFinalSignaturePad();

    // Checklist persistence & forms
    initializeChecklists();
    setTimeout(updateFinalSubmissionForm, 500);

    // CSRF meta fallback
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }

    // Animatie op cards
    const cards = document.querySelectorAll('.task-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200 + 300);
    });

    // Progress circle init
    const progressCircle = document.querySelector('circle[stroke="#3b82f6"]');
    if (progressCircle) {
        const circumference = 2 * Math.PI * 40;
        const progressPercent = {{ $progressPercent }};
        const offset = circumference - (progressPercent / 100) * circumference;
        progressCircle.style.strokeDasharray = circumference;
        progressCircle.style.strokeDashoffset = circumference;
        setTimeout(() => {
            progressCircle.style.strokeDashoffset = offset;
        }, 500);
    }

    // Ripple-effect op buttons
    function createRipple(event) {
        const button = event.currentTarget;
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');
        button.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    }
    document.querySelectorAll('button[type="submit"], a[href*="dashboard"]').forEach(btn => {
        btn.addEventListener('click', createRipple);
    });

    // Klein touch-effect op mobile
    document.addEventListener('touchstart', function(e) {
        const t = e.target.closest('button, a');
        if (t) t.style.transform = 'scale(0.98)';
    });
    document.addEventListener('touchend', function(e) {
        const t = e.target.closest('button, a');
        if (t) setTimeout(() => { t.style.transform = ''; }, 150);
    });

    // AJAX final form (indien al actief)
    initializeFinalSubmissionAjax();

    console.log('Helpers ready. Cards:', document.querySelectorAll('.task-card').length);
});

// ---- Signature helpers ----
function clearSignaturePad(key) {
    if (window.signaturePads && window.signaturePads[key]) {
        window.signaturePads[key].clear();
    }
}

function setupFinalSignaturePad() {
    const canvasFinal = document.getElementById('signature-pad-final');
    if (!canvasFinal || typeof SignaturePad === 'undefined') return;
    if (window.signaturePadFinal) return;

    window.signaturePadFinal = new SignaturePad(canvasFinal, {
        backgroundColor: 'rgba(255,255,255,0)',
        penColor: 'rgb(0, 0, 0)',
        minWidth: 1,
        maxWidth: 3
    });
}

function clearSignaturePadFinal() {
    if (window.signaturePadFinal) {
        window.signaturePadFinal.clear();
    }
}

// ===== Helpers & AJAX for forms =====

function validateTaskForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        field.classList.remove('border-red-500', 'border-red-300');

        if (field.type === 'file') {
            if (field.files.length === 0) {
                field.classList.add('border-red-500');
                showNotification('Bewijs is vereist voor deze taak.', 'error');
                isValid = false;
            }
        } else if ((field.type === 'checkbox' || field.type === 'radio') &&
                   !form.querySelector(`input[name="${field.name}"]:checked`)) {
            showNotification('Dit veld is verplicht.', 'error');
            isValid = false;
        } else if (!field.value.trim()) {
            field.classList.add('border-red-500');
            isValid = false;
        }
    });

    const signatureCanvas = form.querySelector('canvas[id^="signature-pad-task-"]');
    if (signatureCanvas) {
        const key = 'task-' + signatureCanvas.id.replace('signature-pad-task-', '');
        if (window.signaturePads && window.signaturePads[key] && window.signaturePads[key].isEmpty()) {
            showNotification('Een digitale handtekening is vereist voor deze taak.', 'error');
            isValid = false;
        } else if (window.signaturePads && window.signaturePads[key]) {
            const hidden = form.querySelector('input[name="digital_signature"]');
            if (hidden) hidden.value = window.signaturePads[key].toDataURL();
        }
    }

    if (!isValid) {
        showNotification('Alle verplichte velden moeten ingevuld worden.', 'error');
    }

    return isValid;
}

function showLoadingOverlay() {
    hideLoadingOverlay();
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4';
    overlay.innerHTML = `
        <div class="bg-white rounded-lg p-6 sm:p-8 flex flex-col items-center space-y-4 max-w-sm w-full">
            <svg class="animate-spin h-10 w-10 sm:h-12 sm:w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <div class="text-center">
                <p class="text-base sm:text-lg font-semibold text-gray-900">Bezig met verwerken...</p>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Even geduld alsjeblieft</p>
            </div>
        </div>
    `;
    document.body.appendChild(overlay);
}

function hideLoadingOverlay() {
    const overlay = document.querySelector('.loading-overlay');
    if (overlay) overlay.remove();
}

function elementContainsText(element, selector, text) {
    const els = element.querySelectorAll(selector);
    return Array.from(els).some(el => el.textContent.includes(text));
}

function countCompletedRequiredTasks() {
    let completedRequired = 0;
    document.querySelectorAll('.task-card').forEach(card => {
        const isRequired = card.dataset.required === '1';
        const status = card.dataset.status;
        if (isRequired && status === 'completed') completedRequired++;
    });
    return completedRequired;
}

function countTotalRequiredTasks() {
    let totalRequired = 0;
    document.querySelectorAll('.task-card').forEach(card => {
        if (card.dataset.required === '1') totalRequired++;
    });
    return totalRequired;
}

// ✅ Geen scroll naar beneden meer
function updateFinalSubmissionForm() {
    try {
        const completedRequiredTasks = countCompletedRequiredTasks();
        const totalRequiredTasks = countTotalRequiredTasks();
        const finalSection = document.getElementById('final-submission-section');
        if (!finalSection) return;

        if (completedRequiredTasks >= totalRequiredTasks && totalRequiredTasks > 0) {
            enableFinalSubmissionForm();
            showNotification('🎉 Alle verplichte taken zijn voltooid! Je kunt nu de checklist indienen.', 'success', 5000);
        } else {
            updateToDisabledState();
        }
    } catch (e) {
        console.error('updateFinalSubmissionForm error:', e);
    }
}

function updateToDisabledState() {
    const finalSection = document.getElementById('final-submission-section');
    if (!finalSection) return;

    const header = finalSection.querySelector('.bg-gradient-to-r');
    if (header) {
        header.className = 'bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-100 px-4 sm:px-6 py-4 sm:py-6';
        
        const icon = header.querySelector('.w-9.h-9, .w-10.h-10');
        if (icon) {
            icon.className = 'w-9 h-9 sm:w-10 sm:h-10 bg-amber-500 rounded-xl flex items-center justify-center mr-0';
            icon.innerHTML = `
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            `;
        }
        
        const title = header.querySelector('h3');
        if (title) title.textContent = 'Checklist Indienen';
        
        const description = header.querySelector('p');
        if (description) {
            description.textContent = 'Voltooi eerst alle verplichte taken om de checklist in te kunnen dienen.';
            description.className = 'text-sm sm:text-base text-amber-700 font-medium';
        }
    }
}

function enableFinalSubmissionForm() {
    const finalSection = document.getElementById('final-submission-section');
    if (!finalSection) return;

    const header = finalSection.querySelector('.bg-gradient-to-r');
    if (header) {
        header.className = 'bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100 px-4 sm:px-6 py-4 sm:py-6';
        const icon = header.querySelector('.w-9.h-9, .w-10.h-10');
        if (icon) {
            icon.className = 'w-9 h-9 sm:w-10 sm:h-10 bg-green-600 rounded-xl flex items-center justify-center mr-0';
            icon.innerHTML = `
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            `;
        }
        const title = header.querySelector('h3');
        if (title) title.textContent = '🎉 Klaar om in te dienen!';
        const description = header.querySelector('p');
        if (description) {
            description.textContent = 'Alle verplichte taken zijn voltooid. Je kunt nu de checklist indienen voor review.';
            description.className = 'text-sm sm:text-base text-gray-600';
        }
    }

    const sigPad = finalSection.querySelector('#signature-pad-final');
    if (sigPad) {
        sigPad.classList.remove('opacity-50');
        sigPad.style.pointerEvents = 'auto';
        const sigInput = finalSection.querySelector('#signature-input-final');
        if (sigInput) sigInput.required = true;
        const help = sigPad.parentElement.parentElement.querySelector('p.text-xs, p.text-sm');
        if (help) help.textContent = 'Teken je handtekening hierboven. Deze wordt opgeslagen als bewijs van voltooiing.';
        const label = sigPad.parentElement.parentElement.querySelector('label');
        if (label) label.innerHTML = 'Digitale Handtekening <span class="text-red-500">*</span>';
    }

    const clearBtn = finalSection.querySelector('button[onclick="clearSignaturePadFinal()"]');
    if (clearBtn) {
        clearBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        clearBtn.disabled = false;
    }

    const notes = finalSection.querySelector('textarea[name="notes"]');
    if (notes) {
        notes.classList.remove('opacity-50', 'bg-gray-50');
        notes.disabled = false;
        notes.placeholder = 'Eventuele aanvullende opmerkingen over deze checklist...';
        const label = notes.parentElement.querySelector('label');
        if (label) label.textContent = 'Aanvullende Opmerkingen (Optioneel)';
    }

    const disabledBtn = finalSection.querySelector('.bg-gray-200');
    const warn = finalSection.querySelector('.text-amber-600.text-center');
    if (disabledBtn && warn) {
        const container = disabledBtn.parentElement;
        disabledBtn.remove();
        warn.remove();

        const enabledButton = document.createElement('button');
        enabledButton.type = 'submit';
        enabledButton.id = 'submit-checklist-btn';
        enabledButton.className = 'w-full sm:w-auto inline-flex items-center justify-center px-7 sm:px-8 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105';
        enabledButton.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Checklist Indienen
        `;
        container.appendChild(enabledButton);
    }

    setupFinalSignaturePad();
    initializeFinalSubmissionAjax();
}

function initializeFinalSubmissionAjax() {
    const form = document.querySelector('#final-submission-form');
    if (!form || form.dataset.ajaxBound === '1') return;

    form.dataset.ajaxBound = '1';

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const sigInput = form.querySelector('#signature-input-final');
        const sigCanvas = document.getElementById('signature-pad-final');
        if (sigInput && sigCanvas) {
            if (!window.signaturePadFinal || window.signaturePadFinal.isEmpty()) {
                showNotification('Een digitale handtekening is vereist om de checklist in te dienen.', 'error');
                return;
            }
            sigInput.value = window.signaturePadFinal.toDataURL();
        }

        const requiredFields = form.querySelectorAll('[required]');
        let ok = true;
        requiredFields.forEach(field => {
            field.classList.remove('border-red-500', 'border-red-300');
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                ok = false;
            }
        });
        if (!ok) {
            showNotification('Alle verplichte velden moeten ingevuld worden.', 'error');
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');
        const original = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Indienen...
        `;
        showLoadingOverlay();

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(r => {
            if (r.ok) return r.json();
            if (r.status === 422) return r.json().then(d => { throw new ValidationError(d.message || 'Validation failed', d.errors); });
            if (r.status === 403) throw new Error('Toegang geweigerd. Ververs de pagina en probeer opnieuw.');
            if (r.status === 500) throw new Error('Server fout opgetreden. Probeer het over een moment opnieuw.');
            throw new Error(`Verzoek gefaald met status ${r.status}`);
        })
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Onbekende fout opgetreden');

            try {
                localStorage.setItem('completed_list_{{ $submission->taskList->id }}', Date.now().toString());
            } catch (e) {
                console.warn('Kon localStorage niet schrijven:', e);
            }
            
            showNotification('Checklist succesvol ingediend!', 'success');
            hideLoadingOverlay();
            setTimeout(() => {
                window.location.href = data.redirect_url || '/employee/dashboard';
            }, 1500);
        })
        .catch(err => {
            let msg = 'Fout bij het indienen van checklist. Probeer opnieuw.';
            if (err instanceof ValidationError) {
                msg = err.message;
                if (err.errors && Object.keys(err.errors).length > 0) {
                    const first = Object.values(err.errors)[0];
                    if (Array.isArray(first) && first.length > 0) msg = first[0];
                }
            } else if (err.message) msg = err.message;
            showNotification(msg, 'error');
            hideLoadingOverlay();
            submitButton.disabled = false;
            submitButton.innerHTML = original;
        });
    });
}

// ✅ Belangrijk: hier vervangen we altijd gewoon het formulierblok → direct visuele update
function updateTaskToCompleted(taskId, completedAt) {
    console.log('updateTaskToCompleted CALLED for task:', taskId, completedAt);

    const form = document.querySelector(`#task-form-${taskId}`);
    const taskCard = form ? form.closest('.task-card') : null;

    if (taskCard) {
        taskCard.dataset.status = 'completed';
    }

    // Header optioneel stylen
    if (taskCard) {
        const taskHeader = taskCard.querySelector('.bg-gradient-to-r');
        if (taskHeader) {
            const headerIconDiv = taskHeader.querySelector('.w-9.h-9, .w-10.h-10');
            if (headerIconDiv) {
                headerIconDiv.className = 'w-9 h-9 sm:w-10 sm:h-10 bg-green-500 rounded-xl flex items-center justify-center';
                headerIconDiv.innerHTML = `
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
            }

            const titleEl = taskHeader.querySelector('h3');
            if (titleEl) titleEl.className = 'text-lg sm:text-xl font-bold text-green-900';

            const descEl = taskHeader.querySelector('p.text-sm, p.text-sm.sm\\:text-base');
            if (descEl) descEl.className = 'text-sm sm:text-base text-green-700';

            const badge = taskHeader.querySelector('.flex.flex-wrap.gap-2');
            if (badge && !badge.querySelector('.bg-green-100')) {
                badge.innerHTML += `
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Completed
                    </span>
                `;
            }
        }
    }

    // Formulierblok vervangen door "Task completed"
    if (form && form.parentElement) {
        const container = form.parentElement;
        container.innerHTML = `
            <div class="bg-green-50 border-l-4 border-green-400 px-4 sm:px-6 py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 text-sm sm:text-base">
                        <div class="text-sm sm:text-base font-semibold text-green-900 mb-2">
                            ✅ Task completed successfully
                        </div>
                        <div class="text-sm sm:text-base font-semibold text-green-900 mb-2">
                            Completed: ${new Date(completedAt).toLocaleDateString('nl-NL', { 
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric',
                                hour: 'numeric',
                                minute: '2-digit'
                            })}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    if (taskCard) {
        taskCard.style.opacity = '0';
        setTimeout(() => {
            taskCard.style.opacity = '1';
            taskCard.style.transition = 'opacity 0.5s ease-in-out';
        }, 100);
    }
}

function updateProgressIndicator() {
    try {
        const cards = document.querySelectorAll('.task-card');
        let completed = 0;
        cards.forEach(card => {
            if (card.dataset.status === 'completed') completed++;
        });
        const total = cards.length;
        const percent = total > 0 ? Math.round((completed / total) * 100) : 0;

        const progressCircle = document.querySelector('circle[stroke="#3b82f6"]');
        const progressText = document.querySelector('.text-base.sm\\:text-lg.font-bold');
        const progressCount = document.querySelector('.text-xs.sm\\:text-sm.text-gray-500');

        if (progressCircle && progressText && progressCount) {
            const circumference = 2 * Math.PI * 40;
            const offset = circumference * (1 - (percent / 100));
            progressCircle.style.strokeDashoffset = offset;
            progressText.textContent = percent + '%';
            progressCount.textContent = `${completed}/${total} tasks`;
        }
    } catch (e) {
        console.error('updateProgressIndicator error:', e);
    }
}

function initializeChecklists() {
    const checklistCheckboxes = document.querySelectorAll('.checklist-checkbox');
    const submissionId = '{{ $submission->id }}';

    checklistCheckboxes.forEach((checkbox) => {
        const taskId = checkbox.dataset.taskId;
        const key = `checklist_${submissionId}_${taskId}`;
        const idx = parseInt(checkbox.dataset.itemIndex);

        const saved = localStorage.getItem(key);
        if (saved) {
            const state = JSON.parse(saved);
            if (state[idx]) checkbox.checked = true;
        }
        checkbox.addEventListener('change', function() {
            const all = document.querySelectorAll(`.checklist-checkbox[data-task-id="${taskId}"]`);
            const state = {};
            all.forEach(cb => state[parseInt(cb.dataset.itemIndex)] = cb.checked);
            localStorage.setItem(key, JSON.stringify(state));
        });
    });

    document.querySelectorAll('form[id^="task-form-"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!validateTaskForm(form)) return;

            const taskId = form.id.replace('task-form-', '');
            const key = `checklist_${submissionId}_${taskId}`;
            const saved = localStorage.getItem(key);
            if (saved) {
                const progressInput = document.getElementById(`checklist-progress-${taskId}`);
                if (progressInput) progressInput.value = saved;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            const original = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Bezig...
            `;
            showLoadingOverlay();

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            })
            .then(r => {
                if (r.ok) return r.json();
                if (r.status === 422) return r.json().then(d => { throw new ValidationError(d.message || 'Validation failed', d.errors); });
                if (r.status === 403) throw new Error('Access denied. Please refresh the page and try again.');
                if (r.status === 500) throw new Error('Server error occurred. Please try again in a moment.');
                throw new Error(`Request failed with status ${r.status}`);
            })
            .then(data => {
                if (!data.success) throw new Error(data.message || 'Onbekende fout opgetreden');

                updateTaskToCompleted(taskId, data.completed_at);
                localStorage.removeItem(key);
                updateProgressIndicator();
                showNotification('Taak succesvol afgerond!', 'success');
                updateFinalSubmissionForm();
                hideLoadingOverlay();

                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = original;
                }
            })
            .catch(err => {
                let msg = 'Fout bij het afronden van taak. Probeer opnieuw.';
                if (err instanceof ValidationError) {
                    msg = err.message;
                    if (err.errors && Object.keys(err.errors).length > 0) {
                        const first = Object.values(err.errors)[0];
                        if (Array.isArray(first) && first.length > 0) msg = first[0];
                    }
                } else if (err.message) msg = err.message;
                showNotification(msg, 'error');
                hideLoadingOverlay();
                submitBtn.disabled = false;
                submitBtn.innerHTML = original;
            });
        });
    });
}

function showNotification(message, type = 'success', duration = 3000) {
    const notification = document.createElement('div');
    const typeClasses = {
        success: 'bg-green-500 text-white border-green-600',
        error: 'bg-red-500 text-white border-red-600',
        warning: 'bg-amber-500 text-white border-amber-600',
        info: 'bg-blue-500 text-white border-blue-600'
    };
    const icons = {
        success: '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        error: '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        warning: '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>',
        info: '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    };

    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg border-2 ${typeClasses[type] || typeClasses.info} transform translate-x-full transition-transform duration-300 max-w-md w-[calc(100%-2rem)] sm:w-auto`;
    notification.innerHTML = `
        <div class="flex items-center">
            ${icons[type] || icons.info}
            <span class="flex-1 text-sm sm:text-base">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(notification);
    setTimeout(() => { notification.style.transform = 'translateX(0)'; }, 50);
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 320);
    }, duration);
}

function toggleInstructions(e, taskId) {
    const instructionsElement = document.getElementById('instructions-' + taskId);
    const button = e.currentTarget;
    
    if (!instructionsElement || !button) return;

    if (instructionsElement.classList.contains('hidden')) {
        instructionsElement.classList.remove('hidden');
        instructionsElement.style.maxHeight = '0px';
        instructionsElement.style.overflow = 'hidden';
        instructionsElement.style.transition = 'max-height 0.3s ease-out';

        setTimeout(() => {
            instructionsElement.style.maxHeight = instructionsElement.scrollHeight + 'px';
        }, 10);

        button.classList.add('bg-blue-100');
        button.title = 'Verberg instructies';

        const icon = button.querySelector('svg');
        if (icon) {
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
            `;
        }
    } else {
        instructionsElement.style.maxHeight = '0px';
        setTimeout(() => {
            instructionsElement.classList.add('hidden');
            instructionsElement.style.maxHeight = '';
            instructionsElement.style.overflow = '';
            instructionsElement.style.transition = '';
        }, 300);

        button.classList.remove('bg-blue-100');
        button.title = 'Klik voor gedetailleerde instructies';

        const icon = button.querySelector('svg');
        if (icon) {
            icon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M13 16h-1v-4h-1m1-4h.01
                         M21 12a9 9 0 11-18 0 
                         9 9 0 0118 0z"></path>
            `;
        }
    }
}
</script>

<style>
.ripple {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: scale(0);
    animation: ripple-animation 0.6s linear;
    pointer-events: none;
}
@keyframes ripple-animation {
    to { transform: scale(4); opacity: 0; }
}

.task-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.task-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

button, a[role="button"], a[href*="dashboard"] {
    position: relative;
    overflow: hidden;
}

circle[stroke="#3b82f6"] { transition: stroke-dashoffset 1s ease-in-out; }

input:focus, textarea:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
@endsection

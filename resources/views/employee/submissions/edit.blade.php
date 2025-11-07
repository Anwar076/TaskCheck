@extends('layouts.employee')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Clean Header Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $submission->taskList->title }}</h1>
                            <p class="text-gray-600 font-medium">Started {{ $submission->started_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Indicator -->
                <div class="flex flex-col items-center lg:items-end">
                    @php
                        $completedTasks = $submission->submissionTasks->where('status', 'completed')->count();
                        $totalTasks = $submission->submissionTasks->count();
                        $progressPercent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                    @endphp
                    <div class="w-20 h-20 relative">
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="40" stroke="#e5e7eb" stroke-width="6" fill="none"/>
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
                            <div class="text-lg font-bold text-gray-900">{{ $progressPercent }}%</div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">{{ $completedTasks }}/{{ $totalTasks }} tasks</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Tasks -->
        <div class="space-y-6">
            @foreach($submission->submissionTasks as $index => $submissionTask)
                @php $task = $submissionTask->task; @endphp
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden task-card">
                    <!-- Task Header -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-4">
                                    @if($submissionTask->status === 'completed')
                                        <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                            <span class="text-sm font-bold text-blue-700">{{ $index + 1 }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold {{ $submissionTask->status === 'completed' ? 'text-green-900' : 'text-gray-900' }} mb-2">
                                        {{ $task->title }}
                                    </h3>
                                    @if($task->description)
                                        <p class="text-sm {{ $submissionTask->status === 'completed' ? 'text-green-700' : 'text-gray-600' }}">
                                            {{ $task->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
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
                                @elseif($submissionTask->redo_requested)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Redo Required
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($submissionTask->status === 'rejected' || $submissionTask->redo_requested)
                        <!-- Rejection/Redo Information -->
                        <div class="bg-red-50 border-l-4 border-red-400 p-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    @if($submissionTask->status === 'rejected')
                                        <h4 class="text-lg font-semibold text-red-900 mb-2">Task Rejected</h4>
                                        @if($submissionTask->rejection_reason)
                                            <p class="text-red-800 mb-2">
                                                <strong>Reason:</strong> {{ $submissionTask->rejection_reason }}
                                            </p>
                                        @endif
                                        <p class="text-red-700">
                                            This task was rejected on {{ $submissionTask->rejected_at->format('M j, Y g:i A') }}. 
                                            Please review the feedback and complete the task again.
                                        </p>
                                    @elseif($submissionTask->redo_requested)
                                        <h4 class="text-lg font-semibold text-amber-900 mb-2">Redo Requested</h4>
                                        <p class="text-amber-800">
                                            Please redo this task. Review the instructions and complete it again.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($submissionTask->status === 'pending' || $submissionTask->status === 'rejected' || $submissionTask->redo_requested)
                        <!-- Task Completion Form -->
                        <div class="p-6">
                            @if($task->instructions)
                                <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-blue-900 mb-2">Instructions</h4>
                                            <p class="text-sm text-blue-700 whitespace-pre-line">{{ $task->instructions }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($task->checklist_items && count($task->checklist_items) > 0)
                                <div class="mb-6 p-4 bg-cyan-50 rounded-xl border border-cyan-200">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-3">
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
                                                               class="checklist-checkbox mt-0.5 w-5 h-5 text-cyan-600 border-2 border-cyan-300 rounded focus:ring-cyan-500 focus:ring-2">
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
                                        <textarea name="proof_text" rows="4" class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-3" 
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
                                            <div class="flex flex-wrap gap-3">
                                                @if($task->required_proof_type === 'photo' || $task->required_proof_type === 'any')
                                                    <button type="button" 
                                                            class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
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
                                                            class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                                                            onclick="takeVideo('{{ $submissionTask->id }}')">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                        Make Video
                                                    </button>
                                                @endif
                                                
                                                <button type="button" 
                                                        class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                                                        onclick="uploadFile('{{ $submissionTask->id }}')">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                    </svg>
                                                    Upload File
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
                                        
                                        <p class="mt-2 text-sm text-gray-500">
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
                                        <div class="mt-2">
                                            <canvas id="signature-pad-task-{{ $submissionTask->id }}" class="border border-gray-300 rounded-xl bg-white shadow-sm" width="350" height="120"></canvas>
                                            <input type="hidden" name="digital_signature" id="signature-input-task-{{ $submissionTask->id }}" required>
                                            <div class="flex space-x-2 mt-3">
                                                <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-medium transition-colors" onclick="clearSignaturePad('task-{{ $submissionTask->id }}')">Clear Signature</button>
                                            </div>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500">Draw your signature above. This will be saved as proof of completion.</p>
                                    </div>
                                <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
                                <script>
                                    // Initialize signature pad when page loads
                                    document.addEventListener('DOMContentLoaded', function() {
                                        if (!window.signaturePads) window.signaturePads = {};
                                        
                                        var canvas = document.getElementById('signature-pad-task-{{ $submissionTask->id }}');
                                        if (canvas && typeof SignaturePad !== 'undefined') {
                                            var signaturePad = new SignaturePad(canvas, { 
                                                backgroundColor: 'rgba(255,255,255,0)',
                                                penColor: 'rgb(0, 0, 0)',
                                                minWidth: 1,
                                                maxWidth: 3
                                            });
                                            window.signaturePads['task-{{ $submissionTask->id }}'] = signaturePad;
                                            
                                            // Handle form submission
                                            var form = canvas.closest('form');
                                            if (form) {
                                                form.addEventListener('submit', function(e) {
                                                    if (signaturePad.isEmpty()) {
                                                        alert('Please provide a signature.');
                                                        e.preventDefault();
                                                        return false;
                                                    }
                                                    document.getElementById('signature-input-task-{{ $submissionTask->id }}').value = signaturePad.toDataURL();
                                                });
                                            }
                                        } else {
                                            console.error('SignaturePad not loaded or canvas not found');
                                        }
                                    });
                                    
                                    // Clear signature function
                                    window.clearSignaturePad = function(key) {
                                        if (window.signaturePads && window.signaturePads[key]) {
                                            window.signaturePads[key].clear();
                                        }
                                    }
                                </script>
                            @endif

                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
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
                        <div class="p-6 bg-green-50 border-l-4 border-green-400">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-3">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-semibold text-green-900 mb-2">
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
                                                        <div class="flex items-center space-x-2 text-sm {{ $isChecked ? 'text-green-700' : 'text-gray-500' }}">
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
                                                        <div class="flex items-center text-sm text-green-700">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                            </svg>
                                                            {{ $file['original_name'] }} ({{ number_format($file['size'] / 1024, 1) }} KB)
                                                        </div>
                                                        @if(isset($file['mime_type']) && strpos($file['mime_type'], 'image/') === 0)
                                                            <div class="mt-2">
                                                                <img src="{{ url('storage/' . $file['path']) }}" alt="{{ $file['original_name'] }}" class="max-w-xs max-h-40 rounded shadow border" />
                                                            </div>
                                                        @endif
                                                        @if(isset($file['mime_type']) && strpos($file['mime_type'], 'video/') === 0)
                                                            <div class="mt-2">
                                                                <video controls class="max-w-xs max-h-40 rounded shadow border">
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
        @if($submission->status === 'in_progress')
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden" id="final-submission-section">
                @if($allRequiredCompleted)
                    <!-- All Required Tasks Completed - Form Enabled -->
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100 p-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">🎉 Klaar om in te dienen!</h3>
                                <p class="text-gray-600">Alle verplichte taken zijn voltooid. Je kunt nu de checklist indienen voor review.</p>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Tasks Still Pending - Form Disabled -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-100 p-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Checklist Indienen</h3>
                                <p class="text-amber-700 font-medium">Voltooi eerst alle verplichte taken om de checklist in te kunnen dienen.</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="p-6">
                    <form method="POST" action="{{ route('employee.submissions.complete', $submission) }}" class="space-y-6" id="final-submission-form">
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
                                <canvas id="signature-pad-final" 
                                        class="border border-gray-300 rounded-xl bg-white mt-1 shadow-sm @if(!$allRequiredCompleted) opacity-50 @endif" 
                                        width="350" 
                                        height="120"
                                        @if(!$allRequiredCompleted) style="pointer-events: none;" @endif></canvas>
                                <input type="hidden" name="employee_signature" id="signature-input-final" @if($allRequiredCompleted) required @endif>
                                <div class="mt-3 flex gap-2">
                                    <button type="button" 
                                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-medium transition-colors @if(!$allRequiredCompleted) opacity-50 cursor-not-allowed @endif" 
                                            onclick="clearSignaturePadFinal()"
                                            @if(!$allRequiredCompleted) disabled @endif>
                                        Handtekening Wissen
                                    </button>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    @if($allRequiredCompleted)
                                        Teken je handtekening hierboven. Deze wordt opgeslagen als bewijs van voltooiing.
                                    @else
                                        Handtekening wordt beschikbaar nadat alle verplichte taken zijn voltooid.
                                    @endif
                                </p>
                            </div>
                            <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var canvasFinal = document.getElementById('signature-pad-final');
                                    if (canvasFinal && typeof SignaturePad !== 'undefined') {
                                        var signaturePadFinal = new SignaturePad(canvasFinal, { 
                                            backgroundColor: 'rgba(255,255,255,0)',
                                            penColor: 'rgb(0, 0, 0)',
                                            minWidth: 1,
                                            maxWidth: 3
                                        });
                                        
                                        window.clearSignaturePadFinal = function() {
                                            signaturePadFinal.clear();
                                        }
                                        
                                        // Store reference globally for validation
                                        window.signaturePadFinal = signaturePadFinal;
                                        
                                        var form = document.querySelector('form[action="{{ route('employee.submissions.complete', $submission) }}"]');
                                        if (form) {
                                            form.addEventListener('submit', function(e) {
                                                if (signaturePadFinal.isEmpty()) {
                                                    e.preventDefault();
                                                    alert('Please provide a signature.');
                                                    return false;
                                                }
                                                document.getElementById('signature-input-final').value = signaturePadFinal.toDataURL();
                                            });
                                        }
                                    } else {
                                        console.error('SignaturePad not loaded or canvas not found');
                                    }
                                });
                            </script>
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
                                     class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-3 @if(!$allRequiredCompleted) opacity-50 bg-gray-50 @endif" 
                                     placeholder="@if($allRequiredCompleted)Eventuele aanvullende opmerkingen over deze checklist...@else Dit veld wordt beschikbaar nadat alle verplichte taken zijn voltooid.@endif"
                                     @if(!$allRequiredCompleted) disabled @endif></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-end">
                            <a href="{{ route('employee.dashboard') }}" 
                               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Opslaan & Later Verder
                            </a>
                            
                            @if($allRequiredCompleted)
                                <button type="submit" 
                                        class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105"
                                        id="submit-checklist-btn">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Checklist Indienen
                                </button>
                            @else
                                <div class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-sm font-semibold rounded-xl text-gray-400 bg-gray-200 cursor-not-allowed"
                                     title="Voltooi eerst alle verplichte taken">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Checklist Indienen (Vergrendeld)
                                </div>
                                <p class="text-sm text-amber-600 text-center mt-2">
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
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-amber-50 border-l-4 border-amber-400 p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-3">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-amber-900 mb-2">Complete Required Tasks</h3>
                            <p class="text-amber-800">Please complete all required tasks before submitting this checklist.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- CRITICAL CAMERA & UPLOAD FUNCTIONS (single source of truth) -->
<script>
// === GLOBAL CAMERA / UPLOAD API ===
// Buttons in the markup call: takePhoto(taskId), takeVideo(taskId), uploadFile(taskId)

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

// Build simple modal for camera preview + capture/record
function createCameraModal(type, taskId, stream) {
    // Remove existing modal if any
    const existing = document.getElementById('camera-modal');
    if (existing) existing.remove();

    const modal = document.createElement('div');
    modal.id = 'camera-modal';
    modal.className = 'fixed inset-0 bg-black bg-opacity-80 flex items-center justify-center z-[9999]';

    const isVideo = type === 'video';
    const startText = isVideo ? 'Start Opname' : 'Maak Foto';

    modal.innerHTML = `
        <div class="bg-white rounded-xl p-6 w-[95%] max-w-[520px]">
            <h3 class="text-lg font-semibold mb-3 text-gray-900">${isVideo ? 'Video opnemen' : 'Foto maken'}</h3>
            <video id="cam-prev-${taskId}" autoplay playsinline muted class="w-full h-64 bg-black rounded-lg"></video>
            <div id="rec-status-${taskId}" class="hidden mt-2 text-red-600 font-semibold">🔴 Opname bezig...</div>
            <div class="flex gap-3 justify-end mt-4">
                <button id="cap-btn-${taskId}" class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700">${startText}</button>
                <button id="stop-btn-${taskId}" class="px-4 py-2 rounded-lg bg-amber-600 text-white hover:bg-amber-700 hidden">Stop Opname</button>
                <button id="close-btn-${taskId}" class="px-4 py-2 rounded-lg bg-gray-600 text-white hover:bg-gray-700">Sluiten</button>
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

    // Handlers
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
            // start recording
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

    // save stream reference for cleanup
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

// Add captured/recorded blob as File to the matching hidden input + show preview
function addMediaToTask(blob, filename, taskId, mediaType) {
    const fileInput = document.getElementById('file-input-' + taskId);
    if (!fileInput) {
        alert('File input niet gevonden voor task ' + taskId);
        return;
    }

    const file = new File([blob], filename, { type: blob.type || (mediaType === 'image' ? 'image/jpeg' : 'video/webm') });

    // keep existing files and append new one
    const dt = new DataTransfer();
    for (let i = 0; i < fileInput.files.length; i++) dt.items.add(fileInput.files[i]);
    dt.items.add(file);
    fileInput.files = dt.files;

    // Update preview (reuses the same preview function as manual upload)
    updateMediaPreview(taskId, file);
}

// Manual upload onchange handler
function handleFileSelect(input, taskId) {
    const previewArea = document.getElementById('preview-area-' + taskId);
    if (!previewArea) return;
    previewArea.innerHTML = '';

    Array.from(input.files).forEach(file => updateMediaPreview(taskId, file));
}

// Draw individual preview row
function updateMediaPreview(taskId, file) {
    const previewArea = document.getElementById('preview-area-' + taskId);
    if (!previewArea) return;

    const url = URL.createObjectURL(file);
    const isImg = file.type.startsWith('image/');
    const isVid = file.type.startsWith('video/');

    const row = document.createElement('div');
    row.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border';

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
        <div class="flex items-center space-x-3">
            ${mediaHtml}
            <div>
                <p class="text-sm font-medium text-gray-900">${file.name}</p>
                <p class="text-xs text-gray-500">${Math.round(file.size/1024)} KB • ${isImg ? 'Foto' : (isVid ? 'Video' : 'Bestand')}</p>
            </div>
        </div>
        <button type="button" class="text-red-600 hover:text-red-800" onclick="removePreviewItem(this, '${taskId}', '${file.name}')">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    previewArea.appendChild(row);
}

// Remove preview row AND keep remaining files in the input
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

<!-- Enhanced JavaScript (no duplicate camera code, fixed syntax) -->
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
    // Checklist persistence & forms
    initializeChecklists();

    // Ensure CSRF meta present
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }

    // Animate task cards
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

    // Animate progress ring once
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

    // Ripple on primary buttons
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

    // Touch feedback
    document.addEventListener('touchstart', function(e) {
        const t = e.target.closest('button, a');
        if (t) t.style.transform = 'scale(0.98)';
    });
    document.addEventListener('touchend', function(e) {
        const t = e.target.closest('button, a');
        if (t) setTimeout(() => { t.style.transform = ''; }, 150);
    });

    // Initial final-section check
    setTimeout(updateFinalSubmissionForm, 400);

    console.log('Helpers ready. Cards:', document.querySelectorAll('.task-card').length);
}); // <-- IMPORTANT: properly close DOMContentLoaded handler

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
        } else if (field.type === 'checkbox' || field.type === 'radio') {
            const checkedBoxes = form.querySelectorAll(`input[name="${field.name}"]:checked`);
            if (checkedBoxes.length === 0) {
                showNotification('Dit veld is verplicht.', 'error');
                isValid = false;
            }
        } else if (!field.value.trim()) {
            field.classList.add('border-red-500');
            if (field.name === 'digital_signature') {
                showNotification('Een digitale handtekening is vereist.', 'error');
            } else {
                showNotification('Alle verplichte velden moeten ingevuld worden.', 'error');
            }
            isValid = false;
        }
    });

    // Signature canvas check (per task)
    const signatureCanvas = form.querySelector('canvas[id^="signature-pad"]');
    if (signatureCanvas) {
        const key = signatureCanvas.id.replace('signature-pad-', '');
        if (window.signaturePads && window.signaturePads[key] && window.signaturePads[key].isEmpty()) {
            showNotification('Een digitale handtekening is vereist voor deze taak.', 'error');
            isValid = false;
        }
    }
    return isValid;
}

function showLoadingOverlay() {
    hideLoadingOverlay();
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
    overlay.innerHTML = `
        <div class="bg-white rounded-lg p-8 flex flex-col items-center space-y-4 max-w-sm mx-4">
            <svg class="animate-spin h-12 w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <div class="text-center">
                <p class="text-lg font-semibold text-gray-900">Bezig met verwerken...</p>
                <p class="text-sm text-gray-600 mt-1">Even geduld alsjeblieft</p>
            </div>
        </div>
    `;
    document.body.appendChild(overlay);
}
function hideLoadingOverlay() {
    const overlay = document.querySelector('.loading-overlay');
    if (overlay) overlay.remove();
}

function findElementsContaining(elements, text) {
    if (typeof elements === 'string') elements = document.querySelectorAll(elements);
    return Array.from(elements).filter(el => el.textContent.includes(text));
}
function elementContainsText(element, selector, text) {
    const els = element.querySelectorAll(selector);
    return Array.from(els).some(el => el.textContent.includes(text));
}

function updateFinalSubmissionForm() {
    try {
        const completedRequiredTasks = countCompletedRequiredTasks();
        const totalRequiredTasks = countTotalRequiredTasks();
        const finalSection = document.getElementById('final-submission-section');
        if (!finalSection) return;

        if (completedRequiredTasks >= totalRequiredTasks && totalRequiredTasks > 0) {
            enableFinalSubmissionForm();
            setTimeout(() => {
                finalSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                finalSection.style.boxShadow = '0 0 0 3px rgba(34, 197, 94, 0.5)';
                finalSection.style.transition = 'all 0.5s ease-out';
                showNotification('🎉 Alle verplichte taken zijn voltooid! Je kunt nu de checklist indienen.', 'success', 5000);
                setTimeout(() => { finalSection.style.boxShadow = ''; }, 3000);
            }, 100);
        }
    } catch (e) {
        console.error('updateFinalSubmissionForm error:', e);
    }
}

function enableFinalSubmissionForm() {
    const finalSection = document.getElementById('final-submission-section');
    if (!finalSection) return;

    const header = finalSection.querySelector('.bg-gradient-to-r');
    if (header) {
        header.className = 'bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100 p-6';
        const icon = header.querySelector('.w-10.h-10');
        if (icon) {
            icon.className = 'w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center mr-4';
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
            description.className = 'text-gray-600';
        }
    }

    const sigPad = finalSection.querySelector('#signature-pad-final');
    if (sigPad) {
        sigPad.classList.remove('opacity-50');
        sigPad.style.pointerEvents = 'auto';
        const sigInput = finalSection.querySelector('#signature-input-final');
        if (sigInput) sigInput.required = true;
        const help = sigPad.parentElement.querySelector('p');
        if (help) help.textContent = 'Teken je handtekening hierboven. Deze wordt opgeslagen als bewijs van voltooiing.';
        const label = finalSection.querySelector('label');
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
    const warn = finalSection.querySelector('.text-amber-600');
    if (disabledBtn && warn) {
        const container = disabledBtn.parentElement;
        disabledBtn.remove();
        warn.remove();

        const enabledButton = document.createElement('button');
        enabledButton.type = 'submit';
        enabledButton.id = 'submit-checklist-btn';
        enabledButton.className = 'inline-flex items-center justify-center px-8 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105';
        enabledButton.innerHTML = `
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Checklist Indienen
        `;
        container.appendChild(enabledButton);
        initializeFinalSubmissionAjax(); // rebind ajax submit
    }
}

function initializeFinalSubmissionAjax() {
    const finalSubmitForm = document.querySelector('#final-submission-form');
    if (!finalSubmitForm) return;

    const newForm = finalSubmitForm.cloneNode(true);
    finalSubmitForm.parentNode.replaceChild(newForm, finalSubmitForm);

    newForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!validateFinalForm(newForm)) return;

        const submitButton = newForm.querySelector('button[type="submit"]');
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

        const formData = new FormData(newForm);

        fetch(newForm.action, {
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

function validateFinalForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let ok = true;
    requiredFields.forEach(field => {
        field.classList.remove('border-red-500', 'border-red-300');
        if (!field.value.trim()) {
            field.classList.add('border-red-500');
            ok = false;
        }
    });
    const sigCanvas = form.querySelector('#signature-pad-final');
    if (sigCanvas && window.signaturePadFinal && window.signaturePadFinal.isEmpty()) {
        showNotification('Een digitale handtekening is vereist om de checklist in te dienen.', 'error');
        ok = false;
    }
    if (!ok) showNotification('Alle verplichte velden moeten ingevuld worden.', 'error');
    return ok;
}

function countCompletedRequiredTasks() {
    let completedRequired = 0;
    document.querySelectorAll('.task-card').forEach(card => {
        const isRequired = elementContainsText(card, '.text-red-800', 'Required');
        if (isRequired) {
            const serverCompleted = card.querySelector('.bg-green-50') !== null;
            const dynamicCompleted = elementContainsText(card, '.text-green-900', '✅ Task completed successfully');
            if (serverCompleted || dynamicCompleted) completedRequired++;
        }
    });
    return completedRequired;
}
function countTotalRequiredTasks() {
    let totalRequired = 0;
    document.querySelectorAll('.task-card').forEach(card => {
        if (elementContainsText(card, '.text-red-800', 'Required')) totalRequired++;
    });
    return totalRequired;
}

function updateTaskToCompleted(taskId, completedAt) {
    const taskCard = document.querySelector(`#task-form-${taskId}`)?.closest('.task-card');
    if (!taskCard) return;

    const taskHeader = taskCard.querySelector('.bg-gradient-to-r');
    const taskContent = taskCard.querySelector('.p-6:not(.bg-gradient-to-r)');
    if (!taskHeader || !taskContent) return;

    const headerIconDiv = taskHeader.querySelector('.w-10.h-10');
    if (headerIconDiv) {
        headerIconDiv.className = 'w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center';
        headerIconDiv.innerHTML = `
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        `;
    }
    const titleEl = taskHeader.querySelector('.text-xl.font-bold');
    if (titleEl) titleEl.className = 'text-xl font-bold text-green-900 mb-2';
    const descEl = taskHeader.querySelector('.text-sm.text-gray-600');
    if (descEl) descEl.className = 'text-sm text-green-700';

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

    taskContent.innerHTML = `
        <div class="bg-green-50 border-l-4 border-green-400">
            <div class="flex items-start">
                <div class="flex-shrink-0 mr-3">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="text-sm font-semibold text-green-900 mb-2">
                        ✅ Task completed successfully
                    </div>
                    <div class="text-sm font-semibold text-green-900 mb-2">
                        Completed: ${new Date(completedAt).toLocaleDateString('nl-NL', { 
                            month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit'
                        })}
                    </div>
                </div>
            </div>
        </div>
    `;

    taskCard.style.opacity = '0';
    setTimeout(() => {
        taskCard.style.opacity = '1';
        taskCard.style.transition = 'opacity 0.5s ease-in-out';
    }, 100);
}

function updateProgressIndicator() {
    try {
        const serverCompleted = document.querySelectorAll('.task-card .bg-green-50').length;
        let dynamicCompleted = 0;
        document.querySelectorAll('.task-card').forEach(card => {
            if (elementContainsText(card, '.text-green-900', '✅ Task completed successfully')) dynamicCompleted++;
        });
        const completed = serverCompleted + dynamicCompleted;
        const total = document.querySelectorAll('.task-card').length;
        const percent = total > 0 ? Math.round((completed / total) * 100) : 0;

        const progressCircle = document.querySelector('circle[stroke="#3b82f6"]');
        const progressText = document.querySelector('.text-lg.font-bold');
        const progressCount = document.querySelector('.text-sm.text-gray-500');

        if (progressCircle && progressText && progressCount) {
            const circumference = 2 * Math.PI * 40;
            progressCircle.style.strokeDashoffset = circumference * (1 - (percent / 100));
            progressText.textContent = percent + '%';
            progressCount.textContent = `${completed}/${total} tasks`;
        }
    } catch (e) {
        console.error('updateProgressIndicator error:', e);
    }
}

// Checklist + per-task AJAX submit
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

    // Per-task AJAX submit
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

    // Final submit ajax (bind if enabled on load)
    const finalForm = document.querySelector('#final-submission-form');
    if (finalForm && !finalForm.querySelector('.bg-gray-200')) {
        initializeFinalSubmissionAjax();
    }
}

// Notifications
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

    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg border-2 ${typeClasses[type] || typeClasses.info} transform translate-x-full transition-transform duration-300 max-w-md`;
    notification.innerHTML = `
        <div class="flex items-center">
            ${icons[type] || icons.info}
            <span class="flex-1">${message}</span>
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
</script>

<style>
/* Ripple effect styles */
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

/* Task card hover effects */
.task-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
.task-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Button hover effects */
button, a[role="button"] { position: relative; overflow: hidden; }

/* Progress circle animation */
circle[stroke="#3b82f6"] { transition: stroke-dashoffset 1s ease-in-out; }

/* Form field focus effects */
input:focus, textarea:focus { box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); }
</style>
@endsection

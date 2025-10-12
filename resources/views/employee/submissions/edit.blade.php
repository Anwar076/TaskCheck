@extends('layouts.employee')

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
                                            <p class="text-sm text-blue-700">{{ $task->instructions }}</p>
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

                            <form method="POST" action="{{ route('employee.submissions.tasks.complete', [$submission, $task]) }}" enctype="multipart/form-data" class="space-y-6">
                                @csrf

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
                                                    <button type="button" onclick="openCamera('photo', '{{ $submissionTask->id }}')" 
                                                            class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        </svg>
                                                        Make Photo
                                                    </button>
                                                @endif
                                                
                                                @if($task->required_proof_type === 'video' || $task->required_proof_type === 'any')
                                                    <button type="button" onclick="openCamera('video', '{{ $submissionTask->id }}')" 
                                                            class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                        </svg>
                                                        Make Video
                                                    </button>
                                                @endif
                                                
                                                <button type="button" onclick="document.getElementById('file-input-{{ $submissionTask->id }}').click()" 
                                                        class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
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

        @if($allRequiredCompleted && $submission->status === 'in_progress')
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-600 rounded-xl flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Submit Checklist</h3>
                            <p class="text-gray-600">All required tasks have been completed. You can now submit this checklist for review.</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-6">
                    <form method="POST" action="{{ route('employee.submissions.complete', $submission) }}" class="space-y-6">
                        @csrf
                        
                        @if($submission->taskList->requires_signature)
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Digital Signature <span class="text-red-500">*</span></label>
                                <canvas id="signature-pad-final" class="border border-gray-300 rounded-xl bg-white mt-1 shadow-sm" width="350" height="120"></canvas>
                                <input type="hidden" name="employee_signature" id="signature-input-final" required>
                                <div class="mt-3 flex gap-2">
                                    <button type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-medium transition-colors" onclick="clearSignaturePadFinal()">Clear Signature</button>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">Draw your signature above. This will be saved as proof of completion.</p>
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
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Notes (Optional)</label>
                            <textarea name="notes" rows="4" class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 px-4 py-3" placeholder="Any additional comments about this checklist..."></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-end">
                            <a href="{{ route('employee.dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Save & Continue Later
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Submit Checklist
                            </button>
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

<!-- Enhanced JavaScript with Animations -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Checklist persistence
    initializeChecklists();
    
    // Card animations
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

    // Progress circle animation
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

    // Button ripple effect
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
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }

    // Add ripple effect to action buttons
    const actionButtons = document.querySelectorAll('button[type="submit"], a[href*="dashboard"]');
    actionButtons.forEach(button => {
        button.addEventListener('click', createRipple);
    });

    // Touch feedback for mobile
    document.addEventListener('touchstart', function(e) {
        if (e.target.closest('button, a')) {
            e.target.closest('button, a').style.transform = 'scale(0.98)';
        }
    });

    document.addEventListener('touchend', function(e) {
        if (e.target.closest('button, a')) {
            setTimeout(() => {
                e.target.closest('button, a').style.transform = '';
            }, 150);
        }
    });
});

// Camera functionality
function openCamera(type, taskId) {
    const constraints = {
        video: {
            facingMode: 'environment', // Use back camera if available
            width: { ideal: 1280 },
            height: { ideal: 720 }
        },
        audio: type === 'video'
    };

    navigator.mediaDevices.getUserMedia(constraints)
        .then(stream => {
            showCameraModal(type, taskId, stream);
        })
        .catch(error => {
            console.error('Camera access error:', error);
            alert('Camera toegang geweigerd of niet beschikbaar. Gebruik de upload optie in plaats daarvan.');
        });
}

function showCameraModal(type, taskId, stream) {
    // Create modal
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
    modal.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <div class="text-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">
                    ${type === 'photo' ? 'Foto Maken' : 'Video Opnemen'}
                </h3>
            </div>
            
            <div class="mb-4">
                <video id="camera-preview-${taskId}" autoplay muted class="w-full h-64 bg-gray-900 rounded-lg"></video>
                <canvas id="camera-canvas-${taskId}" class="hidden"></canvas>
            </div>
            
            <div class="flex justify-center space-x-4">
                <button id="capture-btn-${taskId}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    ${type === 'photo' ? 'Foto Maken' : 'Opname Starten'}
                </button>
                <button id="stop-btn-${taskId}" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors hidden">
                    Opname Stoppen
                </button>
                <button id="close-camera-${taskId}" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Sluiten
                </button>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    
    const video = document.getElementById(`camera-preview-${taskId}`);
    const canvas = document.getElementById(`camera-canvas-${taskId}`);
    const captureBtn = document.getElementById(`capture-btn-${taskId}`);
    const stopBtn = document.getElementById(`stop-btn-${taskId}`);
    const closeBtn = document.getElementById(`close-camera-${taskId}`);
    
    video.srcObject = stream;
    
    let mediaRecorder;
    let recordedChunks = [];
    
    if (type === 'video') {
        mediaRecorder = new MediaRecorder(stream);
        mediaRecorder.ondataavailable = event => {
            if (event.data.size > 0) {
                recordedChunks.push(event.data);
            }
        };
        
        mediaRecorder.onstop = () => {
            const blob = new Blob(recordedChunks, { type: 'video/webm' });
            addFileToPreview(blob, `video-${Date.now()}.webm`, taskId);
            recordedChunks = [];
        };
    }
    
    captureBtn.addEventListener('click', () => {
        if (type === 'photo') {
            // Take photo
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(video, 0, 0);
            
            canvas.toBlob(blob => {
                addFileToPreview(blob, `photo-${Date.now()}.jpg`, taskId);
                closeCameraModal(modal, stream);
            }, 'image/jpeg', 0.8);
        } else {
            // Start video recording
            mediaRecorder.start();
            captureBtn.classList.add('hidden');
            stopBtn.classList.remove('hidden');
        }
    });
    
    stopBtn.addEventListener('click', () => {
        mediaRecorder.stop();
        closeCameraModal(modal, stream);
    });
    
    closeBtn.addEventListener('click', () => {
        closeCameraModal(modal, stream);
    });
    
    // Close on outside click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            closeCameraModal(modal, stream);
        }
    });
}

function closeCameraModal(modal, stream) {
    // Stop all tracks
    stream.getTracks().forEach(track => track.stop());
    
    // Remove modal
    document.body.removeChild(modal);
}

function addFileToPreview(blob, filename, taskId) {
    const previewArea = document.getElementById(`preview-area-${taskId}`);
    const fileInput = document.getElementById(`file-input-${taskId}`);
    
    // Create a File object from the blob
    const file = new File([blob], filename, { type: blob.type });
    
    // Add to file input
    const dataTransfer = new DataTransfer();
    dataTransfer.items.add(file);
    fileInput.files = dataTransfer.files;
    
    // Create preview element
    const previewItem = document.createElement('div');
    previewItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border';
    
    const isImage = file.type.startsWith('image/');
    const isVideo = file.type.startsWith('video/');
    
    let previewContent = '';
    if (isImage) {
        const url = URL.createObjectURL(blob);
        previewContent = `
            <div class="flex items-center space-x-3">
                <img src="${url}" alt="Preview" class="w-16 h-16 object-cover rounded-lg">
                <div>
                    <p class="text-sm font-medium text-gray-900">${filename}</p>
                    <p class="text-xs text-gray-500">Foto</p>
                </div>
            </div>
        `;
    } else if (isVideo) {
        const url = URL.createObjectURL(blob);
        previewContent = `
            <div class="flex items-center space-x-3">
                <video src="${url}" class="w-16 h-16 object-cover rounded-lg" muted></video>
                <div>
                    <p class="text-sm font-medium text-gray-900">${filename}</p>
                    <p class="text-xs text-gray-500">Video</p>
                </div>
            </div>
        `;
    } else {
        previewContent = `
            <div class="flex items-center space-x-3">
                <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">${filename}</p>
                    <p class="text-xs text-gray-500">Bestand</p>
                </div>
            </div>
        `;
    }
    
    previewItem.innerHTML = previewContent + `
        <button type="button" onclick="removePreviewItem(this, '${taskId}')" class="text-red-600 hover:text-red-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    
    previewArea.appendChild(previewItem);
}

function handleFileSelect(input, taskId) {
    const files = Array.from(input.files);
    const previewArea = document.getElementById(`preview-area-${taskId}`);
    
    // Clear existing previews
    previewArea.innerHTML = '';
    
    files.forEach(file => {
        addFileToPreview(file, file.name, taskId);
    });
}

function removePreviewItem(button, taskId) {
    const previewItem = button.closest('div');
    previewItem.remove();
    
    // Update file input
    const fileInput = document.getElementById(`file-input-${taskId}`);
    const dataTransfer = new DataTransfer();
    
    // Get remaining files from preview area
    const remainingItems = document.querySelectorAll(`#preview-area-${taskId} > div`);
    remainingItems.forEach(item => {
        // This is a simplified approach - in a real implementation you'd need to track the actual files
    });
    
    fileInput.files = dataTransfer.files;
}

// Checklist state management
function initializeChecklists() {
    const checklistCheckboxes = document.querySelectorAll('.checklist-checkbox');
    const submissionId = '{{ $submission->id }}';
    
    checklistCheckboxes.forEach((checkbox, index) => {
        const taskId = checkbox.dataset.taskId;
        const checklistKey = `checklist_${submissionId}_${taskId}`;
        
        // Load saved state from localStorage
        const savedState = localStorage.getItem(checklistKey);
        if (savedState) {
            const checkedItems = JSON.parse(savedState);
            if (checkedItems[index]) {
                checkbox.checked = true;
            }
        }
        
        // Save state on change
        checkbox.addEventListener('change', function() {
            const allCheckboxes = document.querySelectorAll(`.checklist-checkbox[data-task-id="${taskId}"]`);
            const checkedState = Array.from(allCheckboxes).map(cb => cb.checked);
            localStorage.setItem(checklistKey, JSON.stringify(checkedState));
        });
    });
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
    to {
        transform: scale(4);
        opacity: 0;
    }
}

/* Task card hover effects */
.task-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.task-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Button hover effects */
button, a[role="button"] {
    position: relative;
    overflow: hidden;
}

/* Progress circle animation */
circle[stroke="#3b82f6"] {
    transition: stroke-dashoffset 1s ease-in-out;
}

/* Form field focus effects */
input:focus, textarea:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
@endsection
@extends('layouts.employee')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('nav-extra')
        @php
            $currentListPosition = $currentListPosition ?? 1;
            $totalLists = $totalLists ?? 1;
            $listUrls = $listUrls ?? [];
            $currentListInJump = $currentListInJump ?? true;
        @endphp
        <div class="hero-list-nav">
            <button type="button"
                    class="hero-nav-btn"
                    id="hero-nav-prev"
                    aria-label="Vorige lijst"
                    @if(!empty($previousListUrl)) data-url="{{ $previousListUrl }}" @else disabled @endif>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>

            <div class="hero-list-nav-center">
                <h1 class="hero-list-title">
                    {{ $submission->taskList->title }}
                </h1>

                @if($currentListInJump && $totalLists > 0)
                <label class="list-jump" title="Ga naar een andere lijst">
                    <span class="sr-only">Lijstnummer</span>
                    <input type="number"
                           inputmode="numeric"
                           min="1"
                           max="{{ max(1, $totalLists) }}"
                           value="{{ $currentListPosition }}"
                           id="list-position-input"
                           class="list-jump-input"
                           aria-label="Lijst {{ $currentListPosition }} van {{ $totalLists }}"
                           data-current="{{ $currentListPosition }}"
                           data-total="{{ $totalLists }}"
                           data-urls='@json($listUrls)'
                           @if($totalLists <= 1) disabled @endif>
                    <span class="list-jump-total">/{{ $totalLists }}</span>
                </label>
                @endif
            </div>

            <button type="button"
                    class="hero-nav-btn"
                    id="hero-nav-next"
                    aria-label="Volgende lijst"
                    @if(!empty($nextListUrl)) data-url="{{ $nextListUrl }}" @else disabled @endif>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
@endsection

@section('content')
<div class=" bg-gray-50">
    @php
        $completedTasks = $submission->submissionTasks->whereIn('status', ['completed', 'approved'])->count();
        $totalTasks = $submission->submissionTasks->count();
        $progressPercent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        $progressColor = $progressPercent == 0 ? '#ef4444' : ($progressPercent >= 100 ? '#22c55e' : '#3b82f6');
    @endphp

    {{-- Taken: exact dezelfde max-w-7xl + px wrapper als hero --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <div class="space-y-3 sm:space-y-4">
            @foreach($submission->submissionTasks as $index => $submissionTask)
                @php
                    $task = $submissionTask->task;
                    $isTaskDone = in_array($submissionTask->status, ['completed', 'approved'], true);
                    $needsAttention = in_array($submissionTask->status, ['rejected', 'redo_requested'], true)
                        || ($submissionTask->rejection_reason && $submissionTask->status === 'pending');
                    $taskValidationRules = is_array($task->validation_rules) ? $task->validation_rules : [];
                    $hasMetricRuleHeader = !empty($taskValidationRules['metric']);
                    $requiresEvidence = in_array($task->required_proof_type, ['photo', 'video', 'file', 'text', 'any'], true)
                        || (bool) $task->requires_signature
                        || $hasMetricRuleHeader;
                    $canQuickComplete = !$isTaskDone
                        && in_array($submissionTask->status, ['pending', 'redo_requested'], true)
                        && !$requiresEvidence;
                    $listStillOpen = !in_array($submission->status, ['completed', 'reviewed'], true);
                    $canEditTask = $listStillOpen
                        && in_array($submissionTask->status, ['pending', 'redo_requested', 'completed'], true);
                    $startExpanded = $needsAttention;
                @endphp
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden task-card {{ $startExpanded ? 'is-expanded' : '' }} {{ $isTaskDone ? 'task-completed' : '' }}"
                     data-task-id="{{ $task->id }}"
                     data-required="{{ $task->is_required ? '1' : '0' }}"
                     data-status="{{ $submissionTask->status }}"
                     data-expanded="{{ $startExpanded ? '1' : '0' }}">
                    <div class="task-header">
                        <div class="task-index {{ $isTaskDone ? 'is-done' : '' }}">
                            @if($isTaskDone)
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @else
                                <span>{{ $index + 1 }}</span>
                            @endif
                        </div>

                        <div class="task-title-row">
                            <h3 class="task-title font-sans {{ $isTaskDone ? 'is-done' : '' }}">
                                {{ $task->title }}@if($task->is_required)<span class="task-required-mark" title="Verplicht">*</span>@endif
                            </h3>
                        </div>

                        <div class="task-actions">
                            @if($submissionTask->status === 'rejected')
                                <span class="task-chip task-chip-required">Afgewezen</span>
                            @elseif($submissionTask->status === 'redo_requested')
                                <span class="task-chip task-chip-redo">Opnieuw</span>
                            @endif
                            @if($canQuickComplete)
                                <button type="button"
                                        class="task-quick-complete {{ $startExpanded ? 'hidden' : '' }}"
                                        data-task-id="{{ $task->id }}"
                                        title="Markeren als voltooid"
                                        onclick="event.stopPropagation(); quickCompleteTask('{{ $task->id }}');">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            @endif
                            <button type="button"
                                    class="task-toggle"
                                    aria-expanded="{{ $startExpanded ? 'true' : 'false' }}"
                                    aria-controls="task-body-{{ $submissionTask->id }}"
                                    title="{{ $startExpanded ? 'Taak inklappen' : 'Taak uitklappen' }}"
                                    onclick="event.stopPropagation(); toggleTaskCard(this.closest('.task-card'));">
                                <svg class="task-toggle-icon w-4 h-4 {{ $startExpanded ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </div>

                        @if($task->description)
                            <p class="task-detail {{ $isTaskDone ? 'text-green-700' : 'text-gray-600' }} {{ $startExpanded ? '' : 'hidden' }}">
                                {{ $task->description }}
                            </p>
                        @endif
                        @if($task->instructions)
                            <div class="task-detail task-instructions {{ $startExpanded ? '' : 'hidden' }}">
                                <p class="task-instructions-label">Gedetailleerde instructies</p>
                                <p class="task-instructions-text">{{ $task->instructions }}</p>
                            </div>
                        @endif
                    </div>

                    <div id="task-body-{{ $submissionTask->id }}" class="task-body {{ $startExpanded ? '' : 'hidden' }}">
                    @if($submissionTask->rejection_reason && in_array($submissionTask->status, ['pending', 'redo_requested']))
                        <!-- Rejection/Redo Information -->
                        <div class="bg-red-50 border-l-4 border-red-400 px-4 sm:px-6 py-4 sm:py-6">
                            <div class="flex flex-col sm:flex-row items-start gap-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 text-sm sm:text-base">
                                    @if($submissionTask->rejection_reason)
                                        <h4 class="text-base sm:text-lg font-semibold text-red-900 mb-2">Taak Afgekeurd</h4>
                                        @if($submissionTask->rejection_reason)
                                            <p class="text-red-800 mb-2">
                                                <strong>Reden:</strong> {{ $submissionTask->rejection_reason }}
                                            </p>
                                        @endif
                                        <p class="text-red-700">
                                            Deze taak is afgekeurd op {{ $submissionTask->rejected_at ? $submissionTask->rejected_at->setTimezone('Europe/Amsterdam')->format('d M Y H:i') : 'onbekende datum' }}.
                                            Voer deze taak opnieuw uit en dien daarna de checklist opnieuw in.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($canEditTask)
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
                                                               class="checklist-checkbox mt-0.5 w-4 h-4 text-cyan-600 border-2 border-cyan-300 rounded focus:ring-cyan-500 focus:ring-2"
                                                               @checked(!empty(($submissionTask->checklist_progress ?? [])[$index]))>
                                                        <span class="text-sm text-cyan-800 flex-1">{{ $item }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            <p class="text-xs text-cyan-600 mt-3 italic">Check off items as you complete them. These are for guidance only.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($isTaskDone)
                                <p class="task-edit-hint text-sm text-slate-600 mb-4">Deze taak is afgerond. Je kunt de gegevens nog aanpassen tot je de lijst indient.</p>
                            @endif

                            <form method="POST" action="{{ route('employee.submissions.tasks.complete', [$submission, $task]) }}" enctype="multipart/form-data" class="space-y-6" id="task-form-{{ $task->id }}" data-required-proof-type="{{ $task->required_proof_type }}" data-has-existing-proof="{{ !empty($submissionTask->proof_files) ? '1' : '0' }}" data-has-signature="{{ $submissionTask->digital_signature ? '1' : '0' }}">
                                @csrf
                                @php
                                    $validationRules = is_array($task->validation_rules) ? $task->validation_rules : [];
                                    $metricType = $validationRules['metric'] ?? null;
                                    $hasMetricRule = in_array($metricType, ['temperature', 'ph'], true);
                                    $metricUnit = $validationRules['unit'] ?? ($metricType === 'ph' ? 'pH' : '°C');
                                    $metricMin = $validationRules['min'] ?? null;
                                    $metricMax = $validationRules['max'] ?? null;
                                    $metricComparison = $validationRules['comparison'] ?? null;
                                    $metricValue = null;
                                    if ($hasMetricRule && filled($submissionTask->proof_text) && preg_match('/[-+]?\d+(?:[.,]\d+)?/', $submissionTask->proof_text, $metricMatch)) {
                                        $metricValue = str_replace(',', '.', $metricMatch[0]);
                                    }
                                @endphp
                                
                                <!-- Hidden field for checklist progress -->
                                <input type="hidden" name="checklist_progress" id="checklist-progress-{{ $task->id }}" value="">

                                @if($hasMetricRule)
                                    <input type="hidden" name="proof_text" id="metric-proof-text-{{ $task->id }}" value="{{ old('proof_text', $submissionTask->proof_text) }}">
                                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">
                                        <div class="mb-2 flex items-center justify-between gap-2">
                                            <label for="metric-input-{{ $task->id }}" class="text-sm font-semibold text-blue-900">
                                                {{ $metricType === 'ph' ? 'pH meting' : 'Temperatuur meting' }}
                                            </label>
                                            <span class="text-xs font-semibold text-blue-700">
                                                @if(!is_null($metricMin) && !is_null($metricMax))
                                                    Norm: {{ $metricMin }} - {{ $metricMax }} {{ $metricUnit }}
                                                @elseif(!is_null($metricMin))
                                                    Norm: ≥ {{ $metricMin }} {{ $metricUnit }}
                                                @elseif(!is_null($metricMax))
                                                    Norm: {{ $metricComparison === 'lte' ? '≤' : 'max' }} {{ $metricMax }} {{ $metricUnit }}
                                                @endif
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <input
                                                type="number"
                                                step="0.1"
                                                id="metric-input-{{ $task->id }}"
                                                class="block w-full rounded-xl border border-blue-300 px-4 py-3 text-base shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                placeholder="{{ $metricType === 'ph' ? 'Bijv. 4.3' : 'Bijv. 6.5' }}"
                                                value="{{ old('proof_text', $metricValue) }}"
                                                required
                                                data-min="{{ $metricMin }}"
                                                data-max="{{ $metricMax }}"
                                                data-comparison="{{ $metricComparison }}"
                                                data-unit="{{ $metricUnit }}"
                                                data-status-id="metric-status-{{ $task->id }}"
                                                data-proof-id="metric-proof-text-{{ $task->id }}"
                                            >
                                            <span class="text-sm font-semibold text-blue-800">{{ $metricUnit }}</span>
                                        </div>
                                        <p id="metric-status-{{ $task->id }}" class="mt-2 text-sm font-medium text-blue-700">
                                            Vul de meting in om direct te zien of deze binnen norm valt.
                                        </p>
                                    </div>
                                    <script>
                                        (function () {
                                            const input = document.getElementById('metric-input-{{ $task->id }}');
                                            if (!input) return;
                                            const statusEl = document.getElementById(input.dataset.statusId);
                                            const proofEl = document.getElementById(input.dataset.proofId);
                                            const min = input.dataset.min !== '' ? Number(input.dataset.min) : null;
                                            const max = input.dataset.max !== '' ? Number(input.dataset.max) : null;
                                            const comparison = input.dataset.comparison || '';
                                            const unit = input.dataset.unit || '';

                                            const updateMetricStatus = () => {
                                                const value = Number(input.value);
                                                if (Number.isNaN(value)) {
                                                    input.classList.remove('border-red-400', 'bg-red-50', 'border-green-400', 'bg-green-50');
                                                    input.classList.add('border-blue-300');
                                                    statusEl.textContent = 'Vul de meting in om direct te zien of deze binnen norm valt.';
                                                    statusEl.className = 'mt-2 text-sm font-medium text-blue-700';
                                                    if (proofEl) proofEl.value = '';
                                                    return;
                                                }

                                                let inRange = true;
                                                if (min !== null && value < min) inRange = false;
                                                if (max !== null) {
                                                    if (comparison === 'lt') {
                                                        inRange = inRange && value < max;
                                                    } else {
                                                        inRange = inRange && value <= max;
                                                    }
                                                }

                                                if (inRange) {
                                                    input.classList.remove('border-red-400', 'bg-red-50', 'border-blue-300');
                                                    input.classList.add('border-green-400', 'bg-green-50');
                                                    statusEl.textContent = `Binnen norm: ${value} ${unit}`;
                                                    statusEl.className = 'mt-2 text-sm font-semibold text-green-700';
                                                } else {
                                                    input.classList.remove('border-green-400', 'bg-green-50', 'border-blue-300');
                                                    input.classList.add('border-red-400', 'bg-red-50');
                                                    statusEl.textContent = `Afwijking: ${value} ${unit} valt buiten norm`;
                                                    statusEl.className = 'mt-2 text-sm font-semibold text-red-700';
                                                }

                                                if (proofEl) {
                                                    proofEl.value = `${value} ${unit}`.trim();
                                                }
                                            };

                                            input.addEventListener('input', updateMetricStatus);
                                            updateMetricStatus();
                                        })();
                                    </script>
                                @endif

                                <!-- Text Proof -->
                                @if((in_array($task->required_proof_type, ['text', 'any']) || $task->required_proof_type === 'none') && !$hasMetricRule)
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Notities/Opmerkingen
                                            @if($task->required_proof_type === 'text') <span class="text-red-500">*</span> @endif
                                        </label>
                                        <textarea name="proof_text" rows="4" class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base" 
                                            placeholder="Voeg notities of opmerkingen toe over het voltooien van deze taak..."
                                            {{ $task->required_proof_type === 'text' ? 'required' : '' }}>{{ old('proof_text', $submissionTask->proof_text) }}</textarea>
                                    </div>
                                @endif

                                <!-- File/Photo/Video Proof -->
                                {{-- File/Photo/Video Proof --}}
                                @if(in_array($task->required_proof_type, ['photo', 'video', 'file', 'any']))
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                                            Bewijsbestanden
                                            @if($task->required_proof_type !== 'any') <span class="text-red-500">*</span> @endif
                                        </label>
                                        
                                        <!-- Knoppen -->
                                        <div class="mb-4">
                                            <div class="flex flex-col gap-3">

                                                {{-- DESKTOP: knoppen in één rij --}}
                                                <div class="hidden md:flex md:flex-row md:flex-wrap md:gap-3">
                                                    @if($task->required_proof_type === 'photo' || $task->required_proof_type === 'any')
                                                        <button type="button" 
                                                                class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm"
                                                                onclick="openCamera('{{ $task->id }}', 'photo')">
                                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            </svg>
                                                            Foto maken
                                                        </button>
                                                    @endif
                                                    
                                                    @if($task->required_proof_type === 'video' || $task->required_proof_type === 'any')
                                                        <button type="button" 
                                                                class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm"
                                                                onclick="openCamera('{{ $task->id }}', 'video')">
                                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                            </svg>
                                                            Video opnemen
                                                        </button>
                                                    @endif

                                                    <button type="button" 
                                                            class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm"
                                                            onclick="uploadFile('{{ $task->id }}')">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                        </svg>
                                                        Upload bestand
                                                    </button>
                                                </div>

                                                {{-- MOBIEL / TABLET: onder elkaar --}}
                                                <div class="md:hidden flex flex-col gap-2 w-full">
                                                    @if($task->required_proof_type === 'photo' || $task->required_proof_type === 'any')
                                                        <button type="button" 
                                                                class="w-full flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm"
                                                                onclick="openCamera('{{ $task->id }}', 'photo')">
                                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            </svg>
                                                            Foto maken
                                                        </button>
                                                    @endif

                                                    @if($task->required_proof_type === 'video' || $task->required_proof_type === 'any')
                                                        <button type="button" 
                                                                class="w-full flex items-center justify-center px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm"
                                                                onclick="openCamera('{{ $task->id }}', 'video')">
                                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                            </svg>
                                                            Video opnemen
                                                        </button>
                                                    @endif

                                                    <button type="button" 
                                                            class="w-full flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm"
                                                            onclick="uploadFile('{{ $task->id }}')">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                        </svg>
                                                        Upload / galerij
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- VERBORGEN INPUTS --}}

                                        {{-- Hoofd-input die naar de server gaat --}}
                                        <input 
                                            type="file" 
                                            id="file-input-{{ $task->id }}" 
                                            name="proof_files[]" 
                                            multiple 
                                            class="hidden"
                                            @if($task->required_proof_type === 'photo') accept="image/*" @endif
                                            @if($task->required_proof_type === 'video') accept="video/*" @endif
                                            onchange="handleFileSelect(this, '{{ $task->id }}')">

                                        {{-- Camera-input voor foto (wordt via JS gekopieerd naar file-input) --}}
                                        @if($task->required_proof_type === 'photo' || $task->required_proof_type === 'any')
                                            <input 
                                                type="file" 
                                                id="camera-input-photo-{{ $task->id }}" 
                                                accept="image/*" 
                                                capture="environment"
                                                class="hidden"
                                                onchange="handleCameraCapture(this, '{{ $task->id }}')">
                                        @endif

                                        {{-- Camera-input voor video (wordt via JS gekopieerd naar file-input) --}}
                                        @if($task->required_proof_type === 'video' || $task->required_proof_type === 'any')
                                            <input 
                                                type="file" 
                                                id="camera-input-video-{{ $task->id }}" 
                                                accept="video/*" 
                                                capture="environment"
                                                class="hidden"
                                                onchange="handleCameraCapture(this, '{{ $task->id }}')">
                                        @endif
                                        
                                        <!-- Preview area -->
                                        <div id="preview-area-{{ $task->id }}" class="mt-4 space-y-2"></div>
                                        
                                        <p class="mt-2 text-xs sm:text-sm text-gray-500">
                                            @if($task->required_proof_type === 'photo')
                                                Maak een foto of upload een afbeelding als bewijs. Max 5MB per bestand.
                                            @elseif($task->required_proof_type === 'video')
                                                Neem een video op of upload een video als bewijs. Max 10MB per bestand.
                                            @else
                                                Upload bestanden als bewijs. Max 10MB per bestand.
                                            @endif
                                        </p>
                                    </div>
                                @endif

                                @if($task->required_proof_type !== 'text')
                                <details class="group rounded-xl border border-slate-200 bg-gradient-to-r from-slate-50 to-white shadow-sm" @if($submissionTask->employee_comment) open @endif>
                                    <summary class="list-none cursor-pointer px-4 py-3.5 select-none">
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="flex items-center gap-2.5 min-w-0">
                                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h8M8 14h5M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                                                    </svg>
                                                </span>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-semibold text-slate-800">Opmerking toevoegen <span class="text-slate-500 font-medium">(optioneel)</span></p>
                                                    <p class="text-xs text-slate-500">Extra context of bijzonderheden bij deze taak</p>
                                                </div>
                                            </div>
                                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-200 group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </summary>
                                    <div class="border-t border-slate-200 px-4 pb-4 pt-3 bg-white/80">
                                        <textarea
                                            name="employee_comment"
                                            rows="3"
                                            class="mt-1 block w-full border border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500 px-3 sm:px-4 py-2 sm:py-3 text-sm sm:text-base"
                                            placeholder="Bijv. bijzonderheden, afwijking, of extra context bij deze taak..."
                                        >{{ old('employee_comment', $submissionTask->employee_comment) }}</textarea>
                                        <p class="mt-1 text-xs text-gray-500">Dit veld is niet verplicht.</p>
                                    </div>
                                </details>
                                @endif


                                <!-- Digital Signature for Individual Task -->
                                @if($task->requires_signature)
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Handtekening @if(!$submissionTask->digital_signature)<span class="text-red-500">*</span>@endif</label>
                                        <div class="mt-2 w-full max-w-full overflow-x-auto">
                                            <canvas id="signature-pad-task-{{ $submissionTask->id }}" class="border border-gray-300 rounded-xl bg-white shadow-sm w-full max-w-sm" width="350" height="120"></canvas>
                                        </div>
                                        <input type="hidden" name="digital_signature" id="signature-input-task-{{ $submissionTask->id }}" @if(!$submissionTask->digital_signature) required @endif>
                                        <div class="flex flex-col sm:flex-row gap-2 mt-3">
                                            <button type="button" class="w-full sm:w-auto px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-lg text-sm font-medium transition-colors" onclick="clearSignaturePad('task-{{ $submissionTask->id }}')">Handtekening Wissen</button>
                                        </div>
                                        <p class="mt-2 text-xs sm:text-sm text-gray-500">
                                            @if($submissionTask->digital_signature)
                                                Er is al een handtekening opgeslagen. Teken opnieuw als je die wilt vervangen.
                                            @else
                                                Teken je handtekening hierboven. Dit wordt opgeslagen als bewijs van voltooiing.
                                            @endif
                                        </p>
                                    </div>
                                @endif

                                <div class="flex flex-col sm:flex-row justify-end">
                                    <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-5 sm:px-6 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 mt-2 sm:mt-0">
                                            @if($isTaskDone)
                                                Wijzigingen opslaan
                                            @elseif($task->requires_signature)
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            Teken & Voltooien
                                            @else
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Markeren als Voltooid
                                            @endif
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <!-- Completed Task Display -->
                        <div class="task-completed-banner">
                            <div class="task-completed-body">
                            <p class="task-completed-text">
                                Voltooid op {{ $submissionTask->completed_at->setTimezone('Europe/Amsterdam')->format('d M Y H:i') }}
                            </p>
                                    
                                    @if($task->checklist_items && count($task->checklist_items) > 0)
                                        <div class="mb-4">
                                            <strong class="text-sm text-green-800">Checklist Voortgang:</strong>
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
                                                    {{ $completedCount }}/{{ count($task->checklist_items) }} items voltooid
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($submissionTask->proof_text)
                                        <div class="mb-4">
                                            <strong class="text-sm text-green-800">Notities:</strong>
                                            <p class="text-sm text-green-700 mt-1 bg-white p-3 rounded-lg border border-green-200">{{ $submissionTask->proof_text }}</p>
                                        </div>
                                    @endif

                                    @if($submissionTask->employee_comment && $task->required_proof_type !== 'text')
                                        <div class="mb-4">
                                            <strong class="text-sm text-green-800">Opmerking medewerker:</strong>
                                            <p class="text-sm text-green-700 mt-1 bg-white p-3 rounded-lg border border-green-200">{{ $submissionTask->employee_comment }}</p>
                                        </div>
                                    @endif

                                    @if($submissionTask->proof_files && count($submissionTask->proof_files) > 0)
                                        <div>
                                            <strong class="text-sm text-green-800">Geüploade Bestanden:</strong>
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
                    @endif
                    </div>{{-- /task-body --}}
                </div>
            @endforeach
        </div>

        <!-- Final Submission -->
        @php
            // Alleen completed/approved tellen als klaar voor indienen.
            $allRequiredCompleted = $submission->submissionTasks
                ->filter(fn($st) => $st->task->is_required)
                ->every(fn($st) => in_array($st->status, ['completed', 'approved']));
            $hasRedoRequired = $submission->submissionTasks
                ->filter(fn($st) => $st->task->is_required)
                ->contains(fn($st) => in_array($st->status, ['redo_requested', 'rejected']) || (!empty($st->rejection_reason) && $st->status === 'pending'));
        @endphp

        <!-- Final Submission - Always Visible -->
       {{-- Final Submission - Always Visible --}}
        @if($submission->status === 'in_progress')
            <div class="mt-6 sm:mt-8 bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden"
                 id="final-submission-section"
                 data-ready="{{ $allRequiredCompleted ? '1' : '0' }}">
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
                                    @if($hasRedoRequired)
                                        Voer de taken die opnieuw moeten worden gedaan eerst opnieuw uit om de checklist in te kunnen dienen.
                                    @else
                                        Voltooi eerst alle verplichte taken om de checklist in te kunnen dienen.
                                    @endif
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

                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-end items-stretch sm:items-center">
                            <a href="{{ route('employee.dashboard') }}" 
                            class="w-full sm:w-auto inline-flex items-center justify-center px-5 sm:px-6 py-3 border border-gray-300 text-sm font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Opslaan & Later Verder
                            </a>

                            <div class="flex flex-col items-stretch sm:items-end gap-2">
                                <button type="submit"
                                        id="submit-checklist-btn"
                                        @if(!$allRequiredCompleted) disabled @endif
                                        class="w-full sm:w-auto inline-flex items-center justify-center px-7 sm:px-8 py-3 border border-transparent text-sm font-semibold rounded-xl text-white transition-all duration-200 shadow-lg {{ $allRequiredCompleted ? 'bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 hover:shadow-xl transform hover:scale-105' : 'bg-gray-300 cursor-not-allowed opacity-70' }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Checklist Indienen
                                </button>
                                <p id="submit-checklist-hint" class="text-xs sm:text-sm text-amber-600 text-center sm:text-right {{ $allRequiredCompleted ? 'hidden' : '' }}">
                                    @if($hasRedoRequired)
                                        Voer de opnieuw vereiste taken eerst opnieuw uit
                                    @else
                                        Voltooi alle verplichte taken om in te kunnen dienen
                                    @endif
                                </p>
                            </div>
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
                            <h3 class="text-base sm:text-lg font-semibold text-amber-900 mb-2 break-words">Voltooi Verplichte Taken</h3>
                            <p class="text-sm sm:text-base text-amber-800">
                                @if($hasRedoRequired ?? false)
                                    Voer de taken die opnieuw moeten worden gedaan eerst opnieuw uit.
                                @else
                                    Voltooi eerst alle verplichte taken voordat je deze checklist indient.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
@php
    $savedProofFilesByTask = $submission->submissionTasks->mapWithKeys(function ($st) {
        return [
            (string) $st->task_id => \App\Helpers\ProofFileHelper::withAbsoluteUrls(
                is_array($st->proof_files) ? array_values($st->proof_files) : []
            ),
        ];
    });
@endphp
<!-- SignaturePad lib (één keer laden voor alle handtekeningen) -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<!-- CRITICAL CAMERA & UPLOAD FUNCTIONS (single source of truth) -->
<script>
// === GLOBAL CAMERA / UPLOAD API ===

async function openCamera(taskId, mode) {
    const nativeCamera = window.TaskCheckNative?.plugin?.('Camera');
    if (mode === 'photo' && nativeCamera) {
        try {
            const image = await nativeCamera.getPhoto({
                quality: 85,
                allowEditing: false,
                correctOrientation: true,
                resultType: 'uri',
                source: 'camera',
            });
            const response = await fetch(image.webPath);
            const blob = await response.blob();
            const extension = image.format === 'png' ? 'png' : 'jpg';
            const file = new File([blob], `taskcheck-${Date.now()}.${extension}`, {
                type: blob.type || `image/${extension === 'jpg' ? 'jpeg' : extension}`,
                lastModified: Date.now(),
            });
            addProofFiles(taskId, [file]);
            return;
        } catch (error) {
            if (/cancel/i.test(error?.message || '')) return;
            console.warn('Native camera niet beschikbaar; browsercamera wordt gebruikt.', error);
        }
    }

    // mode = 'photo' of 'video'
    const inputId = mode === 'video' 
        ? 'camera-input-video-' + taskId 
        : 'camera-input-photo-' + taskId;

    const input = document.getElementById(inputId);
    if (input) {
        input.click(); // triggert Android camera (als ondersteund)
    } else {
        console.warn('Camera-input niet gevonden voor', inputId);
    }
}

function uploadFile(taskId) {
    const input = document.getElementById('file-input-' + taskId);
    if (input) {
        // Allow selecting the same file again on a next pick.
        input.value = '';
        input.click();
    } else {
        console.warn('File-input niet gevonden voor', taskId);
    }
}

window.taskProofFileStore = window.taskProofFileStore || {};
window.savedProofFiles = Object.assign({}, window.savedProofFiles || {}, @json($savedProofFilesByTask));

function proofFileKey(file) {
    return `${file.name}|${file.size}|${file.lastModified}`;
}

function getStoredProofFiles(taskId) {
    return window.taskProofFileStore[String(taskId)] || [];
}

function setStoredProofFiles(taskId, files) {
    window.taskProofFileStore[String(taskId)] = files;
    syncProofFilesToInput(taskId);
}

function getSavedProofFiles(taskId) {
    return window.savedProofFiles[String(taskId)] || [];
}

function setSavedProofFiles(taskId, files) {
    window.savedProofFiles[String(taskId)] = Array.isArray(files) ? files : [];
}

function syncProofFilesToInput(taskId) {
    const fileInput = document.getElementById('file-input-' + taskId);
    if (!fileInput) return;

    const dt = new DataTransfer();
    getStoredProofFiles(taskId).forEach(file => dt.items.add(file));
    fileInput.files = dt.files;
}

function addProofFiles(taskId, newFiles) {
    const existing = getStoredProofFiles(taskId);
    const seen = new Set(existing.map(proofFileKey));
    const merged = [...existing];

    Array.from(newFiles).forEach(file => {
        const key = proofFileKey(file);
        if (!seen.has(key)) {
            seen.add(key);
            merged.push(file);
        }
    });

    setStoredProofFiles(taskId, merged);
    renderProofFilePreviews(taskId);
}

function handleCameraCapture(cameraInput, taskId) {
    if (!cameraInput.files.length) return;

    addProofFiles(taskId, cameraInput.files);
    cameraInput.value = '';
    cameraInput.name = '';

    const photoInput = document.getElementById('camera-input-photo-' + taskId);
    const videoInput = document.getElementById('camera-input-video-' + taskId);
    if (photoInput && photoInput !== cameraInput) photoInput.name = '';
    if (videoInput && videoInput !== cameraInput) videoInput.name = '';
}

function handleFileSelect(input, taskId) {
    const newFiles = Array.from(input.files || []);
    if (newFiles.length === 0) return;

    addProofFiles(taskId, newFiles);
}

function proofFileKind(mime, name) {
    const type = (mime || '').toLowerCase();
    const fileName = (name || '').toLowerCase();
    if (type.startsWith('image/') || /\.(jpe?g|png|gif|webp|heic|heif)$/i.test(fileName)) return 'image';
    if (type.startsWith('video/') || /\.(mp4|webm|mov|m4v|avi)$/i.test(fileName)) return 'video';
    return 'file';
}

function renderSavedProofFilePreview(taskId, file) {
    const previewArea = document.getElementById('preview-area-' + taskId);
    if (!previewArea) return;

    const name = file.original_name || 'Bestand';
    const kind = proofFileKind(file.mime_type, name);
    const url = file.url || '';
    const sizeKb = file.size ? Math.round(Number(file.size) / 1024) : null;

    const row = document.createElement('div');
    row.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border flex-col sm:flex-row gap-3 sm:gap-0';
    row.dataset.savedPath = file.path || '';

    let mediaHtml = `
        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
    `;
    if (kind === 'image' && url) {
        mediaHtml = `<img src="${url}" alt="Opgeslagen foto" class="w-16 h-16 object-cover rounded-lg">`;
    } else if (kind === 'video' && url) {
        mediaHtml = `<video src="${url}" class="w-16 h-16 object-cover rounded-lg" muted></video>`;
    }

    row.innerHTML = `
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            ${mediaHtml}
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate"></p>
                <p class="text-xs text-gray-500">${sizeKb !== null ? sizeKb + ' KB • ' : ''}${kind === 'image' ? 'Foto' : (kind === 'video' ? 'Video' : 'Bestand')} • Opgeslagen</p>
            </div>
        </div>
    `;
    const titleEl = row.querySelector('.text-sm.font-medium');
    if (titleEl) titleEl.textContent = name;

    previewArea.appendChild(row);
}

function renderProofFilePreviews(taskId) {
    const previewArea = document.getElementById('preview-area-' + taskId);
    if (!previewArea) return;

    previewArea.innerHTML = '';

    const photoInput = document.getElementById('camera-input-photo-' + taskId);
    const videoInput = document.getElementById('camera-input-video-' + taskId);
    if (photoInput) photoInput.name = '';
    if (videoInput) videoInput.name = '';

    const saved = getSavedProofFiles(taskId);
    saved.forEach(file => renderSavedProofFilePreview(taskId, file));
    getStoredProofFiles(taskId).forEach(file => updateMediaPreview(taskId, file));

    if (saved.length > 0) {
        const hint = document.createElement('p');
        hint.className = 'text-xs text-slate-500';
        hint.textContent = 'Eerder opgeslagen bestanden blijven bewaard. Nieuwe foto’s of video’s worden toegevoegd.';
        previewArea.appendChild(hint);
    }
}

function updateMediaPreview(taskId, file) {
    const previewArea = document.getElementById('preview-area-' + taskId);
    if (!previewArea) return;

    const url = URL.createObjectURL(file);
    const kind = proofFileKind(file.type, file.name);
    const fileKey = proofFileKey(file);

    const row = document.createElement('div');
    row.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border flex-col sm:flex-row gap-3 sm:gap-0';
    row.dataset.fileKey = fileKey;

    let mediaHtml = `
        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
    `;

    if (kind === 'image') {
        mediaHtml = `<img src="${url}" alt="Preview" class="w-16 h-16 object-cover rounded-lg">`;
    } else if (kind === 'video') {
        mediaHtml = `<video src="${url}" class="w-16 h-16 object-cover rounded-lg" muted></video>`;
    }

    row.innerHTML = `
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            ${mediaHtml}
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate"></p>
                <p class="text-xs text-gray-500">${Math.round(file.size / 1024)} KB • ${kind === 'image' ? 'Foto' : (kind === 'video' ? 'Video' : 'Bestand')}</p>
            </div>
        </div>
        <button type="button" class="text-red-600 hover:text-red-800 self-end sm:self-center" onclick="removePreviewItem(this, '${taskId}')">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    const titleEl = row.querySelector('.text-sm.font-medium');
    if (titleEl) titleEl.textContent = file.name;

    const hint = previewArea.querySelector('p.text-xs.text-slate-500');
    if (hint) {
        previewArea.insertBefore(row, hint);
    } else {
        previewArea.appendChild(row);
    }
}

function removePreviewItem(btn, taskId) {
    const row = btn.closest('[data-file-key]');
    if (!row) return;

    const fileKey = row.dataset.fileKey;
    setStoredProofFiles(
        taskId,
        getStoredProofFiles(taskId).filter(file => proofFileKey(file) !== fileKey)
    );
    renderProofFilePreviews(taskId);
}

Object.keys(window.savedProofFiles || {}).forEach(function(taskId) {
    if ((window.savedProofFiles[taskId] || []).length) {
        renderProofFilePreviews(taskId);
    }
});

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
    const completionStorageKey = 'completed_list_{{ $submission->taskList->id }}:' + new Date().toISOString().slice(0, 10);

    if (!window.signaturePads) window.signaturePads = {};

    // Init per-task signature pads (only expanded cards; hidden canvases get a broken size)
    document.querySelectorAll('.task-card.is-expanded canvas[id^="signature-pad-task-"]').forEach(canvas => {
        ensureTaskSignaturePad(canvas);
    });

    initializeTaskAccordions();

    // Final signature pad (indien aanwezig)
    setupFinalSignaturePad();

    // Checklist persistence & forms
    initializeChecklists();
    syncFinalSubmissionForm(false);

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
        }, index * 40 + 80);
    });

    // Progress circle init
    const progressCircle = document.getElementById('submission-progress-circle');
    if (progressCircle) {
        const circumference = 2 * Math.PI * 40;
        const progressPercent = {{ $progressPercent }};
        const offset = circumference - (progressPercent / 100) * circumference;
        progressCircle.style.strokeDasharray = String(circumference);
        progressCircle.style.strokeDashoffset = String(circumference);
        setTimeout(() => {
            progressCircle.style.strokeDashoffset = String(offset);
        }, 500);
    }

    initializeListSwipe();
    initializeListJump();

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

function buildTaskFormData(form, taskId) {
    syncProofFilesToInput(taskId);

    const storedProofFiles = getStoredProofFiles(taskId);
    const formData = new FormData(form);

    if (storedProofFiles.length === 0) {
        return formData;
    }

    const fresh = new FormData();
    for (const [key, value] of formData.entries()) {
        if (key !== 'proof_files[]') {
            fresh.append(key, value);
        }
    }

    storedProofFiles.forEach(file => {
        fresh.append('proof_files[]', file, file.name);
    });

    return fresh;
}

// ===== Helpers & AJAX for forms =====

function validateTaskForm(form) {
    let isValid = true;
    const requiredProofType = form.dataset.requiredProofType || 'none';
    const taskId = form.id.replace('task-form-', '');
    const mainFileInput = form.querySelector('input[type="file"][name="proof_files[]"]');
    syncProofFilesToInput(taskId);
    const hasProofFiles = getStoredProofFiles(taskId).length > 0
        || getSavedProofFiles(taskId).length > 0
        || !!(mainFileInput && mainFileInput.files && mainFileInput.files.length > 0)
        || form.dataset.hasExistingProof === '1';

    const signatureCanvas = form.querySelector('canvas[id^="signature-pad-task-"]');
    if (signatureCanvas) {
        const key = 'task-' + signatureCanvas.id.replace('signature-pad-task-', '');
        const hidden = form.querySelector('input[name="digital_signature"]');

        if (window.signaturePads && window.signaturePads[key]) {
            if (window.signaturePads[key].isEmpty()) {
                if (form.dataset.hasSignature !== '1') {
                    showNotification('Een digitale handtekening is vereist voor deze taak.', 'error');
                    isValid = false;
                }
            } else if (hidden) {
                hidden.value = window.signaturePads[key].toDataURL();
            }
        }
    }

    const requiredFields = form.querySelectorAll('[required]');

    if (['photo', 'video', 'file'].includes(requiredProofType) && !hasProofFiles) {
        if (mainFileInput) {
            mainFileInput.classList.add('border-red-500');
        }
        if (requiredProofType === 'photo') {
            showNotification('Je hebt geen afbeelding toegevoegd aan de taak.', 'error');
        } else {
            showNotification('Bewijs is vereist voor deze taak.', 'error');
        }
        return false;
    }

    requiredFields.forEach(field => {
        field.classList.remove('border-red-500', 'border-red-300');

        if (field.type === 'file') {
            if (field.name === 'proof_files[]') {
                return;
            }
            if (field.files.length === 0) {
                field.classList.add('border-red-500');
                if (requiredProofType === 'photo') {
                    showNotification('Je hebt geen afbeelding toegevoegd aan de taak.', 'error');
                } else {
                    showNotification('Bewijs is vereist voor deze taak.', 'error');
                }
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
        if (isRequired && (status === 'completed' || status === 'approved')) completedRequired++;
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

// ✅ Sync final submission block (single submit button)
let finalSubmissionWasReady = null;

function syncFinalSubmissionForm(showCelebration = false) {
    try {
        const completedRequiredTasks = countCompletedRequiredTasks();
        const totalRequiredTasks = countTotalRequiredTasks();
        const ready = completedRequiredTasks >= totalRequiredTasks && totalRequiredTasks > 0;

        setFinalSubmissionReady(ready);

        if (showCelebration && ready && finalSubmissionWasReady === false) {
            showNotification('Alle verplichte taken zijn voltooid. Je kunt nu de checklist indienen.', 'success', 5000);
        }

        finalSubmissionWasReady = ready;
    } catch (e) {
        console.error('syncFinalSubmissionForm error:', e);
    }
}

function setFinalSubmissionReady(ready) {
    const finalSection = document.getElementById('final-submission-section');
    if (!finalSection) return;

    finalSection.dataset.ready = ready ? '1' : '0';

    const header = finalSection.querySelector('.bg-gradient-to-r');
    if (header) {
        header.className = ready
            ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100 px-4 sm:px-6 py-4 sm:py-6'
            : 'bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-100 px-4 sm:px-6 py-4 sm:py-6';

        const icon = header.querySelector('.w-9.h-9, .w-10.h-10');
        if (icon) {
            icon.className = ready
                ? 'w-9 h-9 sm:w-10 sm:h-10 bg-green-600 rounded-xl flex items-center justify-center flex-shrink-0'
                : 'w-9 h-9 sm:w-10 sm:h-10 bg-amber-500 rounded-xl flex items-center justify-center flex-shrink-0';
            icon.innerHTML = ready
                ? '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                : '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
        }

        const title = header.querySelector('h3');
        if (title) {
            title.textContent = ready ? 'Klaar om in te dienen!' : 'Checklist Indienen';
        }

        const description = header.querySelector('p');
        if (description) {
            description.textContent = ready
                ? 'Alle verplichte taken zijn voltooid. Je kunt nu de checklist indienen voor review.'
                : 'Voltooi eerst alle verplichte taken om de checklist in te kunnen dienen.';
            description.className = ready
                ? 'mt-1 text-sm sm:text-base text-gray-600'
                : 'mt-1 text-sm sm:text-base text-amber-700 font-medium';
        }
    }

    const sigPad = finalSection.querySelector('#signature-pad-final');
    const sigInput = finalSection.querySelector('#signature-input-final');
    const clearBtn = finalSection.querySelector('button[onclick="clearSignaturePadFinal()"]');
    const notes = finalSection.querySelector('textarea[name="notes"]');
    const submitBtn = finalSection.querySelector('#submit-checklist-btn');
    const submitHint = finalSection.querySelector('#submit-checklist-hint');

    if (sigPad) {
        sigPad.classList.toggle('opacity-50', !ready);
        sigPad.style.pointerEvents = ready ? 'auto' : 'none';
    }
    if (sigInput) {
        sigInput.required = !!ready && !!sigPad;
        if (!ready) {
            sigInput.value = '';
        }
    }
    if (clearBtn) {
        clearBtn.disabled = !ready;
        clearBtn.classList.toggle('opacity-50', !ready);
        clearBtn.classList.toggle('cursor-not-allowed', !ready);
    }
    if (notes) {
        notes.disabled = !ready;
        notes.classList.toggle('opacity-50', !ready);
        notes.classList.toggle('bg-gray-50', !ready);
        notes.placeholder = ready
            ? 'Eventuele aanvullende opmerkingen over deze checklist...'
            : 'Dit veld wordt beschikbaar nadat alle verplichte taken zijn voltooid.';
    }
    if (submitBtn) {
        submitBtn.disabled = !ready;
        submitBtn.className = ready
            ? 'w-full sm:w-auto inline-flex items-center justify-center px-7 sm:px-8 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105'
            : 'w-full sm:w-auto inline-flex items-center justify-center px-7 sm:px-8 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gray-300 cursor-not-allowed opacity-70';
    }
    if (submitHint) {
        submitHint.classList.toggle('hidden', ready);
    }

    if (ready) {
        setupFinalSignaturePad();
    }
}

function updateFinalSubmissionForm() {
    syncFinalSubmissionForm(true);
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

        const submitButton = form.querySelector('#submit-checklist-btn');
        if (!submitButton || submitButton.disabled) {
            showNotification('Voltooi eerst alle verplichte taken om de checklist in te dienen.', 'warning');
            return;
        }
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
                localStorage.setItem('completed_list_{{ $submission->taskList->id }}:' + new Date().toISOString().slice(0, 10), Date.now().toString());
            } catch (e) {
                console.warn('Kon localStorage niet schrijven:', e);
            }
            
            showNotification(data.message || 'Checklist succesvol ingediend!', 'success');
            setTimeout(() => {
                window.location.href = data.redirect_url || '/employee/dashboard';
            }, data.next_list ? 700 : 1200);
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
            setFinalSubmissionReady(true);
            if (submitButton) {
                submitButton.innerHTML = original;
            }
        });
    });
}

function updateTaskToCompleted(taskId, completedAt, proofFiles) {
    const form = document.querySelector(`#task-form-${taskId}`);
    const taskCard = form ? form.closest('.task-card') : null;

    if (Array.isArray(proofFiles)) {
        setSavedProofFiles(taskId, proofFiles);
    }

    if (taskCard) {
        taskCard.dataset.status = 'completed';
        taskCard.classList.add('task-completed');
    }

    if (taskCard) {
        const taskHeader = taskCard.querySelector('.task-header');
        if (taskHeader) {
            const indexEl = taskHeader.querySelector('.task-index');
            if (indexEl) {
                indexEl.classList.add('is-done');
                indexEl.innerHTML = `
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
            }

            const titleEl = taskHeader.querySelector('.task-title');
            if (titleEl) titleEl.classList.add('is-done');

            const descEl = taskHeader.querySelector('p.task-detail');
            if (descEl) {
                descEl.classList.remove('text-gray-600');
                descEl.classList.add('text-green-700');
            }
        }
    }

    if (form) {
        const savedCount = getSavedProofFiles(taskId).length;
        const hadNewProof = getStoredProofFiles(taskId).length > 0;
        if (form.dataset.hasExistingProof === '1' || hadNewProof || savedCount > 0) {
            form.dataset.hasExistingProof = '1';
        }
        const sigHidden = form.querySelector('input[name="digital_signature"]');
        if (sigHidden && sigHidden.value) {
            form.dataset.hasSignature = '1';
            sigHidden.removeAttribute('required');
        }
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.textContent = 'Wijzigingen opslaan';

        const formWrap = form.parentElement;
        if (formWrap && !formWrap.querySelector('.task-edit-hint')) {
            const hint = document.createElement('p');
            hint.className = 'task-edit-hint text-sm text-slate-600 mb-4';
            hint.textContent = 'Deze taak is afgerond. Je kunt de gegevens nog aanpassen tot je de lijst indient.';
            formWrap.insertBefore(hint, form);
        }
    }

    if (typeof setStoredProofFiles === 'function') {
        setStoredProofFiles(taskId, []);
    }
    renderProofFilePreviews(taskId);

    if (taskCard) {
        const quickBtn = taskCard.querySelector('.task-quick-complete');
        if (quickBtn) quickBtn.remove();
        setTaskExpanded(taskCard, false);
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
            if (card.dataset.status === 'completed' || card.dataset.status === 'approved') {
                completed++;
            }
        });
        const total = cards.length;
        const percent = total > 0 ? Math.round((completed / total) * 100) : 0;

        const progressCircle = document.getElementById('submission-progress-circle');
        const progressCount = document.getElementById('submission-progress-count');

        if (progressCircle) {
            const circumference = 2 * Math.PI * 40;
            const offset = circumference * (1 - (percent / 100));
            progressCircle.style.strokeDasharray = String(circumference);
            progressCircle.style.strokeDashoffset = String(offset);

            const color = percent >= 100 ? '#22c55e' : (percent > 0 ? '#3b82f6' : '#ef4444');
            progressCircle.setAttribute('stroke', color);
        }
        if (progressCount) {
            progressCount.textContent = `${completed}/${total} taken`;
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
            if (form.dataset.submitting === '1') {
                return;
            }
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
            form.dataset.submitting = '1';
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Bezig...
            `;
            showLoadingOverlay();

            const formData = buildTaskFormData(form, taskId);
            const submitTaskForm = async (allowRetry = true) => {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (response.ok) {
                    return response.json();
                }

                if (response.status === 422) {
                    const data = await response.json();
                    throw new ValidationError(data.message || 'Validation failed', data.errors);
                }

                if (response.status === 419) {
                    // Refresh token once and retry transparently.
                    const refreshResponse = await fetch('/refresh-csrf', {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json' }
                    });
                    if (refreshResponse.ok) {
                        const refreshData = await refreshResponse.json();
                        if (refreshData && refreshData.token) {
                            const meta = document.querySelector('meta[name="csrf-token"]');
                            if (meta) meta.setAttribute('content', refreshData.token);
                        }
                    }

                    if (allowRetry) {
                        return submitTaskForm(false);
                    }
                }

                // Temporary/transient errors: retry once.
                if (allowRetry && (response.status >= 500 || response.status === 0)) {
                    await new Promise(resolve => setTimeout(resolve, 350));
                    return submitTaskForm(false);
                }

                if (response.status === 403) throw new Error('Toegang geweigerd. Ververs de pagina en probeer opnieuw.');
                if (response.status >= 500) throw new Error('Serverfout opgetreden. Probeer het opnieuw.');
                throw new Error(`Verzoek mislukt met status ${response.status}`);
            };

            submitTaskForm()
            .then(data => {
                if (!data.success) throw new Error(data.message || 'Onbekende fout opgetreden');

                updateTaskToCompleted(taskId, data.completed_at, data.proof_files);
                localStorage.removeItem(key);
                updateProgressIndicator();
                showNotification('Opgeslagen.', 'success');
                updateFinalSubmissionForm();
                hideLoadingOverlay();

                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Wijzigingen opslaan';
                }
                form.dataset.submitting = '0';
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
                form.dataset.submitting = '0';
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

function ensureTaskSignaturePad(canvas) {
    if (!canvas || typeof SignaturePad === 'undefined') return;
    if (!window.signaturePads) window.signaturePads = {};
    const key = 'task-' + canvas.id.replace('signature-pad-task-', '');
    if (window.signaturePads[key]) return;
    window.signaturePads[key] = new SignaturePad(canvas, {
        backgroundColor: 'rgba(255,255,255,0)',
        penColor: 'rgb(0, 0, 0)',
        minWidth: 1,
        maxWidth: 3
    });
}

function setTaskExpanded(card, expanded) {
    if (!card) return;
    const body = card.querySelector('.task-body');
    const toggle = card.querySelector('.task-toggle');
    const toggleIcon = card.querySelector('.task-toggle-icon');

    card.dataset.expanded = expanded ? '1' : '0';
    card.classList.toggle('is-expanded', expanded);

    if (body) body.classList.toggle('hidden', !expanded);

    card.querySelectorAll('.task-detail').forEach(el => {
        el.classList.toggle('hidden', !expanded);
    });

    if (toggle) {
        toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        toggle.title = expanded ? 'Taak inklappen' : 'Taak uitklappen';
    }
    if (toggleIcon) {
        toggleIcon.classList.toggle('rotate-180', expanded);
    }

    if (expanded) {
        requestAnimationFrame(() => {
            card.querySelectorAll('canvas[id^="signature-pad-task-"]').forEach(ensureTaskSignaturePad);
        });
    }
}

function toggleTaskCard(card) {
    if (!card) return;
    setTaskExpanded(card, card.dataset.expanded !== '1');
}

function initializeTaskAccordions() {
    document.querySelectorAll('.task-header').forEach(header => {
        header.addEventListener('click', function(e) {
            if (e.target.closest('button, a, input, textarea, label, canvas')) return;
            toggleTaskCard(header.closest('.task-card'));
        });
    });
}

function quickCompleteTask(taskId) {
    const form = document.getElementById('task-form-' + taskId);
    if (!form) return;
    if (typeof form.requestSubmit === 'function') {
        form.requestSubmit();
        return;
    }
    form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
}

function goToNeighborList(url) {
    if (!url) return;
    window.location.href = url;
}

function initializeListJump() {
    const input = document.getElementById('list-position-input');
    if (!input || input.disabled) return;

    const current = parseInt(input.dataset.current, 10);
    const total = parseInt(input.dataset.total, 10);
    let urls = [];
    try {
        urls = JSON.parse(input.dataset.urls || '[]');
    } catch (e) {
        urls = [];
    }

    const reset = () => {
        input.value = String(current);
    };

    const go = () => {
        const value = parseInt(String(input.value).replace(/[^\d]/g, ''), 10);
        if (!Number.isInteger(value) || value < 1 || value > total || !urls[value - 1]) {
            reset();
            return;
        }
        if (value === current) {
            reset();
            return;
        }
        goToNeighborList(urls[value - 1]);
    };

    input.addEventListener('focus', function() {
        input.select();
    });
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            go();
        }
        if (e.key === 'Escape') {
            reset();
            input.blur();
        }
    });
    input.addEventListener('change', go);
}

function initializeListSwipe() {
    const prevBtn = document.getElementById('hero-nav-prev');
    const nextBtn = document.getElementById('hero-nav-next');
    const prevUrl = prevBtn && !prevBtn.disabled ? prevBtn.dataset.url : '';
    const nextUrl = nextBtn && !nextBtn.disabled ? nextBtn.dataset.url : '';

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            goToNeighborList(prevUrl);
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            goToNeighborList(nextUrl);
        });
    }

    if (!prevUrl && !nextUrl) return;

    let startX = 0;
    let startY = 0;
    let tracking = false;

    document.addEventListener('touchstart', function(e) {
        if (e.touches.length !== 1) return;
        if (e.target.closest('input, textarea, select, canvas, button, a, label')) return;
        tracking = true;
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
    }, { passive: true });

    document.addEventListener('touchend', function(e) {
        if (!tracking) return;
        tracking = false;
        const touch = e.changedTouches[0];
        const dx = touch.clientX - startX;
        const dy = touch.clientY - startY;
        if (Math.abs(dx) < 70 || Math.abs(dx) < Math.abs(dy) * 1.2) return;
        if (dx > 0) {
            goToNeighborList(prevUrl);
            return;
        }
        goToNeighborList(nextUrl);
    }, { passive: true });
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

.hero-list-nav {
    display: grid;
    grid-template-columns: 2.5rem minmax(0, 1fr) 2.5rem;
    align-items: center;
    justify-items: center;
    column-gap: 0.75rem;
    width: 100%;
}
.hero-list-nav-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
    min-width: 0;
    width: 100%;
    text-align: center;
}
.hero-list-title {
    margin: 0;
    min-width: 0;
    width: 100%;
    text-align: center;
    font-size: 1.25rem;
    line-height: 1.3;
    font-weight: 700;
    color: #111827;
    overflow-wrap: anywhere;
}
@media (min-width: 640px) {
    .hero-list-title { font-size: 1.5rem; }
}
@media (min-width: 1024px) {
    .hero-list-title { font-size: 1.875rem; }
}

.hero-nav-btn {
    width: 2.5rem;
    height: 2.5rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border-radius: 0.75rem;
    border: 1px solid #e5e7eb;
    background: #fff;
    color: #6b7280;
    cursor: pointer;
    transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
}
.hero-nav-btn:hover:not(:disabled) {
    background: #f8fafc;
    color: #111827;
    border-color: #cbd5e1;
}
.hero-nav-btn:disabled {
    color: #d1d5db;
    border-color: #f3f4f6;
    background: #f9fafb;
    cursor: not-allowed;
}

.list-jump {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.15rem;
    flex-shrink: 0;
    color: #6b7280;
    font-size: 0.875rem;
    font-weight: 600;
    line-height: 1;
}
.list-jump-input {
    width: 2rem;
    height: 1.75rem;
    padding: 0;
    text-align: center;
    font-size: 0.8125rem;
    font-weight: 700;
    color: #111827;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    background: #fff;
    -moz-appearance: textfield;
    appearance: textfield;
}
.list-jump-input:focus {
    outline: none;
    border-color: #93c5fd;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}
.list-jump-input:disabled {
    color: #9ca3af;
    background: #f9fafb;
    cursor: default;
}
.list-jump-input::-webkit-outer-spin-button,
.list-jump-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
.list-jump-total {
    min-width: 1.25rem;
}

.task-card {
    font-family: inherit;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.task-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
}
.task-card.is-expanded {
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
}
.task-card.task-completed {
    border-color: #bbf7d0;
}
.task-card.task-completed .task-header {
    background: #f8fffb;
}

.task-completed-banner {
    display: grid;
    grid-template-columns: 2.5rem minmax(0, 1fr) auto;
    column-gap: 12px;
    padding: 0 20px 16px;
    background: #ecfdf5;
}
.task-completed-body {
    grid-column: 2 / -1;
    min-width: 0;
}
.task-completed-text {
    margin: 0;
    font-size: 0.875rem;
    line-height: 1.5;
    color: #065f46;
}

.task-header {
    display: grid;
    grid-template-columns: auto minmax(0, 1fr) auto;
    align-items: center;
    column-gap: 12px;
    row-gap: 8px;
    padding: 16px 20px;
    cursor: pointer;
    user-select: none;
    background: #fff;
}
.task-card.is-expanded .task-header {
    background: linear-gradient(to right, #eff6ff, #eef2ff);
    border-bottom: 1px solid #f3f4f6;
}
.task-card.task-completed.is-expanded .task-header {
    background: linear-gradient(to right, #f0fdf4, #ecfdf5);
}

.task-index {
    grid-column: 1;
    grid-row: 1;
    flex-shrink: 0;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 0.75rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: inherit;
    font-size: 0.875rem;
    font-weight: 700;
    background: #dbeafe;
    color: #1d4ed8;
    line-height: 1;
}
.task-index.is-done {
    background: #22c55e;
    color: #fff;
}

.task-title-row {
    grid-column: 2;
    grid-row: 1;
    display: flex;
    align-items: center;
    gap: 6px;
    min-width: 0;
}
.task-title {
    margin: 0;
    min-width: 0;
    font-family: inherit;
    font-size: 1.125rem;
    font-weight: 700;
    color: #111827;
    line-height: 1.35;
    overflow-wrap: anywhere;
}
.task-required-mark {
    color: #dc2626;
    font-weight: 700;
    margin-left: 0.15em;
}
.task-title.is-done {
    color: #14532d;
}

.task-chip {
    display: inline-flex;
    align-items: center;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-family: inherit;
    font-size: 0.75rem;
    font-weight: 500;
    line-height: 1.25;
    white-space: nowrap;
}
.task-chip-required { background: #fef2f2; color: #b91c1c; }
.task-chip-redo { background: #fff7ed; color: #c2410c; }

.task-detail {
    grid-column: 2 / -1;
    margin: 0;
    font-family: inherit;
    font-size: 0.875rem;
    line-height: 1.5;
}
.task-instructions-label {
    margin: 0 0 2px;
    font-size: 0.75rem;
    font-weight: 600;
    color: #334155;
}
.task-instructions-text {
    margin: 0;
    color: #475569;
    white-space: pre-line;
}

.task-actions {
    grid-column: 3;
    grid-row: 1;
    display: flex;
    align-items: center;
    gap: 8px;
}
.task-quick-complete,
.task-toggle {
    width: 2.5rem;
    height: 2.5rem;
    padding: 0;
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    border-radius: 0.75rem;
    cursor: pointer;
    flex-shrink: 0;
    transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
}
.task-quick-complete {
    visibility: visible !important;
    border: 1px solid #86efac;
    background: #16a34a;
    color: #fff;
}
.task-card.is-expanded .task-quick-complete {
    display: none !important;
}
.task-quick-complete:hover {
    background: #15803d;
    border-color: #22c55e;
}
.task-toggle {
    border: 1px solid #e5e7eb;
    background: #fff;
    color: #6b7280;
}
.task-toggle:hover {
    background: #f8fafc;
    color: #111827;
    border-color: #cbd5e1;
}
.task-toggle-icon { transition: transform 0.2s ease; }

@media (min-width: 640px) {
    .task-title { font-size: 1.25rem; }
    .task-detail { font-size: 1rem; }
}

@media (max-width: 639px) {
    .task-header {
        padding: 14px;
        column-gap: 10px;
    }
    .task-completed-banner {
        padding: 0 14px 14px;
        column-gap: 10px;
        grid-template-columns: 2.25rem minmax(0, 1fr) auto;
    }
    .task-card.is-expanded .task-header {
        padding: 14px;
    }
    .task-index,
    .task-quick-complete,
    .task-toggle {
        width: 2.25rem;
        height: 2.25rem;
    }
    .task-title { font-size: 1rem; }
    .task-actions { gap: 6px; }
}

button[type="submit"], a[href*="dashboard"] {
    position: relative;
    overflow: hidden;
}

.progress-circle { transition: stroke-dashoffset 1s ease-in-out, stroke 0.3s ease; }

input:focus, textarea:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
@endsection

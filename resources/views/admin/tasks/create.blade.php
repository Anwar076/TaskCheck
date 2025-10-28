@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 p-4 md:p-6 lg:p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Enhanced Header -->
        <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-6 py-8 md:px-8 relative overflow-hidden">
                <!-- Decorative elements -->
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-12 -translate-x-12"></div>
                
                <div class="relative flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg border border-white/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Create New Task</h1>
                            <div class="flex flex-wrap items-center gap-2 mt-3">
                                <span class="text-blue-100 text-sm font-medium">Adding to list:</span>
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-white/20 text-white backdrop-blur-sm border border-white/30">
                                    {{ $list->title }}
                                </span>
                                @if(isset($selectedWeekday))
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-amber-400 to-orange-400 text-orange-900">
                                        {{ ucfirst($selectedWeekday) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.lists.show', $list) }}" class="group inline-flex items-center px-5 py-2.5 border-2 border-white/30 text-sm font-semibold rounded-xl text-white bg-white/10 backdrop-blur-sm hover:bg-white/20 hover:border-white/50 transition-all duration-300 hover:scale-105">
                        <svg class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                </div>
            </div>
        </div>

        <!-- Enhanced Form Container -->
        <div class="bg-white shadow-xl rounded-2xl border border-gray-100 overflow-hidden backdrop-blur-sm">
            <form method="POST" action="{{ route('admin.lists.tasks.store', $list) }}" class="divide-y divide-gray-100">
                @csrf
                
                @if(isset($targetList) && $targetList->id !== $list->id)
                    <input type="hidden" name="target_list_id" value="{{ $targetList->id }}">
                @endif

                <!-- Basic Information Section -->
                <div class="p-6 md:p-8 bg-gradient-to-r from-blue-50/50 to-indigo-50/50">
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Task Information</h3>
                        </div>
                        <p class="text-gray-600 ml-11">Enter the basic details for your new task</p>
                    </div>
                    
                    <div class="space-y-8">
                        <!-- Title Field -->
                        <div class="group">
                            <label for="title" class="block text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                Task Title
                            </label>
                            <div class="relative">
                                <input type="text" name="title" id="title" required 
                                       class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm bg-white/70 backdrop-blur-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 group-hover:border-gray-300 group-hover:shadow-md" 
                                       value="{{ old('title') }}" placeholder="e.g., Empty all trash bins">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('title')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Description Field -->
                        <div class="group">
                            <label for="description" class="block text-sm font-semibold text-gray-900 mb-3">Description</label>
                            <div class="relative">
                                <textarea name="description" id="description" rows="3" 
                                          class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm bg-white/70 backdrop-blur-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 resize-none group-hover:border-gray-300 group-hover:shadow-md" 
                                          placeholder="Provide a clear description of what needs to be done...">{{ old('description') }}</textarea>
                                <div class="absolute top-3 right-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                </div>
                            </div>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Instructions Field -->
                        <div class="group">
                            <label for="instructions" class="block text-sm font-semibold text-gray-900 mb-3">Detailed Instructions</label>
                            <div class="relative">
                                <textarea name="instructions" id="instructions" rows="4" 
                                          class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm bg-white/70 backdrop-blur-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 resize-none group-hover:border-gray-300 group-hover:shadow-md" 
                                          placeholder="Provide step-by-step instructions on how to complete this task...">{{ old('instructions') }}</textarea>
                                <div class="absolute top-3 right-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-3 text-sm text-gray-600 bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-start">
                                <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                These instructions will be shown to employees when completing the task.
                            </p>
                            @error('instructions')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                @if(in_array($list->schedule_type, ['daily', 'weekly', 'custom']))
                <!-- Day Selection Section -->
                <div class="p-6 md:p-8 bg-gradient-to-r from-indigo-50/50 to-purple-50/50">
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Schedule Assignment</h3>
                        </div>
                        <p class="text-gray-600 ml-11">Choose which days this task should be available (optional)</p>
                    </div>
                    
                    <!-- Schedule Info -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-xl p-6 mb-8 shadow-sm">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-blue-900 mb-2 text-lg">Current Schedule: {{ ucfirst($list->schedule_type) }}</h4>
                                <p class="text-blue-800">
                                    @if($list->schedule_type === 'daily')
                                        This list appears every day. You can assign this task to specific days or leave it as a general task.
                                    @elseif($list->schedule_type === 'weekly')
                                        This list appears on: <span class="font-bold bg-blue-200 px-2 py-1 rounded-lg">{{ implode(', ', array_map('ucfirst', $list->getShowOnDays())) }}</span>
                                    @else
                                        Custom schedule configuration is active.
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <!-- Day Selection Grid -->
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                            @php
                                $weekdays = [
                                    'monday' => 'Monday',
                                    'tuesday' => 'Tuesday', 
                                    'wednesday' => 'Wednesday',
                                    'thursday' => 'Thursday',
                                    'friday' => 'Friday',
                                    'saturday' => 'Saturday',
                                    'sunday' => 'Sunday'
                                ];
                                $selectedDays = old('weekdays', isset($selectedWeekday) ? [$selectedWeekday] : []);
                                $listAvailableDays = $list->getShowOnDays();
                            @endphp
                            @foreach($weekdays as $dayKey => $dayName)
                                @php
                                    $isAvailableOnList = in_array($dayKey, $listAvailableDays);
                                    $isSelectedForTask = in_array($dayKey, $selectedDays);
                                @endphp
                                <div class="relative group">
                                    <label class="flex flex-col items-center p-4 border-2 rounded-2xl transition-all duration-200 cursor-pointer
                                        @if(!$isAvailableOnList) 
                                            border-gray-300 bg-gray-100 cursor-not-allowed opacity-50
                                        @elseif($isSelectedForTask) 
                                            border-green-500 bg-green-500 text-white shadow-md
                                        @else 
                                            border-gray-300 bg-white hover:border-green-400 hover:bg-green-50
                                        @endif">
                                        @if($isAvailableOnList)
                                            <input type="checkbox" name="weekdays[]" value="{{ $dayKey }}" 
                                                   class="hidden day-checkbox" 
                                                   {{ $isSelectedForTask ? 'checked' : '' }}>
                                        @endif
                                        
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-2 text-lg font-bold
                                            @if(!$isAvailableOnList)
                                                bg-gray-300 text-gray-500
                                            @elseif($isSelectedForTask)
                                                bg-green-600 text-white
                                            @else
                                                bg-gray-200 text-gray-700 group-hover:bg-green-200 group-hover:text-green-800
                                            @endif">
                                            {{ substr($dayName, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-semibold
                                            @if(!$isAvailableOnList)
                                                text-gray-400
                                            @elseif($isSelectedForTask)
                                                text-white
                                            @else
                                                text-gray-700 group-hover:text-green-700
                                            @endif">{{ $dayName }}</span>
                                        @if(!$isAvailableOnList)
                                            <span class="text-xs text-gray-400 mt-1">Niet beschikbaar</span>
                                        @endif
                                    </label>
                                    @if($isSelectedForTask)
                                        <div class="absolute -top-2 -right-2 w-6 h-6 bg-white border-2 border-green-500 rounded-full flex items-center justify-center shadow-sm">
                                            <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <!-- Help Information -->
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                            <div class="flex items-start space-x-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-blue-900 mb-3">Hoe dag toewijzing werkt</h5>
                                    <ul class="text-sm text-blue-800 space-y-2">
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                            <span><strong>Groene dagen:</strong> Taak verschijnt alleen op geselecteerde dagen</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-gray-400 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                            <span><strong>Geen dagen geselecteerd:</strong> Taak verschijnt elke dag dat deze lijst actief is</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="w-2 h-2 bg-gray-300 rounded-full mr-3 mt-2 flex-shrink-0"></span>
                                            <span><strong>Grijze dagen:</strong> Lijst is niet beschikbaar op deze dagen</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Task Settings Section -->
                <div class="p-6 md:p-8 bg-gradient-to-r from-purple-50/50 to-pink-50/50">
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Task Configuration</h3>
                        </div>
                        <p class="text-gray-600 ml-11">Configure proof requirements and task position</p>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Proof Type Selection -->
                        <div class="group">
                            <label for="required_proof_type" class="block text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                <span class="w-2 h-2 bg-red-500 rounded-full mr-2"></span>
                                Required Proof Type
                            </label>
                            <div class="relative">
                                <select name="required_proof_type" id="required_proof_type" required 
                                        class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm bg-white/70 backdrop-blur-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 group-hover:border-gray-300 group-hover:shadow-md appearance-none">
                                    <option value="none" {{ old('required_proof_type', 'none') === 'none' ? 'selected' : '' }}>🚫 No proof required</option>
                                    <option value="photo" {{ old('required_proof_type') === 'photo' ? 'selected' : '' }}>📸 Photo required</option>
                                    <option value="video" {{ old('required_proof_type') === 'video' ? 'selected' : '' }}>🎥 Video required</option>
                                    <option value="text" {{ old('required_proof_type') === 'text' ? 'selected' : '' }}>📝 Text note required</option>
                                    <option value="file" {{ old('required_proof_type') === 'file' ? 'selected' : '' }}>📎 File upload required</option>
                                    <option value="any" {{ old('required_proof_type') === 'any' ? 'selected' : '' }}>✨ Any proof type</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-3 text-sm text-gray-600 bg-purple-50 border border-purple-200 rounded-lg p-3 flex items-start">
                                <svg class="w-4 h-4 text-purple-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Choose what type of proof employees must provide to complete this task.
                            </p>
                            @error('required_proof_type')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Order Position -->
                        <div class="group">
                            <label for="order_index" class="block text-sm font-semibold text-gray-900 mb-3">Order Position</label>
                            <div class="relative">
                                <input type="number" name="order_index" id="order_index" min="1" 
                                       class="block w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm bg-white/70 backdrop-blur-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 group-hover:border-gray-300 group-hover:shadow-md" 
                                       value="{{ old('order_index', ($list->tasks->count() ?? 0) + 1) }}" placeholder="1">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-3 text-sm text-gray-600 bg-blue-50 border border-blue-200 rounded-lg p-3 flex items-start">
                                <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                                The order in which this task should appear in the list.
                            </p>
                            @error('order_index')
                                <p class="mt-2 text-sm text-red-600 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Task Options Section -->
                <div class="p-6 md:p-8 bg-gradient-to-r from-emerald-50/50 to-teal-50/50">
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Task Options</h3>
                        </div>
                        <p class="text-gray-600 ml-11">Configure additional task requirements</p>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Required Task Option -->
                        <div class="relative bg-white/80 backdrop-blur-sm border-2 border-gray-200 rounded-xl p-5 hover:border-emerald-300 hover:shadow-md transition-all duration-300">
                            <div class="flex items-start space-x-4">
                                <div class="flex items-center h-6 mt-1">
                                    <input type="checkbox" name="is_required" id="is_required" value="1" {{ old('is_required', true) ? 'checked' : '' }} 
                                           class="h-5 w-5 text-emerald-600 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-colors duration-200">
                                </div>
                                <div class="flex-1">
                                    <label for="is_required" class="font-bold text-gray-900 text-lg cursor-pointer flex items-center">
                                        ⚡ Required task
                                    </label>
                                    <p class="text-gray-600 mt-2">This task must be completed before the employee can submit their work.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Digital Signature Option -->
                        <div class="relative bg-white/80 backdrop-blur-sm border-2 border-gray-200 rounded-xl p-5 hover:border-emerald-300 hover:shadow-md transition-all duration-300">
                            <div class="flex items-start space-x-4">
                                <div class="flex items-center h-6 mt-1">
                                    <input type="checkbox" name="requires_signature" id="requires_signature" value="1" {{ old('requires_signature') ? 'checked' : '' }} 
                                           class="h-5 w-5 text-emerald-600 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-colors duration-200">
                                </div>
                                <div class="flex-1">
                                    <label for="requires_signature" class="font-bold text-gray-900 text-lg cursor-pointer flex items-center">
                                        ✍️ Requires digital signature
                                    </label>
                                    <p class="text-gray-600 mt-2">Employee must provide a digital signature when completing this task.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Checklist Section -->
                <div class="p-6 md:p-8 bg-gradient-to-r from-cyan-50/50 to-blue-50/50">
                    <div class="mb-8">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Checklist Items</h3>
                        </div>
                        <p class="text-gray-600 ml-11">Add specific items that employees must check off (optional)</p>
                    </div>
                    
                    <div class="space-y-6">
                        <div id="checklist-container" class="space-y-4 min-h-[60px] bg-white/50 backdrop-blur-sm rounded-xl border-2 border-dashed border-gray-300 p-4">
                            <!-- Checklist items will be added here dynamically -->
                            <div class="text-center text-gray-500 py-8" id="empty-checklist-message">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                <p class="text-sm">No checklist items added yet. Click the button below to add your first item!</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-center">
                            <button type="button" id="add-checklist-item" class="group inline-flex items-center px-6 py-3 border-2 border-transparent text-base font-semibold rounded-xl text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5 mr-3 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                ✨ Add Checklist Item
                            </button>
                        </div>
                    </div>
                </div>



                <!-- Submit Section -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 px-6 py-8 md:px-8 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('admin.lists.show', $list) }}" 
                           class="group inline-flex items-center justify-center px-6 py-3 border-2 border-gray-300 text-base font-semibold rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-300 hover:shadow-md">
                            <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" 
                                class="group inline-flex items-center justify-center px-8 py-3 border-2 border-transparent text-base font-bold rounded-xl text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 hover:from-blue-700 hover:via-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-3 group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            🚀 Create Task
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
// Day Selection Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle day checkbox styling and functionality
    const dayCheckboxes = document.querySelectorAll('.day-checkbox');
    
    dayCheckboxes.forEach(checkbox => {
        const label = checkbox.parentElement;
        
        function updateStyle() {
            const icon = label.querySelector('div');
            const text = label.querySelector('span:last-child');
            
            if (checkbox.checked) {
                // Selected state - green
                label.classList.remove('border-gray-300', 'bg-white', 'hover:border-green-400', 'hover:bg-green-50');
                label.classList.add('border-green-500', 'bg-green-500', 'text-white', 'shadow-md');
                
                icon.classList.remove('bg-gray-200', 'text-gray-700', 'group-hover:bg-green-200', 'group-hover:text-green-800');
                icon.classList.add('bg-green-600', 'text-white');
                
                text.classList.remove('text-gray-700', 'group-hover:text-green-700');
                text.classList.add('text-white');
            } else {
                // Unselected state - gray/white with green hover
                label.classList.remove('border-green-500', 'bg-green-500', 'text-white', 'shadow-md');
                if (!label.classList.contains('cursor-not-allowed')) {
                    label.classList.add('border-gray-300', 'bg-white', 'hover:border-green-400', 'hover:bg-green-50');
                    
                    icon.classList.remove('bg-green-600', 'text-white');
                    icon.classList.add('bg-gray-200', 'text-gray-700', 'group-hover:bg-green-200', 'group-hover:text-green-800');
                    
                    text.classList.remove('text-white');
                    text.classList.add('text-gray-700', 'group-hover:text-green-700');
                }
            }
        }
        
        // Initial state
        updateStyle();
        
        // Add click event to label (only for available days)
        if (!label.classList.contains('cursor-not-allowed')) {
            label.addEventListener('click', function(e) {
                e.preventDefault();
                checkbox.checked = !checkbox.checked;
                updateStyle();
            });
            
            // Add change event to checkbox
            checkbox.addEventListener('change', updateStyle);
        }
    });
    
    // Form validation for weekly structure (optional - no days selected = general task)
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const weekdaysCheckboxes = document.querySelectorAll('input[name="weekdays[]"]:checked');
            // No validation needed - if no days selected, it becomes a general task
            console.log('Selected days:', weekdaysCheckboxes.length);
            
            // Debug: Log all form data
            const formData = new FormData(form);
            console.log('Form data:');
            for (let [key, value] of formData.entries()) {
                console.log(key, value);
            }
        });
    }
    
    // Checklist Management
    let checklistItemCount = 0;
    const checklistContainer = document.getElementById('checklist-container');
    const addChecklistBtn = document.getElementById('add-checklist-item');
    
        function createChecklistItem(value = '') {
        checklistItemCount++;
        const itemId = `checklist-item-${checklistItemCount}`;
        
        // Hide empty message if it exists
        const emptyMessage = document.getElementById('empty-checklist-message');
        if (emptyMessage) {
            emptyMessage.style.display = 'none';
        }
        
        const itemDiv = document.createElement('div');
        itemDiv.className = 'flex items-center space-x-4 checklist-item bg-white/80 backdrop-blur-sm border-2 border-gray-200 rounded-xl p-4 hover:border-cyan-300 hover:shadow-md transition-all duration-300 transform hover:scale-[1.02]';
        itemDiv.id = itemId;
        
        itemDiv.innerHTML = `
            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-r from-cyan-500 to-blue-500 rounded-xl flex items-center justify-center shadow-sm">
                <span class="text-white font-bold text-sm">${checklistItemCount}</span>
            </div>
            <input type="text" 
                   name="checklist_items[]" 
                   class="flex-1 px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm bg-white/70 backdrop-blur-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-300" 
                   placeholder="e.g., Check equipment condition..." 
                   value="${value}">
            <button type="button" 
                    onclick="removeChecklistItem('${itemId}')" 
                    class="group flex-shrink-0 p-3 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-xl transition-all duration-300 transform hover:scale-110">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        checklistContainer.appendChild(itemDiv);
        
        // Focus on the newly added input
        const input = itemDiv.querySelector('input');
        input.focus();
    }    window.removeChecklistItem = function(itemId) {
        const item = document.getElementById(itemId);
        if (item) {
            // Add exit animation
            item.style.transform = 'scale(0.8)';
            item.style.opacity = '0';
            
            setTimeout(() => {
                item.remove();
                
                // Renumber remaining items
                const items = document.querySelectorAll('.checklist-item');
                items.forEach((item, index) => {
                    const numberSpan = item.querySelector('.text-white');
                    if (numberSpan) {
                        numberSpan.textContent = index + 1;
                    }
                });
                
                // Show empty message if no items left
                if (items.length === 0) {
                    const emptyMessage = document.getElementById('empty-checklist-message');
                    if (emptyMessage) {
                        emptyMessage.style.display = 'block';
                    }
                }
            }, 300);
        }
    };
    
    addChecklistBtn.addEventListener('click', function() {
        createChecklistItem();
    });
    
    // Load old values if validation fails
    @if(old('checklist_items'))
        @foreach(old('checklist_items') as $item)
            createChecklistItem(@json($item));
        @endforeach
    @endif
});
</script>

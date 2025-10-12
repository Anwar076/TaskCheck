@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 p-6">
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Enhanced Page Header -->
        <div class="bg-white/90 backdrop-blur-sm shadow-2xl rounded-3xl border border-white/30 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-8 py-6 border-b border-slate-200/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-slate-900">Add New Task</h1>
                            <p class="text-slate-600 font-medium mt-1">
                                Add a new task to "{{ $list->title }}"
                                @if(isset($selectedWeekday))
                                    <span class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-semibold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200 ml-3">
                                        {{ ucfirst($selectedWeekday) }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('admin.lists.show', $list) }}" class="group inline-flex items-center px-6 py-3 border-2 border-slate-300 text-base font-semibold rounded-2xl shadow-lg text-slate-700 bg-white hover:bg-gradient-to-r hover:from-slate-50 hover:to-blue-50 hover:border-slate-400 transition-all duration-300 hover:scale-105">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel
                    </a>
                </div>
            </div>
        </div>

        <!-- Enhanced Form Container -->
        <div class="bg-white/90 backdrop-blur-sm shadow-2xl rounded-3xl border border-white/30 overflow-hidden">
            <form method="POST" action="{{ route('admin.lists.tasks.store', $list) }}" class="space-y-8 p-8">
                @csrf
                
                @if(isset($targetList) && $targetList->id !== $list->id)
                    <input type="hidden" name="target_list_id" value="{{ $targetList->id }}">
                @endif

                <!-- Basic Information Section -->
                <div class="bg-gradient-to-r from-slate-50 to-blue-50 rounded-3xl p-6 border border-slate-200/50">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        Basic Information
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label for="title" class="block text-sm font-bold text-slate-700 mb-3">Task Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" required 
                                   class="block w-full px-6 py-4 border-2 border-slate-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-lg font-medium bg-white" 
                                   value="{{ old('title') }}" placeholder="e.g., Empty all trash bins">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="description" class="block text-sm font-bold text-slate-700 mb-3">Description</label>
                            <textarea name="description" id="description" rows="3" 
                                      class="block w-full px-6 py-4 border-2 border-slate-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-lg font-medium bg-white" 
                                      placeholder="Provide a clear description of what needs to be done...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="instructions" class="block text-sm font-bold text-slate-700 mb-3">Detailed Instructions</label>
                            <textarea name="instructions" id="instructions" rows="4" 
                                      class="block w-full px-6 py-4 border-2 border-slate-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-lg font-medium bg-white" 
                                      placeholder="Provide step-by-step instructions on how to complete this task...">{{ old('instructions') }}</textarea>
                            <p class="mt-2 text-sm text-slate-600 font-medium">These instructions will be shown to employees when they're completing the task.</p>
                            @error('instructions')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                @if($list->hasWeeklyStructure())
                <!-- Day Selection Section -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-6 border border-blue-200/50">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        Select Days for This Task
                    </h3>
                    
                    <div class="mb-4">
                        <p class="text-slate-600 font-medium mb-4">Choose which days of the week this task should be available for:</p>
                        <div class="grid grid-cols-7 gap-3">
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
                            @endphp
                            @foreach($weekdays as $dayKey => $dayName)
                            <div class="group relative">
                                <label class="flex flex-col items-center justify-center p-4 border-2 border-slate-200 rounded-2xl cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-all duration-200 {{ in_array($dayKey, $selectedDays) ? 'border-blue-500 bg-blue-50' : '' }}">
                                    <input type="checkbox" name="weekdays[]" value="{{ $dayKey }}" 
                                           class="hidden day-checkbox" 
                                           {{ in_array($dayKey, $selectedDays) ? 'checked' : '' }}>
                                    <div class="w-8 h-8 bg-gradient-to-br from-slate-400 to-slate-500 rounded-xl flex items-center justify-center mb-2 group-hover:from-blue-400 group-hover:to-blue-500 transition-all duration-200 {{ in_array($dayKey, $selectedDays) ? 'from-blue-400 to-blue-500' : '' }}">
                                        <span class="text-white font-bold text-xs">{{ substr($dayName, 0, 1) }}</span>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-700 group-hover:text-blue-700 transition-colors duration-200 {{ in_array($dayKey, $selectedDays) ? 'text-blue-700' : '' }}">{{ substr($dayName, 0, 3) }}</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-xl">
                            <p class="text-sm text-blue-700 font-medium">
                                <span class="font-bold">Optional:</span> 
                                Select specific days for this task, or leave empty to create a general task for the entire list.
                            </p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Task Settings Section -->
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-3xl p-6 border border-emerald-200/50">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        Task Settings
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="required_proof_type" class="block text-sm font-bold text-slate-700 mb-3">Required Proof Type <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <select name="required_proof_type" id="required_proof_type" required 
                                        class="block w-full px-6 py-4 border-2 border-slate-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 text-lg font-medium bg-white">
                                    <option value="none" {{ old('required_proof_type', 'none') === 'none' ? 'selected' : '' }}>No proof required</option>
                                    <option value="photo" {{ old('required_proof_type') === 'photo' ? 'selected' : '' }}>Photo required</option>
                                    <option value="video" {{ old('required_proof_type') === 'video' ? 'selected' : '' }}>Video required</option>
                                    <option value="text" {{ old('required_proof_type') === 'text' ? 'selected' : '' }}>Text note required</option>
                                    <option value="file" {{ old('required_proof_type') === 'file' ? 'selected' : '' }}>File upload required</option>
                                    <option value="any" {{ old('required_proof_type') === 'any' ? 'selected' : '' }}>Any proof type</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="mt-2 text-sm text-slate-600 font-medium">Choose what type of proof employees must provide to complete this task.</p>
                            @error('required_proof_type')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="order_index" class="block text-sm font-bold text-slate-700 mb-3">Order Position</label>
                            <input type="number" name="order_index" id="order_index" min="1" 
                                   class="block w-full px-6 py-4 border-2 border-slate-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200 text-lg font-medium bg-white" 
                                   value="{{ old('order_index', ($list->tasks->count() ?? 0) + 1) }}" placeholder="1">
                            <p class="mt-2 text-sm text-slate-600 font-medium">The order in which this task should appear in the list.</p>
                            @error('order_index')
                                <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Task Options Section -->
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-3xl p-6 border border-amber-200/50">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        Task Options
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center p-4 bg-white rounded-2xl border border-amber-200/50 hover:shadow-md transition-all duration-200">
                            <input type="checkbox" name="is_required" id="is_required" value="1" {{ old('is_required', true) ? 'checked' : '' }} 
                                   class="w-5 h-5 text-amber-600 border-2 border-slate-300 rounded focus:ring-amber-500 focus:ring-2">
                            <label for="is_required" class="ml-3 block text-base font-semibold text-slate-900">
                                Required task (must be completed before submission)
                            </label>
                        </div>
                        
                        <div class="flex items-center p-4 bg-white rounded-2xl border border-amber-200/50 hover:shadow-md transition-all duration-200">
                            <input type="checkbox" name="requires_signature" id="requires_signature" value="1" {{ old('requires_signature') ? 'checked' : '' }} 
                                   class="w-5 h-5 text-amber-600 border-2 border-slate-300 rounded focus:ring-amber-500 focus:ring-2">
                            <label for="requires_signature" class="ml-3 block text-base font-semibold text-slate-900">
                                Requires digital signature for completion
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Checklist Section -->
                <div class="bg-gradient-to-r from-cyan-50 to-blue-50 rounded-3xl p-6 border border-cyan-200/50">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        Checklist Items (Optional)
                    </h3>
                    
                    <div class="bg-white rounded-2xl p-6 border border-cyan-200/50">
                        <p class="text-sm text-slate-600 font-medium mb-4">Add checklist items that employees must complete. These will appear as checkboxes in the task.</p>
                        
                        <div id="checklist-container" class="space-y-3">
                            <!-- Checklist items will be added here dynamically -->
                        </div>
                        
                        <button type="button" id="add-checklist-item" class="mt-4 inline-flex items-center px-6 py-3 bg-gradient-to-r from-cyan-500 to-blue-600 text-white text-base font-semibold rounded-2xl shadow-lg hover:from-cyan-600 hover:to-blue-700 transition-all duration-300 hover:shadow-xl hover:scale-105">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Checklist Item
                        </button>
                    </div>
                </div>

                <!-- Proof Type Help Section -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-3xl p-6 border border-indigo-200/50">
                    <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        Proof Type Guide
                    </h3>
                    
                    <div class="bg-white rounded-2xl p-6 border border-indigo-200/50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900">Photo</h4>
                                        <p class="text-sm text-slate-600">Employee must take and upload a photo</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900">Video</h4>
                                        <p class="text-sm text-slate-600">Employee must record and upload a video</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900">Text</h4>
                                        <p class="text-sm text-slate-600">Employee must write a note or comment</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900">File</h4>
                                        <p class="text-sm text-slate-600">Employee must upload a document or file</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-rose-500 to-pink-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900">Any</h4>
                                        <p class="text-sm text-slate-600">Employee can choose any type of proof</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-slate-500 to-gray-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900">None</h4>
                                        <p class="text-sm text-slate-600">No proof required, just mark as complete</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="bg-gradient-to-r from-slate-50 to-blue-50 px-8 py-6 border-t border-slate-200/50">
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.lists.show', $list) }}" 
                           class="px-8 py-3 border-2 border-slate-300 text-base font-semibold rounded-2xl shadow-lg text-slate-700 bg-white hover:bg-gradient-to-r hover:from-slate-50 hover:to-blue-50 hover:border-slate-400 transition-all duration-300 hover:scale-105">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white text-base font-semibold rounded-2xl shadow-xl hover:from-purple-700 hover:via-indigo-700 hover:to-blue-700 transition-all duration-300 hover:shadow-2xl hover:scale-105">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Task
                            </span>
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
            if (checkbox.checked) {
                label.classList.add('border-blue-500', 'bg-blue-50');
                label.classList.remove('hover:border-blue-300', 'hover:bg-blue-50');
                // Update the icon styling
                const icon = label.querySelector('div');
                icon.classList.add('from-blue-400', 'to-blue-500');
                icon.classList.remove('from-slate-400', 'to-slate-500');
                // Update the text styling
                const text = label.querySelector('span:last-child');
                text.classList.add('text-blue-700');
                text.classList.remove('text-slate-700');
            } else {
                label.classList.remove('border-blue-500', 'bg-blue-50');
                label.classList.add('hover:border-blue-300', 'hover:bg-blue-50');
                // Update the icon styling
                const icon = label.querySelector('div');
                icon.classList.remove('from-blue-400', 'to-blue-500');
                icon.classList.add('from-slate-400', 'to-slate-500');
                // Update the text styling
                const text = label.querySelector('span:last-child');
                text.classList.remove('text-blue-700');
                text.classList.add('text-slate-700');
            }
        }
        
        // Initial state
        updateStyle();
        
        // Add click event to label
        label.addEventListener('click', function(e) {
            e.preventDefault();
            checkbox.checked = !checkbox.checked;
            updateStyle();
        });
        
        // Add change event to checkbox
        checkbox.addEventListener('change', updateStyle);
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
        
        const itemDiv = document.createElement('div');
        itemDiv.className = 'flex items-center space-x-3 checklist-item';
        itemDiv.id = itemId;
        
        itemDiv.innerHTML = `
            <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-sm">${checklistItemCount}</span>
            </div>
            <input type="text" 
                   name="checklist_items[]" 
                   class="flex-1 px-4 py-3 border-2 border-slate-200 rounded-xl shadow-sm focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 transition-all duration-200 text-base font-medium bg-white" 
                   placeholder="e.g., Check equipment condition" 
                   value="${value}">
            <button type="button" 
                    onclick="removeChecklistItem('${itemId}')" 
                    class="flex-shrink-0 w-10 h-10 bg-red-100 hover:bg-red-200 text-red-600 rounded-xl flex items-center justify-center transition-all duration-200 hover:scale-105">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        checklistContainer.appendChild(itemDiv);
        
        // Focus on the newly added input
        itemDiv.querySelector('input').focus();
    }
    
    window.removeChecklistItem = function(itemId) {
        const item = document.getElementById(itemId);
        if (item) {
            item.remove();
            
            // Renumber remaining items
            const items = document.querySelectorAll('.checklist-item');
            items.forEach((item, index) => {
                const numberSpan = item.querySelector('.text-white');
                if (numberSpan) {
                    numberSpan.textContent = index + 1;
                }
            });
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

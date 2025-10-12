@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Enhanced Page Header -->
        <div class="mb-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h1 class="text-4xl font-bold leading-tight bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 bg-clip-text text-transparent">
                        {{ $list->title }}
                    </h1>
                    <p class="mt-3 text-lg text-slate-600 font-medium">{{ $list->description }}</p>
                    <div class="mt-4 flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-semibold 
                            @if($list->priority === 'urgent') bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200
                            @elseif($list->priority === 'high') bg-gradient-to-r from-orange-100 to-yellow-100 text-orange-800 border border-orange-200
                            @elseif($list->priority === 'medium') bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200
                            @else bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200 @endif">
                        {{ ucfirst($list->priority) }} Priority
                    </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-semibold bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200">
                        {{ ucfirst($list->schedule_type) }}
                    </span>
                    @if($list->category)
                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-semibold bg-gradient-to-r from-slate-100 to-gray-100 text-slate-800 border border-slate-200">
                                {{ $list->category }}
                        </span>
                    @endif
                    @if($list->requires_signature)
                            <span class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-semibold bg-gradient-to-r from-indigo-100 to-purple-100 text-indigo-800 border border-indigo-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            Signature Required
                        </span>
                    @endif
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-sm font-semibold 
                            @if($list->is_active) bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200
                            @else bg-gradient-to-r from-gray-100 to-slate-100 text-gray-800 border border-gray-200 @endif">
                            <div class="w-2 h-2 rounded-full mr-2 {{ $list->is_active ? 'bg-emerald-500' : 'bg-gray-500' }}"></div>
                        {{ $list->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
                <div class="mt-6 flex space-x-3 md:ml-4 md:mt-0">
                    <a href="{{ route('admin.lists.edit', $list) }}" 
                       class="group inline-flex items-center px-6 py-3 border border-slate-300 text-base font-semibold rounded-2xl shadow-lg text-slate-700 bg-white hover:bg-gradient-to-r hover:from-slate-50 hover:to-blue-50 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    Edit List
                </a>
                    <a href="{{ route('admin.lists.index') }}" 
                       class="group inline-flex items-center px-6 py-3 border border-slate-300 text-base font-semibold rounded-2xl shadow-lg text-slate-700 bg-white hover:bg-gradient-to-r hover:from-slate-50 hover:to-blue-50 hover:border-slate-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Lists
                </a>
            </div>
        </div>
    </div>

        <!-- Enhanced Statistics Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="group relative overflow-hidden bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-white/30">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Total Tasks</dt>
                                <dd class="text-3xl font-bold text-slate-900 mt-1">{{ $list->tasks->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-white/30">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-green-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Required Tasks</dt>
                                <dd class="text-3xl font-bold text-slate-900 mt-1">{{ $list->tasks->where('is_required', true)->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-white/30">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-indigo-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Assigned Users</dt>
                                <dd class="text-3xl font-bold text-slate-900 mt-1">{{ $list->assignments->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="group relative overflow-hidden bg-white/90 backdrop-blur-sm shadow-xl rounded-2xl hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-white/30">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/10 to-yellow-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-yellow-600 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-semibold text-slate-600 uppercase tracking-wide">Submissions</dt>
                                <dd class="text-3xl font-bold text-slate-900 mt-1">{{ $list->submissions->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($list->schedule_type === 'daily' || $list->schedule_type === 'weekly')
            <!-- Day-Specific Lists Overview -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">
                                    @if($list->schedule_type === 'daily')
                                        Daily Schedule Lists
                                    @else
                                        Weekly Schedule Lists
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-600">
                                    @if($list->schedule_type === 'daily')
                                        Separate lists for each day of the week
                                    @else
                                        Day-specific lists based on selected schedule
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.lists.edit', $list) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Schedule
                            </a>
                        </div>
                    </div>
                </div>
                    
                <div class="p-6">
                    <!-- Day Selection Display -->
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">
                            @if($list->schedule_type === 'daily')
                                All Days Overview
                            @else
                                Selected Days Overview
                            @endif
                        </h4>
                        <p class="text-sm text-gray-600 mb-4">
                            @if($list->schedule_type === 'daily')
                                Click on a day to view its day-specific list. Green days have lists created.
                            @else
                                Click on a day to view its day-specific list. Green days have lists created.
                            @endif
                        </p>
                        <div class="grid grid-cols-7 gap-2">
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
                                
                                // Get selected days based on schedule type
                                if ($list->schedule_type === 'daily') {
                                    $selectedDays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                } else {
                                    $selectedDays = $list->schedule_config['weekdays'] ?? [];
                                }
                            @endphp
                            @foreach($weekdays as $dayKey => $dayName)
                                @php
                                    // Check if day-specific list exists
                                    $dayList = $list->subLists()->where('weekday', $dayKey)->first();
                                    $hasList = $dayList !== null;
                                    $isSelected = in_array($dayKey, $selectedDays);
                                @endphp
                                <div class="group relative">
                                    <button onclick="scrollToDaySection('{{ $dayKey }}')" 
                                            class="w-full flex flex-col items-center justify-center p-3 border rounded-lg transition-colors hover:bg-gray-50 cursor-pointer
                                            @if($hasList) 
                                                border-green-300 bg-green-50 hover:bg-green-100 
                                            @elseif($isSelected) 
                                                border-blue-300 bg-blue-50 hover:bg-blue-100 
                                            @else 
                                                border-gray-200 bg-gray-50 hover:bg-gray-100 
                                            @endif">
                                        <div class="w-6 h-6 
                                            @if($hasList) 
                                                bg-green-500 
                                            @elseif($isSelected) 
                                                bg-blue-500 
                                            @else 
                                                bg-gray-400 
                                            @endif rounded-md flex items-center justify-center mb-2 relative">
                                            <span class="text-white font-bold text-xs">{{ substr($dayName, 0, 1) }}</span>
                                            @if($hasList)
                                                <div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full flex items-center justify-center">
                                                    <span class="text-white text-xs font-bold">{{ $dayList->tasks->count() }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="text-xs font-medium 
                                            @if($hasList) 
                                                text-green-700 
                                            @elseif($isSelected) 
                                                text-blue-700 
                                            @else 
                                                text-gray-500 
                                            @endif">{{ substr($dayName, 0, 3) }}</span>
                                        @if($hasList)
                                            <span class="text-xs text-green-600 mt-1">{{ $dayList->tasks->count() }} task{{ $dayList->tasks->count() > 1 ? 's' : '' }}</span>
                                        @endif
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Day Sections Display -->
                    <div class="space-y-4">
                        @foreach($weekdays as $dayKey => $dayName)
                            @php
                                // Check if day-specific list exists
                                $dayList = $list->subLists()->where('weekday', $dayKey)->first();
                                $hasList = $dayList !== null;
                                $isSelected = in_array($dayKey, $selectedDays);
                            @endphp
                            @if($isSelected)
                                <div id="day-section-{{ $dayKey }}" class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden">
                                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                                                    <span class="text-white font-bold text-sm">{{ substr($dayName, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <h4 class="text-lg font-semibold text-gray-900">{{ $dayName }} List</h4>
                                                    <p class="text-sm text-gray-600">
                                                        @if($hasList)
                                                            Day-specific list with {{ $dayList->tasks->count() }} tasks
                                                        @else
                                                            Day-specific list not created yet
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                @if($hasList)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-sm font-medium bg-green-100 text-green-800">
                                                        {{ $dayList->tasks->count() }} tasks
                                                    </span>
                                                    <a href="{{ route('admin.lists.show', $dayList) }}" class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                        View List
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-sm font-medium bg-orange-100 text-orange-800">
                                                        Not Created
                                                    </span>
                                                    <button onclick="createDayList('{{ $dayKey }}', '{{ $dayName }}')" class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                        Create List
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        @if($hasList)
                                            <div class="text-center py-6">
                                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </div>
                                                <h5 class="text-lg font-semibold text-gray-900 mb-2">{{ $dayName }} List Created</h5>
                                                <p class="text-gray-600 mb-4">This day has its own dedicated list with {{ $dayList->tasks->count() }} tasks</p>
                                                <a href="{{ route('admin.lists.show', $dayList) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    View {{ $dayName }} List
                                                </a>
                                            </div>
                                        @else
                                            <div class="text-center py-6">
                                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                                    </svg>
                                                </div>
                                                <h5 class="text-lg font-semibold text-gray-900 mb-2">No {{ $dayName }} List Yet</h5>
                                                <p class="text-gray-600 mb-4">Create a dedicated list for {{ $dayName }} to get started</p>
                                                <button onclick="createDayList('{{ $dayKey }}', '{{ $dayName }}')" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    Create {{ $dayName }} List
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- General Tasks Overview (for non-daily/weekly schedules) -->
        @if($list->schedule_type !== 'daily' && $list->schedule_type !== 'weekly')
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Tasks Overview</h3>
                                <p class="text-sm text-gray-600">{{ $list->tasks->count() }} tasks in this list</p>
                            </div>
                        </div>
                        <a href="{{ route('admin.lists.tasks.create', $list) }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Task
                        </a>
                    </div>
                </div>
                    
                <div class="p-6">
                    @php
                        // Show all tasks for non-daily/weekly schedules
                        $allTasks = $list->tasks->sortBy('order_index');
                    @endphp
                    @if($allTasks->count() > 0)
                        <div class="space-y-3">
                            @foreach($allTasks as $task)
                                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                <div class="w-6 h-6 bg-blue-600 rounded-md flex items-center justify-center">
                                                    <span class="text-white font-bold text-xs">{{ $task->order_index }}</span>
                                                </div>
                                                <div>
                                                    <h4 class="text-base font-semibold text-gray-900">{{ $task->title }}</h4>
                                                    @if($task->description)
                                                        <p class="text-sm text-gray-600 mt-1">{{ $task->description }}</p>
                                                    @endif
                                                    @if($task->checklist_items && count($task->checklist_items) > 0)
                                                        <div class="mt-2 text-xs">
                                                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-cyan-100 text-cyan-800 font-medium">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                                                </svg>
                                                                {{ count($task->checklist_items) }} checklist items
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="flex flex-wrap items-center gap-2">
                                                @if($task->is_required)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-red-100 text-red-800">
                                                        Required
                                                    </span>
                                                @endif
                                                @if($task->requires_signature)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                        </svg>
                                                        Signature
                                                    </span>
                                                @endif
                                                @if($task->required_proof_type !== 'none')
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ ucfirst($task->required_proof_type) }}
                                                    </span>
                                                @endif
                                                @if($task->assignments->count() > 0)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                                        {{ $task->assignments->count() }} assigned
                                                    </span>
                                                @endif
                                            </div>
                                            
                                            @if($task->instructions)
                                                <div class="mt-3 p-3 bg-gray-50 rounded-md border border-gray-200">
                                                    <h5 class="text-sm font-medium text-gray-700 mb-1">Instructions:</h5>
                                                    <p class="text-sm text-gray-600">{{ $task->instructions }}</p>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <div class="flex space-x-2 ml-4">
                                            <a href="{{ route('admin.tasks.edit', $task) }}" 
                                               class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </form>
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
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">No tasks yet</h3>
                            <p class="text-gray-600 mb-6">Add tasks to this list to get started</p>
                            <a href="{{ route('admin.lists.tasks.create', $list) }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Task
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Assignment Management -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Team Assignments</h3>
                            <p class="text-sm text-gray-600">Manage who has access to this task list</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="showAssignModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Assign List
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                @if($list->assignments->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($list->assignments as $assignment)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
                                <!-- User Info Section -->
                                <div class="flex items-center space-x-3 mb-4">
                                    <div class="relative">
                                        @if($assignment->user)
                                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                                <span class="text-white font-bold text-sm">{{ substr($assignment->user->name, 0, 2) }}</span>
                                            </div>
                                        @elseif($assignment->department)
                                            <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-10 h-10 bg-gray-400 rounded-lg flex items-center justify-center">
                                                <span class="text-white font-bold text-sm">NA</span>
                                            </div>
                                        @endif
                                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-2 h-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-base font-semibold text-gray-900">
                                            @if($assignment->user)
                                                {{ $assignment->user->name }}
                                            @elseif($assignment->department)
                                                <span class="text-green-600">{{ $assignment->department }} Department</span>
                                            @else
                                                <span class="text-gray-500 italic">User not found</span>
                                            @endif
                                        </h4>
                                        <p class="text-sm text-gray-600">
                                            @if($assignment->user)
                                                {{ $assignment->user->department ?? 'No Department' }}
                                            @elseif($assignment->department)
                                                <span class="text-green-600">Department Assignment</span>
                                            @else
                                                <span class="text-gray-400">No Department</span>
                                            @endif
                                        </p>
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                                <div class="w-2 h-2 bg-green-500 rounded-full mr-1"></div>
                                                Active Assignment
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Date Information -->
                                <div class="bg-gray-50 rounded-lg p-3 mb-3 border border-gray-200">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-sm font-medium text-gray-600">Assigned</span>
                                            </div>
                                            <span class="text-sm font-semibold text-gray-900">{{ $assignment->assigned_date->format('M j, Y') }}</span>
                                        </div>
                                        @if($assignment->due_date)
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-sm font-medium text-gray-600">Due Date</span>
                                                </div>
                                                <span class="text-sm font-semibold text-gray-900">{{ $assignment->due_date->format('M j, Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Action Section -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm font-medium text-gray-600">Assignment Status</span>
                                    </div>
                                    <button onclick="removeAssignment({{ $assignment->id }})" 
                                            class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Remove
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No team assignments yet</h3>
                        <p class="text-gray-600 mb-6">Assign this list to team members or departments to get started</p>
                        <button onclick="showAssignModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="relative z-10">Assign to Team</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Assignment Modal -->
<div id="assignModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Assign List to Team</h3>
                <button onclick="closeAssignModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="assignForm" method="POST" action="{{ route('admin.lists.assign', $list) }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assignment Type</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="radio" name="assignment_type" value="user" class="assignment-type-radio" checked onchange="toggleAssignmentType()">
                            <span class="ml-2 text-sm text-gray-700">Individual User</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="assignment_type" value="department" class="assignment-type-radio" onchange="toggleAssignmentType()">
                            <span class="ml-2 text-sm text-gray-700">Department</span>
                        </label>
                    </div>
                </div>

                <div id="userAssignment" class="mb-4">
                    <label for="user_ids" class="block text-sm font-medium text-gray-700 mb-2">Select User</label>
                    <select name="user_ids[]" id="user_ids" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Choose a user...</option>
                        @foreach(\App\Models\User::where('role', 'employee')->get() as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->department ?? 'No Department' }})</option>
                        @endforeach
                    </select>
                </div>

                <div id="departmentAssignment" class="mb-4 hidden">
                    <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Select Department</label>
                    <select name="department" id="department" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Choose a department...</option>
                        @php
                            $departments = \App\Models\User::where('role', 'employee')->whereNotNull('department')->distinct()->pluck('department');
                        @endphp
                        @foreach($departments as $dept)
                            <option value="{{ $dept }}">{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="assigned_date" class="block text-sm font-medium text-gray-700 mb-2">Assigned Date</label>
                    <input type="date" name="assigned_date" id="assigned_date" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Due Date (Optional)</label>
                    <input type="date" name="due_date" id="due_date" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAssignModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                        Assign List
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Day List Modal -->
<div id="createDayListModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Create Day-Specific List</h3>
                <button onclick="closeCreateDayListModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <form id="createDayListForm">
                @csrf
                <div class="mb-4">
                    <label for="dayListTitle" class="block text-sm font-medium text-gray-700 mb-2">List Title</label>
                    <input type="text" name="title" id="dayListTitle" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="dayListDescription" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="dayListDescription" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <input type="hidden" name="weekday" id="dayListWeekday">
                <input type="hidden" name="parent_list_id" value="{{ $list->id }}">

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCreateDayListModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        Create List
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
// Assignment Modal Functions
function showAssignModal() {
    document.getElementById('assignModal').classList.remove('hidden');
}

function closeAssignModal() {
    document.getElementById('assignModal').classList.add('hidden');
    document.getElementById('assignForm').reset();
}

function toggleAssignmentType() {
    const userAssignment = document.getElementById('userAssignment');
    const departmentAssignment = document.getElementById('departmentAssignment');
    const assignmentType = document.querySelector('input[name="assignment_type"]:checked').value;
    
    if (assignmentType === 'user') {
        userAssignment.classList.remove('hidden');
        departmentAssignment.classList.add('hidden');
        document.getElementById('department').value = '';
    } else {
        userAssignment.classList.add('hidden');
        departmentAssignment.classList.remove('hidden');
        document.getElementById('user_ids').value = '';
    }
}

// Create Day List Modal Functions
function createDayList(dayKey, dayName) {
    document.getElementById('dayListTitle').value = '{{ $list->title }} - ' + dayName;
    document.getElementById('dayListDescription').value = '{{ $list->description }} - ' + dayName + ' specific tasks';
    document.getElementById('dayListWeekday').value = dayKey;
    document.getElementById('createDayListModal').classList.remove('hidden');
}

function closeCreateDayListModal() {
    document.getElementById('createDayListModal').classList.add('hidden');
    document.getElementById('createDayListForm').reset();
}

// Handle Create Day List Form Submission
document.getElementById('createDayListForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    submitBtn.textContent = 'Creating...';
    submitBtn.disabled = true;
    
    fetch('/admin/lists/{{ $list->id }}/create-day-list', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error creating day list');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error creating day list');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
});

// Remove Assignment Function
function removeAssignment(assignmentId) {
    if (confirm('Are you sure you want to remove this assignment?')) {
        fetch(`/admin/assignments/${assignmentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error removing assignment');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing assignment');
        });
    }
}

// Scroll to Day Section Function
function scrollToDaySection(dayKey) {
    const element = document.getElementById('day-section-' + dayKey);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    const assignModal = document.getElementById('assignModal');
    const createDayListModal = document.getElementById('createDayListModal');
    
    if (e.target === assignModal) {
        closeAssignModal();
    }
    
    if (e.target === createDayListModal) {
        closeCreateDayListModal();
    }
});
</script>
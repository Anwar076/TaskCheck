@extends('layouts.employee')

@section('content')
<div class="min-h-screen bg-gray-50 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-6 lg:py-8">
    <!-- Greeting + Progress Section -->
    <div class="pt-0 pb-4 sm:pb-6">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-200 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <!-- Left: Greeting + Profile Picture -->
                <div class="flex items-start gap-4 flex-1 min-w-0">
                    <!-- Profile Picture -->
                    <div class="flex-shrink-0">
                        <div class="h-16 w-16 sm:h-20 sm:w-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl sm:text-2xl shadow-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                    
                    <!-- Greeting Content -->
                    <div class="flex-1 min-w-0">
                        <div class="mb-4 sm:mb-6">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 mb-1 sm:mb-2 break-words">
                                Goede{{ now()->hour < 12 ? 'morgen' : (now()->hour < 17 ? 'middag' : 'avond') }}, 
                                <span class="text-blue-600">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            </h1>
                            <p class="text-gray-600 text-sm sm:text-base lg:text-lg break-words">
                                @php
                                    $now = \Carbon\Carbon::now()->locale('nl');
                                @endphp
                                {{ $now->translatedFormat('l, j F Y') }}
                                <span class="ml-2 text-gray-500 font-medium" id="current-time">{{ $now->format('H:i') }}</span>
                            </p>
                        </div>
                        
                        <!-- Linear Progress Bar -->
                        <div class="w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl">
                            @php
                                // Bereken totaal aantal taken alleen van lijsten die aan deze gebruiker zijn toegewezen
                                $totalTasksToday = 0;
                                
                                // Alleen tellen als er lijsten zijn toegewezen
                                if ($todaysLists && $todaysLists->count() > 0) {
                                    foreach($todaysLists as $list) {
                                        // Check of de lijst taken heeft geladen
                                        if ($list->tasks) {
                                            $totalTasksToday += $list->tasks->count();
                                        }
                                    }
                                }
                                
                                // Als er geen taken zijn vandaag, toon 0% voortgang
                                if ($totalTasksToday == 0) {
                                    $progressPercent = 0;
                                    $progressColor = 'from-gray-400 to-gray-500';
                                    $textColor = 'text-gray-600';
                                } else {
                                    $totalForProgress = $totalTasksToday;
                                    // Alleen afgeronde taken tellen, max 100%
                                    $progressPercent = min(100, round(($stats['completed_today'] / $totalForProgress) * 100));
                                    
                                    // Bepaal kleur op basis van voortgang
                                    $progressColor = 'from-blue-500 to-blue-600';
                                    $textColor = 'text-blue-600';
                                    if ($progressPercent == 100) {
                                        $progressColor = 'from-green-500 to-green-600';
                                        $textColor = 'text-green-600';
                                    } elseif ($progressPercent >= 50) {
                                        $progressColor = 'from-orange-500 to-orange-600';
                                        $textColor = 'text-orange-600';
                                    } elseif ($progressPercent == 0) {
                                        $progressColor = 'from-red-500 to-red-600';
                                        $textColor = 'text-red-600';
                                    }
                                }
                            @endphp
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-1">
                                <span class="text-sm font-medium text-gray-700">Voortgang Vandaag</span>
                                <span class="text-sm font-bold {{ $textColor }}">
                                    {{ $progressPercent }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="progress-bar h-full bg-gradient-to-r {{ $progressColor }} rounded-full transition-all duration-1000 ease-out shadow-sm" 
                                     style="width: {{ $progressPercent }}%">
                                </div>
                            </div>
                            <div class="flex flex-col xs:flex-row xs:justify-between text-xs text-gray-500 mt-2 gap-1">
                                <span>{{ $stats['completed_today'] }} afgerond</span>
                                @if($totalTasksToday > 0)
                                    <span>{{ $totalTasksToday }} totaal</span>
                                @else
                                    <span>Geen taken vandaag</span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Call to Actions -->
                        @if($todaysLists->count() > 0)
                        <div class="flex flex-col xs:flex-row flex-wrap gap-3 mt-4 sm:mt-6">
                            <a href="#todays-tasks" class="inline-flex items-center justify-center min-h-[44px] px-4 py-3 sm:py-2 bg-blue-600 text-white rounded-xl font-semibold hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg text-sm sm:text-base touch-manipulation">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                Openstaande Taken Bekijken
                            </a>
                            @if($redoTasks->count() > 0)
                            <a href="#attention-tasks" class="inline-flex items-center justify-center min-h-[44px] px-4 py-3 sm:py-2 bg-amber-600 text-white rounded-xl font-semibold hover:bg-amber-700 transition-colors shadow-md hover:shadow-lg text-sm sm:text-base touch-manipulation">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                {{ $redoTasks->count() }} Taak/Taken Opnieuw Uitvoeren
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Right: Organisatie logo -->
                @php $company = auth()->user()->company; @endphp
                <div class="flex-shrink-0 flex items-center justify-center md:justify-end mt-4 md:mt-0">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 sm:p-6 border border-blue-100 shadow-sm">
                        <div class="text-center">
                            @if($company && $company->logo_path)
                                <img src="{{ Storage::url($company->logo_path) }}" alt="{{ $company->name }}" class="h-12 sm:h-14 lg:h-16 w-auto mx-auto object-contain">
                                <div class="text-[10px] sm:text-xs lg:text-sm text-gray-600 font-medium mt-2">{{ $company->name }}</div>
                            @else
                                <div class="text-2xl sm:text-3xl lg:text-4xl font-bold text-blue-600 mb-1 sm:mb-2">{{ $company->name ?? 'JAYAS' }}</div>
                                <div class="text-[10px] sm:text-xs lg:text-sm text-gray-600 font-medium">Organisatie</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        {{-- Alerts Section --}}
        @if($redoTasks->count() > 0 || $notifications->count() > 0)
            @php
                $redoCount = $redoTasks->count();
                $notificationCount = $notifications->count();
            @endphp

            <div class="grid grid-cols-1 gap-4 sm:gap-6 mb-6 sm:mb-8 md:grid-cols-2">

                {{-- OPNIEUW UITVOEREN (alleen wanneer manager om herhaling vraagt) --}}
                @if($redoCount > 0)
                    <div id="attention-tasks" class="bg-white rounded-xl sm:rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full ring-1 ring-slate-900/5">
                        <div class="bg-gradient-to-br from-slate-50 via-amber-50/50 to-orange-50/30 border-b border-slate-100 px-4 sm:px-5 py-3 sm:py-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm border border-amber-100/80">
                                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-sm sm:text-base font-semibold text-slate-800">
                                                Taken opnieuw uitvoeren
                                            </h3>
                                            <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full text-[10px] font-bold text-white bg-amber-600">
                                                {{ $redoCount > 99 ? '99+' : $redoCount }}
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            {{ $redoCount }} {{ Str::plural('taak', $redoCount) }} opnieuw uitvoeren om de lijst af te ronden
                                        </p>
                                    </div>
                                </div>
                                @if($redoCount > 3)
                                    <a href="{{ route('employee.notifications.index') }}"
                                    class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium text-amber-700 bg-white/80 hover:bg-white border border-amber-100/80 hover:border-amber-200 transition-colors shadow-sm min-h-[36px] touch-manipulation">
                                        Alles bekijken
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="p-0 flex-1 flex flex-col divide-y divide-slate-100">
                            @foreach($redoTasks->take(3) as $redoTask)
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-end justify-between gap-3 px-4 sm:px-5 py-3 sm:py-4 bg-amber-50/50 border-l-4 border-l-amber-500 transition-colors hover:bg-amber-50">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-slate-900 truncate">
                                            {{ $redoTask->task->title }}
                                        </h4>
                                        <p class="text-xs text-slate-500 mt-0.5 truncate">
                                            Checklist: {{ $redoTask->submission->taskList->title }}
                                        </p>
                                        @if($redoTask->redo_reason)
                                            <p class="mt-2 text-xs text-amber-800 line-clamp-2 bg-amber-50 rounded px-2 py-1.5 border border-amber-100">
                                                <span class="font-medium">Reden:</span> {{ $redoTask->redo_reason }}
                                            </p>
                                        @endif
                                        <p class="mt-1 text-xs text-amber-700 font-medium">Voer deze taak opnieuw uit om de lijst af te ronden.</p>
                                    </div>
                                    <a href="{{ route('employee.submissions.edit', $redoTask->submission) }}"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-3 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-amber-600 hover:bg-amber-700 shadow-sm transition-all hover:shadow-md whitespace-nowrap flex-shrink-0 min-h-[44px] touch-manipulation">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                        </svg>
                                        Herhaal taak
                                    </a>
                                </div>
                            @endforeach

                            @if($redoCount > 3)
                                <div class="px-4 sm:px-5 py-3 sm:py-4 bg-slate-50/50">
                                    <a href="{{ route('employee.notifications.index') }}"
                                    class="flex items-center justify-center gap-2 py-3 sm:py-2 min-h-[44px] text-xs sm:text-sm font-semibold text-amber-700 hover:text-amber-800 transition-colors touch-manipulation">
                                        Bekijk alle {{ $redoCount }} taken
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- MELDINGEN --}}
                @if($notificationCount > 0)
                    @php $unreadCount = $notifications->whereNull('read_at')->count(); @endphp
                    <div class="bg-white rounded-xl sm:rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col h-full ring-1 ring-slate-900/5">
                        <div class="bg-gradient-to-br from-slate-50 via-blue-50/50 to-sky-50/30 border-b border-slate-100 px-4 sm:px-5 py-3 sm:py-4">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm border border-blue-100/80">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-sm sm:text-base font-semibold text-slate-800">
                                                Meldingen
                                            </h3>
                                            @if($unreadCount > 0)
                                                <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1.5 rounded-full text-[10px] font-bold text-white bg-blue-600">
                                                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            {{ $notificationCount }} {{ Str::plural('melding', $notificationCount) }}
                                        </p>
                                    </div>
                                </div>

                                <a href="{{ route('employee.notifications.index') }}"
                                class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-medium text-blue-700 bg-white/80 hover:bg-white border border-blue-100/80 hover:border-blue-200 transition-colors shadow-sm min-h-[36px] touch-manipulation">
                                    Alles bekijken
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="p-0 flex-1 flex flex-col divide-y divide-slate-100">
                            @foreach($notifications->take(3) as $notification)
                                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 px-4 sm:px-5 py-3 sm:py-4 {{ is_null($notification->read_at) ? 'bg-blue-50/30' : 'bg-white' }} {{ is_null($notification->read_at) ? 'border-l-4 border-l-blue-500' : '' }} transition-colors hover:bg-slate-50/50">
                                    <div class="flex-1 min-w-0 pl-0 {{ is_null($notification->read_at) ? 'sm:pl-0' : '' }}">
                                        <h4 class="text-sm font-semibold {{ is_null($notification->read_at) ? 'text-slate-900' : 'text-slate-700' }} truncate">
                                            {{ $notification->title }}
                                        </h4>
                                        <p class="text-xs text-slate-500 mt-0.5 line-clamp-2 min-w-0">
                                            {{ Str::limit($notification->message, 70) }}
                                        </p>
                                        <p class="mt-2 flex items-center gap-1.5 text-[11px] text-slate-400">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2 flex-shrink-0 w-full sm:w-auto justify-between sm:justify-end mt-2 sm:mt-0">
                                        <a href="{{ route('employee.notifications.index') }}"
                                        class="inline-flex sm:hidden items-center justify-center min-h-[44px] px-4 py-2 rounded-xl text-xs font-medium text-blue-700 bg-blue-100/80 hover:bg-blue-100 transition-colors touch-manipulation flex-1 sm:flex-initial">
                                            Open
                                        </a>

                                        @if(is_null($notification->read_at))
                                            <button
                                                onclick="markNotificationAsRead({{ $notification->id }})"
                                                class="min-w-[44px] min-h-[44px] w-11 h-11 rounded-xl border border-emerald-200 bg-emerald-50 flex items-center justify-center hover:bg-emerald-100 hover:border-emerald-300 transition-all shadow-sm touch-manipulation"
                                                title="Markeer als gelezen">
                                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @if($notificationCount > 3)
                                <div class="px-4 sm:px-5 py-3 sm:py-4 bg-slate-50/50">
                                    <a href="{{ route('employee.notifications.index') }}"
                                    class="flex items-center justify-center gap-2 py-3 sm:py-2 min-h-[44px] text-xs sm:text-sm font-semibold text-blue-700 hover:text-blue-800 transition-colors touch-manipulation">
                                        Bekijk alle {{ $notificationCount }} meldingen
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif


        <!-- Main Content -->
        <div class="mb-6 sm:mb-8">
            <!-- Today's Tasks -->
            <div id="todays-tasks">
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100 p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900">Taken van Vandaag</h3>
                                    <p id="todays-lists-counter"
                                       class="text-gray-600 text-sm sm:text-base"
                                       data-total-lists="{{ $todaysLists->count() }}">
                                        {{ $todaysLists->count() }} {{ $todaysLists->count() === 1 ? 'lijst' : 'lijsten' }} toegewezen
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        @if($todaysLists->count() > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            @foreach($todaysLists as $list)
                            <div class="bg-gray-50 rounded-xl p-4 sm:p-5 lg:p-6 hover:bg-gray-100 transition-colors duration-200 today-list-card min-w-0"
                                 data-list-id="{{ $list->id }}">
                                <div class="flex flex-col h-full">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900 mb-2 break-words">{{ $list->title }}</h4>
                                        <p class="text-gray-600 text-sm sm:text-base mb-3 sm:mb-4 break-words line-clamp-3">{{ Str::limit($list->description, 120) }}</p>
                                        
                                        <div class="flex flex-wrap gap-1.5 sm:gap-2 mb-3 sm:mb-4">
                                            <span class="inline-flex items-center px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs sm:text-sm font-medium
                                                @if($list->priority === 'urgent') bg-red-100 text-red-800 border border-red-200
                                                @elseif($list->priority === 'high') bg-orange-100 text-orange-800 border border-orange-200
                                                @elseif($list->priority === 'medium') bg-amber-100 text-amber-800 border border-amber-200
                                                @else bg-green-100 text-green-800 border border-green-200 @endif">
                                                @if($list->priority === 'urgent') Urgente
                                                @elseif($list->priority === 'high') Hoge
                                                @elseif($list->priority === 'medium') Gemiddelde
                                                @else Lage @endif Prioriteit
                                            </span>
                                            <span class="inline-flex items-center px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs sm:text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                                {{ $list->tasks->count() }} {{ $list->tasks->count() === 1 ? 'Taak' : 'Taken' }}
                                            </span>
                                            @if($list->requires_signature)
                                            <span class="inline-flex items-center px-2.5 sm:px-3 py-0.5 sm:py-1 rounded-full text-xs sm:text-sm font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                                Handtekening Vereist
                                            </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Task Preview -->
                                        @if($list->tasks->count() > 0)
                                        <div class="space-y-2 mb-4">
                                            @foreach($list->tasks->take(3) as $task)
                                            @php
                                                $currentTime = now();
                                                $isOverdue = false;
                                                $isUpcoming = false;
                                                $isCompleted = isset($task->is_completed) && $task->is_completed;
                                                
                                                if ($task->end_time) {
                                                    $endTime = \Carbon\Carbon::parse($currentTime->format('Y-m-d') . ' ' . $task->end_time);
                                                    $isOverdue = $currentTime->isAfter($endTime) && !$isCompleted;
                                                }
                                                
                                                if ($task->start_time) {
                                                    $startTime = \Carbon\Carbon::parse($currentTime->format('Y-m-d') . ' ' . $task->start_time);
                                                    $isUpcoming = $currentTime->isBefore($startTime) && !$isCompleted;
                                                }
                                            @endphp
                                            <div class="flex items-center space-x-3 {{ $isCompleted ? 'bg-green-50 rounded-lg p-2 -mx-2' : ($isOverdue ? 'bg-red-50 rounded-lg p-2 -mx-2' : ($isUpcoming ? 'bg-blue-50 rounded-lg p-2 -mx-2' : '')) }}">
                                                <div class="w-4 h-4 border-2 {{ $isCompleted ? 'border-green-500 bg-green-500' : ($isOverdue ? 'border-red-400' : ($isUpcoming ? 'border-blue-400' : 'border-gray-300')) }} rounded-sm flex items-center justify-center">
                                                    @if($isCompleted)
                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-2 h-2 {{ $isOverdue ? 'text-red-500' : ($isUpcoming ? 'text-blue-500' : 'text-gray-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <span class="text-sm {{ $isCompleted ? 'text-green-700 font-semibold line-through' : ($isOverdue ? 'text-red-700 font-semibold' : ($isUpcoming ? 'text-blue-700' : 'text-gray-700')) }} truncate">{{ $task->title }}</span>
                                                    @if($task->start_time || $task->end_time)
                                                        <span class="text-xs ml-2 {{ $isCompleted ? 'text-green-600' : ($isOverdue ? 'text-red-600 font-medium' : ($isUpcoming ? 'text-blue-600' : 'text-gray-500')) }}" 
                                                              @if($task->end_time && !$isCompleted) data-task-time="{{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }}" data-task-id="{{ $task->id }}" @endif>
                                                            @if($task->start_time && $task->end_time)
                                                                ({{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }})
                                                                @if($isCompleted)
                                                                    <span class="font-medium">✓ Afgerond</span>
                                                                @elseif($isOverdue)
                                                                    <span class="font-bold">⚠️ Te laat!</span>
                                                                @endif
                                                            @elseif($task->start_time)
                                                                (vanaf {{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }})
                                                                @if($isCompleted)
                                                                    <span class="font-medium">✓ Afgerond</span>
                                                                @endif
                                                            @elseif($task->end_time)
                                                                (tot {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }})
                                                                @if($isCompleted)
                                                                    <span class="font-medium">✓ Afgerond</span>
                                                                @elseif($isOverdue)
                                                                    <span class="font-bold">⚠️ Te laat!</span>
                                                                @endif
                                                            @endif
                                                        </span>
                                                    @elseif($isCompleted)
                                                        <span class="text-xs ml-2 text-green-600 font-medium">✓ Afgerond</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach
                                            @if($list->tasks->count() > 3)
                                            <div x-data="{ open: false }" class="task-accordion-{{ $list->id }}">
                                                <button @click="open = !open" 
                                                        type="button"
                                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium ml-7 flex items-center gap-1 cursor-pointer focus:outline-none min-h-[44px] -mb-2 py-2 touch-manipulation">
                                                    <span>+{{ $list->tasks->count() - 3 }} Meer Taken</span>
                                                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                                <div x-show="open" 
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0 max-h-0"
                                                     x-transition:enter-end="opacity-100 max-h-screen"
                                                     x-transition:leave="transition ease-in duration-200"
                                                     x-transition:leave-start="opacity-100 max-h-screen"
                                                     x-transition:leave-end="opacity-0 max-h-0"
                                                     class="mt-2 ml-7 space-y-2 overflow-hidden">
                                                    @foreach($list->tasks->skip(3) as $task)
                                                    @php
                                                        $currentTime = now();
                                                        $isOverdue = false;
                                                        $isUpcoming = false;
                                                        $isCompleted = isset($task->is_completed) && $task->is_completed;
                                                        
                                                        if ($task->end_time) {
                                                            $endTime = \Carbon\Carbon::parse($currentTime->format('Y-m-d') . ' ' . $task->end_time);
                                                            $isOverdue = $currentTime->isAfter($endTime) && !$isCompleted;
                                                        }
                                                        
                                                        if ($task->start_time) {
                                                            $startTime = \Carbon\Carbon::parse($currentTime->format('Y-m-d') . ' ' . $task->start_time);
                                                            $isUpcoming = $currentTime->isBefore($startTime) && !$isCompleted;
                                                        }
                                                    @endphp
                                                    <div class="flex items-center space-x-3 {{ $isCompleted ? 'bg-green-50 rounded-lg p-2 -mx-2' : ($isOverdue ? 'bg-red-50 rounded-lg p-2 -mx-2' : ($isUpcoming ? 'bg-blue-50 rounded-lg p-2 -mx-2' : '')) }}">
                                                        <div class="w-4 h-4 border-2 {{ $isCompleted ? 'border-green-500 bg-green-500' : ($isOverdue ? 'border-red-400' : ($isUpcoming ? 'border-blue-400' : 'border-gray-300')) }} rounded-sm flex items-center justify-center">
                                                            @if($isCompleted)
                                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            @else
                                                                <svg class="w-2 h-2 {{ $isOverdue ? 'text-red-500' : ($isUpcoming ? 'text-blue-500' : 'text-gray-400') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <span class="text-sm {{ $isCompleted ? 'text-green-700 font-semibold line-through' : ($isOverdue ? 'text-red-700 font-semibold' : ($isUpcoming ? 'text-blue-700' : 'text-gray-700')) }} truncate">{{ $task->title }}</span>
                                                            @if($task->start_time || $task->end_time)
                                                                <span class="text-xs ml-2 {{ $isCompleted ? 'text-green-600' : ($isOverdue ? 'text-red-600 font-medium' : ($isUpcoming ? 'text-blue-600' : 'text-gray-500')) }}"
                                                                      @if($task->end_time && !$isCompleted) data-task-time="{{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }}" data-task-id="{{ $task->id }}" @endif>
                                                                    @if($task->start_time && $task->end_time)
                                                                        ({{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }})
                                                                        @if($isCompleted)
                                                                            <span class="font-medium">✓ Afgerond</span>
                                                                        @elseif($isOverdue)
                                                                            <span class="font-bold">⚠️ Te laat!</span>
                                                                        @endif
                                                                    @elseif($task->start_time)
                                                                        (vanaf {{ \Carbon\Carbon::parse($task->start_time)->format('H:i') }})
                                                                        @if($isCompleted)
                                                                            <span class="font-medium">✓ Afgerond</span>
                                                                        @endif
                                                                    @elseif($task->end_time)
                                                                        (tot {{ \Carbon\Carbon::parse($task->end_time)->format('H:i') }})
                                                                        @if($isCompleted)
                                                                            <span class="font-medium">✓ Afgerond</span>
                                                                        @elseif($isOverdue)
                                                                            <span class="font-bold">⚠️ Te laat!</span>
                                                                        @endif
                                                                    @endif
                                                                </span>
                                                            @elseif($isCompleted)
                                                                <span class="text-xs ml-2 text-green-600 font-medium">✓ Afgerond</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                        @endif
                                    </div>

                                    <!-- Start/Bekijk Task buttons -->
                                    @php
                                        $existingSubmission = \App\Models\Submission::where('user_id', auth()->id())
                                            ->where('list_id', $list->id)
                                            ->whereDate('created_at', today())
                                            ->whereIn('status', ['in_progress', 'rejected', 'redo_requested'])
                                            ->first();
                                    @endphp
                                    
                                    @if($existingSubmission)
                                    <!-- If submission exists, show "Bekijk Taak" button -->
                                    <a href="{{ route('employee.submissions.edit', $existingSubmission) }}" 
                                       class="mt-4 w-full min-h-[44px] bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 sm:px-6 py-3 sm:py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center group justify-center text-sm sm:text-base touch-manipulation">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <span class="truncate">Bekijk Taak</span>
                                    </a>
                                    @else
                                    <!-- If no submission, show "Start Taak" button -->
                                    <form method="POST" action="{{ route('employee.submissions.start', $list) }}" class="mt-4">
                                        @csrf
                                        <button type="submit" class="w-full min-h-[44px] bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 sm:px-6 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center group justify-center text-sm sm:text-base touch-manipulation">
                                            <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            <span class="truncate">Start Taak</span>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8 sm:py-12 lg:py-16 px-4">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 lg:w-20 lg:h-20 mx-auto bg-gradient-to-br from-green-100 to-emerald-100 rounded-xl sm:rounded-2xl flex items-center justify-center mb-4 sm:mb-6 shadow-lg">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg sm:text-xl lg:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">Alles Klaar Voor Vandaag!</h3>
                            <p class="text-gray-600 text-sm sm:text-base lg:text-lg">Je hebt alle toegewezen taken voltooid. Geweldig werk!</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Timeline (moved to bottom) -->
        <!-- @if($recentSubmissions->count() > 0)
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 border-b border-gray-100 p-4 sm:p-6">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-gray-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-base sm:text-lg lg:text-xl font-bold text-gray-900">Recente Activiteit</h3>
                            <p class="text-gray-600 text-xs sm:text-sm lg:text-base">Jouw laatste inzendingen</p>
                        </div>
                    </div>
                </div>
                <div class="p-4 sm:p-6">
                    @foreach($recentSubmissions as $index => $submission)
                    <div class="timeline-item relative {{ $index !== $recentSubmissions->count() - 1 ? 'pb-5 sm:pb-6' : '' }}">
                        @if($index !== $recentSubmissions->count() - 1)
                        <div class="absolute left-3 sm:left-4 top-8 w-0.5 h-full bg-gray-200"></div>
                        @endif
                        <div class="flex items-start space-x-3 sm:space-x-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                @if($submission->status === 'completed') bg-green-100
                                @elseif($submission->status === 'reviewed') bg-blue-100
                                @elseif($submission->status === 'rejected') bg-red-100
                                @else bg-amber-100 @endif">
                                <svg class="w-4 h-4 
                                    @if($submission->status === 'completed') text-green-600
                                    @elseif($submission->status === 'reviewed') text-blue-600
                                    @elseif($submission->status === 'rejected') text-red-600
                                    @else text-amber-600 @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 mb-1 truncate">{{ $submission->taskList->title }}</h4>
                                <p class="text-sm text-gray-600 mb-2 truncate">
                                    {{ $submission->completed_at ? 'Afgerond ' . $submission->completed_at->diffForHumans() : 'Gestart ' . $submission->created_at->diffForHumans() }}
                                </p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($submission->status === 'completed') bg-green-100 text-green-800 border border-green-200
                                    @elseif($submission->status === 'reviewed') bg-blue-100 text-blue-800 border border-blue-200
                                    @elseif($submission->status === 'rejected') bg-red-100 text-red-800 border border-red-200
                                    @else bg-amber-100 text-amber-800 border border-amber-200 @endif">
                                    @if($submission->status === 'completed') Afgerond
                                    @elseif($submission->status === 'reviewed') Beoordeeld
                                    @elseif($submission->status === 'rejected') Afgewezen
                                    @elseif($submission->status === 'in_progress') In Behandeling
                                    @else {{ ucfirst($submission->status) }} @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif -->

        <!-- Subtle Success Message -->
        @if($stats['completed_today'] > 0)
        <div class="mb-6 sm:mb-8">
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 sm:p-5 lg:p-6">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-green-900">Geweldige Voortgang Vandaag!</h3>
                        <p class="text-green-700 text-xs sm:text-sm lg:text-base">
                            Je hebt vandaag al {{ $stats['completed_today'] }} 
                            {{ $stats['completed_today'] === 1 ? 'Taak' : 'Taken' }} afgerond. 
                            Ga zo door met je uitstekende werk!
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    </div>
</div>

<!-- Enhanced JavaScript with Animations + Dynamic List Removal -->
<script>
function markNotificationAsRead(notificationId) {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
    button.disabled = true;
    
    fetch(`/employee/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.innerHTML = '<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            button.innerHTML = originalContent;
            button.disabled = false;
        }
    })
    .catch(error => {
        button.innerHTML = originalContent;
        button.disabled = false;
        console.error('Error:', error);
    });
}

function showDashboardToast(message, type = 'success') {
    const colors = {
        success: 'bg-green-500 border-green-600',
        info: 'bg-blue-500 border-blue-600',
        warning: 'bg-amber-500 border-amber-600',
        error: 'bg-red-500 border-red-600'
    };

    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white border ${colors[type] || colors.info} transform translate-y-4 opacity-0 transition-all duration-300 max-w-xs`;
    toast.innerHTML = `
        <div class="flex items-center space-x-2">
            <span class="text-sm font-medium">${message}</span>
            <button type="button" class="ml-2 text-white/80 hover:text-white" onclick="this.closest('div').remove()">
                ✕
            </button>
        </div>
    `;
    document.body.appendChild(toast);
    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-4', 'opacity-0');
    });
    setTimeout(() => {
        toast.classList.add('translate-y-4', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Lijst-kaart weg-animeren en verwijderen
function removeListCard(listId) {
    const card = document.querySelector(`[data-list-id="${listId}"]`);
    if (!card) return;

    card.style.transition = 'opacity 0.3s ease, transform 0.3s ease, max-height 0.3s ease';
    card.style.opacity = '0';
    card.style.transform = 'translateY(8px)';
    card.style.maxHeight = card.offsetHeight + 'px';

    setTimeout(() => {
        card.style.maxHeight = '0px';
    }, 10);

    setTimeout(() => {
        card.remove();
        updateTodaysListsCounter();
    }, 350);
}

// Counter “X lijsten toegewezen” updaten
function updateTodaysListsCounter() {
    const counterEl = document.getElementById('todays-lists-counter');
    if (!counterEl) return;

    const remaining = document.querySelectorAll('[data-list-id]').length;
    counterEl.dataset.totalLists = remaining;
    counterEl.textContent = `${remaining} ${remaining === 1 ? 'lijst toegewezen' : 'lijsten toegewezen'}`;
}

// Completed lijsten uit localStorage toepassen (bij load)
function applyCompletedListsFromStorage() {
    try {
        const todayKey = new Date().toISOString().slice(0, 10);
        Object.keys(localStorage).forEach(key => {
            if (key.startsWith('completed_list_')) {
                const parts = key.split(':');
                if (parts.length !== 2) {
                    localStorage.removeItem(key);
                    return;
                }
                const listId = parts[0].replace('completed_list_', '');
                const completionDate = parts[1];
                if (completionDate !== todayKey) {
                    localStorage.removeItem(key);
                    return;
                }
                removeListCard(listId);
            }
        });
    } catch (e) {
        console.warn('Kon localStorage niet lezen:', e);
    }
}

// Luister naar wijzigingen in localStorage (andere tab/pagina)
window.addEventListener('storage', function(e) {
    if (!e.key || !e.key.startsWith('completed_list_')) return;
    const parts = e.key.split(':');
    if (parts.length !== 2) return;
    const listId = parts[0].replace('completed_list_', '');
    const completionDate = parts[1];
    const todayKey = new Date().toISOString().slice(0, 10);
    if (completionDate !== todayKey) return;
    removeListCard(listId);
    showDashboardToast('Een lijst is zojuist afgerond en van je dashboard gehaald 🎉', 'success');
});

// Progress bar + animaties + init
document.addEventListener('DOMContentLoaded', function() {
    // Update huidige tijd elke minuut en check voor te late taken
    function updateTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const currentTimeStr = `${hours}:${minutes}`;
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = currentTimeStr;
        }
        
        // Check alle taken met tijdslots of ze te laat zijn en toon notificatie
        checkOverdueTasks(now);
    }
    
    // Functie om te checken welke taken te laat zijn
    function checkOverdueTasks(currentDate) {
        const taskItems = document.querySelectorAll('[data-task-time]');
        const overdueTasks = [];
        
        taskItems.forEach(taskItem => {
            const endTimeStr = taskItem.getAttribute('data-task-time');
            if (endTimeStr) {
                const [hours, minutes] = endTimeStr.split(':').map(Number);
                const endTime = new Date(currentDate);
                endTime.setHours(hours, minutes, 0, 0);
                
                const taskRow = taskItem.closest('.flex.items-center');
                // Skip als taak al completed is (heeft groene achtergrond)
                if (taskRow && taskRow.classList.contains('bg-green-50')) {
                    return;
                }
                
                if (currentDate > endTime && !taskItem.classList.contains('overdue')) {
                    // Taak is te laat geworden
                    taskItem.classList.add('overdue');
                    taskItem.classList.remove('upcoming');
                    
                    if (taskRow) {
                        taskRow.classList.add('bg-red-50', 'rounded-lg', 'p-2', '-mx-2');
                        taskRow.classList.remove('bg-blue-50');
                        const textElement = taskRow.querySelector('.text-sm');
                        if (textElement) {
                            textElement.classList.add('text-red-700', 'font-semibold');
                            textElement.classList.remove('text-blue-700');
                        }
                        // Voeg "Te laat!" toe aan tijdspan
                        const timeSpan = taskRow.querySelector('.text-xs');
                        if (timeSpan && !timeSpan.textContent.includes('Te laat')) {
                            timeSpan.innerHTML += ' <span class="font-bold">⚠️ Te laat!</span>';
                            timeSpan.classList.add('text-red-600', 'font-medium');
                            timeSpan.classList.remove('text-blue-600', 'text-gray-500');
                            
                            // Verzamel taak informatie voor notificatie
                            const taskTitle = textElement ? textElement.textContent.trim() : 'Taak';
                            const taskId = taskItem.getAttribute('data-task-id');
                            const listId = taskRow.closest('[data-list-id]')?.getAttribute('data-list-id');
                            
                            overdueTasks.push({
                                title: taskTitle,
                                taskId: taskId,
                                listId: listId
                            });
                        }
                    }
                }
            }
        });
        
        // Maak notificatie aan in database voor nieuwe te late taken (alleen eerste keer per taak)
        if (overdueTasks.length > 0) {
            overdueTasks.forEach((task, index) => {
                const notificationKey = 'overdue_notification_' + (task.taskId || task.title.replace(/\s+/g, '_').substring(0, 30));
                const notificationShown = sessionStorage.getItem(notificationKey);
                
                if (!notificationShown && task.taskId) {
                    // Wacht even voor elke volgende notificatie
                    setTimeout(() => {
                        // Maak notificatie aan in database via API
                        fetch('{{ route("employee.notifications.task-overdue") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                            },
                            body: JSON.stringify({
                                task_id: task.taskId,
                                task_title: task.title,
                                list_id: task.listId || null
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Notificatie succesvol aangemaakt
                                sessionStorage.setItem(notificationKey, 'true');
                                // Optioneel: update notificatie counter in menu
                                const notificationBadge = document.querySelector('.notification-badge');
                                if (notificationBadge) {
                                    const currentCount = parseInt(notificationBadge.textContent) || 0;
                                    notificationBadge.textContent = currentCount + 1;
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Fout bij aanmaken notificatie:', error);
                        });
                    }, index * 500); // 500ms delay tussen notificaties
                }
            });
        }
    }
    
    updateTime();
    setInterval(updateTime, 60000); // Update elke minuut
    
    // Smooth scroll naar anchors
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Progress bar animatie
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        const width = progressBar.style.width;
        progressBar.style.width = '0%';
        setTimeout(() => {
            progressBar.style.width = width;
        }, 500);
    }

    // Timeline item animaties
    const timelineItems = document.querySelectorAll('.timeline-item');
    timelineItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        setTimeout(() => {
            item.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 100 + 800);
    });

    // Ripple effect voor CTA-knoppen (start taak)
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

    const ctaButtons = document.querySelectorAll('.today-list-card form button[type="submit"]');
    ctaButtons.forEach(button => {
        button.addEventListener('click', createRipple);
    });

    // Hover effecten voor cards
    const taskCards = document.querySelectorAll('.bg-gray-50.rounded-xl');
    taskCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // LocalStorage voltooide lijsten toepassen + counter updaten
    applyCompletedListsFromStorage();
    updateTodaysListsCounter();
    
});


</script>

<style>
/* Responsive helpers for xs (min-width: 375px) */
@media (min-width: 375px) {
    .xs\:flex-row { flex-direction: row !important; }
    .xs\:items-center { align-items: center !important; }
    .xs\:justify-between { justify-content: space-between !important; }
    .xs\:mt-0 { margin-top: 0 !important; }
    .xs\:ml-4 { margin-left: 1rem !important; }
}

/* Touch-friendly: removes 300ms tap delay on mobile */
.touch-manipulation {
    touch-action: manipulation;
}

/* Prevent overflow on small screens - min-w-0 allows flex children to shrink */

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

/* Smooth transitions for interactive elements */
.task-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.task-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Timeline animation */
.timeline-item {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}

.timeline-item.animate {
    opacity: 1;
    transform: translateY(0);
}

/* Progress bar animation */
.progress-bar {
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    background: linear-gradient(90deg, #3b82f6, #2563eb);
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
}

/* Utility for truncating text on small screens */
.truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.break-words {
    word-break: break-word;
}
</style>
@endsection

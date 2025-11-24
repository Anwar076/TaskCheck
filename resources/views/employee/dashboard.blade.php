@extends('layouts.employee')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Greeting + Progress Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <!-- Greeting Content -->
                <div class="flex-1 min-w-0">
                    <div class="mb-6">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 break-words">
                            Goede{{ now()->hour < 12 ? 'morgen' : (now()->hour < 17 ? 'middag' : 'avond') }}, 
                            <span class="text-blue-600">{{ explode(' ', auth()->user()->name)[0] }}</span>
                        </h1>
                        <p class="text-gray-600 text-base sm:text-lg">{{ now()->translatedFormat('l, j F Y') }}</p>
                    </div>
                    
                    <!-- Linear Progress Bar -->
                    <div class="w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl">
                        @php
                            $totalForProgress = max(($stats['pending_tasks'] + $stats['completed_today'] + $stats['in_progress']), 1);
                            $progressPercent = round((($stats['completed_today'] + $stats['in_progress']) / $totalForProgress) * 100);
                        @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-1">
                            <span class="text-sm font-medium text-gray-700">Voortgang Vandaag</span>
                            <span class="text-sm font-bold text-blue-600">
                                {{ $progressPercent }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="progress-bar h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-1000 ease-out shadow-sm" 
                                 style="width: {{ $progressPercent }}%">
                            </div>
                        </div>
                        <div class="flex flex-col xs:flex-row xs:justify-between text-xs text-gray-500 mt-2 gap-1">
                            <span>{{ $stats['completed_today'] }} afgerond</span>
                            <span>{{ $stats['pending_tasks'] }} openstaand</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-2 sm:px-4 md:px-6 lg:px-8 py-6 sm:py-8">
        {{-- Alerts Section --}}
        @if($rejectedTasks->count() > 0 || $notifications->count() > 0)
            @php
                $rejectedCount = $rejectedTasks->count();
                $notificationCount = $notifications->count();
            @endphp

            <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2">

                {{-- AFGEWEZEN TAKEN --}}
                @if($rejectedCount > 0)
                    <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden flex flex-col h-full">
                        <div class="bg-gradient-to-r from-red-50 to-rose-50 border-b border-red-100 px-4 sm:px-5 py-3.5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-red-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856
                                                    c1.54 0 2.502-1.667 1.732-2.5L13.732 4
                                                    c-.77-.833-1.964-.833-2.732 0L3.732 16.5
                                                    c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm sm:text-base font-semibold text-red-900">
                                            Taken vereisen aandacht
                                        </h3>
                                        <p class="text-xs sm:text-sm text-red-700">
                                            {{ $rejectedCount }} afgewezen {{ Str::plural('taak', $rejectedCount) }} die je moet herstellen.
                                        </p>
                                    </div>
                                </div>
                                @if($rejectedCount > 3)
                                    <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                                        Top {{ min(3, $rejectedCount) }} getoond
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 sm:p-5 flex-1 flex flex-col">
                            @foreach($rejectedTasks->take(3) as $rejectedTask)
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 py-3 border-b border-slate-100 last:border-b-0">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-slate-900 truncate">
                                            {{ $rejectedTask->task->title }}
                                        </h4>
                                        <p class="text-xs text-slate-500 truncate">
                                            Checklist: {{ $rejectedTask->submission->taskList->title }}
                                        </p>
                                        @if($rejectedTask->rejection_reason)
                                            <p class="mt-1 text-xs text-red-600 line-clamp-2">
                                                Reden: {{ $rejectedTask->rejection_reason }}
                                            </p>
                                        @endif
                                    </div>

                                    <a href="{{ route('employee.submissions.edit', $rejectedTask->submission) }}"
                                    class="inline-flex items-center px-3.5 py-1.5 rounded-lg text-xs font-semibold text-white bg-red-600 hover:bg-red-700 shadow-sm transition-colors whitespace-nowrap">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581
                                                    m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        Herstel
                                    </a>
                                </div>
                            @endforeach

                            @if($rejectedCount > 3)
                                <div class="pt-3 text-center">
                                    {{-- Pas route aan als je een aparte pagina hebt voor afgewezen taken --}}
                                    <a href="{{ route('employee.notifications.index') }}"
                                    class="text-xs sm:text-sm font-semibold text-red-700 hover:text-red-800">
                                        Bekijk alle {{ $rejectedCount }} afgewezen taken
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- MELDINGEN --}}
                @if($notificationCount > 0)
                    <div class="bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden flex flex-col h-full">
                        <div class="bg-gradient-to-r from-blue-50 to-sky-50 border-b border-blue-100 px-4 sm:px-5 py-3.5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-5 5v-5z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm sm:text-base font-semibold text-slate-900">
                                            Meldingen
                                        </h3>
                                        <p class="text-xs sm:text-sm text-slate-600">
                                            {{ $notificationCount }} {{ Str::plural('melding', $notificationCount) }} in totaal.
                                        </p>
                                    </div>
                                </div>

                                <a href="{{ route('employee.notifications.index') }}"
                                class="hidden sm:inline-flex items-center text-xs font-semibold text-blue-700 hover:text-blue-800">
                                    Alles bekijken
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>

                        <div class="p-4 sm:p-5 flex-1 flex flex-col">
                            @foreach($notifications->take(3) as $notification)
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 py-3 border-b border-slate-100 last:border-b-0">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-slate-900 truncate">
                                            {{ $notification->title }}
                                        </h4>
                                        <p class="text-xs text-slate-500 truncate">
                                            {{ Str::limit($notification->message, 70) }}
                                        </p>
                                        <p class="mt-1 text-[11px] text-slate-400">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('employee.notifications.index') }}"
                                        class="inline-flex sm:hidden items-center px-3 py-1.5 rounded-lg text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-100">
                                            Open
                                        </a>

                                        @if(is_null($notification->read_at))
                                            <button
                                                onclick="markNotificationAsRead(this, {{ $notification->id }})"
                                                class="w-8 h-8 rounded-lg border border-emerald-100 bg-emerald-50 flex items-center justify-center hover:bg-emerald-100 transition-colors"
                                                title="Markeer als gelezen">
                                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            @if($notificationCount > 3)
                                <div class="pt-3 text-center">
                                    <a href="{{ route('employee.notifications.index') }}"
                                    class="text-xs sm:text-sm font-semibold text-blue-700 hover:text-blue-800">
                                        Bekijk alle meldingen
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @endif


        <!-- Main Content -->
        <div class="grid grid-cols-1 gap-6 mb-8 lg:grid-cols-3">
            <!-- Today's Tasks -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 hover:scale-[1.02]">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100 p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg sm:text-xl font-bold text-gray-900">Taken van Vandaag</h3>
                                    <p id="todays-lists-counter"
                                       class="text-gray-600 text-sm sm:text-base"
                                       data-total-lists="{{ $todaysLists->count() }}">
                                        {{ $todaysLists->count() }} lijsten toegewezen
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        @forelse($todaysLists as $list)
                        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 mb-6 last:mb-0 hover:bg-gray-100 transition-colors duration-200 today-list-card"
                             data-list-id="{{ $list->id }}">
                            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-4 gap-4">
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg sm:text-xl font-bold text-gray-900 mb-2 break-words">{{ $list->title }}</h4>
                                    <p class="text-gray-600 mb-4 break-words">{{ Str::limit($list->description, 120) }}</p>
                                    
                                    <div class="flex flex-wrap gap-2 mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                            @if($list->priority === 'urgent') bg-red-100 text-red-800 border border-red-200
                                            @elseif($list->priority === 'high') bg-orange-100 text-orange-800 border border-orange-200
                                            @elseif($list->priority === 'medium') bg-amber-100 text-amber-800 border border-amber-200
                                            @else bg-green-100 text-green-800 border border-green-200 @endif">
                                            @if($list->priority === 'urgent') Urgente
                                            @elseif($list->priority === 'high') Hoge
                                            @elseif($list->priority === 'medium') Gemiddelde
                                            @else Lage @endif Prioriteit
                                        </span>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                            {{ $list->tasks->count() }} taken
                                        </span>
                                        @if($list->requires_signature)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 border border-purple-200">
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
                                        <div class="flex items-center space-x-3">
                                            <div class="w-4 h-4 border-2 border-gray-300 rounded-sm flex items-center justify-center">
                                                <svg class="w-2 h-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm text-gray-700 truncate">{{ $task->title }}</span>
                                        </div>
                                        @endforeach
                                        @if($list->tasks->count() > 3)
                                        <div class="text-sm text-gray-500 ml-7">
                                            +{{ $list->tasks->count() - 3 }} meer taken
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                </div>

                                <!-- Start Task: maakt submission aan en gaat naar checklist -->
                                <form method="POST" action="{{ route('employee.submissions.start', $list) }}" class="mt-4 md:mt-0 md:ml-6 w-full md:w-auto">
                                    @csrf
                                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center group justify-center">
                                        <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        <span class="truncate">Start Taak</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12 sm:py-16">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto bg-gradient-to-br from-green-100 to-emerald-100 rounded-2xl flex items-center justify-center mb-4 sm:mb-6 shadow-lg">
                                <svg class="w-8 h-8 sm:w-10 sm:h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2 sm:mb-3">Alles klaar voor vandaag!</h3>
                            <p class="text-gray-600 text-base sm:text-lg">Je hebt alle toegewezen taken voltooid. Geweldig werk!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Activity Timeline -->
            <div>
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-50 to-slate-50 border-b border-gray-100 p-4 sm:p-6">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-gray-600 rounded-xl flex items-center justify-center mr-4">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900">Recente Activiteit</h3>
                                <p class="text-gray-600 text-sm sm:text-base">Jouw laatste inzendingen</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        @forelse($recentSubmissions as $index => $submission)
                        <div class="timeline-item relative {{ $index !== $recentSubmissions->count() - 1 ? 'pb-6' : '' }}">
                            @if($index !== $recentSubmissions->count() - 1)
                            <div class="absolute left-4 top-8 w-0.5 h-full bg-gray-200"></div>
                            @endif
                            <div class="flex items-start space-x-4">
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
                        @empty
                        <div class="text-center py-8 sm:py-12 text-gray-500">
                            <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto bg-gray-100 rounded-2xl flex items-center justify-center mb-3 sm:mb-4 shadow-lg">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-base sm:text-lg font-medium">Geen recente activiteit</p>
                            <p class="text-xs sm:text-sm">Begin met werken aan taken om hier voortgang te zien</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Subtle Success Message -->
        @if($stats['completed_today'] > 0)
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 sm:p-6 mb-8">
            <div class="flex items-center">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 sm:mr-4">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-semibold text-green-900">Geweldige voortgang vandaag!</h3>
                    <p class="text-green-700 text-sm sm:text-base">
                        Je hebt vandaag al {{ $stats['completed_today'] }} 
                        {{ $stats['completed_today'] === 1 ? 'taak' : 'taken' }} afgerond. 
                        Ga zo door met je uitstekende werk!
                    </p>
                </div>
            </div>
        </div>
        @endif
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
        Object.keys(localStorage).forEach(key => {
            if (key.startsWith('completed_list_')) {
                const listId = key.replace('completed_list_', '');
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
    const listId = e.key.replace('completed_list_', '');
    removeListCard(listId);
    showDashboardToast('Een lijst is zojuist afgerond en van je dashboard gehaald 🎉', 'success');
});

// Progress bar + animaties + init
document.addEventListener('DOMContentLoaded', function() {
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

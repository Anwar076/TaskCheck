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
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-1">
                            <span class="text-sm font-medium text-gray-700">Voortgang Vandaag</span>
                            <span class="text-sm font-bold text-blue-600">
                                {{ round((($stats['completed_today'] + $stats['in_progress']) / max(($stats['pending_tasks'] + $stats['completed_today'] + $stats['in_progress']), 1)) * 100) }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                            <div class="progress-bar h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-1000 ease-out shadow-sm" 
                                 style="width: {{ round((($stats['completed_today'] + $stats['in_progress']) / max(($stats['pending_tasks'] + $stats['completed_today'] + $stats['in_progress']), 1)) * 100) }}%">
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
        <!-- Alerts Section -->
        @if($rejectedTasks->count() > 0 || $notifications->count() > 0)
        <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2">
            @if($rejectedTasks->count() > 0)
            <div class="bg-white rounded-xl border border-red-200 overflow-hidden flex flex-col h-full">
                <div class="bg-red-50 border-b border-red-200 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Taken Vereisen Aandacht</h3>
                                <p class="text-sm text-gray-600">{{ $rejectedTasks->count() }} afgewezen taken</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4 flex-1 flex flex-col">
                    @foreach($rejectedTasks->take(3) as $rejectedTask)
                    <div class="flex flex-col xs:flex-row items-start xs:items-center justify-between py-3 border-b border-gray-100 last:border-b-0 gap-2">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-gray-900 truncate">{{ $rejectedTask->task->title }}</h4>
                            <p class="text-sm text-gray-600 truncate">{{ $rejectedTask->submission->taskList->title }}</p>
                        </div>
                        <a href="{{ route('employee.submissions.edit', $rejectedTask->submission) }}" 
                           class="mt-2 xs:mt-0 xs:ml-4 bg-red-600 text-white px-3 py-1 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors whitespace-nowrap">
                            Herstel
                        </a>
                    </div>
                    @endforeach
                    @if($rejectedTasks->count() > 3)
                    <div class="text-center mt-3">
                        <a href="#" class="text-sm text-red-600 hover:text-red-700 font-medium">Bekijk alle {{ $rejectedTasks->count() }} taken</a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            @if($notifications->count() > 0)
            <div class="bg-white rounded-xl border border-blue-200 overflow-hidden flex flex-col h-full">
                <div class="bg-blue-50 border-b border-blue-200 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Meldingen</h3>
                                <p class="text-sm text-gray-600">{{ $notifications->count() }} ongelezen</p>
                            </div>
                        </div>
                        <a href="{{ route('employee.notifications.index') }}" 
                           class="text-sm text-blue-600 hover:text-blue-700 font-medium mt-2 sm:mt-0">Bekijk alle</a>
                    </div>
                </div>
                <div class="p-4 flex-1 flex flex-col">
                    @foreach($notifications->take(3) as $notification)
                    <div class="flex flex-col xs:flex-row items-start xs:items-center justify-between py-3 border-b border-gray-100 last:border-b-0 gap-2">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-gray-900 truncate">{{ $notification->title }}</h4>
                            <p class="text-sm text-gray-600 truncate">{{ Str::limit($notification->message, 60) }}</p>
                        </div>
                        <button onclick="markNotificationAsRead({{ $notification->id }})" 
                                class="mt-2 xs:mt-0 xs:ml-4 bg-blue-600 text-white p-1 rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </button>
                    </div>
                    @endforeach
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
                                    <p class="text-gray-600 text-sm sm:text-base">{{ $todaysLists->count() }} lijsten toegewezen</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-4 sm:p-6">
                        @forelse($todaysLists as $list)
                        <div class="bg-gray-50 rounded-xl p-4 sm:p-6 mb-6 last:mb-0 hover:bg-gray-100 transition-colors duration-200">
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
                                <a href="{{ route('employee.lists.show', $list) }}" 
                                   class="mt-4 md:mt-0 md:ml-6 bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center group w-full md:w-auto justify-center">
                                    <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <span class="truncate">Start Taak</span>
                                </a>
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

<!-- Enhanced JavaScript with Animations -->
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

// Progress bar animation
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bar on load
    const progressBar = document.querySelector('.progress-bar');
    if (progressBar) {
        const width = progressBar.style.width;
        progressBar.style.width = '0%';
        setTimeout(() => {
            progressBar.style.width = width;
        }, 500);
    }

    // Timeline item animations
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

    // Add ripple effect to CTA buttons
    const ctaButtons = document.querySelectorAll('a[href*="lists.show"]');
    ctaButtons.forEach(button => {
        button.addEventListener('click', createRipple);
    });

    // Hover effects for task cards
    const taskCards = document.querySelectorAll('.bg-gray-50.rounded-xl');
    taskCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
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

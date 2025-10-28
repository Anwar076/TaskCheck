@extends('layouts.employee')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Clean Header Section -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center justify-between mb-6">
                <button onclick="history.back()" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors group">
                    <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span class="font-medium">Terug naar Taken</span>
                </button>
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $list->title }}</h1>
                @if($list->description)
                    <p class="text-gray-600 text-lg">{{ $list->description }}</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Priority & Status Badges -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-10">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border
                    @if($list->priority === 'urgent') bg-red-100 text-red-800 border-red-200
                    @elseif($list->priority === 'high') bg-orange-100 text-orange-800 border-orange-200
                    @elseif($list->priority === 'medium') bg-amber-100 text-amber-800 border-amber-200
                    @else bg-green-100 text-green-800 border-green-200 @endif">
                    {{ ucfirst($list->priority) }} Prioriteit
                </span>
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-800 border border-gray-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    {{ $list->tasks->count() }} Taken
                </span>
                @if($list->requires_signature)
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-purple-100 text-purple-800 border border-purple-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                        </svg>
                        Handtekening Vereist
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Task Overview -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Taakoverzicht</h3>
                        <p class="text-gray-600">Voltooi deze taken in volgorde</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($list->tasks as $index => $task)
                        <div class="flex items-start p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200 task-item">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold text-blue-700">{{ $index + 1 }}</span>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="font-semibold text-gray-900 mb-2">{{ $task->title }}</h4>
                                @if($task->description)
                                    <p class="text-sm text-gray-600 mb-3">{{ Str::limit($task->description, 120) }}</p>
                                @endif
                                @if($task->checklist_items && count($task->checklist_items) > 0)
                                    <div class="mb-3 text-xs">
                                        <span class="inline-flex items-center px-2 py-1 rounded-md bg-cyan-100 text-cyan-800 font-medium">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                            </svg>
                                            {{ count($task->checklist_items) }} checklist stappen
                                        </span>
                                    </div>
                                @endif
                                <div class="flex flex-wrap items-center gap-2">
                                    @if($task->is_required)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200">
                                            Vereist
                                        </span>
                                    @endif
                                    @if($task->requires_signature)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            Handtekening
                                        </span>
                                    @endif
                                    @if($task->required_proof_type !== 'none')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                            </svg>
                                            {{ ucfirst($task->required_proof_type) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions Card -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100 p-6">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Hoe het werkt</h3>
                        <p class="text-gray-600">Volg deze stappen om uw taken te voltooien</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <span class="text-sm font-bold text-blue-700">1</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Voltooi Taken</h4>
                            <p class="text-sm text-gray-600">Werk elke taak in de aangegeven volgorde door</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <span class="text-sm font-bold text-blue-700">2</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Upload Bewijs</h4>
                            <p class="text-sm text-gray-600">Lever de vereiste documentatie aan wanneer nodig</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <span class="text-sm font-bold text-blue-700">3</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Indienen</h4>
                            <p class="text-sm text-gray-600">@if($list->requires_signature) Handteken en dien in ter beoordeling @else Dien in ter beoordeling @endif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Button -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        @if($existingSubmission)
            <a href="{{ route('employee.submissions.edit', $existingSubmission) }}" 
               class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-xl font-semibold text-center block hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center group">
                <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Blijf Werken
            </a>
        @else
            <form method="POST" action="{{ route('employee.submissions.start', $list) }}">
                @csrf
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-8 py-4 rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center group">
                    <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Start Taak
                </button>
            </form>
        @endif
    </div>
</div>

<!-- Enhanced JavaScript with Animations -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Card animations
    const cards = document.querySelectorAll('.bg-white.rounded-2xl');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 200 + 300);
    });

    // Task item animations
    const taskItems = document.querySelectorAll('.task-item');
    taskItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        item.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
        
        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateX(0)';
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

    // Add ripple effect to action buttons
    const actionButtons = document.querySelectorAll('button[type="submit"], a[href*="submissions.edit"]');
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

    // Back button animation
    const backButton = document.querySelector('button[onclick="history.back()"]');
    if (backButton) {
        backButton.addEventListener('click', function(e) {
            e.preventDefault();
            this.style.transform = 'translateX(-4px)';
            setTimeout(() => {
                history.back();
            }, 150);
        });
    }
});
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

/* Task item hover effects */
.task-item {
    transition: all 0.2s ease-out;
}

.task-item:hover {
    transform: translateX(4px);
}

/* Button hover effects */
button, a[role="button"] {
    position: relative;
    overflow: hidden;
}

/* Smooth transitions for interactive elements */
.bg-white.rounded-2xl {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.bg-white.rounded-2xl:hover {
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
@endsection
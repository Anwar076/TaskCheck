@extends('layouts.employee')

@section('content')
<div class="min-h-screen bg-gray-50 pt-6 sm:pt-8">
    {{-- Hero (floating block, zelfde breedte als filter) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 sm:mb-8">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Mijn Taken</h1>
                        <p class="text-gray-600 text-lg">{{ $assignedLists->count() }} Lijsten Toegewezen</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar (floating block, zelfde breedte als hero) --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-4 sm:p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prioriteit</label>
                    <select data-filter="priority" class="filter-select w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all">
                        <option value="">Alle Prioriteiten</option>
                        <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgente Prioriteit</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>Hoge Prioriteit</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Middelmatige Prioriteit</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Lage Prioriteit</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categorie</label>
                    <select data-filter="category" class="filter-select w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all">
                        <option value="">Alle Categorieën</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select data-filter="status" class="filter-select w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all">
                        <option value="">Alle Statussen</option>
                        <option value="openstaand" {{ request('status') === 'openstaand' ? 'selected' : '' }}>Openstaande Taken</option>
                        <option value="afgerond" {{ request('status') === 'afgerond' ? 'selected' : '' }}>Afgeronde Taken</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Cards Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($assignedLists as $list)
                @php
                    // Check if user has already started/completed this list today
                    $existingSubmission = \App\Models\Submission::where('user_id', auth()->id())
                        ->where('list_id', $list->id)
                        ->whereDate('created_at', today())
                        ->first();
                @endphp
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 hover:scale-[1.02] task-card">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100 p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $list->title }}</h3>
                                @if($list->description)
                                    <p class="text-gray-600 text-sm leading-relaxed mb-3">{{ Str::limit($list->description, 120) }}</p>
                                @endif
                                @php
                                    $taskUrl = $existingSubmission ? route('employee.submissions.edit', $existingSubmission) : route('employee.lists.show', $list);
                                    $priorityConfig = [
                                        'urgent' => ['label' => 'Urgente Prioriteit', 'class' => 'bg-red-100 text-red-800 border-red-200 hover:bg-red-200', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z'],
                                        'high' => ['label' => 'Hoge Prioriteit', 'class' => 'bg-orange-100 text-orange-800 border-orange-200 hover:bg-orange-200', 'icon' => 'M5 10l7-7m0 0l7 7m-7-7v18'],
                                        'medium' => ['label' => 'Gemiddelde Prioriteit', 'class' => 'bg-amber-100 text-amber-800 border-amber-200 hover:bg-amber-200', 'icon' => 'M20 12H4'],
                                        'low' => ['label' => 'Lage Prioriteit', 'class' => 'bg-green-100 text-green-800 border-green-200 hover:bg-green-200', 'icon' => 'M19 14l-7 7m0 0l-7-7m7 7V3']
                                    ];
                                    $priority = $priorityConfig[$list->priority] ?? $priorityConfig['medium'];
                                @endphp
                                <a href="{{ $taskUrl }}" class="inline-flex flex-wrap items-center gap-2 text-sm mt-2">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-medium border transition-colors {{ $priority['class'] }}">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $priority['icon'] }}"/></svg>
                                        {{ $priority['label'] }}
                                    </span>
                                    @if($existingSubmission)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-medium border transition-colors
                                            @if($existingSubmission->status === 'completed') bg-green-100 text-green-800 border-green-200 hover:bg-green-200
                                            @elseif($existingSubmission->status === 'in_progress') bg-blue-100 text-blue-800 border-blue-200 hover:bg-blue-200
                                            @else bg-gray-100 text-gray-800 border-gray-200 hover:bg-gray-200 @endif">
                                            @php
                                                $statusLabels = ['in_progress' => 'Bezig', 'completed' => 'Voltooid', 'reviewed' => 'Goedgekeurd', 'rejected' => 'Afgewezen', 'redo_requested' => 'Opnieuw vereist'];
                                            @endphp
                                            {{ $statusLabels[$existingSubmission->status] ?? ucfirst($existingSubmission->status) }}
                                        </span>
                                    @endif
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-medium border bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                        {{ $list->tasks->count() }} Taken
                                    </span>
                                    @if($list->requires_signature)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full font-medium border bg-purple-50 text-purple-700 border-purple-200 hover:bg-purple-100 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            Handtekening Vereist
                                        </span>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card Content -->
                    <div class="p-6">

                        <!-- Progress Bar (if in progress) -->
                        @if($existingSubmission && $existingSubmission->status === 'in_progress')
                            @php
                                $completedTasks = $existingSubmission->submissionTasks->where('status', 'completed')->count();
                                $totalTasks = $existingSubmission->submissionTasks->count();
                                $percentage = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                            @endphp
                            <div class="mb-6">
                                <div class="flex justify-between text-sm text-gray-600 mb-2">
                                    <span class="font-medium">Voortgang</span>
                                    <span class="font-semibold">{{ $completedTasks }}/{{ $totalTasks }} Taken</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="progress-bar h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-1000 ease-out shadow-sm" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Button -->
                        <div class="mt-6">
                            @if($existingSubmission)
                                @if($existingSubmission->status === 'in_progress')
                                    <a href="{{ route('employee.submissions.edit', $existingSubmission) }}" 
                                       class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-semibold text-center block hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center group">
                                        <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Taak voortzetten
                                    </a>
                                @elseif($existingSubmission->status === 'completed')
                                    <a href="{{ route('employee.submissions.edit', $existingSubmission) }}" 
                                       class="w-full bg-gradient-to-r from-green-50 to-emerald-50 text-green-800 px-6 py-3 rounded-xl font-semibold text-center border border-green-200 flex items-center justify-center hover:from-green-100 hover:to-emerald-100 hover:border-green-300 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Vandaag voltooid — Bekijk
                                    </a>
                                @elseif($existingSubmission->status === 'reviewed')
                                    <a href="{{ route('employee.submissions.edit', $existingSubmission) }}" 
                                       class="w-full bg-gradient-to-r from-green-50 to-emerald-50 text-green-800 px-6 py-3 rounded-xl font-semibold text-center border border-green-200 flex items-center justify-center hover:from-green-100 hover:to-emerald-100 hover:border-green-300 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Goedgekeurd — Bekijk
                                    </a>
                                @elseif($existingSubmission->status === 'rejected')
                                    <a href="{{ route('employee.submissions.edit', $existingSubmission) }}" 
                                       class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white px-6 py-3 rounded-xl font-semibold text-center block hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center group">
                                        <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                        Herstel Vereist
                                    </a>
                                @endif
                            @else
                                <form method="POST" action="{{ route('employee.submissions.start', $list) }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl font-semibold text-center hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 flex items-center justify-center group">
                                        <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Start Taak
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center mb-6 shadow-lg">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">Geen Toegewezen Taken</h3>
                        <p class="text-gray-600 mb-6 text-lg">
                            @if(request('priority') || request('category') || request('status'))
                                Geen Taken voldoen aan uw huidige filters.
                            @else
                                U heeft nog geen Taken toegewezen gekregen. Kijk later nog eens!
                            @endif
                        </p>
                        @if(request('priority') || request('category') || request('status'))
                            <a href="{{ route('employee.lists.index') }}" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl font-semibold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Filters Wissen
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Filter: preserve all params when changing -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = '{{ route("employee.lists.index") }}';
    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', function() {
            const param = this.dataset.filter;
            const value = this.value;
            const params = new URLSearchParams(window.location.search);
            if (value) params.set(param, value);
            else params.delete(param);
            window.location.href = baseUrl + (params.toString() ? '?' + params.toString() : '');
        });
    });
});
</script>

<!-- Enhanced JavaScript with Animations -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Card animations
    const cards = document.querySelectorAll('.task-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150 + 300);
    });

    // Progress bar animations
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 800);
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
    const actionButtons = document.querySelectorAll('a[href*="lists.show"], a[href*="submissions.edit"]');
    actionButtons.forEach(button => {
        button.addEventListener('click', createRipple);
    });

    // Touch feedback for mobile
    document.addEventListener('touchstart', function(e) {
        if (e.target.closest('a')) {
            e.target.closest('a').style.transform = 'scale(0.98)';
        }
    });

    document.addEventListener('touchend', function(e) {
        if (e.target.closest('a')) {
            setTimeout(() => {
                e.target.closest('a').style.transform = '';
            }, 150);
        }
    });
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

/* Progress bar animation */
.progress-bar {
    transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
    background: linear-gradient(90deg, #3b82f6, #2563eb);
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
}

/* Task card hover effects */
.task-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.task-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}
</style>
@endsection
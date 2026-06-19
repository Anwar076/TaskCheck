@extends('layouts.employee')

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-8 pb-8 sm:pb-12 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- HEADER (floating block) --}}
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-200 overflow-hidden mb-4 sm:mb-8">
        <div class="px-4 sm:px-6 lg:px-8 py-5 sm:py-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-1">Meldingen</h1>

                    @php
                        $totalCount = $notifications->total();
                        $unreadCount = $notifications->whereNull('read_at')->count();
                    @endphp

                    <p class="text-slate-600 text-sm sm:text-base">
                        <span class="font-semibold">{{ $totalCount }}</span> Totaal
                        @if($unreadCount > 0)
                            • <span class="font-semibold text-blue-600">{{ $unreadCount }}</span> Ongelezen
                        @else
                            • Alles Gelezen 🎉
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="hidden sm:flex flex-col items-end text-xs text-slate-500">
                        <span>Je meldingen worden automatisch vernieuwd</span>
                        <span>Ongelezen meldingen worden vet en met een blauwe stip getoond</span>
                    </div>
                    <div class="w-10 h-10 sm:w-11 sm:h-11 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl sm:rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTION BAR (zelfde breedte als hero) --}}
    @if($notifications->count() > 0 && $unreadCount > 0)
        <div class="pt-3 sm:pt-6 relative z-10 w-full">
            <div class="w-full bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-900">Ongelezen Meldingen</p>
                        <p class="text-xs text-slate-500">
                            Je hebt <span class="font-semibold text-blue-600">{{ $unreadCount }}</span> Ongelezen Melding(en).
                        </p>
                    </div>
                </div>

                <button onclick="markAllAsRead(this)"
                        class="js-ripple inline-flex items-center justify-center w-full sm:w-auto min-h-[44px] px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 touch-manipulation">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    Markeer Alles Als Gelezen
                </button>
            </div>
        </div>
    @endif

    {{-- NOTIFICATION LIST (zelfde breedte als hero) --}}
    <div class="py-4 sm:py-8 w-full">
        <div class="space-y-3 sm:space-y-4 w-full">
            @forelse($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $type = $notification->type;
                    $data = $notification->data ?? [];
                    $taskTitle = $data['task_title'] ?? null;
                    if ($type === 'task_rejected' && $taskTitle && isset($data['reason'])) {
                        $displayTitle = "Je taak '{$taskTitle}' is afgewezen";
                        $displayMessage = $data['reason'] . "\n\nVoer deze taak opnieuw uit en dien daarna de checklist opnieuw in.";
                    } elseif ($type === 'task_redo_requested' && $taskTitle) {
                        $displayTitle = "Herhaal taak '{$taskTitle}'";
                        $displayMessage = ($data['redo_reason'] ?? null)
                            ? "Reden: " . ($data['redo_reason']) . "\n\nVoer deze taak opnieuw uit om de checklist te kunnen afronden."
                            : "Voer deze taak opnieuw uit om de checklist te kunnen afronden.";
                    } else {
                        $displayTitle = $notification->title;
                        $displayMessage = $notification->message;
                    }
                    $targetUrl = $data['url'] ?? (isset($data['submission_id']) ? route('employee.submissions.edit', $data['submission_id']) : null);
                    if (is_string($targetUrl) && str_ends_with($targetUrl, '/edit')) {
                        $targetUrl = substr($targetUrl, 0, -5);
                    }
                    $borderColor = match ($type) {
                        'task_rejected' => 'border-red-400',
                        'task_redo_requested' => 'border-amber-400',
                        default => 'border-blue-400',
                    };
                    $badgeLabel = match ($type) {
                        'task_rejected' => 'Taak Afgewezen',
                        'task_redo_requested' => 'Opnieuw Uitvoeren',
                        default => 'Algemene Melding',
                    };
                    $badgeColor = match ($type) {
                        'task_rejected' => 'bg-red-50 text-red-700 border-red-100',
                        'task_redo_requested' => 'bg-amber-50 text-amber-700 border-amber-100',
                        default => 'bg-blue-50 text-blue-700 border-blue-100',
                    };
                @endphp

                <article
                    class="notification-card bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative {{ $isUnread ? 'ring-1 ring-blue-50' : 'opacity-80' }}">
                    <div class="absolute inset-y-0 left-0 w-1 sm:w-1.5 {{ $borderColor }}"></div>

                    <div class="p-4 sm:p-6 pl-5 sm:pl-7">
                        <div class="flex items-start gap-3 sm:gap-4">
                            {{-- Icon --}}
                            <div class="flex-shrink-0 mt-0.5 sm:mt-1">
                                @if($type === 'task_rejected')
                                    <div class="w-9 h-9 sm:w-11 sm:h-11 bg-red-50 rounded-lg sm:rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                @elseif($type === 'task_redo_requested')
                                    <div class="w-9 h-9 sm:w-11 sm:h-11 bg-amber-50 rounded-lg sm:rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-9 h-9 sm:w-11 sm:h-11 bg-blue-50 rounded-lg sm:rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0 overflow-hidden">
                                <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-start sm:justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-2 min-w-0">
                                        @if($isUnread)
                                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                                        @endif
                                        <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-slate-900 break-words {{ $isUnread ? '' : 'font-normal' }}">
                                            {{ $displayTitle }}
                                        </h3>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 flex-shrink-0">
                                        <span class="inline-flex items-center px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-full text-[10px] sm:text-[11px] font-medium border {{ $badgeColor }}">
                                            {{ $badgeLabel }}
                                        </span>
                                        @if($isUnread)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-[11px] font-semibold bg-blue-600 text-white">
                                                Nieuw
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-3 break-words whitespace-pre-line">
                                    {{ $displayMessage }}
                                </p>

                                <div class="flex flex-col sm:flex-row sm:flex-wrap sm:items-center sm:justify-between gap-2 sm:gap-3 pt-3 border-t border-slate-100">
                                    <span class="text-xs text-slate-500 order-2 sm:order-1">
                                        {{ $notification->created_at->locale('nl')->diffForHumans() }}
                                    </span>

                                    <div class="flex items-center gap-2 order-1 sm:order-2">
                                        @if($targetUrl)
                                            <a href="{{ $targetUrl }}"
                                               class="js-ripple inline-flex items-center justify-center min-h-[44px] px-3 py-2.5 sm:py-1.5 rounded-xl sm:rounded-lg text-xs sm:text-sm font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-100 transition-colors touch-manipulation">
                                                <span>Bekijk Taak</span>
                                                <svg class="w-4 h-4 ml-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                                </svg>
                                            </a>
                                        @endif

                                        @if($isUnread)
                                            <button
                                                onclick="markAsRead(this, {{ $notification->id }})"
                                                class="js-ripple min-h-[44px] min-w-[44px] w-11 h-11 sm:w-8 sm:h-8 sm:min-h-0 sm:min-w-0 rounded-xl sm:rounded-lg border border-green-100 bg-green-50 flex items-center justify-center hover:bg-green-100 transition-colors touch-manipulation"
                                                title="Markeer Als Gelezen">
                                                <svg class="w-5 h-5 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </button>
                                        @endif

                                        <button
                                            onclick="deleteNotification(this, {{ $notification->id }})"
                                            class="js-ripple min-h-[44px] min-w-[44px] w-11 h-11 sm:w-8 sm:h-8 sm:min-h-0 sm:min-w-0 rounded-xl sm:rounded-lg border border-red-100 bg-red-50 flex items-center justify-center hover:bg-red-100 transition-colors touch-manipulation"
                                            title="Verwijder Melding">
                                            <svg class="w-5 h-5 sm:w-4 sm:h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-10 text-center">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl sm:rounded-3xl flex items-center justify-center mb-4 sm:mb-6 shadow-inner">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                        </svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-slate-900 mb-2">Je Bent Helemaal Bij!</h3>
                    <p class="text-slate-600 mb-4 sm:mb-6 text-sm sm:text-base px-2">
                        Er zijn op dit moment geen meldingen om weer te geven.
                    </p>
                    <a href="{{ route('employee.dashboard') }}"
                       class="js-ripple inline-flex items-center justify-center min-h-[44px] px-6 py-3 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md hover:from-blue-700 hover:to-indigo-700 transition-all touch-manipulation">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                        </svg>
                        Terug Naar Dashboard
                    </a>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        @if($notifications->hasPages())
            <div class="mt-6 sm:mt-8">
                <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 p-3 sm:p-5 overflow-x-auto">
                    {{ $notifications->links() }}
                </div>
            </div>
        @endif
    </div>
    </div>
</div>

{{-- SCRIPTS --}}
<script>
    function markAsRead(button, notificationId) {
        const card = button.closest('.notification-card');
        const original = button.innerHTML;

        button.disabled = true;
        button.innerHTML =
            '<svg class="w-4 h-4 text-green-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
            '<circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle>' +
            '<path class="opacity-75" stroke-width="4" d="M4 12a8 8 0 018-8v4m0-4l3-3m-3 3L9 7"/>' +
            '</svg>';

        fetch(`/employee/notifications/${notificationId}/mark-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) throw new Error('Mislukt');
            // Mooie fade-out
            card.style.opacity = '0';
            card.style.transform = 'translateY(-6px)';
            card.style.transition = 'all .25s ease-out';
            setTimeout(() => card.remove(), 260);
        })
        .catch(() => {
            button.disabled = false;
            button.innerHTML = original;
        });
    }

    function markAllAsRead(button) {
        const original = button.innerHTML;
        button.disabled = true;
        button.innerHTML =
            '<svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
            '<circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle>' +
            '<path class="opacity-75" stroke-width="4" d="M4 12a8 8 0 018-8v4m0-4l3-3m-3 3L9 7"/>' +
            '</svg>Bezig...';

        fetch('/employee/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) throw new Error('Mislukt');
            document.querySelectorAll('.notification-card').forEach((card, i) => {
                setTimeout(() => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(-6px)';
                    card.style.transition = 'all .25s ease-out';
                }, i * 40);
            });
            setTimeout(() => location.reload(), 400);
        })
        .catch(() => {
            button.disabled = false;
            button.innerHTML = original;
        });
    }

    function deleteNotification(button, notificationId) {
        if (!confirm('Weet je zeker dat je deze melding wilt verwijderen?')) return;

        const card = button.closest('.notification-card');
        const original = button.innerHTML;

        button.disabled = true;
        button.innerHTML =
            '<svg class="w-4 h-4 text-red-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
            '<circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle>' +
            '<path class="opacity-75" stroke-width="4" d="M4 12a8 8 0 018-8v4m0-4l3-3m-3 3L9 7"/>' +
            '</svg>';

        fetch(`/employee/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) throw new Error('Mislukt');
            card.style.opacity = '0';
            card.style.transform = 'translateX(-12px)';
            card.style.transition = 'all .25s ease-out';
            setTimeout(() => card.remove(), 260);
        })
        .catch(() => {
            button.disabled = false;
            button.innerHTML = original;
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Cards subtiel laten binnenkomen
        document.querySelectorAll('.notification-card').forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(14px)';
            card.style.transition = 'opacity .4s ease-out, transform .4s ease-out';
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 120 + index * 80);
        });

        // Ripple effect
        function createRipple(event) {
            const button = event.currentTarget;
            const rect = button.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = event.clientX - rect.left - size / 2;
            const y = event.clientY - rect.top - size / 2;

            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;

            button.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        }

        document.querySelectorAll('.js-ripple').forEach(btn => {
            btn.addEventListener('click', createRipple);
        });
    });
</script>

<style>
    .ripple {
        position: absolute;
        border-radius: 9999px;
        background: rgba(255, 255, 255, 0.35);
        transform: scale(0);
        animation: ripple-animation 0.6s linear;
        pointer-events: none;
    }

    @keyframes ripple-animation {
        to {
            transform: scale(3.5);
            opacity: 0;
        }
    }

    .notification-card {
        border-radius: 1.25rem;
        background-clip: padding-box;
    }

    button,
    a[role="button"],
    .js-ripple {
        position: relative;
        overflow: hidden;
    }
</style>
@endsection

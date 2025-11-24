@extends('layouts.employee')

@section('content')
<div class="min-h-screen bg-slate-50">

    {{-- HEADER --}}
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-1">Meldingen</h1>

                    @php
                        $totalCount = $notifications->total();
                        $unreadCount = $notifications->whereNull('read_at')->count();
                    @endphp

                    <p class="text-slate-600 text-sm sm:text-base">
                        <span class="font-semibold">{{ $totalCount }}</span> totaal
                        @if($unreadCount > 0)
                            • <span class="font-semibold text-blue-600">{{ $unreadCount }}</span> ongelezen
                        @else
                            • alles gelezen 🎉
                        @endif
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex flex-col items-end text-xs text-slate-500">
                        <span>Je meldingen worden automatisch vernieuwd</span>
                        <span>Ongelezen meldingen worden vet en met een blauwe stip getoond</span>
                    </div>
                    <div class="w-11 h-11 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 16h-1v-4h-1m1-4h.01
                                     M21 12a9 9 0 11-18 0
                                     9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ACTION BAR --}}
    @if($notifications->count() > 0 && $unreadCount > 0)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-10">
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                        <span class="w-2 h-2 rounded-full bg-blue-500 block"></span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Ongelezen meldingen</p>
                        <p class="text-xs text-slate-500">
                            Je hebt <span class="font-semibold text-blue-600">{{ $unreadCount }}</span> ongelezen melding(en).
                        </p>
                    </div>
                </div>

                <button onclick="markAllAsRead(this)"
                        class="js-ripple inline-flex items-center justify-center w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md hover:from-blue-700 hover:to-indigo-700 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                    Markeer alles als gelezen
                </button>
            </div>
        </div>
    @endif

    {{-- NOTIFICATION LIST --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="space-y-4">
            @forelse($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $type = $notification->type;
                    $borderColor = match ($type) {
                        'task_rejected' => 'border-red-400',
                        'task_redo_requested' => 'border-amber-400',
                        default => 'border-blue-400',
                    };
                    $badgeLabel = match ($type) {
                        'task_rejected' => 'Taak afgekeurd',
                        'task_redo_requested' => 'Opnieuw uitvoeren',
                        default => 'Algemene melding',
                    };
                    $badgeColor = match ($type) {
                        'task_rejected' => 'bg-red-50 text-red-700 border-red-100',
                        'task_redo_requested' => 'bg-amber-50 text-amber-700 border-amber-100',
                        default => 'bg-blue-50 text-blue-700 border-blue-100',
                    };
                @endphp

                <article
                    class="notification-card bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative {{ $isUnread ? 'ring-1 ring-blue-50' : 'opacity-80' }}">
                    <div class="absolute inset-y-0 left-0 w-1.5 {{ $borderColor }}"></div>

                    <div class="p-5 sm:p-6 pl-6 sm:pl-7">
                        <div class="flex items-start gap-4">
                            {{-- Icon --}}
                            <div class="flex-shrink-0 mt-1">
                                @if($type === 'task_rejected')
                                    <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 9v2m0 4h.01m-6.938 4h13.856
                                                     c1.54 0 2.502-1.667 1.732-2.5L13.732 4
                                                     c-.77-.833-1.964-.833-2.732 0L3.732 16.5
                                                     c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                    </div>
                                @elseif($type === 'task_redo_requested')
                                    <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581
                                                     m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 17h5l-5 5v-5z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                                    <div class="flex items-center gap-2">
                                        @if($isUnread)
                                            <span class="inline-block w-2 h-2 rounded-full bg-blue-500"></span>
                                        @endif
                                        <h3 class="text-base sm:text-lg font-semibold text-slate-900 {{ $isUnread ? '' : 'font-normal' }}">
                                            {{ $notification->title }}
                                        </h3>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium border {{ $badgeColor }}">
                                            {{ $badgeLabel }}
                                        </span>
                                        @if($isUnread)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-blue-600 text-white">
                                                Nieuw
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <p class="text-sm text-slate-600 leading-relaxed mb-3">
                                    {{ $notification->message }}
                                </p>

                                <div class="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100">
                                    <span class="text-xs sm:text-sm text-slate-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>

                                    <div class="flex items-center gap-2">
                                        @if($notification->data && isset($notification->data['submission_id']))
                                            <a href="{{ route('employee.submissions.edit', $notification->data['submission_id']) }}"
                                               class="js-ripple inline-flex items-center px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-100 transition-colors">
                                                <span>Bekijk taak</span>
                                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                        @endif

                                        @if($isUnread)
                                            <button
                                                onclick="markAsRead(this, {{ $notification->id }})"
                                                class="js-ripple w-8 h-8 rounded-lg border border-green-100 bg-green-50 flex items-center justify-center hover:bg-green-100 transition-colors"
                                                title="Markeer als gelezen">
                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M5 13l4 4L19 7"/>
                                                </svg>
                                            </button>
                                        @endif

                                        <button
                                            onclick="deleteNotification(this, {{ $notification->id }})"
                                            class="js-ripple w-8 h-8 rounded-lg border border-red-100 bg-red-50 flex items-center justify-center hover:bg-red-100 transition-colors"
                                            title="Verwijder melding">
                                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7
                                                         m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-10 text-center">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-slate-50 to-slate-100 rounded-3xl flex items-center justify-center mb-6 shadow-inner">
                        <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 17h5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">Je bent helemaal bij!</h3>
                    <p class="text-slate-600 mb-6 text-sm sm:text-base">
                        Er zijn op dit moment geen meldingen om weer te geven.
                    </p>
                    <a href="{{ route('employee.dashboard') }}"
                       class="js-ripple inline-flex items-center px-6 py-3 rounded-xl text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 shadow-md hover:from-blue-700 hover:to-indigo-700 transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Terug naar dashboard
                    </a>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        @if($notifications->hasPages())
            <div class="mt-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-5">
                    {{ $notifications->links() }}
                </div>
            </div>
        @endif
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

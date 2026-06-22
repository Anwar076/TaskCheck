@extends('layouts.admin')

@section('page-title', 'Notificaties')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <span class="text-slate-900 font-semibold truncate">Notificaties</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100 flex items-center justify-between gap-3">
                <div>
                    <h1 class="text-lg sm:text-xl font-bold text-slate-900">Alle notificaties</h1>
                    <p class="text-sm text-slate-500">{{ $notifications->total() }} meldingen</p>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                    <a href="{{ route('admin.notifications.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                        Nieuwe notificatie
                    </a>
                    @if($notifications->whereNull('read_at')->count() > 0)
                        <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}" onsubmit="event.preventDefault(); markAllAdminRead(this);">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium rounded-xl border border-slate-200 text-slate-700 hover:bg-slate-50">
                                Markeer alles gelezen
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="mx-4 sm:mx-6 mt-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="divide-y divide-slate-100">
                @forelse($notifications as $notification)
                    @php
                        $notificationData = is_array($notification->data) ? $notification->data : [];
                        $targetUrl = $notificationData['url'] ?? route('admin.dashboard');
                        $isUnread = is_null($notification->read_at);
                    @endphp
                    <div class="px-4 sm:px-6 py-4 {{ $isUnread ? 'bg-blue-50/30' : 'bg-white' }}">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-900">{{ $notification->title }}</p>
                                <p class="text-sm text-slate-600 mt-1">{{ $notification->message }}</p>
                                <p class="text-xs text-slate-500 mt-2">{{ $notification->created_at?->diffForHumans() }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ $targetUrl }}" class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg border border-blue-200 text-blue-700 hover:bg-blue-50">
                                    Openen
                                </a>
                                @if($isUnread)
                                    <button
                                        type="button"
                                        onclick="markSingleAdminRead({{ $notification->id }}, this)"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 text-xs font-medium rounded-lg border border-emerald-200 text-emerald-700 hover:bg-emerald-50"
                                    >
                                        Markeer gelezen
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-4 sm:px-6 py-12 text-center text-slate-500">
                        Nog geen notificaties.
                    </div>
                @endforelse
            </div>
        </div>

        @if($notifications->hasPages())
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-slate-100 p-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

<script>
async function markSingleAdminRead(notificationId, button) {
    button.disabled = true;
    const res = await fetch(`/admin/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    });
    if (res.ok) {
        location.reload();
        return;
    }
    button.disabled = false;
}

async function markAllAdminRead(form) {
    const res = await fetch(`/admin/notifications/mark-all-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    });
    if (res.ok) {
        location.reload();
    }
}
</script>
@endsection

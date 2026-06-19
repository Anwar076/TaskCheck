@extends('layouts.admin')

@section('page-title', $user->name)

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <a href="{{ route('admin.users.index') }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors">Gebruikers</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-semibold truncate">{{ $user->name }}</span>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
    {{-- Hero / Header --}}
    <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden mb-6 sm:mb-8">
        <div class="bg-gradient-to-br from-slate-50 via-blue-50/50 to-indigo-50/30 px-6 sm:px-8 py-6 sm:py-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex items-start sm:items-center gap-4 sm:gap-6">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xl sm:text-2xl shadow-lg flex-shrink-0">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-slate-900 truncate">{{ $user->name }}</h1>
                        <p class="mt-1 text-slate-600 text-sm sm:text-base">{{ $user->email }}</p>
                        <div class="mt-3 sm:mt-4 flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold
                                @if($user->role === 'admin') bg-purple-100 text-purple-800 border border-purple-200
                                @else bg-blue-100 text-blue-800 border border-blue-200 @endif">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                {{ $user->role === 'admin' ? 'Beheerder' : 'Medewerker' }}
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold
                                @if($user->is_active) bg-emerald-100 text-emerald-800 border border-emerald-200
                                @else bg-red-100 text-red-800 border border-red-200 @endif">
                                <span class="w-2 h-2 rounded-full mr-1.5 {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                {{ $user->is_active ? 'Actief' : 'Inactief' }}
                            </span>
                            @if($user->department)
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    {{ $user->department === 'Cleaning' ? 'Schoonmaak' : ($user->department === 'Maintenance' ? 'Onderhoud' : $user->department) }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3 sm:flex-shrink-0">
                    <a href="{{ route('admin.users.edit', $user) }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-sm hover:shadow transition-all min-h-[44px] touch-manipulation">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Gebruiker bewerken
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 transition-all min-h-[44px] touch-manipulation">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Terug naar gebruikers
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Cards Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Gebruikersgegevens --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Gebruikersgegevens
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex justify-between items-start py-2 border-b border-slate-50">
                    <span class="text-sm font-medium text-slate-500">Naam</span>
                    <span class="text-sm text-slate-900 text-right">{{ $user->name }}</span>
                </div>
                <div class="flex justify-between items-start py-2 border-b border-slate-50">
                    <span class="text-sm font-medium text-slate-500">E-mail</span>
                    <a href="mailto:{{ $user->email }}" class="text-sm text-blue-600 hover:text-blue-700 truncate max-w-[180px]">{{ $user->email }}</a>
                </div>
                @if($user->phone)
                    <div class="flex justify-between items-start py-2 border-b border-slate-50">
                        <span class="text-sm font-medium text-slate-500">Telefoon</span>
                        <a href="tel:{{ $user->phone }}" class="text-sm text-slate-900">{{ $user->phone }}</a>
                    </div>
                @endif
                <div class="flex justify-between items-start py-2 border-b border-slate-50">
                    <span class="text-sm font-medium text-slate-500">Rol</span>
                    <span class="text-sm text-slate-900">{{ $user->role === 'admin' ? 'Beheerder' : 'Medewerker' }}</span>
                </div>
                @if($user->department)
                    <div class="flex justify-between items-start py-2 border-b border-slate-50">
                        <span class="text-sm font-medium text-slate-500">Afdeling</span>
                        <span class="text-sm text-slate-900">{{ $user->department === 'Cleaning' ? 'Schoonmaak' : ($user->department === 'Maintenance' ? 'Onderhoud' : $user->department) }}</span>
                    </div>
                @endif
                <div class="flex justify-between items-start py-2 border-b border-slate-50">
                    <span class="text-sm font-medium text-slate-500">Status</span>
                    <span class="text-sm {{ $user->is_active ? 'text-emerald-600 font-medium' : 'text-red-600 font-medium' }}">{{ $user->is_active ? 'Actief' : 'Inactief' }}</span>
                </div>
                <div class="flex justify-between items-start py-2">
                    <span class="text-sm font-medium text-slate-500">Lid sinds</span>
                    <span class="text-sm text-slate-900">{{ $user->created_at->translatedFormat('d M Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Activiteitsstatistieken --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Activiteitsstatistieken
                </h3>
            </div>
            <div class="p-6">
                @if($user->role === 'employee')
                    @php
                        $totalSubmissions = $user->submissions()->count();
                        $completedSubmissions = $user->submissions()->where('status', 'completed')->count();
                        $reviewedSubmissions = $user->submissions()->where('status', 'reviewed')->count();
                        $inProgressSubmissions = $user->submissions()->where('status', 'in_progress')->count();
                        $rejectedTasks = \App\Models\SubmissionTask::whereHas('submission', function($q) use ($user) { $q->where('user_id', $user->id); })->where('status', 'rejected')->count();
                        $successRate = $totalSubmissions > 0 ? round((($completedSubmissions + $reviewedSubmissions) / $totalSubmissions) * 100) : 0;
                    @endphp
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-slate-600">Totaal inzendingen</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $totalSubmissions }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-slate-600">Voltooid</span>
                            <span class="text-sm font-semibold text-emerald-600">{{ $completedSubmissions }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-slate-600">Beoordeeld</span>
                            <span class="text-sm font-semibold text-blue-600">{{ $reviewedSubmissions }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-slate-600">In behandeling</span>
                            <span class="text-sm font-semibold text-amber-600">{{ $inProgressSubmissions }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-slate-600">Afgewezen taken</span>
                            <span class="text-sm font-semibold {{ $rejectedTasks > 0 ? 'text-red-600' : 'text-slate-900' }}">{{ $rejectedTasks }}</span>
                        </div>
                        <div class="pt-3 mt-3 border-t border-slate-100 flex justify-between items-center">
                            <span class="text-sm font-medium text-slate-700">Slagingspercentage</span>
                            <span class="text-base font-bold text-slate-900">{{ $successRate }}%</span>
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-slate-600">Lijsten aangemaakt</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $user->createdLists()->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-slate-600">Taken beoordeeld</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $user->reviewedTasks()->count() }}</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Taaktoewijzingen (medewerkers) --}}
        @if($user->role === 'employee')
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Taaktoewijzingen
                    </h3>
                </div>
                <div class="p-6">
                    @php $assignedTasks = $user->taskAssignments()->with('task.taskList')->active()->take(5)->get(); @endphp
                    @if($assignedTasks->count() > 0)
                        <div class="space-y-3">
                            @foreach($assignedTasks as $assignment)
                                <div class="p-3 rounded-xl bg-slate-50 border-l-4 border-blue-500">
                                    <p class="text-sm font-medium text-slate-900">{{ $assignment->task->title }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $assignment->task->taskList->title ?? '—' }}</p>
                                    <p class="text-xs text-slate-400 mt-1">{{ $assignment->assigned_at->diffForHumans() }}</p>
                                </div>
                            @endforeach
                            @if($user->taskAssignments()->active()->count() > 5)
                                <p class="text-xs text-slate-500 mt-2">
                                    +{{ $user->taskAssignments()->active()->count() - 5 }} meer toewijzingen
                                </p>
                            @endif
                        </div>
                    @else
                        <p class="text-sm text-slate-500 py-4">Geen specifieke taaktoewijzingen</p>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Recente inzendingen --}}
    @if($user->role === 'employee')
        <div class="mt-6 sm:mt-8 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Recente inzendingen
                </h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($user->submissions()->with('taskList')->latest()->take(10)->get() as $submission)
                    <div class="px-6 py-4 hover:bg-slate-50/50 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-900 truncate">{{ $submission->taskList->title }}</p>
                                <p class="text-sm text-slate-500 mt-0.5">
                                    {{ $submission->completed_at ? 'Voltooid' : 'Gestart' }} {{ ($submission->completed_at ?? $submission->created_at)->translatedFormat('d M Y H:i') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0">
                                @php
                                    $statusConfig = [
                                        'completed' => ['bg-amber-100 text-amber-800 border-amber-200', 'In afwachting'],
                                        'reviewed' => ['bg-emerald-100 text-emerald-800 border-emerald-200', 'Beoordeeld'],
                                        'rejected' => ['bg-red-100 text-red-800 border-red-200', 'Afgewezen'],
                                        'in_progress' => ['bg-blue-100 text-blue-800 border-blue-200', 'In behandeling'],
                                    ];
                                    $cfg = $statusConfig[$submission->status] ?? ['bg-slate-100 text-slate-800 border-slate-200', ucfirst($submission->status)];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium border {{ $cfg[0] }}">{{ $cfg[1] }}</span>
                                <a href="{{ route('admin.submissions.show', $submission) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-blue-600 hover:bg-blue-50 transition-colors min-h-[40px]">
                                    Bekijken
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-slate-500">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm font-medium">Nog geen inzendingen</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    {{-- Recente meldingen --}}
    @if($user->role === 'employee')
        <div class="mt-6 sm:mt-8 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-base font-semibold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Recente meldingen
                </h3>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($user->notifications()->latest()->take(5)->get() as $notification)
                    <div class="px-6 py-4 {{ is_null($notification->read_at) ? 'bg-blue-50/30' : '' }} hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-900">{{ $notification->title }}</p>
                                <p class="text-sm text-slate-600 mt-0.5 line-clamp-2">{{ $notification->message }}</p>
                                <p class="text-xs text-slate-400 mt-2">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium flex-shrink-0
                                {{ is_null($notification->read_at) ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                {{ is_null($notification->read_at) ? 'Ongelezen' : 'Gelezen' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center text-slate-500">
                        <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <p class="text-sm font-medium">Geen meldingen</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endif
</div>
@endsection

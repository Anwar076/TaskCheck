@extends('layouts.admin')

@section('page-title', $list->title)

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <a href="{{ route('admin.lists.index') }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors">Takenlijsten</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-semibold truncate">{{ $list->title }}</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden" data-sortable-reorder-url="{{ route('admin.lists.tasks.reorder', $list) }}">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl" role="alert">
                <p class="font-medium text-red-800">Validatiefouten:</p>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- Hero --}}
        <div class="mb-6 sm:mb-8">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
                <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white truncate">{{ $list->title }}</h1>
                            @if($list->description)
                                <p class="text-blue-100/90 text-sm sm:text-base mt-1 line-clamp-2">{{ $list->description }}</p>
                            @endif
                            <div class="mt-3 flex flex-wrap gap-2">
                                @php
                                    $priorityLabels = ['low' => 'Laag', 'medium' => 'Normaal', 'high' => 'Hoog', 'urgent' => 'Urgent'];
                                    $scheduleLabels = ['once' => 'Eenmalig', 'daily' => 'Dagelijks', 'weekly' => 'Wekelijks', 'monthly' => 'Maandelijks', 'custom' => 'Aangepast'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                                    @if($list->priority === 'urgent') bg-red-500/30 text-red-100
                                    @elseif($list->priority === 'high') bg-amber-500/30 text-amber-100
                                    @elseif($list->priority === 'medium') bg-blue-500/30 text-blue-100
                                    @else bg-slate-500/30 text-slate-100 @endif">
                                    {{ $priorityLabels[$list->priority] ?? $list->priority }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/20 text-white">
                                    {{ $scheduleLabels[$list->schedule_type] ?? $list->schedule_type }}
                                </span>
                                @if($list->category)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/20 text-white">{{ $list->category }}</span>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/20 text-white">
                                    Locatie: {{ $list->location?->name ?? 'Algemeen' }}
                                </span>
                                @if($list->requires_signature)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/20 text-white">Handtekening</span>
                                @endif
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium {{ $list->is_active ? 'bg-emerald-500/30 text-emerald-100' : 'bg-slate-500/30 text-slate-100' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $list->is_active ? 'bg-emerald-300' : 'bg-slate-300' }}"></span>
                                    {{ $list->is_active ? 'Actief' : 'Inactief' }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($list->template_id)
                                <form method="POST" action="{{ route('admin.lists.sync-template', $list) }}" onsubmit="return confirm('Taken uit het gekoppelde template opnieuw toepassen op deze lijst?')">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182"/></svg>
                                        Template opnieuw synchroniseren
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.lists.edit', $list) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-blue-600 text-sm font-semibold rounded-xl hover:bg-blue-50 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                Bewerken
                            </a>
                            <a href="{{ route('admin.lists.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 text-white text-sm font-medium rounded-xl hover:bg-white/30 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18.75"/></svg>
                                Naar overzicht
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistieken --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3" data-onboarding-target="assignment-summary">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $list->tasks->count() }}</p>
                        <p class="text-sm text-slate-600">Taken</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $list->tasks->where('is_required', true)->count() }}</p>
                        <p class="text-sm text-slate-600">Verplichte taken</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-violet-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $list->assignments->count() }}</p>
                        <p class="text-sm text-slate-600">Toegewezen</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-4 sm:p-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $list->submissions->count() }}</p>
                        <p class="text-sm text-slate-600">Inzendingen</p>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.lists.partials.task-calendar', [
            'list' => $list,
            'calendar' => $calendar,
            'calendarView' => $calendarView,
            'selectedDay' => $selectedDay,
            'miniMonth' => $miniMonth,
            'weekStart' => $weekStart,
        ])

        @include('admin.tasks.partials.task-create-modal', ['list' => $list])

        {{-- Toewijzingen --}}
        <div id="assignments-container" class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-violet-50 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Toewijzingen</h2>
                        <p class="text-sm text-slate-600">Wie heeft toegang tot deze lijst</p>
                    </div>
                </div>
                <button type="button" data-onboarding-target="assign-list" onclick="showAssignModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Lijst toewijzen
                </button>
            </div>
            <div class="p-4 sm:p-6">
                @if($list->assignments->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($list->assignments as $assignment)
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
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
                                        <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-emerald-500 rounded-full flex items-center justify-center">
                                            <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-base font-semibold text-gray-900">
                                            @if($assignment->user)
                                                {{ $assignment->user->name }}
                                            @elseif($assignment->department)
                                                <span class="text-emerald-600">{{ $assignment->department }}</span>
                                            @else
                                                <span class="text-slate-500 italic">Gebruiker niet gevonden</span>
                                            @endif
                                        </h4>
                                        <p class="text-sm text-gray-600">
                                            @if($assignment->user)
                                                {{ $assignment->user->department ?? 'Geen afdeling' }}
                                            @elseif($assignment->department)
                                                <span class="text-emerald-600">Afdeling</span>
                                            @else
                                                <span class="text-slate-400">Geen afdeling</span>
                                            @endif
                                        </p>
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                            Actief
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="bg-slate-50 rounded-lg p-3 mb-3 border border-slate-100">
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
                                                    <span class="text-sm font-medium text-slate-600">Vervaldatum</span>
                                                </div>
                                                <span class="text-sm font-semibold text-slate-900">{{ $assignment->due_date->locale('nl')->translatedFormat('d M Y') }}</span>
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
                                        <span class="text-sm font-medium text-slate-600">Status</span>
                                    </div>
                                    <button type="button" onclick="removeAssignment({{ $assignment->id }})" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-700 text-sm font-medium rounded-lg hover:bg-red-100 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        Verwijderen
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                        </div>
                        <h3 class="font-semibold text-slate-900 mb-2">Nog geen toewijzingen</h3>
                        <p class="text-slate-600 text-sm mb-4">Wijs deze lijst toe aan medewerkers of afdelingen</p>
                        <button type="button" data-onboarding-target="assign-list" onclick="showAssignModal()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                            Lijst toewijzen
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Assignment Modal -->
<div id="assignModal" class="fixed inset-0 z-[245] hidden overflow-y-auto" aria-labelledby="assign-modal-title" aria-modal="true" role="dialog">
    <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeAssignModal()" aria-hidden="true"></div>
        <div class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-slate-200 transition-all">
            {{-- Header --}}
            <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-5 sm:px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 id="assign-modal-title" class="text-lg font-bold text-white">Lijst toewijzen</h3>
                            <p class="text-sm text-blue-100/90">Wijs deze lijst toe aan medewerker of afdeling</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeAssignModal()" class="rounded-xl p-2 text-white/80 hover:bg-white/20 hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-white/50" aria-label="Sluiten">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            {{-- Form --}}
            <form id="assignForm" method="POST" action="{{ route('admin.lists.assign', $list) }}" class="px-5 sm:px-6 py-5">
                @csrf
                <div class="space-y-5">
                    {{-- Type selector --}}
                    <div>
                        <p class="text-sm font-semibold text-slate-900 mb-3">Type toewijzing</p>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="assignment-type-card relative flex cursor-pointer flex-col items-center gap-2 rounded-xl border-2 border-slate-200 bg-slate-50/50 p-4 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20">
                                <input type="radio" name="assignment_type" value="user" class="sr-only" checked onchange="toggleAssignmentType()">
                                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-900">Gebruiker</span>
                            </label>
                            <label class="assignment-type-card relative flex cursor-pointer flex-col items-center gap-2 rounded-xl border-2 border-slate-200 bg-slate-50/50 p-4 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-500/20">
                                <input type="radio" name="assignment_type" value="department" class="sr-only" onchange="toggleAssignmentType()">
                                <div class="w-10 h-10 rounded-xl bg-violet-100 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-900">Afdeling</span>
                            </label>
                        </div>
                    </div>

                    {{-- User select --}}
                    <div id="userAssignment" data-onboarding-target="assign-user-field" data-users-empty="{{ $users->isEmpty() ? '1' : '0' }}">
                        <label for="user_ids" class="block text-sm font-semibold text-slate-900 mb-1.5">
                            Gebruiker <span class="text-red-500">*</span>
                        </label>
                        <select name="user_ids" id="user_ids" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 disabled:bg-slate-100 disabled:text-slate-500" {{ $users->isEmpty() ? 'disabled' : 'required' }}>
                            <option value="">Kies een gebruiker...</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @if($users->isEmpty())
                            <p class="mt-1.5 text-sm text-amber-600">Geen gebruikers. <a href="{{ route('admin.users.create') }}" class="font-medium underline hover:text-amber-800">Gebruiker aanmaken</a></p>
                        @else
                            <p class="mt-1 text-xs text-slate-500">Medewerker aan wie de lijst wordt toegewezen</p>
                        @endif
                    </div>

                    {{-- Department select --}}
                    <div id="departmentAssignment" class="hidden" data-departments-empty="{{ empty($departments ?? []) ? '1' : '0' }}">
                        <label for="department" class="block text-sm font-semibold text-slate-900 mb-1.5">
                            Afdeling <span class="text-red-500">*</span>
                        </label>
                        <select name="department" id="department" class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 disabled:bg-slate-100" disabled>
                            <option value="">Kies een afdeling...</option>
                            @foreach(($departments ?? []) as $departmentOption)
                                <option value="{{ $departmentOption }}">{{ $departmentOption }}</option>
                            @endforeach
                        </select>
                        @if(empty($departments ?? []))
                            <p class="mt-1.5 text-sm text-amber-600">
                                Er zijn geen afdelingen. Wil je een afdeling maken?
                                <a href="{{ route('admin.settings.edit') }}" class="font-medium underline hover:text-amber-800">Ga naar instellingen</a>
                            </p>
                        @else
                            <p class="mt-1 text-xs text-slate-500">Alle medewerkers in deze afdeling krijgen toegang</p>
                        @endif
                    </div>

                    {{-- Dates --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="assigned_date" class="block text-sm font-semibold text-slate-900 mb-1.5">
                                Toewijzingsdatum <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="assigned_date" id="assigned_date" value="{{ date('Y-m-d') }}" required
                                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-semibold text-slate-900 mb-1.5">
                                Vervaldatum <span class="text-slate-400 font-normal">(optioneel)</span>
                            </label>
                            <input type="date" name="due_date" id="due_date"
                                class="block w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <button type="button" onclick="closeAssignModal()"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:ring-offset-2 transition-colors">
                        Annuleren
                    </button>
                    <button type="submit" id="submitAssignmentBtn" data-onboarding-target="assign-save"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        <span id="submitBtnText">Toewijzen</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Old Create Day List Modal removed - using new agenda system -->
<!-- Sortable.js is loaded via Vite (resources/js/list-sortable.js) -->
<style>
.sortable-ghost { opacity: 0.4; background-color: rgb(226 232 240); }
.sortable-chosen { box-shadow: 0 0 0 2px rgb(96 165 250); outline: 2px solid rgb(96 165 250); outline-offset: 2px; }
.sortable-dragging { cursor: grabbing !important; }
</style>

<script>
function showAssignModal() {
    const modal = document.getElementById('assignModal');
    if (modal) {
        modal.classList.remove('hidden');
        toggleAssignmentType();
        document.dispatchEvent(new CustomEvent('onboarding:modal-opened'));
    }
}

function closeAssignModal() {
    const modal = document.getElementById('assignModal');
    if (modal) {
        modal.classList.add('hidden');
        const form = document.getElementById('assignForm');
        if (form) form.reset();
    }
}

function toggleAssignmentType() {
    const userAssignment = document.getElementById('userAssignment');
    const departmentAssignment = document.getElementById('departmentAssignment');
    const userSelect = document.getElementById('user_ids');
    const departmentSelect = document.getElementById('department');
    const assignmentTypeRadio = document.querySelector('input[name="assignment_type"]:checked');
    
    if (!assignmentTypeRadio) return;
    const departmentsEmpty = departmentAssignment?.dataset?.departmentsEmpty === '1';
    
    if (assignmentTypeRadio.value === 'user') {
        userAssignment?.classList.remove('hidden');
        departmentAssignment?.classList.add('hidden');
        userSelect.disabled = false;
        departmentSelect.disabled = true;
        departmentSelect.value = '';
    } else {
        userAssignment?.classList.add('hidden');
        departmentAssignment?.classList.remove('hidden');
        userSelect.disabled = true;
        userSelect.value = '';
        departmentSelect.disabled = departmentsEmpty;
        if (departmentsEmpty) {
            departmentSelect.value = '';
        }
    }
}

window.removeAssignment = function(assignmentId) {
    if (!assignmentId) return alert('Ongeldige ID.');
    if (!confirm('Weet je zeker dat je deze toewijzing wilt verwijderen?')) return;

    const meta = document.querySelector('meta[name="csrf-token"]');
    const formData = new FormData();
    formData.append('_token', meta ? meta.getAttribute('content') : '');
    formData.append('_method', 'DELETE');

    fetch(`/admin/assignments/${assignmentId}`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': meta ? meta.getAttribute('content') : ''
        },
        body: formData
    })
        .then(res => {
            if (res.ok) {
                refreshAssignments();
            } else {
                alert('Er ging iets mis bij verwijderen.');
            }
        })
        .catch(() => {
            alert('Netwerkfout bij verwijderen.');
        });
};

// 💾 Handle assignment form submit
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('assignForm');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        const userAssignment = document.getElementById('userAssignment');
        const assignmentType = document.querySelector('input[name="assignment_type"]:checked');
        const usersEmpty = userAssignment?.dataset?.usersEmpty === '1';
        const departmentsEmpty = document.getElementById('departmentAssignment')?.dataset?.departmentsEmpty === '1';
        if (assignmentType?.value === 'user' && usersEmpty) {
            e.preventDefault();
            alert('Geen gebruikers beschikbaar. Maak eerst een gebruiker aan (Gebruikers → Nieuwe gebruiker) of kies voor afdelingstoewijzing.');
            return;
        }
        if (assignmentType?.value === 'department' && departmentsEmpty) {
            e.preventDefault();
            alert('Er zijn geen afdelingen ingesteld. Voeg eerst afdelingen toe bij instellingen.');
            return;
        }

        const submitBtn = document.getElementById('submitAssignmentBtn');
        if (submitBtn) {
            submitBtn.disabled = true;
            const btnText = document.getElementById('submitBtnText');
            if (btnText) btnText.textContent = 'Bezig met toewijzen...';
        }
        // Laat de form normaal submitten (geen AJAX) - betrouwbaarder
    });

    // Modal sluitgedrag
    document.addEventListener('click', e => {
        const assignModal = document.getElementById('assignModal');
        if (e.target === assignModal) closeAssignModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeAssignModal();
    });

    toggleAssignmentType();
});

function refreshAssignments() {
    fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContainer = doc.getElementById('assignments-container');
            const current = document.getElementById('assignments-container');
            if (newContainer && current) {
                current.innerHTML = newContainer.innerHTML;
            } else {
                window.location.reload();
            }
        })
        .catch(() => window.location.reload());
}

</script>
@endsection

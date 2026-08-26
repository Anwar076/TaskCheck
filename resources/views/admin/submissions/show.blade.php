@extends('layouts.admin')

@section('page-title', 'Inzending beoordelen')

@section('breadcrumbs')
    <span class="text-slate-500">/</span>
    <a href="{{ route('admin.submissions.index') }}" class="text-slate-500 hover:text-slate-700 font-medium transition-colors">Inzendingen</a>
    <span class="text-slate-400">/</span>
    <span class="text-slate-900 font-semibold truncate">Beoordelen</span>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50 pt-4 sm:pt-6 lg:pt-8 pb-8 overflow-x-hidden">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">

        @php
            $teamContributors = $submission->contributorTaskSummary();
            $teamContributorCount = count($teamContributors);
            $listDepartments = $submission->taskList?->assignedDepartmentLabels() ?? [];
        @endphp

        {{-- Hero --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg border border-slate-100 overflow-hidden mb-6 sm:mb-8">
            <div class="bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 px-4 sm:px-6 lg:px-8 py-5 sm:py-8">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4 sm:gap-6">
                    <div class="flex items-start gap-3 sm:gap-4 min-w-0">
                        @if($submission->is_team_submission)
                            <div class="flex -space-x-2 flex-shrink-0">
                                @forelse(collect($teamContributors)->take(4) as $contributor)
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-white/20 backdrop-blur border-2 border-white/40 flex items-center justify-center">
                                        <span class="text-lg sm:text-xl font-bold text-white">{{ $contributor['initials'] }}</span>
                                    </div>
                                @empty
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-white/20 backdrop-blur flex items-center justify-center">
                                        <span class="text-lg sm:text-xl font-bold text-white">{{ substr($submission->user->name ?? 'T', 0, 1) }}</span>
                                    </div>
                                @endforelse
                                @if($teamContributorCount > 4)
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-white/30 backdrop-blur border-2 border-white/40 flex items-center justify-center">
                                        <span class="text-sm font-bold text-white">+{{ $teamContributorCount - 4 }}</span>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-white/20 backdrop-blur flex items-center justify-center flex-shrink-0">
                                <span class="text-xl sm:text-2xl font-bold text-white">{{ substr($submission->user->name, 0, 1) }}</span>
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white break-words">Inzending beoordelen</h1>
                            <p class="mt-1 text-blue-100 text-base sm:text-lg break-words">{{ $submission->taskList->title }}</p>
                            @if(!empty($listDepartments))
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    @foreach($listDepartments as $department)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-white/15 text-white border border-white/20">{{ $department }}</span>
                                    @endforeach
                                </div>
                            @endif
                            @if($submission->is_team_submission)
                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-white/15 text-white border border-white/20">Team checklist</span>
                                    <span class="text-blue-200/90 text-sm">
                                        {{ $teamContributorCount > 0 ? $teamContributorCount . ' medewerker' . ($teamContributorCount === 1 ? '' : 's') . ' actief' : 'Gestart door ' . $submission->user->name }}
                                    </span>
                                </div>
                            @else
                                <p class="mt-0.5 text-blue-200/90 text-sm sm:text-base break-words">
                                    <span>{{ $submission->user->name }}</span>
                                    <span class="hidden sm:inline"> • </span>
                                    <span class="block sm:inline break-all">{{ $submission->user->email }}</span>
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-3 w-full lg:w-auto">
                        @php
                            $statusLabels = ['in_progress' => 'Bezig', 'completed' => 'Afgerond', 'reviewed' => 'Beoordeeld', 'rejected' => 'Afgewezen'];
                            $statusColors = ['completed' => 'bg-amber-100 text-amber-800 border-amber-200', 'reviewed' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'rejected' => 'bg-red-100 text-red-800 border-red-200', 'in_progress' => 'bg-blue-100 text-blue-800 border-blue-200'];
                            $s = $submission->status;
                        @endphp
                        <span class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold border {{ $statusColors[$s] ?? 'bg-slate-100 text-slate-800 border-slate-200' }}">
                            @if($s === 'completed' || $s === 'reviewed')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @elseif($s === 'rejected')
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @endif
                            {{ $statusLabels[$s] ?? ucfirst($s) }}
                        </span>
                        <a href="{{ route('admin.submissions.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white/20 hover:bg-white/30 text-white text-sm font-medium rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                            Naar overzicht
                        </a>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            @php
                $totalTasks = $submission->submissionTasks->count();
                $completedTasks = $submission->submissionTasks->where('status', 'completed')->count();
                $approvedTasks = $submission->submissionTasks->where('status', 'approved')->count();
                $progress = $totalTasks > 0 ? round(($completedTasks + $approvedTasks) / $totalTasks * 100) : 0;
            @endphp
            <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-5 bg-slate-50 border-t border-slate-200">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Gestart</p>
                            <p class="text-sm font-medium text-slate-900 break-words">{{ ($submission->started_at ?? $submission->created_at)->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    @if($submission->completed_at)
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Afgerond</p>
                                <p class="text-sm font-medium text-slate-900 break-words">{{ $submission->completed_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif
                    <div class="flex items-center gap-3 min-w-0 sm:col-span-2 xl:col-span-1">
                        <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6"/></svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-medium text-slate-500 uppercase tracking-wide">Voortgang</p>
                            <p class="text-sm font-medium text-slate-900">{{ $completedTasks + $approvedTasks }}/{{ $totalTasks }} taken ({{ $progress }}%)</p>
                            <div class="mt-2 h-2.5 bg-slate-200 rounded-full overflow-hidden">
                                <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($submission->is_team_submission)
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-6 mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Team bijdrage</h3>
                        <p class="text-sm text-slate-500 mt-0.5">Wie heeft welke taken ingevuld in deze gezamenlijke checklist</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $teamContributorCount > 0 ? $teamContributorCount . ' medewerker' . ($teamContributorCount === 1 ? '' : 's') : 'Nog geen taken ingevuld' }}
                    </span>
                </div>

                @if($teamContributorCount > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                        @foreach($teamContributors as $contributor)
                            <div class="rounded-xl border border-slate-100 bg-slate-50/70 px-4 py-3 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white font-bold">
                                    {{ $contributor['initials'] }}
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $contributor['name'] }}</p>
                                    <p class="text-xs text-slate-500">{{ $contributor['completed_tasks'] }} taak{{ $contributor['completed_tasks'] === 1 ? '' : 'en' }} ingevuld</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                        Deze teamchecklist is gestart door {{ $submission->user->name }}, maar er zijn nog geen taken afgerond door teamleden.
                    </div>
                @endif
            </div>
        @endif

        @php
            $aiReview = $submission->metadata['ai_review'] ?? null;
            $taskReviewsById = [];
            if ($aiReview && !empty($aiReview['task_reviews']) && is_array($aiReview['task_reviews'])) {
                foreach ($aiReview['task_reviews'] as $tr) {
                    if (isset($tr['task_id'])) {
                        $taskReviewsById[$tr['task_id']] = $tr;
                    }
                }
            }
        @endphp

        {{--
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 p-5 sm:p-6 mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16h6m2 4H7a2 2 0 01-2-2V6a2 2 0 012-2h3.586a1 1 0 01.707.293L13.414 6H17a2 2 0 012 2v10a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-semibold text-slate-900">AI-controle</h2>
                        <p class="text-sm text-slate-600 mt-0.5">
                            Laat AI een snelle kwaliteitscontrole doen op deze inzending.
                        </p>
                        @if($aiReview)
                            <div class="mt-3 rounded-xl border px-3 py-2 inline-flex items-center gap-2 text-xs
                                @if($aiReview['overall_status'] === 'ok') bg-emerald-50 border-emerald-200 text-emerald-800
                                @elseif($aiReview['overall_status'] === 'waarschuwing') bg-amber-50 border-amber-200 text-amber-800
                                @else bg-red-50 border-red-200 text-red-800 @endif">
                                <span class="w-1.5 h-1.5 rounded-full
                                    @if($aiReview['overall_status'] === 'ok') bg-emerald-500
                                    @elseif($aiReview['overall_status'] === 'waarschuwing') bg-amber-500
                                    @else bg-red-500 @endif"></span>
                                <span class="font-semibold">
                                    @if($aiReview['overall_status'] === 'ok')
                                        Geen opvallende problemen gevonden
                                    @elseif($aiReview['overall_status'] === 'waarschuwing')
                                        Let op: mogelijke aandachtspunten
                                    @else
                                        Controle aanbevolen
                                    @endif
                                </span>
                                <span class="text-slate-500 text-[11px]">
                                    ({{ \Carbon\Carbon::parse($aiReview['ran_at'] ?? $submission->updated_at)->diffForHumans() }})
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.submissions.ai-review', $submission) }}" class="flex-shrink-0">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold
                            {{ $aiReview ? 'bg-slate-900 text-white hover:bg-slate-800' : 'bg-emerald-600 text-white hover:bg-emerald-700' }}
                            shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 4H6a2 2 0 00-2 2v7m2 5h11a2 2 0 002-2v-5M7 20l-3-3m0 0l3-3m-3 3h10m4-9l3-3m0 0l-3-3m3 3H11" />
                        </svg>
                        {{ $aiReview ? 'AI-review opnieuw uitvoeren' : 'AI-review uitvoeren' }}
                    </button>
                </form>
            </div>

            @if($aiReview)
                <div class="mt-4 grid md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <h3 class="text-sm font-semibold text-slate-900 mb-1.5">Samenvatting</h3>
                        <p class="text-sm text-slate-700 leading-relaxed">
                            {{ $aiReview['summary'] ?? 'Geen samenvatting beschikbaar.' }}
                        </p>
                    </div>
                    <div>
                        @if(!empty($aiReview['missing_required_tasks']))
                            <h3 class="text-sm font-semibold text-red-800 mb-1.5">Missende verplichte taken</h3>
                            <ul class="text-sm text-red-800 space-y-1.5">
                                @foreach($aiReview['missing_required_tasks'] as $item)
                                    <li class="flex items-start gap-1.5">
                                        <span class="mt-1 w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        <span>
                                            <span class="font-semibold">{{ $item['task_title'] ?? 'Taak' }}:</span>
                                            <span>{{ $item['reason'] ?? '' }}</span>
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <h3 class="text-sm font-semibold text-slate-900 mb-1.5">Verplichte taken</h3>
                            <p class="text-sm text-slate-600">AI heeft geen ontbrekende verplichte taken gevonden.</p>
                        @endif
                    </div>
                </div>

                @if(!empty($aiReview['notes']))
                    <div class="mt-4 border-t border-slate-100 pt-4">
                        <h3 class="text-sm font-semibold text-slate-900 mb-1.5">Opmerkingen van AI</h3>
                        <ul class="text-sm text-slate-700 space-y-1.5">
                            @foreach($aiReview['notes'] as $note)
                                <li class="flex items-start gap-1.5">
                                    <span class="mt-1 w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    <span>{{ $note }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endif
        </div>
        --}}

        @if($submission->employee_signature)
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 p-4 sm:p-6 mb-6 sm:mb-8">
                <div class="flex items-start gap-3 sm:gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="text-lg font-semibold text-slate-900 mb-1">Handtekening medewerker</h3>
                        <p class="text-sm text-slate-600 mb-4">Digitale handtekening geleverd door de medewerker</p>
                        <div class="bg-slate-50 rounded-xl p-3 sm:p-4 overflow-x-auto">
                            <img src="{{ $submission->employee_signature }}" alt="Handtekening medewerker" class="border border-slate-200 rounded-lg bg-white max-w-full w-auto max-h-32 shadow-sm">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-4 sm:px-6 lg:px-8 py-5 bg-slate-50 border-b border-slate-200">
                @php
                    $bulkApprovableCount = $submission->submissionTasks
                        ->where('status', 'completed')
                        ->whereNull('corrective_action')
                        ->count();
                @endphp
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-lg sm:text-2xl font-bold text-slate-900">Taken beoordelen</h3>
                            <p class="text-sm text-slate-500">{{ $submission->submissionTasks->count() }} taken in totaal</p>
                            @if(!empty($taskReviewsById))
                                <p class="text-xs text-slate-400 mt-1">
                                    AI heeft feedback gegeven per taak (zie badges en opmerkingen).
                                </p>
                            @endif
                        </div>
                    </div>
                    @if($submission->taskList->requires_review && $bulkApprovableCount > 0)
                        <form method="POST" action="{{ route('admin.submissions.approve-all', $submission) }}" class="w-full sm:w-auto" onsubmit="return confirm('Weet je zeker dat je alle {{ $bulkApprovableCount }} afgeronde taken wilt goedkeuren?');">
                            @csrf
                            <button type="submit" class="inline-flex w-full sm:w-auto justify-center items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                Keur alles goed ({{ $bulkApprovableCount }})
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            <div class="divide-y divide-slate-200">
                @foreach($submission->submissionTasks as $index => $submissionTask)
                    @php
                        $taskStatusLabels = ['completed' => 'Afgerond', 'approved' => 'Goedgekeurd', 'rejected' => 'Afgekeurd', 'redo_requested' => 'Opnieuw uitvoeren', 'pending' => 'Openstaand'];
                        $taskStatusColors = ['completed' => 'bg-amber-100 text-amber-800 border-amber-200', 'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'rejected' => 'bg-red-100 text-red-800 border-red-200', 'redo_requested' => 'bg-orange-100 text-orange-800 border-orange-200', 'pending' => 'bg-slate-100 text-slate-800 border-slate-200'];
                        $ts = $submissionTask->status;
                        $aiTask = $taskReviewsById[$submissionTask->task->id] ?? null;
                        $validationRules = $submissionTask->task->validation_rules ?? [];
                        $proofText = (string) ($submissionTask->proof_text ?? '');
                        $hasMetricRule = is_array($validationRules) && !empty($validationRules['metric']);
                        $metricValue = null;
                        if ($hasMetricRule && preg_match('/-?\d+(?:[.,]\d+)?/', $proofText, $metricMatches)) {
                            $metricValue = (float) str_replace(',', '.', $metricMatches[0]);
                        }
                        $metricMin = isset($validationRules['min']) && is_numeric($validationRules['min']) ? (float) $validationRules['min'] : null;
                        $metricMax = isset($validationRules['max']) && is_numeric($validationRules['max']) ? (float) $validationRules['max'] : null;
                        $metricComparison = $validationRules['comparison'] ?? null;
                        $isCriticalDeviation = \App\Helpers\MetricValidationHelper::isDeviation(
                            $hasMetricRule ? $validationRules : null,
                            $proofText
                        );
                    @endphp
                    <div class="p-4 sm:p-6 lg:p-8 hover:bg-slate-50/50 transition-colors submission-task" data-submission-task-id="{{ $submissionTask->id }}">
                        <div class="flex flex-col xl:flex-row xl:items-start xl:gap-8">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start gap-3 sm:gap-4 mb-4 sm:mb-6">
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-sm flex-shrink-0">
                                        <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-base sm:text-xl font-bold text-slate-900 break-words">{{ $submissionTask->task->title }}</h4>
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-lg text-xs sm:text-sm font-semibold border task-status-badge {{ $taskStatusColors[$ts] ?? 'bg-slate-100 text-slate-800 border-slate-200' }}">
                                                @if(in_array($ts, ['completed','approved']))
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                @elseif($ts === 'rejected')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                @elseif($ts === 'redo_requested')
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                                @else
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                @endif
                                                {{ $taskStatusLabels[$ts] ?? ucfirst(str_replace('_', ' ', $ts)) }}
                                            </span>
                                            @php
                                                $taskCompleter = $submissionTask->completedBy;
                                                if (!$taskCompleter && !$submission->is_team_submission && in_array($ts, ['completed', 'approved', 'rejected', 'redo_requested'])) {
                                                    $taskCompleter = $submission->user;
                                                }
                                            @endphp
                                            @if($taskCompleter)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 sm:px-3 py-1.5 rounded-lg text-xs font-medium border bg-indigo-50 text-indigo-800 border-indigo-200 max-w-full">
                                                    <span class="truncate">Ingevuld door {{ $taskCompleter->name }}</span>
                                                </span>
                                            @endif
                                            @if($aiTask)
                                                @php
                                                    $aiColor = $aiTask['status'] === 'ok'
                                                        ? 'bg-emerald-50 text-emerald-800 border-emerald-200'
                                                        : ($aiTask['status'] === 'waarschuwing'
                                                            ? 'bg-amber-50 text-amber-800 border-amber-200'
                                                            : 'bg-red-50 text-red-800 border-red-200');
                                                    $aiLabel = $aiTask['status'] === 'ok'
                                                        ? 'AI: ok'
                                                        : ($aiTask['status'] === 'waarschuwing'
                                                            ? 'AI: waarschuwing'
                                                            : 'AI: nakijken');
                                                @endphp
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border {{ $aiColor }}">
                                                    <span class="w-1.5 h-1.5 rounded-full
                                                        @if($aiTask['status'] === 'ok') bg-emerald-500
                                                        @elseif($aiTask['status'] === 'waarschuwing') bg-amber-500
                                                        @else bg-red-500 @endif"></span>
                                                    {{ $aiLabel }}
                                                </span>
                                            @endif
                                            @if($isCriticalDeviation)
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border bg-red-100 text-red-800 border-red-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                                    Kritieke afwijking
                                                </span>
                                            @endif
                                        </div>
                                        @if($submissionTask->task->description)
                                            <p class="mt-2 text-slate-600 leading-relaxed break-words">{{ $submissionTask->task->description }}</p>
                                        @endif
                                        @if($submissionTask->task->instructions)
                                            <div class="mt-3 p-4 bg-blue-50 rounded-xl border border-blue-100">
                                                <div class="flex items-start gap-3">
                                                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                                                    <div class="min-w-0">
                                                        <p class="text-sm font-medium text-blue-900">Instructies</p>
                                                        <p class="text-sm text-blue-800 mt-1 whitespace-pre-line break-words">{{ $submissionTask->task->instructions }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @if($hasMetricRule)
                                            @php
                                                $metricUnit = $validationRules['unit'] ?? '';
                                                $metricLabel = ($validationRules['metric'] ?? '') === 'ph' ? 'pH meting' : 'Temperatuur meting';
                                            @endphp
                                            <div class="mt-3 p-4 rounded-xl border {{ $isCriticalDeviation ? 'bg-red-50 border-red-200' : 'bg-emerald-50 border-emerald-200' }}">
                                                <div class="flex flex-wrap items-center justify-between gap-3">
                                                    <p class="text-sm font-semibold {{ $isCriticalDeviation ? 'text-red-900' : 'text-emerald-900' }}">{{ $metricLabel }}</p>
                                                    <span class="text-xs font-semibold {{ $isCriticalDeviation ? 'text-red-700' : 'text-emerald-700' }}">
                                                        @if(!is_null($metricMin) && !is_null($metricMax))
                                                            Norm: {{ $metricMin }} - {{ $metricMax }} {{ $metricUnit }}
                                                        @elseif(!is_null($metricMin))
                                                            Norm: >= {{ $metricMin }} {{ $metricUnit }}
                                                        @elseif(!is_null($metricMax))
                                                            Norm: {{ $metricComparison === 'lt' ? '<' : '<=' }} {{ $metricMax }} {{ $metricUnit }}
                                                        @else
                                                            Norm niet ingesteld
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="mt-2 text-sm">
                                                    <span class="font-semibold {{ $isCriticalDeviation ? 'text-red-900' : 'text-emerald-900' }}">Gemeten:</span>
                                                    <span class="{{ $isCriticalDeviation ? 'text-red-800' : 'text-emerald-800' }}">
                                                        @if(!is_null($metricValue))
                                                            {{ $metricValue }} {{ $metricUnit }}
                                                        @else
                                                            Niet ingevuld
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($submissionTask->task->checklist_items && count($submissionTask->task->checklist_items) > 0)
                                    @php
                                        $checklistProgress = is_array($submissionTask->checklist_progress) ? $submissionTask->checklist_progress : [];
                                        $completedCount = 0;
                                        foreach($submissionTask->task->checklist_items as $indexItem => $item) {
                                            if (isset($checklistProgress[$indexItem]) && $checklistProgress[$indexItem]) { $completedCount++; }
                                        }
                                        $progressPercent = count($submissionTask->task->checklist_items) > 0 ? round(($completedCount / count($submissionTask->task->checklist_items)) * 100) : 0;
                                    @endphp
                                    <div class="mt-6">
                                        <div class="bg-emerald-50 rounded-xl p-4 sm:p-6 border border-emerald-100">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4 mb-4">
                                                <div class="flex items-center gap-3 min-w-0">
                                                    <div class="w-9 h-9 bg-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                                    </div>
                                                    <h5 class="text-base font-bold text-emerald-900">Checklist</h5>
                                                </div>
                                                <div class="sm:text-right">
                                                    <span class="text-lg sm:text-xl font-bold text-emerald-900">{{ $completedCount }}/{{ count($submissionTask->task->checklist_items) }}</span>
                                                    <span class="text-sm text-emerald-700 ml-1">{{ $progressPercent }}% voltooid</span>
                                                </div>
                                            </div>
                                            <div class="mb-4 h-2 bg-emerald-200 rounded-full overflow-hidden">
                                                <div class="h-full bg-emerald-500 rounded-full transition-all" style="width: {{ $progressPercent }}%"></div>
                                            </div>
                                            <div class="space-y-2">
                                                @foreach($submissionTask->task->checklist_items as $indexItem => $item)
                                                    @php $isChecked = isset($checklistProgress[$indexItem]) && $checklistProgress[$indexItem]; @endphp
                                                    <div class="flex items-start gap-3 p-3 rounded-lg {{ $isChecked ? 'bg-white/80' : 'bg-emerald-100/50' }}">
                                                        <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 {{ $isChecked ? 'bg-emerald-500' : 'border-2 border-slate-300' }}">
                                                            @if($isChecked)<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>@endif
                                                        </div>
                                                        <span class="text-sm break-words {{ $isChecked ? 'text-emerald-900 font-medium' : 'text-slate-600' }}">{{ $item }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if($submissionTask->proof_text || $submissionTask->employee_comment || $submissionTask->proof_files || $submissionTask->digital_signature || !empty($aiTask['comment'] ?? null) || !empty($aiTask['image_feedback'] ?? null))
                                    <div class="mt-6">
                                        <div class="bg-violet-50 rounded-xl p-4 sm:p-6 border border-violet-100">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="w-9 h-9 bg-violet-500 rounded-xl flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                </div>
                                                <h5 class="text-base font-bold text-violet-900">Bewijs medewerker</h5>
                                            </div>
                                                @if($submissionTask->proof_text)
                                                <div class="mb-4 p-4 bg-white/80 rounded-xl">
                                                    <p class="text-sm font-medium text-violet-900 mb-2">Omschrijving</p>
                                                    <p class="text-slate-700 leading-relaxed">{{ $submissionTask->proof_text }}</p>
                                                </div>
                                            @endif

                                            @if($submissionTask->employee_comment && $submissionTask->task->required_proof_type !== 'text')
                                                <div class="mb-4 p-4 bg-white/80 rounded-xl">
                                                    <p class="text-sm font-medium text-violet-900 mb-2">Opmerking medewerker</p>
                                                    <p class="text-slate-700 leading-relaxed">{{ $submissionTask->employee_comment }}</p>
                                                </div>
                                            @endif
                                            
                                            @if($submissionTask->proof_files)
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                                    @foreach($submissionTask->proof_files as $file)
                                                        @php
                                                            $isImage = is_array($file) && isset($file['mime_type']) && strpos($file['mime_type'], 'image/') === 0;
                                                            $isVideo = is_array($file) && isset($file['mime_type']) && strpos($file['mime_type'], 'video/') === 0;
                                                        @endphp
                                                        <div class="bg-white bg-opacity-60 rounded-lg p-4">
                                                            @if($isImage && isset($file['path']))
                                                                <img src="{{ url('storage/' . $file['path']) }}" 
                                                                     alt="Bewijs foto" 
                                                                     class="w-full h-40 sm:h-48 object-cover rounded-lg shadow-sm border border-gray-200 cursor-pointer hover:shadow-md transition-shadow" 
                                                                     onclick="openImageModal('{{ url('storage/' . $file['path']) }}')" />
                                                            @elseif($isVideo && isset($file['path']))
                                                                <video controls class="w-full h-40 sm:h-48 rounded-lg shadow-sm border border-gray-200">
                                                                    <source src="{{ url('storage/' . $file['path']) }}" type="{{ $file['mime_type'] }}">
                                                                    Je browser ondersteunt geen video.
                                                                </video>
                                                            @else
                                                                <div class="flex items-center justify-center h-24 bg-slate-100 rounded-lg">
                                                                    <span class="text-slate-500 text-sm">Bestandsbijlage</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if(!empty($aiTask['comment'] ?? null) || !empty($aiTask['image_feedback'] ?? null))
                                                <div class="mt-4 p-4 bg-slate-900 text-slate-50 rounded-xl border border-slate-800">
                                                    <p class="text-sm font-semibold mb-1.5">AI-opmerking over deze taak</p>
                                                    @if(!empty($aiTask['comment'] ?? null))
                                                        <p class="text-sm mb-1">{{ $aiTask['comment'] }}</p>
                                                    @endif
                                                    @if(!empty($aiTask['image_feedback'] ?? null))
                                                        <p class="text-xs text-slate-300">Over de foto&apos;s: {{ $aiTask['image_feedback'] }}</p>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($submissionTask->digital_signature)
                                                <div class="bg-white/80 rounded-xl p-4">
                                                    <p class="text-sm font-medium text-violet-900 mb-3">Digitale handtekening</p>
                                                    <div class="bg-slate-50 rounded-lg p-3 sm:p-4 overflow-x-auto">
                                                        <img src="{{ $submissionTask->digital_signature }}" 
                                                             alt="Digital Signature" 
                                                             class="border border-gray-300 rounded bg-white max-w-full w-auto max-h-32 shadow-sm">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Enhanced Manager Review -->
                                @if($submissionTask->manager_comment || $submissionTask->rejection_reason)
                                    <div class="mt-6 manager-review-block">
                                        <div class="bg-slate-50 rounded-xl p-4 sm:p-6 border border-slate-200">
                                            <div class="flex items-center gap-3 mb-4">
                                                <div class="w-9 h-9 bg-slate-500 rounded-xl flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                                </div>
                                                <h5 class="text-base font-bold text-slate-900">Beoordeling beheerder</h5>
                                            </div>
                                            @if($submissionTask->rejection_reason)
                                                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4 rejection-reason-block">
                                                <div class="flex items-start gap-3">
                                                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                                                    <div class="min-w-0">
                                                        <p class="font-semibold text-red-900 mb-1">Afwijzingsreden</p>
                                                        <p class="text-red-800 leading-relaxed break-words rejection-reason-text">{{ $submissionTask->rejection_reason }}</p>
                                                    </div>
                                                </div>
                                                </div>
                                            @endif
                                            @if($submissionTask->manager_comment)
                                                <div class="bg-white/80 rounded-xl p-4 manager-comment-block">
                                                    <p class="font-semibold text-slate-900 mb-2">Opmerking beheerder</p>
                                                    <p class="text-slate-700 leading-relaxed manager-comment-text">{{ $submissionTask->manager_comment }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-6 manager-review-block"></div>
                                @endif
                            </div>

                            @if($submissionTask->status === 'completed')
                                <div class="mt-6 xl:mt-0 xl:flex-shrink-0 xl:w-80">
                                    <div class="bg-white rounded-xl p-4 sm:p-6 border border-slate-200 shadow-sm review-action-box">
                                        <h6 class="text-base font-bold text-slate-900 mb-4 text-center">Beoordelingsacties</h6>
                                        <form method="POST" action="{{ route('admin.submission-tasks.approve', $submissionTask) }}" class="mb-4 approve-form" id="approve-form-{{ $submissionTask->id }}">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-slate-700 mb-2">Opmerking goedkeuring (optioneel)</label>
                                                <textarea name="manager_comment" placeholder="Voeg feedback of opmerkingen toe..." rows="3" class="w-full text-sm border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 resize-none"></textarea>
                                            </div>
                                            @if($submissionTask->corrective_action)
                                                <div class="mb-4 rounded-xl border border-blue-100 bg-blue-50 p-3">
                                                    <p class="text-xs font-semibold text-blue-900">Corrigerende actie verifiëren</p>
                                                    <p class="mt-1 text-xs text-blue-800">{{ $submissionTask->corrective_action }}</p>
                                                    <label class="mt-3 flex items-start gap-2 text-xs text-slate-700"><input type="checkbox" name="confirm_corrective_action_closed" value="1" class="mt-0.5 rounded border-slate-300 text-blue-600"> Actie uitgevoerd en aantoonbaar effectief</label>
                                                    <textarea name="verification_note" rows="2" placeholder="Hoe is de werking geverifieerd?" class="mt-2 w-full text-sm border-slate-200 rounded-xl"></textarea>
                                                </div>
                                            @endif
                                            <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition-colors" id="approve-btn-{{ $submissionTask->id }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                Taak goedkeuren
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.submission-tasks.reject', $submissionTask) }}" class="reject-form" id="reject-form-{{ $submissionTask->id }}">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-slate-700 mb-2">Afwijzingsreden (verplicht)</label>
                                                <textarea name="rejection_reason" placeholder="Leg uit waarom deze taak wordt afgewezen..." rows="3" required class="w-full text-sm border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none"></textarea>
                                            </div>
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-slate-700 mb-2">Corrigerende actie <span class="text-red-500">*</span></label>
                                                <textarea name="corrective_action" placeholder="Welke concrete actie moet worden uitgevoerd?" rows="2" required class="w-full text-sm border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500"></textarea>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-1 gap-3 mb-4">
                                                <div><label class="block text-sm font-medium text-slate-700 mb-1">Actiehouder</label><select name="corrective_action_owner_id" required class="w-full text-sm border-slate-200 rounded-xl"><option value="">Kies verantwoordelijke…</option>@foreach($correctiveActionOwners as $owner)<option value="{{ $owner->id }}">{{ $owner->name }}</option>@endforeach</select></div>
                                                <div><label class="block text-sm font-medium text-slate-700 mb-1">Deadline</label><input type="date" name="corrective_action_due_at" min="{{ now()->toDateString() }}" required class="w-full text-sm border-slate-200 rounded-xl"></div>
                                            </div>
                                            <button type="submit" class="w-full inline-flex justify-center items-center gap-2 px-4 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-xl transition-colors" id="reject-btn-{{ $submissionTask->id }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Taak afwijzen
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @elseif($submissionTask->status === 'rejected')
                                <div class="mt-6 xl:mt-0 xl:flex-shrink-0 xl:w-80">
                                    <div class="bg-red-50 rounded-xl p-4 sm:p-6 border border-red-100 review-action-box">
                                        <div class="text-center mb-4">
                                            <div class="w-14 h-14 bg-red-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </div>
                                            <h6 class="text-base font-bold text-red-900 mb-2">Taak afgekeurd</h6>
                                            <p class="text-sm text-red-700 mb-4">Medewerker moet deze taak opnieuw uitvoeren en de checklist opnieuw indienen.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($submissionTask->status === 'approved')
                                <div class="mt-6 xl:mt-0 xl:flex-shrink-0 xl:w-80">
                                    <div class="bg-emerald-50 rounded-xl p-4 sm:p-6 border border-emerald-100 review-action-box">
                                        <div class="text-center">
                                            <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            </div>
                                            <h6 class="text-base font-bold text-emerald-900 mb-2">Taak goedgekeurd</h6>
                                            <p class="text-sm text-emerald-700">Deze taak is beoordeeld en goedgekeurd.</p>
                                        </div>
                                    </div>
                                </div>
                            @elseif($submissionTask->status === 'redo_requested')
                                <div class="mt-6 xl:mt-0 xl:flex-shrink-0 xl:w-80">
                                    <div class="bg-orange-50 rounded-xl p-4 sm:p-6 border border-orange-100 review-action-box">
                                        <div class="text-center">
                                            <div class="w-14 h-14 bg-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg>
                                            </div>
                                            <h6 class="text-base font-bold text-orange-900 mb-2">Opnieuw gevraagd</h6>
                                            <p class="text-sm text-orange-700">De medewerker is gevraagd deze taak opnieuw te doen.</p>
                                            @if($submissionTask->redo_reason)
                                                <div class="mt-4 p-3 bg-orange-100 rounded-xl">
                                                    <p class="text-sm text-orange-800"><strong>Reden:</strong> {{ $submissionTask->redo_reason }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @php
            $submissionAuditEvents = $submission->submissionTasks->flatMap->auditEvents->sortByDesc('created_at');
            $auditEventLabels = ['created'=>'Aangemaakt','submitted'=>'Ingevoerd','resubmitted'=>'Bijgewerkt en opnieuw ingediend','rejected'=>'Afgekeurd','approved'=>'Goedgekeurd','verified'=>'Actie geverifieerd','updated'=>'Gegevens bijgewerkt','legacy_snapshot'=>'Beginstand audittrail'];
        @endphp
        <div class="mt-6 sm:mt-8 bg-white rounded-xl sm:rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100">
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Geschiedenis en audittrail</h2>
                <p class="mt-1 text-sm text-slate-500">Chronologische registratie van invoer, wijzigingen, afkeuringen en goedkeuringen.</p>
            </div>
            <div class="p-4 sm:p-6 overflow-x-auto">
                @forelse($submissionAuditEvents as $event)
                    <div class="relative pl-7 pb-5 last:pb-0 border-l-2 border-slate-200 last:border-transparent">
                        <span class="absolute -left-[7px] top-0 w-3 h-3 rounded-full bg-blue-600 ring-4 ring-blue-50"></span>
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-1 min-w-0">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-900">{{ $auditEventLabels[$event->event_type] ?? $event->event_type }}</p>
                                <p class="text-sm text-slate-600 break-words">{{ $event->submissionTask?->task?->title ?? 'Controlepunt' }} · {{ $event->from_status ?: '—' }} → {{ $event->to_status ?: '—' }}</p>
                            </div>
                            <p class="text-xs text-slate-500 sm:whitespace-nowrap sm:flex-shrink-0">{{ $event->created_at->format('d-m-Y H:i:s') }}</p>
                        </div>
                        <p class="mt-1 text-xs text-slate-500">Door {{ $event->actor?->name ?? 'Systeem' }}</p>
                        @if(data_get($event->snapshot, 'rejection_reason') || data_get($event->snapshot, 'verification_note') || data_get($event->snapshot, 'proof_text'))
                            <p class="mt-2 text-sm text-slate-700 bg-slate-50 rounded-lg px-3 py-2 break-words">{{ data_get($event->snapshot, 'rejection_reason') ?: (data_get($event->snapshot, 'verification_note') ?: data_get($event->snapshot, 'proof_text')) }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-slate-500">Nog geen gebeurtenissen geregistreerd.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center p-3 sm:p-4 bg-black/60 backdrop-blur-sm" onclick="if(event.target===this)closeImageModal()">
    <div class="bg-white rounded-xl sm:rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-end px-3 sm:px-4 py-2 sm:py-3 border-b border-slate-200">
            <button type="button" onclick="closeImageModal()" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors" aria-label="Sluiten">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-3 sm:p-4 overflow-auto max-h-[calc(90vh-60px)]">
            <img id="modalImage" src="" alt="Bewijs foto" class="max-w-full max-h-[70vh] mx-auto rounded-lg object-contain">
        </div>
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    const modal = document.getElementById('imageModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Notification function
function showNotification(message, type = 'success') {
    // Remove existing notification if any
    const existingNotification = document.querySelector('.notification-toast');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 left-4 right-4 sm:left-auto sm:right-5 sm:max-w-sm z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ease-out ${
        type === 'success' 
            ? 'bg-green-500 text-white' 
            : 'bg-red-500 text-white'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                ${type === 'success' 
                    ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                    : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                }
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <div class="ml-auto pl-3">
                <div class="flex">
                    <button type="button" onclick="this.closest('.notification-toast').remove()" class="rounded-md p-1.5 hover:bg-black hover:bg-opacity-10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}

// 🎯 Helpers om UI te updaten zonder refresh
function updateTaskUIAfterApprove(taskId, managerComment) {
    const container = document.querySelector(`.submission-task[data-submission-task-id="${taskId}"]`);
    if (!container) return;

    const badge = container.querySelector('.task-status-badge');
    if (badge) {
        badge.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold border task-status-badge bg-emerald-100 text-emerald-800 border-emerald-200';
        badge.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Goedgekeurd`;
    }

    const box = container.querySelector('.review-action-box');
    if (box) {
        box.className = 'bg-emerald-50 rounded-xl p-5 sm:p-6 border border-emerald-100 review-action-box';
        box.innerHTML = `
            <div class="text-center">
                <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h6 class="text-base font-bold text-emerald-900 mb-2">Taak goedgekeurd</h6>
                <p class="text-sm text-emerald-700">Deze taak is beoordeeld en goedgekeurd.</p>
            </div>
        `;
    }

    if (managerComment && managerComment.trim() !== '') {
        let reviewBlock = container.querySelector('.manager-review-block');
        if (!reviewBlock) {
            reviewBlock = document.createElement('div');
            reviewBlock.className = 'mt-6 manager-review-block';
            const proofSection = container.querySelector('.bg-violet-50')?.closest('.mt-6');
            if (proofSection && proofSection.parentElement) {
                proofSection.parentElement.insertAdjacentElement('afterend', reviewBlock);
            } else {
                container.querySelector('.flex-1')?.appendChild(reviewBlock);
            }
        }
        reviewBlock.innerHTML = `
            <div class="bg-slate-50 rounded-xl p-5 sm:p-6 border border-slate-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 bg-slate-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <h5 class="text-base font-bold text-slate-900">Beoordeling beheerder</h5>
                </div>
                <div class="bg-white/80 rounded-xl p-4 manager-comment-block">
                    <p class="font-semibold text-slate-900 mb-2">Opmerking beheerder</p>
                    <p class="text-slate-700 leading-relaxed manager-comment-text"></p>
                </div>
            </div>
        `;
        reviewBlock.querySelector('.manager-comment-text').textContent = managerComment;
    }
}

function updateTaskUIAfterReject(taskId, rejectionReason) {
    const container = document.querySelector(`.submission-task[data-submission-task-id="${taskId}"]`);
    if (!container) return;

    const badge = container.querySelector('.task-status-badge');
    if (badge) {
        badge.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold border task-status-badge bg-red-100 text-red-800 border-red-200';
        badge.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Afgewezen`;
    }

    const box = container.querySelector('.review-action-box');
    if (box) {
        box.className = 'bg-red-50 rounded-xl p-5 sm:p-6 border border-red-100 review-action-box';
        box.innerHTML = `
            <div class="text-center">
                <div class="w-14 h-14 bg-red-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <h6 class="text-base font-bold text-red-900 mb-2">Taak afgekeurd</h6>
                <p class="text-sm text-red-700">Medewerker moet deze taak opnieuw uitvoeren en de checklist opnieuw indienen.</p>
            </div>
        `;
    }

    let reviewBlock = container.querySelector('.manager-review-block');
    if (!reviewBlock) {
        reviewBlock = document.createElement('div');
        reviewBlock.className = 'mt-6 manager-review-block';
        const proofSection = container.querySelector('.bg-violet-50')?.closest('.mt-6');
        if (proofSection && proofSection.parentElement) {
            proofSection.parentElement.insertAdjacentElement('afterend', reviewBlock);
        } else {
            container.querySelector('.flex-1')?.appendChild(reviewBlock);
        }
    }
    reviewBlock.innerHTML = `
        <div class="bg-slate-50 rounded-xl p-5 sm:p-6 border border-slate-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 bg-slate-500 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
                <h5 class="text-base font-bold text-slate-900">Beoordeling beheerder</h5>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 rejection-reason-block">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    <div>
                        <p class="font-semibold text-red-900 mb-1">Afwijzingsreden</p>
                        <p class="text-red-800 leading-relaxed rejection-reason-text"></p>
                    </div>
                </div>
            </div>
        </div>
    `;
    reviewBlock.querySelector('.rejection-reason-text').textContent = rejectionReason;
}

async function parseJsonResponseOrThrow(response, fallbackMessage) {
    const contentType = response.headers.get('content-type') || '';

    if (!response.ok || !contentType.includes('application/json')) {
        throw new Error(fallbackMessage);
    }

    const payload = await response.json();
    if (!payload || payload.success !== true) {
        throw new Error(payload?.message || fallbackMessage);
    }

    return payload;
}

// Auto handling of forms (AJAX + direct UI update)
document.addEventListener('DOMContentLoaded', function() {
    // Approve
    document.querySelectorAll('[id^="approve-form-"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const taskId = form.id.replace('approve-form-', '');
            const submitBtn = document.getElementById('approve-btn-' + taskId);
            const managerComment = form.querySelector('textarea[name="manager_comment"]')?.value || '';

            submitBtn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Verwerken...`;
            submitBtn.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => parseJsonResponseOrThrow(response, 'Goedkeuren mislukt. Controleer je sessie en probeer opnieuw.'))
            .then(() => {
                showNotification('Taak succesvol goedgekeurd!', 'success');
                updateTaskUIAfterApprove(taskId, managerComment);
            })
            .catch(error => {
                showNotification(error.message || 'Fout bij goedkeuren. Probeer het opnieuw.', 'error');
                submitBtn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Taak goedkeuren
                `;
                submitBtn.disabled = false;
            });
        });
    });
    
    // Reject
    document.querySelectorAll('[id^="reject-form-"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const taskId = form.id.replace('reject-form-', '');
            const submitBtn = document.getElementById('reject-btn-' + taskId);
            const rejectionReason = form.querySelector('textarea[name="rejection_reason"]').value.trim();
            
            if (!rejectionReason) {
                showNotification('Afwijzingsreden is verplicht', 'error');
                return;
            }
            
            submitBtn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Verwerken...`;
            submitBtn.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => parseJsonResponseOrThrow(response, 'Afwijzen mislukt. Controleer je sessie en probeer opnieuw.'))
            .then((payload) => {
                showNotification(`Taak afgewezen. Notificatie #${payload.notification_id ?? '-'} voor user ${payload.notification_user_id ?? '-'}.`, 'success');
                updateTaskUIAfterReject(taskId, rejectionReason);
            })
            .catch(error => {
                showNotification(error.message || 'Afwijzen mislukt. Probeer het opnieuw.', 'error');
                submitBtn.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reject Task
                `;
                submitBtn.disabled = false;
            });
        });
    });
});
</script>

@endsection

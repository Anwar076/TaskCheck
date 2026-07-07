@php
    $tasks = $list->tasks->sortBy('order_index')->values();
    $proofLabels = [
        'none' => null,
        'photo' => 'Foto',
        'video' => 'Video',
        'text' => 'Tekst',
        'file' => 'Bestand',
        'any' => 'Bewijs',
    ];
    $weekdayLabels = [
        'monday' => 'Maandag',
        'tuesday' => 'Dinsdag',
        'wednesday' => 'Woensdag',
        'thursday' => 'Donderdag',
        'friday' => 'Vrijdag',
        'saturday' => 'Zaterdag',
        'sunday' => 'Zondag',
    ];
@endphp

<div class="mb-6 sm:mb-8 bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-100 overflow-hidden" data-onboarding-target="list-tasks">
    <div class="px-4 sm:px-6 py-4 sm:py-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">Taken op deze lijst</h2>
                <p class="text-sm text-slate-600">
                    {{ $tasks->count() }} {{ $tasks->count() === 1 ? 'taak' : 'taken' }}
                    @if($tasks->where('is_required', true)->count() > 0)
                        · {{ $tasks->where('is_required', true)->count() }} verplicht
                    @endif
                </p>
            </div>
        </div>
        <button type="button"
                data-open-task-create
                data-onboarding-target="list-add-task"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Taak toevoegen
        </button>
    </div>

    <div class="p-4 sm:p-6">
        @if($tasks->isNotEmpty())
            <p class="mb-4 text-xs text-slate-500 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6h2v2H8V6zm0 4h2v2H8v-2zm0 4h2v2H8v-2zm4-8h2v2h-2V6zm0 4h2v2h-2v-2zm0 4h2v2h-2v-2z"/></svg>
                Sleep taken om de volgorde aan te passen
            </p>
            <div id="sortable-tasks" class="space-y-3">
                @foreach($tasks as $index => $task)
                    <div class="task-item group rounded-xl border border-slate-200 bg-white hover:border-blue-200 hover:shadow-sm transition-all"
                         data-task-id="{{ $task->id }}">
                        <div class="flex items-start gap-3 p-4">
                            <div class="drag-handle cursor-grab active:cursor-grabbing mt-0.5 p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 touch-manipulation flex-shrink-0"
                                 title="Slepen om te sorteren">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8 6h2v2H8V6zm0 4h2v2H8v-2zm0 4h2v2H8v-2zm4-8h2v2h-2V6zm0 4h2v2h-2v-2zm0 4h2v2h-2v-2z"/>
                                </svg>
                            </div>

                            <div class="task-order-badge flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-sm font-bold text-white shadow-sm">
                                {{ $index + 1 }}
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-start justify-between gap-2">
                                    <h3 class="font-semibold text-slate-900 leading-snug">{{ $task->title }}</h3>
                                    <div class="flex items-center gap-1 flex-shrink-0 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                        <button type="button"
                                                data-open-task-edit
                                                data-task-id="{{ $task->id }}"
                                                class="p-2 text-slate-500 hover:text-blue-600 rounded-lg hover:bg-blue-50 transition-colors"
                                                title="Bewerken">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                            </svg>
                                        </button>
                                        <form method="POST"
                                              action="{{ route('admin.tasks.destroy', $task) }}"
                                              class="inline"
                                              onsubmit="return confirm('Weet je zeker dat je deze taak wilt verwijderen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 text-slate-500 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors"
                                                    title="Verwijderen">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                @if($task->description)
                                    <p class="mt-1 text-sm text-slate-600 line-clamp-2">{{ $task->description }}</p>
                                @endif

                                <div class="mt-3 flex flex-wrap gap-1.5">
                                    @if($task->is_required)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-red-50 text-red-700 ring-1 ring-red-100">
                                            Verplicht
                                        </span>
                                    @endif
                                    @if($task->requires_signature)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium bg-violet-50 text-violet-700 ring-1 ring-violet-100">
                                            Handtekening
                                        </span>
                                    @endif
                                    @if($task->required_proof_type && $task->required_proof_type !== 'none' && ($proofLabels[$task->required_proof_type] ?? null))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-blue-50 text-blue-700 ring-1 ring-blue-100">
                                            {{ $proofLabels[$task->required_proof_type] }} vereist
                                        </span>
                                    @endif
                                    @if($task->weekday && isset($weekdayLabels[$task->weekday]))
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-amber-50 text-amber-800 ring-1 ring-amber-100">
                                            {{ $weekdayLabels[$task->weekday] }}
                                        </span>
                                    @endif
                                    @if($task->start_time || $task->end_time)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-medium bg-slate-100 text-slate-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $task->start_time ? substr($task->start_time, 0, 5) : '—' }}
                                            –
                                            {{ $task->end_time ? substr($task->end_time, 0, 5) : '—' }}
                                        </span>
                                    @endif
                                    @if(is_array($task->checklist_items) && count($task->checklist_items) > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-medium bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                                            {{ count($task->checklist_items) }} checklist-items
                                        </span>
                                    @endif
                                </div>

                                @if($task->instructions)
                                    <div class="mt-3 rounded-lg bg-slate-50 border border-slate-100 px-3 py-2">
                                        <p class="text-xs font-medium text-slate-500 mb-0.5">Instructies</p>
                                        <p class="text-sm text-slate-600 line-clamp-2">{{ $task->instructions }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-14 px-4">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-semibold text-slate-900 mb-2">Nog geen taken</h3>
                <p class="text-slate-600 text-sm mb-6 max-w-sm mx-auto">
                    Voeg taken toe aan deze lijst. Medewerkers zien ze wanneer de lijst is toegewezen.
                </p>
                <button type="button"
                        data-open-task-create
                        data-onboarding-target="list-add-task"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-xl hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Eerste taak toevoegen
                </button>
            </div>
        @endif
    </div>
</div>

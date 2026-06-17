@props(['tasks', 'list', 'calendarService' => null])

@php
    use App\Services\Admin\ListCalendarService;

    $service = $calendarService ?? app(ListCalendarService::class);
    $schedule = $service->partitionTasksForDay($tasks);
    $allDayTasks = $schedule['all_day_tasks'];
    $timedTasks = $schedule['timed_tasks'];
@endphp

@if($timedTasks->isNotEmpty())
    <div class="mb-6">
        <div class="mb-3 text-[11px] font-medium uppercase tracking-wide text-slate-500">Tijdschema</div>
        <div class="max-w-3xl space-y-2">
            @foreach($timedTasks as $entry)
                @php $task = $entry['task']; @endphp
                <div class="task-item group flex items-stretch gap-3 rounded-lg border border-slate-200 bg-white shadow-sm hover:shadow-md transition-shadow" data-task-id="{{ $task->id }}">
                    <div class="flex w-16 shrink-0 flex-col items-center justify-center border-r border-slate-100 bg-slate-50 px-2 py-3 text-center">
                        <span class="text-xs font-semibold text-slate-700">{{ $entry['start_time'] }}</span>
                        @if($entry['end_time'])
                            <span class="text-[10px] text-slate-400">{{ $entry['end_time'] }}</span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1 p-3">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0 flex-1 border-l-[3px] pl-3 {{ empty($task->weekday) ? 'border-blue-600' : 'border-violet-600' }}">
                                <h4 class="font-medium text-slate-900">{{ $task->title }}</h4>
                                <p class="mt-0.5 text-xs text-slate-500">{{ $entry['time_label'] }}</p>
                                @if($task->description)
                                    <p class="mt-1 text-sm text-slate-600">{{ Str::limit($task->description, 120) }}</p>
                                @endif
                            </div>
                            <div class="flex shrink-0 gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                <a href="{{ route('admin.tasks.edit', $task) }}" class="rounded p-1.5 text-slate-500 hover:bg-blue-50 hover:text-blue-600" title="Bewerken">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

@if($allDayTasks->isNotEmpty())
    <div>
        <div class="mb-3 text-[11px] font-medium uppercase tracking-wide text-slate-500">Hele dag</div>
        <div id="sortable-day-{{ $sortableKey ?? 'all-day' }}" class="task-day-list max-w-3xl space-y-2">
            @foreach($allDayTasks as $task)
                <div class="task-item group cursor-grab rounded-lg border border-slate-200 bg-white p-3 shadow-sm hover:shadow-md transition-shadow" data-task-id="{{ $task->id }}">
                    <div class="flex items-start gap-3">
                        <div class="drag-handle mt-0.5 cursor-grab text-slate-400 hover:text-slate-600">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6h2v2H8V6zm0 4h2v2H8v-2zm0 4h2v2H8v-2zm4-8h2v2h-2V6zm0 4h2v2h-2v-2zm0 4h2v2h-2v-2z"/></svg>
                        </div>
                        <div class="min-w-0 flex-1 border-l-[3px] pl-3 {{ empty($task->weekday) ? 'border-blue-600' : 'border-violet-600' }}">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <h4 class="font-medium text-slate-900">{{ $task->title }}</h4>
                                    <p class="mt-0.5 text-xs text-slate-500">Geen vaste tijd</p>
                                    @if($task->description)
                                        <p class="mt-0.5 text-sm text-slate-600">{{ Str::limit($task->description, 120) }}</p>
                                    @endif
                                    @if($task->is_required)
                                        <span class="mt-1 inline-block text-xs text-red-600">Verplicht</span>
                                    @endif
                                </div>
                                <div class="flex shrink-0 gap-1 opacity-0 transition-opacity group-hover:opacity-100">
                                    <a href="{{ route('admin.tasks.edit', $task) }}" class="rounded p-1.5 text-slate-500 hover:bg-blue-50 hover:text-blue-600" title="Bewerken">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" onsubmit="return confirm('Taak verwijderen?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rounded p-1.5 text-slate-500 hover:bg-red-50 hover:text-red-600" title="Verwijderen">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

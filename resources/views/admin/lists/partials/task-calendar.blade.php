@php
    $calendarView = $calendarView ?? 'week';
    $selectedDay = $selectedDay ?? strtolower(now()->format('l'));
    $weekQuery = ['week' => $calendar['week_start'] ?? (isset($weekStart) ? $weekStart->format('Y-m-d') : now()->startOfWeek()->format('Y-m-d'))];
    $monthQuery = ['month' => $calendar['month_start'] ?? ($miniMonth['month_start'] ?? now()->format('Y-m-01'))];
    $todayLink = route('admin.lists.show', [
        $list,
        'view' => $calendarView,
        'week' => now()->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d'),
        'month' => now()->format('Y-m-01'),
        'day' => strtolower(now()->format('l')),
    ]);

    $selectedDayData = collect($calendar['days'] ?? [])->firstWhere('key', $selectedDay)
        ?? ($calendar['days'][0] ?? null);

    $navStep = function (int $direction) use ($calendarView, $calendar, $selectedDayData) {
        if ($calendarView === 'month') {
            return $direction < 0 ? ['month' => $calendar['prev']] : ['month' => $calendar['next']];
        }

        if ($calendarView === 'day' && $selectedDayData) {
            $date = \Carbon\Carbon::parse($selectedDayData['date'])->addDays($direction);

            return [
                'day' => strtolower($date->format('l')),
                'week' => $date->copy()->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d'),
            ];
        }

        return $direction < 0 ? ['week' => $calendar['prev']] : ['week' => $calendar['next']];
    };

    $navParams = function (array $extra = []) use ($list, $calendarView, $selectedDay, $weekQuery, $monthQuery) {
        $base = match ($calendarView) {
            'month' => array_merge(['view' => 'month'], $monthQuery),
            'day' => array_merge(['view' => 'day', 'day' => $selectedDay], $weekQuery),
            default => array_merge(['view' => 'week'], $weekQuery),
        };

        return route('admin.lists.show', array_merge([$list], $base, $extra));
    };
@endphp

<div class="mb-6 sm:mb-8 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" data-onboarding-target="list-tasks">
    {{-- Google Calendar toolbar --}}
    <div class="flex flex-col gap-3 border-b border-slate-200 px-3 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-4">
        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
            <a href="{{ $todayLink }}"
               class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                Vandaag
            </a>
            <div class="inline-flex items-center">
                <a href="{{ $navParams($navStep(-1)) }}"
                   class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-600 hover:bg-slate-100"
                   title="Vorige">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                </a>
                <a href="{{ $navParams($navStep(1)) }}"
                   class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-600 hover:bg-slate-100"
                   title="Volgende">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
            <h2 class="text-lg font-normal text-slate-800 sm:text-xl capitalize">
                @if($calendarView === 'week')
                    {{ $calendar['week_label'] ?? $calendar['title'] }}
                @elseif($calendarView === 'day' && $selectedDayData)
                    {{ $selectedDayData['date_label'] }}
                @else
                    {{ $calendar['title'] ?? '' }}
                @endif
            </h2>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <div class="inline-flex rounded-lg border border-slate-200 bg-slate-50 p-0.5 text-sm" data-onboarding-target="calendar-view-switch">
                @foreach(['week' => 'Week', 'day' => 'Dag', 'month' => 'Maand'] as $viewKey => $viewLabel)
                    @php
                        $viewLink = match ($viewKey) {
                            'month' => route('admin.lists.show', [$list, 'view' => 'month', 'month' => $miniMonth['month_start'] ?? now()->format('Y-m-01')]),
                            'day' => route('admin.lists.show', array_merge([$list], $weekQuery, ['view' => 'day', 'day' => $selectedDayData['key'] ?? $selectedDay, 'month' => $miniMonth['month_start'] ?? now()->format('Y-m-01')])),
                            default => route('admin.lists.show', array_merge([$list], $weekQuery, ['view' => 'week', 'month' => $miniMonth['month_start'] ?? now()->format('Y-m-01')])),
                        };
                    @endphp
                    <a href="{{ $viewLink }}"
                       class="rounded-md px-3 py-1.5 font-medium transition-colors {{ $calendarView === $viewKey ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">
                        {{ $viewLabel }}
                    </a>
                @endforeach
            </div>
            <button type="button" data-open-task-create data-onboarding-target="list-add-task"
               class="inline-flex items-center gap-1.5 rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Taak
            </button>
        </div>
    </div>

    <div class="flex">
        {{-- Mini month (Google sidebar) --}}
        <aside class="hidden w-56 shrink-0 border-r border-slate-200 p-3 xl:block">
            <div class="mb-2 flex items-center justify-between">
                <span class="text-sm font-medium capitalize text-slate-700">{{ $miniMonth['title'] }}</span>
                <div class="flex">
                    <a href="{{ route('admin.lists.show', array_merge([$list], ['view' => $calendarView, 'month' => $miniMonth['prev']], $calendarView === 'month' ? [] : $weekQuery, $calendarView === 'day' ? ['day' => $selectedDay] : [])) }}"
                       class="rounded p-1 text-slate-500 hover:bg-slate-100"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg></a>
                    <a href="{{ route('admin.lists.show', array_merge([$list], ['view' => $calendarView, 'month' => $miniMonth['next']], $calendarView === 'month' ? [] : $weekQuery, $calendarView === 'day' ? ['day' => $selectedDay] : [])) }}"
                       class="rounded p-1 text-slate-500 hover:bg-slate-100"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></a>
                </div>
            </div>
            <div class="grid grid-cols-7 gap-0.5 text-center text-[10px] font-medium text-slate-500">
                @foreach($miniMonth['weekday_headers'] as $header)
                    <div class="py-1">{{ $header }}</div>
                @endforeach
            </div>
            @foreach($miniMonth['weeks'] as $miniWeek)
                <div class="grid grid-cols-7 gap-0.5">
                    @foreach($miniWeek as $miniDay)
                        <a href="{{ route('admin.lists.show', array_merge([$list], $weekQuery, ['view' => 'day', 'day' => $miniDay['key'], 'week' => \Carbon\Carbon::parse($miniDay['date'])->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d')])) }}"
                           class="flex h-7 w-7 items-center justify-center rounded-full text-xs transition-colors
                           {{ !$miniDay['is_current_month'] ? 'text-slate-300' : 'text-slate-700 hover:bg-slate-100' }}
                           {{ $miniDay['is_today'] ? '!bg-blue-600 !text-white hover:!bg-blue-700' : '' }}
                           {{ ($calendarView === 'week' && isset($calendar['week_start'], $calendar['week_end']) && \Carbon\Carbon::parse($miniDay['date'])->between(\Carbon\Carbon::parse($calendar['week_start']), \Carbon\Carbon::parse($calendar['week_end']))) ? 'bg-blue-50 text-blue-700' : '' }}">
                            {{ $miniDay['day_number'] }}
                        </a>
                    @endforeach
                </div>
            @endforeach
            <p class="mt-3 text-[11px] leading-relaxed text-slate-500">{{ $calendar['schedule_summary'] ?? $miniMonth['schedule_summary'] }}</p>
            <a href="{{ route('admin.lists.edit', $list) }}" class="mt-1 inline-block text-[11px] font-medium text-blue-600 hover:text-blue-800">Planning bewerken</a>
        </aside>

        <div class="min-w-0 flex-1">
            @if($calendarView === 'month')
                {{-- Month grid --}}
                <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50 text-center text-xs font-medium uppercase tracking-wide text-slate-500">
                    @foreach($calendar['weekday_headers'] as $header)
                        <div class="border-r border-slate-200 py-2 last:border-r-0">{{ $header }}</div>
                    @endforeach
                </div>
                @foreach($calendar['weeks'] as $weekRow)
                    <div class="grid grid-cols-7 border-b border-slate-200 last:border-b-0">
                        @foreach($weekRow as $day)
                            <div class="min-h-[7.5rem] border-r border-slate-200 p-1 last:border-r-0 {{ !$day['is_current_month'] ? 'bg-slate-50/60' : 'bg-white' }}">
                                <div class="flex items-start justify-between gap-1">
                                    <a href="{{ route('admin.lists.show', array_merge([$list], ['view' => 'day', 'day' => $day['key'], 'week' => \Carbon\Carbon::parse($day['date'])->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d')])) }}"
                                       class="inline-flex h-7 w-7 items-center justify-center rounded-full text-sm {{ $day['is_today'] ? 'bg-blue-600 font-semibold text-white' : ($day['is_current_month'] ? 'text-slate-800 hover:bg-slate-100' : 'text-slate-400') }}">
                                        {{ $day['day_number'] }}
                                    </a>
                                    @if($day['is_list_active'] && $day['task_count'] > 0)
                                        <span class="rounded bg-blue-100 px-1.5 py-0.5 text-[10px] font-medium text-blue-800">{{ $day['task_count'] }}</span>
                                    @endif
                                </div>
                                <div class="mt-1 space-y-0.5">
                                    @if($day['is_list_active'])
                                        @include('admin.lists.partials.list-calendar-event', [
                                            'list' => $list,
                                            'day' => $day,
                                            'taskCount' => $day['tasks']->count(),
                                            'variant' => 'mini',
                                            'timeLabel' => app(\App\Services\Admin\ListCalendarService::class)->primaryTimeLabelForListOnDay($list, $day['key']),
                                        ])
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

            @elseif($calendarView === 'week')
                @include('admin.lists.partials.calendar-time-grid', [
                    'days' => $calendar['days'],
                    'timeHours' => $calendar['time_hours'] ?? [],
                    'list' => $list,
                    'weekQuery' => $weekQuery,
                    'scope' => 'list',
                ])

            @else
                {{-- Day view --}}
                @if($selectedDayData)
                    @if(!$selectedDayData['is_list_active'])
                        <div class="mx-4 mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 sm:mx-6">
                            Geen planning op deze dag.
                            <a href="{{ route('admin.lists.edit', $list) }}" class="font-semibold underline">Planning aanpassen</a>
                        </div>
                    @else
                        @include('admin.lists.partials.calendar-time-grid', [
                            'days' => [$selectedDayData],
                            'timeHours' => $calendar['time_hours'] ?? [],
                            'list' => $list,
                            'weekQuery' => $weekQuery,
                            'scope' => 'list',
                            'singleDay' => true,
                        ])
                    @endif

                    <div class="border-t border-slate-200 bg-slate-50/50 px-4 py-2 text-[11px] font-medium uppercase tracking-wide text-slate-500 sm:px-6">
                        Taken op {{ $selectedDayData['label_full'] ?? $selectedDayData['label'] }}
                    </div>

                    <div class="p-4 sm:p-6">
                        @if($selectedDayData['tasks']->isNotEmpty())
                            @include('admin.lists.partials.calendar-day-tasks', [
                                'tasks' => $selectedDayData['tasks'],
                                'list' => $list,
                                'sortableKey' => $selectedDayData['key'],
                            ])
                        @else
                            <div class="max-w-lg rounded-xl border border-dashed border-slate-300 py-12 text-center">
                                <p class="text-slate-600">Geen taken op deze dag</p>
                                <div class="mt-4 flex flex-wrap justify-center gap-2">
                                    <button type="button" data-open-task-create class="rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">Taak toevoegen</button>
                                    @if($selectedDayData['is_list_active'])
                                        <button type="button" data-open-task-create data-weekday="{{ $selectedDayData['key'] }}" class="rounded-full border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50">Taak voor {{ $selectedDayData['label'] }}</button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

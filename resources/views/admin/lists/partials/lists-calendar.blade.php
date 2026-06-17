@php
    $calendarView = $calendarView ?? 'week';
    $selectedDay = $selectedDay ?? strtolower(now()->format('l'));
    $locationId = $locationId ?? null;
    $weekQuery = ['week' => $calendar['week_start'] ?? (isset($weekStart) ? $weekStart->format('Y-m-d') : now()->startOfWeek()->format('Y-m-d'))];
    $monthQuery = ['month' => $calendar['month_start'] ?? ($miniMonth['month_start'] ?? now()->format('Y-m-01'))];
    $filterQuery = $locationId ? ['location_id' => $locationId] : [];

    $todayLink = route('admin.lists.calendar', array_merge([
        'view' => $calendarView,
        'week' => now()->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d'),
        'month' => now()->format('Y-m-01'),
        'day' => strtolower(now()->format('l')),
    ], $filterQuery));

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

    $navParams = function (array $extra = []) use ($calendarView, $selectedDay, $weekQuery, $monthQuery, $filterQuery) {
        $base = match ($calendarView) {
            'month' => array_merge(['view' => 'month'], $monthQuery),
            'day' => array_merge(['view' => 'day', 'day' => $selectedDay], $weekQuery),
            default => array_merge(['view' => 'week'], $weekQuery),
        };

        return route('admin.lists.calendar', array_merge($base, $filterQuery, $extra));
    };
@endphp

<div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm" data-onboarding-target="calendar-main">
    <div class="flex flex-col gap-3 border-b border-slate-200 px-3 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-4">
        <div class="flex flex-wrap items-center gap-2 sm:gap-3">
            <a href="{{ $todayLink }}" class="inline-flex items-center rounded-full border border-slate-300 bg-white px-4 py-1.5 text-sm font-medium text-slate-700 hover:bg-slate-50">Vandaag</a>
            <div class="inline-flex items-center">
                <a href="{{ $navParams($navStep(-1)) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-600 hover:bg-slate-100" title="Vorige">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                </a>
                <a href="{{ $navParams($navStep(1)) }}" class="inline-flex h-9 w-9 items-center justify-center rounded-full text-slate-600 hover:bg-slate-100" title="Volgende">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>
            <h2 class="text-lg font-normal capitalize text-slate-800 sm:text-xl">
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
                            'month' => route('admin.lists.calendar', array_merge(['view' => 'month', 'month' => $miniMonth['month_start'] ?? now()->format('Y-m-01')], $filterQuery)),
                            'day' => route('admin.lists.calendar', array_merge($weekQuery, ['view' => 'day', 'day' => $selectedDayData['key'] ?? $selectedDay, 'month' => $miniMonth['month_start'] ?? now()->format('Y-m-01')], $filterQuery)),
                            default => route('admin.lists.calendar', array_merge($weekQuery, ['view' => 'week', 'month' => $miniMonth['month_start'] ?? now()->format('Y-m-01')], $filterQuery)),
                        };
                    @endphp
                    <a href="{{ $viewLink }}" class="rounded-md px-3 py-1.5 font-medium transition-colors {{ $calendarView === $viewKey ? 'bg-white text-blue-700 shadow-sm' : 'text-slate-600 hover:text-slate-900' }}">{{ $viewLabel }}</a>
                @endforeach
            </div>
            <a href="{{ route('admin.lists.create') }}" data-onboarding-target="calendar-add-list" class="inline-flex items-center gap-1.5 rounded-full bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Lijst
            </a>
        </div>
    </div>

    <div class="flex">
        <aside class="hidden w-56 shrink-0 border-r border-slate-200 p-3 xl:block">
            <div class="mb-2 flex items-center justify-between">
                <span class="text-sm font-medium capitalize text-slate-700">{{ $miniMonth['title'] }}</span>
                <div class="flex">
                    <a href="{{ route('admin.lists.calendar', array_merge(['view' => $calendarView, 'month' => $miniMonth['prev']], $calendarView === 'month' ? [] : $weekQuery, $calendarView === 'day' ? ['day' => $selectedDay] : [], $filterQuery)) }}" class="rounded p-1 text-slate-500 hover:bg-slate-100"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg></a>
                    <a href="{{ route('admin.lists.calendar', array_merge(['view' => $calendarView, 'month' => $miniMonth['next']], $calendarView === 'month' ? [] : $weekQuery, $calendarView === 'day' ? ['day' => $selectedDay] : [], $filterQuery)) }}" class="rounded p-1 text-slate-500 hover:bg-slate-100"><svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg></a>
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
                        <a href="{{ route('admin.lists.calendar', array_merge($weekQuery, ['view' => 'day', 'day' => $miniDay['key'], 'week' => \Carbon\Carbon::parse($miniDay['date'])->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d')], $filterQuery)) }}"
                           class="flex h-7 w-7 items-center justify-center rounded-full text-xs transition-colors
                           {{ !$miniDay['is_current_month'] ? 'text-slate-300' : 'text-slate-700 hover:bg-slate-100' }}
                           {{ $miniDay['is_today'] ? '!bg-blue-600 !text-white hover:!bg-blue-700' : '' }}
                           {{ ($calendarView === 'week' && isset($calendar['week_start'], $calendar['week_end']) && \Carbon\Carbon::parse($miniDay['date'])->between(\Carbon\Carbon::parse($calendar['week_start']), \Carbon\Carbon::parse($calendar['week_end']))) ? 'bg-blue-50 text-blue-700' : '' }}">
                            {{ $miniDay['day_number'] }}
                        </a>
                    @endforeach
                </div>
            @endforeach
            <p class="mt-3 text-[11px] leading-relaxed text-slate-500">{{ $calendar['total_lists'] ?? 0 }} actieve lijsten in deze weergave.</p>
        </aside>

        <div class="min-w-0 flex-1">
            @if($calendarView === 'month')
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
                                    <a href="{{ route('admin.lists.calendar', array_merge(['view' => 'day', 'day' => $day['key'], 'week' => \Carbon\Carbon::parse($day['date'])->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d')], $filterQuery)) }}"
                                       class="inline-flex h-7 w-7 items-center justify-center rounded-full text-sm {{ $day['is_today'] ? 'bg-blue-600 font-semibold text-white' : ($day['is_current_month'] ? 'text-slate-800 hover:bg-slate-100' : 'text-slate-400') }}">
                                        {{ $day['day_number'] }}
                                    </a>
                                    @if($day['list_count'] > 0)
                                        <span class="rounded bg-blue-100 px-1.5 py-0.5 text-[10px] font-medium text-blue-800">{{ $day['list_count'] }}</span>
                                    @endif
                                </div>
                                <div class="mt-1 space-y-0.5">
                                    @foreach($day['lists']->take(3) as $listItem)
                                        @include('admin.lists.partials.list-calendar-event', [
                                            'list' => $listItem,
                                            'day' => $day,
                                            'variant' => 'mini',
                                            'timeLabel' => app(\App\Services\Admin\ListCalendarService::class)->primaryTimeLabelForListOnDay($listItem, $day['key']),
                                        ])
                                    @endforeach
                                    @if($day['lists']->count() > 3)
                                        <a href="{{ route('admin.lists.calendar', array_merge(['view' => 'day', 'day' => $day['key'], 'week' => \Carbon\Carbon::parse($day['date'])->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d')], $filterQuery)) }}"
                                           class="block px-1 text-[10px] font-medium text-slate-500 hover:text-blue-600">
                                            +{{ $day['lists']->count() - 3 }} meer
                                        </a>
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
                    'weekQuery' => $weekQuery,
                    'filterQuery' => $filterQuery,
                    'scope' => 'company',
                    'allLists' => $lists ?? collect(),
                ])

            @else
                @if($selectedDayData)
                    @include('admin.lists.partials.calendar-time-grid', [
                        'days' => [$selectedDayData],
                        'timeHours' => $calendar['time_hours'] ?? [],
                        'weekQuery' => $weekQuery,
                        'filterQuery' => $filterQuery,
                        'scope' => 'company',
                        'allLists' => $lists ?? collect(),
                        'singleDay' => true,
                    ])
                @endif
            @endif
        </div>
    </div>
</div>

@php
    use App\Services\Admin\ListCalendarService;

    $timeHours = $timeHours ?? ($calendar['time_hours'] ?? []);
    $timeAxis = $calendar['time_axis'] ?? [
        'start_hour' => ListCalendarService::DAY_START_HOUR,
        'end_hour' => ListCalendarService::DAY_END_HOUR,
    ];
    $hourCount = max(count($timeHours), 1);
    $gridHeight = $hourCount * 3;
    $scope = $scope ?? 'list';
    $list = $list ?? null;
    $allLists = $allLists ?? collect();
    $slotMinutes = ListCalendarService::DEFAULT_TASK_DURATION_MINUTES;
    $singleDay = $singleDay ?? (count($days) === 1);
    $gridCols = $singleDay ? 'grid-cols-[5rem_minmax(0,1fr)]' : 'grid-cols-8';
    $wrapperClass = $singleDay ? 'w-full' : 'min-w-[760px]';

    $dayCreateConfig = function ($day) use ($scope, $list, $allLists) {
        $selectableLists = $scope === 'company'
            ? $allLists
            : ($list ? collect([$list]) : collect());

        return [
            'weekday' => $day['key'],
            'canCreate' => $selectableLists->isNotEmpty(),
            'lists' => $selectableLists->map(fn ($listItem) => [
                'id' => $listItem->id,
                'title' => $listItem->title,
                'storeUrl' => route('admin.lists.schedule-slot', $listItem),
            ])->values()->all(),
        ];
    };

    $showAllDayRow = collect($days)->contains(function ($day) use ($scope) {
        if ($scope === 'list' && ! ($day['is_list_active'] ?? true)) {
            return true;
        }

        return ($day['all_day_lists'] ?? collect())->isNotEmpty();
    });

    $timeGridStartRow = $showAllDayRow ? 2 : 1;
    $timeGridEndRow = $timeGridStartRow + $hourCount;
    $hourRowHeight = '3rem';
@endphp

<style>
    .calendar-timed-list-btn {
        display: block;
        min-height: 0;
        box-sizing: border-box;
    }
    .calendar-timed-list-btn > div {
        height: 100%;
    }
    .calendar-day-grid {
        display: grid;
        grid-template-columns: 5rem minmax(0, 1fr);
    }
    .calendar-day-events {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        height: 100%;
        min-height: 0;
    }
    [data-calendar-selection],
    [data-calendar-drag-preview] {
        position: absolute;
        left: 0;
        right: 0;
        z-index: 8;
        pointer-events: none;
        background: rgba(37, 99, 235, 0.28);
        border-top: 2px solid #2563eb;
        border-bottom: 2px solid #2563eb;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.65);
    }
    [data-calendar-drag-preview] {
        background: rgba(59, 130, 246, 0.38);
        z-index: 9;
    }
    [data-calendar-selection-label] {
        position: absolute;
        top: 4px;
        left: 6px;
        right: 6px;
        padding: 2px 6px;
        border-radius: 4px;
        background: #2563eb;
        color: #fff;
        font-size: 10px;
        font-weight: 600;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<div class="overflow-x-auto"
     data-calendar-slot-grid
     data-onboarding-target="calendar-schedule-grid"
     data-day-start-hour="{{ $timeAxis['start_hour'] ?? ListCalendarService::DAY_START_HOUR }}"
     data-day-end-hour="{{ $timeAxis['end_hour'] ?? ListCalendarService::DAY_END_HOUR }}"
     data-slot-minutes="{{ $slotMinutes }}">
    <div class="{{ $wrapperClass }}">
        @unless($singleDay)
            <p class="border-b border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-500" data-onboarding-target="calendar-schedule-help">
                Klik of sleep in het tijdschema om een <span class="font-medium">lijst aan een tijdslot</span> te koppelen.
                Klik op een bestaand blok om tijd of lijst aan te passen. Meerdere lijsten kunnen op hetzelfde tijdslot staan.
                Lijsten zonder vaste tijd staan op de rij &ldquo;Hele dag&rdquo;.
            </p>

            <div class="grid {{ $gridCols }} border-b border-slate-200">
                <div class="border-r border-slate-200 px-2 py-3"></div>
                @foreach($days as $day)
                    <div class="border-r border-slate-200 px-1 py-2 text-center last:border-r-0">
                        <div class="text-[11px] font-medium uppercase tracking-wide text-slate-500">{{ $day['label'] }}</div>
                        @php
                            $dayLink = $scope === 'company'
                                ? route('admin.lists.calendar', array_merge($weekQuery ?? [], ['view' => 'day', 'day' => $day['key']], $filterQuery ?? []))
                                : route('admin.lists.show', array_merge([$list], $weekQuery ?? [], ['view' => 'day', 'day' => $day['key']]));
                        @endphp
                        <a href="{{ $dayLink }}"
                           class="mx-auto mt-1 inline-flex h-9 w-9 items-center justify-center rounded-full text-lg {{ $day['is_today'] ? 'bg-blue-600 font-medium text-white' : 'text-slate-800 hover:bg-slate-100' }}">
                            {{ $day['day_number'] }}
                        </a>
                    </div>
                @endforeach
            </div>
        @endunless

        @if($singleDay)
            {{-- Single day: CSS grid keeps time labels and event blocks aligned --}}
            <div class="calendar-day-grid border-t border-slate-200"
                 style="grid-template-rows: @if($showAllDayRow) auto @endif repeat({{ $hourCount }}, {{ $hourRowHeight }});">
                @if($showAllDayRow)
                    <div class="border-b border-r border-slate-200 bg-slate-50/40 px-2 py-2 text-[10px] font-medium leading-tight text-slate-500">Hele dag</div>
                    @foreach($days as $day)
                        @php $createConfig = $dayCreateConfig($day); @endphp
                        <div class="min-h-[3rem] border-b border-slate-200 p-1 {{ ($day['is_today'] ?? false) ? 'bg-blue-50/20' : 'bg-slate-50/40' }}"
                             data-calendar-all-day-column
                             data-day-config='@json($createConfig)'>
                            <div class="space-y-1">
                                @if($scope === 'list')
                                    @if(!($day['is_list_active'] ?? true))
                                        <div class="rounded border border-dashed border-slate-200 px-2 py-2 text-center text-[10px] text-slate-400">Niet gepland</div>
                                    @else
                                        @foreach(($day['all_day_lists'] ?? collect()) as $listItem)
                                            @include('admin.lists.partials.list-calendar-event', [
                                                'list' => $listItem,
                                                'day' => $day,
                                                'variant' => 'mini',
                                                'schedulable' => $createConfig['canCreate'],
                                            ])
                                        @endforeach
                                    @endif
                                @else
                                    @foreach(($day['all_day_lists'] ?? collect()) as $listItem)
                                        @include('admin.lists.partials.list-calendar-event', [
                                            'list' => $listItem,
                                            'day' => $day,
                                            'taskCount' => $listItem->tasks_count ?? null,
                                            'variant' => 'mini',
                                            'schedulable' => $createConfig['canCreate'],
                                        ])
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif

                @foreach($timeHours as $index => $hourLabel)
                    <div class="relative border-t border-r border-slate-100 bg-white"
                         style="grid-column: 1; grid-row: {{ $timeGridStartRow + $index }};">
                        <span class="absolute right-1.5 top-1 text-[10px] leading-none text-slate-400">{{ $hourLabel }}</span>
                    </div>
                @endforeach

                @foreach($days as $day)
                    @php $createConfig = $dayCreateConfig($day); @endphp
                    <div class="relative min-h-0 border-slate-200 bg-white {{ ($day['is_today'] ?? false) ? 'bg-blue-50/10' : '' }} {{ $createConfig['canCreate'] ? 'cursor-crosshair' : '' }}"
                         style="grid-column: 2; grid-row: {{ $timeGridStartRow }} / {{ $timeGridEndRow }};"
                         data-calendar-time-column
                         data-calendar-day-column
                         data-day-config='@json($createConfig)'
                         title="{{ $createConfig['canCreate'] ? 'Klik of sleep om lijst te koppelen' : '' }}">
                        <div class="calendar-day-events" style="grid-template-rows: repeat({{ $hourCount }}, {{ $hourRowHeight }});">
                            @foreach($timeHours as $index => $hourLabel)
                                <div class="pointer-events-none relative min-h-0 border-t border-slate-100" style="grid-row: {{ $index + 1 }}; grid-column: 1;">
                                    <div class="absolute left-0 right-0 top-1/2 border-t border-dashed border-slate-100/80"></div>
                                </div>
                            @endforeach

                            @foreach(($day['timed_lists'] ?? []) as $entry)
                                @include('admin.lists.partials.list-calendar-timed-block', [
                                    'entry' => $entry,
                                    'day' => $day,
                                    'scope' => $scope,
                                    'gridHeight' => $gridHeight,
                                    'layout' => 'grid',
                                ])
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Week view --}}
            @if($showAllDayRow)
                <div class="grid {{ $gridCols }} border-b border-slate-200 bg-slate-50/40">
                    <div class="border-r border-slate-200 px-2 py-2 text-[10px] font-medium text-slate-500">Hele dag</div>
                    @foreach($days as $day)
                        @php $createConfig = $dayCreateConfig($day); @endphp
                        <div class="min-h-[4rem] border-r border-slate-200 p-1 last:border-r-0 {{ ($day['is_today'] ?? false) ? 'bg-blue-50/20' : 'bg-white' }}"
                             data-calendar-all-day-column
                             data-day-config='@json($createConfig)'>
                            <div class="space-y-1">
                                @if($scope === 'list')
                                    @if(!($day['is_list_active'] ?? true))
                                        <div class="rounded border border-dashed border-slate-200 px-2 py-2 text-center text-[10px] text-slate-400">Niet gepland</div>
                                    @else
                                        @foreach(($day['all_day_lists'] ?? collect()) as $listItem)
                                            @include('admin.lists.partials.list-calendar-event', [
                                                'list' => $listItem,
                                                'day' => $day,
                                                'variant' => 'mini',
                                                'schedulable' => $createConfig['canCreate'],
                                            ])
                                        @endforeach
                                        @if(($day['all_day_lists'] ?? collect())->isEmpty())
                                            <div class="px-1 py-3 text-center text-[10px] text-slate-400">Geen lijst</div>
                                        @endif
                                    @endif
                                @else
                                    @foreach(($day['all_day_lists'] ?? collect()) as $listItem)
                                        @include('admin.lists.partials.list-calendar-event', [
                                            'list' => $listItem,
                                            'day' => $day,
                                            'taskCount' => $listItem->tasks_count ?? null,
                                            'variant' => 'mini',
                                            'schedulable' => $createConfig['canCreate'],
                                        ])
                                    @endforeach
                                    @if(($day['all_day_lists'] ?? collect())->isEmpty())
                                        <div class="px-1 py-3 text-center text-[10px] text-slate-400">Geen lijst</div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="grid {{ $gridCols }}">
                <div class="flex flex-col border-r border-slate-200 bg-white" style="height: {{ $gridHeight }}rem;">
                    @foreach($timeHours as $hourLabel)
                        <div class="relative min-h-0 flex-1 border-t border-slate-100">
                            <span class="absolute right-1.5 top-1 text-[10px] leading-none text-slate-400">{{ $hourLabel }}</span>
                        </div>
                    @endforeach
                </div>
                @foreach($days as $day)
                    @php $createConfig = $dayCreateConfig($day); @endphp
                    <div class="relative border-r border-slate-200 bg-white last:border-r-0 {{ ($day['is_today'] ?? false) ? 'bg-blue-50/10' : '' }} {{ $createConfig['canCreate'] ? 'cursor-crosshair' : '' }}"
                         style="height: {{ $gridHeight }}rem;"
                         data-calendar-time-column
                         data-calendar-day-column
                         data-day-config='@json($createConfig)'
                         title="{{ $createConfig['canCreate'] ? 'Klik of sleep om lijst te koppelen' : '' }}">
                        <div class="pointer-events-none absolute inset-0 flex flex-col">
                            @foreach($timeHours as $hourLabel)
                                <div class="relative min-h-0 flex-1 border-t border-slate-100">
                                    <div class="absolute left-0 right-0 top-1/2 border-t border-dashed border-slate-100/80"></div>
                                </div>
                            @endforeach
                        </div>

                        @foreach(($day['timed_lists'] ?? []) as $entry)
                            @include('admin.lists.partials.list-calendar-timed-block', ['entry' => $entry, 'day' => $day, 'scope' => $scope, 'gridHeight' => $gridHeight])
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@include('admin.lists.partials.calendar-list-schedule')

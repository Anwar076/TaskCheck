@props(['list', 'day', 'taskCount' => null, 'variant' => 'default', 'timeLabel' => null, 'schedulable' => false])

@php
    use App\Services\Admin\ListCalendarService;

    $color = ListCalendarService::LIST_COLORS[$list->id % count(ListCalendarService::LIST_COLORS)];
    $weekStart = \Carbon\Carbon::parse($day['date'])->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d');
    $href = route('admin.lists.show', [$list, 'view' => 'day', 'day' => $day['key'], 'week' => $weekStart]);
    $sizeClass = $variant === 'mini' ? 'text-[10px]' : 'text-xs';
    $timeLabel = $timeLabel ?? null;
    $accentClass = str_replace('border-', 'bg-', $color['border']);
    $paddingClass = $variant === 'mini' ? 'px-2 py-0.5' : 'px-2 py-1';
@endphp

@if($schedulable)
    <button type="button"
            data-calendar-all-day-list
            data-list-id="{{ $list->id }}"
            data-list-title="{{ $list->title }}"
            data-weekday="{{ $day['key'] }}"
            data-store-url="{{ route('admin.lists.schedule-slot', $list) }}"
            class="group relative z-[2] flex w-full overflow-hidden rounded-md text-left font-medium leading-snug transition-shadow hover:shadow-sm {{ $sizeClass }}"
            title="{{ $list->title }} — klik om tijdslot in te stellen">
        <span class="w-[3px] shrink-0 {{ $accentClass }}"></span>
        <span class="min-w-0 flex-1 {{ $color['bg'] }} {{ $color['text'] }} {{ $color['hover'] }} {{ $paddingClass }}">
            <span class="block truncate">{{ $list->title }}</span>
            <span class="block truncate text-[9px] font-normal opacity-75">+ Tijd instellen</span>
        </span>
    </button>
@else
    <a href="{{ $href }}"
       data-calendar-list-event
       class="group relative z-[2] flex overflow-hidden rounded-md font-medium leading-snug transition-shadow hover:shadow-sm {{ $sizeClass }}"
       title="{{ $timeLabel ? $timeLabel.' — ' : '' }}{{ $list->title }}">
        <span class="w-[3px] shrink-0 {{ $accentClass }}"></span>
        <span class="min-w-0 flex-1 {{ $color['bg'] }} {{ $color['text'] }} {{ $color['hover'] }} {{ $paddingClass }}">
            @if($timeLabel)
                <span class="block truncate font-semibold opacity-90">{{ $timeLabel }}</span>
            @endif
            <span class="block truncate">{{ $list->title }}</span>
            @if($taskCount !== null && $variant !== 'mini')
                <span class="mt-0.5 block text-[10px] font-normal opacity-80">{{ $taskCount }} {{ $taskCount === 1 ? 'taak' : 'taken' }}</span>
            @endif
        </span>
    </a>
@endif

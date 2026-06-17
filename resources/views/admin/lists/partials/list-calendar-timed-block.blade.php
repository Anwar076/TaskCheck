@props(['entry', 'day', 'scope' => 'company', 'gridHeight' => 48, 'layout' => 'absolute'])

@php
    $list = $entry['list'];
    $color = $entry['color'];
    $weekStart = \Carbon\Carbon::parse($day['date'])->startOfWeek(\Carbon\Carbon::MONDAY)->format('Y-m-d');
    $manageUrl = route('admin.lists.show', [$list, 'view' => 'day', 'day' => $day['key'], 'week' => $weekStart]);
    $leftPercent = $entry['left_percent'] ?? 0;
    $widthPercent = $entry['width_percent'] ?? 100;
    $heightPercent = $entry['height_percent'] ?? 100;
    $topRem = round(($entry['top_percent'] / 100) * $gridHeight, 4);
    $heightRem = round(($heightPercent / 100) * $gridHeight, 4);
    $gridRowStart = $entry['grid_row_start'] ?? 1;
    $gridRowEnd = $entry['grid_row_end'] ?? 2;
    $rowSpan = max(1, $gridRowEnd - $gridRowStart);
    if ($layout === 'grid') {
        $compact = $rowSpan <= 1;
        $tiny = $heightPercent < 3;
    } else {
        $compact = $heightPercent < 5;
        $tiny = $heightPercent < 2.5;
    }
    $accentClass = str_replace('border-', 'bg-', $color['border']);
    $positionStyle = $layout === 'grid'
        ? 'left: calc('.$leftPercent.'% + 2px); width: calc('.$widthPercent.'% - 4px); height: 100%; top: 0;'
        : 'top: '.$topRem.'rem; height: '.$heightRem.'rem; left: calc('.$leftPercent.'% + 2px); width: calc('.$widthPercent.'% - 4px);';
    $positionClass = $layout === 'grid'
        ? 'absolute min-h-0'
        : 'absolute z-10';
@endphp

@if($layout === 'grid')
    <div class="pointer-events-none relative z-10 min-h-0 px-0.5"
         style="grid-row: {{ $gridRowStart }} / {{ $gridRowEnd }}; grid-column: 1;">
        <div class="pointer-events-auto relative h-full min-h-0">
@endif

<button type="button"
        data-calendar-timed-list
        data-slot-id="{{ $entry['slot_id'] }}"
        data-list-id="{{ $list->id }}"
        data-list-title="{{ $list->title }}"
        data-weekday="{{ $entry['weekday'] ?? $day['key'] }}"
        data-start-time="{{ $entry['start_time'] }}"
        data-end-time="{{ $entry['end_time'] ?? '' }}"
        data-update-url="{{ route('admin.lists.schedule-slot.update', [$list, $entry['slot_id']]) }}"
        data-delete-url="{{ route('admin.lists.schedule-slot.destroy', [$list, $entry['slot_id']]) }}"
        data-manage-url="{{ $manageUrl }}"
        class="calendar-timed-list-btn {{ $positionClass }} m-0 appearance-none border-0 bg-transparent p-0 text-left shadow-none"
        style="{{ $positionStyle }}"
        title="{{ $entry['time_label'] }} — {{ $list->title }}{{ !empty($entry['is_default']) ? ' (standaard)' : '' }} (klik om aan te passen)">
    <div class="flex h-full min-h-0 w-full overflow-hidden rounded shadow-sm transition-shadow hover:shadow {{ $color['hover'] }}">
        <div class="w-[3px] shrink-0 {{ $accentClass }}"></div>
        <div class="min-w-0 flex-1 overflow-hidden {{ $color['bg'] }} {{ $color['text'] }} px-2 {{ $tiny ? 'py-0.5' : 'py-1' }} text-[10px] font-medium leading-tight">
            @if($tiny)
                <span class="block truncate">{{ $list->title }}</span>
            @elseif($compact)
                <span class="block truncate font-semibold">{{ $entry['time_label'] }} · {{ $list->title }}@if(!empty($entry['is_default']))<span class="font-normal opacity-75"> · standaard</span>@endif</span>
            @else
                <span class="block truncate font-semibold">{{ $entry['time_label'] }}@if(!empty($entry['is_default']))<span class="font-normal opacity-75"> · standaard</span>@endif</span>
                <span class="mt-0.5 block truncate">{{ $list->title }}</span>
            @endif
        </div>
    </div>
</button>

@if($layout === 'grid')
        </div>
    </div>
@endif

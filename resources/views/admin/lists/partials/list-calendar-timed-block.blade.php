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
    $durationMinutes = max(
        0,
        (int) ($entry['end_minutes'] ?? (($entry['start_minutes'] ?? 0) + 30)) - (int) ($entry['start_minutes'] ?? 0)
    );
    $compact = $durationMinutes > 0 && $durationMinutes < 60;
    $tiny = $durationMinutes > 0 && $durationMinutes <= 30;
    $roomy = $durationMinutes > 60;
    $contentPaddingClass = $tiny ? 'py-0.5' : ($compact ? 'py-1' : 'py-1.5');
    $contentTextClass = $tiny ? 'text-[10px]' : 'text-[11px]';
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
        data-date="{{ $entry['date'] ?? $day['date'] }}"
        data-start-time="{{ $entry['start_time'] }}"
        data-end-time="{{ $entry['end_time'] ?? '' }}"
        data-is-default="{{ !empty($entry['is_default']) ? '1' : '0' }}"
        data-update-url="{{ route('admin.lists.schedule-slot.update', [$list, $entry['slot_id']]) }}"
        data-delete-url="{{ route('admin.lists.schedule-slot.destroy', [$list, $entry['slot_id']]) }}"
        data-manage-url="{{ $manageUrl }}"
        class="calendar-timed-list-btn group {{ $positionClass }} m-0 appearance-none border-0 bg-transparent p-0 text-left shadow-none {{ empty($entry['is_default']) ? 'cursor-grab active:cursor-grabbing' : '' }}"
        style="{{ $positionStyle }}"
        title="{{ $entry['time_label'] }} — {{ $list->title }}{{ !empty($entry['is_default']) ? ' (standaard)' : '' }}{{ empty($entry['is_default']) ? ' (sleep om te verplaatsen)' : ' (klik om aan te passen)' }}">
    <div class="relative flex h-full min-h-0 w-full overflow-hidden rounded shadow-sm transition-shadow hover:shadow {{ $color['hover'] }}">
        <div class="w-[3px] shrink-0 {{ $accentClass }}"></div>
        <div class="min-w-0 flex-1 overflow-hidden {{ $color['bg'] }} {{ $color['text'] }} px-2 {{ $contentPaddingClass }} {{ $contentTextClass }} font-medium leading-tight">
            @if($tiny)
                <span class="block truncate">{{ $list->title }}</span>
            @elseif($compact)
                <span class="block truncate font-semibold">{{ $entry['time_label'] }}@if(!empty($entry['is_default']))<span class="font-normal opacity-75"> · standaard</span>@endif</span>
                <span class="mt-0.5 block truncate font-medium">{{ $list->title }}</span>
            @else
                <span class="block truncate font-semibold">{{ $entry['time_label'] }}@if(!empty($entry['is_default']))<span class="font-normal opacity-75"> · standaard</span>@endif</span>
                <span class="mt-0.5 block {{ $roomy ? 'overflow-hidden whitespace-normal break-words' : 'truncate' }}" @if($roomy) style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;" @endif>{{ $list->title }}</span>
            @endif
        </div>
        @if(empty($entry['is_default']))
            <span data-calendar-resize-handle="start"
                  class="absolute left-1 right-1 top-0 h-2 cursor-ns-resize rounded-t opacity-0 transition-opacity group-hover:opacity-100"
                  aria-hidden="true">
                <span class="mx-auto mt-1 block h-0.5 w-6 rounded-full bg-current opacity-30"></span>
            </span>
            <span data-calendar-resize-handle="end"
                  class="absolute bottom-0 left-1 right-1 h-2 cursor-ns-resize rounded-b opacity-0 transition-opacity group-hover:opacity-100"
                  aria-hidden="true">
                <span class="mx-auto mt-0.5 block h-0.5 w-6 rounded-full bg-current opacity-30"></span>
            </span>
        @endif
    </div>
</button>

@if($layout === 'grid')
        </div>
    </div>
@endif

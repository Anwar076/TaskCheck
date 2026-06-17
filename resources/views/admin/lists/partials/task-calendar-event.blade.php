@props(['task', 'list', 'inactive' => false, 'variant' => 'default', 'subtitle' => null])

@php
    use App\Services\Admin\ListCalendarService;

    $isGeneral = empty($task->weekday);
    $colorClass = $inactive
        ? 'border-slate-300 bg-slate-100 text-slate-500'
        : ($isGeneral
            ? 'border-blue-600 bg-blue-50 text-blue-900 hover:bg-blue-100'
            : 'border-violet-600 bg-violet-50 text-violet-900 hover:bg-violet-100');
    $timeLabel = app(ListCalendarService::class)->formatTaskTimeLabel($task);
@endphp

<a href="{{ route('admin.tasks.edit', $task) }}"
   data-calendar-task
   class="group relative z-[2] block rounded-md border-l-[3px] px-2 py-1 text-xs font-medium leading-snug transition-shadow {{ $colorClass }} hover:shadow-sm"
   title="{{ $timeLabel !== 'Hele dag' ? $timeLabel.' — ' : '' }}{{ $task->title }}">
    @if($timeLabel !== 'Hele dag' && $variant !== 'mini')
        <span class="block truncate text-[10px] font-semibold opacity-90">{{ $timeLabel }}</span>
    @endif
    @if($subtitle)
        <span class="block truncate text-[10px] opacity-75">{{ $subtitle }}</span>
    @endif
    <span class="block truncate">{{ $task->title }}</span>
    @if($task->is_required && $variant !== 'mini')
        <span class="mt-0.5 block text-[10px] font-normal opacity-80">Verplicht</span>
    @endif
</a>

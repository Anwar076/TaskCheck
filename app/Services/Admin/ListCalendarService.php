<?php

namespace App\Services\Admin;

use App\Models\Checklist\Task;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\Company;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ListCalendarService
{
    public const DAY_START_HOUR = 6;

    public const DAY_END_HOUR = 21;

    public const DEFAULT_TASK_DURATION_MINUTES = 30;
    public const LIST_COLORS = [
        ['border' => 'border-blue-600', 'bg' => 'bg-blue-50', 'text' => 'text-blue-900', 'hover' => 'hover:bg-blue-100'],
        ['border' => 'border-violet-600', 'bg' => 'bg-violet-50', 'text' => 'text-violet-900', 'hover' => 'hover:bg-violet-100'],
        ['border' => 'border-emerald-600', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-900', 'hover' => 'hover:bg-emerald-100'],
        ['border' => 'border-amber-600', 'bg' => 'bg-amber-50', 'text' => 'text-amber-900', 'hover' => 'hover:bg-amber-100'],
        ['border' => 'border-rose-600', 'bg' => 'bg-rose-50', 'text' => 'text-rose-900', 'hover' => 'hover:bg-rose-100'],
        ['border' => 'border-cyan-600', 'bg' => 'bg-cyan-50', 'text' => 'text-cyan-900', 'hover' => 'hover:bg-cyan-100'],
    ];
    public const WEEKDAY_LABELS = [
        'monday' => 'Ma',
        'tuesday' => 'Di',
        'wednesday' => 'Wo',
        'thursday' => 'Do',
        'friday' => 'Vr',
        'saturday' => 'Za',
        'sunday' => 'Zo',
    ];

    public const WEEKDAY_LABELS_FULL = [
        'monday' => 'Maandag',
        'tuesday' => 'Dinsdag',
        'wednesday' => 'Woensdag',
        'thursday' => 'Donderdag',
        'friday' => 'Vrijdag',
        'saturday' => 'Zaterdag',
        'sunday' => 'Zondag',
    ];

    public function buildWeek(TaskList $list, Carbon $weekStart, ?array $visibleDayKeys = null): array
    {
        $weekStart = $weekStart->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);
        $axis = $this->timeAxisForCompany($list->company, $visibleDayKeys ?? array_keys(self::WEEKDAY_LABELS));

        $generalTasks = $list->tasks->whereNull('weekday')->values();
        $tasksByDay = $list->tasks->whereNotNull('weekday')->groupBy('weekday');

        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $dayKey = strtolower($date->format('l'));
            $isListActive = $this->isListActiveOnDate($list, $date);
            $daySpecific = ($tasksByDay->get($dayKey) ?? collect())->values();
            $visibleTasks = $isListActive
                ? $generalTasks->concat($daySpecific)->sortBy('order_index')->values()
                : collect();
            $listSchedule = $isListActive
                ? $this->aggregateListDaySchedule(collect([$list]), $dayKey, $axis)
                : ['all_day_lists' => collect(), 'timed_lists' => collect()];

            $days[] = [
                'key' => $dayKey,
                'label' => self::WEEKDAY_LABELS[$dayKey] ?? ucfirst($dayKey),
                'label_full' => self::WEEKDAY_LABELS_FULL[$dayKey] ?? ucfirst($dayKey),
                'date' => $date->format('Y-m-d'),
                'day_number' => $date->day,
                'date_label' => $date->locale('nl')->translatedFormat('d M'),
                'is_today' => $date->isToday(),
                'is_weekend' => $date->isWeekend(),
                'is_list_active' => $isListActive,
                'general_count' => $isListActive ? $generalTasks->count() : 0,
                'day_specific_count' => $daySpecific->count(),
                'tasks' => $visibleTasks,
                'all_day_lists' => $listSchedule['all_day_lists'],
                'timed_lists' => $listSchedule['timed_lists'],
                'working_hours' => $this->workingHoursForDay($list->company, $dayKey),
                'non_working_ranges' => $this->nonWorkingRangesForDay($list->company, $dayKey, $axis),
            ];
        }

        return [
            'mode' => 'week',
            'week_start' => $weekStart->format('Y-m-d'),
            'week_end' => $weekEnd->format('Y-m-d'),
            'week_label' => $weekStart->locale('nl')->translatedFormat('d M')
                . ' – '
                . $weekEnd->locale('nl')->translatedFormat('d M Y'),
            'title' => $weekStart->locale('nl')->translatedFormat('F Y'),
            'prev' => $weekStart->copy()->subWeek()->format('Y-m-d'),
            'next' => $weekStart->copy()->addWeek()->format('Y-m-d'),
            'today' => now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'),
            'days' => $days,
            'general_tasks' => $generalTasks,
            'schedule_summary' => $this->scheduleSummary($list),
            'time_hours' => $this->timeAxisHours($axis),
            'time_axis' => $axis,
        ];
    }

    public function buildMonth(TaskList $list, Carbon $month): array
    {
        $month = $month->copy()->startOfMonth();
        $gridStart = $month->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $generalTasks = $list->tasks->whereNull('weekday')->values();
        $tasksByDay = $list->tasks->whereNotNull('weekday')->groupBy('weekday');

        $weeks = [];
        $cursor = $gridStart->copy();

        while ($cursor <= $gridEnd) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $date = $cursor->copy();
                $dayKey = strtolower($date->format('l'));
                $isListActive = $this->isListActiveOnDate($list, $date);
                $daySpecific = ($tasksByDay->get($dayKey) ?? collect())->values();
                $visibleTasks = $isListActive
                    ? $generalTasks->concat($daySpecific)->sortBy('order_index')->values()
                    : collect();

                $week[] = [
                    'key' => $dayKey,
                    'label' => self::WEEKDAY_LABELS[$dayKey] ?? ucfirst($dayKey),
                    'date' => $date->format('Y-m-d'),
                    'day_number' => $date->day,
                    'is_today' => $date->isToday(),
                    'is_current_month' => $date->month === $month->month,
                    'is_list_active' => $isListActive,
                    'tasks' => $visibleTasks,
                    'task_count' => $visibleTasks->count(),
                ];

                $cursor->addDay();
            }
            $weeks[] = $week;
        }

        return [
            'mode' => 'month',
            'month' => $month->format('Y-m'),
            'month_start' => $month->format('Y-m-01'),
            'title' => $month->locale('nl')->translatedFormat('F Y'),
            'prev' => $month->copy()->subMonth()->format('Y-m-01'),
            'next' => $month->copy()->addMonth()->format('Y-m-01'),
            'today' => now()->format('Y-m-d'),
            'weeks' => $weeks,
            'weekday_headers' => array_values(self::WEEKDAY_LABELS),
            'general_tasks' => $generalTasks,
            'schedule_summary' => $this->scheduleSummary($list),
        ];
    }

    public function isListActiveOnDate(TaskList $list, Carbon $date): bool
    {
        $dayKey = strtolower($date->format('l'));
        $config = is_array($list->schedule_config) ? $list->schedule_config : [];

        return match ($list->schedule_type) {
            'once' => $list->due_date ? $date->lte($list->due_date) : true,
            'daily' => true,
            'weekly' => in_array($dayKey, $list->getShowOnDays(), true),
            'monthly' => $date->day === (int) ($config['day_of_month'] ?? 1),
            'custom' => $this->isCustomActiveOnDate($config, $date, $list),
            default => true,
        };
    }

    private function isCustomActiveOnDate(array $config, Carbon $date, TaskList $list): bool
    {
        $dayKey = strtolower($date->format('l'));
        $type = $config['type'] ?? null;

        return match ($type) {
            'specific_days' => in_array($dayKey, $config['days'] ?? [], true),
            'interval' => isset($config['interval_days'], $config['start_date'])
                && Carbon::parse($config['start_date'])->diffInDays($date) % (int) $config['interval_days'] === 0,
            'date_range' => isset($config['start_date'], $config['end_date'])
                && $date->between(Carbon::parse($config['start_date']), Carbon::parse($config['end_date'])),
            default => in_array($dayKey, $list->getShowOnDays(), true),
        };
    }

    private function scheduleSummary(TaskList $list): string
    {
        $labels = [
            'once' => 'Eenmalig',
            'daily' => 'Dagelijks',
            'weekly' => 'Wekelijks',
            'monthly' => 'Maandelijks',
            'custom' => 'Aangepast',
        ];

        $base = $labels[$list->schedule_type] ?? $list->schedule_type;

        if (in_array($list->schedule_type, ['weekly', 'custom'], true)) {
            $days = $list->getShowOnDays();
            if ($days !== []) {
                $names = array_map(fn ($d) => self::WEEKDAY_LABELS[$d] ?? $d, $days);

                return $base . ': ' . implode(', ', $names);
            }
        }

        if ($list->schedule_type === 'monthly') {
            $dom = (int) (is_array($list->schedule_config) ? ($list->schedule_config['day_of_month'] ?? 1) : 1);

            return $base . ': elke ' . $dom . 'e van de maand';
        }

        $default = $this->getDefaultTimeSlot($list);
        if ($default !== null) {
            return $base . ' · ' . $this->formatSlotTimeLabel($default);
        }

        return $base;
    }

    public function listColor(int $listId): array
    {
        return self::LIST_COLORS[$listId % count(self::LIST_COLORS)];
    }

    /**
     * @param  Collection<int, TaskList>  $lists
     * @return Collection<int, TaskList>
     */
    public function activeListsForDate(Collection $lists, Carbon $date): Collection
    {
        return $lists
            ->filter(fn (TaskList $list) => $list->is_active && $this->isListActiveOnDate($list, $date))
            ->values();
    }

    /**
     * @param  Collection<int, TaskList>  $lists
     */
    public function buildCompanyWeek(Collection $lists, Carbon $weekStart, ?Company $company = null, ?array $visibleDayKeys = null): array
    {
        $weekStart = $weekStart->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);
        $axis = $this->timeAxisForCompany($company, $visibleDayKeys ?? array_keys(self::WEEKDAY_LABELS));

        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i);
            $dayKey = strtolower($date->format('l'));
            $dayLists = $this->activeListsForDate($lists, $date);
            $daySchedule = $this->aggregateListDaySchedule($dayLists, $dayKey, $axis);

            $days[] = [
                'key' => $dayKey,
                'label' => self::WEEKDAY_LABELS[$dayKey] ?? ucfirst($dayKey),
                'label_full' => self::WEEKDAY_LABELS_FULL[$dayKey] ?? ucfirst($dayKey),
                'date' => $date->format('Y-m-d'),
                'day_number' => $date->day,
                'date_label' => $date->locale('nl')->translatedFormat('d M Y'),
                'is_today' => $date->isToday(),
                'lists' => $dayLists,
                'list_count' => $dayLists->count(),
                'all_day_lists' => $daySchedule['all_day_lists'],
                'timed_lists' => $daySchedule['timed_lists'],
                'working_hours' => $this->workingHoursForDay($company, $dayKey),
                'non_working_ranges' => $this->nonWorkingRangesForDay($company, $dayKey, $axis),
            ];
        }

        return [
            'mode' => 'week',
            'week_start' => $weekStart->format('Y-m-d'),
            'week_end' => $weekEnd->format('Y-m-d'),
            'week_label' => $weekStart->locale('nl')->translatedFormat('d M')
                . ' – '
                . $weekEnd->locale('nl')->translatedFormat('d M Y'),
            'title' => $weekStart->locale('nl')->translatedFormat('F Y'),
            'prev' => $weekStart->copy()->subWeek()->format('Y-m-d'),
            'next' => $weekStart->copy()->addWeek()->format('Y-m-d'),
            'today' => now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'),
            'days' => $days,
            'total_lists' => $lists->count(),
            'time_hours' => $this->timeAxisHours($axis),
            'time_axis' => $axis,
        ];
    }

    /**
     * @param  Collection<int, Task>  $tasks
     * @return array{all_day_tasks: Collection, timed_tasks: Collection<int, array<string, mixed>>}
     */
    public function partitionTasksForDay(Collection $tasks): array
    {
        $allDayTasks = $tasks->filter(fn (Task $task) => ! $this->taskHasTimeSlot($task))->values();
        $timedTasks = $tasks
            ->filter(fn (Task $task) => $this->taskHasTimeSlot($task))
            ->sortBy(fn (Task $task) => $this->normalizeTime($task->start_time))
            ->values()
            ->map(fn (Task $task) => $this->mapTimedTask($task))
            ->values();

        return [
            'all_day_tasks' => $allDayTasks,
            'timed_tasks' => $timedTasks,
        ];
    }

    /**
     * @param  Collection<int, TaskList>  $lists
     * @return array{all_day_lists: Collection<int, TaskList>, timed_lists: Collection<int, array<string, mixed>>}
     */
    public function aggregateListDaySchedule(Collection $lists, string $dayKey, ?array $axis = null): array
    {
        $axis ??= $this->defaultTimeAxis();
        $allDayLists = collect();
        $timedLists = collect();

        foreach ($lists as $list) {
            $slots = $this->getTimeSlotsForWeekday($list, $dayKey);

            if ($slots !== []) {
                foreach ($slots as $slot) {
                    $timedLists->push(array_merge(
                        $this->mapTimedListSlot($list, $slot, $axis),
                        ['list' => $list, 'color' => $this->listColor($list->id)]
                    ));
                }

                continue;
            }

            $allDayLists->push($list);
        }

        return [
            'all_day_lists' => $allDayLists->values(),
            'timed_lists' => $this->layoutTimedListColumns($timedLists)->values(),
        ];
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $entries
     * @return Collection<int, array<string, mixed>>
     */
    public function layoutTimedListColumns(Collection $entries): Collection
    {
        if ($entries->isEmpty()) {
            return $entries;
        }

        $items = $entries->values()->all();
        $groups = [];

        foreach ($items as $item) {
            $placed = false;

            foreach ($groups as &$group) {
                foreach ($group as $existing) {
                    if ($this->timeRangesOverlap($item, $existing)) {
                        $group[] = $item;
                        $placed = true;
                        break 2;
                    }
                }
            }
            unset($group);

            if (! $placed) {
                $groups[] = [$item];
            }
        }

        $groups = $this->mergeOverlappingGroups($groups);
        $laidOut = collect();

        foreach ($groups as $group) {
            $count = count($group);
            foreach (array_values($group) as $index => $entry) {
                $entry['column_index'] = $index;
                $entry['column_count'] = $count;
                $entry['width_percent'] = 100 / $count;
                $entry['left_percent'] = ($index / $count) * 100;
                $laidOut->push($entry);
            }
        }

        return $laidOut->sortBy('start_minutes')->values();
    }

    /**
     * @param  array<int, array<int, array<string, mixed>>>  $groups
     * @return array<int, array<int, array<string, mixed>>>
     */
    private function mergeOverlappingGroups(array $groups): array
    {
        $merged = true;

        while ($merged) {
            $merged = false;

            for ($i = 0; $i < count($groups); $i++) {
                for ($j = $i + 1; $j < count($groups); $j++) {
                    if ($this->groupsOverlap($groups[$i], $groups[$j])) {
                        $groups[$i] = array_merge($groups[$i], $groups[$j]);
                        array_splice($groups, $j, 1);
                        $merged = true;
                        break 2;
                    }
                }
            }
        }

        return $groups;
    }

    /**
     * @param  array<int, array<string, mixed>>  $groupA
     * @param  array<int, array<string, mixed>>  $groupB
     */
    private function groupsOverlap(array $groupA, array $groupB): bool
    {
        foreach ($groupA as $a) {
            foreach ($groupB as $b) {
                if ($this->timeRangesOverlap($a, $b)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $a
     * @param  array<string, mixed>  $b
     */
    private function timeRangesOverlap(array $a, array $b): bool
    {
        $aStart = (int) ($a['start_minutes'] ?? 0);
        $aEnd = (int) ($a['end_minutes'] ?? ($aStart + self::DEFAULT_TASK_DURATION_MINUTES));
        $bStart = (int) ($b['start_minutes'] ?? 0);
        $bEnd = (int) ($b['end_minutes'] ?? ($bStart + self::DEFAULT_TASK_DURATION_MINUTES));

        return $aStart < $bEnd && $bStart < $aEnd;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getTimeSlots(TaskList $list): array
    {
        $config = is_array($list->schedule_config) ? $list->schedule_config : [];
        $slots = is_array($config['time_slots'] ?? null) ? $config['time_slots'] : [];

        [$normalized, $changed] = $this->normalizeTimeSlotIds($slots);

        if ($changed) {
            $config['time_slots'] = $normalized;
            $list->update(['schedule_config' => $config]);
        }

        return $normalized;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getDefaultTimeSlot(TaskList $list): ?array
    {
        $config = is_array($list->schedule_config) ? $list->schedule_config : [];
        $default = $config['default_time_slot'] ?? null;

        if (! is_array($default) || empty($default['start_time'])) {
            return null;
        }

        return [
            'start_time' => $this->formatClock($default['start_time']),
            'end_time' => ! empty($default['end_time']) ? $this->formatClock($default['end_time']) : null,
        ];
    }

    public function setDefaultTimeSlot(TaskList $list, string $startTime, ?string $endTime = null): void
    {
        $config = is_array($list->schedule_config) ? $list->schedule_config : [];
        $config['default_time_slot'] = [
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];
        $list->update(['schedule_config' => $config]);
    }

    public function removeDefaultTimeSlot(TaskList $list): void
    {
        $config = is_array($list->schedule_config) ? $list->schedule_config : [];
        unset($config['default_time_slot']);
        $list->update(['schedule_config' => $config]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getTimeSlotsForWeekday(TaskList $list, string $dayKey): array
    {
        $explicit = array_values(array_filter(
            $this->getTimeSlots($list),
            fn (array $slot) => ($slot['weekday'] ?? '') === $dayKey && filled($slot['start_time'] ?? null)
        ));

        if ($explicit !== []) {
            return $explicit;
        }

        $default = $this->getDefaultTimeSlot($list);
        if ($default === null) {
            return [];
        }

        return [[
            'id' => 'default',
            'weekday' => $dayKey,
            'start_time' => $default['start_time'],
            'end_time' => $default['end_time'],
            'is_default' => true,
        ]];
    }

    public function assignListTimeSlot(TaskList $list, string $weekday, string $startTime, ?string $endTime = null): void
    {
        $this->ensureListScheduledOnWeekday($list, $weekday);
        $list->refresh();

        $config = is_array($list->schedule_config) ? $list->schedule_config : [];
        $slots = $this->getTimeSlots($list);

        $slots[] = [
            'id' => (string) Str::uuid(),
            'weekday' => $weekday,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ];

        $config['time_slots'] = $slots;
        $list->update(['schedule_config' => $config]);
    }

    public function updateListTimeSlot(TaskList $list, string $slotId, string $weekday, string $startTime, ?string $endTime = null): void
    {
        $config = is_array($list->schedule_config) ? $list->schedule_config : [];
        $slots = $this->getTimeSlots($list);
        $updated = false;

        foreach ($slots as &$slot) {
            if (($slot['id'] ?? null) !== $slotId) {
                continue;
            }

            $slot['weekday'] = $weekday;
            $slot['start_time'] = $startTime;
            $slot['end_time'] = $endTime;
            $updated = true;
            break;
        }
        unset($slot);

        if (! $updated) {
            abort(404, 'Tijdslot niet gevonden.');
        }

        $this->ensureListScheduledOnWeekday($list, $weekday);
        $config['time_slots'] = $slots;
        $list->update(['schedule_config' => $config]);
    }

    public function removeListTimeSlot(TaskList $list, string $slotId): void
    {
        $config = is_array($list->schedule_config) ? $list->schedule_config : [];
        $slots = $this->getTimeSlots($list);
        $remaining = array_values(array_filter(
            $slots,
            fn (array $slot) => ($slot['id'] ?? null) !== $slotId
        ));

        if (count($remaining) === count($slots)) {
            abort(404, 'Tijdslot niet gevonden.');
        }

        $config['time_slots'] = $remaining;
        $list->update(['schedule_config' => $config]);
    }

    public function moveListTimeSlot(
        TaskList $source,
        string $slotId,
        TaskList $target,
        string $weekday,
        string $startTime,
        ?string $endTime = null
    ): void {
        $this->removeListTimeSlot($source, $slotId);
        $this->assignListTimeSlot($target, $weekday, $startTime, $endTime);
    }

    /**
     * @param  array<int, array<string, mixed>>  $slots
     * @return array{0: array<int, array<string, mixed>>, 1: bool}
     */
    private function normalizeTimeSlotIds(array $slots): array
    {
        $changed = false;

        foreach ($slots as &$slot) {
            if (empty($slot['id'])) {
                $slot['id'] = (string) Str::uuid();
                $changed = true;
            }
        }
        unset($slot);

        return [$slots, $changed];
    }

    public function findListTimeSlot(TaskList $list, string $slotId): ?array
    {
        if ($slotId === 'default') {
            $default = $this->getDefaultTimeSlot($list);

            return $default ? array_merge($default, ['id' => 'default']) : null;
        }

        foreach ($this->getTimeSlots($list) as $slot) {
            if (($slot['id'] ?? null) === $slotId) {
                return $slot;
            }
        }

        return null;
    }

    public function ensureListScheduledOnWeekday(TaskList $list, string $weekday): void
    {
        if ($list->schedule_type === 'daily') {
            return;
        }

        $config = is_array($list->schedule_config) ? $list->schedule_config : [];

        if ($list->schedule_type === 'weekly') {
            $days = $config['show_on_days'] ?? [];
            if (! in_array($weekday, $days, true)) {
                $config['show_on_days'] = array_values(array_unique([...$days, $weekday]));
                $list->update(['schedule_config' => $config]);
            }

            return;
        }

        if ($list->schedule_type === 'custom') {
            $type = $config['type'] ?? null;

            if ($type === 'specific_days') {
                $days = $config['days'] ?? [];
                if (! in_array($weekday, $days, true)) {
                    $config['days'] = array_values(array_unique([...$days, $weekday]));
                    $list->update(['schedule_config' => $config]);
                }
            } else {
                $days = $config['show_on_days'] ?? [];
                if (! in_array($weekday, $days, true)) {
                    $config['show_on_days'] = array_values(array_unique([...$days, $weekday]));
                    $list->update(['schedule_config' => $config]);
                }
            }

            return;
        }

        $list->update([
            'schedule_type' => 'weekly',
            'schedule_config' => array_merge($config, [
                'show_on_days' => array_values(array_unique([$weekday])),
            ]),
        ]);
    }

    public function formatSlotTimeLabel(array $slot): string
    {
        $start = $slot['start_time'] ?? null;
        $end = $slot['end_time'] ?? null;

        if ($start && $end) {
            return $this->formatClock($start).' – '.$this->formatClock($end);
        }

        if ($start) {
            return $this->formatClock($start);
        }

        return 'Hele dag';
    }

    public function primaryTimeLabelForListOnDay(TaskList $list, string $dayKey): ?string
    {
        $slots = $this->getTimeSlotsForWeekday($list, $dayKey);

        if ($slots === []) {
            return null;
        }

        return $this->formatSlotTimeLabel($slots[0]);
    }

    /**
     * @return array<string, mixed>
     */
    private function mapTimedListSlot(TaskList $list, array $slot, ?array $axis = null): array
    {
        $axis ??= $this->defaultTimeAxis();
        $startMinutes = $this->timeToMinutes($this->normalizeTime($slot['start_time']));
        $endMinutes = ! empty($slot['end_time'])
            ? $this->timeToMinutes($this->normalizeTime($slot['end_time']))
            : $startMinutes + self::DEFAULT_TASK_DURATION_MINUTES;

        if ($endMinutes <= $startMinutes) {
            $endMinutes = $startMinutes + self::DEFAULT_TASK_DURATION_MINUTES;
        }

        $gridStart = (int) $axis['start_minutes'];
        $gridEnd = (int) $axis['end_minutes'];
        $totalMinutes = max(1, $gridEnd - $gridStart);
        $hourCount = max(1, (int) $axis['end_hour'] - (int) $axis['start_hour']);
        $gridRowStart = max(1, min($hourCount, (int) floor(($startMinutes - $gridStart) / 60) + 1));
        $gridRowEnd = max($gridRowStart + 1, min($hourCount + 1, (int) floor(($endMinutes - $gridStart) / 60) + 1));

        return [
            'slot_id' => $slot['id'] ?? null,
            'weekday' => $slot['weekday'] ?? null,
            'is_default' => (bool) ($slot['is_default'] ?? false),
            'list' => $list,
            'start_time' => $this->formatClock($slot['start_time']),
            'end_time' => ! empty($slot['end_time']) ? $this->formatClock($slot['end_time']) : null,
            'time_label' => $this->formatSlotTimeLabel($slot),
            'start_minutes' => $startMinutes,
            'end_minutes' => $endMinutes,
            'top_percent' => max(0, (($startMinutes - $gridStart) / $totalMinutes) * 100),
            'height_percent' => max(0.5, (($endMinutes - $startMinutes) / $totalMinutes) * 100),
            'grid_row_start' => $gridRowStart,
            'grid_row_end' => $gridRowEnd,
        ];
    }

    /**
     * @param  Collection<int, TaskList>  $lists
     * @return array{all_day_tasks: Collection, timed_tasks: Collection<int, array<string, mixed>>}
     * @deprecated Use aggregateListDaySchedule for agenda views.
     */
    public function aggregateCompanyDaySchedule(Collection $lists, string $dayKey): array
    {
        $schedule = $this->aggregateListDaySchedule($lists, $dayKey);

        return [
            'all_day_tasks' => collect(),
            'timed_tasks' => $schedule['timed_lists'],
        ];
    }

    /**
     * @return Collection<int, Task>
     */
    public function tasksForListOnDay(TaskList $list, string $dayKey): Collection
    {
        $generalTasks = $list->tasks->whereNull('weekday')->values();
        $daySpecific = $list->tasks->where('weekday', $dayKey)->values();

        return $generalTasks->concat($daySpecific)->sortBy('order_index')->values();
    }

    public function taskHasTimeSlot(Task $task): bool
    {
        return filled($task->start_time);
    }

    public function formatTaskTimeLabel(Task $task): string
    {
        if ($task->start_time && $task->end_time) {
            return $this->formatClock($task->start_time).' – '.$this->formatClock($task->end_time);
        }

        if ($task->start_time) {
            return $this->formatClock($task->start_time);
        }

        return 'Hele dag';
    }

    /**
     * @return array<int, string>
     */
    public function timeAxisHours(?array $axis = null): array
    {
        $axis ??= $this->defaultTimeAxis();
        $hours = [];
        for ($hour = (int) $axis['start_hour']; $hour < (int) $axis['end_hour']; $hour++) {
            $hours[] = sprintf('%02d:00', $hour);
        }

        return $hours;
    }

    public function timeAxisForCompany(?Company $company, array $dayKeys): array
    {
        if ($company?->calendar_time_mode === Company::CALENDAR_TIME_MODE_24_HOURS) {
            return [
                'start_hour' => 0,
                'end_hour' => 24,
                'start_minutes' => 0,
                'end_minutes' => 24 * 60,
            ];
        }

        if ($company) {
            return $company->workingHoursForDays($dayKeys);
        }

        return $this->defaultTimeAxis();
    }

    private function workingHoursForDay(?Company $company, string $dayKey): array
    {
        return $company?->normalizedWorkingHours()[$dayKey]
            ?? Company::defaultWorkingHours()[$dayKey]
            ?? ['enabled' => true, 'start' => '06:00', 'end' => '21:00'];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function nonWorkingRangesForDay(?Company $company, string $dayKey, array $axis): array
    {
        $hours = $this->workingHoursForDay($company, $dayKey);
        $gridStart = (int) ($axis['start_minutes'] ?? (self::DAY_START_HOUR * 60));
        $gridEnd = (int) ($axis['end_minutes'] ?? (self::DAY_END_HOUR * 60));
        $totalMinutes = max(1, $gridEnd - $gridStart);

        if (! (bool) ($hours['enabled'] ?? true)) {
            return [$this->nonWorkingRange($gridStart, $gridEnd, $gridStart, $totalMinutes)];
        }

        $workStart = $this->timeToMinutes($this->normalizeTime($hours['start'] ?? '06:00'));
        $workEnd = $this->timeToMinutes($this->normalizeTime($hours['end'] ?? '21:00'));

        if ($workEnd <= $workStart) {
            return [];
        }

        $ranges = [];
        if ($workStart > $gridStart) {
            $ranges[] = $this->nonWorkingRange($gridStart, min($workStart, $gridEnd), $gridStart, $totalMinutes);
        }

        if ($workEnd < $gridEnd) {
            $ranges[] = $this->nonWorkingRange(max($workEnd, $gridStart), $gridEnd, $gridStart, $totalMinutes);
        }

        return array_values(array_filter($ranges, fn (array $range) => $range['height_percent'] > 0));
    }

    /**
     * @return array<string, mixed>
     */
    private function nonWorkingRange(int $startMinutes, int $endMinutes, int $gridStart, int $totalMinutes): array
    {
        $startMinutes = max($gridStart, $startMinutes);
        $endMinutes = max($startMinutes, $endMinutes);

        return [
            'start_minutes' => $startMinutes,
            'end_minutes' => $endMinutes,
            'start_time' => sprintf('%02d:%02d', intdiv($startMinutes, 60), $startMinutes % 60),
            'end_time' => sprintf('%02d:%02d', intdiv($endMinutes, 60), $endMinutes % 60),
            'top_percent' => max(0, (($startMinutes - $gridStart) / $totalMinutes) * 100),
            'height_percent' => max(0, (($endMinutes - $startMinutes) / $totalMinutes) * 100),
        ];
    }

    private function defaultTimeAxis(): array
    {
        return [
            'start_hour' => self::DAY_START_HOUR,
            'end_hour' => self::DAY_END_HOUR,
            'start_minutes' => self::DAY_START_HOUR * 60,
            'end_minutes' => self::DAY_END_HOUR * 60,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mapTimedTask(Task $task): array
    {
        $startMinutes = $this->timeToMinutes($this->normalizeTime($task->start_time));
        $endMinutes = $task->end_time
            ? $this->timeToMinutes($this->normalizeTime($task->end_time))
            : $startMinutes + self::DEFAULT_TASK_DURATION_MINUTES;

        if ($endMinutes <= $startMinutes) {
            $endMinutes = $startMinutes + self::DEFAULT_TASK_DURATION_MINUTES;
        }

        $gridStart = self::DAY_START_HOUR * 60;
        $gridEnd = self::DAY_END_HOUR * 60;
        $totalMinutes = max(1, $gridEnd - $gridStart);

        return [
            'task' => $task,
            'start_time' => $this->formatClock($task->start_time),
            'end_time' => $task->end_time ? $this->formatClock($task->end_time) : null,
            'time_label' => $this->formatTaskTimeLabel($task),
            'start_minutes' => $startMinutes,
            'top_percent' => max(0, (($startMinutes - $gridStart) / $totalMinutes) * 100),
            'height_percent' => max(4, (($endMinutes - $startMinutes) / $totalMinutes) * 100),
        ];
    }

    private function formatClock(?string $time): string
    {
        return Carbon::parse($this->normalizeTime($time))->format('H:i');
    }

    private function normalizeTime(?string $time): string
    {
        if (! $time) {
            return '00:00:00';
        }

        return strlen($time) === 5 ? $time.':00' : $time;
    }

    private function timeToMinutes(string $time): int
    {
        $parts = explode(':', $this->normalizeTime($time));

        return ((int) ($parts[0] ?? 0) * 60) + (int) ($parts[1] ?? 0);
    }

    /**
     * @param  Collection<int, TaskList>  $lists
     */
    public function buildCompanyMonth(Collection $lists, Carbon $month): array
    {
        $month = $month->copy()->startOfMonth();
        $gridStart = $month->copy()->startOfWeek(Carbon::MONDAY);
        $gridEnd = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $weeks = [];
        $cursor = $gridStart->copy();

        while ($cursor <= $gridEnd) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $date = $cursor->copy();
                $dayKey = strtolower($date->format('l'));
                $dayLists = $this->activeListsForDate($lists, $date);

                $week[] = [
                    'key' => $dayKey,
                    'label' => self::WEEKDAY_LABELS[$dayKey] ?? ucfirst($dayKey),
                    'date' => $date->format('Y-m-d'),
                    'day_number' => $date->day,
                    'is_today' => $date->isToday(),
                    'is_current_month' => $date->month === $month->month,
                    'lists' => $dayLists,
                    'list_count' => $dayLists->count(),
                ];

                $cursor->addDay();
            }
            $weeks[] = $week;
        }

        return [
            'mode' => 'month',
            'month' => $month->format('Y-m'),
            'month_start' => $month->format('Y-m-01'),
            'title' => $month->locale('nl')->translatedFormat('F Y'),
            'prev' => $month->copy()->subMonth()->format('Y-m-01'),
            'next' => $month->copy()->addMonth()->format('Y-m-01'),
            'today' => now()->format('Y-m-d'),
            'weeks' => $weeks,
            'weekday_headers' => array_values(self::WEEKDAY_LABELS),
            'total_lists' => $lists->count(),
        ];
    }
}

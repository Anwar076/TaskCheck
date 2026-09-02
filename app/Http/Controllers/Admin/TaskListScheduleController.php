<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\Location;
use App\Services\Admin\ListCalendarService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TaskListScheduleController extends Controller
{
    public function calendar(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $company = auth()->user()->company;
        $calendarService = app(ListCalendarService::class);
        $viewParam = $request->query('view', 'week');
        $calendarView = in_array($viewParam, ['week', 'day', 'month'], true) ? $viewParam : 'week';
        $selectedDay = $request->query('day', strtolower(now()->format('l')));
        if (! array_key_exists($selectedDay, ListCalendarService::WEEKDAY_LABELS)) {
            $selectedDay = strtolower(now()->format('l'));
        }

        $locationId = null;
        if ($request->filled('location_id')) {
            $candidateLocationId = (int) $request->get('location_id');
            if (Location::where('company_id', $companyId)->where('id', $candidateLocationId)->exists()) {
                $locationId = $candidateLocationId;
            }
        }

        $lists = TaskList::withCount('tasks')
            ->with(['tasks', 'location'])
            ->where('company_id', $companyId)
            ->where('is_active', true)
            ->when($locationId, fn ($query) => $query->where('location_id', $locationId))
            ->orderBy('title')
            ->get();

        $unscheduledLists = $lists
            ->filter(fn (TaskList $list) => $this->isUnscheduledList($list))
            ->values();

        if ($calendarView === 'month') {
            $monthStart = Carbon::parse($request->query('month', now()->format('Y-m-01')))->startOfMonth();
            $calendar = $calendarService->buildCompanyMonth($lists, $monthStart);
            $weekStart = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        } else {
            $weekStart = Carbon::parse($request->query('week', now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d')))
                ->startOfWeek(Carbon::MONDAY);
            $calendar = $calendarService->buildCompanyWeek(
                $lists,
                $weekStart,
                $company,
                $calendarView === 'day' ? [$selectedDay] : null
            );
            $validDayKeys = collect($calendar['days'])->pluck('key')->all();
            if (! in_array($selectedDay, $validDayKeys, true)) {
                $selectedDay = strtolower(now()->format('l'));
            }
        }

        $miniMonth = $calendarService->buildCompanyMonth(
            $lists,
            $calendarView === 'month'
                ? Carbon::parse($calendar['month_start'])
                : Carbon::parse($request->query('month', $weekStart->copy()->startOfMonth()->format('Y-m-01')))->startOfMonth()
        );

        $locations = Location::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.lists.calendar', compact(
            'calendar',
            'calendarView',
            'selectedDay',
            'miniMonth',
            'weekStart',
            'lists',
            'unscheduledLists',
            'locations',
            'locationId'
        ));
    }

    public function scheduleDay(Request $request, TaskList $list)
    {
        if ($list->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }

        $validated = $request->validate([
            'weekday' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ]);

        app(ListCalendarService::class)->ensureListScheduledOnWeekday($list, $validated['weekday']);

        return response()->json([
            'success' => true,
            'message' => "Lijst '{$list->title}' is gepland.",
            'list' => [
                'id' => $list->id,
                'title' => $list->title,
                'show_url' => route('admin.lists.show', [$list, 'view' => 'week', 'day' => $validated['weekday']]),
            ],
        ]);
    }

    public function scheduleTimeSlot(Request $request, TaskList $list)
    {
        if ($list->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }

        $validated = $request->validate([
            'weekday' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'date' => 'nullable|date_format:Y-m-d',
        ]);

        $startTime = $validated['start_time'];
        $endTime = ! empty($validated['end_time']) ? $validated['end_time'] : null;

        if ($endTime && $endTime <= $startTime) {
            return response()->json([
                'success' => false,
                'message' => 'Eindtijd moet na starttijd liggen.',
                'errors' => ['end_time' => ['Eindtijd moet na starttijd liggen.']],
            ], 422);
        }

        app(ListCalendarService::class)->assignListTimeSlot(
            $list,
            $validated['weekday'],
            $startTime,
            $endTime,
            $validated['date'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => "Lijst '{$list->title}' is gekoppeld aan het tijdslot.",
            'list' => [
                'id' => $list->id,
                'title' => $list->title,
                'show_url' => route('admin.lists.show', [$list, 'view' => 'week', 'day' => $validated['weekday']]),
            ],
        ]);
    }

    private function isUnscheduledList(TaskList $list): bool
    {
        $config = is_array($list->schedule_config) ? $list->schedule_config : [];
        $today = now()->startOfDay();

        return match ($list->schedule_type) {
            'once' => $list->due_date !== null && $list->due_date->lt($today),
            'daily', 'monthly' => false,
            'weekly' => ($config['show_on_days'] ?? []) === [],
            'custom' => $this->customScheduleHasNoFutureDates($config, $today),
            default => false,
        };
    }

    private function customScheduleHasNoFutureDates(array $config, Carbon $today): bool
    {
        $type = $config['type'] ?? null;

        if ($type === 'specific_days') {
            return ($config['days'] ?? $config['show_on_days'] ?? []) === [];
        }

        if ($type === 'interval') {
            return empty($config['interval_days']) || empty($config['start_date']);
        }

        if ($type === 'date_range') {
            if (empty($config['start_date']) || empty($config['end_date'])) {
                return true;
            }

            return Carbon::parse($config['end_date'])->endOfDay()->lt($today);
        }

        return array_key_exists('show_on_days', $config) && ($config['show_on_days'] ?? []) === [];
    }

    public function updateScheduleTimeSlot(Request $request, TaskList $list, string $slot)
    {
        if ($list->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }

        $validated = $request->validate([
            'weekday' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'date' => 'nullable|date_format:Y-m-d',
            'target_list_id' => 'nullable|integer|exists:task_lists,id',
        ]);

        $startTime = $validated['start_time'];
        $endTime = ! empty($validated['end_time']) ? $validated['end_time'] : null;

        if ($endTime && $endTime <= $startTime) {
            return response()->json([
                'success' => false,
                'message' => 'Eindtijd moet na starttijd liggen.',
                'errors' => ['end_time' => ['Eindtijd moet na starttijd liggen.']],
            ], 422);
        }

        $service = app(ListCalendarService::class);

        if (! $service->findListTimeSlot($list, $slot)) {
            abort(404, 'Tijdslot niet gevonden.');
        }

        $targetListId = isset($validated['target_list_id']) ? (int) $validated['target_list_id'] : null;
        $resultList = $list;
        $date = $validated['date'] ?? null;

        if ($date) {
            $service->assignListTimeSlot($list, $validated['weekday'], $startTime, $endTime, $date);
        } elseif ($slot === 'default') {
            if ($targetListId && $targetListId !== $list->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Standaard tijdslot kan niet naar een andere lijst verplaatst worden.',
                ], 422);
            }

            $service->assignListTimeSlot($list, $validated['weekday'], $startTime, $endTime);
        } elseif ($targetListId && $targetListId !== $list->id) {
            $target = TaskList::where('company_id', auth()->user()->company_id)->findOrFail($targetListId);
            $service->moveListTimeSlot($list, $slot, $target, $validated['weekday'], $startTime, $endTime);
            $resultList = $target;
        } else {
            $service->updateListTimeSlot($list, $slot, $validated['weekday'], $startTime, $endTime);
        }

        return response()->json([
            'success' => true,
            'message' => "Tijdslot voor '{$resultList->title}' is bijgewerkt.",
            'list' => [
                'id' => $resultList->id,
                'title' => $resultList->title,
                'show_url' => route('admin.lists.show', [$resultList, 'view' => 'week', 'day' => $validated['weekday']]),
            ],
        ]);
    }

    public function destroyScheduleTimeSlot(Request $request, TaskList $list, string $slot)
    {
        if ($list->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }

        $service = app(ListCalendarService::class);
        $date = $request->input('date');

        if ($date) {
            $service->removeListTimeSlotForDate($list, $date);

            return response()->json([
                'success' => true,
                'message' => "Tijdslot voor '{$list->title}' op deze dag is verwijderd.",
            ]);
        }

        if ($slot === 'default') {
            $service->removeDefaultTimeSlot($list);
        } else {
            $service->removeListTimeSlot($list, $slot);
        }

        return response()->json([
            'success' => true,
            'message' => "Tijdslot voor '{$list->title}' is verwijderd.",
        ]);
    }
}

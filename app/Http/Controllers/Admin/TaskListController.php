<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checklist\TaskList;
use App\Models\Checklist\ListAssignment;
use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use App\Models\Communication\Notification;
use App\Models\Organisation\Location;
use App\Models\Organisation\Company;
use App\Models\Checklist\Task;
use App\Models\Checklist\TaskTemplate;
use App\Services\Admin\ListCalendarService;
use App\Services\Admin\WeeklyOverviewService;
use App\Services\Ai\AiUsageLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class TaskListController extends Controller
{
    public function index(Request $request)
    {
        $query = TaskList::with(['creator', 'template', 'location'])
            ->withCount('tasks');

        if ($request->filled('search')) {
            $term = $request->get('search');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%")
                    ->orWhere('category', 'like', "%{$term}%");
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        if ($request->filled('location_id')) {
            $query->where('location_id', (int) $request->get('location_id'));
        }

        $lists = $query
            ->orderByRaw('display_order IS NULL')
            ->orderBy('display_order')
            ->orderBy('id')
            ->paginate(12);

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json($lists);
        }

        return view('admin.lists.index-api', [
            'initialLists' => $lists,
            'orderableLists' => TaskList::query()
                ->orderByRaw('display_order IS NULL')
                ->orderBy('display_order')
                ->orderBy('id')
                ->get(['id', 'title', 'is_active']),
        ]);
    }

    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'list_ids' => ['required', 'array', 'min:1'],
            'list_ids.*' => ['required', 'integer', 'distinct'],
        ]);

        $companyListIds = TaskList::query()->pluck('id')->map(fn ($id) => (int) $id);
        $submittedIds = collect($validated['list_ids'])->map(fn ($id) => (int) $id);

        if ($submittedIds->sort()->values()->all() !== $companyListIds->sort()->values()->all()) {
            throw ValidationException::withMessages([
                'list_ids' => 'De lijstvolgorde is verouderd. Vernieuw de pagina en probeer het opnieuw.',
            ]);
        }

        DB::transaction(function () use ($submittedIds) {
            foreach ($submittedIds as $index => $listId) {
                TaskList::query()->whereKey($listId)->update(['display_order' => $index + 1]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'De volgorde voor medewerkers is opgeslagen.',
        ]);
    }

    public function create()
    {
        $companyId = auth()->user()->company_id;

        // Get available templates (same company only)
        $templates = \App\Models\Checklist\TaskTemplate::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $locations = Location::where('is_active', true)->orderBy('name')->get();

        return view('admin.lists.create', compact('templates', 'locations'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'compliance_framework' => 'nullable|string|max:255',
            'policy_reference' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'schedule_type' => 'required|in:once,daily,weekly,monthly',
            'due_date' => 'nullable|date',
            'parent_list_id' => 'nullable|exists:task_lists,id',
            'requires_signature' => 'boolean',
            'requires_review' => 'boolean',
            'is_template' => 'boolean',
            'is_active' => 'boolean',
            'schedule_config' => 'nullable|array',
            'template_id' => 'nullable|exists:task_templates,id',
            'selected_days' => 'nullable|array',
            'selected_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'location_id' => [
                'nullable',
                Rule::exists('locations', 'id')->where(function ($query) {
                    $query->where('company_id', auth()->user()->company_id);
                }),
            ],
            'default_time_slot_enabled' => 'boolean',
            'default_time_slot_start' => 'nullable|date_format:H:i|required_if:default_time_slot_enabled,1',
            'default_time_slot_end' => 'nullable|date_format:H:i',
            'time_slots' => 'nullable|array',
            'time_slots.*.weekday' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'time_slots.*.start_time' => 'required|date_format:H:i',
            'time_slots.*.end_time' => 'nullable|date_format:H:i',
        ]);

        if ($request->boolean('default_time_slot_enabled')
            && $request->filled('default_time_slot_start')
            && $request->filled('default_time_slot_end')
            && $request->input('default_time_slot_end') <= $request->input('default_time_slot_start')) {
            return back()
                ->withErrors(['default_time_slot_end' => 'Eindtijd moet na starttijd liggen.'])
                ->withInput();
        }

        $rawTimeSlots = collect($request->input('time_slots', []))
            ->filter(fn ($slot) => is_array($slot) && ! empty($slot['weekday']) && ! empty($slot['start_time']))
            ->values();

        foreach ($rawTimeSlots as $index => $slot) {
            $end = $slot['end_time'] ?? null;
            if ($end && $end <= $slot['start_time']) {
                return back()
                    ->withErrors(["time_slots.{$index}.end_time" => 'Eindtijd moet na starttijd liggen.'])
                    ->withInput();
            }
        }

        // Set creator and company
        $validatedData['created_by'] = auth()->id();
        $validatedData['company_id'] = auth()->user()->company_id;
        $validatedData['requires_review'] = $request->boolean('requires_review');

        $scheduleConfig = $validatedData['schedule_config'] ?? [];

        if ($validatedData['schedule_type'] === 'weekly') {
            $selectedDays = $validatedData['selected_days'] ?? [];
            if ($selectedDays === []) {
                return back()
                    ->withErrors(['selected_days' => 'Selecteer minimaal één dag voor een wekelijkse lijst.'])
                    ->withInput();
            }
        }

        if ($validatedData['schedule_type'] !== 'once') {
            $validatedData['due_date'] = null;
        }

        $scheduleConfig = $this->normalizeScheduleConfig(
            $validatedData['schedule_type'],
            $scheduleConfig,
            $validatedData['selected_days'] ?? null
        );

        if ($request->boolean('default_time_slot_enabled') && $request->filled('default_time_slot_start')) {
            $scheduleConfig['default_time_slot'] = [
                'start_time' => $request->input('default_time_slot_start'),
                'end_time' => $request->filled('default_time_slot_end') ? $request->input('default_time_slot_end') : null,
            ];
        }

        if ($rawTimeSlots->isNotEmpty()) {
            $scheduleConfig['time_slots'] = $rawTimeSlots->map(fn (array $slot) => [
                'id' => (string) Str::uuid(),
                'weekday' => $slot['weekday'],
                'start_time' => $slot['start_time'],
                'end_time' => ! empty($slot['end_time']) ? $slot['end_time'] : null,
            ])->all();
        }

        $validatedData['schedule_config'] = $scheduleConfig;

        // Remove selected_days from the main data as it's now in schedule_config
        unset($validatedData['selected_days']);
        unset($validatedData['default_time_slot_enabled'], $validatedData['default_time_slot_start'], $validatedData['default_time_slot_end']);
        unset($validatedData['time_slots']);
        $validatedData['location_id'] = $validatedData['location_id'] ?? null;

        // Decouple from template: store which template was used for reference, but clear
        // the link so syncToLists() never overwrites the customer's future edits.
        $usedTemplateId = $validatedData['template_id'] ?? null;
        $validatedData['template_id'] = null;

        // Create the task list (without template_id so it is independent)
        $taskList = TaskList::create($validatedData);

        // If a template was selected, copy its tasks as independent tasks into the new list
        if (!empty($usedTemplateId)) {
            $template = \App\Models\Checklist\TaskTemplate::find($usedTemplateId);
            if ($template) {
                foreach ($template->templateTasks as $templateTask) {
                    \App\Models\Checklist\Task::create([
                        'list_id'             => $taskList->id,
                        'title'               => $templateTask->title,
                        'description'         => $templateTask->description,
                        'instructions'        => $templateTask->instructions,
                        'checklist_items'     => $templateTask->checklist_items,
                        'required_proof_type' => $templateTask->required_proof_type,
                        'is_required'         => $templateTask->is_required,
                        'attachments'         => $templateTask->attachments,
                        'validation_rules'    => $templateTask->validation_rules,
                        'start_time'          => $templateTask->start_time,
                        'end_time'            => $templateTask->end_time,
                        'order_index'         => $templateTask->sort_order,
                        'created_by'          => auth()->id(),
                        'weekday'             => null,
                    ]);
                }
            }
        }

        // If AI-taken zijn meegestuurd, maak daar meteen echte taken van
        $aiTasksRaw = $request->input('ai_tasks');
        if ($aiTasksRaw) {
            $decoded = json_decode($aiTasksRaw, true);
            if (is_array($decoded)) {
                $orderBase = \App\Models\Checklist\Task::where('list_id', $taskList->id)->max('order_index') ?? 0;
                $order = $orderBase + 1;

                foreach ($decoded as $taskData) {
                    if (!is_array($taskData)) {
                        continue;
                    }
                    $title = isset($taskData['title']) ? trim((string) $taskData['title']) : '';
                    if ($title === '') {
                        continue;
                    }
                    $description = isset($taskData['description']) ? trim((string) $taskData['description']) : null;

                    \App\Models\Checklist\Task::create([
                        'list_id' => $taskList->id,
                        'title' => $title,
                        'description' => $description,
                        'instructions' => null,
                        'checklist_items' => null,
                        'required_proof_type' => 'none',
                        'is_required' => false,
                        'attachments' => [],
                        'validation_rules' => [],
                        'start_time' => null,
                        'end_time' => null,
                        'order_index' => $order,
                        'order' => $order,
                        'created_by' => auth()->id(),
                        'weekday' => null,
                        'requires_signature' => false,
                    ]);

                    $order++;
                }
            }
        }

        $company = auth()->user()->company;
        if ($company) {
            app(\App\Services\Platform\AdminOnboardingService::class)->handleListCreated($company, $taskList);
        }

        return redirect()->route('admin.lists.show', $taskList)
            ->with('success', 'Takenlijst succesvol aangemaakt!' . ($usedTemplateId ? ' Taken uit template zijn toegevoegd.' : '') . ($aiTasksRaw ? ' AI-voorgestelde taken zijn toegevoegd.' : ''));
    }

    public function show(Request $request, TaskList $list)
    {
        $list->load([
            'assignments.user',
            'tasks' => fn ($query) => $query->orderBy('order_index'),
            'submissions',
            'location',
        ]);
        
        // Get all users for the assignment modal (zelfde bedrijf; bij null company_id alle gebruikers)
        $companyId = auth()->user()->company_id;
        $users = \App\Models\Organisation\User::query()
            ->when($companyId !== null, fn($q) => $q->where('company_id', $companyId))
            ->whereIn('role', ['employee', 'admin'])
            ->where('is_active', true)
            ->when($list->location_id, fn($q) => $q->where('location_id', $list->location_id))
            ->orderBy('name')
            ->get();

        $company = auth()->user()->company;
        $departments = collect($company?->departments ?? [])
            ->filter(fn ($item) => is_string($item) && trim($item) !== '')
            ->values()
            ->all();
        
        // Ensure list belongs to same company
        if ($list->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }
        
        return view('admin.lists.show', compact('list', 'users', 'departments'));
    }

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

    public function edit(TaskList $list)
    {
        // Ensure list belongs to same company
        if ($list->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }
        
        $locations = Location::where('is_active', true)
            ->orWhere('id', $list->location_id)
            ->orderBy('name')
            ->get();

        $calendarService = app(ListCalendarService::class);
        $timeSlots = $calendarService->getTimeSlots($list);
        $defaultTimeSlot = $calendarService->getDefaultTimeSlot($list);

        return view('admin.lists.edit', compact('list', 'locations', 'timeSlots', 'defaultTimeSlot'));
    }

    public function update(Request $request, TaskList $list)
    {
        // Ensure list belongs to same company
        if ($list->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }
        
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'compliance_framework' => 'nullable|string|max:255',
            'policy_reference' => 'nullable|string|max:255',
            'priority' => 'required|in:low,medium,high,urgent',
            'schedule_type' => 'required|in:once,daily,weekly,monthly',
            'due_date' => 'nullable|date',
            'parent_list_id' => 'nullable|exists:task_lists,id',
            'requires_signature' => 'boolean',
            'requires_review' => 'boolean',
            'is_template' => 'boolean',
            'is_active' => 'boolean',
            'schedule_config' => 'nullable|array',
            'selected_days' => 'nullable|array',
            'selected_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'location_id' => [
                'nullable',
                Rule::exists('locations', 'id')->where(function ($query) {
                    $query->where('company_id', auth()->user()->company_id);
                }),
            ],
            'default_time_slot_enabled' => 'boolean',
            'default_time_slot_start' => 'nullable|date_format:H:i|required_if:default_time_slot_enabled,1',
            'default_time_slot_end' => 'nullable|date_format:H:i',
        ]);

        if ($request->boolean('default_time_slot_enabled')
            && $request->filled('default_time_slot_start')
            && $request->filled('default_time_slot_end')
            && $request->input('default_time_slot_end') <= $request->input('default_time_slot_start')) {
            return back()
                ->withErrors(['default_time_slot_end' => 'Eindtijd moet na starttijd liggen.'])
                ->withInput();
        }

        // Handle improved schedule configuration
        $existingConfig = is_array($list->schedule_config) ? $list->schedule_config : [];
        $inputConfig = $validatedData['schedule_config'] ?? [];

        if ($validatedData['schedule_type'] === 'weekly') {
            $selectedDays = $validatedData['selected_days'] ?? [];
            if ($selectedDays === []) {
                return back()
                    ->withErrors(['selected_days' => 'Selecteer minimaal één dag voor een wekelijkse lijst.'])
                    ->withInput();
            }
        }

        if ($validatedData['schedule_type'] !== 'once') {
            $validatedData['due_date'] = null;
        }

        $scheduleConfig = $this->normalizeScheduleConfig(
            $validatedData['schedule_type'],
            array_merge($existingConfig, $inputConfig),
            $validatedData['selected_days'] ?? null
        );

        $scheduledDays = $scheduleConfig['show_on_days'] ?? null;

        if (isset($existingConfig['time_slots'])) {
            $timeSlots = is_array($existingConfig['time_slots']) ? $existingConfig['time_slots'] : [];

            if (is_array($scheduledDays)) {
                $timeSlots = array_values(array_filter($timeSlots, function ($slot) use ($scheduledDays) {
                    return in_array($slot['weekday'] ?? null, $scheduledDays, true);
                }));
            }

            $scheduleConfig['time_slots'] = $timeSlots;
        }

        if ($request->boolean('default_time_slot_enabled') && $request->filled('default_time_slot_start')) {
            $scheduleConfig['default_time_slot'] = [
                'start_time' => $request->input('default_time_slot_start'),
                'end_time' => $request->filled('default_time_slot_end') ? $request->input('default_time_slot_end') : null,
            ];
        } else {
            unset($scheduleConfig['default_time_slot']);
        }

        $validatedData['schedule_config'] = $scheduleConfig;

        // Remove selected_days from the main data as it's now in schedule_config
        unset($validatedData['selected_days']);
        unset($validatedData['default_time_slot_enabled'], $validatedData['default_time_slot_start'], $validatedData['default_time_slot_end']);
        $validatedData['location_id'] = $validatedData['location_id'] ?? null;
        $validatedData['requires_review'] = $request->boolean('requires_review');

        // Update the task list
        $list->update($validatedData);

        return redirect()->route('admin.lists.show', $list)
            ->with('success', 'Takenlijst succesvol bijgewerkt!');
    }

    public function aiGenerate(Request $request)
    {
        $this->ensurePlanFeatureAvailable('ai');

        $validated = $request->validate([
            'prompt' => 'nullable|string|max:2000',
            'source_file' => 'nullable|file|max:8192|mimes:jpg,jpeg,png,webp,pdf',
        ]);

        $apiKey = Config::get('services.openai.key');
        $model = Config::get('services.openai.model', 'gpt-4.1-mini');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'AI is niet geconfigureerd (OPENAI_API_KEY ontbreekt).',
            ], 422);
        }

        $hasPrompt = !empty($validated['prompt']);
        $hasFile = $request->hasFile('source_file');

        if (!$hasPrompt && !$hasFile) {
            return response()->json([
                'success' => false,
                'message' => 'Geef een korte beschrijving of upload een bestand.',
            ], 422);
        }

        $fileUrl = null;
        $fileType = null;

        if ($hasFile) {
            $file = $request->file('source_file');
            $fileType = strtolower($file->getClientOriginalExtension());

            // Voor nu ondersteunen we alleen echte afbeeldingen voor AI-visie
            if (!in_array($fileType, ['jpg', 'jpeg', 'png', 'webp'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alleen afbeeldingsbestanden (jpg, jpeg, png, webp) worden momenteel ondersteund voor AI-lijstbouw. PDF/Word volgt later.',
                ], 422);
            }

            // In een lokale ontwikkelomgeving (127.0.0.1 / localhost) kan OpenAI deze URL niet bereiken.
            // Daarom blokkeren we fotogebruik lokaal en vragen we om alleen tekst te gebruiken.
            $appUrl = config('app.url');
            if ($appUrl && (str_contains($appUrl, '127.0.0.1') || str_contains($appUrl, 'localhost'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Foto-gebaseerde AI lijstbouw werkt niet in de lokale omgeving. Gebruik hier een tekstbeschrijving; in productie met een publiek bereikbare URL kan de foto wel worden gelezen.',
                ], 422);
            }

            $path = $file->store('ai-list-sources', 'public');
            $fileUrl = asset('storage/' . $path);
        }

        $systemPrompt = <<<'PROMPT'
Je bent een Nederlandse assistent die op basis van een korte beschrijving of een foto van een papieren checklist een digitale takenlijst maakt.

Je taak:
- Bedenk een heldere titel voor de lijst.
- Schrijf een korte beschrijving (1-3 zinnen) in het Nederlands.
- Bedenk een korte categorie (bijv. "Schoonmaak", "Veiligheid", "Keuken", "Kantoor").
- Haal uit de tekst/foto de afzonderlijke taken en maak daarvan een reeks concrete taken.
- Per taak: geef een korte titel en optionele korte toelichting.

Geef je ANTWOORD ALLEEN als JSON in dit formaat:
{
  "title": "lijsttitel",
  "description": "korte beschrijving",
  "category": "categorie of leeg",
  "tasks": [
    {
      "title": "taaktitel",
      "description": "optionele korte toelichting of leeg"
    }
  ]
}

Maak maximaal 25 taken. Schrijf alles in duidelijk, praktisch Nederlands.
PROMPT;

        $userParts = [];
        if ($hasPrompt) {
            $userParts[] = "Beschrijving van de lijst:\n" . $validated['prompt'];
        }
        if ($hasFile && $fileUrl) {
            $userParts[] = "Gebruik ook de informatie van de meegestuurde foto van een checklist.";
        }

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        if ($hasFile && $fileUrl) {
            $messages[] = [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => implode("\n\n", $userParts),
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => $fileUrl,
                        ],
                    ],
                ],
            ];
        } else {
            $messages[] = [
                'role' => 'user',
                'content' => implode("\n\n", $userParts),
            ];
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'response_format' => ['type' => 'json_object'],
                    'messages' => $messages,
                ]);

            if (!$response->ok()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI-verzoek mislukt: ' . $response->body(),
                ], 500);
            }

            AiUsageLogger::logChatCompletion(
                $response,
                AiUsageLogger::FEATURE_LIST_AI_GENERATE,
                auth()->user()->company_id,
                auth()->id(),
                $model
            );

            $content = $response->json('choices.0.message.content');
            $decoded = is_string($content) ? json_decode($content, true) : null;

            if (!is_array($decoded)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ongeldig AI-antwoord ontvangen.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'title' => $decoded['title'] ?? '',
                    'description' => $decoded['description'] ?? '',
                    'category' => $decoded['category'] ?? '',
                    'tasks' => $decoded['tasks'] ?? [],
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error('AI list generate failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'AI-verzoek is mislukt: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function aiImportPage(?Company $company = null)
    {
        if (!$company) {
            $this->ensurePlanFeatureAvailable('ai');
        }

        return view('admin.lists.ai-import', compact('company'));
    }

    public function aiImportGenerate(Request $request, ?Company $company = null)
    {
        if (!$company) {
            $this->ensurePlanFeatureAvailable('ai');
        }

        $validated = $request->validate([
            'prompt' => 'nullable|string|max:4000',
            // The UI keeps multi-upload UX but sends each document separately.
            // This bounds latency and prevents a single oversized AI generation.
            'source_files' => 'nullable|array|max:1',
            'source_files.*' => 'file|max:12288|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp',
        ]);

        $apiKey = Config::get('services.openai.key');
        $model = Config::get('services.openai.model', 'gpt-4.1-mini');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'AI is niet geconfigureerd (OPENAI_API_KEY ontbreekt).',
            ], 422);
        }

        $prompt = trim((string) ($validated['prompt'] ?? ''));
        $files = array_values(array_filter((array) $request->file('source_files', [])));
        if ($prompt === '' && empty($files)) {
            return response()->json([
                'success' => false,
                'message' => 'Geef een beschrijving of upload maximaal 5 bestanden.',
            ], 422);
        }

        $messages = [];
        $messages[] = [
            'role' => 'system',
            'content' => <<<'PROMPT'
Je bent een nauwkeurige Nederlandse assistent die operationele documenten omzet naar digitale takenlijsten.

Maak voor ieder aangeleverd document precies één lijst, in dezelfde volgorde als de documenten. Splits een document nooit op in meerdere lijsten en voeg verschillende documenten nooit samen. Als er geen document is, maak precies één lijst van de gebruikersbeschrijving.
Output ALLEEN JSON in exact dit formaat:
{
  "lists": [
    {
      "title": "string",
      "description": "string",
      "category": "string",
      "priority": "low|medium|high|urgent",
      "schedule_type": "once|daily|weekly|monthly|custom",
      "tasks": [
        {
          "title": "string",
          "description": "string",
          "required_proof_type": "none|photo|video|text|file|any",
          "is_required": true,
          "requires_signature": false,
          "checklist_items": ["string", "string"]
        }
      ]
    }
  ]
}

Regels:
- Het aantal lijsten moet exact gelijk zijn aan het aantal aangeleverde documenten.
- Neem als title exact de opgegeven gewenste lijstnaam over.
- Max 40 taken per lijst.
- Gebruik iedere herkenbare taakregel of ieder vinkje precies één keer als afzonderlijke taak. Sla niets over en voeg geen nieuwe werkzaamheden toe.
- Houd de taaknaam kort. Plaats uitleg, aantallen, tijden, temperaturen, dagverdelingen en voorwaarden volledig in description.
- Behoud belangrijke termen en corrigeer alleen onmiskenbare HTML-codes of evidente woordafbrekingen. Ga niet raden als de bron onduidelijk is.
- Gebruik schedule_type daily voor operationele dag-, openings-, sluitings-, schoonmaak-, voorbereidings- en bijvullijsten, tenzij de bron of gebruiker expliciet iets anders zegt.
- Gebruik priority medium als de bron geen prioriteit noemt.
- Gebruik standaard required_proof_type none. Kies alleen photo als de bron of gebruiker expliciet om een foto/zichtbaar bewijs vraagt, text als werkelijk een waarde of toelichting geregistreerd moet worden, en file als een document moet worden aangeleverd.
- Zet is_required op true voor reguliere en kritieke taken. Voorwaardelijke taken blijven taken; vermeld de voorwaarde duidelijk in description.
- Zet requires_signature alleen op true wanneer de bron expliciet om een handtekening, paraaf of akkoord vraagt.
- Gebruik checklist_items alleen voor echte subhandelingen of meerdere controlepunten (2-8 items), niet om de taakomschrijving te herhalen.
PROMPT,
        ];

        $userParts = [];
        if ($prompt !== '') {
            $userParts[] = "Extra context van gebruiker:\n" . $prompt;
        }

        $content = [];
        if (!empty($userParts)) {
            $content[] = [
                'type' => 'text',
                'text' => implode("\n\n", $userParts),
            ];
        }

        $documentTitles = [];
        foreach ($files as $index => $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
            $title = Str::limit(trim((string) pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)), 255, '');
            $title = $title !== '' ? $title : 'Geïmporteerde lijst '.($index + 1);
            $documentTitles[] = $title;

            $content[] = [
                'type' => 'text',
                'text' => "DOCUMENT ".($index + 1)." — gewenste lijstnaam: {$title}",
            ];

            if ($isImage) {
                $bytes = file_get_contents($file->getRealPath());
                $mime = $file->getMimeType() ?: 'image/png';
                $content[] = [
                    'type' => 'image_url',
                    'image_url' => [
                        'url' => 'data:' . $mime . ';base64,' . base64_encode($bytes),
                    ],
                ];
                $content[] = [
                    'type' => 'text',
                    'text' => 'Lees uitsluitend de direct voorafgaande afbeelding als document '.($index + 1).'.',
                ];
            } else {
                $extractedText = $this->extractImportSourceText($file);
                if (trim($extractedText) === '') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kon onvoldoende tekst halen uit '.$file->getClientOriginalName().'. Probeer een duidelijker bestand of voeg extra context toe.',
                    ], 422);
                }
                $content[] = [
                    'type' => 'text',
                    'text' => "Tekst van document ".($index + 1).":\n" . mb_substr($extractedText, 0, 12000),
                ];
            }
        }

        if (empty($content)) {
            $content[] = ['type' => 'text', 'text' => 'Maak een algemene takenlijst op basis van de context.'];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $content,
        ];

        try {
            // Stay below the common 60-second nginx/FastCGI limit. The browser
            // submits one document per request so one slow file cannot block all imports.
            $timeout = max(15, min(50, (int) Config::get('services.openai.ai_import_timeout', 45)));
            $maxTokens = max(1000, min(12000, (int) Config::get('services.openai.ai_import_max_tokens', 8000)));
            $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
                ->connectTimeout(10)
                ->timeout($timeout)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'response_format' => ['type' => 'json_object'],
                    'max_tokens' => $maxTokens,
                    'messages' => $messages,
                ]);

            if (!$response->ok()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI-verzoek mislukt: ' . $response->body(),
                ], 500);
            }

            AiUsageLogger::logChatCompletion(
                $response,
                AiUsageLogger::FEATURE_LIST_AI_IMPORT,
                $company?->id ?? auth()->user()->company_id,
                auth()->id(),
                $model
            );

            $contentText = $response->json('choices.0.message.content');
            $decoded = is_string($contentText) ? json_decode($contentText, true) : null;

            if (!is_array($decoded) || !isset($decoded['lists']) || !is_array($decoded['lists'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI gaf geen geldig lijst-formaat terug.',
                ], 500);
            }

            if (!empty($documentTitles) && count($decoded['lists']) !== count($documentTitles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI maakte niet voor ieder bestand precies één lijst. Probeer het opnieuw of upload minder bestanden tegelijk.',
                ], 500);
            }

            $lists = $this->normalizeAiImportLists($decoded['lists']);
            foreach ($documentTitles as $index => $title) {
                $lists[$index]['title'] = $title;
                $lists[$index]['schedule_type'] = 'daily';
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'lists' => $lists,
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error('AI import generate failed', [
                'error' => $e->getMessage(),
                'exception' => $e::class,
                'company_id' => $company?->id ?? auth()->user()?->company_id,
                'file_count' => count($files),
                'model' => $model,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e instanceof \Illuminate\Http\Client\ConnectionException
                    ? 'OpenAI reageerde niet op tijd. Probeer dit document opnieuw; andere documenten kunnen gewoon doorgaan.'
                    : 'AI-import is mislukt. Probeer dit document opnieuw.',
            ], $e instanceof \Illuminate\Http\Client\ConnectionException ? 504 : 500);
        }
    }

    public function aiImportStore(Request $request, ?Company $company = null)
    {
        if (!$company) {
            $this->ensurePlanFeatureAvailable('ai');
        }

        $validated = $request->validate([
            'import_payload' => 'required|string',
            'selected_indices' => 'required|array|min:1',
            'selected_indices.*' => 'integer|min:0',
        ]);

        $payload = json_decode($validated['import_payload'], true);
        if (!is_array($payload) || !isset($payload['lists']) || !is_array($payload['lists'])) {
            return redirect()->back()->with('error', 'Ongeldige import-payload.');
        }

        $allowedPriority = ['low', 'medium', 'high', 'urgent'];
        $allowedSchedule = ['once', 'daily', 'weekly', 'monthly', 'custom'];
        $allowedProofTypes = ['none', 'photo', 'video', 'text', 'file', 'any'];

        $createdLists = 0;
        $createdTasks = 0;

        foreach ($validated['selected_indices'] as $idx) {
            if (!isset($payload['lists'][$idx]) || !is_array($payload['lists'][$idx])) {
                continue;
            }
            $item = $payload['lists'][$idx];
            $title = trim((string) ($item['title'] ?? ''));
            if ($title === '') {
                continue;
            }

            $requestedPriority = (string) ($item['priority'] ?? 'medium');
            $requestedSchedule = (string) ($item['schedule_type'] ?? 'once');
            $priority = in_array($requestedPriority, $allowedPriority, true) ? $requestedPriority : 'medium';
            $scheduleType = in_array($requestedSchedule, $allowedSchedule, true) ? $requestedSchedule : 'once';

            $list = TaskList::create([
                'title' => $title,
                'description' => trim((string) ($item['description'] ?? '')) ?: null,
                'category' => trim((string) ($item['category'] ?? '')) ?: null,
                'priority' => $priority,
                'schedule_type' => $scheduleType,
                'due_date' => null,
                'parent_list_id' => null,
                'requires_signature' => false,
                'is_template' => false,
                'is_active' => true,
                'schedule_config' => null,
                'created_by' => auth()->id(),
                'company_id' => $company?->id ?? auth()->user()->company_id,
            ]);
            $createdLists++;

            $tasks = is_array($item['tasks'] ?? null) ? $item['tasks'] : [];
            $order = 1;
            foreach ($tasks as $taskItem) {
                if (!is_array($taskItem)) {
                    continue;
                }
                $taskTitle = trim((string) ($taskItem['title'] ?? ''));
                if ($taskTitle === '') {
                    continue;
                }

                $proofType = (string) ($taskItem['required_proof_type'] ?? 'none');
                \App\Models\Checklist\Task::create([
                    'list_id' => $list->id,
                    'title' => $taskTitle,
                    'description' => trim((string) ($taskItem['description'] ?? '')) ?: null,
                    'instructions' => null,
                    'checklist_items' => $this->normalizeChecklistItems($taskItem['checklist_items'] ?? null),
                    'required_proof_type' => in_array($proofType, $allowedProofTypes, true) ? $proofType : 'none',
                    'is_required' => (bool) ($taskItem['is_required'] ?? false),
                    'attachments' => [],
                    'validation_rules' => [],
                    'start_time' => null,
                    'end_time' => null,
                    'order_index' => $order,
                    'order' => $order,
                    'created_by' => auth()->id(),
                    'weekday' => null,
                    'requires_signature' => (bool) ($taskItem['requires_signature'] ?? false),
                ]);
                $order++;
                $createdTasks++;
            }
        }

        $redirect = $company
            ? redirect()->route('super-admin.companies.show', $company)
            : redirect()->route('admin.lists.index');

        return $redirect
            ->with('success', "AI-import voltooid: {$createdLists} lijst(en) en {$createdTasks} taak/taken aangemaakt.");
    }

    private function extractImportSourceText(\Illuminate\Http\UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();
        if (!$path) {
            return '';
        }

        if ($ext === 'pdf') {
            return $this->extractPdfTextFallback($path);
        }
        if ($ext === 'docx') {
            return $this->extractDocxText($path);
        }
        if (in_array($ext, ['xlsx', 'xls'])) {
            return $this->extractXlsxText($path);
        }
        if ($ext === 'doc') {
            return '';
        }

        return (string) file_get_contents($path);
    }

    private function normalizeAiImportLists(array $lists): array
    {
        $allowedPriority = ['low', 'medium', 'high', 'urgent'];
        $allowedSchedule = ['once', 'daily', 'weekly', 'monthly', 'custom'];
        $allowedProofTypes = ['none', 'photo', 'video', 'text', 'file', 'any'];

        $normalized = [];
        foreach ($lists as $list) {
            if (!is_array($list)) {
                continue;
            }

            $tasks = [];
            foreach (array_slice((array) ($list['tasks'] ?? []), 0, 40) as $task) {
                if (!is_array($task)) {
                    continue;
                }
                $title = trim((string) ($task['title'] ?? ''));
                if ($title === '') {
                    continue;
                }
                $proofType = (string) ($task['required_proof_type'] ?? 'none');
                $tasks[] = [
                    'title' => $title,
                    'description' => trim((string) ($task['description'] ?? '')),
                    'required_proof_type' => in_array($proofType, $allowedProofTypes, true) ? $proofType : 'none',
                    'is_required' => (bool) ($task['is_required'] ?? false),
                    'requires_signature' => (bool) ($task['requires_signature'] ?? false),
                    'checklist_items' => $this->normalizeChecklistItems($task['checklist_items'] ?? null) ?? [],
                ];
            }

            $priority = (string) ($list['priority'] ?? 'medium');
            $scheduleType = (string) ($list['schedule_type'] ?? 'once');
            $normalized[] = [
                'title' => trim((string) ($list['title'] ?? '')) ?: 'Nieuwe AI lijst',
                'description' => trim((string) ($list['description'] ?? '')),
                'category' => trim((string) ($list['category'] ?? '')),
                'priority' => in_array($priority, $allowedPriority, true) ? $priority : 'medium',
                'schedule_type' => in_array($scheduleType, $allowedSchedule, true) ? $scheduleType : 'once',
                'tasks' => $tasks,
            ];
        }

        return $normalized;
    }

    private function normalizeChecklistItems($items): ?array
    {
        if (!is_array($items)) {
            return null;
        }

        $clean = [];
        foreach ($items as $item) {
            $value = trim((string) $item);
            if ($value !== '') {
                $clean[] = $value;
            }
        }

        return empty($clean) ? null : array_values($clean);
    }

    private function extractPdfTextFallback(string $path): string
    {
        $content = (string) file_get_contents($path);
        preg_match_all('/\(([^)]{2,200})\)/', $content, $matches);
        $chunks = $matches[1] ?? [];
        $text = implode(' ', $chunks);
        $text = preg_replace('/\s+/', ' ', (string) $text);
        return trim((string) $text);
    }

    private function extractDocxText(string $path): string
    {
        if (!class_exists(\ZipArchive::class)) {
            return '';
        }

        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return '';
        }

        $xml = (string) $zip->getFromName('word/document.xml');
        $zip->close();
        if ($xml === '') {
            return '';
        }

        $xml = str_replace(['</w:tc>', '</w:tr>', '</w:p>'], ["\t", "\n", "\n"], $xml);
        $text = html_entity_decode(strip_tags($xml), ENT_QUOTES | ENT_XML1, 'UTF-8');
        $text = preg_replace('/[ \t]+/', ' ', (string) $text);
        $text = preg_replace('/\s*\n\s*/', "\n", (string) $text);
        return trim((string) $text);
    }

    private function extractXlsxText(string $path): string
    {
        if (!class_exists(\ZipArchive::class)) {
            return '';
        }

        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return '';
        }

        $sharedStringsXml = (string) $zip->getFromName('xl/sharedStrings.xml');
        $sharedStrings = [];
        if ($sharedStringsXml !== '') {
            $sx = @simplexml_load_string($sharedStringsXml);
            if ($sx && isset($sx->si)) {
                foreach ($sx->si as $item) {
                    $sharedStrings[] = trim((string) $item->t);
                }
            }
        }

        $textParts = [];
        for ($i = 1; $i <= 5; $i++) {
            $sheetXml = (string) $zip->getFromName("xl/worksheets/sheet{$i}.xml");
            if ($sheetXml === '') {
                continue;
            }
            $sheet = @simplexml_load_string($sheetXml);
            if (!$sheet || !isset($sheet->sheetData->row)) {
                continue;
            }
            foreach ($sheet->sheetData->row as $row) {
                foreach ($row->c as $cell) {
                    $type = (string) ($cell['t'] ?? '');
                    $raw = (string) ($cell->v ?? '');
                    if ($raw === '') {
                        continue;
                    }
                    if ($type === 's') {
                        $idx = (int) $raw;
                        $textParts[] = $sharedStrings[$idx] ?? '';
                    } else {
                        $textParts[] = $raw;
                    }
                }
            }
        }

        $zip->close();
        $text = implode("\n", array_filter($textParts));
        $text = preg_replace('/\s+/', ' ', (string) $text);
        return trim((string) $text);
    }

    public function destroy(TaskList $list)
    {
        // Ensure list belongs to same company
        if ($list->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }
        
        try {
            \Log::info('Attempting to delete task list', [
                'list_id' => $list->id,
                'list_title' => $list->title,
                'request_method' => request()->method(),
                'request_ajax' => request()->ajax(),
                'request_expects_json' => request()->expectsJson(),
            ]);

            // Check for related data that might prevent deletion
            $tasksCount = $list->tasks()->count();
            // Get ALL assignments, not just active ones
            $allAssignmentsCount = \App\Models\Checklist\ListAssignment::where('list_id', $list->id)->count();
            $submissionsCount = $list->submissions()->count();
            $childListsCount = $list->subLists()->count();

            \Log::info('Related data count', [
                'tasks' => $tasksCount,
                'all_assignments' => $allAssignmentsCount,
                'submissions' => $submissionsCount,
                'child_lists' => $childListsCount
            ]);

            // First, handle related records in correct order to avoid foreign key violations
            
            // 1. Handle child lists (sublists) - set parent_list_id to null or delete them
            if ($childListsCount > 0) {
                $list->subLists()->update(['parent_list_id' => null]);
                \Log::info('Updated child lists to remove parent reference');
            }
            
            // 2. Handle submissions FIRST - delete them and their tasks to avoid foreign key constraint
            if ($submissionsCount > 0) {
                // First delete submission_tasks that reference the tasks
                $submissions = $list->submissions;
                foreach ($submissions as $submission) {
                    $submission->submissionTasks()->delete();
                }
                // Then delete the submissions themselves
                $list->submissions()->delete();
                \Log::info('Deleted submissions and their tasks');
            }
            
            // 3. Delete ALL assignments (both active and inactive) to avoid foreign key constraint
            if ($allAssignmentsCount > 0) {
                \App\Models\Checklist\ListAssignment::where('list_id', $list->id)->delete();
                \Log::info('Deleted all assignments');
            }
            
            // 4. Delete tasks LAST (after submission_tasks are gone)
            if ($tasksCount > 0) {
                $list->tasks()->delete();
                \Log::info('Deleted associated tasks');
            }
            
            // Now delete the list
            $list->delete();
            
            \Log::info('Task list deleted successfully', ['list_id' => $list->id]);
            
            // Check if it's an AJAX request
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Takenlijst succesvol verwijderd.'
                ]);
            }
            
            return redirect()->route('admin.lists.index')->with('success', 'Takenlijst succesvol verwijderd.');
            
        } catch (\Exception $e) {
            \Log::error('Failed to delete task list', [
                'list_id' => $list->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Check if it's an AJAX request
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Er is een fout opgetreden bij het verwijderen: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.lists.index')
                ->with('error', 'Er is een fout opgetreden bij het verwijderen: ' . $e->getMessage());
        }
    }

    public function assign(Request $request, TaskList $list)
    {
        // Zorg dat de lijst bij hetzelfde bedrijf hoort
        if ($list->company_id !== auth()->user()->company_id) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Geen toegang tot deze lijst.'], 403);
            }
            abort(403, 'Geen toegang tot deze lijst.');
        }

        try {
            \Log::info('Assignment request received', [
                'list_id' => $list->id,
                'request_data' => $request->all()
            ]);

            $validationRules = [
                'assignment_type' => 'required|in:user,department',
                'assigned_date' => 'required|date',
                'due_date' => 'nullable|date|after_or_equal:assigned_date',
            ];

            // Add conditional validation based on assignment type
            if ($request->assignment_type === 'user') {
                $validationRules['user_ids'] = 'required|exists:users,id';
            } elseif ($request->assignment_type === 'department') {
                $validationRules['department'] = 'required|string|max:100';
            }

            $validatedData = $request->validate($validationRules);

            \Log::info('Validation passed', ['validated_data' => $validatedData]);

            $assignments = [];
            $skippedAssignments = 0;

            if ($validatedData['assignment_type'] === 'user') {
                $userIds = array_values(array_unique(array_map(
                    'intval',
                    is_array($validatedData['user_ids']) ? $validatedData['user_ids'] : [$validatedData['user_ids']]
                )));

                foreach ($userIds as $userId) {
                    $selectedUser = \App\Models\Organisation\User::query()
                        ->where('id', $userId)
                        ->where('company_id', auth()->user()->company_id)
                        ->whereIn('role', ['employee', 'admin'])
                        ->where('is_active', true)
                        ->first();

                    if (!$selectedUser) {
                        throw ValidationException::withMessages([
                            'user_ids' => 'Een of meer geselecteerde medewerkers zijn ongeldig voor jouw bedrijf.',
                        ]);
                    }

                    if ($list->location_id && (int) $selectedUser->location_id !== (int) $list->location_id) {
                        throw ValidationException::withMessages([
                            'user_ids' => $selectedUser->name . ' hoort niet bij de locatie van deze takenlijst.',
                        ]);
                    }

                    $existingAssignment = \App\Models\Checklist\ListAssignment::where('list_id', $list->id)
                        ->where('user_id', $userId)
                        ->where('is_active', true)
                        ->first();

                    if (!$existingAssignment) {
                        $assignment = \App\Models\Checklist\ListAssignment::create([
                            'list_id' => $list->id,
                            'user_id' => $userId,
                            'department' => null,
                            'assigned_date' => $validatedData['assigned_date'],
                            'due_date' => $validatedData['due_date'] ?? null,
                            'is_active' => true,
                        ]);
                        $assignments[] = $assignment;
                        if ($selectedUser->isEmployee()) {
                            \App\Models\Communication\Notification::createListAssigned(
                                (int) $selectedUser->id,
                                (int) $list->id,
                                (string) $list->title,
                                'user'
                            );
                        }
                        \Log::info('Created user assignment', ['assignment_id' => $assignment->id, 'user_id' => $userId]);
                    } else {
                        $skippedAssignments++;
                        \Log::info('Skipped duplicate user assignment', ['user_id' => $userId]);
                    }
                }
            } elseif ($validatedData['assignment_type'] === 'department') {
                // Check if department assignment already exists
                $existingAssignment = \App\Models\Checklist\ListAssignment::where('list_id', $list->id)
                    ->where('department', $validatedData['department'])
                    ->where('is_active', true)
                    ->first();

                if (!$existingAssignment) {
                    $assignment = \App\Models\Checklist\ListAssignment::create([
                        'list_id' => $list->id,
                        'user_id' => null,
                        'department' => $validatedData['department'],
                        'assigned_date' => $validatedData['assigned_date'],
                        'due_date' => $validatedData['due_date'] ?? null,
                        'is_active' => true,
                    ]);
                    $assignments[] = $assignment;

                    $departmentUsers = \App\Models\Organisation\User::query()
                        ->where('company_id', auth()->user()->company_id)
                        ->where('role', 'employee')
                        ->where('is_active', true)
                        ->where('department', $validatedData['department'])
                        ->when($list->location_id, fn ($q) => $q->where('location_id', $list->location_id))
                        ->get(['id']);

                    foreach ($departmentUsers as $departmentUser) {
                        \App\Models\Communication\Notification::createListAssigned(
                            (int) $departmentUser->id,
                            (int) $list->id,
                            (string) $list->title,
                            'department'
                        );
                    }

                    \Log::info('Created department assignment', ['assignment_id' => $assignment->id, 'department' => $validatedData['department']]);
                } else {
                    $skippedAssignments++;
                    \Log::info('Skipped duplicate department assignment', ['department' => $validatedData['department']]);
                }
            }

            $message = 'Takenlijst succesvol toegewezen aan ' . count($assignments) . ' toewijzing(en).';
            if ($skippedAssignments > 0) {
                $message .= ' ' . $skippedAssignments . ' duplicaat toewijzing(en) overgeslagen.';
            }

            if (count($assignments) > 0) {
                $company = auth()->user()->company;
                if ($company) {
                    $onboardingJustCompleted = app(\App\Services\Platform\AdminOnboardingService::class)
                        ->handleAssignmentCreated($company, (int) $list->id);

                    if ($onboardingJustCompleted) {
                        if (request()->ajax() || request()->wantsJson()) {
                            return response()->json([
                                'success' => true,
                                'message' => $message,
                                'assignments_created' => count($assignments),
                                'assignments_skipped' => $skippedAssignments,
                                'onboarding_completed' => true,
                                'redirect' => route('admin.lists.show', $list),
                            ]);
                        }

                        return redirect()->route('admin.lists.show', $list)
                            ->with('onboarding_completed', [
                                'list_title' => $list->title,
                                'list_id' => $list->id,
                            ]);
                    }
                }
            }

            // Check if it's an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'assignments_created' => count($assignments),
                    'assignments_skipped' => $skippedAssignments
                ]);
            }

            return redirect()->route('admin.lists.show', $list)
                ->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Assignment validation failed', ['errors' => $e->errors()]);
            
            // Check if it's an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validatie mislukt. Controleer je invoer.',
                    'errors' => $e->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Validatie mislukt. Controleer je invoer.');
        } catch (\Exception $e) {
            \Log::error('Assignment failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            // Check if it's an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Er is een fout opgetreden bij het toewijzen van de lijst: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het toewijzen van de lijst: ' . $e->getMessage());
        }
    }

    public function removeAssignment(ListAssignment $assignment)
    {
        try {
            \Log::info('Removing assignment', ['assignment_id' => $assignment->id]);
            
            $assignment->update(['is_active' => false]);
            
            // Check if it's an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Toewijzing succesvol verwijderd.'
                ]);
            }
            
            return redirect()->back()
                ->with('success', 'Toewijzing succesvol verwijderd.');
                
        } catch (\Exception $e) {
            \Log::error('Failed to remove assignment', [
                'assignment_id' => $assignment->id,
                'error' => $e->getMessage()
            ]);
            
            // Check if it's an AJAX request
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Er is een fout opgetreden bij het verwijderen van de toewijzing: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Er is een fout opgetreden bij het verwijderen van de toewijzing: ' . $e->getMessage());
        }
    }

    public function showSubmission(\App\Models\Submissions\Submission $submission)
    {
        $submission->load(['user', 'taskList.assignments', 'submissionTasks.task', 'submissionTasks.completedBy', 'submissionTasks.correctiveActionOwner', 'submissionTasks.verifier', 'submissionTasks.auditEvents.actor']);
        $correctiveActionOwners = \App\Models\Organisation\User::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)->orderBy('name')->get(['id', 'name']);
        
        return view('admin.submissions.show', compact('submission', 'correctiveActionOwners'));
    }

    public function aiReviewSubmission(Request $request, \App\Models\Submissions\Submission $submission, \App\Services\Ai\SubmissionReviewService $ai)
    {
        try {
            if (!$ai->isEnabled()) {
                return redirect()->back()
                    ->with('error', 'AI-review is niet geconfigureerd. Voeg een OPENAI_API_KEY toe aan je .env bestand.');
            }

            $review = $ai->review($submission);

            $metadata = $submission->metadata ?? [];
            $metadata['ai_review'] = [
                'overall_status' => $review['overall_status'],
                'summary' => $review['summary'],
                'missing_required_tasks' => $review['missing_required_tasks'],
                'notes' => $review['notes'],
                'model' => $review['_model'] ?? null,
                'ran_at' => now()->toIso8601String(),
            ];

            $submission->update(['metadata' => $metadata]);

            return redirect()->back()
                ->with('success', 'AI-review is uitgevoerd.');
        } catch (\Throwable $e) {
            \Log::error('AI submission review failed', [
                'submission_id' => $submission->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'AI-review is mislukt: ' . $e->getMessage());
        }
    }

    public function reviewSubmission(Request $request, \App\Models\Submissions\Submission $submission)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:approved,rejected,needs_revision',
            'admin_notes' => 'nullable|string',
        ]);

        $submission->update([
            'status' => $validatedData['status'],
            'admin_notes' => $validatedData['admin_notes'],
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Inzending succesvol beoordeeld.');
    }

    public function rejectTask(Request $request, \App\Models\Submissions\SubmissionTask $submissionTask)
    {
        $validatedData = $request->validate([
            'rejection_reason' => 'required|string',
            'corrective_action' => 'required|string|max:2000',
            'corrective_action_owner_id' => ['required', Rule::exists('users', 'id')->where(fn ($query) => $query->where('company_id', auth()->user()->company_id))],
            'corrective_action_due_at' => 'required|date|after_or_equal:today',
        ]);

        $notification = DB::transaction(function () use ($submissionTask, $validatedData) {
            // "Afkeuren" betekent direct: taak opnieuw laten uitvoeren.
            $submissionTask->update([
                'status' => 'pending',
                'rejection_reason' => $validatedData['rejection_reason'],
                'corrective_action' => $validatedData['corrective_action'],
                'corrective_action_owner_id' => $validatedData['corrective_action_owner_id'],
                'corrective_action_due_at' => $validatedData['corrective_action_due_at'],
                'corrective_action_completed_at' => null,
                'verification_note' => null,
                'verified_by' => null,
                'verified_at' => null,
                'rejected_at' => now(),
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'redo_requested' => false,
                'redo_reason' => null,
            ]);

            $created = Notification::createTaskRejected(
                app(\App\Services\CollaborativeSubmissionService::class)->notifyUserIdForTask($submissionTask),
                $submissionTask->task->title,
                $validatedData['rejection_reason'],
                $submissionTask->submission_id
            );

            // Safety net: if model-level helper failed to return/create, create notification here as backup.
            if (!$created) {
                $created = Notification::createTaskRejected(
                    app(\App\Services\CollaborativeSubmissionService::class)->notifyUserIdForTask($submissionTask),
                    $submissionTask->task->title,
                    $validatedData['rejection_reason'],
                    $submissionTask->submission_id
                );
            }

            // Bij afwijzen terug naar in_progress zodat medewerker opnieuw uitvoert en opnieuw indient.
            $submissionTask->submission->update(['status' => 'in_progress']);

            return $created;
        });

        // Check if it's an AJAX request
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Taak afgekeurd. Medewerker moet de taak opnieuw uitvoeren en daarna de checklist opnieuw indienen.',
                'notification_id' => $notification->id ?? null,
                'notification_user_id' => $notification->user_id ?? null,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Taak afgekeurd. Medewerker moet de taak opnieuw uitvoeren en daarna de checklist opnieuw indienen.');
    }

    public function requestRedo(Request $request, \App\Models\Submissions\SubmissionTask $submissionTask)
    {
        $validatedData = $request->validate([
            'redo_reason' => 'nullable|string',
        ]);

        $notification = DB::transaction(function () use ($submissionTask, $validatedData) {
            $created = $submissionTask->requestRedo(auth()->id(), $validatedData['redo_reason']);

            if (!$created) {
                $created = Notification::createRedoRequested(
                    $submissionTask->submission->user_id,
                    $submissionTask->task->title,
                    $submissionTask->submission_id,
                    $validatedData['redo_reason'] ?? null
                );
            }

            return $created;
        });

        // Check if it's an AJAX request
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Opnieuw doen aangevraagd. De medewerker kan deze taak opnieuw uitvoeren en is op de hoogte gebracht.',
                'notification_id' => $notification->id ?? null,
                'notification_user_id' => $notification->user_id ?? null,
            ]);
        }

        return redirect()->back()
            ->with('success', 'Opnieuw doen aangevraagd. De medewerker kan deze taak opnieuw uitvoeren en is op de hoogte gebracht.');
    }

    public function approveTask(Request $request, \App\Models\Submissions\SubmissionTask $submissionTask)
    {
        $rules = [
            'manager_comment' => 'nullable|string',
            'verification_note' => 'nullable|string|max:2000',
            'confirm_corrective_action_closed' => 'boolean',
        ];
        if ($submissionTask->corrective_action) {
            $rules['verification_note'] = 'required|string|max:2000';
            $rules['confirm_corrective_action_closed'] = 'required|accepted';
        }
        $validatedData = $request->validate($rules);

        $submissionTask->approve(auth()->id(), $validatedData['manager_comment'] ?? null);
        if ($submissionTask->corrective_action) {
            $submissionTask->update([
                'corrective_action_completed_at' => now(),
                'verification_note' => $validatedData['verification_note'],
                'verified_by' => auth()->id(),
                'verified_at' => now(),
            ]);
        }

        // Als alle taken zijn goedgekeurd, zet de inzending op 'reviewed'
        $this->updateSubmissionStatusIfAllTasksReviewed($submissionTask->submission);

        // Check if it's an AJAX request
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Taak succesvol goedgekeurd.',
            ]);
        }

        return redirect()->back()
            ->with('success', 'Taak succesvol goedgekeurd.');
    }

    public function approveAllTasks(Request $request, \App\Models\Submissions\Submission $submission)
    {
        $submission->loadMissing('taskList');
        $wantsJson = $request->ajax() || $request->expectsJson();

        if (! $submission->taskList?->requires_review) {
            $message = 'Deze takenlijst hoeft niet gecontroleerd te worden.';
            if ($wantsJson) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }

            return redirect()->back()->with('error', $message);
        }

        $approvedCount = DB::transaction(function () use ($submission) {
            $tasks = $submission->submissionTasks()
                ->where('status', 'completed')
                // Corrigerende acties vragen om een expliciete verificatie en kunnen
                // daarom niet veilig via de bulkactie worden afgesloten.
                ->whereNull('corrective_action')
                ->lockForUpdate()
                ->get();

            foreach ($tasks as $task) {
                $task->approve(auth()->id());
            }

            $this->updateSubmissionStatusIfAllTasksReviewed($submission);

            return $tasks->count();
        });

        if ($approvedCount === 0) {
            $message = 'Er zijn geen taken die in één keer goedgekeurd kunnen worden.';
            if ($wantsJson) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }

            return redirect()->back()->with('error', $message);
        }

        $message = $approvedCount === 1
            ? '1 taak succesvol goedgekeurd.'
            : "{$approvedCount} taken succesvol goedgekeurd.";

        if ($wantsJson) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'approved_count' => $approvedCount,
                'submission_status' => $submission->fresh()->status,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Update submission status when all tasks have been reviewed (approved or rejected).
     * If all approved -> reviewed. If any rejected -> rejected.
     */
    protected function updateSubmissionStatusIfAllTasksReviewed(\App\Models\Submissions\Submission $submission): void
    {
        $submission->load('submissionTasks');
        $tasks = $submission->submissionTasks;
        if ($tasks->isEmpty()) {
            return;
        }

        $reviewedStatuses = ['approved', 'rejected'];
        $allReviewed = $tasks->every(fn ($t) => in_array($t->status, $reviewedStatuses));
        if (!$allReviewed) {
            return;
        }

        $hasRejected = $tasks->contains('status', 'rejected');
        $submission->update(['status' => $hasRejected ? 'rejected' : 'reviewed']);
    }

    public function weeklyOverview(Request $request, WeeklyOverviewService $weeklyOverviewService)
    {
        $this->ensurePlanFeatureAvailable('weekly_overview');

        $startDate = $request->get('start_date', now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfWeek()->format('Y-m-d'));
        $companyId = auth()->user()->company_id;
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        if ($start->gt($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
            $startDate = $start->toDateString();
            $endDate = $end->toDateString();
        }

        $selectedLocationId = null;
        if ($request->filled('location_id')) {
            $candidateLocationId = (int) $request->get('location_id');
            $locationExists = Location::where('company_id', $companyId)->where('id', $candidateLocationId)->exists();
            if ($locationExists) {
                $selectedLocationId = $candidateLocationId;
            }
        }

        $locations = Location::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $reportLists = TaskList::where('company_id', $companyId)
            ->orderBy('title')
            ->get(['id', 'title']);

        $employees = \App\Models\Organisation\User::where('company_id', $companyId)
            ->where('role', 'employee')
            ->where('is_active', true)
            ->when($selectedLocationId, fn ($query) => $query->where('location_id', $selectedLocationId))
            ->orderBy('name')
            ->get();

        $overview = $weeklyOverviewService->buildEmployeeOverview($companyId, $start, $end, $selectedLocationId);

        $lists = TaskList::with(['assignments.user', 'tasks'])
            ->withCount(['submissions as period_submissions_count' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            }])
            ->where('company_id', $companyId)
            ->where('is_active', true)
            ->when($selectedLocationId, fn ($query) => $query->where('location_id', $selectedLocationId))
            ->whereHas('submissions', fn ($query) => $query->whereBetween('created_at', [$start, $end]))
            ->orderByDesc('period_submissions_count')
            ->orderBy('title')
            ->take(12)
            ->get();

        $summary = $weeklyOverviewService->buildSummary($companyId, $start, $end, $selectedLocationId);
        $chartData = $weeklyOverviewService->buildChartData($companyId, $start, $end, $selectedLocationId);
        $summary['active_employees'] = count($overview);
        $summary['total_employees'] = $employees->count();
        $summary['avg_lists_per_employee'] = $summary['active_employees'] > 0
            ? round($summary['total_lists'] / $summary['active_employees'], 1)
            : 0;

        return view('admin.lists.weekly-overview', compact(
            'lists',
            'overview',
            'startDate',
            'endDate',
            'locations',
            'selectedLocationId',
            'summary',
            'chartData',
            'reportLists',
        ));
    }

    private function ensurePlanFeatureAvailable(string $feature): void
    {
        $company = auth()->user()->company;
        $plan = $company?->subscription_plan ?: 'starter';

        if ($feature === 'ai' && $plan === 'starter') {
            abort(403, 'AI-import is beschikbaar vanaf Professional.');
        }

        if ($feature === 'weekly_overview' && $plan === 'starter') {
            abort(403, 'Rapportages zijn beschikbaar vanaf Professional.');
        }
    }

    public function createDailySubLists(Request $request, TaskList $list)
    {
        // This method would create daily sublists for weekly lists
        // Implementation depends on your specific business logic
        
        return redirect()->back()
            ->with('success', 'Dagelijkse sublijsten succesvol aangemaakt.');
    }

    public function createDayList(Request $request, TaskList $list)
    {
        $validatedData = $request->validate([
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ]);

        // Create a day-specific sublist
        $dayList = TaskList::create([
            'title' => $list->title . ' - ' . ucfirst($validatedData['day']),
            'description' => $list->description,
            'category' => $list->category,
            'priority' => $list->priority,
            'schedule_type' => 'once',
            'parent_list_id' => $list->id,
            'created_by' => auth()->id(),
            'is_active' => true,
            'schedule_config' => ['day' => $validatedData['day']],
            'location_id' => $list->location_id,
        ]);

        return redirect()->route('admin.lists.show', $dayList)
            ->with('success', 'Daglijst succesvol aangemaakt.');
    }

    public function syncTemplate(TaskList $list)
    {
        if ($list->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }

        if (!$list->template_id) {
            return redirect()->route('admin.lists.show', $list)
                ->with('error', 'Deze takenlijst is niet gekoppeld aan een template.');
        }

        $template = TaskTemplate::withoutGlobalScopes()
            ->where('id', $list->template_id)
            ->where('company_id', auth()->user()->company_id)
            ->with('templateTasks')
            ->first();

        if (!$template) {
            return redirect()->route('admin.lists.show', $list)
                ->with('error', 'Gekoppeld template niet gevonden.');
        }

        DB::transaction(function () use ($list, $template) {
            $templateTasks = $template->templateTasks()->orderBy('sort_order')->get();
            $matchedTaskIds = [];
            $templateOrderIndexes = $templateTasks->pluck('sort_order')->filter()->values()->all();

            $list->load('tasks');

            foreach ($templateTasks as $tt) {
                $task = $list->tasks->firstWhere('order_index', $tt->sort_order)
                    ?? $list->tasks->firstWhere('title', $tt->title);

                $payload = [
                    'title' => $tt->title,
                    'description' => $tt->description,
                    'instructions' => $tt->instructions,
                    'required_proof_type' => $tt->required_proof_type,
                    'is_required' => $tt->is_required,
                    'checklist_items' => $tt->checklist_items,
                    'attachments' => $tt->attachments,
                    'validation_rules' => $tt->validation_rules,
                    'start_time' => $tt->start_time,
                    'end_time' => $tt->end_time,
                    'order_index' => $tt->sort_order,
                ];

                if ($task) {
                    $task->update($payload);
                } else {
                    $payload['list_id'] = $list->id;
                    $payload['created_by'] = auth()->id();
                    $task = Task::create($payload);
                }

                $matchedTaskIds[] = $task->id;
            }

            $toDelete = $list->tasks->filter(function ($task) use ($templateOrderIndexes, $matchedTaskIds) {
                if (in_array($task->id, $matchedTaskIds, true)) {
                    return false;
                }

                if ($task->order_index !== null && in_array($task->order_index, $templateOrderIndexes, true)) {
                    return false;
                }

                return true;
            });

            foreach ($toDelete as $task) {
                $task->delete();
            }
        });

        return redirect()->route('admin.lists.show', $list)
            ->with('success', 'Takenlijst is opnieuw gesynchroniseerd met het template.');
    }

    private function normalizeScheduleConfig(string $scheduleType, array $config, ?array $selectedDays = null): array
    {
        $normalized = [];

        foreach (['time_slots', 'default_time_slot'] as $key) {
            if (array_key_exists($key, $config)) {
                $normalized[$key] = $config[$key];
            }
        }

        return match ($scheduleType) {
            'daily' => array_merge($normalized, [
                'show_on_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
            ]),
            'weekly' => array_merge($normalized, [
                'show_on_days' => array_values(array_unique($selectedDays ?? [])),
            ]),
            'monthly' => array_merge($normalized, [
                'day_of_month' => max(1, min(31, (int) ($config['day_of_month'] ?? 1))),
            ]),
            default => $normalized,
        };
    }
}

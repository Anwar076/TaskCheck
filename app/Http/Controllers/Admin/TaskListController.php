<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checklist\Task;
use App\Models\Checklist\TaskList;
use App\Models\Checklist\TaskTemplate;
use App\Models\Organisation\Company;
use App\Models\Organisation\Location;
use App\Services\Admin\ListCalendarService;
use App\Services\Admin\WeeklyOverviewService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
            'auto_accept_without_review' => 'boolean',
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
        $validatedData['auto_accept_without_review'] = ! $validatedData['requires_review']
            && $request->boolean('auto_accept_without_review');

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
        if (! empty($usedTemplateId)) {
            $template = \App\Models\Checklist\TaskTemplate::find($usedTemplateId);
            if ($template) {
                foreach ($template->templateTasks as $templateTask) {
                    \App\Models\Checklist\Task::create([
                        'list_id' => $taskList->id,
                        'title' => $templateTask->title,
                        'description' => $templateTask->description,
                        'instructions' => $templateTask->instructions,
                        'checklist_items' => $templateTask->checklist_items,
                        'required_proof_type' => $templateTask->required_proof_type,
                        'is_required' => $templateTask->is_required,
                        'attachments' => $templateTask->attachments,
                        'validation_rules' => $templateTask->validation_rules,
                        'start_time' => $templateTask->start_time,
                        'end_time' => $templateTask->end_time,
                        'order_index' => $templateTask->sort_order,
                        'created_by' => auth()->id(),
                        'weekday' => null,
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
                    if (! is_array($taskData)) {
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
            ->with('success', 'Takenlijst succesvol aangemaakt!'.($usedTemplateId ? ' Taken uit template zijn toegevoegd.' : '').($aiTasksRaw ? ' AI-voorgestelde taken zijn toegevoegd.' : ''));
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
            ->when($companyId !== null, fn ($q) => $q->where('company_id', $companyId))
            ->whereIn('role', ['employee', 'admin'])
            ->where('is_active', true)
            ->when($list->location_id, fn ($q) => $q->where('location_id', $list->location_id))
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
            'auto_accept_without_review' => 'boolean',
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
        $validatedData['auto_accept_without_review'] = ! $validatedData['requires_review']
            && $request->boolean('auto_accept_without_review');

        // Update the task list
        $list->update($validatedData);

        return redirect()->route('admin.lists.show', $list)
            ->with('success', 'Takenlijst succesvol bijgewerkt!');
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
                'child_lists' => $childListsCount,
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
                    'message' => 'Takenlijst succesvol verwijderd.',
                ]);
            }

            return redirect()->route('admin.lists.index')->with('success', 'Takenlijst succesvol verwijderd.');

        } catch (\Exception $e) {
            \Log::error('Failed to delete task list', [
                'list_id' => $list->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Check if it's an AJAX request
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Er is een fout opgetreden bij het verwijderen: '.$e->getMessage(),
                ], 500);
            }

            return redirect()->route('admin.lists.index')
                ->with('error', 'Er is een fout opgetreden bij het verwijderen: '.$e->getMessage());
        }
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
        if ($feature === 'ai' && ! $company?->hasPlanFeature('ai_import')) {
            abort(403, 'AI-import is beschikbaar vanaf Professional.');
        }

        if ($feature === 'weekly_overview' && ! $company?->hasPlanFeature('reports')) {
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
            'title' => $list->title.' - '.ucfirst($validatedData['day']),
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

        if (! $list->template_id) {
            return redirect()->route('admin.lists.show', $list)
                ->with('error', 'Deze takenlijst is niet gekoppeld aan een template.');
        }

        $template = TaskTemplate::withoutGlobalScopes()
            ->where('id', $list->template_id)
            ->where('company_id', auth()->user()->company_id)
            ->with('templateTasks')
            ->first();

        if (! $template) {
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

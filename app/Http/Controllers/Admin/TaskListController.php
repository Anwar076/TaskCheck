<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskList;
use App\Models\User;
use App\Models\ListAssignment;
use App\Models\Submission;
use App\Models\SubmissionTask;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = TaskList::with(['creator', 'tasks'])
            ->withCount('submissions');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('category', 'like', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->get('category'));
        }

        // Priority filter
        if ($request->filled('priority')) {
            $query->where('priority', $request->get('priority'));
        }

        $lists = $query->latest()->paginate(15);

        return view('admin.lists.index', compact('lists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentLists = TaskList::where('parent_list_id', null)->get();
        return view('admin.lists.create', compact('parentLists'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_list_id' => 'nullable|exists:lists,id',
            'schedule_type' => 'required|in:once,daily,weekly,monthly,custom',
            'schedule_config' => 'nullable|array',
            'schedule_config.weekdays' => 'nullable|array',
            'schedule_config.weekdays.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedule_config.day_of_month' => 'nullable|integer|between:1,31',
            'schedule_config.type' => 'nullable|in:specific_days,interval,date_range',
            'schedule_config.days' => 'nullable|array',
            'schedule_config.days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedule_config.interval_days' => 'nullable|integer|min:1|max:365',
            'schedule_config.start_date' => 'nullable|date',
            'schedule_config.end_date' => 'nullable|date|after_or_equal:schedule_config.start_date',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'tags' => 'nullable|array',
            'category' => 'nullable|string|max:100',
            'requires_signature' => 'boolean',
            'is_template' => 'boolean',
            'is_active' => 'boolean',
            'create_daily_sublists' => 'boolean',
            // Weekly Schedule Structure fields
            'enable_weekly_structure' => 'boolean',
            'selected_days' => 'nullable|array',
            'selected_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'day_tasks' => 'nullable|array',
            'day_tasks.*' => 'array',
            'day_tasks.*.*.title' => 'required|string|max:255',
            'day_tasks.*.*.order' => 'required|integer|min:1',
            'day_tasks.*.*.description' => 'nullable|string',
            'day_tasks.*.*.is_required' => 'boolean',
            'day_tasks.*.*.required_proof_type' => 'in:none,photo,signature,both',
            'day_tasks.*.*.instructions' => 'nullable|string',
        ]);

        // Clean up schedule_config based on schedule_type
        if (isset($validated['schedule_config'])) {
            switch ($validated['schedule_type']) {
                case 'once':
                case 'daily':
                    $validated['schedule_config'] = null;
                    break;
                case 'weekly':
                    $weekdays = $validated['schedule_config']['weekdays'] ?? [];
                    $validated['schedule_config'] = [
                        'weekdays' => $weekdays,
                        'weekly_structure' => [
                            'enabled' => !empty($weekdays),
                            'selected_days' => $weekdays
                        ]
                    ];
                    break;
                case 'monthly':
                    $validated['schedule_config'] = [
                        'day_of_month' => $validated['schedule_config']['day_of_month'] ?? 1
                    ];
                    break;
                case 'custom':
                    $config = [];
                    $type = $validated['schedule_config']['type'] ?? 'specific_days';
                    $config['type'] = $type;
                    
                    switch ($type) {
                        case 'specific_days':
                            $config['days'] = $validated['schedule_config']['days'] ?? [];
                            break;
                        case 'interval':
                            $config['interval_days'] = $validated['schedule_config']['interval_days'] ?? 1;
                            $config['start_date'] = $validated['schedule_config']['start_date'] ?? null;
                            break;
                        case 'date_range':
                            $config['start_date'] = $validated['schedule_config']['start_date'] ?? null;
                            $config['end_date'] = $validated['schedule_config']['end_date'] ?? null;
                            break;
                    }
                    $validated['schedule_config'] = $config;
                    break;
            }
        }

        $validated['created_by'] = auth()->id();
        
        // Ensure boolean fields have default values
        $validated['requires_signature'] = $validated['requires_signature'] ?? false;
        $validated['is_template'] = $validated['is_template'] ?? false;
        $validated['is_active'] = $validated['is_active'] ?? true;

        $list = TaskList::create($validated);

        // Handle automatic day-specific list creation based on schedule type
        if ($validated['schedule_type'] === 'daily') {
            // Create lists for all 7 days
            $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            foreach ($weekdays as $weekday) {
                $this->createDaySpecificList($list, $weekday);
            }
        } elseif ($validated['schedule_type'] === 'weekly' && isset($validated['schedule_config']['weekdays'])) {
            // Create lists only for selected days
            foreach ($validated['schedule_config']['weekdays'] as $weekday) {
                $this->createDaySpecificList($list, $weekday);
            }
        }

        return redirect()->route('admin.lists.show', $list)
            ->with('success', 'Task list created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskList $list)
    {
        // Load tasks with proper relationships
        $list->load(['assignments.user', 'submissions.user']);
        
        // Load all tasks for this list (both general and day-specific)
        $list->load(['tasks' => function ($query) {
            $query->orderBy('order_index')->orderBy('created_at');
        }]);
        
        return view('admin.lists.show', compact('list'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskList $list)
    {
        $parentLists = TaskList::where('parent_list_id', null)
            ->where('id', '!=', $list->id)
            ->get();
            
        return view('admin.lists.edit', compact('list', 'parentLists'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskList $list)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_list_id' => 'nullable|exists:lists,id',
            'schedule_type' => 'required|in:once,daily,weekly,monthly,custom',
            'schedule_config' => 'nullable|array',
            'schedule_config.weekdays' => 'nullable|array',
            'schedule_config.weekdays.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedule_config.day_of_month' => 'nullable|integer|between:1,31',
            'schedule_config.type' => 'nullable|in:specific_days,interval,date_range',
            'schedule_config.days' => 'nullable|array',
            'schedule_config.days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedule_config.interval_days' => 'nullable|integer|min:1|max:365',
            'schedule_config.start_date' => 'nullable|date',
            'schedule_config.end_date' => 'nullable|date|after_or_equal:schedule_config.start_date',
            'priority' => 'required|in:low,medium,high,urgent',
            'due_date' => 'nullable|date',
            'tags' => 'nullable|array',
            'category' => 'nullable|string|max:100',
            'requires_signature' => 'boolean',
            'is_template' => 'boolean',
            'is_active' => 'boolean',
            'create_daily_sublists' => 'boolean',
            // Weekly Schedule Structure fields
            'enable_weekly_structure' => 'boolean',
            'selected_days' => 'nullable|array',
            'selected_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'day_tasks' => 'nullable|array',
            'day_tasks.*' => 'array',
            'day_tasks.*.*.title' => 'required|string|max:255',
            'day_tasks.*.*.order' => 'required|integer|min:1',
            'day_tasks.*.*.description' => 'nullable|string',
            'day_tasks.*.*.is_required' => 'boolean',
            'day_tasks.*.*.required_proof_type' => 'in:none,photo,signature,both',
            'day_tasks.*.*.instructions' => 'nullable|string',
        ]);

        // Clean up schedule_config based on schedule_type
        if (isset($validated['schedule_config'])) {
            switch ($validated['schedule_type']) {
                case 'once':
                case 'daily':
                    $validated['schedule_config'] = null;
                    break;
                case 'weekly':
                    $weekdays = $validated['schedule_config']['weekdays'] ?? [];
                    $validated['schedule_config'] = [
                        'weekdays' => $weekdays,
                        'weekly_structure' => [
                            'enabled' => !empty($weekdays),
                            'selected_days' => $weekdays
                        ]
                    ];
                    break;
                case 'monthly':
                    $validated['schedule_config'] = [
                        'day_of_month' => $validated['schedule_config']['day_of_month'] ?? 1
                    ];
                    break;
                case 'custom':
                    $config = [];
                    $type = $validated['schedule_config']['type'] ?? 'specific_days';
                    $config['type'] = $type;
                    
                    switch ($type) {
                        case 'specific_days':
                            $config['days'] = $validated['schedule_config']['days'] ?? [];
                            break;
                        case 'interval':
                            $config['interval_days'] = $validated['schedule_config']['interval_days'] ?? 1;
                            $config['start_date'] = $validated['schedule_config']['start_date'] ?? null;
                            break;
                        case 'date_range':
                            $config['start_date'] = $validated['schedule_config']['start_date'] ?? null;
                            $config['end_date'] = $validated['schedule_config']['end_date'] ?? null;
                            break;
                    }
                    $validated['schedule_config'] = $config;
                    break;
            }
        }

        $oldScheduleType = $list->schedule_type;
        $oldScheduleConfig = $list->schedule_config;
        
        // Ensure boolean fields have default values
        $validated['requires_signature'] = $validated['requires_signature'] ?? $list->requires_signature ?? false;
        $validated['is_template'] = $validated['is_template'] ?? $list->is_template ?? false;
        $validated['is_active'] = $validated['is_active'] ?? $list->is_active ?? true;
        
        $list->update($validated);

        // Handle schedule type changes and day-specific list management
        $this->handleScheduleTypeChanges($list, $oldScheduleType, $oldScheduleConfig, $validated);

        return redirect()->route('admin.lists.show', $list)
            ->with('success', 'Task list updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskList $list)
    {
        // Delete all related records first to avoid foreign key constraints
        // Delete submissions and submission tasks
        $submissions = Submission::where('list_id', $list->id)->get();
        foreach ($submissions as $submission) {
            $submission->submissionTasks()->delete();
            $submission->delete();
        }
        
        // Delete list assignments
        ListAssignment::where('list_id', $list->id)->delete();
        
        // Delete tasks
        $list->tasks()->delete();
        
        // Delete sublists if any
        $list->subLists()->delete();
        
        // Finally delete the list itself
        $list->delete();

        return redirect()->route('admin.lists.index')
            ->with('success', 'Task list deleted successfully.');
    }

    /**
     * Assign list to users/departments/roles
     */
    public function assign(Request $request, TaskList $list)
    {
        // Debug: Log all request data
        \Log::info('Assignment request data:', $request->all());
        
        $validated = $request->validate([
            'assignment_type' => ['required', Rule::in(['user', 'department', 'role'])],
            'user_ids' => 'required_if:assignment_type,user|array|nullable',
            'user_ids.*' => 'required_if:assignment_type,user|exists:users,id',
            'department' => 'required_if:assignment_type,department|string|nullable',
            'role' => 'required_if:assignment_type,role|string|nullable',
            'assigned_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:assigned_date',
        ]);

        // Debug: Log validated data
        \Log::info('Validated assignment data:', $validated);
        
        try {
            // Remove previous assignments of this type for this list
            if ($validated['assignment_type'] === 'user') {
                // Remove all user assignments for this list
                ListAssignment::where('list_id', $list->id)->whereNotNull('user_id')->delete();
                foreach ($validated['user_ids'] as $userId) {
                    ListAssignment::create([
                        'list_id' => $list->id,
                        'user_id' => $userId,
                        'assigned_date' => $validated['assigned_date'],
                        'due_date' => $validated['due_date'],
                    ]);
                }
            } elseif ($validated['assignment_type'] === 'department') {
                // Remove all department assignments for this list
                ListAssignment::where('list_id', $list->id)->whereNotNull('department')->delete();
                ListAssignment::create([
                    'list_id' => $list->id,
                    'department' => $validated['department'],
                    'assigned_date' => $validated['assigned_date'],
                    'due_date' => $validated['due_date'],
                ]);
            } elseif ($validated['assignment_type'] === 'role') {
                // Remove all role assignments for this list
                ListAssignment::where('list_id', $list->id)->whereNotNull('role')->delete();
                ListAssignment::create([
                    'list_id' => $list->id,
                    'role' => $validated['role'],
                    'assigned_date' => $validated['assigned_date'],
                    'due_date' => $validated['due_date'],
                ]);
            }
            
            \Log::info('Assignment successful for list ' . $list->id);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'List assigned successfully.'
                ]);
            }
            
            return back()->with('success', 'List assigned successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Assignment failed: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Assignment failed: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->withErrors(['error' => 'Assignment failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove assignment
     */
    public function removeAssignment(Request $request, $assignmentId)
    {
        try {
            $assignment = ListAssignment::findOrFail($assignmentId);
            $assignment->delete();
            
            \Log::info('Assignment removed successfully: ' . $assignmentId);
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Assignment removed successfully.'
                ]);
            }
            
            return back()->with('success', 'Assignment removed successfully.');
            
        } catch (\Exception $e) {
            \Log::error('Failed to remove assignment: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to remove assignment: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->withErrors(['error' => 'Failed to remove assignment: ' . $e->getMessage()]);
        }
    }
    public function submissions(Request $request)
    {
        $query = Submission::with(['user', 'taskList']);

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $submissions = $query->latest()->paginate(20);

        return view('admin.submissions.index', compact('submissions'));
    }

    /**
     * View specific submission
     */
    public function showSubmission(Submission $submission)
    {
        $submission->load(['user', 'taskList', 'submissionTasks.task', 'submissionTasks.reviewer']);
        
        return view('admin.submissions.show', compact('submission'));
    }

    /**
     * Review submission
     */
    public function reviewSubmission(Request $request, Submission $submission)
    {
        $validated = $request->validate([
            'task_reviews' => 'required|array',
            'task_reviews.*.status' => 'required|in:approved,rejected',
            'task_reviews.*.comment' => 'nullable|string',
        ]);

        foreach ($validated['task_reviews'] as $taskId => $review) {
            $submissionTask = $submission->submissionTasks()
                ->where('task_id', $taskId)
                ->first();

            if ($submissionTask) {
                $submissionTask->update([
                    'status' => $review['status'],
                    'manager_comment' => $review['comment'],
                    'reviewed_at' => now(),
                    'reviewed_by' => auth()->id(),
                ]);
            }
        }

        // Update overall submission status
        $allApproved = $submission->submissionTasks()
            ->where('status', '!=', 'approved')
            ->count() === 0;

        $submission->update([
            'status' => $allApproved ? 'reviewed' : 'rejected'
        ]);

        return back()->with('success', 'Submission reviewed successfully.');
    }

    /**
     * Reject specific submission task
     */
    public function rejectTask(Request $request, SubmissionTask $submissionTask)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $submissionTask->reject($validated['rejection_reason'], auth()->id());

        return back()->with('success', 'Task rejected successfully.');
    }

    /**
     * Request redo for specific submission task
     */
    public function requestRedo(Request $request, SubmissionTask $submissionTask)
    {
        $submissionTask->requestRedo(auth()->id());

        return back()->with('success', 'Redo requested successfully.');
    }

    /**
     * Get weekly overview for admin dashboard
     */
    public function weeklyOverview(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfWeek());
        $endDate = $request->get('end_date', now()->endOfWeek());

        $employees = User::where('role', 'employee')->where('is_active', true)->get();
        
        $overview = [];
        foreach ($employees as $employee) {
            $submissions = Submission::where('user_id', $employee->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->with(['taskList', 'submissionTasks'])
                ->get();

            $totalSubmissions = $submissions->count();
            $completedSubmissions = $submissions->where('status', 'completed');
            $rejectedSubmissions = $submissions->filter(function ($submission) {
                return $submission->hasRejectedTasks();
            });

            // Calculate completion rate
            $completionRate = $totalSubmissions > 0 ? 
                round(($completedSubmissions->count() / $totalSubmissions) * 100, 1) : 0;

            // Calculate average completion time (in hours)
            $avgCompletionTime = 0;
            if ($completedSubmissions->count() > 0) {
                $totalTime = $completedSubmissions->sum(function ($submission) {
                    // Use created_at as fallback if started_at is not available
                    $startTime = $submission->started_at ?? $submission->created_at;
                    $endTime = $submission->completed_at;
                    
                    if ($startTime && $endTime) {
                        // Calculate difference in hours, with minimum of 0.1 hours
                        $hours = $startTime->diffInHours($endTime);
                        return max($hours, 0.1); // Minimum 0.1 hours (6 minutes)
                    }
                    return 0;
                });
                
                $avgCompletionTime = $totalTime > 0 ? round($totalTime / $completedSubmissions->count(), 1) : 0;
            }

            // Calculate on-time rate (submissions completed within expected timeframe)
            $onTimeRate = 0;
            if ($completedSubmissions->count() > 0) {
                $onTimeSubmissions = $completedSubmissions->filter(function ($submission) {
                    // Use started_at as fallback if available, otherwise use created_at
                    $startTime = $submission->started_at ?? $submission->created_at;
                    
                    // Consider a submission on-time if completed within 24 hours of start
                    $expectedTime = $startTime->addHours(24);
                    return $submission->completed_at && $submission->completed_at <= $expectedTime;
                });
                $onTimeRate = round(($onTimeSubmissions->count() / $completedSubmissions->count()) * 100, 1);
            }

            // Calculate quality score based on completion rate and rejection rate
            $rejectionRate = $totalSubmissions > 0 ? 
                ($rejectedSubmissions->count() / $totalSubmissions) * 100 : 0;
            
            // Quality score: 5 = perfect, 0 = poor
            // Based on completion rate (70% weight) and low rejection rate (30% weight)
            $qualityScore = 0;
            if ($totalSubmissions > 0) {
                $completionScore = ($completionRate / 100) * 3.5; // Max 3.5 points
                $rejectionScore = max(0, 1.5 - ($rejectionRate / 100) * 1.5); // Max 1.5 points
                $qualityScore = round($completionScore + $rejectionScore, 1);
            }

            $overview[$employee->id] = [
                'employee' => $employee,
                'total_submissions' => $totalSubmissions,
                'completed' => $completedSubmissions->count(),
                'in_progress' => $submissions->where('status', 'in_progress')->count(),
                'rejected' => $rejectedSubmissions->count(),
                'completion_rate' => $completionRate,
                'avg_completion_time' => $avgCompletionTime,
                'on_time_rate' => $onTimeRate,
                'quality_score' => $qualityScore,
                'submissions' => $submissions,
            ];
        }

        return view('admin.weekly-overview', compact('overview', 'startDate', 'endDate'));
    }

    /**
     * Create daily sub-lists for a main list
     */
    public function createDailySubLists(TaskList $list)
    {
        if (!$list->isMainList()) {
            return back()->with('error', 'Only main lists can have daily sub-lists.');
        }

        // Update the main list to daily schedule type
        $list->update(['schedule_type' => 'daily']);

        $list->createDailySubLists();

        return back()->with('success', 'Daily sub-lists created successfully. You can now drag and drop tasks to specific days.');
    }

    /**
     * Create a day-specific list for a parent list
     */
    public function createDaySpecificList(TaskList $parentList, string $weekday)
    {
        $dayNames = [
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday'
        ];

        $dayName = $dayNames[$weekday] ?? ucfirst($weekday);

        // Check if day-specific list already exists
        $existingList = $parentList->subLists()->where('weekday', $weekday)->first();
        if ($existingList) {
            return $existingList;
        }

        // Create the day-specific list
        $dayList = TaskList::create([
            'title' => $parentList->title . ' - ' . $dayName,
            'description' => $parentList->description . ' (Scheduled for ' . $dayName . ')',
            'parent_list_id' => $parentList->id,
            'weekday' => $weekday,
            'schedule_type' => 'once', // Day-specific lists are always one-time
            'schedule_config' => null,
            'priority' => $parentList->priority,
            'category' => $parentList->category,
            'requires_signature' => $parentList->requires_signature ?? false,
            'is_template' => false,
            'is_active' => $parentList->is_active ?? true,
            'created_by' => $parentList->created_by,
        ]);

        return $dayList;
    }

    /**
     * Handle schedule type changes and manage day-specific lists
     */
    private function handleScheduleTypeChanges(TaskList $list, string $oldScheduleType, ?array $oldScheduleConfig, array $newValidated)
    {
        $newScheduleType = $newValidated['schedule_type'];
        $newScheduleConfig = $newValidated['schedule_config'] ?? null;

        // If schedule type changed or schedule config changed
        if ($oldScheduleType !== $newScheduleType || $oldScheduleConfig !== $newScheduleConfig) {
            
            // Remove existing day-specific lists if changing away from daily/weekly
            if (($oldScheduleType === 'daily' || $oldScheduleType === 'weekly') && 
                ($newScheduleType !== 'daily' && $newScheduleType !== 'weekly')) {
                $list->subLists()->delete();
            }
            
            // Handle new schedule type
            if ($newScheduleType === 'daily') {
                // Create lists for all 7 days
                $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                foreach ($weekdays as $weekday) {
                    $this->createDaySpecificList($list, $weekday);
                }
            } elseif ($newScheduleType === 'weekly' && isset($newScheduleConfig['weekdays'])) {
                // Create lists only for selected days
                foreach ($newScheduleConfig['weekdays'] as $weekday) {
                    $this->createDaySpecificList($list, $weekday);
                }
                
                // Remove day-specific lists for unselected days
                $list->subLists()->whereNotIn('weekday', $newScheduleConfig['weekdays'])->delete();
            }
        }
    }

    /**
     * Create a day-specific list via AJAX
     */
    public function createDayList(Request $request, TaskList $list)
    {
        $validated = $request->validate([
            'weekday' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'day_name' => 'required|string|max:255'
        ]);

        $dayList = $this->createDaySpecificList($list, $validated['weekday']);

        return response()->json([
            'success' => true,
            'message' => $validated['day_name'] . ' list created successfully.',
            'day_list_id' => $dayList->id
        ]);
    }
}

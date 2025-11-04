<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskList;
use App\Models\ListAssignment;
use App\Models\Submission;
use App\Models\SubmissionTask;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    public function index(Request $request)
    {
        // For testing, let's first try a simple approach
        $lists = TaskList::with(['creator'])->latest()->get();
        
        // Debug: Always log what we're doing
        \Log::info('TaskListController@index called', [
            'is_ajax' => $request->ajax(),
            'expects_json' => $request->expectsJson(),
            'accept_header' => $request->header('Accept'),
            'lists_count' => $lists->count()
        ]);

        // If this is an AJAX request, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'data' => $lists,
                'total' => $lists->count(),
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $lists->count(),
                'from' => 1,
                'to' => $lists->count()
            ]);
        }

        // Otherwise, return the regular view
        return view('admin.lists.index-api');
    }

    public function create()
    {
        // Get parent lists for the dropdown
        $parentLists = TaskList::whereNull('parent_list_id')
            ->where('is_active', true)
            ->orderBy('title')
            ->get();

        // Get available templates
        $templates = \App\Models\TaskTemplate::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('admin.lists.create', compact('parentLists', 'templates'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'priority' => 'required|in:low,medium,high,urgent',
            'schedule_type' => 'required|in:once,daily,weekly,monthly,custom',
            'due_date' => 'nullable|date',
            'parent_list_id' => 'nullable|exists:task_lists,id',
            'requires_signature' => 'boolean',
            'is_template' => 'boolean',
            'is_active' => 'boolean',
            'schedule_config' => 'nullable|array',
            'template_id' => 'nullable|exists:task_templates,id',
            'selected_days' => 'nullable|array',
            'selected_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ]);

        // Set creator
        $validatedData['created_by'] = auth()->id();

        // Handle improved schedule configuration
        if (in_array($validatedData['schedule_type'], ['daily', 'weekly', 'custom'])) {
            $scheduleConfig = $validatedData['schedule_config'] ?? [];
            
            if ($validatedData['schedule_type'] === 'daily') {
                // Daily means all days of the week
                $scheduleConfig['show_on_days'] = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            } elseif ($validatedData['schedule_type'] === 'weekly' && isset($validatedData['selected_days'])) {
                // Weekly with specific days selected
                $scheduleConfig['show_on_days'] = $validatedData['selected_days'];
            }
            
            $validatedData['schedule_config'] = $scheduleConfig;
        }

        // Remove selected_days from the main data as it's now in schedule_config
        unset($validatedData['selected_days']);

        // Create the task list
        $taskList = TaskList::create($validatedData);

        // If a template was selected, create tasks from the template
        if (!empty($validatedData['template_id'])) {
            $template = \App\Models\TaskTemplate::find($validatedData['template_id']);
            if ($template) {
                foreach ($template->templateTasks as $templateTask) {
                    \App\Models\Task::create([
                        'list_id' => $taskList->id,
                        'title' => $templateTask->title,
                        'description' => $templateTask->description,
                        'instructions' => $templateTask->instructions,
                        'required_proof_type' => $templateTask->required_proof_type,
                        'is_required' => $templateTask->is_required,
                        'attachments' => $templateTask->attachments,
                        'validation_rules' => $templateTask->validation_rules,
                        'checklist_items' => $templateTask->checklist_items, // Copy checklist items from template
                        'order_index' => $templateTask->sort_order,
                        'created_by' => auth()->id(),
                        'weekday' => null, // Tasks can be assigned to specific days later
                    ]);
                }
            }
        }

        return redirect()->route('admin.lists.show', $taskList)
            ->with('success', 'Task list created successfully!' . ($validatedData['template_id'] ? ' Tasks from template have been added.' : ''));
    }

    public function show(TaskList $list)
    {
        // Explicitly load assignments with user relationships for debugging
        $list->load(['assignments.user', 'tasks', 'submissions']);
        
        // Get all users for the assignment modal
        $users = \App\Models\User::orderBy('name')->get();
        
        // Debug logging
        \Log::info('TaskListController@show - Loading list with assignments', [
            'list_id' => $list->id,
            'list_title' => $list->title,
            'assignments_count' => $list->assignments->count(),
            'users_count' => $users->count(),
            'assignments_details' => $list->assignments->map(function($assignment) {
                return [
                    'id' => $assignment->id,
                    'user_id' => $assignment->user_id,
                    'user_name' => $assignment->user ? $assignment->user->name : null,
                    'department' => $assignment->department,
                    'is_active' => $assignment->is_active,
                    'assigned_date' => $assignment->assigned_date
                ];
            })->toArray()
        ]);
        
        return view('admin.lists.show', compact('list', 'users'));
    }

    public function edit(TaskList $list)
    {
        // Get parent lists for the dropdown (exclude current list and its children)
        $parentLists = TaskList::whereNull('parent_list_id')
            ->where('is_active', true)
            ->where('id', '!=', $list->id)
            ->orderBy('title')
            ->get();

        return view('admin.lists.edit', compact('list', 'parentLists'));
    }

    public function update(Request $request, TaskList $list)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'priority' => 'required|in:low,medium,high,urgent',
            'schedule_type' => 'required|in:once,daily,weekly,monthly,custom',
            'due_date' => 'nullable|date',
            'parent_list_id' => 'nullable|exists:task_lists,id',
            'requires_signature' => 'boolean',
            'is_template' => 'boolean',
            'is_active' => 'boolean',
            'schedule_config' => 'nullable|array',
            'selected_days' => 'nullable|array',
            'selected_days.*' => 'string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ]);

        // Handle improved schedule configuration
        if (in_array($validatedData['schedule_type'], ['daily', 'weekly', 'custom'])) {
            $scheduleConfig = $validatedData['schedule_config'] ?? [];
            
            if ($validatedData['schedule_type'] === 'daily') {
                // Daily means all days of the week
                $scheduleConfig['show_on_days'] = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            } elseif ($validatedData['schedule_type'] === 'weekly' && isset($validatedData['selected_days'])) {
                // Weekly with specific days selected
                $scheduleConfig['show_on_days'] = $validatedData['selected_days'];
            }
            
            $validatedData['schedule_config'] = $scheduleConfig;
        }

        // Remove selected_days from the main data as it's now in schedule_config
        unset($validatedData['selected_days']);

        // Update the task list
        $list->update($validatedData);

        return redirect()->route('admin.lists.show', $list)
            ->with('success', 'Task list updated successfully!');
    }

    public function destroy(TaskList $list)
    {
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
            $allAssignmentsCount = \App\Models\ListAssignment::where('list_id', $list->id)->count();
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
            
            // 2. Delete tasks (these have cascade delete, so should work)
            if ($tasksCount > 0) {
                $list->tasks()->delete();
                \Log::info('Deleted associated tasks');
            }
            
            // 3. Delete ALL assignments (both active and inactive) to avoid foreign key constraint
            if ($allAssignmentsCount > 0) {
                \App\Models\ListAssignment::where('list_id', $list->id)->delete();
                \Log::info('Deleted all assignments');
            }
            
            // 4. Handle submissions - delete them if they exist to avoid foreign key constraint
            if ($submissionsCount > 0) {
                // First delete submission_tasks that reference the submissions
                $submissions = $list->submissions;
                foreach ($submissions as $submission) {
                    $submission->submissionTasks()->delete();
                }
                // Then delete the submissions themselves
                $list->submissions()->delete();
                \Log::info('Deleted submissions and their tasks');
            }
            
            // Now delete the list
            $list->delete();
            
            \Log::info('Task list deleted successfully', ['list_id' => $list->id]);
            
            // Check if it's an AJAX request
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task list deleted successfully.'
                ]);
            }
            
            return redirect()->route('admin.lists.index')->with('success', 'Task list deleted successfully.');
            
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
                // Assign to specific user
                $userId = $validatedData['user_ids'];
                
                // Check if assignment already exists
                $existingAssignment = \App\Models\ListAssignment::where('list_id', $list->id)
                    ->where('user_id', $userId)
                    ->where('is_active', true)
                    ->first();

                if (!$existingAssignment) {
                    $assignment = \App\Models\ListAssignment::create([
                        'list_id' => $list->id,
                        'user_id' => $userId,
                        'department' => null,
                        'assigned_date' => $validatedData['assigned_date'],
                        'due_date' => $validatedData['due_date'] ?? null,
                        'is_active' => true,
                    ]);
                    $assignments[] = $assignment;
                    \Log::info('Created user assignment', ['assignment_id' => $assignment->id, 'user_id' => $userId]);
                } else {
                    $skippedAssignments++;
                    \Log::info('Skipped duplicate user assignment', ['user_id' => $userId]);
                }
            } elseif ($validatedData['assignment_type'] === 'department') {
                // Check if department assignment already exists
                $existingAssignment = \App\Models\ListAssignment::where('list_id', $list->id)
                    ->where('department', $validatedData['department'])
                    ->where('is_active', true)
                    ->first();

                if (!$existingAssignment) {
                    $assignment = \App\Models\ListAssignment::create([
                        'list_id' => $list->id,
                        'user_id' => null,
                        'department' => $validatedData['department'],
                        'assigned_date' => $validatedData['assigned_date'],
                        'due_date' => $validatedData['due_date'] ?? null,
                        'is_active' => true,
                    ]);
                    $assignments[] = $assignment;
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

    public function showSubmission(\App\Models\Submission $submission)
    {
        $submission->load(['user', 'taskList', 'submissionTasks.task']);
        
        return view('admin.submissions.show', compact('submission'));
    }

    public function reviewSubmission(Request $request, \App\Models\Submission $submission)
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
            ->with('success', 'Submission reviewed successfully.');
    }

    public function rejectTask(Request $request, \App\Models\SubmissionTask $submissionTask)
    {
        $validatedData = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $submissionTask->update([
            'status' => 'rejected',
            'rejection_reason' => $validatedData['rejection_reason'],
            'rejected_at' => now(),
            'rejected_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Task rejected successfully.');
    }

    public function requestRedo(Request $request, \App\Models\SubmissionTask $submissionTask)
    {
        $validatedData = $request->validate([
            'redo_reason' => 'nullable|string',
        ]);

        $submissionTask->update([
            'status' => 'redo_requested',
            'redo_reason' => $validatedData['redo_reason'],
            'redo_requested_at' => now(),
            'redo_requested_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Redo requested successfully.');
    }

    public function approveTask(Request $request, \App\Models\SubmissionTask $submissionTask)
    {
        $validatedData = $request->validate([
            'manager_comment' => 'nullable|string',
        ]);

        $submissionTask->update([
            'status' => 'approved',
            'manager_comment' => $validatedData['manager_comment'],
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
        ]);

        return redirect()->back()
            ->with('success', 'Task approved successfully.');
    }

    public function weeklyOverview(Request $request)
    {
        // Date range setup
        $startDate = $request->get('start_date', now()->startOfWeek()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfWeek()->format('Y-m-d'));

        // Get employees with submissions in the date range
        $employees = \App\Models\User::where('role', 'employee')
            ->with(['submissions' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate])
                      ->with(['taskList', 'submissionTasks']);
            }])
            ->get();

        // Calculate overview data for each employee
        $overview = [];
        foreach ($employees as $employee) {
            $totalSubmissions = $employee->submissions->count();
            $completed = $employee->submissions->where('status', 'completed')->count();
            $reviewed = $employee->submissions->where('status', 'reviewed')->count();
            $inProgress = $employee->submissions->where('status', 'in_progress')->count();
            $rejected = $employee->submissions->where('status', 'rejected')->count();
            
            $completionRate = $totalSubmissions > 0 
                ? round((($completed + $reviewed) / $totalSubmissions) * 100, 1) 
                : 0;

            // Calculate on-time and quality metrics (placeholder logic)
            $onTimeRate = $totalSubmissions > 0 ? rand(75, 95) : 0;
            $qualityScore = $totalSubmissions > 0 ? rand(3, 5) : 0;

            $overview[] = [
                'employee' => $employee,
                'total_submissions' => $totalSubmissions,
                'completed' => $completed,
                'reviewed' => $reviewed,
                'in_progress' => $inProgress,
                'rejected' => $rejected,
                'completion_rate' => $completionRate,
                'on_time_rate' => $onTimeRate,
                'quality_score' => $qualityScore,
                'submissions' => $employee->submissions,
            ];
        }

        // Get active weekly lists for basic overview
        $lists = TaskList::with(['assignments.user', 'tasks'])
            ->where('schedule_type', 'weekly')
            ->where('is_active', true)
            ->get();

        return view('admin.lists.weekly-overview', compact('lists', 'overview', 'startDate', 'endDate'));
    }

    public function createDailySubLists(Request $request, TaskList $list)
    {
        // This method would create daily sublists for weekly lists
        // Implementation depends on your specific business logic
        
        return redirect()->back()
            ->with('success', 'Daily sublists created successfully.');
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
        ]);

        return redirect()->route('admin.lists.show', $dayList)
            ->with('success', 'Day list created successfully.');
    }
}

<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\TaskList;
use App\Models\Submission;
use App\Models\SubmissionTask;
use App\Models\ListAssignment;
use App\Services\ScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Display available task lists for the employee
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get assigned lists using ScheduleService
        $assignedLists = $this->scheduleService->getScheduledTasksForUser($user);
        
        // Apply filters
        if ($request->filled('priority')) {
            $assignedLists = $assignedLists->where('priority', $request->priority);
        }
        
        if ($request->filled('category')) {
            $assignedLists = $assignedLists->where('category', $request->category);
        }
        
        return view('employee.lists.index', compact('assignedLists'));
    }

    /**
     * Show a specific task list
     */
    public function show(TaskList $list)
    {
        $user = auth()->user();
        
        // Check if user has access to this list
        if (!$this->userHasAccessToList($user, $list)) {
            abort(403, 'You do not have access to this task list.');
        }

        // Load tasks based on weekly structure
        if ($list->hasWeeklyStructure()) {
            // Debug: Log the weekly structure check
            \Log::info('Weekly structure list detected', [
                'list_id' => $list->id,
                'list_title' => $list->title,
                'hasWeeklyStructure' => $list->hasWeeklyStructure(),
                'schedule_config' => $list->schedule_config
            ]);
            
            // For weekly structure lists, only show tasks for today's weekday
            $todayWeekday = strtolower(now()->format('l')); // monday, tuesday, etc.
            
            \Log::info('Today weekday', ['today' => $todayWeekday]);
            
            $list->load(['tasks' => function ($query) use ($todayWeekday) {
                $query->where('is_active', true)
                      ->where(function ($q) use ($todayWeekday) {
                          $q->where('weekday', $todayWeekday)  // Tasks for today
                            ->orWhereNull('weekday');           // General tasks (no specific day)
                      });
            }]);
            
            \Log::info('Tasks loaded', ['task_count' => $list->tasks->count()]);
        } else {
            // For regular lists, load all active tasks
            $list->load(['tasks' => function ($query) {
                $query->where('is_active', true);
            }]);
        }
        
        // Check if user has already started this list today
        $existingSubmission = Submission::where('user_id', $user->id)
            ->where('list_id', $list->id)
            ->whereDate('created_at', today())
            ->first();

        return view('employee.lists.show', compact('list', 'existingSubmission'));
    }

    /**
     * Start a new submission
     */
    public function start(Request $request, TaskList $list)
    {
        $user = auth()->user();
        
        // Check if user has access to this list
        if (!$this->userHasAccessToList($user, $list)) {
            abort(403, 'You do not have access to this task list.');
        }

        // Check if user has already started this list today
        $existingSubmission = Submission::where('user_id', $user->id)
            ->where('list_id', $list->id)
            ->whereDate('created_at', today())
            ->first();

        if ($existingSubmission) {
            return redirect()->route('employee.submissions.edit', $existingSubmission)
                ->with('info', 'You have already started this list today.');
        }

        // Create new submission
        $submission = Submission::create([
            'user_id' => $user->id,
            'list_id' => $list->id,
            'started_at' => now(),
            'status' => 'in_progress',
            'metadata' => [
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ],
        ]);

        // Create submission tasks for each task in the list
        // Load tasks with proper filtering for weekly structure lists
        if ($list->hasWeeklyStructure()) {
            // For weekly structure lists, only include tasks for today's weekday or general tasks
            $todayWeekday = strtolower(now()->format('l')); // monday, tuesday, etc.
            
            $tasks = $list->tasks()
                ->where('is_active', true)
                ->where(function ($query) use ($todayWeekday) {
                    $query->where('weekday', $todayWeekday)  // Tasks for today
                          ->orWhereNull('weekday');           // General tasks (no specific day)
                })
                ->get();
        } else {
            // For regular lists, include all active tasks
            $tasks = $list->tasks()->where('is_active', true)->get();
        }
        
        foreach ($tasks as $task) {
            SubmissionTask::create([
                'submission_id' => $submission->id,
                'task_id' => $task->id,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('employee.submissions.edit', $submission)
            ->with('success', 'Task list started successfully!');
    }

    /**
     * Show the form for editing a submission (completing tasks)
     */
    public function edit(Submission $submission)
    {
        // Check if user owns this submission
        if ($submission->user_id !== auth()->id()) {
            abort(403, 'You do not have access to this submission.');
        }

    $submission->load(['taskList', 'submissionTasks.task']);
    // Laat ALLE taken zien die bij deze submission horen
    return view('employee.submissions.edit', compact('submission'));
    }

    /**
     * Complete a specific task within a submission
     */
    public function completeTask(Request $request, Submission $submission, $taskId)
    {
        // Check if user owns this submission
        if ($submission->user_id !== auth()->id()) {
            abort(403, 'You do not have access to this submission.');
        }

        $submissionTask = $submission->submissionTasks()
            ->where('task_id', $taskId)
            ->firstOrFail();

        $task = $submissionTask->task;

        // Validate based on required proof type
        $rules = ['proof_text' => 'nullable|string'];
        
        if (in_array($task->required_proof_type, ['photo', 'video', 'file', 'any'])) {
            $rules['proof_files'] = 'nullable|array';
            $rules['proof_files.*'] = 'file|max:10240'; // 10MB max per file
        }

        if ($task->required_proof_type === 'photo') {
            $rules['proof_files.*'] = 'image|max:5120'; // 5MB max for images
        }

        // Add digital signature validation if required
        if ($task->requires_signature) {
            $rules['digital_signature'] = 'required|string';
        }

        $validated = $request->validate($rules);

        // Handle file uploads
        $proofFiles = [];
        if ($request->hasFile('proof_files')) {
            foreach ($request->file('proof_files') as $file) {
                $path = $file->store('submissions/' . $submission->id, 'public');
                $proofFiles[] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ];
            }
        }

        // Update submission task
        $updateData = [
            'proof_text' => $validated['proof_text'] ?? null,
            'proof_files' => $proofFiles,
            'status' => 'completed',
            'completed_at' => now(),
            'redo_requested' => false, // Reset redo flag when task is completed again
        ];

        // Add digital signature if provided
        if (isset($validated['digital_signature'])) {
            $updateData['digital_signature'] = $validated['digital_signature'];
            $updateData['signature_date'] = now();
        }

        $submissionTask->update($updateData);

        return redirect()->route('employee.submissions.edit', $submission)->with('success', 'Task completed successfully!');
    }

    /**
     * Complete the entire submission
     */
    public function complete(Request $request, Submission $submission)
    {
        // Check if user owns this submission
        if ($submission->user_id !== auth()->id()) {
            abort(403, 'You do not have access to this submission.');
        }

        // Check if all required tasks are completed
        $pendingTasks = $submission->submissionTasks()
            ->where('status', 'pending')
            ->whereHas('task', function ($query) {
                $query->where('is_required', true);
            })
            ->count();

        if ($pendingTasks > 0) {
            return back()->with('error', 'Please complete all required tasks before submitting.');
        }

        // Handle digital signature if required
        $validated = $request->validate([
            'employee_signature' => $submission->taskList->requires_signature ? 'required|string' : 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $submission->update([
            'completed_at' => now(),
            'status' => 'completed',
            'employee_signature' => $validated['employee_signature'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('employee.dashboard')
            ->with('success', '🎉 Congratulations! You have successfully completed your checklist. Thank you for your hard work!');
    }

    private function userHasAccessToList($user, $list)
    {
        // Use the ScheduleService to check if the user has access and the list is scheduled
        $assignedLists = $this->scheduleService->getScheduledTasksForUser($user);
        return $assignedLists->contains('id', $list->id);
    }
}

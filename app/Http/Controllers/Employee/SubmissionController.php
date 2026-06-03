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
        
        // Get assigned lists using ScheduleService (open/pending lists)
        $scheduledLists = $this->scheduleService->getScheduledTasksForUser($user);
        $completedLists = Submission::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->whereIn('status', ['completed', 'reviewed'])
            ->with('taskList')
            ->get()
            ->pluck('taskList')
            ->filter()
            ->unique('id')
            ->values();

        // Status filter: "afgerond" = completed today, "openstaand" = scheduled only
        if ($request->filled('status') && $request->status === 'afgerond') {
            $assignedLists = $completedLists;
        } else {
            $assignedLists = $scheduledLists;
        }

        // Categories for dropdown: merge both so all options show
        $categories = $scheduledLists->merge($completedLists)->pluck('category')->filter()->unique()->sort()->values();

        // Apply priority and category filters
        if ($request->filled('priority')) {
            $assignedLists = $assignedLists->where('priority', $request->priority);
        }
        if ($request->filled('category')) {
            $assignedLists = $assignedLists->where('category', $request->category);
        }

        // Status filter "openstaand": only lists not completed/reviewed
        if ($request->filled('status') && $request->status === 'openstaand') {
            $assignedLists = $assignedLists->filter(function ($list) use ($user) {
                $submission = Submission::where('user_id', $user->id)
                    ->where('list_id', $list->id)
                    ->whereDate('created_at', today())
                    ->first();
                return !$submission || in_array($submission->status, ['in_progress', 'rejected', 'redo_requested']);
            })->values();
        }
        
        return view('employee.lists.index', compact('assignedLists', 'categories'));
    }

    /**
     * Show a specific task list
     */
    public function show(TaskList $list)
    {
        $user = auth()->user();
        
        // Ensure list belongs to same company
        if ($list->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }
        
        // Check if user has access to this list
        if (!$this->userHasAccessToList($user, $list)) {
            abort(403, 'You do not have access to this task list.');
        }

        // Load tasks with weekday filtering - tasks with weekday should only show on that day
        $todayWeekday = strtolower(now()->format('l')); // monday, tuesday, etc.
        
        // Always filter tasks by weekday - only show tasks for today or tasks without weekday (general tasks)
        $list->load(['tasks' => function ($query) use ($todayWeekday) {
            $query->where('is_active', true)
                  ->where(function ($q) use ($todayWeekday) {
                      $q->whereNull('weekday')      // General tasks (no specific day) - always show
                        ->orWhere('weekday', $todayWeekday); // Tasks for today's weekday
                  });
        }]);
        
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
        
        // Ensure list belongs to same company
        if ($list->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }
        
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
            return redirect()->route('employee.submissions.edit', ['submission' => $existingSubmission->id, 'updated' => time()])
                ->with('info', 'You have already started this list today.');
        }

        // Ensure list belongs to same company
        if ($list->company_id !== $user->company_id) {
            abort(403, 'You do not have access to this task list.');
        }

        // Create new submission
        $submission = Submission::create([
            'user_id' => $user->id,
            'list_id' => $list->id,
            'company_id' => $user->company_id,
            'started_at' => now(),
            'status' => 'in_progress',
            'metadata' => [
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ],
        ]);

        // Create submission tasks for each task in the list
        // Load tasks with weekday filtering - tasks with weekday should only show on that day
        $todayWeekday = strtolower(now()->format('l')); // monday, tuesday, etc.
        
        // Always filter tasks by weekday - only show tasks for today or tasks without weekday (general tasks)
        $tasks = $list->tasks()
            ->where('is_active', true)
            ->where(function ($query) use ($todayWeekday) {
                $query->whereNull('weekday')      // General tasks (no specific day) - always show
                      ->orWhere('weekday', $todayWeekday); // Tasks for today's weekday
            })
            ->get();
        
        foreach ($tasks as $task) {
            SubmissionTask::create([
                'submission_id' => $submission->id,
                'task_id' => $task->id,
                'status' => 'pending',
            ]);
        }

        return redirect()->route('employee.submissions.edit', ['submission' => $submission->id, 'updated' => time()])
            ->with('success', 'Takenlijst succesvol gestart!');
    }

    /**
     * Show the form for editing a submission (completing tasks)
     */
    public function edit(Submission $submission)
    {
        $user = auth()->user();
        
        // Check if user owns this submission and belongs to same company
        if ($submission->user_id !== $user->id || $submission->company_id !== $user->company_id) {
            abort(403, 'You do not have access to this submission.');
        }

    $submission->load(['taskList', 'submissionTasks.task']);
    
    // Check if there are tasks in the list that are not yet in the submission
    // This can happen if tasks were added to the list after the submission was created
    // or if weekday filtering changed which tasks should be included
    $todayWeekday = strtolower(now()->format('l')); // monday, tuesday, etc.
    
    // Get all tasks that should be in this submission (filtered by weekday)
    $tasksThatShouldBeIncluded = $submission->taskList->tasks()
        ->where('is_active', true)
        ->where(function ($query) use ($todayWeekday) {
            $query->whereNull('weekday')      // General tasks (no specific day) - always show
                  ->orWhere('weekday', $todayWeekday); // Tasks for today's weekday
        })
        ->pluck('id');
    
    // Get task IDs that are already in the submission
    $existingTaskIds = $submission->submissionTasks->pluck('task_id');
    
    // Find missing task IDs
    $missingTaskIds = $tasksThatShouldBeIncluded->diff($existingTaskIds);
    
    // Add missing tasks to the submission
    if ($missingTaskIds->count() > 0) {
        foreach ($missingTaskIds as $taskId) {
            \App\Models\SubmissionTask::create([
                'submission_id' => $submission->id,
                'task_id' => $taskId,
                'status' => 'pending',
            ]);
        }
        
        // Reload the submission with the new tasks
        $submission->load(['taskList', 'submissionTasks.task']);
    }
    
    // Laat ALLE taken zien die bij deze submission horen
    return view('employee.submissions.edit', compact('submission'));
    }

    /**
     * Complete a specific task within a submission
     */
    public function completeTask(Request $request, Submission $submission, $taskId)
    {
        try {
            $user = auth()->user();
            
            // Check if user owns this submission and belongs to same company
            if ($submission->user_id !== $user->id || $submission->company_id !== $user->company_id) {
                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Je hebt geen toegang tot deze checklist.'
                    ], 403);
                }
                abort(403, 'You do not have access to this submission.');
            }

            $submissionTask = $submission->submissionTasks()
                ->where('task_id', $taskId)
                ->firstOrFail();

            $task = $submissionTask->task;

            // Validate based on required proof type
            $rules = ['proof_text' => 'nullable|string'];
            $messages = [
                'proof_files.required' => 'Bewijs is vereist voor deze taak.',
                'proof_files.*.file' => 'Elk bestand moet geldig zijn.',
                'proof_files.*.max' => 'Bestanden mogen niet groter zijn dan :max KB.',
                'proof_files.*.image' => 'Alleen afbeeldingen zijn toegestaan voor deze taak.',
                'digital_signature.required' => 'Een digitale handtekening is vereist voor deze taak.',
                'proof_text.required' => 'Tekst bewijs is vereist voor deze taak.'
            ];
            
            if (in_array($task->required_proof_type, ['photo', 'video', 'file', 'any'])) {
                if ($task->required_proof_type !== 'any') {
                    $rules['proof_files'] = 'required|array|min:1';
                } else {
                    $rules['proof_files'] = 'nullable|array';
                }
                $rules['proof_files.*'] = 'file|max:10240'; // 10MB max per file
            }

            if ($task->required_proof_type === 'photo') {
                $rules['proof_files.*'] = 'image|max:5120'; // 5MB max for images
                $messages['proof_files.required'] = 'Je hebt geen afbeelding toegevoegd aan de taak.';
            }
            
            if ($task->required_proof_type === 'text') {
                $rules['proof_text'] = 'required|string|min:3';
            }

            $validationRules = is_array($task->validation_rules) ? $task->validation_rules : [];
            if (!empty($validationRules['metric'])) {
                $rules['proof_text'] = 'required|string|min:1';
                $messages['proof_text.required'] = 'Vul de meting in (temperatuur of pH).';
            }

            // Add digital signature validation if required
            if ($task->requires_signature) {
                $rules['digital_signature'] = 'required|string';
            }
            
            // Checklist progress (optional)
            $rules['checklist_progress'] = 'nullable|string';

            $validated = $request->validate($rules, $messages);

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
            
            // Add checklist progress if provided
            if ($request->has('checklist_progress') && !empty($request->input('checklist_progress'))) {
                $checklistProgress = json_decode($request->input('checklist_progress'), true);
                if (is_array($checklistProgress)) {
                    $updateData['checklist_progress'] = $checklistProgress;
                }
            }

            $submissionTask->update($updateData);

            // Clear company storage cache when new files are uploaded
            if (!empty($proofFiles)) {
                $submission->company?->clearStorageCache();
            }

            // Handle AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Taak succesvol afgerond!',
                    'completed_at' => $submissionTask->completed_at->toISOString(),
                    'task_id' => $taskId
                ]);
            }

            return redirect()->route('employee.submissions.edit', ['submission' => $submission->id, 'updated' => time()])
                ->with('success', 'Taak succesvol afgerond!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Task completion validation error', [
                'user_id' => auth()->id(),
                'submission_id' => $submission->id,
                'task_id' => $taskId,
                'errors' => $e->errors()
            ]);
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validatie fout: ' . collect($e->errors())->flatten()->first(),
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            \Log::error('Task completion error', [
                'user_id' => auth()->id(),
                'submission_id' => $submission->id,
                'task_id' => $taskId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Er is een fout opgetreden bij het afronden van de taak. Probeer het opnieuw.'
                ], 500);
            }
            
            return back()->with('error', 'Er is een fout opgetreden bij het afronden van de taak. Probeer het opnieuw.');
        }
    }

    /**
     * Complete the entire submission
     */
    public function complete(Request $request, Submission $submission)
    {
        try {
            $user = auth()->user();
            
            // Check if user owns this submission and belongs to same company
            if ($submission->user_id !== $user->id || $submission->company_id !== $user->company_id) {
                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Je hebt geen toegang tot deze checklist.'
                    ], 403);
                }
                abort(403, 'You do not have access to this submission.');
            }

            // Check if all required tasks are done for submission purposes:
            // - completed, approved: taak is afgerond
            // - pending/redo_requested/rejected: nog niet klaar om in te dienen
            $incompleteRequiredTasks = $submission->submissionTasks()
                ->whereHas('task', function ($query) {
                    $query->where('is_required', true);
                })
                ->whereNotIn('status', ['completed', 'approved'])
                ->count();

            if ($incompleteRequiredTasks > 0) {
                $message = 'Voltooi eerst alle verplichte taken om de checklist in te dienen. ';
                $hasRedoRequested = $submission->submissionTasks()
                    ->whereHas('task', fn($q) => $q->where('is_required', true))
                    ->where('status', 'redo_requested')
                    ->exists();
                if ($hasRedoRequested) {
                    $message .= 'Voer de taken die opnieuw moeten worden gedaan eerst opnieuw uit.';
                }

                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 422);
                }
                
                return redirect()->route('employee.submissions.edit', ['submission' => $submission->id, 'updated' => time()])
                    ->with('error', $message);
            }

            // Handle digital signature validation with custom messages
            $rules = [
                'employee_signature' => $submission->taskList->requires_signature ? 'required|string' : 'nullable|string',
                'notes' => 'nullable|string',
            ];
            
            $messages = [
                'employee_signature.required' => 'Een digitale handtekening is vereist om de checklist in te dienen.'
            ];

            $validated = $request->validate($rules, $messages);

            $submission->update([
                'completed_at' => now(),
                'status' => 'completed',
                'employee_signature' => $validated['employee_signature'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $submission->loadMissing(['taskList', 'user']);
            $adminUsers = \App\Models\User::query()
                ->where('company_id', $submission->company_id)
                ->where('role', 'admin')
                ->pluck('id');

            foreach ($adminUsers as $adminUserId) {
                \App\Models\Notification::createSubmissionCompletedForAdmin(
                    (int) $adminUserId,
                    (int) $submission->id,
                    (string) ($submission->user->name ?? 'Een medewerker'),
                    (string) ($submission->taskList->title ?? 'Checklist')
                );
            }

            // Handle AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Checklist succesvol ingediend!',
                    'redirect_url' => route('employee.dashboard')
                ]);
            }

            return redirect()->route('employee.dashboard')
                ->with('success', '🎉 Gefeliciteerd! Je hebt je checklist succesvol voltooid. Bedankt voor je harde werk!');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Submission completion validation error', [
                'user_id' => auth()->id(),
                'submission_id' => $submission->id,
                'errors' => $e->errors()
            ]);
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validatie fout: ' . collect($e->errors())->flatten()->first(),
                    'errors' => $e->errors()
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            \Log::error('Submission completion error', [
                'user_id' => auth()->id(),
                'submission_id' => $submission->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Er is een fout opgetreden bij het indienen van de checklist. Probeer het opnieuw.'
                ], 500);
            }
            
            return back()->with('error', 'Er is een fout opgetreden bij het indienen van de checklist. Probeer het opnieuw.');
        }
    }

    private function userHasAccessToList($user, $list)
    {
        // Use the ScheduleService to check if the user has access and the list is scheduled
        $assignedLists = $this->scheduleService->getScheduledTasksForUser($user);
        return $assignedLists->contains('id', $list->id);
    }
}

<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Checklist\TaskList;
use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use App\Models\Checklist\ListAssignment;
use App\Services\ScheduleService;
use App\Helpers\ProofFileHelper;
use App\Services\CollaborativeSubmissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    protected $scheduleService;
    protected $collaborativeSubmissionService;

    public function __construct(
        ScheduleService $scheduleService,
        CollaborativeSubmissionService $collaborativeSubmissionService
    ) {
        $this->scheduleService = $scheduleService;
        $this->collaborativeSubmissionService = $collaborativeSubmissionService;
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
                $submission = $this->collaborativeSubmissionService->todaySubmissionForUser($user, $list);
                return !$submission || in_array($submission->status, ['in_progress', 'rejected', 'redo_requested']);
            })->values();
        }

        $assignedLists = $assignedLists
            ->sortBy(fn ($list) => [$list->display_order ?? PHP_INT_MAX, $list->id])
            ->values();
        
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
        $existingSubmission = $this->collaborativeSubmissionService->todaySubmissionForUser($user, $list);

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
        $existingSubmission = $this->collaborativeSubmissionService->todaySubmissionForUser($user, $list);

        if ($existingSubmission) {
            return redirect()->route('employee.submissions.edit', ['submission' => $existingSubmission->id, 'updated' => time()])
                ->with('info', 'Deze lijst is vandaag al gestart.');
        }

        $submission = $this->collaborativeSubmissionService->resolveOrCreateTodaySubmission($user, $list, [
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
        ]);

        $this->ensureSubmissionTasksExist($submission, $list);

        return redirect()->route('employee.submissions.edit', ['submission' => $submission->id, 'updated' => time()]);
    }

    /**
     * Show the form for editing a submission (completing tasks)
     */
    public function edit(Submission $submission)
    {
        $user = auth()->user();
        
        // Check if user can access this submission
        if (!$this->collaborativeSubmissionService->userCanAccessSubmission($user, $submission)) {
            abort(403, 'You do not have access to this submission.');
        }

    $submission->load(['taskList', 'submissionTasks.task']);
    
    // Check if there are tasks in the list that are not yet in the submission
    $this->ensureSubmissionTasksExist($submission, $submission->taskList);
    $submission->load(['taskList', 'submissionTasks.task']);
    
    // Laat ALLE taken zien die bij deze submission horen
    $neighborLists = $this->neighborListUrls($user, $submission->taskList);

    return view('employee.submissions.edit', array_merge(
        compact('submission'),
        $neighborLists
    ));
    }

    /**
     * Open an assigned list: continue today's submission or start a new one.
     */
    public function open(Request $request, TaskList $list)
    {
        $user = auth()->user();

        if ($list->company_id !== $user->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }

        if (!$this->userHasAccessToList($user, $list)) {
            abort(403, 'You do not have access to this task list.');
        }

        $submission = $this->collaborativeSubmissionService->resolveOrCreateTodaySubmission($user, $list, [
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
        ]);

        $this->ensureSubmissionTasksExist($submission, $list);

        return redirect()->route('employee.submissions.edit', ['submission' => $submission->id, 'updated' => time()]);
    }

    /**
     * Complete a specific task within a submission
     */
    public function completeTask(Request $request, Submission $submission, $taskId)
    {
        try {
            $user = auth()->user();
            
            if (!$this->collaborativeSubmissionService->userCanAccessSubmission($user, $submission)) {
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

            if (in_array($submission->status, ['completed', 'reviewed'], true)) {
                $message = 'Deze lijst is al ingediend. Taken kunnen niet meer worden bewerkt.';
                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 403);
                }
                return back()->with('error', $message);
            }

            if ($submissionTask->status === 'approved') {
                $message = 'Deze taak is goedgekeurd en kan niet meer worden bewerkt.';
                if ($request->ajax() || $request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => $message
                    ], 403);
                }
                return back()->with('error', $message);
            }

            $task = $submissionTask->task;
            $existingProofFiles = $submissionTask->proof_files;
            if (is_string($existingProofFiles)) {
                $decoded = json_decode($existingProofFiles, true);
                $existingProofFiles = is_array($decoded) ? $decoded : [];
            } elseif (! is_array($existingProofFiles)) {
                $existingProofFiles = [];
            } else {
                $existingProofFiles = array_values($existingProofFiles);
            }

            // Validate based on required proof type
            $rules = [
                'proof_text' => 'nullable|string',
                'employee_comment' => 'nullable|string|max:2000',
            ];
            $messages = [
                'proof_files.required' => 'Bewijs is vereist voor deze taak.',
                'proof_files.*.file' => 'Elk bestand moet geldig zijn.',
                'proof_files.*.max' => 'Bestanden mogen niet groter zijn dan :max KB.',
                'proof_files.*.image' => 'Alleen afbeeldingen zijn toegestaan voor deze taak.',
                'digital_signature.required' => 'Een digitale handtekening is vereist voor deze taak.',
                'proof_text.required' => 'Tekst bewijs is vereist voor deze taak.'
            ];
            
            if (in_array($task->required_proof_type, ['photo', 'video', 'file', 'any'])) {
                if ($task->required_proof_type !== 'any' && empty($existingProofFiles)) {
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
            if ($task->requires_signature && empty($submissionTask->digital_signature)) {
                $rules['digital_signature'] = 'required|string';
            }
            
            // Checklist progress (optional)
            $rules['checklist_progress'] = 'nullable|string';

            $validated = $request->validate($rules, $messages);

            // Handle file uploads (append to existing proof files)
            $proofFiles = ProofFileHelper::mergeUploadedProofFiles(
                $request,
                $submission->id,
                $existingProofFiles
            );

            // Update submission task
            $updateData = [
                'proof_text' => $request->has('proof_text')
                    ? ($validated['proof_text'] ?? null)
                    : $submissionTask->proof_text,
                'employee_comment' => $request->has('employee_comment')
                    ? ($validated['employee_comment'] ?? null)
                    : $submissionTask->employee_comment,
                'proof_files' => $proofFiles,
                'status' => 'completed',
                'completed_at' => $submissionTask->completed_at ?? now(),
                'completed_by_user_id' => $submissionTask->completed_by_user_id ?? $user->id,
                'redo_requested' => false,
            ];

            // Add digital signature if provided
            if (!empty($validated['digital_signature'])) {
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
                    'task_id' => $taskId,
                    'proof_files' => ProofFileHelper::withAbsoluteUrls($proofFiles),
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
            
            // Check if user can access this submission
            if (!$this->collaborativeSubmissionService->userCanAccessSubmission($user, $submission)) {
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

            $submission->loadMissing(['taskList', 'user']);
            $neighborLists = $this->neighborListUrls($user, $submission->taskList);
            $redirectUrl = $neighborLists['nextListUrl'] ?? route('employee.dashboard');
            $goingToNextList = !empty($neighborLists['nextListUrl']);
            $successMessage = $goingToNextList
                ? 'Checklist ingediend. De volgende lijst wordt geopend.'
                : '🎉 Gefeliciteerd! Je hebt je laatste checklist succesvol voltooid.';

            $submission->update([
                'completed_at' => now(),
                'status' => 'completed',
                'employee_signature' => $validated['employee_signature'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $adminUsers = \App\Models\Organisation\User::query()
                ->where('company_id', $submission->company_id)
                ->where('role', 'admin')
                ->pluck('id');

            foreach ($adminUsers as $adminUserId) {
                \App\Models\Communication\Notification::createSubmissionCompletedForAdmin(
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
                    'message' => $successMessage,
                    'redirect_url' => $redirectUrl,
                    'next_list' => $goingToNextList,
                ]);
            }

            return redirect($redirectUrl)->with('success', $successMessage);
                
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
        if ($this->listsForNavigation($user)->contains('id', $list->id)) {
            return true;
        }

        return $this->collaborativeSubmissionService->todaySubmissionForUser($user, $list) !== null;
    }

    private function listsForNavigation($user)
    {
        return $this->scheduleService->getScheduledTasksForUser($user)
            ->sortBy(fn ($list) => [$list->display_order ?? PHP_INT_MAX, $list->id])
            ->values();
    }

    private function neighborListUrls($user, TaskList $currentList): array
    {
        $lists = $this->listsForNavigation($user);
        $index = $lists->search(fn ($list) => (int) $list->id === (int) $currentList->id);
        $listUrls = $lists->map(fn ($list) => route('employee.lists.open', $list))->values()->all();

        if ($index === false) {
            return [
                'previousListUrl' => null,
                'nextListUrl' => $lists->isNotEmpty() ? route('employee.lists.open', $lists->first()) : null,
                'currentListPosition' => 0,
                'totalLists' => $lists->count(),
                'listUrls' => $listUrls,
                'currentListInJump' => false,
            ];
        }

        $previous = $index > 0 ? $lists[$index - 1] : null;
        $next = $index < $lists->count() - 1 ? $lists[$index + 1] : null;

        return [
            'previousListUrl' => $previous ? route('employee.lists.open', $previous) : null,
            'nextListUrl' => $next ? route('employee.lists.open', $next) : null,
            'currentListPosition' => $index + 1,
            'totalLists' => $lists->count(),
            'listUrls' => $listUrls,
            'currentListInJump' => true,
        ];
    }

    private function ensureSubmissionTasksExist(Submission $submission, TaskList $list): void
    {
        $todayWeekday = strtolower(now()->format('l'));

        $tasksThatShouldBeIncluded = $list->tasks()
            ->where('is_active', true)
            ->where(function ($query) use ($todayWeekday) {
                $query->whereNull('weekday')
                    ->orWhere('weekday', $todayWeekday);
            })
            ->pluck('id');

        $submission->loadMissing('submissionTasks');
        $existingTaskIds = $submission->submissionTasks->pluck('task_id');
        $missingTaskIds = $tasksThatShouldBeIncluded->diff($existingTaskIds);

        foreach ($missingTaskIds as $taskId) {
            SubmissionTask::create([
                'submission_id' => $submission->id,
                'task_id' => $taskId,
                'status' => 'pending',
            ]);
        }
    }
}

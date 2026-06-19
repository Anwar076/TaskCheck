<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use App\Models\Checklist\TaskList;
use App\Services\Mobile\MobileSerializer;
use App\Services\Mobile\MobileTaskAccess;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class TaskListController extends MobileController
{
    public function __construct(
        protected ScheduleService $scheduleService,
        protected MobileTaskAccess $taskAccess,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $lists = $this->scheduleService->getScheduledTasksForUser($user);

        if ($request->filled('location_id')) {
            $locationId = (int) $request->get('location_id');
            $lists = $lists->filter(fn ($list) => (int) $list->location_id === $locationId);
        }

        if ($request->filled('search')) {
            $search = strtolower((string) $request->get('search'));
            $lists = $lists->filter(function ($list) use ($search) {
                return str_contains(strtolower((string) $list->title), $search)
                    || str_contains(strtolower((string) $list->description), $search);
            });
        }

        $items = $lists->map(function ($list) use ($user) {
            $submission = $this->taskAccess->todaySubmission($user, $list);

            if ($requestStatus = request()->get('status')) {
                if ($requestStatus === 'completed' && (!$submission || !in_array($submission->status, ['completed', 'reviewed'], true))) {
                    return null;
                }
                if ($requestStatus === 'open' && $submission && in_array($submission->status, ['completed', 'reviewed'], true)) {
                    return null;
                }
            }

            return MobileSerializer::taskListItem($list, $submission);
        })->filter()->values();

        return $this->success($items);
    }

    public function show(Request $request, int $id)
    {
        $user = $request->user();
        $list = TaskList::where('company_id', $user->company_id)->findOrFail($id);

        if (!$this->taskAccess->userCanAccessList($user, $list)) {
            return $this->error('Geen toegang tot deze takenlijst.', 403);
        }

        $submission = $this->taskAccess->todaySubmission($user, $list);
        if ($submission) {
            $this->taskAccess->syncMissingSubmissionTasks($submission);
            $submission->load(['submissionTasks.task']);
        }

        $tasks = $this->taskAccess->tasksForToday($list);

        return $this->success(
            MobileSerializer::taskListDetail($list, $submission, $tasks)
        );
    }

    public function start(Request $request, int $id)
    {
        $user = $request->user();
        $list = TaskList::where('company_id', $user->company_id)->findOrFail($id);

        if (!$this->taskAccess->userCanAccessList($user, $list)) {
            return $this->error('Geen toegang tot deze takenlijst.', 403);
        }

        $existing = $this->taskAccess->todaySubmission($user, $list);
        if ($existing) {
            return $this->success(['submission_id' => $existing->id]);
        }

        $submission = Submission::create([
            'user_id' => $user->id,
            'list_id' => $list->id,
            'company_id' => $user->company_id,
            'started_at' => now(),
            'status' => 'in_progress',
            'metadata' => [
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'source' => 'mobile',
            ],
        ]);

        foreach ($this->taskAccess->tasksForToday($list) as $task) {
            SubmissionTask::create([
                'submission_id' => $submission->id,
                'task_id' => $task->id,
                'status' => 'pending',
            ]);
        }

        return $this->success(['submission_id' => $submission->id], 'Takenlijst gestart.');
    }
}

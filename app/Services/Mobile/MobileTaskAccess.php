<?php

namespace App\Services\Mobile;

use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\User;
use App\Services\CollaborativeSubmissionService;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class MobileTaskAccess
{
    public function __construct(
        protected ScheduleService $scheduleService,
        protected CollaborativeSubmissionService $collaborativeSubmissionService,
    ) {}

    public function userCanAccessList(User $user, TaskList $list): bool
    {
        if ($list->company_id !== $user->company_id) {
            return false;
        }

        return $this->scheduleService
            ->getScheduledTasksForUser($user)
            ->contains('id', $list->id);
    }

    public function todaySubmission(User $user, TaskList $list): ?Submission
    {
        return $this->collaborativeSubmissionService->todaySubmissionForUser($user, $list);
    }

    public function userCanAccessSubmission(User $user, Submission $submission): bool
    {
        return $this->collaborativeSubmissionService->userCanAccessSubmission($user, $submission);
    }

    public function tasksForToday(TaskList $list)
    {
        $todayWeekday = strtolower(now()->format('l'));

        return $list->tasks()
            ->where('is_active', true)
            ->where(function ($query) use ($todayWeekday) {
                $query->whereNull('weekday')
                    ->orWhere('weekday', $todayWeekday);
            })
            ->orderBy('order')
            ->orderBy('order_index')
            ->get();
    }

    public function syncMissingSubmissionTasks(Submission $submission): void
    {
        $submission->loadMissing(['taskList', 'submissionTasks']);
        $tasks = $this->tasksForToday($submission->taskList);
        $existing = $submission->submissionTasks->pluck('task_id');

        foreach ($tasks->pluck('id')->diff($existing) as $taskId) {
            SubmissionTask::create([
                'submission_id' => $submission->id,
                'task_id' => $taskId,
                'status' => 'pending',
            ]);
        }

        $submission->load('submissionTasks');
    }

    public function findOwnedSubmissionTask(Request $request, int $submissionTaskId): ?SubmissionTask
    {
        $user = $request->user();

        $submissionTask = SubmissionTask::query()
            ->where('id', $submissionTaskId)
            ->whereHas('submission', fn ($query) => $query->where('company_id', $user->company_id))
            ->with(['submission.taskList', 'task'])
            ->first();

        if (!$submissionTask || !$this->userCanAccessSubmission($user, $submissionTask->submission)) {
            return null;
        }

        return $submissionTask;
    }
}

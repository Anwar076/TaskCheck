<?php

namespace App\Http\Controllers\Api\Mobile\Admin;

use App\Http\Controllers\Api\Mobile\MobileController;
use App\Models\Communication\Notification;
use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubmissionTaskController extends MobileController
{
    public function approve(Request $request, int $submissionTaskId)
    {
        $submissionTask = $this->findCompanySubmissionTask($request, $submissionTaskId);

        $validated = $request->validate([
            'manager_comment' => ['nullable', 'string'],
        ]);

        $submissionTask->approve($request->user()->id, $validated['manager_comment'] ?? null);
        $this->updateSubmissionStatusIfAllTasksReviewed($submissionTask->submission);

        return $this->success(null, 'Taak goedgekeurd.');
    }

    public function reject(Request $request, int $submissionTaskId)
    {
        $submissionTask = $this->findCompanySubmissionTask($request, $submissionTaskId);

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($submissionTask, $validated, $request) {
            $submissionTask->update([
                'status' => 'pending',
                'rejection_reason' => $validated['rejection_reason'],
                'rejected_at' => now(),
                'reviewed_by' => $request->user()->id,
                'reviewed_at' => now(),
                'redo_requested' => false,
                'redo_reason' => null,
            ]);

            Notification::createTaskRejected(
                app(\App\Services\CollaborativeSubmissionService::class)->notifyUserIdForTask($submissionTask),
                $submissionTask->task->title,
                $validated['rejection_reason'],
                $submissionTask->submission_id
            );

            $submissionTask->submission->update(['status' => 'in_progress']);
        });

        return $this->success(null, 'Taak afgekeurd.');
    }

    public function redo(Request $request, int $submissionTaskId)
    {
        $submissionTask = $this->findCompanySubmissionTask($request, $submissionTaskId);

        $validated = $request->validate([
            'redo_reason' => ['nullable', 'string'],
        ]);

        $submissionTask->requestRedo($request->user()->id, $validated['redo_reason'] ?? null);

        return $this->success(null, 'Opnieuw doen aangevraagd.');
    }

    protected function findCompanySubmissionTask(Request $request, int $id): SubmissionTask
    {
        return SubmissionTask::query()
            ->with(['submission', 'task'])
            ->whereHas('submission', fn ($q) => $q->where('company_id', $request->user()->company_id))
            ->findOrFail($id);
    }

    protected function updateSubmissionStatusIfAllTasksReviewed(Submission $submission): void
    {
        $submission->load('submissionTasks');
        $tasks = $submission->submissionTasks;
        if ($tasks->isEmpty()) {
            return;
        }

        $reviewedStatuses = ['approved', 'rejected'];
        $allReviewed = $tasks->every(fn ($t) => in_array($t->status, $reviewedStatuses, true));
        if (! $allReviewed) {
            return;
        }

        $hasRejected = $tasks->contains('status', 'rejected');
        $submission->update(['status' => $hasRejected ? 'rejected' : 'reviewed']);
    }
}

<?php

namespace App\Services\Submissions;

use App\Enums\SubmissionStatus;
use App\Models\Checklist\TaskList;
use App\Models\Submissions\Submission;

class SubmissionWorkflowService
{
    public function statusAfterEmployeeCompletion(TaskList $list): SubmissionStatus
    {
        return ! $list->requires_review && $list->auto_accept_without_review
            ? SubmissionStatus::REVIEWED
            : SubmissionStatus::COMPLETED;
    }

    public function isAwaitingReview(Submission $submission): bool
    {
        $submission->loadMissing('taskList');

        return $submission->status === SubmissionStatus::COMPLETED->value
            && (bool) $submission->taskList?->requires_review;
    }

    public function isFinished(Submission $submission): bool
    {
        return in_array($submission->status, SubmissionStatus::finishedValues(), true);
    }

    public function isAccepted(Submission $submission): bool
    {
        return $submission->status === SubmissionStatus::REVIEWED->value;
    }

    public function isClosed(Submission $submission): bool
    {
        return in_array($submission->status, SubmissionStatus::closedValues(), true);
    }
}

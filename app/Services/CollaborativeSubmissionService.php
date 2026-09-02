<?php

namespace App\Services;

use App\Enums\SubmissionStatus;
use App\Models\Checklist\ListAssignment;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use Carbon\Carbon;

class CollaborativeSubmissionService
{
    public function usesTeamSubmission(TaskList $list): bool
    {
        $assignments = ListAssignment::query()
            ->where('list_id', $list->id)
            ->where('is_active', true)
            ->get();

        $userAssignmentCount = $assignments->whereNotNull('user_id')->count();

        if ($userAssignmentCount >= 2) {
            return true;
        }

        return $assignments->contains(fn (ListAssignment $a) => filled($a->department) || filled($a->role));
    }

    public function todaySubmissionForUser(User $user, TaskList $list, ?Carbon $date = null): ?Submission
    {
        $date = $date ? Carbon::parse($date) : now();

        if ($this->usesTeamSubmission($list)) {
            return $this->todayTeamSubmission($list, $date);
        }

        return Submission::query()
            ->where('user_id', $user->id)
            ->where('list_id', $list->id)
            ->where('is_team_submission', false)
            ->whereDate('created_at', $date)
            ->first();
    }

    public function todayTeamSubmission(TaskList $list, ?Carbon $date = null): ?Submission
    {
        $date = $date ? Carbon::parse($date) : now();

        return Submission::query()
            ->where('list_id', $list->id)
            ->where('company_id', $list->company_id)
            ->where('is_team_submission', true)
            ->whereDate('created_at', $date)
            ->first();
    }

    public function userCanAccessSubmission(User $user, Submission $submission): bool
    {
        if ($submission->company_id !== $user->company_id) {
            return false;
        }

        if (! $submission->is_team_submission) {
            return (int) $submission->user_id === (int) $user->id;
        }

        $submission->loadMissing('taskList');

        return app(ScheduleService::class)
            ->getScheduledTasksForUser($user)
            ->contains('id', $submission->list_id);
    }

    public function resolveOrCreateTodaySubmission(User $user, TaskList $list, array $metadata = []): Submission
    {
        if ($this->usesTeamSubmission($list)) {
            return $this->resolveOrCreateTeamSubmission($user, $list, $metadata);
        }

        $existing = Submission::query()
            ->where('user_id', $user->id)
            ->where('list_id', $list->id)
            ->where('is_team_submission', false)
            ->whereDate('created_at', today())
            ->first();

        if ($existing) {
            return $existing;
        }

        return Submission::create([
            'user_id' => $user->id,
            'list_id' => $list->id,
            'company_id' => $user->company_id,
            'started_at' => now(),
            'status' => 'in_progress',
            'is_team_submission' => false,
            'metadata' => $metadata,
        ]);
    }

    public function resolveOrCreateTeamSubmission(User $user, TaskList $list, array $metadata = []): Submission
    {
        $teamSubmission = $this->todayTeamSubmission($list);

        if ($teamSubmission) {
            return $teamSubmission;
        }

        $personalSubmission = Submission::query()
            ->where('list_id', $list->id)
            ->where('company_id', $list->company_id)
            ->where('is_team_submission', false)
            ->whereDate('created_at', today())
            ->first();

        if ($personalSubmission) {
            $personalSubmission->update(['is_team_submission' => true]);

            return $personalSubmission->fresh();
        }

        return Submission::create([
            'user_id' => $user->id,
            'list_id' => $list->id,
            'company_id' => $user->company_id,
            'started_at' => now(),
            'status' => 'in_progress',
            'is_team_submission' => true,
            'metadata' => $metadata,
        ]);
    }

    public function notifyUserIdForTask(SubmissionTask $submissionTask): int
    {
        if ($submissionTask->completed_by_user_id) {
            return (int) $submissionTask->completed_by_user_id;
        }

        return (int) $submissionTask->submission->user_id;
    }

    public function isListCompletedOnDate(TaskList $list, User $user, Carbon $date): bool
    {
        $query = Submission::query()
            ->where('list_id', $list->id)
            ->whereIn('status', SubmissionStatus::finishedValues());

        if ($this->usesTeamSubmission($list)) {
            $query->where('is_team_submission', true);

            if ($list->schedule_type === 'once') {
                return $query->exists();
            }

            return $query->whereDate('created_at', $date)->exists();
        }

        $query->where('user_id', $user->id)
            ->where('is_team_submission', false);

        if ($list->schedule_type === 'once') {
            return $query->exists();
        }

        return $query->whereDate('created_at', $date)->exists();
    }
}

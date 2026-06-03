<?php

namespace App\Services\Mobile;

use App\Helpers\ProofFileHelper;
use App\Models\Location;
use App\Models\Notification;
use App\Models\Submission;
use App\Models\SubmissionTask;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Support\Collection;

class MobileSerializer
{
    public static function user(User $user): array
    {
        $user->loadMissing(['company', 'location']);

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_admin' => $user->isAdmin(),
            'phone' => $user->phone,
            'department' => $user->department,
            'company_id' => $user->company_id,
            'location_id' => $user->location_id,
            'company' => $user->company ? [
                'id' => $user->company->id,
                'name' => $user->company->name,
            ] : null,
            'location' => $user->location ? [
                'id' => $user->location->id,
                'name' => $user->location->name,
            ] : null,
        ];
    }

    public static function notification(Notification $notification): array
    {
        $data = is_array($notification->data) ? $notification->data : [];

        return [
            'id' => $notification->id,
            'title' => $notification->title,
            'message' => $notification->message,
            'type' => $notification->type,
            'read_at' => $notification->read_at?->toIso8601String(),
            'created_at' => $notification->created_at?->toIso8601String(),
            'url' => $data['url'] ?? null,
            'data' => $data,
        ];
    }

    public static function taskListItem(TaskList $list, ?Submission $submission = null): array
    {
        $list->loadMissing(['location', 'tasks']);

        $totalTasks = $list->tasks?->count() ?? 0;
        $progress = 0;
        $submissionId = null;
        $submissionStatus = null;

        if ($submission) {
            $submission->loadMissing('submissionTasks');
            $total = max($submission->submissionTasks->count(), 1);
            $completed = $submission->submissionTasks
                ->whereIn('status', ['completed', 'approved'])
                ->count();
            $progress = $totalTasks > 0
                ? (int) round(($completed / $total) * 100)
                : 0;
            $submissionId = $submission->id;
            $submissionStatus = $submission->status;
        }

        return [
            'id' => $list->id,
            'title' => $list->title,
            'description' => $list->description,
            'priority' => $list->priority,
            'category' => $list->category,
            'due_date' => $list->due_date?->toIso8601String(),
            'location' => $list->location ? [
                'id' => $list->location->id,
                'name' => $list->location->name,
            ] : null,
            'task_count' => $totalTasks,
            'progress_percentage' => $progress,
            'submission_id' => $submissionId,
            'submission_status' => $submissionStatus,
        ];
    }

    public static function taskItem(Task $task, ?SubmissionTask $submissionTask = null): array
    {
        return [
            'id' => $task->id,
            'submission_task_id' => $submissionTask?->id,
            'title' => $task->title,
            'description' => $task->description,
            'instructions' => $task->instructions,
            'is_required' => (bool) $task->is_required,
            'required_proof_type' => $task->required_proof_type,
            'requires_signature' => (bool) $task->requires_signature,
            'status' => $submissionTask?->status ?? 'pending',
            'completed_at' => $submissionTask?->completed_at?->toIso8601String(),
            'rejection_reason' => $submissionTask?->rejection_reason,
            'redo_reason' => $submissionTask?->redo_reason,
            'notes' => $submissionTask?->proof_text,
        ];
    }

    public static function taskListDetail(TaskList $list, ?Submission $submission, Collection $tasks): array
    {
        $submissionTasksByTaskId = $submission
            ? $submission->submissionTasks->keyBy('task_id')
            : collect();

        $taskItems = $tasks->map(function (Task $task) use ($submissionTasksByTaskId) {
            return self::taskItem($task, $submissionTasksByTaskId->get($task->id));
        })->values()->all();

        $base = self::taskListItem($list, $submission);

        return array_merge($base, [
            'requires_signature' => (bool) $list->requires_signature,
            'submission' => $submission ? [
                'id' => $submission->id,
                'status' => $submission->status,
                'started_at' => $submission->started_at?->toIso8601String(),
                'completed_at' => $submission->completed_at?->toIso8601String(),
            ] : null,
            'tasks' => $taskItems,
        ]);
    }

    public static function submissionSummary(Submission $submission): array
    {
        $submission->loadMissing(['taskList.location', 'submissionTasks.task']);

        $totalTasks = $submission->submissionTasks->count();
        $completedTasks = $submission->submissionTasks
            ->whereIn('status', ['completed', 'approved'])
            ->count();
        $progress = $totalTasks > 0
            ? (int) round(($completedTasks / $totalTasks) * 100)
            : 0;

        $metadata = is_array($submission->metadata) ? $submission->metadata : [];

        return [
            'id' => $submission->id,
            'status' => $submission->status,
            'list_id' => $submission->list_id,
            'list_title' => $submission->taskList?->title,
            'location' => $submission->taskList?->location ? [
                'id' => $submission->taskList->location->id,
                'name' => $submission->taskList->location->name,
            ] : null,
            'progress_percentage' => $progress,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'started_at' => $submission->started_at?->toIso8601String(),
            'completed_at' => $submission->completed_at?->toIso8601String(),
            'created_at' => $submission->created_at?->toIso8601String(),
            'review_notes' => $metadata['admin_notes'] ?? $submission->notes,
            'tasks' => $submission->submissionTasks->map(function (SubmissionTask $st) {
                return [
                    'id' => $st->id,
                    'submission_task_id' => $st->id,
                    'task_id' => $st->task_id,
                    'title' => $st->task?->title,
                    'status' => $st->status,
                    'notes' => $st->proof_text,
                    'proof_text' => $st->proof_text,
                    'proof_files' => ProofFileHelper::withAbsoluteUrls($st->proof_files),
                    'rejection_reason' => $st->rejection_reason,
                    'completed_at' => $st->completed_at?->toIso8601String(),
                ];
            })->values()->all(),
        ];
    }

    public static function locationItem(Location $location, int $openTaskLists = 0): array
    {
        return [
            'id' => $location->id,
            'name' => $location->name,
            'address' => $location->address,
            'notes' => $location->notes,
            'open_task_lists' => $openTaskLists,
        ];
    }

    public static function adminUser(User $user): array
    {
        $user->loadMissing('location');

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => (bool) $user->is_active,
            'department' => $user->department,
            'location' => $user->location ? [
                'id' => $user->location->id,
                'name' => $user->location->name,
            ] : null,
        ];
    }

    public static function adminLocation(Location $location): array
    {
        return [
            'id' => $location->id,
            'name' => $location->name,
            'address' => $location->address,
            'notes' => $location->notes,
            'is_active' => (bool) $location->is_active,
            'lists_count' => $location->taskLists()->count(),
        ];
    }

    public static function adminSubmissionListItem(Submission $submission): array
    {
        $submission->loadMissing(['user', 'taskList.location', 'submissionTasks']);

        $totalTasks = $submission->submissionTasks->count();
        $completedTasks = $submission->submissionTasks
            ->whereIn('status', ['completed', 'approved'])
            ->count();

        return [
            'id' => $submission->id,
            'status' => $submission->status,
            'list_title' => $submission->taskList?->title,
            'progress_percentage' => $totalTasks > 0
                ? (int) round(($completedTasks / $totalTasks) * 100)
                : 0,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'user' => $submission->user ? [
                'id' => $submission->user->id,
                'name' => $submission->user->name,
                'email' => $submission->user->email,
            ] : null,
            'taskList' => $submission->taskList ? [
                'id' => $submission->taskList->id,
                'title' => $submission->taskList->title,
            ] : null,
            'location' => $submission->taskList?->location ? [
                'id' => $submission->taskList->location->id,
                'name' => $submission->taskList->location->name,
            ] : null,
            'started_at' => $submission->started_at?->toIso8601String(),
            'created_at' => $submission->created_at?->toIso8601String(),
            'completed_at' => $submission->completed_at?->toIso8601String(),
        ];
    }

    public static function adminTaskListDetail(TaskList $list): array
    {
        $list->loadMissing(['location', 'assignments.user', 'tasks']);

        return [
            'id' => $list->id,
            'title' => $list->title,
            'description' => $list->description,
            'category' => $list->category,
            'priority' => $list->priority,
            'schedule_type' => $list->schedule_type,
            'is_active' => (bool) $list->is_active,
            'task_count' => $list->tasks->count(),
            'location' => $list->location ? [
                'id' => $list->location->id,
                'name' => $list->location->name,
                'address' => $list->location->address,
            ] : null,
            'assignments' => $list->assignments->map(fn ($a) => [
                'id' => $a->id,
                'user' => $a->user ? ['id' => $a->user->id, 'name' => $a->user->name] : null,
                'department' => $a->department,
                'role' => $a->role,
            ])->values()->all(),
            'tasks' => $list->tasks->map(fn ($t) => [
                'id' => $t->id,
                'title' => $t->title,
                'description' => $t->description,
                'is_required' => (bool) $t->is_required,
                'required_proof_type' => $t->required_proof_type,
            ])->values()->all(),
        ];
    }

    public static function adminSubmissionDetail(Submission $submission): array
    {
        $submission->loadMissing(['user', 'taskList.location', 'submissionTasks.task']);

        $metadata = is_array($submission->metadata) ? $submission->metadata : [];

        return array_merge(self::adminSubmissionListItem($submission), [
            'admin_notes' => $metadata['admin_notes'] ?? null,
            'tasks' => $submission->submissionTasks->map(function (SubmissionTask $st) {
                return [
                    'submission_task_id' => $st->id,
                    'task_id' => $st->task_id,
                    'title' => $st->task?->title,
                    'status' => $st->status,
                    'proof_text' => $st->proof_text,
                    'proof_files' => ProofFileHelper::withAbsoluteUrls($st->proof_files),
                    'description' => $st->task?->description,
                    'digital_signature' => $st->digital_signature,
                    'manager_comment' => $st->manager_comment,
                    'rejection_reason' => $st->rejection_reason,
                    'redo_reason' => $st->redo_reason,
                    'completed_at' => $st->completed_at?->toIso8601String(),
                ];
            })->values()->all(),
        ]);
    }
}

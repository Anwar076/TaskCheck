<?php

namespace App\Services\Admin;

use App\Enums\SubmissionStatus;
use App\Enums\SubmissionTaskStatus;
use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use App\Services\CollaborativeSubmissionService;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TeamPerformanceService
{
    public function __construct(
        protected ScheduleService $scheduleService,
        protected CollaborativeSubmissionService $collaborativeSubmissionService,
    ) {}

    /**
     * @return array{summary: array<string, mixed>, employees: array<int, array<string, mixed>>, updated_at: string}
     */
    public function build(int $companyId, ?int $locationId = null, ?Carbon $date = null): array
    {
        $date = $date ? $date->copy()->startOfDay() : now()->startOfDay();

        $employees = User::query()
            ->where('company_id', $companyId)
            ->where('role', 'employee')
            ->where('is_active', true)
            ->when($locationId, fn ($query) => $query->where('location_id', $locationId))
            ->orderBy('name')
            ->get(['id', 'name', 'department', 'company_id', 'role']);

        $todaySubmissions = Submission::query()
            ->with([
                'taskList:id,title',
                'submissionTasks:id,submission_id,status,completed_by_user_id,updated_at',
            ])
            ->where('company_id', $companyId)
            ->whereDate('created_at', $date)
            ->when($locationId, function ($query) use ($locationId) {
                $query->whereHas('taskList', fn ($taskListQuery) => $taskListQuery->where('location_id', $locationId));
            })
            ->get();

        $personalSubmissionsByUser = $todaySubmissions
            ->where('is_team_submission', false)
            ->groupBy('user_id');

        $teamSubmissionsByListId = $todaySubmissions
            ->where('is_team_submission', true)
            ->keyBy('list_id');

        $rows = [];
        $teamProgressSum = 0.0;
        $teamProgressCount = 0;

        foreach ($employees as $employee) {
            $personalSubmissions = $personalSubmissionsByUser->get($employee->id, collect());
            $scheduledLists = $this->scheduleService->getScheduledTasksForUser($employee, $date);
            $relevantSubmissions = $this->relevantSubmissionsForEmployee(
                $employee,
                $scheduledLists,
                $personalSubmissions,
                $teamSubmissionsByListId
            );

            $openListIds = $scheduledLists->pluck('id')->unique();
            $finishedListIds = $relevantSubmissions
                ->whereIn('status', SubmissionStatus::finishedValues())
                ->pluck('list_id')
                ->unique();
            $inProgressSubmissions = $relevantSubmissions->whereIn('status', ['in_progress', 'redo_requested']);
            $inProgressListIds = $inProgressSubmissions->pluck('list_id')->unique();
            $pendingReview = $relevantSubmissions->where('status', 'completed')->count();

            $totalListIds = $openListIds
                ->merge($finishedListIds)
                ->merge($inProgressListIds)
                ->unique();

            $totalLists = $totalListIds->count();
            $finishedLists = $finishedListIds->count();
            $inProgressLists = $inProgressListIds->count();
            $openLists = $openListIds->diff($finishedListIds)->diff($inProgressListIds)->count();

            if ($totalLists === 0 && $relevantSubmissions->isEmpty()) {
                continue;
            }

            $listProgressValues = $totalListIds
                ->map(fn (int $listId) => $this->listProgress(
                    $this->submissionForList(
                        $employee,
                        $listId,
                        $scheduledLists,
                        $personalSubmissions,
                        $teamSubmissionsByListId
                    )
                ))
                ->values();

            $completionRate = $listProgressValues->isNotEmpty()
                ? round($listProgressValues->avg(), 1)
                : 0;

            $teamProgressSum += $listProgressValues->sum();
            $teamProgressCount += $listProgressValues->count();

            [$isActiveNow, $activeSubmission, $activeListProgress, $isTeamActive] = $this->resolveActiveSession(
                $employee,
                $inProgressSubmissions
            );

            $rows[] = [
                'id' => $employee->id,
                'name' => $employee->name,
                'initials' => mb_strtoupper(mb_substr($employee->name, 0, 1)),
                'department' => $employee->department,
                'finished_lists' => $finishedLists,
                'total_lists' => $totalLists,
                'in_progress_lists' => $inProgressLists,
                'open_lists' => $openLists,
                'pending_review' => $pendingReview,
                'completion_rate' => $completionRate,
                'is_active_now' => $isActiveNow,
                'is_team_active' => $isTeamActive,
                'current_list' => $activeSubmission?->taskList?->title,
                'progress' => $isActiveNow ? $activeListProgress : null,
                'profile_url' => route('admin.users.show', $employee),
            ];
        }

        usort($rows, function (array $a, array $b): int {
            if ($a['is_active_now'] !== $b['is_active_now']) {
                return $a['is_active_now'] ? -1 : 1;
            }

            if ($a['total_lists'] !== $b['total_lists']) {
                return $b['total_lists'] <=> $a['total_lists'];
            }

            return $a['completion_rate'] <=> $b['completion_rate'];
        });

        $summary = [
            'employee_count' => count($rows),
            'total_lists' => array_sum(array_column($rows, 'total_lists')),
            'finished_lists' => array_sum(array_column($rows, 'finished_lists')),
            'in_progress_lists' => array_sum(array_column($rows, 'in_progress_lists')),
            'open_lists' => array_sum(array_column($rows, 'open_lists')),
            'pending_review' => $todaySubmissions->where('status', 'completed')->count(),
            'active_now' => count(array_filter($rows, fn (array $row) => $row['is_active_now'])),
            'completion_rate' => 0,
        ];

        if ($teamProgressCount > 0) {
            $summary['completion_rate'] = round($teamProgressSum / $teamProgressCount, 1);
        }

        return [
            'summary' => $summary,
            'employees' => $rows,
            'updated_at' => now()->toIso8601String(),
        ];
    }

    private function relevantSubmissionsForEmployee(
        User $employee,
        Collection $scheduledLists,
        Collection $personalSubmissions,
        Collection $teamSubmissionsByListId
    ): Collection {
        $submissions = collect();

        foreach ($scheduledLists as $list) {
            $submission = $this->submissionForList(
                $employee,
                $list->id,
                $scheduledLists,
                $personalSubmissions,
                $teamSubmissionsByListId
            );

            if ($submission && ! $submissions->contains('id', $submission->id)) {
                $submissions->push($submission);
            }
        }

        foreach ($personalSubmissions as $submission) {
            if (! $submissions->contains('id', $submission->id)) {
                $submissions->push($submission);
            }
        }

        return $submissions->unique('id')->values();
    }

    private function submissionForList(
        User $employee,
        int $listId,
        Collection $scheduledLists,
        Collection $personalSubmissions,
        Collection $teamSubmissionsByListId
    ): ?Submission {
        $list = $scheduledLists->firstWhere('id', $listId);

        if ($list && $this->collaborativeSubmissionService->usesTeamSubmission($list)) {
            return $teamSubmissionsByListId->get($listId)
                ?? $personalSubmissions->firstWhere('list_id', $listId);
        }

        if ((int) ($personalSubmissions->firstWhere('list_id', $listId)?->user_id) === (int) $employee->id) {
            return $personalSubmissions->firstWhere('list_id', $listId);
        }

        return $personalSubmissions->firstWhere('list_id', $listId);
    }

    /**
     * @return array{0: bool, 1: ?Submission, 2: ?int, 3: bool}
     */
    private function resolveActiveSession(User $employee, Collection $inProgressSubmissions): array
    {
        $recentThreshold = now()->subMinutes(15);

        foreach ($inProgressSubmissions->sortByDesc('updated_at') as $submission) {
            if ($submission->is_team_submission) {
                $recentTaskByEmployee = $submission->submissionTasks
                    ->where('completed_by_user_id', $employee->id)
                    ->where('updated_at', '>=', $recentThreshold)
                    ->isNotEmpty();

                if (! $recentTaskByEmployee) {
                    continue;
                }

                return [
                    true,
                    $submission,
                    (int) round($this->listProgress($submission)),
                    true,
                ];
            }

            if ((int) $submission->user_id !== (int) $employee->id) {
                continue;
            }

            if ($submission->updated_at < $recentThreshold) {
                continue;
            }

            return [
                true,
                $submission,
                (int) round($this->listProgress($submission)),
                false,
            ];
        }

        return [false, null, null, false];
    }

    /**
     * Task progress within a list (0–100). Finished submissions count as 100%.
     */
    private function listProgress(?Submission $submission): float
    {
        if ($submission === null) {
            return 0;
        }

        if (in_array($submission->status, ['completed', 'reviewed'], true)) {
            return 100;
        }

        $tasks = $submission->relationLoaded('submissionTasks')
            ? $submission->submissionTasks
            : $submission->submissionTasks()->get(['id', 'submission_id', 'status']);

        $total = $tasks->count();
        if ($total === 0) {
            return 0;
        }

        $done = $tasks->whereIn('status', SubmissionTaskStatus::finishedValues())->count();

        return round(($done / $total) * 100, 1);
    }
}

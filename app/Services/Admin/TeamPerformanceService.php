<?php

namespace App\Services\Admin;

use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use App\Services\ScheduleService;
use Carbon\Carbon;

class TeamPerformanceService
{
    public function __construct(
        protected ScheduleService $scheduleService,
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
            ->get(['id', 'name', 'department']);

        $todaySubmissions = Submission::query()
            ->with(['taskList:id,title', 'submissionTasks:id,submission_id,status'])
            ->whereIn('user_id', $employees->pluck('id'))
            ->whereDate('created_at', $date)
            ->when($locationId, function ($query) use ($locationId) {
                $query->whereHas('taskList', fn ($taskListQuery) => $taskListQuery->where('location_id', $locationId));
            })
            ->get();

        $submissionsByUser = $todaySubmissions->groupBy('user_id');

        $rows = [];
        $teamProgressSum = 0.0;
        $teamProgressCount = 0;

        foreach ($employees as $employee) {
            $userSubmissions = $submissionsByUser->get($employee->id, collect());
            $scheduledLists = $this->scheduleService->getScheduledTasksForUser($employee, $date);

            $openListIds = $scheduledLists->pluck('id')->unique();
            $finishedListIds = $userSubmissions
                ->whereIn('status', ['completed', 'reviewed'])
                ->pluck('list_id')
                ->unique();
            $inProgressSubmissions = $userSubmissions->whereIn('status', ['in_progress', 'redo_requested']);
            $inProgressListIds = $inProgressSubmissions->pluck('list_id')->unique();
            $pendingReview = $userSubmissions->where('status', 'completed')->count();

            $totalListIds = $openListIds
                ->merge($finishedListIds)
                ->merge($inProgressListIds)
                ->unique();

            $totalLists = $totalListIds->count();
            $finishedLists = $finishedListIds->count();
            $inProgressLists = $inProgressListIds->count();
            $openLists = $openListIds->diff($finishedListIds)->diff($inProgressListIds)->count();

            if ($totalLists === 0 && $userSubmissions->isEmpty()) {
                continue;
            }

            $listProgressValues = $totalListIds
                ->map(fn (int $listId) => $this->listProgress(
                    $userSubmissions->firstWhere('list_id', $listId)
                ))
                ->values();

            $completionRate = $listProgressValues->isNotEmpty()
                ? round($listProgressValues->avg(), 1)
                : 0;

            $teamProgressSum += $listProgressValues->sum();
            $teamProgressCount += $listProgressValues->count();

            $activeSubmission = $inProgressSubmissions->sortByDesc('updated_at')->first();
            $isActiveNow = $activeSubmission !== null
                && $activeSubmission->updated_at >= now()->subMinutes(15);
            $activeListProgress = $activeSubmission
                ? (int) round($this->listProgress($activeSubmission))
                : null;

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
            'pending_review' => array_sum(array_column($rows, 'pending_review')),
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

        $done = $tasks->whereIn('status', ['completed', 'approved'])->count();

        return round(($done / $total) * 100, 1);
    }
}

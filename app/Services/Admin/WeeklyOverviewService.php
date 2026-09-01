<?php

namespace App\Services\Admin;

use App\Helpers\MetricValidationHelper;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class WeeklyOverviewService
{
    /**
     * @return array<string, mixed>
     */
    public function buildSummary(int $companyId, Carbon $start, Carbon $end, ?int $locationId = null): array
    {
        $submissions = $this->periodSubmissions($companyId, $start, $end, $locationId);

        $total = $submissions->count();
        $completed = $submissions->where('status', 'completed')->count();
        $reviewed = $submissions->where('status', 'reviewed')->count();
        $inProgress = $submissions->where('status', 'in_progress')->count();
        $rejected = $submissions->where('status', 'rejected')->count();
        $finished = $completed + $reviewed;

        $completionRate = $total > 0 ? round(($finished / $total) * 100, 1) : 0;

        $today = $this->scopedQuery($companyId, $locationId)
            ->whereDate('created_at', today())
            ->count();

        $yesterday = $this->scopedQuery($companyId, $locationId)
            ->whereDate('created_at', today()->subDay())
            ->count();

        $growthRate = $yesterday > 0
            ? round((($today - $yesterday) / $yesterday) * 100, 1)
            : 0;

        $thisWeekTotal = $this->scopedQuery($companyId, $locationId)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        $lastWeekTotal = $this->scopedQuery($companyId, $locationId)
            ->whereBetween('created_at', [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()])
            ->count();

        $weeklyGrowth = $lastWeekTotal > 0
            ? round((($thisWeekTotal - $lastWeekTotal) / $lastWeekTotal) * 100, 1)
            : 0;

        $productivityScore = $completionRate >= 80
            ? 'Uitstekend'
            : ($completionRate >= 60 ? 'Goed' : ($completionRate >= 40 ? 'Matig' : 'Verbetering nodig'));

        return [
            'total_lists' => $total,
            'completed' => $completed,
            'reviewed' => $reviewed,
            'finished' => $finished,
            'in_progress' => $inProgress,
            'rejected' => $rejected,
            'completion_rate' => $completionRate,
            'today_lists' => $today,
            'growth_rate' => $growthRate,
            'weekly_growth' => $weeklyGrowth,
            'productivity_score' => $productivityScore,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function buildEmployeeOverview(int $companyId, Carbon $start, Carbon $end, ?int $locationId = null): array
    {
        $employees = User::query()
            ->where('company_id', $companyId)
            ->where('role', 'employee')
            ->where('is_active', true)
            ->when($locationId, fn ($query) => $query->where('location_id', $locationId))
            ->with(['submissions' => function ($query) use ($start, $end, $locationId) {
                $query->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
                    ->when($locationId, function ($submissionQuery) use ($locationId) {
                        $submissionQuery->whereHas('taskList', fn ($taskListQuery) => $taskListQuery->where('location_id', $locationId));
                    })
                    ->with(['taskList:id,title']);
            }])
            ->orderBy('name')
            ->get();

        $overview = [];

        foreach ($employees as $employee) {
            $totalSubmissions = $employee->submissions->count();
            if ($totalSubmissions === 0) {
                continue;
            }

            $completed = $employee->submissions->where('status', 'completed')->count();
            $reviewed = $employee->submissions->where('status', 'reviewed')->count();
            $inProgress = $employee->submissions->where('status', 'in_progress')->count();
            $rejected = $employee->submissions->where('status', 'rejected')->count();
            $finished = $completed + $reviewed;

            $overview[] = [
                'employee' => $employee,
                'name' => $employee->name,
                'department' => $employee->department,
                'total_submissions' => $totalSubmissions,
                'completed' => $completed,
                'reviewed' => $reviewed,
                'finished' => $finished,
                'in_progress' => $inProgress,
                'rejected' => $rejected,
                'completion_rate' => round(($finished / $totalSubmissions) * 100, 1),
            ];
        }

        usort($overview, fn (array $a, array $b) => $b['completion_rate'] <=> $a['completion_rate']);

        return $overview;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function buildTopLists(int $companyId, Carbon $start, Carbon $end, ?int $locationId = null, int $limit = 12): array
    {
        return TaskList::query()
            ->withCount(['submissions as period_submissions_count' => function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()]);
            }])
            ->where('company_id', $companyId)
            ->where('is_active', true)
            ->when($locationId, fn ($query) => $query->where('location_id', $locationId))
            ->whereHas('submissions', fn ($query) => $query->whereBetween('created_at', [
                $start->copy()->startOfDay(),
                $end->copy()->endOfDay(),
            ]))
            ->orderByDesc('period_submissions_count')
            ->orderBy('title')
            ->take($limit)
            ->get(['id', 'title', 'priority'])
            ->map(fn (TaskList $list) => [
                'title' => $list->title,
                'submissions_count' => (int) $list->period_submissions_count,
                'priority' => $list->priority,
            ])
            ->all();
    }

    /**
     * Only tasks that need attention: comments, review issues or measurements outside their norm.
     * Normal completed tasks are deliberately excluded.
     *
     * @return array<int, array<string, mixed>>
     */
    public function buildAttentionPoints(int $companyId, Carbon $start, Carbon $end, ?int $locationId = null): array
    {
        $tasks = SubmissionTask::query()
            ->whereHas('submission', function ($query) use ($companyId, $start, $end, $locationId) {
                $query->where('company_id', $companyId)
                    ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
                    ->when($locationId, fn ($submissionQuery) => $submissionQuery->whereHas(
                        'taskList',
                        fn ($taskListQuery) => $taskListQuery->where('location_id', $locationId)
                    ));
            })
            ->with(['task:id,list_id,title,validation_rules', 'submission:id,list_id,user_id,created_at', 'submission.taskList:id,title', 'submission.user:id,name'])
            ->orderBy('submission_id')
            ->orderBy('id')
            ->get()
            ->map(function (SubmissionTask $submissionTask) {
                $messages = collect([
                    $submissionTask->employee_comment,
                    $submissionTask->manager_comment,
                    $submissionTask->rejection_reason,
                    $submissionTask->redo_reason,
                    $submissionTask->corrective_action,
                    $submissionTask->verification_note,
                ])->filter(fn ($message) => filled($message))->map(fn ($message) => trim((string) $message))->unique()->values();

                $rules = $submissionTask->task?->validation_rules;
                if (MetricValidationHelper::isDeviation($rules, $submissionTask->proof_text)) {
                    $messages->prepend($this->metricDeviationLabel($rules, $submissionTask->proof_text));
                }

                if ($messages->isEmpty() && in_array($submissionTask->status, ['rejected', 'redo_requested'], true)) {
                    $messages->push($submissionTask->status === 'rejected' ? 'Afgekeurd' : 'Opnieuw uitvoeren');
                }

                if ($messages->isEmpty()) {
                    return null;
                }

                return [
                    'submission_id' => (int) $submissionTask->submission_id,
                    'list_title' => $submissionTask->submission?->taskList?->title ?? 'Onbekende lijst',
                    'employee_name' => $submissionTask->submission?->user?->name,
                    'submitted_at' => $submissionTask->submission?->created_at,
                    'task_title' => $submissionTask->task?->title ?? 'Onbekend punt',
                    'messages' => $messages->all(),
                ];
            })
            ->filter();

        return $tasks
            ->groupBy('submission_id')
            ->map(function (Collection $items) {
                $first = $items->first();

                return [
                    'list_title' => $first['list_title'],
                    'employee_name' => $first['employee_name'],
                    'submitted_at' => $first['submitted_at'],
                    'items' => $items->map(fn (array $item) => [
                        'task_title' => $item['task_title'],
                        'messages' => $item['messages'],
                    ])->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    private function metricDeviationLabel(?array $rules, ?string $proofText): string
    {
        $value = trim((string) $proofText);
        $unit = trim((string) ($rules['unit'] ?? ''));
        $bounds = [];

        if (isset($rules['min']) && is_numeric($rules['min'])) {
            $bounds[] = 'min. '.$rules['min'].$unit;
        }
        if (isset($rules['max']) && is_numeric($rules['max'])) {
            $bounds[] = 'max. '.$rules['max'].$unit;
        }

        return 'Afwijkende meting: '.$value.($bounds ? ' (norm '.implode(', ', $bounds).')' : '');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function buildChartData(int $companyId, Carbon $start, Carbon $end, ?int $locationId = null): array
    {
        $submissions = $this->periodSubmissions($companyId, $start, $end, $locationId);
        $rows = [];

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $daySubs = $submissions->filter(fn (Submission $submission) => $submission->created_at->isSameDay($date));
            $dayTotal = $daySubs->count();
            $dayFinished = $daySubs->whereIn('status', ['completed', 'reviewed'])->count();

            $rows[] = [
                'date' => $date->locale('nl')->translatedFormat('d M'),
                'submissions' => $dayTotal,
                'finished' => $dayFinished,
                'rate' => $dayTotal > 0 ? round(($dayFinished / $dayTotal) * 100, 1) : 0,
            ];
        }

        return $rows;
    }

    /**
     * @return Collection<int, Submission>
     */
    private function periodSubmissions(int $companyId, Carbon $start, Carbon $end, ?int $locationId): Collection
    {
        return $this->scopedQuery($companyId, $locationId)
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->get();
    }

    private function scopedQuery(int $companyId, ?int $locationId)
    {
        return Submission::query()
            ->where('company_id', $companyId)
            ->when($locationId, function ($query) use ($locationId) {
                $query->whereHas('taskList', fn ($taskListQuery) => $taskListQuery->where('location_id', $locationId));
            });
    }
}

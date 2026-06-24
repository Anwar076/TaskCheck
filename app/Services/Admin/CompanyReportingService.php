<?php

namespace App\Services\Admin;

use App\Models\Checklist\TaskList;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use Carbon\Carbon;

class CompanyReportingService
{
    /**
     * @return array<string, mixed>
     */
    public function buildReport(Company $company, string $frequency, Carbon $referenceTime): array
    {
        $timezone = 'Europe/Amsterdam';
        $now = $referenceTime->copy()->timezone($timezone);

        if ($frequency === Company::REPORTING_FREQUENCY_WEEKLY) {
            $periodStart = $now->copy()->startOfWeek();
            $periodEnd = $now->copy()->endOfWeek();
            $periodLabel = 'Weekrapportage';
        } else {
            $periodStart = $now->copy()->startOfDay();
            $periodEnd = $now->copy()->endOfDay();
            $periodLabel = 'Dagrapportage';
        }

        $submissionQuery = Submission::query()
            ->where('company_id', $company->id)
            ->whereBetween('created_at', [$periodStart->copy()->utc(), $periodEnd->copy()->utc()]);

        $totalSubmissions = (clone $submissionQuery)->count();
        $pendingReview = (clone $submissionQuery)->where('status', 'completed')->count();
        $reviewed = (clone $submissionQuery)->where('status', 'reviewed')->count();
        $rejected = (clone $submissionQuery)->where('status', 'rejected')->count();
        $inProgress = (clone $submissionQuery)->where('status', 'in_progress')->count();

        $topLists = TaskList::query()
            ->where('company_id', $company->id)
            ->withCount(['submissions' => function ($query) use ($periodStart, $periodEnd) {
                $query->whereBetween('created_at', [$periodStart->copy()->utc(), $periodEnd->copy()->utc()]);
            }])
            ->orderByDesc('submissions_count')
            ->take(5)
            ->get(['id', 'title'])
            ->filter(fn (TaskList $list) => $list->submissions_count > 0)
            ->values();

        $activeEmployees = User::query()
            ->where('company_id', $company->id)
            ->where('role', 'employee')
            ->where('is_active', true)
            ->count();

        return [
            'title' => $periodLabel,
            'frequency' => $frequency,
            'generated_at' => $now,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'stats' => [
                'total_submissions' => $totalSubmissions,
                'pending_review' => $pendingReview,
                'reviewed' => $reviewed,
                'rejected' => $rejected,
                'in_progress' => $inProgress,
                'active_employees' => $activeEmployees,
            ],
            'top_lists' => $topLists->map(fn (TaskList $list) => [
                'title' => $list->title,
                'submissions_count' => (int) $list->submissions_count,
            ])->all(),
            'dashboard_url' => route('admin.dashboard'),
        ];
    }
}

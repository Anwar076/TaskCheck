<?php

namespace App\Services\Admin;

use App\Mail\CompanyReportMail;
use App\Models\Organisation\Company;
use App\Models\Organisation\CompanyReportRecipient;
use App\Models\Organisation\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class CompanyReportingService
{
    public function __construct(
        private WeeklyOverviewService $weeklyOverviewService
    ) {}

    /** @return array<string, mixed> */
    public function sendNow(CompanyReportRecipient $recipient, ?Carbon $referenceTime = null): array
    {
        $recipient->loadMissing('company');
        $report = $this->buildReport(
            $recipient->company,
            $recipient->frequency,
            $referenceTime ?? now('Europe/Amsterdam'),
        );
        $report['sections'] = $recipient->normalizedSections();

        Mail::to($recipient->email)->send(new CompanyReportMail(
            $recipient->company,
            $report,
            $recipient->delivery_format,
        ));

        return $report;
    }

    /**
     * @return array<string, mixed>
     */
    public function buildReport(Company $company, string $frequency, Carbon $referenceTime): array
    {
        $timezone = 'Europe/Amsterdam';
        $now = $referenceTime->copy()->timezone($timezone);

        if ($frequency === Company::REPORTING_FREQUENCY_WEEKLY) {
            $periodEnd = $now->copy()->subWeek()->endOfWeek()->endOfDay();
            $periodStart = $periodEnd->copy()->startOfWeek()->startOfDay();
            $periodLabel = 'Weekrapportage';
            $periodDescription = 'Vorige week';
        } else {
            $periodStart = $now->copy()->subDay()->startOfDay();
            $periodEnd = $now->copy()->subDay()->endOfDay();
            $periodLabel = 'Dagrapportage';
            $periodDescription = 'Gisteren';
        }

        $companyId = (int) $company->id;
        $summary = $this->weeklyOverviewService->buildSummary($companyId, $periodStart, $periodEnd);
        $employeeOverview = $this->weeklyOverviewService->buildEmployeeOverview($companyId, $periodStart, $periodEnd);
        $topLists = $this->weeklyOverviewService->buildTopLists($companyId, $periodStart, $periodEnd, null, 10);
        $attentionPoints = $this->weeklyOverviewService->buildAttentionPoints($companyId, $periodStart, $periodEnd);
        $taskSummary = $this->weeklyOverviewService->buildTaskSummary($companyId, $periodStart, $periodEnd);

        $totalEmployees = User::query()
            ->where('company_id', $companyId)
            ->where('role', 'employee')
            ->where('is_active', true)
            ->count();

        $activeEmployees = count($employeeOverview);
        $summary['active_employees'] = $activeEmployees;
        $summary['total_employees'] = $totalEmployees;
        $summary['avg_lists_per_employee'] = $activeEmployees > 0
            ? round($summary['total_lists'] / $activeEmployees, 1)
            : 0;
        $summary['total_tasks'] = $taskSummary['total'];
        $summary['finished_tasks'] = $taskSummary['finished'];
        $summary['unfinished_tasks'] = $taskSummary['unfinished'];

        if ($frequency === Company::REPORTING_FREQUENCY_DAILY) {
            $previousDayStart = $periodStart->copy()->subDay()->startOfDay();
            $previousDayEnd = $periodStart->copy()->subDay()->endOfDay();
            $previousTotal = $this->weeklyOverviewService->buildSummary($companyId, $previousDayStart, $previousDayEnd)['total_lists'];
            $summary['period_growth'] = $previousTotal > 0
                ? round((($summary['total_lists'] - $previousTotal) / $previousTotal) * 100, 1)
                : 0;
        } else {
            $previousWeekStart = $periodStart->copy()->subWeek()->startOfDay();
            $previousWeekEnd = $periodEnd->copy()->subWeek()->endOfDay();
            $previousTotal = $this->weeklyOverviewService->buildSummary($companyId, $previousWeekStart, $previousWeekEnd)['total_lists'];
            $summary['period_growth'] = $previousTotal > 0
                ? round((($summary['total_lists'] - $previousTotal) / $previousTotal) * 100, 1)
                : 0;
        }

        return [
            'title' => $periodLabel,
            'period_description' => $periodDescription,
            'frequency' => $frequency,
            'has_data' => ($summary['total_lists'] ?? 0) > 0,
            'generated_at' => $now,
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
            'summary' => $summary,
            'employee_overview' => array_map(
                fn (array $row) => array_diff_key($row, ['employee' => true]),
                $employeeOverview
            ),
            'top_lists' => $topLists,
            'attention_points' => $attentionPoints,
            'overview_url' => route('admin.weekly-overview', [
                'start_date' => $periodStart->toDateString(),
                'end_date' => $periodEnd->toDateString(),
            ]),
        ];
    }
}

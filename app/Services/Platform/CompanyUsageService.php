<?php

namespace App\Services\Platform;

use App\Models\Company;
use App\Models\ListAssignment;
use App\Models\Submission;
use App\Models\Task;
use App\Models\TaskList;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class CompanyUsageService
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function summarizeForCompanyIds(array $companyIds): array
    {
        if ($companyIds === []) {
            return [];
        }

        $now = now();
        $since7 = $now->copy()->subDays(7);
        $since30 = $now->copy()->subDays(30);

        $listStats = TaskList::query()
            ->whereIn('company_id', $companyIds)
            ->where(function ($q) {
                $q->where('is_template', false)->orWhereNull('is_template');
            })
            ->whereNull('parent_list_id')
            ->groupBy('company_id')
            ->selectRaw('company_id')
            ->selectRaw('COUNT(*) as task_lists_count')
            ->selectRaw('SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active_lists_count')
            ->get()
            ->keyBy('company_id');

        $assignmentStats = ListAssignment::query()
            ->join('lists', 'lists.id', '=', 'list_assignments.list_id')
            ->whereIn('lists.company_id', $companyIds)
            ->where('list_assignments.is_active', true)
            ->groupBy('lists.company_id')
            ->selectRaw('lists.company_id as company_id')
            ->selectRaw('COUNT(DISTINCT list_assignments.list_id) as assigned_lists_count')
            ->selectRaw('COUNT(*) as assignments_count')
            ->get()
            ->keyBy('company_id');

        $taskStats = Task::query()
            ->join('lists', 'lists.id', '=', 'tasks.list_id')
            ->whereIn('lists.company_id', $companyIds)
            ->where(function ($q) {
                $q->where('lists.is_template', false)->orWhereNull('lists.is_template');
            })
            ->groupBy('lists.company_id')
            ->selectRaw('lists.company_id as company_id')
            ->selectRaw('COUNT(*) as tasks_count')
            ->get()
            ->keyBy('company_id');

        $submissionRows = Submission::query()
            ->whereIn('company_id', $companyIds)
            ->groupBy('company_id')
            ->selectRaw('company_id')
            ->selectRaw('COUNT(*) as submissions_total')
            ->selectRaw('SUM(CASE WHEN COALESCE(completed_at, started_at, updated_at) >= ? THEN 1 ELSE 0 END) as submissions_7d', [$since7])
            ->selectRaw('SUM(CASE WHEN COALESCE(completed_at, started_at, updated_at) >= ? THEN 1 ELSE 0 END) as submissions_30d', [$since30])
            ->selectRaw('SUM(CASE WHEN status IN (\'completed\', \'reviewed\') AND COALESCE(completed_at, updated_at) >= ? THEN 1 ELSE 0 END) as completed_30d', [$since30])
            ->selectRaw('MAX(COALESCE(completed_at, started_at, updated_at)) as last_activity_at')
            ->get()
            ->keyBy('company_id');

        $activeUsers = Submission::query()
            ->whereIn('company_id', $companyIds)
            ->where('updated_at', '>=', $since30)
            ->groupBy('company_id')
            ->selectRaw('company_id')
            ->selectRaw('COUNT(DISTINCT user_id) as active_users_30d')
            ->pluck('active_users_30d', 'company_id');

        $result = [];

        foreach ($companyIds as $companyId) {
            $lists = $listStats->get($companyId);
            $assignments = $assignmentStats->get($companyId);
            $tasks = $taskStats->get($companyId);
            $subs = $submissionRows->get($companyId);

            $taskListsCount = (int) ($lists?->task_lists_count ?? 0);
            $activeListsCount = (int) ($lists?->active_lists_count ?? 0);
            $submissionsTotal = (int) ($subs?->submissions_total ?? 0);
            $submissions7d = (int) ($subs?->submissions_7d ?? 0);
            $submissions30d = (int) ($subs?->submissions_30d ?? 0);
            $completed30d = (int) ($subs?->completed_30d ?? 0);
            $activeUsers30d = (int) ($activeUsers[$companyId] ?? 0);
            $lastActivity = $subs?->last_activity_at
                ? Carbon::parse($subs->last_activity_at)
                : null;

            $engagement = $this->resolveEngagement(
                $taskListsCount,
                $submissionsTotal,
                $submissions7d,
                $submissions30d
            );

            $result[$companyId] = [
                'task_lists_count' => $taskListsCount,
                'active_lists_count' => $activeListsCount,
                'assigned_lists_count' => (int) ($assignments?->assigned_lists_count ?? 0),
                'assignments_count' => (int) ($assignments?->assignments_count ?? 0),
                'tasks_count' => (int) ($tasks?->tasks_count ?? 0),
                'submissions_total' => $submissionsTotal,
                'submissions_7d' => $submissions7d,
                'submissions_30d' => $submissions30d,
                'completed_30d' => $completed30d,
                'active_users_30d' => $activeUsers30d,
                'last_activity_at' => $lastActivity,
                'engagement' => $engagement['key'],
                'engagement_label' => $engagement['label'],
                'engagement_color' => $engagement['color'],
            ];
        }

        return $result;
    }

    /**
     * @return array{companies: Collection<int, Company>, usage: array<int, array<string, mixed>>, summary: array<string, int>}
     */
    public function buildUsageOverview(?string $filter = null): array
    {
        $companies = Company::query()
            ->withCount([
                'users as total_users',
                'users as employee_users' => fn ($q) => $q->where('role', 'employee'),
            ])
            ->orderByDesc('created_at')
            ->get();

        $usage = $this->summarizeForCompanyIds($companies->pluck('id')->all());

        $companies->each(function (Company $company) use ($usage) {
            $company->usage = $usage[$company->id] ?? $this->emptyUsage();
        });

        if ($filter && $filter !== 'all') {
            $companies = $companies->filter(
                fn (Company $company) => ($company->usage['engagement'] ?? '') === $filter
            )->values();
        }

        $allUsage = collect($usage);
        $summary = [
            'active' => $allUsage->where('engagement', 'active')->count() + $allUsage->where('engagement', 'power')->count(),
            'power' => $allUsage->where('engagement', 'power')->count(),
            'low' => $allUsage->where('engagement', 'low')->count(),
            'not_started' => $allUsage->where('engagement', 'not_started')->count(),
            'dormant' => $allUsage->where('engagement', 'dormant')->count(),
            'inactive' => $allUsage->where('engagement', 'inactive')->count(),
        ];

        return [
            'companies' => $companies,
            'usage' => $usage,
            'summary' => $summary,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function emptyUsage(): array
    {
        return [
            'task_lists_count' => 0,
            'active_lists_count' => 0,
            'assigned_lists_count' => 0,
            'assignments_count' => 0,
            'tasks_count' => 0,
            'submissions_total' => 0,
            'submissions_7d' => 0,
            'submissions_30d' => 0,
            'completed_30d' => 0,
            'active_users_30d' => 0,
            'last_activity_at' => null,
            'engagement' => 'inactive',
            'engagement_label' => 'Inactief',
            'engagement_color' => 'slate',
        ];
    }

    /**
     * @return array{key: string, label: string, color: string}
     */
    private function resolveEngagement(int $lists, int $total, int $last7, int $last30): array
    {
        if ($lists === 0) {
            return ['key' => 'inactive', 'label' => 'Geen lijsten', 'color' => 'slate'];
        }

        if ($total === 0) {
            return ['key' => 'not_started', 'label' => 'Nog geen gebruik', 'color' => 'amber'];
        }

        if ($last30 === 0) {
            return ['key' => 'dormant', 'label' => 'Slapend', 'color' => 'orange'];
        }

        if ($last30 >= 20 || $last7 >= 10) {
            return ['key' => 'power', 'label' => 'Zwaar gebruik', 'color' => 'emerald'];
        }

        if ($last30 >= 5 || $last7 >= 2) {
            return ['key' => 'active', 'label' => 'Actief', 'color' => 'blue'];
        }

        return ['key' => 'low', 'label' => 'Weinig actief', 'color' => 'violet'];
    }
}

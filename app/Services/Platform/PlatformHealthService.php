<?php

namespace App\Services\Platform;

use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PlatformHealthService
{
    public function snapshot(): array
    {
        $windowMinutes = max(1, (int) config('platform_alerts.session_window_minutes', 15));
        $cutoff = now()->subMinutes($windowMinutes)->getTimestamp();

        $sessionsQuery = DB::table('sessions')->where('last_activity', '>=', $cutoff);

        $activeSessions = (clone $sessionsQuery)
            ->whereNotNull('user_id')
            ->count();

        $activeUsers = (int) (clone $sessionsQuery)
            ->whereNotNull('user_id')
            ->distinct()
            ->count('user_id');

        $submissionsActivityWindow = max(1, (int) config('platform_alerts.submissions_activity_window_minutes', 60));
        $submissionsActivityCutoff = now()->subMinutes($submissionsActivityWindow);

        $submissionsInProgressTotal = Submission::withoutGlobalScope('company')
            ->where('status', 'in_progress')
            ->count();

        $submissionsInProgressActive = Submission::withoutGlobalScope('company')
            ->where('status', 'in_progress')
            ->where('updated_at', '>=', $submissionsActivityCutoff)
            ->count();

        $failureWindow = max(1, (int) config('platform_alerts.failed_jobs_window_minutes', 15));
        $stalledMinutes = max(1, (int) config('platform_alerts.stalled_jobs_minutes', 15));
        $queue = config('queue.connections.'.config('queue.default'), []);
        $queueConnection = $queue['connection'] ?? null;
        $queueTable = $queue['table'] ?? 'jobs';
        $queueAvailable = ($queue['driver'] ?? '') === 'database'
            && Schema::connection($queueConnection)->hasTable($queueTable);
        $stalledJobs = null;
        if ($queueAvailable) {
            $cutoff = now()->subMinutes($stalledMinutes)->timestamp;
            // Future scheduled tasks and jobs currently being processed are not a backlog.
            // An expired reservation counts too: its worker may have stopped.
            $stalledJobs = DB::connection($queueConnection)->table($queueTable)
                ->where('available_at', '<=', $cutoff)
                ->where(function ($query) use ($cutoff) {
                    $query->whereNull('reserved_at')->orWhere('reserved_at', '<=', $cutoff);
                })->count();
        }
        $failedConfig = config('queue.failed', []);
        $failedConnection = $failedConfig['database'] ?? null;
        $failedTable = $failedConfig['table'] ?? 'failed_jobs';
        $failuresAvailable = in_array($failedConfig['driver'] ?? '', ['database', 'database-uuids'], true)
            && Schema::connection($failedConnection)->hasTable($failedTable);
        $recentFailures = $failuresAvailable
            ? DB::connection($failedConnection)->table($failedTable)->where('failed_at', '>=', now()->subMinutes($failureWindow))->count()
            : null;

        $metrics = [
            'active_users' => $activeUsers,
            'active_sessions' => $activeSessions,
            'submissions_in_progress' => $submissionsInProgressActive,
            'submissions_in_progress_total' => $submissionsInProgressTotal,
            'submissions_activity_window_minutes' => $submissionsActivityWindow,
            'stalled_jobs' => $stalledJobs,
            'failed_jobs' => $recentFailures,
            'failed_jobs_window_minutes' => $failureWindow,
            'stalled_jobs_minutes' => $stalledMinutes,
            'total_users' => User::query()->where('is_active', true)->count(),
            'total_companies' => Company::query()->count(),
            'session_window_minutes' => $windowMinutes,
            'checked_at' => now()->toIso8601String(),
        ];

        $thresholds = config('platform_alerts.thresholds', []);
        $labels = config('platform_alerts.labels', []);

        $alerts = [];
        foreach (['stalled_jobs', 'failed_jobs'] as $key) {
            $threshold = (int) ($thresholds[$key] ?? 0);
            $value = $metrics[$key];
            $alerts[$key] = [
                'key' => $key,
                'label' => $labels[$key] ?? $key,
                'value' => $value,
                'threshold' => $threshold,
                'available' => $value !== null,
                'exceeded' => $value !== null && $threshold > 0 && $value >= $threshold,
            ];
        }

        return [
            'metrics' => $metrics,
            'alerts' => $alerts,
            'thresholds' => $thresholds,
        ];
    }
}

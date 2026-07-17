<?php

namespace App\Services\Platform;

use App\Models\Organisation\Company;
use App\Models\Submissions\Submission;
use App\Models\Organisation\User;
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

        $submissionsInProgressTotal = Submission::query()
            ->where('status', 'in_progress')
            ->count();

        $submissionsInProgressActive = Submission::query()
            ->where('status', 'in_progress')
            ->where('updated_at', '>=', $submissionsActivityCutoff)
            ->count();

        $metrics = [
            'active_users' => $activeUsers,
            'active_sessions' => $activeSessions,
            'submissions_in_progress' => $submissionsInProgressActive,
            'submissions_in_progress_total' => $submissionsInProgressTotal,
            'submissions_activity_window_minutes' => $submissionsActivityWindow,
            'pending_jobs' => Schema::hasTable('jobs') ? (int) DB::table('jobs')->count() : 0,
            'failed_jobs' => Schema::hasTable('failed_jobs') ? (int) DB::table('failed_jobs')->count() : 0,
            'total_users' => User::query()->where('is_active', true)->count(),
            'total_companies' => Company::query()->count(),
            'session_window_minutes' => $windowMinutes,
            'checked_at' => now()->toIso8601String(),
        ];

        $thresholds = config('platform_alerts.thresholds', []);
        $labels = config('platform_alerts.labels', []);

        $submissionsMinActiveUsers = max(0, (int) config('platform_alerts.submissions_min_active_users', 5));

        $alerts = [];
        foreach ($thresholds as $key => $threshold) {
            $value = (int) ($metrics[$key] ?? 0);
            $exceeded = $threshold > 0 && $value >= $threshold;

            if ($key === 'submissions_in_progress' && $exceeded) {
                $exceeded = $metrics['active_users'] >= $submissionsMinActiveUsers;
            }

            $alerts[$key] = [
                'key' => $key,
                'label' => $labels[$key] ?? $key,
                'value' => $value,
                'threshold' => (int) $threshold,
                'exceeded' => $exceeded,
            ];
        }

        return [
            'metrics' => $metrics,
            'alerts' => $alerts,
            'thresholds' => $thresholds,
        ];
    }
}

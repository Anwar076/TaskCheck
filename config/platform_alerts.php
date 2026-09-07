<?php

return [

    'enabled' => env('PLATFORM_ALERTS_ENABLED', true),

    /*
    | Comma-separated. Falls back to SUPER_ADMIN_EMAILS when empty.
    */
    'recipients' => env('PLATFORM_ALERT_EMAIL', ''),

    'recovery_minutes' => (int) env('PLATFORM_ALERT_RECOVERY_MINUTES', 15),
    'failed_jobs_window_minutes' => (int) env('PLATFORM_ALERT_FAILED_JOBS_WINDOW_MINUTES', 15),
    'stalled_jobs_minutes' => (int) env('PLATFORM_ALERT_STALLED_JOBS_MINUTES', 15),

    /** Users with session activity in the last N minutes count as "active". */
    'session_window_minutes' => (int) env('PLATFORM_ALERT_SESSION_WINDOW_MINUTES', 15),

    /**
     * In-progress submissions updated within this window count as "active".
     * Open checklists that nobody touched recently are normal and should not trigger alerts.
     */
    'submissions_activity_window_minutes' => (int) env('PLATFORM_ALERT_SUBMISSIONS_ACTIVITY_WINDOW_MINUTES', 60),

    // Usage counts are informational; only processing problems trigger mail.
    'thresholds' => [
        'stalled_jobs' => (int) env('PLATFORM_ALERT_STALLED_JOBS_THRESHOLD', 1),
        'failed_jobs' => (int) env('PLATFORM_ALERT_FAILED_JOBS_THRESHOLD', 5),
    ],

    'labels' => [
        'active_users' => 'Actieve gebruikers (ingelogd)',
        'active_sessions' => 'Actieve sessies',
        'submissions_in_progress' => 'Actieve inzendingen bezig',
        'stalled_jobs' => 'Vertraagde achtergrondtaken',
        'pending_jobs' => 'Wachtrij jobs',
        'failed_jobs' => 'Mislukte jobs',
    ],

];

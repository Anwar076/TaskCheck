<?php

return [

    'enabled' => env('PLATFORM_ALERTS_ENABLED', true),

    /*
    | Comma-separated. Falls back to SUPER_ADMIN_EMAILS when empty.
    */
    'recipients' => env('PLATFORM_ALERT_EMAIL', ''),

    'cooldown_minutes' => (int) env('PLATFORM_ALERT_COOLDOWN_MINUTES', 60),

    /** Users with session activity in the last N minutes count as "active". */
    'session_window_minutes' => (int) env('PLATFORM_ALERT_SESSION_WINDOW_MINUTES', 15),

    /**
     * In-progress submissions updated within this window count as "active".
     * Open checklists that nobody touched recently are normal and should not trigger alerts.
     */
    'submissions_activity_window_minutes' => (int) env('PLATFORM_ALERT_SUBMISSIONS_ACTIVITY_WINDOW_MINUTES', 60),

    /** Only alert on active submissions when at least this many users are logged in. */
    'submissions_min_active_users' => (int) env('PLATFORM_ALERT_SUBMISSIONS_MIN_ACTIVE_USERS', 5),

    'thresholds' => [
        'active_users' => (int) env('PLATFORM_ALERT_ACTIVE_USERS_THRESHOLD', 100),
        'active_sessions' => (int) env('PLATFORM_ALERT_ACTIVE_SESSIONS_THRESHOLD', 150),
        'submissions_in_progress' => (int) env('PLATFORM_ALERT_SUBMISSIONS_IN_PROGRESS_THRESHOLD', 50),
        'pending_jobs' => (int) env('PLATFORM_ALERT_PENDING_JOBS_THRESHOLD', 50),
        'failed_jobs' => (int) env('PLATFORM_ALERT_FAILED_JOBS_THRESHOLD', 5),
    ],

    'labels' => [
        'active_users' => 'Actieve gebruikers (ingelogd)',
        'active_sessions' => 'Actieve sessies',
        'submissions_in_progress' => 'Actieve inzendingen bezig',
        'pending_jobs' => 'Wachtrij jobs',
        'failed_jobs' => 'Mislukte jobs',
    ],

];

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\Location;
use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use App\Services\Admin\TeamPerformanceService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected TeamPerformanceService $teamPerformanceService,
    ) {}

    public function index(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $selectedLocationId = null;

        if ($request->filled('location_id')) {
            $candidateLocationId = (int) $request->get('location_id');
            $locationExists = Location::where('company_id', $companyId)->where('id', $candidateLocationId)->exists();
            if ($locationExists) {
                $selectedLocationId = $candidateLocationId;
            }
        }

        $locations = Location::where('company_id', $companyId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $taskListQuery = TaskList::query()->where('company_id', $companyId);
        if ($selectedLocationId) {
            $taskListQuery->where('location_id', $selectedLocationId);
        }

        $submissionQuery = Submission::query()->whereHas('taskList', function ($query) use ($companyId, $selectedLocationId) {
            $query->where('company_id', $companyId);
            if ($selectedLocationId) {
                $query->where('location_id', $selectedLocationId);
            }
        });

        $tasksQuery = \App\Models\Checklist\Task::query()->whereHas('taskList', function ($query) use ($companyId, $selectedLocationId) {
            $query->where('company_id', $companyId);
            if ($selectedLocationId) {
                $query->where('location_id', $selectedLocationId);
            }
        });

        // Enhanced KPI Statistics
        $stats = [
            // Basic counts
            'total_employees' => User::where('company_id', $companyId)
                ->whereIn('role', ['employee', 'admin'])
                ->when($selectedLocationId, fn ($query) => $query->where('location_id', $selectedLocationId))
                ->count(),
            'total_admins' => User::where('company_id', $companyId)->where('role', 'admin')->count(),
            'total_users' => User::where('company_id', $companyId)->count(),
            'total_lists' => (clone $taskListQuery)->count(),
            'active_lists' => (clone $taskListQuery)->where('is_active', true)->count(),
            'total_tasks' => (clone $tasksQuery)->count(),

            // Submissions stats
            'total_submissions' => (clone $submissionQuery)->count(),
            'pending_submissions' => (clone $submissionQuery)
                ->where('status', 'completed')
                ->whereHas('taskList', fn ($query) => $query->where('requires_review', true))
                ->count(),
            'approved_submissions' => (clone $submissionQuery)->where('status', 'reviewed')->count(),
            'rejected_submissions' => (clone $submissionQuery)->where('status', 'rejected')->count(),

            // Today's activity
            'completed_today' => (clone $submissionQuery)->whereDate('completed_at', today())->count(),
            'started_today' => (clone $submissionQuery)->whereDate('created_at', today())->count(),
            'submissions_this_week' => (clone $submissionQuery)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'submissions_this_month' => (clone $submissionQuery)->whereMonth('created_at', now()->month)->count(),

            // Performance metrics
            'avg_completion_time' => $this->getAverageCompletionTime(),
            'completion_rate_today' => $this->getCompletionRate('today'),
            'completion_rate_week' => $this->getCompletionRate('week'),
            'completion_rate_month' => $this->getCompletionRate('month'),

            // Task statistics
            'most_used_proof_type' => $this->getMostUsedProofType($companyId, $selectedLocationId),
            'tasks_requiring_signature' => (clone $tasksQuery)->where('requires_signature', true)->count(),
            'hygiene_completion_percentage' => $this->getHorecaHygieneCompletionPercentage($companyId, $selectedLocationId),
            'critical_deviations' => $this->getHorecaCriticalDeviationsCount($companyId, $selectedLocationId),
        ];

        // Recent submissions for review
        $recentSubmissions = Submission::with(['user', 'taskList'])
            ->whereHas('taskList', function ($query) use ($companyId, $selectedLocationId) {
                $query->where('company_id', $companyId);
                if ($selectedLocationId) {
                    $query->where('location_id', $selectedLocationId);
                }
            })
            ->where('status', 'completed')
            ->whereHas('taskList', fn ($query) => $query->where('requires_review', true))
            ->latest()
            ->take(10)
            ->get();

        // Most rejected tasks (for improvement insights)
        $rejectedTasks = SubmissionTask::with(['task'])
            ->where('status', 'rejected')
            ->selectRaw('task_id, count(*) as rejection_count')
            ->groupBy('task_id')
            ->orderByDesc('rejection_count')
            ->take(5)
            ->get();

        // Team performance (today, list-based, refreshed via AJAX)
        $teamPerformance = $this->teamPerformanceService->build($companyId, $selectedLocationId);

        // Daily activity for the last 7 days
        $dailyActivity = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyActivity->push([
                'date' => $date->format('M j'),
                'submissions' => (clone $submissionQuery)->whereDate('created_at', $date)->count(),
                'completions' => (clone $submissionQuery)->whereDate('completed_at', $date)->count(),
            ]);
        }

        // List usage statistics
        $listStats = TaskList::withCount(['submissions'])
            ->where('company_id', $companyId)
            ->when($selectedLocationId, fn ($query) => $query->where('location_id', $selectedLocationId))
            ->orderByDesc('submissions_count')
            ->take(5)
            ->get();

        // Priority distribution
        $priorityStats = TaskList::selectRaw('priority, count(*) as count')
            ->where('company_id', $companyId)
            ->when($selectedLocationId, fn ($query) => $query->where('location_id', $selectedLocationId))
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority');

        return view('admin.dashboard', compact(
            'stats',
            'recentSubmissions',
            'rejectedTasks',
            'teamPerformance',
            'dailyActivity',
            'listStats',
            'priorityStats',
            'locations',
            'selectedLocationId'
        ));
    }

    public function teamPerformance(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $selectedLocationId = null;

        if ($request->filled('location_id')) {
            $candidateLocationId = (int) $request->get('location_id');
            $locationExists = Location::where('company_id', $companyId)->where('id', $candidateLocationId)->exists();
            if ($locationExists) {
                $selectedLocationId = $candidateLocationId;
            }
        }

        return response()->json($this->teamPerformanceService->build($companyId, $selectedLocationId));
    }

    private function getAverageCompletionTime()
    {
        $submissions = Submission::whereNotNull('completed_at')
            ->whereNotNull('started_at')
            ->where('completed_at', '>=', now()->subDays(30))
            ->get();

        if ($submissions->isEmpty()) {
            return 0;
        }

        $totalMinutes = $submissions->sum(function ($submission) {
            return $submission->started_at->diffInMinutes($submission->completed_at);
        });

        return round($totalMinutes / $submissions->count(), 1);
    }

    private function getCompletionRate($period)
    {
        $query = Submission::query();

        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month);
                break;
        }

        $total = $query->count();
        $completed = $query->where('status', 'completed')->count();

        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }

    private function getMostUsedProofType(int $companyId, ?int $selectedLocationId = null)
    {
        $proofType = \App\Models\Checklist\Task::selectRaw('required_proof_type, count(*) as count')
            ->whereHas('taskList', function ($query) use ($companyId, $selectedLocationId) {
                $query->where('company_id', $companyId);
                if ($selectedLocationId) {
                    $query->where('location_id', $selectedLocationId);
                }
            })
            ->groupBy('required_proof_type')
            ->orderByDesc('count')
            ->first();

        return $proofType ? ucfirst($proofType->required_proof_type) : 'None';
    }

    private function getHorecaHygieneCompletionPercentage(int $companyId, ?int $selectedLocationId = null): float
    {
        $query = Submission::query()
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->whereHas('taskList', function ($taskListQuery) use ($companyId, $selectedLocationId) {
                $taskListQuery->where('company_id', $companyId)
                    ->where('category', 'Horeca');
                if ($selectedLocationId) {
                    $taskListQuery->where('location_id', $selectedLocationId);
                }
            });

        $total = (clone $query)->count();
        $completed = (clone $query)->whereIn('status', ['completed', 'reviewed'])->count();

        return $total > 0 ? round(($completed / $total) * 100, 1) : 0.0;
    }

    private function getHorecaCriticalDeviationsCount(int $companyId, ?int $selectedLocationId = null): int
    {
        return SubmissionTask::query()
            ->whereIn('status', ['rejected', 'redo_requested'])
            ->whereHas('task', function ($taskQuery) use ($companyId, $selectedLocationId) {
                $taskQuery->whereHas('taskList', function ($taskListQuery) use ($companyId, $selectedLocationId) {
                    $taskListQuery->where('company_id', $companyId)
                        ->where('category', 'Horeca');
                    if ($selectedLocationId) {
                        $taskListQuery->where('location_id', $selectedLocationId);
                    }
                })->where(function ($validationQuery) {
                    $validationQuery->where('validation_rules', 'like', '%"critical":true%')
                        ->orWhere('validation_rules', 'like', '%"critical": true%');
                });
            })
            ->count();
    }

    public function liveMonitoring(Request $request)
    {
        $companyId = auth()->user()->company_id;
        $selectedLocationId = null;
        if ($request->filled('location_id')) {
            $candidateLocationId = (int) $request->get('location_id');
            $locationExists = Location::where('company_id', $companyId)->where('id', $candidateLocationId)->exists();
            if ($locationExists) {
                $selectedLocationId = $candidateLocationId;
            }
        }

        // Get active user sessions (submissions in progress)
        $activeSessions = Submission::with(['user', 'taskList', 'submissionTasks.task', 'submissionTasks.completedBy'])
            ->where('company_id', $companyId)
            ->whereHas('taskList', function ($query) use ($companyId, $selectedLocationId) {
                $query->where('company_id', $companyId);
                if ($selectedLocationId) {
                    $query->where('location_id', $selectedLocationId);
                }
            })
            ->where(function ($query) {
                $query->where('status', 'in_progress')
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereNull('completed_at')
                            ->where('created_at', '>=', now()->subHours(4));
                    });
            })
            ->latest('updated_at')
            ->get()
            ->filter(function ($submission) {
                // Only show submissions with actual task activity or recent creation
                $hasRecentTaskActivity = $submission->submissionTasks
                    ->where('updated_at', '>=', now()->subHours(2))
                    ->count() > 0;

                $isRecentlyCreated = $submission->created_at >= now()->subHours(2);

                return $hasRecentTaskActivity || $isRecentlyCreated;
            })
            ->map(fn ($submission) => $this->mapLiveActiveSession($submission))
            ->values();

        // Get recently completed submissions (last 2 hours)
        $recentCompletions = Submission::with(['user', 'taskList', 'submissionTasks.completedBy'])
            ->where('company_id', $companyId)
            ->whereHas('taskList', function ($query) use ($companyId, $selectedLocationId) {
                $query->where('company_id', $companyId);
                if ($selectedLocationId) {
                    $query->where('location_id', $selectedLocationId);
                }
            })
            ->whereIn('status', ['completed', 'reviewed'])
            ->where('completed_at', '>=', now()->subHours(2))
            ->latest('completed_at')
            ->take(5)
            ->get()
            ->map(function ($submission) {
                $completionTime = $submission->started_at && $submission->completed_at ?
                    $submission->started_at->diffInMinutes($submission->completed_at) :
                    ($submission->created_at && $submission->completed_at ?
                        $submission->created_at->diffInMinutes($submission->completed_at) : 0);

                return [
                    'user_name' => $this->liveSessionParticipantLabel($submission),
                    'task_list_title' => $submission->taskList->title,
                    'completed_at' => $submission->completed_at->diffForHumans(),
                    'completion_time' => $completionTime,
                    'is_team_submission' => (bool) $submission->is_team_submission,
                ];
            });

        // Get users who started tasks in the last hour but haven't been active
        $staleUsers = Submission::with(['user', 'taskList', 'submissionTasks.completedBy'])
            ->where('company_id', $companyId)
            ->whereHas('taskList', function ($query) use ($companyId, $selectedLocationId) {
                $query->where('company_id', $companyId);
                if ($selectedLocationId) {
                    $query->where('location_id', $selectedLocationId);
                }
            })
            ->where('status', '!=', 'completed')
            ->where('created_at', '>=', now()->subHours(1))
            ->where('updated_at', '<', now()->subMinutes(30))
            ->latest('updated_at')
            ->take(3)
            ->get()
            ->map(function ($submission) {
                return [
                    'user_name' => $this->liveSessionParticipantLabel($submission),
                    'task_list_title' => $submission->taskList->title,
                    'inactive_duration' => $submission->updated_at->diffForHumans(),
                    'is_team_submission' => (bool) $submission->is_team_submission,
                ];
            });

        $activeParticipantCount = $activeSessions->sum(function (array $session) {
            if (! empty($session['is_team_submission']) && ! empty($session['active_workers'])) {
                return count($session['active_workers']);
            }

            return 1;
        });

        return response()->json([
            'activeSessions' => $activeSessions,
            'recentCompletions' => $recentCompletions,
            'staleUsers' => $staleUsers,
            'timestamp' => now()->toISOString(),
            'selected_location_id' => $selectedLocationId,
            'summary' => [
                'active_users' => $activeParticipantCount,
                'avg_progress' => round($activeSessions->avg('progress_percentage') ?? 0, 1),
                'completed_last_2h' => $recentCompletions->count(),
                'stale_sessions' => $staleUsers->count(),
            ],
        ]);
    }

    private function mapLiveActiveSession(Submission $submission): array
    {
        $totalTasks = $submission->submissionTasks->count();
        $completedTasks = $submission->submissionTasks
            ->whereIn('status', ['completed', 'approved'])
            ->count();
        $progressPercentage = $totalTasks > 0
            ? (int) round(($completedTasks / $totalTasks) * 100)
            : 0;

        $currentTask = $submission->submissionTasks
            ->whereIn('status', ['in_progress', 'pending'])
            ->first();

        $status = 'Active';
        $lastActivity = $submission->updated_at;

        $recentTaskActivity = $submission->submissionTasks
            ->where('updated_at', '>=', now()->subMinutes(10))
            ->count();

        $mostRecentTaskUpdate = $submission->submissionTasks->max('updated_at');

        if ($mostRecentTaskUpdate && $mostRecentTaskUpdate > $lastActivity) {
            $lastActivity = $mostRecentTaskUpdate;
        }

        if ($recentTaskActivity > 0 || $lastActivity >= now()->subMinutes(10)) {
            $status = $currentTask ? 'Working' : 'Active';
        } elseif ($lastActivity >= now()->subMinutes(30)) {
            $status = 'Idle';
        } else {
            $status = 'Paused';
        }

        $timeActiveMinutes = $submission->started_at
            ? $submission->started_at->diffInMinutes(now())
            : $submission->created_at->diffInMinutes(now());

        $recentWorkers = $submission->submissionTasks
            ->where('updated_at', '>=', now()->subMinutes(30))
            ->map(fn ($task) => $task->completedBy)
            ->filter()
            ->unique('id')
            ->values();

        if ($recentWorkers->isEmpty() && $submission->user) {
            $recentWorkers = collect([$submission->user]);
        }

        return [
            'user_name' => $this->liveSessionParticipantLabel($submission, $recentWorkers),
            'user_id' => $submission->user?->id,
            'task_list_title' => $submission->taskList->title,
            'progress_percentage' => $progressPercentage,
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'current_task' => $currentTask
                ? (strlen($currentTask->task->description) > 50
                    ? substr($currentTask->task->description, 0, 50).'...'
                    : $currentTask->task->description)
                : 'Aan het starten...',
            'status' => $status,
            'started_at' => $submission->started_at
                ? $submission->started_at->diffForHumans()
                : $submission->created_at->diffForHumans(),
            'last_activity' => \Carbon\Carbon::parse($lastActivity)->diffForHumans(),
            'submission_id' => $submission->id,
            'time_active' => $this->formatTimeActive($timeActiveMinutes),
            'time_active_minutes' => $timeActiveMinutes,
            'recent_task_activity' => $recentTaskActivity,
            'is_team_submission' => (bool) $submission->is_team_submission,
            'active_workers' => $recentWorkers->map(fn ($user) => [
                'id' => $user->id,
                'name' => $user->name,
            ])->values()->all(),
        ];
    }

    private function liveSessionParticipantLabel(Submission $submission, $recentWorkers = null): string
    {
        if (! $submission->is_team_submission) {
            return (string) ($submission->user->name ?? 'Medewerker');
        }

        $workers = $recentWorkers ?? $submission->submissionTasks
            ->map(fn ($task) => $task->completedBy)
            ->filter()
            ->unique('id')
            ->values();

        if ($workers->isEmpty()) {
            return 'Team · '.($submission->user->name ?? 'checklist');
        }

        return 'Team · '.$workers->pluck('name')->join(', ');
    }

    private function formatTimeActive($minutes)
    {
        if ($minutes < 1) {
            return 'Net gestart';
        } elseif ($minutes < 60) {
            return round($minutes).' min';
        } else {
            $hours = floor($minutes / 60);
            $remainingMinutes = $minutes % 60;
            if ($remainingMinutes < 1) {
                return $hours.'u';
            } else {
                return $hours.'u '.round($remainingMinutes).'min';
            }
        }
    }
}

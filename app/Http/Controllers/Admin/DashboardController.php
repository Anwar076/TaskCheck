<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Submission;
use App\Models\SubmissionTask;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Enhanced KPI Statistics
        $stats = [
            // Basic counts
            'total_employees' => User::where('role', 'employee')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_users' => User::count(),
            'total_lists' => TaskList::count(),
            'active_lists' => TaskList::active()->count(),
            'total_tasks' => \App\Models\Task::count(),
            
            // Submissions stats
            'total_submissions' => Submission::count(),
            'pending_submissions' => Submission::where('status', 'completed')->count(),
            'approved_submissions' => Submission::where('status', 'reviewed')->count(),
            'rejected_submissions' => Submission::where('status', 'rejected')->count(),
            
            // Today's activity
            'completed_today' => Submission::whereDate('completed_at', today())->count(),
            'started_today' => Submission::whereDate('created_at', today())->count(),
            'submissions_this_week' => Submission::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'submissions_this_month' => Submission::whereMonth('created_at', now()->month)->count(),
            
            // Performance metrics
            'avg_completion_time' => $this->getAverageCompletionTime(),
            'completion_rate_today' => $this->getCompletionRate('today'),
            'completion_rate_week' => $this->getCompletionRate('week'),
            'completion_rate_month' => $this->getCompletionRate('month'),
            
            // Task statistics
            'most_used_proof_type' => $this->getMostUsedProofType(),
            'tasks_requiring_signature' => \App\Models\Task::where('requires_signature', true)->count(),
        ];

        // Recent submissions for review
        $recentSubmissions = Submission::with(['user', 'taskList'])
            ->where('status', 'completed')
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

        // Enhanced employee performance stats (last 30 days)
        $employeeStats = User::where('role', 'employee')
            ->withCount([
                'submissions as total_submissions' => function ($query) {
                    $query->where('created_at', '>=', now()->subDays(30));
                },
                'submissions as completed_submissions' => function ($query) {
                    $query->where('status', 'completed')
                          ->where('created_at', '>=', now()->subDays(30));
                }
            ])
            ->take(10)
            ->get()
            ->map(function ($user) {
                $user->completion_rate = $user->total_submissions > 0 
                    ? round(($user->completed_submissions / $user->total_submissions) * 100, 1)
                    : 0;
                return $user;
            });

        // Daily activity for the last 7 days
        $dailyActivity = collect();
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dailyActivity->push([
                'date' => $date->format('M j'),
                'submissions' => Submission::whereDate('created_at', $date)->count(),
                'completions' => Submission::whereDate('completed_at', $date)->count(),
            ]);
        }

        // List usage statistics
        $listStats = TaskList::withCount(['submissions'])
            ->orderByDesc('submissions_count')
            ->take(5)
            ->get();

        // Priority distribution
        $priorityStats = TaskList::selectRaw('priority, count(*) as count')
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority');

        return view('admin.dashboard', compact(
            'stats', 
            'recentSubmissions', 
            'rejectedTasks', 
            'employeeStats',
            'dailyActivity',
            'listStats',
            'priorityStats'
        ));
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

    private function getMostUsedProofType()
    {
        $proofType = \App\Models\Task::selectRaw('required_proof_type, count(*) as count')
            ->groupBy('required_proof_type')
            ->orderByDesc('count')
            ->first();

        return $proofType ? ucfirst($proofType->required_proof_type) : 'None';
    }
}

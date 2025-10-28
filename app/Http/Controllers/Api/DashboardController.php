<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TaskList;
use App\Models\Submission;
use App\Models\TaskTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get admin dashboard statistics
     */
    public function adminStats(): JsonResponse
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'active_users' => User::where('is_active', true)->count(),
                'total_lists' => TaskList::count(),
                'active_lists' => TaskList::where('is_active', true)->count(),
                'total_submissions' => Submission::count(),
                'pending_submissions' => Submission::where('status', 'pending')->count(),
                'completed_submissions' => Submission::where('status', 'completed')->count(),
                'total_templates' => TaskTemplate::count(),
                'active_templates' => TaskTemplate::where('is_active', true)->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Admin dashboard statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve admin statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get employee dashboard data
     */
    public function employeeData(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Get assigned lists
            $assignedLists = TaskList::whereHas('assignments', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('is_active', true);
            })
            ->with(['tasks', 'template'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();

            // Get user's submissions
            $submissions = Submission::where('user_id', $user->id)
                ->with(['taskList', 'submissionTasks.task'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Get recent notifications
            $notifications = $user->notifications()
                ->whereNull('read_at')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            $data = [
                'assigned_lists' => $assignedLists,
                'recent_submissions' => $submissions,
                'notifications' => $notifications,
                'user_stats' => [
                    'total_submissions' => Submission::where('user_id', $user->id)->count(),
                    'completed_submissions' => Submission::where('user_id', $user->id)
                        ->where('status', 'completed')->count(),
                    'pending_submissions' => Submission::where('user_id', $user->id)
                        ->whereIn('status', ['pending', 'in_progress'])->count(),
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Employee dashboard data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve employee data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get weekly overview data
     */
    public function weeklyOverview(Request $request): JsonResponse
    {
        try {
            $startDate = $request->get('start_date', now()->startOfWeek());
            $endDate = $request->get('end_date', now()->endOfWeek());

            $employees = User::where('role', 'employee')
                ->where('is_active', true)
                ->get();

            $overview = [];
            foreach ($employees as $employee) {
                $submissions = Submission::where('user_id', $employee->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->with(['taskList', 'submissionTasks'])
                    ->get();

                $totalSubmissions = $submissions->count();
                $completedSubmissions = $submissions->where('status', 'completed');
                $rejectedSubmissions = $submissions->filter(function ($submission) {
                    return $submission->hasRejectedTasks();
                });

                // Calculate completion rate
                $completionRate = $totalSubmissions > 0 ? 
                    round(($completedSubmissions->count() / $totalSubmissions) * 100, 1) : 0;

                // Calculate average completion time (in hours)
                $avgCompletionTime = 0;
                if ($completedSubmissions->count() > 0) {
                    $totalTime = $completedSubmissions->sum(function ($submission) {
                        $startTime = $submission->started_at ?? $submission->created_at;
                        $endTime = $submission->completed_at;
                        
                        if ($startTime && $endTime) {
                            $hours = $startTime->diffInHours($endTime);
                            return max($hours, 0.1);
                        }
                        return 0;
                    });
                    
                    $avgCompletionTime = $totalTime > 0 ? round($totalTime / $completedSubmissions->count(), 1) : 0;
                }

                // Calculate on-time rate
                $onTimeRate = 0;
                if ($completedSubmissions->count() > 0) {
                    $onTimeSubmissions = $completedSubmissions->filter(function ($submission) {
                        $startTime = $submission->started_at ?? $submission->created_at;
                        $expectedTime = $startTime->addHours(24);
                        return $submission->completed_at && $submission->completed_at <= $expectedTime;
                    });
                    $onTimeRate = round(($onTimeSubmissions->count() / $completedSubmissions->count()) * 100, 1);
                }

                // Calculate quality score
                $rejectionRate = $totalSubmissions > 0 ? 
                    ($rejectedSubmissions->count() / $totalSubmissions) * 100 : 0;
                
                $qualityScore = 0;
                if ($totalSubmissions > 0) {
                    $completionScore = ($completionRate / 100) * 3.5;
                    $rejectionScore = max(0, 1.5 - ($rejectionRate / 100) * 1.5);
                    $qualityScore = round($completionScore + $rejectionScore, 1);
                }

                $overview[$employee->id] = [
                    'employee' => $employee,
                    'total_submissions' => $totalSubmissions,
                    'completed' => $completedSubmissions->count(),
                    'in_progress' => $submissions->where('status', 'in_progress')->count(),
                    'rejected' => $rejectedSubmissions->count(),
                    'completion_rate' => $completionRate,
                    'avg_completion_time' => $avgCompletionTime,
                    'on_time_rate' => $onTimeRate,
                    'quality_score' => $qualityScore,
                    'submissions' => $submissions,
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'overview' => $overview,
                    'start_date' => $startDate,
                    'end_date' => $endDate
                ],
                'message' => 'Weekly overview retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve weekly overview: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recent activity
     */
    public function recentActivity(): JsonResponse
    {
        try {
            $activities = [];

            // Recent submissions
            $recentSubmissions = Submission::with(['user', 'taskList'])
                ->latest()
                ->limit(10)
                ->get();

            foreach ($recentSubmissions as $submission) {
                $activities[] = [
                    'type' => 'submission',
                    'message' => "{$submission->user->name} submitted list '{$submission->taskList->title}'",
                    'timestamp' => $submission->created_at,
                    'status' => $submission->status,
                ];
            }

            // Recent list assignments
            $recentAssignments = \App\Models\ListAssignment::with(['user', 'taskList'])
                ->latest()
                ->limit(10)
                ->get();

            foreach ($recentAssignments as $assignment) {
                $activities[] = [
                    'type' => 'assignment',
                    'message' => "List '{$assignment->taskList->title}' assigned to {$assignment->user->name}",
                    'timestamp' => $assignment->created_at,
                    'status' => 'assigned',
                ];
            }

            // Sort by timestamp
            usort($activities, function($a, $b) {
                return $b['timestamp']->timestamp - $a['timestamp']->timestamp;
            });

            return response()->json([
                'success' => true,
                'data' => array_slice($activities, 0, 20), // Limit to 20 most recent
                'message' => 'Recent activity retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve recent activity: ' . $e->getMessage()
            ], 500);
        }
    }
}

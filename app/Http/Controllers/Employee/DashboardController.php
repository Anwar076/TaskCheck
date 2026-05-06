<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\TaskList;
use App\Models\ListAssignment;
use App\Models\Submission;
use App\Models\SubmissionTask;
use App\Models\Notification;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function index()
    {
        $user = auth()->user();
        
        // Get assigned lists for today using the new ScheduleService
        $todaysLists = $this->scheduleService->getScheduledTasksForUser($user, today());
        
        // Load tasks with proper filtering - tasks with weekday should only show on that day
        $todayWeekday = strtolower(now()->format('l')); // monday, tuesday, etc.
        
        // Get completed task IDs for today's submissions
        $completedTaskIds = SubmissionTask::whereHas('submission', function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->whereDate('created_at', today());
        })->where('status', 'completed')
          ->pluck('task_id')
          ->toArray();
        
        $todaysLists->each(function ($list) use ($todayWeekday, $completedTaskIds) {
            // Always filter tasks by weekday - only show tasks for today or tasks without weekday (general tasks)
            $list->load(['tasks' => function ($query) use ($todayWeekday) {
                $query->where('is_active', true)
                      ->where(function ($q) use ($todayWeekday) {
                          $q->whereNull('weekday')      // General tasks (no specific day) - always show
                            ->orWhere('weekday', $todayWeekday); // Tasks for today's weekday
                      });
            }]);
            
            // Mark tasks as completed
            $list->tasks->each(function ($task) use ($completedTaskIds) {
                $task->is_completed = in_array($task->id, $completedTaskIds);
            });
        });
        
        // Get recent submissions
        $recentSubmissions = Submission::with(['taskList'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Get rejected tasks
        $rejectedTasks = SubmissionTask::with(['task', 'submission.taskList'])
            ->whereHas('submission', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'rejected')
            ->latest()
            ->take(10)
            ->get();

        // Get tasks that manager has requested to be redone (employee CAN and SHOULD redo now)
        $redoTasks = SubmissionTask::with(['task', 'submission.taskList'])
            ->whereHas('submission', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->where('status', 'redo_requested')
            ->latest()
            ->get();

        // Get unread notifications
        $notifications = $user->unreadNotifications()->latest()->take(5)->get();

        // Aantal taken vandaag (op basis van gefilterde taken in today's lists)
        $totalTasksToday = (int) $todaysLists->sum(function ($list) {
            return $list->tasks ? $list->tasks->count() : 0;
        });

        // Aantal voltooide TAKEN vandaag (niet inzendingen) - voor voortgangsbalk
        $todaysListIds = $todaysLists->pluck('id')->toArray();
        $completedTasksToday = SubmissionTask::whereHas('submission', function ($q) use ($user, $todaysListIds) {
            $q->where('user_id', $user->id)
                ->whereIn('list_id', $todaysListIds);
        })
            ->whereIn('status', ['completed', 'approved'])
            ->whereDate('completed_at', today())
            ->count();

        // Get statistics
        $stats = [
            'pending_tasks' => $todaysLists->count(),
            'completed_today' => $completedTasksToday,
            'total_tasks_today' => $totalTasksToday,
            'total_completed' => Submission::where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),
            'in_progress' => Submission::where('user_id', $user->id)
                ->where('status', 'in_progress')
                ->count(),
            'rejected_tasks' => $rejectedTasks->count(),
            'redo_tasks' => $redoTasks->count(),
            'unread_notifications' => $notifications->count(),
        ];

        return view('employee.dashboard', compact('todaysLists', 'recentSubmissions', 'rejectedTasks', 'redoTasks', 'notifications', 'stats'));
    }

    

    public function markNotificationAsRead($notificationId)
    {
        $notification = auth()->user()->notifications()->findOrFail($notificationId);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }
}

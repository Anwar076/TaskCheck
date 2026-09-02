<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Enums\SubmissionStatus;
use App\Enums\SubmissionTaskStatus;
use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use App\Services\Mobile\MobileSerializer;
use App\Services\Mobile\MobileTaskAccess;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class DashboardController extends MobileController
{
    public function __construct(
        protected ScheduleService $scheduleService,
        protected MobileTaskAccess $taskAccess,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();
        $todaysLists = $this->scheduleService->getScheduledTasksForUser($user, today());

        $todaysListItems = $todaysLists->map(function ($list) use ($user) {
            $list->load(['location']);
            $tasks = $this->taskAccess->tasksForToday($list);
            $list->setRelation('tasks', $tasks);
            $submission = $this->taskAccess->todaySubmission($user, $list);

            return MobileSerializer::taskListItem($list, $submission);
        })->values();

        $recentSubmissions = Submission::query()
            ->with(['taskList.location', 'submissionTasks'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($s) => MobileSerializer::submissionSummary($s))
            ->values();

        $notifications = $user->unreadNotifications()
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn ($n) => MobileSerializer::notification($n))
            ->values();

        $openTasks = (int) $todaysLists->sum(fn ($list) => $this->taskAccess->tasksForToday($list)->count());

        $completedToday = SubmissionTask::query()
            ->whereHas('submission', fn ($q) => $q->where('user_id', $user->id)->whereDate('created_at', today()))
            ->whereIn('status', SubmissionTaskStatus::finishedValues())
            ->whereDate('completed_at', today())
            ->count();

        $pendingReview = Submission::where('user_id', $user->id)->where('status', SubmissionStatus::COMPLETED->value)->count();
        $inProgress = Submission::where('user_id', $user->id)->where('status', SubmissionStatus::IN_PROGRESS->value)->count();

        $rejectedTasks = SubmissionTask::whereHas('submission', fn ($q) => $q->where('user_id', $user->id))
            ->where('status', SubmissionTaskStatus::REJECTED->value)
            ->count();

        $redoTasks = SubmissionTask::whereHas('submission', fn ($q) => $q->where('user_id', $user->id))
            ->where('status', SubmissionTaskStatus::REDO_REQUESTED->value)
            ->count();

        return $this->success([
            'user' => [
                'name' => $user->name,
                'role' => $user->role,
            ],
            'stats' => [
                'tasks_today' => $openTasks + $completedToday,
                'open_tasks' => $openTasks,
                'completed_today' => $completedToday,
                'pending_review' => $pendingReview,
                'in_progress' => $inProgress,
                'rejected_tasks' => $rejectedTasks,
                'redo_tasks' => $redoTasks,
            ],
            'todays_lists' => $todaysListItems,
            'notifications' => $notifications,
            'recent_submissions' => $recentSubmissions,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Toon nieuwste eerst, 20 per pagina
        $notifications = $user->notifications()
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('employee.notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        if (is_null($notification->read_at)) {
            $notification->read_at = now();
            $notification->save();
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        auth()->user()
            ->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->delete();

        return response()->json(['success' => true]);
    }

    public function createTaskOverdue(Request $request)
    {
        $validated = $request->validate([
            'task_id' => 'required|exists:tasks,id',
            'task_title' => 'required|string|max:255',
            'list_id' => 'nullable|exists:lists,id',
        ]);

        $user = auth()->user();
        
        // Check of taak al completed is (dan hoeft er geen notificatie)
        $task = \App\Models\Task::findOrFail($validated['task_id']);
        $submissionTask = \App\Models\SubmissionTask::where('task_id', $validated['task_id'])
            ->whereHas('submission', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereDate('created_at', today());
            })
            ->where('status', 'completed')
            ->first();

        if ($submissionTask) {
            // Taak is al completed, geen notificatie nodig
            return response()->json([
                'success' => false,
                'message' => 'Taak is al afgerond'
            ]);
        }

        // Maak notificatie aan
        $notification = \App\Models\Notification::createTaskOverdue(
            $user->id,
            $validated['task_title'],
            $validated['task_id'],
            $validated['list_id'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => 'Notificatie aangemaakt',
            'notification' => $notification
        ]);
    }

    public function realtimeFeed(Request $request)
    {
        $user = auth()->user();
        $afterId = (int) $request->query('after_id', 0);
        $latestUserNotificationId = (int) ($user->notifications()->max('id') ?? 0);

        // If client-side state is stale or from another account/device context,
        // reset the cursor so new notifications can be delivered again.
        if ($afterId > $latestUserNotificationId) {
            $afterId = 0;
        }

        $newNotifications = $user->notifications()
            ->when($afterId > 0, function ($query) use ($afterId) {
                $query->where('id', '>', $afterId);
            })
            ->orderByDesc('id')
            ->take(10)
            ->get(['id', 'title', 'message', 'type', 'read_at', 'created_at'])
            ->reverse()
            ->values();

        $latestNotificationId = (int) ($newNotifications->max('id') ?? $afterId);

        return response()->json([
            'success' => true,
            'after_id' => $latestNotificationId,
            'latest_user_notification_id' => $latestUserNotificationId,
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $newNotifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'read_at' => $notification->read_at?->toIso8601String(),
                    'created_at' => $notification->created_at?->toIso8601String(),
                ];
            }),
        ], 200, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}

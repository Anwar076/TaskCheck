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
}

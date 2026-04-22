<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        if (is_null($notification->read_at)) {
            $notification->read_at = now();
            $notification->save();
        }

        return response()->json([
            'success' => true,
            'unread_count' => auth()->user()->unreadNotifications()->count(),
        ]);
    }

    public function markAllAsRead()
    {
        $user = auth()->user();

        $user->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }

    public function realtimeFeed(Request $request)
    {
        $user = auth()->user();
        $afterId = (int) $request->query('after_id', 0);
        $latestUserNotificationId = (int) ($user->notifications()->max('id') ?? 0);

        if ($afterId > $latestUserNotificationId) {
            $afterId = 0;
        }

        $newNotifications = $user->notifications()
            ->when($afterId > 0, function ($query) use ($afterId) {
                $query->where('id', '>', $afterId);
            })
            ->orderByDesc('id')
            ->take(10)
            ->get(['id', 'title', 'message', 'type', 'data', 'read_at', 'created_at'])
            ->reverse()
            ->values();

        $unreadNotifications = $user->unreadNotifications()
            ->orderByDesc('id')
            ->take(10)
            ->get(['id', 'title', 'message', 'type', 'data', 'read_at', 'created_at'])
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
                    'data' => $notification->data ?? [],
                    'read_at' => $notification->read_at?->toIso8601String(),
                    'created_at' => $notification->created_at?->toIso8601String(),
                ];
            }),
            'unread_notifications' => $unreadNotifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'type' => $notification->type,
                    'data' => $notification->data ?? [],
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


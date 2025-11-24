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
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Communication\Notification;
use App\Models\Organisation\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $companyId = auth()->user()->company_id;

        $users = User::query()
            ->where('company_id', $companyId)
            ->whereIn('role', ['admin', 'employee'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role', 'department']);

        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $companyId = auth()->user()->company_id;

        $validated = $request->validate([
            'target' => ['required', Rule::in(['all', 'employees', 'admins', 'specific'])],
            'user_ids' => ['required_if:target,specific', 'array'],
            'user_ids.*' => [
                'integer',
                Rule::exists('users', 'id')->where(function ($query) use ($companyId) {
                    $query->where('company_id', $companyId)
                        ->where('is_active', true)
                        ->whereIn('role', ['admin', 'employee']);
                }),
            ],
            'title' => ['required', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $recipients = User::query()
            ->where('company_id', $companyId)
            ->whereIn('role', ['admin', 'employee'])
            ->where('is_active', true)
            ->when($validated['target'] === 'employees', fn ($query) => $query->where('role', 'employee'))
            ->when($validated['target'] === 'admins', fn ($query) => $query->where('role', 'admin'))
            ->when($validated['target'] === 'specific', fn ($query) => $query->whereIn('id', $validated['user_ids'] ?? []))
            ->orderBy('name')
            ->get(['id', 'role']);

        if ($recipients->isEmpty()) {
            return back()
                ->withInput()
                ->withErrors(['user_ids' => 'Selecteer minimaal één actieve gebruiker binnen je bedrijf.']);
        }

        DB::transaction(function () use ($recipients, $validated) {
            foreach ($recipients as $recipient) {
                Notification::create([
                    'user_id' => $recipient->id,
                    'type' => 'company_message',
                    'title' => $validated['title'],
                    'message' => $validated['message'],
                    'data' => [
                        'sender_id' => auth()->id(),
                        'sender_name' => auth()->user()->name,
                        'target' => $validated['target'],
                        'url' => $recipient->role === 'admin'
                            ? route('admin.notifications.index', [], false)
                            : route('employee.notifications.index', [], false),
                    ],
                ]);
            }
        });

        return redirect()
            ->route('admin.notifications.index')
            ->with('success', "Notificatie verzonden naar {$recipients->count()} gebruiker(s).");
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

        $newNotifications = collect();
        if ($afterId > 0) {
            $newNotifications = $user->notifications()
                ->where('id', '>', $afterId)
                ->orderByDesc('id')
                ->take(10)
                ->get(['id', 'title', 'message', 'type', 'data', 'read_at', 'created_at'])
                ->reverse()
                ->values();
        }

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

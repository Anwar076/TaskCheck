<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Models\Notification;
use App\Services\Mobile\MobileSerializer;
use Illuminate\Http\Request;

class NotificationController extends MobileController
{
    public function index(Request $request)
    {
        $user = $request->user();
        $perPage = (int) $request->get('per_page', 20);

        $paginator = Notification::query()
            ->where('user_id', $user->id)
            ->latest()
            ->paginate($perPage);

        $unreadCount = Notification::query()
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'success' => true,
            'data' => $paginator->getCollection()
                ->map(fn ($n) => MobileSerializer::notification($n))
                ->values(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
                'unread_count' => $unreadCount,
            ],
        ]);
    }

    public function unreadCount(Request $request)
    {
        $count = Notification::query()
            ->where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->count();

        return $this->success(['unread_count' => $count]);
    }

    public function markRead(Request $request, int $id)
    {
        $notification = Notification::query()
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        $notification->markAsRead();

        return $this->success(null, 'Melding gemarkeerd als gelezen.');
    }

    public function markAllRead(Request $request)
    {
        Notification::query()
            ->where('user_id', $request->user()->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return $this->success(null, 'Alle meldingen gemarkeerd als gelezen.');
    }
}

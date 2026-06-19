<?php

namespace App\Services\Notifications;

use App\Models\Communication\DevicePushToken;
use App\Models\Communication\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExpoPushService
{
    public function isConfigured(): bool
    {
        return (bool) config('services.expo.enabled', true);
    }

    public function sendForNotification(Notification $notification): void
    {
        if (!$this->isConfigured()) {
            return;
        }

        $tokens = DevicePushToken::query()
            ->where('user_id', $notification->user_id)
            ->pluck('expo_push_token')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($tokens === []) {
            return;
        }

        $messages = array_map(fn (string $token) => [
            'to' => $token,
            'title' => $notification->title ?: 'TaskCheck',
            'body' => $notification->message ?: 'Nieuwe melding',
            'data' => [
                'notification_id' => $notification->id,
                'type' => $notification->type,
                ...(is_array($notification->data) ? $notification->data : []),
            ],
            'sound' => 'default',
        ], $tokens);

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->post('https://exp.host/--/api/v2/push/send', $messages);

            if (!$response->successful()) {
                Log::warning('Expo push request failed', [
                    'notification_id' => $notification->id,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $e) {
            Log::warning('Expo push delivery error', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

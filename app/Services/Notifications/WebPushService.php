<?php

namespace App\Services\Notifications;

use App\Models\Notification;
use App\Models\PushSubscription;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class WebPushService
{
    public function isConfigured(): bool
    {
        return !empty(config('services.webpush.vapid.public_key'))
            && !empty(config('services.webpush.vapid.private_key'))
            && !empty(config('services.webpush.vapid.subject'));
    }

    public function sendForNotification(Notification $notification): void
    {
        if (!$this->isConfigured()) {
            return;
        }

        $subscriptions = PushSubscription::query()
            ->where('user_id', $notification->user_id)
            ->get();

        if ($subscriptions->isEmpty()) {
            return;
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => (string) config('services.webpush.vapid.subject'),
                'publicKey' => (string) config('services.webpush.vapid.public_key'),
                'privateKey' => (string) config('services.webpush.vapid.private_key'),
            ],
        ]);

        $payload = json_encode([
            'title' => $notification->title ?: 'TaskCheck',
            'body' => $notification->message ?: 'Nieuwe melding',
            'url' => $this->resolveTargetUrl($notification),
            'notification_id' => $notification->id,
        ], JSON_UNESCAPED_UNICODE);

        foreach ($subscriptions as $subscription) {
            $webPush->queueNotification(
                Subscription::create([
                    'endpoint' => $subscription->endpoint,
                    'publicKey' => $subscription->public_key,
                    'authToken' => $subscription->auth_token,
                    'contentEncoding' => $subscription->content_encoding ?: 'aes128gcm',
                ]),
                $payload
            );
        }

        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();

            if (!$report->isSuccess()) {
                Log::warning('Web push delivery failed', [
                    'notification_id' => $notification->id,
                    'user_id' => $notification->user_id,
                    'endpoint' => $endpoint,
                    'reason' => $report->getReason(),
                ]);

                if (in_array($report->getReason(), ['Expired', 'Not Found', '410 Gone'], true)) {
                    PushSubscription::where('endpoint', $endpoint)->delete();
                }
            }
        }
    }

    private function resolveTargetUrl(Notification $notification): string
    {
        $data = is_array($notification->data) ? $notification->data : [];
        if (!empty($data['url']) && is_string($data['url'])) {
            return $data['url'];
        }

        $user = $notification->user;
        if ($user && $user->role === 'admin') {
            if (!empty($data['submission_id'])) {
                return '/admin/submissions/'.$data['submission_id'];
            }

            return '/admin/dashboard';
        }

        return '/employee/notifications';
    }
}


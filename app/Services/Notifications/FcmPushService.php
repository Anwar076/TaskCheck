<?php

namespace App\Services\Notifications;

use App\Models\Communication\DevicePushToken;
use App\Models\Communication\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmPushService
{
    private static ?string $cachedAccessToken = null;
    private static int $cachedAccessTokenAt = 0;

    public function isConfigured(): bool
    {
        return (bool) config('services.fcm.enabled')
            && filled(config('services.fcm.project_id'))
            && filled(config('services.fcm.client_email'))
            && filled($this->privateKey());
    }

    public function sendForNotification(Notification $notification): void
    {
        if (! $this->isConfigured()) {
            return;
        }

        $tokens = DevicePushToken::query()
            ->where('user_id', $notification->user_id)
            ->where('push_provider', 'fcm')
            ->whereNotNull('native_push_token')
            ->pluck('native_push_token');

        foreach ($tokens as $token) {
            $this->send((string) $token, $notification);
        }
    }

    private function send(string $token, Notification $notification): void
    {
        $projectId = (string) config('services.fcm.project_id');
        $url = data_get($notification->data, 'url');

        try {
            $response = Http::withToken($this->accessToken())
                ->timeout(10)
                ->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
                    'message' => [
                        'token' => $token,
                        'notification' => [
                            'title' => $notification->title ?: 'TaskCheck',
                            'body' => $notification->message ?: 'Nieuwe melding',
                        ],
                        'data' => array_filter([
                            'notification_id' => (string) $notification->id,
                            'url' => is_string($url) ? $url : null,
                        ]),
                        'android' => [
                            'priority' => 'HIGH',
                            'notification' => [
                                'sound' => 'default',
                            ],
                        ],
                    ],
                ]);

            if ($response->successful()) {
                return;
            }

            $status = $response->json('error.status');
            $code = $response->json('error.details.0.errorCode');
            if (in_array($status, ['NOT_FOUND', 'INVALID_ARGUMENT'], true)
                || in_array($code, ['UNREGISTERED', 'INVALID_ARGUMENT'], true)) {
                DevicePushToken::where('native_push_token', $token)->delete();
            }

            Log::warning('FCM push request failed', [
                'notification_id' => $notification->id,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('FCM push delivery error', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function accessToken(): string
    {
        if (self::$cachedAccessToken && (time() - self::$cachedAccessTokenAt) < 3000) {
            return self::$cachedAccessToken;
        }

        $now = time();
        $header = $this->base64Url(json_encode([
            'alg' => 'RS256',
            'typ' => 'JWT',
        ], JSON_THROW_ON_ERROR));
        $payload = $this->base64Url(json_encode([
            'iss' => config('services.fcm.client_email'),
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'iat' => $now,
            'exp' => $now + 3600,
        ], JSON_THROW_ON_ERROR));
        $signingInput = "{$header}.{$payload}";

        if (! openssl_sign($signingInput, $signature, $this->privateKey(), OPENSSL_ALGO_SHA256)) {
            throw new \RuntimeException('FCM JWT kon niet worden ondertekend.');
        }

        $jwt = $signingInput.'.'.$this->base64Url($signature);
        $response = Http::asForm()->timeout(10)->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if (! $response->successful() || ! filled($response->json('access_token'))) {
            throw new \RuntimeException('FCM access token kon niet worden opgehaald.');
        }

        self::$cachedAccessTokenAt = $now;

        return self::$cachedAccessToken = (string) $response->json('access_token');
    }

    private function privateKey(): ?string
    {
        $key = config('services.fcm.private_key');
        if (! filled($key)) {
            return null;
        }

        return str_replace('\\n', "\n", (string) $key);
    }

    private function base64Url(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}

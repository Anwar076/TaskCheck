<?php

namespace App\Services\Notifications;

use App\Models\Communication\DevicePushToken;
use App\Models\Communication\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApnsPushService
{
    private static ?string $cachedJwt = null;
    private static int $cachedJwtAt = 0;

    public function isConfigured(): bool
    {
        return (bool) config('services.apns.enabled')
            && filled(config('services.apns.team_id'))
            && filled(config('services.apns.key_id'))
            && filled($this->privateKey());
    }

    public function sendForNotification(Notification $notification): void
    {
        if (!$this->isConfigured()) {
            return;
        }

        $tokens = DevicePushToken::query()
            ->where('user_id', $notification->user_id)
            ->where('push_provider', 'apns')
            ->whereNotNull('native_push_token')
            ->pluck('native_push_token');

        foreach ($tokens as $token) {
            $this->send((string) $token, $notification);
        }
    }

    private function send(string $token, Notification $notification): void
    {
        $host = config('services.apns.production')
            ? 'https://api.push.apple.com'
            : 'https://api.sandbox.push.apple.com';

        try {
            $response = Http::withToken($this->jwt())
                ->withOptions([
                    // APNs' provider API only accepts HTTP/2 requests.
                    'version' => 2.0,
                ])
                ->withHeaders([
                    'apns-topic' => (string) config('services.apns.bundle_id'),
                    'apns-push-type' => 'alert',
                    'apns-priority' => '10',
                ])
                ->timeout(10)
                ->post("{$host}/3/device/{$token}", [
                    'aps' => [
                        'alert' => [
                            'title' => $notification->title ?: 'TaskCheck',
                            'body' => $notification->message ?: 'Nieuwe melding',
                        ],
                        'sound' => 'default',
                        'badge' => Notification::query()
                            ->where('user_id', $notification->user_id)
                            ->whereNull('read_at')
                            ->count(),
                    ],
                    'notification_id' => $notification->id,
                    'url' => data_get($notification->data, 'url'),
                ]);

            if ($response->successful()) {
                return;
            }

            $reason = $response->json('reason');
            if (in_array($reason, ['BadDeviceToken', 'Unregistered', 'DeviceTokenNotForTopic'], true)) {
                DevicePushToken::where('native_push_token', $token)->delete();
            }

            Log::warning('APNs push request failed', [
                'notification_id' => $notification->id,
                'status' => $response->status(),
                'reason' => $reason,
            ]);
        } catch (\Throwable $e) {
            Log::warning('APNs push delivery error', [
                'notification_id' => $notification->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function jwt(): string
    {
        if (self::$cachedJwt && (time() - self::$cachedJwtAt) < 3000) {
            return self::$cachedJwt;
        }

        $header = $this->base64Url(json_encode([
            'alg' => 'ES256',
            'kid' => config('services.apns.key_id'),
        ], JSON_THROW_ON_ERROR));
        $payload = $this->base64Url(json_encode([
            'iss' => config('services.apns.team_id'),
            'iat' => time(),
        ], JSON_THROW_ON_ERROR));
        $signingInput = "{$header}.{$payload}";

        if (!openssl_sign($signingInput, $derSignature, $this->privateKey(), OPENSSL_ALGO_SHA256)) {
            throw new \RuntimeException('APNs JWT kon niet worden ondertekend.');
        }

        self::$cachedJwtAt = time();
        return self::$cachedJwt = $signingInput.'.'.$this->base64Url($this->derToJose($derSignature));
    }

    private function privateKey(): ?string
    {
        $inline = config('services.apns.private_key');
        if (filled($inline)) {
            return str_replace('\\n', "\n", (string) $inline);
        }

        $path = config('services.apns.private_key_path');
        return filled($path) && is_readable($path) ? file_get_contents($path) : null;
    }

    private function derToJose(string $der): string
    {
        $offset = 1;
        $this->readLength($der, $offset);
        if (ord($der[$offset++]) !== 0x02) {
            throw new \RuntimeException('Ongeldige APNs ECDSA-handtekening.');
        }
        $rLength = $this->readLength($der, $offset);
        $r = substr($der, $offset, $rLength);
        $offset += $rLength;
        if (ord($der[$offset++]) !== 0x02) {
            throw new \RuntimeException('Ongeldige APNs ECDSA-handtekening.');
        }
        $sLength = $this->readLength($der, $offset);
        $s = substr($der, $offset, $sLength);

        return str_pad(ltrim($r, "\0"), 32, "\0", STR_PAD_LEFT)
            .str_pad(ltrim($s, "\0"), 32, "\0", STR_PAD_LEFT);
    }

    private function readLength(string $value, int &$offset): int
    {
        $length = ord($value[$offset++]);
        if (($length & 0x80) === 0) {
            return $length;
        }
        $bytes = $length & 0x7f;
        $length = 0;
        while ($bytes-- > 0) {
            $length = ($length << 8) | ord($value[$offset++]);
        }
        return $length;
    }

    private function base64Url(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }
}

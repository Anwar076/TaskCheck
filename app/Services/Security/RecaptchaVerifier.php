<?php

namespace App\Services\Security;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class RecaptchaVerifier
{
    public function isConfigured(): bool
    {
        $siteKey = config('services.recaptcha.site_key');
        $secretKey = config('services.recaptcha.secret_key');

        return is_string($siteKey) && $siteKey !== ''
            && is_string($secretKey) && $secretKey !== '';
    }

    public function verify(?string $token, ?string $ip = null, string $expectedAction = 'contact'): bool
    {
        $secret = config('services.recaptcha.secret_key');
        $token = is_string($token) ? trim($token) : '';

        if (! is_string($secret) || $secret === '' || $token === '') {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(8)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $ip,
                ]);
        } catch (Throwable $e) {
            Log::warning('reCAPTCHA verification request failed', [
                'message' => $e->getMessage(),
            ]);

            return false;
        }

        if (! $response->successful()) {
            Log::warning('reCAPTCHA verification returned a non-success HTTP status', [
                'status' => $response->status(),
            ]);

            return false;
        }

        $payload = $response->json();
        if (! is_array($payload) || empty($payload['success'])) {
            return false;
        }

        $score = isset($payload['score']) ? (float) $payload['score'] : 0.0;
        $action = isset($payload['action']) ? (string) $payload['action'] : '';
        $threshold = (float) config('services.recaptcha.score_threshold', 0.5);

        if ($action !== $expectedAction || $score < $threshold) {
            Log::info('reCAPTCHA rejected a contact submission', [
                'action' => $action,
                'expected_action' => $expectedAction,
                'score' => $score,
                'threshold' => $threshold,
            ]);

            return false;
        }

        return true;
    }
}

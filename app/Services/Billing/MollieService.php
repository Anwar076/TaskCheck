<?php

namespace App\Services\Billing;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class MollieService
{
    private const API_BASE = 'https://api.mollie.com/v2';

    public function createCustomer(string $name, string $email): array
    {
        return $this->request('post', '/customers', [
            'name' => $name,
            'email' => $email,
        ]);
    }

    public function createFirstPayment(array $payload): array
    {
        return $this->request('post', '/payments', $payload);
    }

    public function getPayment(string $paymentId): array
    {
        return $this->request('get', "/payments/{$paymentId}");
    }

    public function createSubscription(string $customerId, array $payload): array
    {
        return $this->request('post', "/customers/{$customerId}/subscriptions", $payload);
    }

    public function cancelSubscription(string $customerId, string $subscriptionId): void
    {
        $this->request('delete', "/customers/{$customerId}/subscriptions/{$subscriptionId}");
    }

    private function request(string $method, string $endpoint, array $payload = []): array
    {
        $apiKey = (string) config('services.mollie.key');
        if ($apiKey === '') {
            throw new RuntimeException('Mollie API key ontbreekt. Zet MOLLIE_API_KEY in je .env.');
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->asJson()
            ->send($method, self::API_BASE.$endpoint, [
                'json' => $payload,
            ]);

        if ($response->failed()) {
            $message = $response->json('detail')
                ?? $response->json('title')
                ?? $response->body();

            throw new RuntimeException('Mollie API fout: '.$message);
        }

        return $response->json();
    }
}

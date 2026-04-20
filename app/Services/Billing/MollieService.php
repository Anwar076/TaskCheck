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
        $paymentId = trim($paymentId);
        if ($paymentId === '') {
            throw new RuntimeException('Lege Mollie payment-id ontvangen.');
        }

        return $this->request('get', '/payments/'.rawurlencode($paymentId));
    }

    public function getRecentCustomerPayments(string $customerId, int $limit = 10): array
    {
        $customerId = trim($customerId);
        if ($customerId === '') {
            return [];
        }

        $response = $this->request('get', '/customers/'.rawurlencode($customerId).'/payments?limit='.$limit);
        $payments = $response['_embedded']['payments'] ?? [];

        return is_array($payments) ? $payments : [];
    }

    public function createSubscription(string $customerId, array $payload): array
    {
        return $this->request('post', "/customers/{$customerId}/subscriptions", $payload);
    }

    public function updateSubscription(string $customerId, string $subscriptionId, array $payload): array
    {
        return $this->request('patch', "/customers/{$customerId}/subscriptions/{$subscriptionId}", $payload);
    }

    public function getSubscription(string $customerId, string $subscriptionId): array
    {
        $customerId = trim($customerId);
        $subscriptionId = trim($subscriptionId);
        if ($customerId === '' || $subscriptionId === '') {
            throw new RuntimeException('Lege Mollie customer-id of subscription-id ontvangen.');
        }

        return $this->request('get', '/customers/'.rawurlencode($customerId).'/subscriptions/'.rawurlencode($subscriptionId));
    }

    public function cancelSubscription(string $customerId, string $subscriptionId): void
    {
        $this->request('delete', "/customers/{$customerId}/subscriptions/{$subscriptionId}");
    }

    private function request(string $method, string $endpoint, array $payload = []): array
    {
        $apiKey = trim((string) config('services.mollie.key'));

        // Defensive normalization: users sometimes paste the full header value.
        if (str_starts_with(strtolower($apiKey), 'bearer ')) {
            $apiKey = trim(substr($apiKey, 7));
        }

        if ($apiKey === '') {
            throw new RuntimeException('Mollie API key ontbreekt. Zet MOLLIE_API_KEY in je .env.');
        }

        $http = Http::withToken($apiKey)->acceptJson();

        $options = [];
        $normalizedMethod = strtolower($method);
        if (in_array($normalizedMethod, ['get', 'delete'], true)) {
            if (!empty($payload)) {
                $options['query'] = $payload;
            }
        } else {
            $http = $http->asJson();
            $options['json'] = $payload;
        }

        $response = $http->send($normalizedMethod, self::API_BASE.$endpoint, $options);

        if ($response->failed()) {
            $statusCode = $response->status();
            $message = $response->json('detail')
                ?? $response->json('title')
                ?? $response->body();

            throw new RuntimeException("Mollie API fout ({$statusCode}): ".$message);
        }

        return $response->json();
    }
}

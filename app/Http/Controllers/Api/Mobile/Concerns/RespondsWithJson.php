<?php

namespace App\Http\Controllers\Api\Mobile\Concerns;

use Illuminate\Http\JsonResponse;

trait RespondsWithJson
{
    protected function success(mixed $data = null, ?string $message = null, int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'data' => $data];

        if ($message !== null) {
            $payload['message'] = $message;
        }

        return response()->json($payload, $status);
    }

    protected function error(string $message, int $status = 400, mixed $data = null): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($data !== null) {
            $payload['data'] = $data;
        }

        return response()->json($payload, $status);
    }
}

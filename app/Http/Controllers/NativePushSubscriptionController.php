<?php

namespace App\Http\Controllers;

use App\Models\Communication\DevicePushToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NativePushSubscriptionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => ['required', 'string', 'max:512'],
            'platform' => ['required', 'in:ios,android'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        DevicePushToken::updateOrCreate(
            ['native_push_token' => $validated['token']],
            [
                'user_id' => $request->user()->id,
                'expo_push_token' => null,
                'push_provider' => $validated['platform'] === 'android' ? 'fcm' : 'apns',
                'platform' => $validated['platform'],
                'device_name' => $validated['device_name'] ?? null,
            ]
        );

        return response()->json(['message' => 'Pushtoken geregistreerd.']);
    }

    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate(['token' => ['required', 'string', 'max:512']]);

        DevicePushToken::query()
            ->where('user_id', $request->user()->id)
            ->where('native_push_token', $validated['token'])
            ->delete();

        return response()->json(['message' => 'Pushtoken verwijderd.']);
    }
}

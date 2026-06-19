<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Models\Communication\DevicePushToken;
use Illuminate\Http\Request;

class PushController extends MobileController
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'expo_push_token' => ['required', 'string', 'max:255'],
            'platform' => ['nullable', 'string', 'max:50'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        DevicePushToken::updateOrCreate(
            ['expo_push_token' => $validated['expo_push_token']],
            [
                'user_id' => $user->id,
                'platform' => $validated['platform'] ?? null,
                'device_name' => $validated['device_name'] ?? null,
            ]
        );

        return $this->success(null, 'Push token geregistreerd.');
    }

    public function unregister(Request $request)
    {
        $validated = $request->validate([
            'expo_push_token' => ['required', 'string', 'max:255'],
        ]);

        DevicePushToken::query()
            ->where('user_id', $request->user()->id)
            ->where('expo_push_token', $validated['expo_push_token'])
            ->delete();

        return $this->success(null, 'Push token verwijderd.');
    }
}

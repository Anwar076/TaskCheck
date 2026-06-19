<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Models\Organisation\User;
use App\Services\Mobile\MobileSerializer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends MobileController
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['required', 'string', 'max:255'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['De inloggegevens zijn onjuist.'],
            ]);
        }

        if (!$user->is_active) {
            return $this->error('Account is gedeactiveerd.', 403);
        }

        $token = $user->createToken($validated['device_name'])->plainTextToken;

        return $this->success([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => MobileSerializer::user($user),
        ], 'Ingelogd.');
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return $this->success(null, 'Uitgelogd.');
    }

    public function me(Request $request)
    {
        return $this->success(MobileSerializer::user($request->user()));
    }
}

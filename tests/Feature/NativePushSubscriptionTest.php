<?php

namespace Tests\Feature;

use App\Models\Communication\DevicePushToken;
use App\Models\Communication\Notification;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NativePushSubscriptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_register_ios_push_token(): void
    {
        $user = $this->employee();

        $this->actingAs($user)
            ->postJson(route('push.native.subscribe'), [
                'token' => str_repeat('a', 64),
                'platform' => 'ios',
                'device_name' => 'iPhone',
            ])
            ->assertOk();

        $this->assertDatabaseHas('device_push_tokens', [
            'user_id' => $user->id,
            'native_push_token' => str_repeat('a', 64),
            'push_provider' => 'apns',
            'platform' => 'ios',
        ]);
    }

    public function test_authenticated_user_can_register_android_push_token(): void
    {
        $user = $this->employee();

        $this->actingAs($user)
            ->postJson(route('push.native.subscribe'), [
                'token' => 'fcm-token-'.str_repeat('b', 40),
                'platform' => 'android',
            ])
            ->assertOk();

        $this->assertDatabaseHas('device_push_tokens', [
            'user_id' => $user->id,
            'push_provider' => 'fcm',
            'platform' => 'android',
        ]);
    }

    public function test_guest_cannot_register_native_push_token(): void
    {
        $this->postJson(route('push.native.subscribe'), [
            'token' => str_repeat('a', 64),
            'platform' => 'ios',
        ])->assertUnauthorized();
    }

    public function test_creating_a_notification_does_not_fail_without_native_push_config(): void
    {
        $user = $this->employee();

        $notification = Notification::createListAssigned($user->id, 1, 'Opening');

        $this->assertNotNull($notification->id);
        $this->assertSame(0, DevicePushToken::query()->count());
    }

    private function employee(): User
    {
        $company = Company::query()->create([
            'name' => 'Acme',
            'address' => 'Teststraat 1',
            'phone' => '0612345678',
            'email' => 'acme@example.test',
            'subscription_status' => 'active',
            'subscription_ends_at' => now()->addMonth(),
            'is_active' => true,
            'onboarding_step' => Company::ONBOARDING_STEP_COMPLETED,
            'onboarding_completed_at' => now(),
        ]);

        return User::factory()->create([
            'company_id' => $company->id,
            'role' => 'employee',
            'is_active' => true,
        ]);
    }
}

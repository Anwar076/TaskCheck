<?php

namespace Tests\Feature;

use App\Models\Communication\Notification;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_send_notification_to_specific_company_users(): void
    {
        $company = $this->createCompany('Acme');
        $otherCompany = $this->createCompany('Other');

        $admin = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'admin',
            'is_active' => true,
        ]);
        $employee = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'employee',
            'is_active' => true,
        ]);
        $otherEmployee = User::factory()->create([
            'company_id' => $otherCompany->id,
            'role' => 'employee',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.notifications.store'), [
            'target' => 'specific',
            'user_ids' => [$employee->id],
            'title' => 'Rooster gewijzigd',
            'message' => 'Controleer je planning voor morgen.',
        ]);

        $response->assertRedirect(route('admin.notifications.index'));

        $this->assertDatabaseHas('notifications', [
            'user_id' => $employee->id,
            'type' => 'company_message',
            'title' => 'Rooster gewijzigd',
            'message' => 'Controleer je planning voor morgen.',
        ]);
        $this->assertDatabaseMissing('notifications', [
            'user_id' => $otherEmployee->id,
            'title' => 'Rooster gewijzigd',
        ]);
    }

    public function test_admin_cannot_send_notification_to_user_from_other_company(): void
    {
        $company = $this->createCompany('Acme');
        $otherCompany = $this->createCompany('Other');

        $admin = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'admin',
            'is_active' => true,
        ]);
        $otherEmployee = User::factory()->create([
            'company_id' => $otherCompany->id,
            'role' => 'employee',
            'is_active' => true,
        ]);

        $response = $this->actingAs($admin)->from(route('admin.notifications.create'))->post(route('admin.notifications.store'), [
            'target' => 'specific',
            'user_ids' => [$otherEmployee->id],
            'title' => 'Niet toestaan',
            'message' => 'Deze gebruiker hoort bij een ander bedrijf.',
        ]);

        $response->assertRedirect(route('admin.notifications.create'));
        $response->assertSessionHasErrors('user_ids.0');
        $this->assertSame(0, Notification::query()->count());
    }

    private function createCompany(string $name): Company
    {
        return Company::query()->create([
            'name' => $name,
            'address' => 'Teststraat 1',
            'phone' => '0612345678',
            'email' => strtolower($name).'@example.test',
            'subscription_status' => 'active',
            'subscription_ends_at' => now()->addMonth(),
            'is_active' => true,
            'onboarding_step' => Company::ONBOARDING_STEP_COMPLETED,
            'onboarding_completed_at' => now(),
        ]);
    }
}

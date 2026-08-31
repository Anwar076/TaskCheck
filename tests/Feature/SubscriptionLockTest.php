<?php

namespace Tests\Feature;

use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionLockTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_trial_admin_can_open_settings_but_not_dashboard(): void
    {
        [$admin] = $this->lockedAdmin(['subscription_status' => 'trial', 'trial_ends_at' => now()->subDay()]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.settings.edit'));

        $this->actingAs($admin)
            ->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('Je proefperiode is verlopen');
    }

    public function test_paid_company_marked_active_remains_in_trial_until_first_payment(): void
    {
        $company = $this->company([
            'subscription_status' => 'active',
            'billing_required' => true,
            'trial_ends_at' => now()->addWeek(),
            'mollie_subscription_id' => null,
            'subscription_ends_at' => null,
        ]);

        $this->assertTrue($company->isOnTrial());
        $this->assertFalse($company->hasActiveSubscription());
        $this->assertTrue($company->canAccess());

        $company->trial_ends_at = now()->subDay();

        $this->assertTrue($company->trialExpired());
        $this->assertFalse($company->canAccess());
    }

    public function test_cancelled_subscription_admin_is_locked_to_settings(): void
    {
        [$admin] = $this->lockedAdmin([
            'subscription_status' => 'cancelled',
            'subscription_ends_at' => now()->subDay(),
        ]);

        $this->actingAs($admin)
            ->get(route('admin.lists.index'))
            ->assertRedirect(route('admin.settings.edit'));

        $this->actingAs($admin)
            ->get(route('subscription.show'))
            ->assertOk();
    }

    public function test_cancelled_subscription_still_active_until_end_date(): void
    {
        [$admin] = $this->lockedAdmin([
            'subscription_status' => 'cancelled',
            'subscription_ends_at' => now()->addWeek(),
            'subscription_plan' => 'starter',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_expired_employee_can_open_settings_but_not_tasks(): void
    {
        $company = $this->company(['subscription_status' => 'expired']);
        $employee = User::factory()->create([
            'role' => 'employee',
            'company_id' => $company->id,
            'is_active' => true,
        ]);

        $this->actingAs($employee)
            ->get(route('employee.lists.index'))
            ->assertRedirect(route('employee.settings.edit'));

        $this->actingAs($employee)
            ->get(route('employee.settings.edit'))
            ->assertOk()
            ->assertSee('Je hebt geen actief abonnement');
    }

    /**
     * @return array{0: User, 1: Company}
     */
    private function lockedAdmin(array $companyOverrides = []): array
    {
        $company = $this->company($companyOverrides);
        $admin = User::factory()->create([
            'role' => 'admin',
            'company_id' => $company->id,
            'is_active' => true,
        ]);

        return [$admin, $company];
    }

    private function company(array $overrides = []): Company
    {
        return Company::query()->create(array_merge([
            'name' => 'Lock Test',
            'address' => 'Straat 1',
            'phone' => '0612345678',
            'email' => 'lock@example.com',
            'subscription_status' => 'trial',
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'onboarding_step' => Company::ONBOARDING_STEP_COMPLETED,
        ], $overrides));
    }
}

<?php

namespace Tests\Feature;

use App\Models\Checklist\TaskList;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarWeekOverviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_busy_week_shows_seven_days_with_overlapping_editable_cards(): void
    {
        $company = Company::create([
            'name' => 'Kalender', 'address' => 'Teststraat 1',
            'phone' => '0612345678', 'email' => 'test@example.com',
            'is_active' => true, 'subscription_plan' => 'starter',
            'subscription_status' => 'active', 'billing_required' => false,
            'subscription_ends_at' => now()->addYear(),
            'onboarding_completed_at' => now(), 'onboarding_step' => 'completed',
        ]);
        $admin = User::factory()->create(['company_id' => $company->id, 'role' => 'admin']);
        foreach (range(1, 8) as $index) {
            TaskList::create([
                'company_id' => $company->id, 'created_by' => $admin->id,
                'title' => 'Overlappende lijst '.$index, 'is_active' => true,
                'schedule_type' => 'daily',
                'schedule_config' => ['default_time_slot' => ['start_time' => '10:00', 'end_time' => '17:00']],
            ]);
        }
        $response = $this->actingAs($admin)->get(route('admin.lists.calendar', ['week' => '2026-09-07']));
        $this->assertSame(200, $response->status(), (string) $response->headers->get('Location'));
        $response->assertOk()->assertSee('calendar-overlap-event', false)
            ->assertSee('Overlappende lijst 8')->assertSee('calendar-list-schedule-form')
            ->assertSee('data-calendar-time-column', false)
            ->assertSee('3rem repeat(7, minmax(0, 1fr))', false)
            ->assertDontSee('min-width: calc(3rem', false);
        $html = $response->getContent();
        $this->assertSame(7, substr_count($html, 'data-calendar-time-column'));
        $this->assertGreaterThanOrEqual(8, substr_count($html, 'data-calendar-timed-list'));
        $this->assertStringContainsString('data-update-url=', $html);

        $this->get(route('admin.lists.calendar', ['week' => '2026-09-07', 'view' => 'day', 'day' => 'monday']))
            ->assertOk()->assertSee('data-calendar-time-column', false)->assertDontSee('data-calendar-week-overview', false);
        $this->get(route('admin.lists.calendar', ['view' => 'month', 'month' => '2026-09-01']))->assertOk();
    }
}

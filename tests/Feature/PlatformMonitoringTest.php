<?php

namespace Tests\Feature;

use App\Mail\TaskCheckNotificationMail;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use App\Models\Platform\PlatformAlertLog;
use App\Models\Platform\PlatformAlertState;
use App\Services\Platform\PlatformAlertService;
use App\Services\Platform\PlatformHealthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Tests\TestCase;

class PlatformMonitoringTest extends TestCase
{
    use RefreshDatabase;

    private array $snapshot;

    private PlatformAlertService $alerts;

    protected function setUp(): void
    {
        parent::setUp();
        $this->freezeTime();
        config()->set('platform_alerts.enabled', true);
        config()->set('platform_alerts.recipients', 'monitor@example.com');
        config()->set('platform_alerts.recovery_minutes', 15);
        Mail::fake();
        $this->snapshot = [
            'metrics' => ['failed_jobs_window_minutes' => 15, 'stalled_jobs_minutes' => 15],
            'alerts' => ['failed_jobs' => [
                'key' => 'failed_jobs', 'label' => 'Mislukte jobs', 'value' => 6,
                'threshold' => 5, 'available' => true, 'exceeded' => true,
            ]],
        ];
        $health = Mockery::mock(PlatformHealthService::class);
        $health->shouldReceive('snapshot')->andReturnUsing(fn () => $this->snapshot);
        $this->alerts = new PlatformAlertService($health);
    }

    private function healthy(): void
    {
        $this->snapshot['alerts']['failed_jobs']['value'] = 0;
        $this->snapshot['alerts']['failed_jobs']['exceeded'] = false;
    }

    public function test_an_ongoing_problem_sends_only_one_mail_even_after_days(): void
    {
        $this->assertSame(['failed_jobs'], $this->alerts->checkAndNotify());
        $this->travel(1)->hours();
        $this->assertSame([], $this->alerts->checkAndNotify());
        $this->travel(2)->days();
        $this->assertSame([], $this->alerts->checkAndNotify());
        Mail::assertSentCount(1);
        $this->assertDatabaseCount('platform_alert_logs', 1);
        $this->assertDatabaseHas('platform_alert_logs', ['event_type' => 'opened']);
    }

    public function test_recovery_requires_stability_and_allows_a_new_incident(): void
    {
        $this->alerts->checkAndNotify();
        $this->healthy();
        $this->assertSame([], $this->alerts->checkAndNotify());
        for ($i = 0; $i < 2; $i++) {
            $this->travel(5)->minutes();
            $this->assertSame([], $this->alerts->checkAndNotify());
        }
        $this->travel(5)->minutes();
        $this->assertSame(['failed_jobs'], $this->alerts->checkAndNotify());
        $this->assertSame([], $this->alerts->checkAndNotify());
        Mail::assertSentCount(2);
        Mail::assertSent(TaskCheckNotificationMail::class, fn ($mail) => str_contains($mail->title, 'Herstel:') && str_contains($mail->bodyText, 'niet automatisch'));
        $this->assertDatabaseHas('platform_alert_logs', ['event_type' => 'recovered']);
        $this->snapshot['alerts']['failed_jobs']['exceeded'] = true;
        $this->snapshot['alerts']['failed_jobs']['value'] = 8;
        $this->assertSame(['failed_jobs'], $this->alerts->checkAndNotify());
        Mail::assertSentCount(3);
    }

    public function test_fluctuation_does_not_send_recovery_or_repeat_warning(): void
    {
        $this->alerts->checkAndNotify();
        $this->healthy();
        $this->alerts->checkAndNotify();
        $this->travel(5)->minutes();
        $this->snapshot['alerts']['failed_jobs']['exceeded'] = true;
        $this->alerts->checkAndNotify();
        $this->healthy();
        $this->travel(5)->minutes();
        $this->alerts->checkAndNotify();
        $this->travel(5)->minutes();
        $this->alerts->checkAndNotify();
        Mail::assertSentCount(1);
    }

    public function test_unknown_or_disabled_measurements_do_not_resolve_incidents(): void
    {
        $this->alerts->checkAndNotify();
        $this->healthy();
        $this->alerts->checkAndNotify();
        $this->snapshot['alerts']['failed_jobs']['available'] = false;
        $this->travel(20)->minutes();
        $this->alerts->checkAndNotify();
        $this->assertNull(PlatformAlertState::find('failed_jobs')->healthy_since);
        $this->assertNotNull(PlatformAlertState::find('failed_jobs')->opened_at);
        $this->snapshot['alerts']['failed_jobs']['available'] = true;
        $this->snapshot['alerts']['failed_jobs']['threshold'] = 0;
        $this->alerts->checkAndNotify();
        Mail::assertSentCount(1);
    }

    public function test_a_monitoring_gap_restarts_the_recovery_observation(): void
    {
        $this->alerts->checkAndNotify();
        $this->healthy();
        $this->alerts->checkAndNotify();
        $this->travel(1)->hours();
        $this->assertSame([], $this->alerts->checkAndNotify());
        Mail::assertSentCount(1);
    }

    public function test_disabled_alerts_missing_recipients_and_overlapping_checks_send_nothing(): void
    {
        config()->set('platform_alerts.enabled', false);
        $this->assertSame([], $this->alerts->checkAndNotify());
        config()->set('platform_alerts.enabled', true);
        config()->set('platform_alerts.recipients', 'invalid');
        $this->assertSame([], $this->alerts->checkAndNotify());
        config()->set('platform_alerts.recipients', 'monitor@example.com');
        $lock = Cache::lock('platform-alert-check', 300);
        $lock->get();
        try {
            $this->assertSame([], $this->alerts->checkAndNotify());
        } finally {
            $lock->release();
        }
        Mail::assertNothingSent();
    }

    public function test_historical_mails_and_test_mails_do_not_create_recoveries(): void
    {
        PlatformAlertLog::create(['alert_key' => 'failed_jobs', 'metric_value' => 80, 'threshold' => 5, 'sent_at' => now()->subMonths(2)]);
        $this->alerts->sendTestNotification();
        $this->assertDatabaseCount('platform_alert_states', 0);
        $this->healthy();
        $this->assertSame([], $this->alerts->checkAndNotify());
        Mail::assertSentCount(1);
        $this->assertDatabaseCount('platform_alert_logs', 1);
    }

    public function test_a_mail_failure_does_not_mark_the_problem_as_notified(): void
    {
        $mailFake = Mail::getFacadeRoot();
        Mail::shouldReceive('to')->once()->andThrow(new \RuntimeException('Mail unavailable'));
        try {
            $this->alerts->checkAndNotify();
            $this->fail('Expected mail failure');
        } catch (\RuntimeException $exception) {
            $this->assertSame('Mail unavailable', $exception->getMessage());
        }
        $this->assertDatabaseCount('platform_alert_logs', 0);
        $this->assertNull(PlatformAlertState::find('failed_jobs')->opened_at);
        Mail::swap($mailFake);
        $this->assertSame(['failed_jobs'], $this->alerts->checkAndNotify());
        Mail::assertSentCount(1);
    }

    public function test_queue_measurements_ignore_future_recent_and_actively_processed_tasks(): void
    {
        config()->set('queue.default', 'database');
        config()->set('platform_alerts.stalled_jobs_minutes', 15);
        foreach ([
            [now()->addHour()->timestamp, null],
            [now()->subMinutes(5)->timestamp, null],
            [now()->subHour()->timestamp, now()->subMinute()->timestamp],
            [now()->subMinutes(15)->timestamp, null],
            [now()->subHour()->timestamp, now()->subMinutes(20)->timestamp],
        ] as [$available, $reserved]) {
            DB::table('jobs')->insert(['queue' => 'default', 'payload' => '{}', 'attempts' => 0, 'reserved_at' => $reserved, 'available_at' => $available, 'created_at' => now()->subDay()->timestamp]);
        }
        $snapshot = app(PlatformHealthService::class)->snapshot();
        $this->assertSame(2, $snapshot['metrics']['stalled_jobs']);
        $this->assertTrue($snapshot['alerts']['stalled_jobs']['exceeded']);
        config()->set('queue.default', 'redis');
        $snapshot = app(PlatformHealthService::class)->snapshot();
        $this->assertNull($snapshot['metrics']['stalled_jobs']);
        $this->assertFalse($snapshot['alerts']['stalled_jobs']['available']);
    }

    public function test_only_recent_failures_trigger_alerts_and_usage_thresholds_are_ignored(): void
    {
        config()->set('platform_alerts.failed_jobs_window_minutes', 15);
        config()->set('platform_alerts.thresholds.failed_jobs', 5);
        config()->set('platform_alerts.thresholds.active_users', 1);
        foreach (range(1, 6) as $id) {
            DB::table('failed_jobs')->insert(['uuid' => 'failure-'.$id, 'connection' => 'database', 'queue' => 'default', 'payload' => '{}', 'exception' => 'test', 'failed_at' => $id === 1 ? now()->subDays(2) : now()]);
        }
        $snapshot = app(PlatformHealthService::class)->snapshot();
        $this->assertSame(5, $snapshot['metrics']['failed_jobs']);
        $this->assertTrue($snapshot['alerts']['failed_jobs']['exceeded']);
        $this->assertArrayNotHasKey('active_users', $snapshot['alerts']);
        $this->travel(16)->minutes();
        $snapshot = app(PlatformHealthService::class)->snapshot();
        $this->assertSame(0, $snapshot['metrics']['failed_jobs']);
        $this->assertFalse($snapshot['alerts']['failed_jobs']['exceeded']);
        $this->assertDatabaseCount('failed_jobs', 6);
    }

    public function test_dashboard_and_command_count_submissions_across_companies(): void
    {
        foreach (['One', 'Two'] as $name) {
            $company = Company::create(['name' => $name]);
            $user = User::factory()->create(['company_id' => $company->id, 'role' => 'admin']);
            $listId = DB::table('lists')->insertGetId(['company_id' => $company->id, 'title' => 'Test', 'created_by' => $user->id]);
            DB::table('submissions')->insert(['company_id' => $company->id, 'list_id' => $listId, 'user_id' => $user->id, 'status' => 'in_progress', 'updated_at' => now()]);
        }
        $anonymous = app(PlatformHealthService::class)->snapshot();
        $this->actingAs($user);
        $authenticated = app(PlatformHealthService::class)->snapshot();
        $this->assertSame(2, $anonymous['metrics']['submissions_in_progress']);
        $this->assertSame(2, $authenticated['metrics']['submissions_in_progress']);
        config()->set('app.super_admin_emails', [$user->email]);
        $this->get(route('super-admin.dashboard', ['tab' => 'monitoring']))->assertOk()
            ->assertSee('geen e-mailalert')
            ->assertSee('Geen herhaalmails')
            ->assertDontSee('Productie: zet cron');
    }
}

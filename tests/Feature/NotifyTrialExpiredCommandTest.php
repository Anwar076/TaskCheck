<?php

namespace Tests\Feature;

use App\Mail\TrialExpiredMail;
use App\Models\Organisation\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotifyTrialExpiredCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_email_when_trial_has_expired(): void
    {
        Mail::fake();

        $company = Company::query()->create([
            'name' => 'Trial Bedrijf',
            'email' => 'billing@example.test',
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->subDay(),
            'is_active' => true,
        ]);

        $this->artisan('subscriptions:notify-trial-expired')->assertSuccessful();

        Mail::assertSent(TrialExpiredMail::class, function (TrialExpiredMail $mail) use ($company) {
            return $mail->company->is($company);
        });

        $company->refresh();
        $this->assertNotNull($company->trial_expired_email_sent_at);
    }

    public function test_it_does_not_send_email_twice(): void
    {
        Mail::fake();

        Company::query()->create([
            'name' => 'Trial Bedrijf',
            'email' => 'billing@example.test',
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->subDay(),
            'trial_expired_email_sent_at' => now()->subHour(),
            'is_active' => true,
        ]);

        $this->artisan('subscriptions:notify-trial-expired')->assertSuccessful();

        Mail::assertNothingSent();
    }

    public function test_plan_limits_are_updated(): void
    {
        $this->assertSame(1, Company::PLANS['professional']['max_locations']);
        $this->assertSame(2, Company::PLANS['business']['max_locations']);
        $this->assertSame(3, Company::planRoleLimits('business')['admin']);
        $this->assertSame(23, Company::PLANS['business']['max_users']);
    }
}

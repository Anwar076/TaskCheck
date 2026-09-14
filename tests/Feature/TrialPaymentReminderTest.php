<?php

namespace Tests\Feature;

use App\Mail\FirstPaymentInvitationMail;
use App\Mail\TrialPaymentReminderMail;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class TrialPaymentReminderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_reminder_seven_days_before_trial_ends(): void
    {
        Mail::fake();
        $company = $this->makePayableTrialCompany(now()->addDays(7)->endOfDay());

        $this->artisan('subscriptions:send-trial-payment-reminders')->assertSuccessful();

        Mail::assertSent(TrialPaymentReminderMail::class, function (TrialPaymentReminderMail $mail) use ($company) {
            return $mail->company->is($company) && $mail->daysRemaining === 7;
        });
        $this->assertNotEmpty($company->refresh()->trial_payment_reminders_sent);
    }

    public function test_it_does_not_send_the_same_reminder_twice(): void
    {
        Mail::fake();
        $this->makePayableTrialCompany(now()->addDays(3)->endOfDay());

        $this->artisan('subscriptions:send-trial-payment-reminders')->assertSuccessful();
        $this->artisan('subscriptions:send-trial-payment-reminders')->assertSuccessful();

        Mail::assertSent(TrialPaymentReminderMail::class, 1);
    }

    public function test_it_sends_same_day_reminder(): void
    {
        Mail::fake();
        $this->makePayableTrialCompany(now()->endOfDay());

        $this->artisan('subscriptions:send-trial-payment-reminders')->assertSuccessful();

        Mail::assertSent(TrialPaymentReminderMail::class, fn (TrialPaymentReminderMail $mail) => $mail->daysRemaining === 0);
    }

    public function test_super_admin_can_send_payment_mail(): void
    {
        Mail::fake();
        $company = $this->makePayableTrialCompany(now()->addDays(10)->endOfDay());
        $admin = User::factory()->create(['role' => 'admin', 'email' => 'super@example.test']);
        config()->set('app.super_admin_emails', ['super@example.test']);

        $this->actingAs($admin)
            ->post(route('super-admin.companies.subscription.payment-invitation', $company))
            ->assertRedirect(route('super-admin.companies.show', ['company' => $company, 'section' => 'billing']));

        Mail::assertSent(FirstPaymentInvitationMail::class, fn ($mail) => $mail->company->is($company));
        $this->assertNotNull($company->refresh()->payment_invitation_sent_at);
    }

    private function makePayableTrialCompany($trialEndsAt): Company
    {
        return Company::query()->create([
            'name' => 'Reminder klant',
            'email' => 'billing@example.test',
            'subscription_plan' => 'professional',
            'subscription_status' => 'trial',
            'signup_source' => Company::SIGNUP_SOURCE_MANAGED,
            'billing_required' => true,
            'billing_period' => 'monthly',
            'trial_ends_at' => $trialEndsAt,
            'is_active' => true,
        ]);
    }
}

<?php

namespace Tests\Feature;

use App\Mail\FirstPaymentInvitationMail;
use App\Models\Organisation\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendFirstPaymentInvitationsCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_managed_company_receives_payment_invitation_on_billing_date_once(): void
    {
        Mail::fake();
        $company = Company::query()->create([
            'name' => 'Beheerde klant',
            'email' => 'billing@example.test',
            'subscription_plan' => 'professional',
            'subscription_status' => 'trial',
            'signup_source' => Company::SIGNUP_SOURCE_MANAGED,
            'billing_required' => true,
            'billing_start_date' => today(),
            'trial_ends_at' => today(),
            'is_active' => true,
        ]);

        $this->artisan('subscriptions:send-first-payment-invitations')->assertSuccessful();
        Mail::assertSent(FirstPaymentInvitationMail::class, fn ($mail) => $mail->company->is($company));
        $this->assertNotNull($company->refresh()->payment_invitation_sent_at);

        $this->artisan('subscriptions:send-first-payment-invitations')->assertSuccessful();
        Mail::assertSent(FirstPaymentInvitationMail::class, 1);
    }

    public function test_self_service_trial_does_not_receive_managed_payment_invitation(): void
    {
        Mail::fake();
        Company::query()->create([
            'name' => 'Zelfregistratie',
            'email' => 'self@example.test',
            'subscription_status' => 'trial',
            'signup_source' => Company::SIGNUP_SOURCE_SELF_SERVICE,
            'billing_required' => true,
            'billing_start_date' => today(),
            'is_active' => true,
        ]);

        $this->artisan('subscriptions:send-first-payment-invitations')->assertSuccessful();
        Mail::assertNothingSent();
    }
}

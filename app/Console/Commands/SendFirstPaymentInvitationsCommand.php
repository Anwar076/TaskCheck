<?php

namespace App\Console\Commands;

use App\Models\Organisation\Company;
use App\Services\Billing\PaymentInvitationService;
use Illuminate\Console\Command;
use RuntimeException;

class SendFirstPaymentInvitationsCommand extends Command
{
    protected $signature = 'subscriptions:send-first-payment-invitations';
    protected $description = 'Stuur beheerde klanten op hun eerste betaaldatum een uitnodiging voor de eerste Mollie-betaling';

    public function handle(PaymentInvitationService $invitations): int
    {
        $companies = Company::query()
            ->where('signup_source', Company::SIGNUP_SOURCE_MANAGED)
            ->where('billing_required', true)
            ->whereDate('billing_start_date', '<=', today())
            ->whereNull('mollie_subscription_id')
            ->whereNull('payment_invitation_sent_at')
            ->where('is_active', true)
            ->get();

        foreach ($companies as $company) {
            try {
                $recipient = $invitations->sendPaymentRequest($company);
                $this->info("Betaaluitnodiging verstuurd: {$company->name} ({$recipient})");
            } catch (RuntimeException $exception) {
                $this->warn("Overgeslagen {$company->name}: {$exception->getMessage()}");
            }
        }

        return self::SUCCESS;
    }
}

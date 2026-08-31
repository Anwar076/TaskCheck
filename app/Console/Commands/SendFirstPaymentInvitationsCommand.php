<?php

namespace App\Console\Commands;

use App\Mail\FirstPaymentInvitationMail;
use App\Models\Organisation\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendFirstPaymentInvitationsCommand extends Command
{
    protected $signature = 'subscriptions:send-first-payment-invitations';
    protected $description = 'Stuur beheerde klanten op hun eerste betaaldatum een uitnodiging voor de eerste Mollie-betaling';

    public function handle(): int
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
            $recipient = $company->email ?: $company->users()->where('role', 'admin')->orderBy('id')->value('email');
            if (!$recipient) {
                continue;
            }

            Mail::to($recipient)->send(new FirstPaymentInvitationMail($company, route('subscription.show')));
            $company->update(['payment_invitation_sent_at' => now()]);
            $this->info("Betaaluitnodiging verstuurd: {$company->name} ({$recipient})");
        }

        return self::SUCCESS;
    }
}

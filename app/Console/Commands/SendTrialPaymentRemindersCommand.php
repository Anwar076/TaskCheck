<?php

namespace App\Console\Commands;

use App\Services\Billing\PaymentInvitationService;
use Illuminate\Console\Command;

class SendTrialPaymentRemindersCommand extends Command
{
    protected $signature = 'subscriptions:send-trial-payment-reminders';

    protected $description = 'Stuur betaalherinneringen 7, 3, 2, 1 dag en op de dag dat de proefperiode eindigt';

    public function handle(PaymentInvitationService $invitations): int
    {
        $sent = $invitations->sendDueTrialReminders();

        if ($sent === 0) {
            $this->info('Geen proefperiode-herinneringen om te mailen.');
        } else {
            $this->info("Totaal verstuurd: {$sent}");
        }

        return self::SUCCESS;
    }
}

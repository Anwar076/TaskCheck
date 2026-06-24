<?php

namespace App\Console\Commands;

use App\Mail\TrialExpiredMail;
use App\Models\Organisation\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class NotifyTrialExpiredCommand extends Command
{
    protected $signature = 'subscriptions:notify-trial-expired';

    protected $description = 'Stuur een e-mail wanneer de proefperiode van een bedrijf is verlopen';

    public function handle(): int
    {
        $companies = Company::query()
            ->where('subscription_status', 'trial')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<=', now())
            ->whereNull('trial_expired_email_sent_at')
            ->where('is_active', true)
            ->get();

        $sent = 0;

        /** @var Company $company */
        foreach ($companies as $company) {
            if ($company->hasActiveSubscription() || $company->hasCancelledButStillActiveAccess()) {
                continue;
            }

            $recipient = $company->email
                ?: $company->users()->where('role', 'admin')->orderBy('id')->value('email');

            if (! $recipient) {
                $this->warn(sprintf('Overgeslagen (geen e-mail): %s', $company->name));

                continue;
            }

            $choosePlanUrl = URL::route('subscription.choose-plan');

            Mail::to($recipient)->send(new TrialExpiredMail($company, $choosePlanUrl));

            $company->update([
                'trial_expired_email_sent_at' => now(),
            ]);

            $sent++;
            $this->info(sprintf('Proefperiode-mail verstuurd: %s (%s)', $company->name, $recipient));
        }

        if ($sent === 0) {
            $this->info('Geen verlopen proefperiodes om te mailen.');
        } else {
            $this->info("Totaal verstuurd: {$sent}");
        }

        return self::SUCCESS;
    }
}

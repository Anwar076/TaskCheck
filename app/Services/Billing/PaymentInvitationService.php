<?php

namespace App\Services\Billing;

use App\Mail\FirstPaymentInvitationMail;
use App\Mail\TrialPaymentReminderMail;
use App\Models\Organisation\Company;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class PaymentInvitationService
{
    public const REMINDER_DAYS = [7, 3, 2, 1, 0];

    public function billingRecipient(Company $company): ?string
    {
        $email = trim((string) ($company->email ?: ''));
        if ($email !== '') {
            return $email;
        }

        $adminEmail = $company->users()->where('role', 'admin')->orderBy('id')->value('email');

        return filled($adminEmail) ? (string) $adminEmail : null;
    }

    public function paymentUrl(Company $company): string
    {
        return $company->needsFirstPayment()
            ? route('subscription.show')
            : route('subscription.choose-plan');
    }

    public function sendPaymentRequest(Company $company): string
    {
        if (! $company->billing_required) {
            throw new RuntimeException('Zet eerst automatische betaling aan voordat je een betaalmail stuurt.');
        }

        if (filled($company->mollie_subscription_id)) {
            throw new RuntimeException('Dit bedrijf heeft al een Mollie-abonnement. Er is geen eerste betaling meer nodig.');
        }

        $recipient = $this->billingRecipient($company);
        if (! $recipient) {
            throw new RuntimeException('Geen e-mailadres gevonden bij dit bedrijf of een beheerder.');
        }

        Mail::to($recipient)->send(new FirstPaymentInvitationMail($company, $this->paymentUrl($company)));
        $company->update(['payment_invitation_sent_at' => now()]);

        return $recipient;
    }

    public function sendDueTrialReminders(): int
    {
        $sent = 0;
        $companies = Company::query()
            ->where('billing_required', true)
            ->where('is_active', true)
            ->whereNotNull('trial_ends_at')
            ->whereNull('mollie_subscription_id')
            ->whereIn('subscription_status', ['trial', 'active'])
            ->get();

        foreach ($companies as $company) {
            if ($company->hasActiveSubscription()) {
                continue;
            }

            $days = (int) now()->startOfDay()->diffInDays($company->trial_ends_at->copy()->startOfDay(), false);
            if (! in_array($days, self::REMINDER_DAYS, true)) {
                continue;
            }
            if ($this->reminderSent($company, $days)) {
                continue;
            }

            $recipient = $this->billingRecipient($company);
            if (! $recipient) {
                continue;
            }

            Mail::to($recipient)->send(new TrialPaymentReminderMail($company, $this->paymentUrl($company), $days));
            $this->markReminderSent($company, $days);
            $sent++;
        }

        return $sent;
    }

    private function reminderSent(Company $company, int $days): bool
    {
        $bucket = $company->trial_ends_at?->toDateString();
        if (! $bucket) {
            return true;
        }

        $sent = $company->trial_payment_reminders_sent ?? [];

        return isset($sent[$bucket][(string) $days]);
    }

    private function markReminderSent(Company $company, int $days): void
    {
        $bucket = $company->trial_ends_at->toDateString();
        $sent = $company->trial_payment_reminders_sent ?? [];
        $sent[$bucket][(string) $days] = now()->toIso8601String();
        $company->update(['trial_payment_reminders_sent' => $sent]);
    }
}

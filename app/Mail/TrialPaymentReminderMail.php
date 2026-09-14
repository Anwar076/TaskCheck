<?php

namespace App\Mail;

use App\Models\Organisation\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialPaymentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Company $company,
        public string $paymentUrl,
        public int $daysRemaining
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->daysRemaining) {
            0 => 'Je proefperiode eindigt vandaag — rond je betaling af',
            1 => 'Morgen eindigt je proefperiode — rond je betaling af',
            default => "Over {$this->daysRemaining} dagen eindigt je proefperiode — rond je betaling af",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.trial-payment-reminder');
    }
}

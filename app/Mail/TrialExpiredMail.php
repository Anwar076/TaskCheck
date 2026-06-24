<?php

namespace App\Mail;

use App\Models\Organisation\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TrialExpiredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Company $company,
        public string $choosePlanUrl
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Je proefperiode is verlopen — kies een abonnement',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.trial-expired',
            with: [
                'company' => $this->company,
                'choosePlanUrl' => $this->choosePlanUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

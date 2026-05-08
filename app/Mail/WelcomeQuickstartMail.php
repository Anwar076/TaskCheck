<?php

namespace App\Mail;

use App\Models\Company;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeQuickstartMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public User $user,
        public Company $company
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welkom bij TaskCheck',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome-quickstart',
            with: [
                'user' => $this->user,
                'company' => $this->company,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $fromName,
        public readonly string $fromEmail,
        public readonly string $company,
        public readonly string $subjectLabel,
        public readonly string $messageBody,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: [new Address($this->fromEmail, $this->fromName)],
            subject: "TaskCheck contact: {$this->subjectLabel} — {$this->fromName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.contact-form',
        );
    }
}

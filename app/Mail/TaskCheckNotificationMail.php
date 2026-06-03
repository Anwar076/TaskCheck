<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskCheckNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectLine,
        public string $greetingName,
        public string $title,
        public string $bodyText,
        public ?string $ctaLabel = null,
        public ?string $ctaUrl = null,
        public ?string $metaText = null,
        public bool $showMarketing = false
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.taskcheck-notification',
            with: [
                'greetingName' => $this->greetingName,
                'title' => $this->title,
                'bodyText' => $this->bodyText,
                'ctaLabel' => $this->ctaLabel,
                'ctaUrl' => $this->ctaUrl,
                'metaText' => $this->metaText,
                'showMarketing' => $this->showMarketing,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

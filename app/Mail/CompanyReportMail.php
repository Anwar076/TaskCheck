<?php

namespace App\Mail;

use App\Models\Organisation\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CompanyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array<string, mixed> $report
     */
    public function __construct(
        public Company $company,
        public array $report
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: sprintf('%s - %s', $this->report['title'] ?? 'Rapportage', $this->company->name),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.company-report',
            with: [
                'company' => $this->company,
                'report' => $this->report,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail;

use App\Models\Organisation\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Queue\SerializesModels;

class CompanyReportMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array<string, mixed> $report
     */
    public function __construct(
        public Company $company,
        public array $report,
        public string $deliveryFormat = 'email',
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
            view: $this->deliveryFormat === 'pdf' ? 'emails.company-report-pdf-delivery' : 'emails.company-report',
            with: [
                'company' => $this->company,
                'report' => $this->report,
            ],
        );
    }

    public function attachments(): array
    {
        if (! in_array($this->deliveryFormat, ['pdf', 'both'], true)) {
            return [];
        }

        $pdf = Pdf::loadView('reports.company-report-pdf', ['company' => $this->company, 'report' => $this->report])
            ->setPaper('a4', 'portrait');

        return [Attachment::fromData(fn () => $pdf->output(), sprintf(
            '%s-%s.pdf',
            str($this->report['title'] ?? 'rapportage')->slug(),
            $this->report['period_end']->format('Y-m-d')
        ))->withMime('application/pdf')];
    }
}

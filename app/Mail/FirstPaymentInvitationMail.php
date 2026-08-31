<?php

namespace App\Mail;

use App\Models\Organisation\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FirstPaymentInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Company $company, public string $paymentUrl) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Rond de eerste betaling voor je TaskCheck-abonnement af');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.first-payment-invitation');
    }
}

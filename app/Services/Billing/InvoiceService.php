<?php

namespace App\Services\Billing;

use App\Mail\TaskCheckNotificationMail;
use App\Models\Billing\Invoice;
use App\Models\Organisation\Company;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class InvoiceService
{
    public function sendReceipt(Company $company, array $payment): void
    {
        $paymentId = trim((string) ($payment['id'] ?? ''));
        if ($paymentId === '') {
            return;
        }

        $invoice = $this->fromPayment($company, $payment);
        $cacheKey = "billing_receipt_sent:{$paymentId}";
        if (Cache::has($cacheKey)) {
            return;
        }

        $recipient = trim((string) ($company->email ?: $company->users()->orderBy('id')->value('email')));
        if ($recipient === '') {
            return;
        }

        $description = (string) data_get($payment, 'description', 'TaskCheck betaling');
        $paidAtRaw = (string) data_get($payment, 'paidAt', '');
        $paidAt = $paidAtRaw !== ''
            ? Carbon::parse($paidAtRaw)->timezone('Europe/Amsterdam')->format('d-m-Y H:i')
            : now()->timezone('Europe/Amsterdam')->format('d-m-Y H:i');
        $body = "We hebben je betaling ontvangen.\n\n"
            ."Factuurnummer: {$invoice->invoice_number}\n"
            ."Betaling ID: {$paymentId}\n"
            ."Omschrijving: {$description}\n"
            .'Bedrag: '.data_get($payment, 'amount.currency', 'EUR').' '.data_get($payment, 'amount.value', '0.00')."\n"
            ."Betaald op: {$paidAt}\n\nDe factuur zit als PDF bij deze e-mail.";

        Mail::to($recipient)->send(
            (new TaskCheckNotificationMail(
                subjectLine: 'Factuur - '.$description,
                greetingName: $company->name,
                title: 'Factuur en betaling bevestigd',
                bodyText: $body,
                ctaLabel: 'Bekijk al je facturen',
                ctaUrl: route('subscription.show'),
                metaText: 'Dit is je officiële factuurmail van TaskCheck.',
                showMarketing: false,
            ))->attachData($this->renderPdf($invoice), $invoice->invoice_number.'.pdf', ['mime' => 'application/pdf'])
        );
        Cache::put($cacheKey, true, now()->addDays(7));
    }

    public function fromPayment(Company $company, array $payment): Invoice
    {
        $paymentId = trim((string) ($payment['id'] ?? ''));
        $paidAtRaw = (string) data_get($payment, 'paidAt', '');
        $paidAt = $paidAtRaw !== '' ? Carbon::parse($paidAtRaw) : now();
        $grossAmount = (float) data_get($payment, 'amount.value', 0);
        $vatRate = 21.0;
        $amountExVat = round($grossAmount / (1 + ($vatRate / 100)), 2);
        $existing = Invoice::where('payment_id', $paymentId)->first();

        return Invoice::updateOrCreate(['payment_id' => $paymentId], [
            'company_id' => $company->id,
            'invoice_number' => $existing?->invoice_number ?: $this->nextNumber($paidAt->timezone('Europe/Amsterdam')->format('Ym'), $company),
            'description' => (string) data_get($payment, 'description', 'TaskCheck abonnement'),
            'currency' => (string) data_get($payment, 'amount.currency', 'EUR'),
            'amount' => $grossAmount,
            'vat_rate' => $vatRate,
            'amount_ex_vat' => $amountExVat,
            'vat_amount' => round($grossAmount - $amountExVat, 2),
            'paid_at' => $paidAt,
            'meta' => ['payment_id' => $paymentId, 'method' => (string) data_get($payment, 'method', ''), 'status' => (string) data_get($payment, 'status', '')],
        ]);
    }

    public function renderPdf(Invoice $invoice): string
    {
        $invoice->loadMissing('company');

        return Pdf::loadView('pdf.invoice', ['invoice' => $invoice, 'company' => $invoice->company])->output();
    }

    private function nextNumber(string $datePart, Company $company): string
    {
        $companyCode = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', (string) ($company->name ?? 'COMP')), 0, 4)) ?: 'COMP';
        $prefix = "TC-{$datePart}-{$companyCode}-";
        $latest = Invoice::where('invoice_number', 'like', $prefix.'%')->orderByDesc('id')->value('invoice_number');
        $next = is_string($latest) && str_contains($latest, $prefix) ? ((int) substr($latest, -4)) + 1 : 1;

        return $prefix.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }
}

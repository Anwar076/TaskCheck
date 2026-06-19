<?php

namespace Database\Seeders;

use App\Models\Organisation\Company;
use App\Models\Billing\Invoice;
use Illuminate\Database\Seeder;

class DemoInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::query()->orderBy('id')->first();

        if (!$company) {
            $this->command?->error('Geen bedrijf gevonden. Maak eerst een bedrijf aan.');

            return;
        }

        $gross = 49.00;
        $vatRate = 21.00;
        $amountExVat = round($gross / (1 + ($vatRate / 100)), 2);
        $vatAmount = round($gross - $amountExVat, 2);

        $invoice = Invoice::updateOrCreate(
            ['payment_id' => 'demo-local-test-invoice'],
            [
                'company_id' => $company->id,
                'invoice_number' => 'TC-DEMO-' . now()->format('Ymd') . '-0001',
                'description' => 'TaskCheck Pro — demo factuur (lokaal testen)',
                'currency' => 'EUR',
                'amount' => $gross,
                'vat_rate' => $vatRate,
                'amount_ex_vat' => $amountExVat,
                'vat_amount' => $vatAmount,
                'paid_at' => now(),
                'meta' => [
                    'demo' => true,
                    'note' => 'Aangemaakt voor lokaal testen in Super Admin',
                ],
            ]
        );

        $this->command?->info("Demo factuur aangemaakt: {$invoice->invoice_number} voor {$company->name}");
        $this->command?->line('Bekijk: /super-admin/dashboard?tab=invoices');
        $this->command?->line('PDF: /subscription/invoices/' . $invoice->id . '/download (ingelogd als super admin)');
    }
}

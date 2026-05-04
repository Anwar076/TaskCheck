<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('vat_rate', 5, 2)->default(21.00)->after('amount');
            $table->decimal('amount_ex_vat', 10, 2)->default(0)->after('vat_rate');
            $table->decimal('vat_amount', 10, 2)->default(0)->after('amount_ex_vat');
        });

        DB::table('invoices')
            ->select(['id', 'amount'])
            ->orderBy('id')
            ->chunkById(500, function ($rows): void {
                foreach ($rows as $row) {
                    $gross = (float) ($row->amount ?? 0);
                    $rate = 21.00;
                    $exVat = round($gross / (1 + ($rate / 100)), 2);
                    $vat = round($gross - $exVat, 2);

                    DB::table('invoices')
                        ->where('id', $row->id)
                        ->update([
                            'vat_rate' => $rate,
                            'amount_ex_vat' => $exVat,
                            'vat_amount' => $vat,
                        ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['vat_rate', 'amount_ex_vat', 'vat_amount']);
        });
    }
};

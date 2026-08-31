<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->string('billing_period')->default('monthly')->after('name');
            $table->decimal('billing_amount', 10, 2)->nullable()->after('billing_period');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_plans', fn (Blueprint $table) => $table->dropColumn(['billing_period', 'billing_amount']));
    }
};

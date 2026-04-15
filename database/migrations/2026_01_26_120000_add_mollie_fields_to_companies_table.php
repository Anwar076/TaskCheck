<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('pending_subscription_plan')->nullable()->after('subscription_plan');
            $table->string('mollie_customer_id')->nullable()->after('subscription_ends_at');
            $table->string('mollie_subscription_id')->nullable()->after('mollie_customer_id');
            $table->string('mollie_payment_id')->nullable()->after('mollie_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'pending_subscription_plan',
                'mollie_customer_id',
                'mollie_subscription_id',
                'mollie_payment_id',
            ]);
        });
    }
};

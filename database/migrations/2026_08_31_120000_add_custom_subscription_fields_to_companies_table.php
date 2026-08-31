<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('custom_subscription_name')->nullable()->after('subscription_plan');
            $table->decimal('custom_monthly_price', 10, 2)->nullable()->after('custom_subscription_name');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['custom_subscription_name', 'custom_monthly_price']);
        });
    }
};

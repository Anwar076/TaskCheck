<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            $table->unsignedSmallInteger('trial_duration_value')->default(14)->after('billing_amount');
            $table->string('trial_duration_unit')->default('days')->after('trial_duration_value');
        });
    }

    public function down(): void
    {
        Schema::table('subscription_plans', fn (Blueprint $table) => $table->dropColumn(['trial_duration_value', 'trial_duration_unit']));
    }
};

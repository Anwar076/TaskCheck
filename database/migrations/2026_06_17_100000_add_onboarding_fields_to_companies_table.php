<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('onboarding_step', 32)->default('welcome')->after('is_active');
            $table->timestamp('onboarding_completed_at')->nullable()->after('onboarding_step');
        });

        DB::table('companies')->update([
            'onboarding_step' => 'completed',
            'onboarding_completed_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['onboarding_step', 'onboarding_completed_at']);
        });
    }
};

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
            $table->string('billing_period')->default('monthly')->after('billing_required');
            $table->date('billing_start_date')->nullable()->after('billing_period');
        });

        DB::table('companies')->whereNotNull('trial_ends_at')->update([
            'billing_start_date' => DB::raw('DATE(trial_ends_at)'),
        ]);
        if (Schema::hasTable('subscription_plans')) {
            foreach (DB::table('subscription_plans')->get(['plan_key', 'billing_period']) as $plan) {
                DB::table('companies')->where('subscription_plan', $plan->plan_key)->update(['billing_period' => $plan->billing_period]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('companies', fn (Blueprint $table) => $table->dropColumn(['billing_period', 'billing_start_date']));
    }
};

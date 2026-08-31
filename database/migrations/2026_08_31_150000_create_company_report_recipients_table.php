<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_report_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('frequency');
            $table->time('send_time');
            $table->unsignedTinyInteger('weekly_day')->nullable();
            $table->string('delivery_format')->default('both');
            $table->boolean('is_enabled')->default(true);
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();
            $table->index(['is_enabled', 'send_time']);
        });

        DB::table('companies')->where('reporting_enabled', true)->whereNotNull('email')->orderBy('id')->each(function ($company) {
            DB::table('company_report_recipients')->insert([
                'company_id' => $company->id,
                'email' => $company->email,
                'frequency' => $company->reporting_frequency ?: 'daily',
                'send_time' => $company->reporting_send_time ?: '09:00:00',
                'weekly_day' => $company->reporting_weekly_day ?: 1,
                'delivery_format' => 'email',
                'is_enabled' => true,
                'last_sent_at' => $company->reporting_last_sent_at,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_report_recipients');
    }
};

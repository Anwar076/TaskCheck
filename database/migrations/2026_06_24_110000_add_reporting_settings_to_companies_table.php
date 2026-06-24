<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('reporting_enabled')
                ->default(false)
                ->after('calendar_time_mode');
            $table->string('reporting_frequency')
                ->nullable()
                ->after('reporting_enabled');
            $table->time('reporting_send_time')
                ->nullable()
                ->after('reporting_frequency');
            $table->unsignedTinyInteger('reporting_weekly_day')
                ->default(1)
                ->after('reporting_send_time');
            $table->timestamp('reporting_last_sent_at')
                ->nullable()
                ->after('reporting_weekly_day');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'reporting_enabled',
                'reporting_frequency',
                'reporting_send_time',
                'reporting_weekly_day',
                'reporting_last_sent_at',
            ]);
        });
    }
};

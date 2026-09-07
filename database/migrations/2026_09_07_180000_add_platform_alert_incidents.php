<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_alert_states', function (Blueprint $table) {
            $table->string('alert_key', 64)->primary();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('healthy_since')->nullable();
            $table->timestamps();
        });
        Schema::table('platform_alert_logs', function (Blueprint $table) {
            // Null identifies historical threshold mails, not new incidents.
            $table->string('event_type', 20)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('platform_alert_logs', fn (Blueprint $table) => $table->dropColumn('event_type'));
        Schema::dropIfExists('platform_alert_states');
    }
};

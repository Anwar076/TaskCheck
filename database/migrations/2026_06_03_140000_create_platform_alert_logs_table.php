<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('platform_alert_logs', function (Blueprint $table) {
            $table->id();
            $table->string('alert_key', 64);
            $table->unsignedInteger('metric_value');
            $table->unsignedInteger('threshold');
            $table->timestamp('sent_at');

            $table->index(['alert_key', 'sent_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_alert_logs');
    }
};

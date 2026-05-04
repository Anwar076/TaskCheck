<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('incident_tickets', function (Blueprint $table) {
            $table->string('request_url')->nullable()->after('context');
            $table->string('http_method', 10)->nullable()->after('request_url');
            $table->text('user_agent')->nullable()->after('http_method');
            $table->string('device_type', 20)->nullable()->after('user_agent');
            $table->string('ip_address', 45)->nullable()->after('device_type');
            $table->longText('ai_analysis')->nullable()->after('ip_address');
            $table->string('ai_model', 80)->nullable()->after('ai_analysis');
            $table->timestamp('ai_analyzed_at')->nullable()->after('ai_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incident_tickets', function (Blueprint $table) {
            $table->dropColumn([
                'request_url',
                'http_method',
                'user_agent',
                'device_type',
                'ip_address',
                'ai_analysis',
                'ai_model',
                'ai_analyzed_at',
            ]);
        });
    }
};

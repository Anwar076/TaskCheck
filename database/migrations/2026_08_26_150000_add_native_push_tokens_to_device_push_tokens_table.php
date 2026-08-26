<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device_push_tokens', function (Blueprint $table) {
            $table->string('expo_push_token')->nullable()->change();
            $table->string('native_push_token')->nullable()->unique()->after('expo_push_token');
            $table->string('push_provider', 20)->default('expo')->after('native_push_token');
        });
    }

    public function down(): void
    {
        Schema::table('device_push_tokens', function (Blueprint $table) {
            $table->dropUnique(['native_push_token']);
            $table->dropColumn(['native_push_token', 'push_provider']);
            $table->string('expo_push_token')->nullable(false)->change();
        });
    }
};

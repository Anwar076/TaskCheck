<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            if (! Schema::hasColumn('companies', 'trial_expired_email_sent_at')) {
                $table->timestamp('trial_expired_email_sent_at')->nullable()->after('trial_ends_at');
            }
        });
    }

    public function down(): void
    {
        // Intentionally left blank: this repair migration must not drop a column
        // that may have been created by the original June migration.
    }
};

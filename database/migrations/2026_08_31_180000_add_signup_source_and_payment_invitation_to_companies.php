<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('signup_source', 24)->default('managed')->after('billing_start_date');
            $table->timestamp('payment_invitation_sent_at')->nullable()->after('trial_expired_email_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('companies', fn (Blueprint $table) => $table->dropColumn(['signup_source', 'payment_invitation_sent_at']));
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lists', function (Blueprint $table) {
            $table->string('hygiene_code')->nullable()->after('category');
            $table->string('haccp_plan_reference')->nullable()->after('hygiene_code');
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->string('norm_reference')->nullable()->after('description');
        });
        Schema::table('submission_tasks', function (Blueprint $table) {
            $table->text('corrective_action')->nullable()->after('rejection_reason');
            $table->foreignId('corrective_action_owner_id')->nullable()->after('corrective_action')->constrained('users')->nullOnDelete();
            $table->timestamp('corrective_action_due_at')->nullable()->after('corrective_action_owner_id');
            $table->timestamp('corrective_action_completed_at')->nullable()->after('corrective_action_due_at');
            $table->text('verification_note')->nullable()->after('corrective_action_completed_at');
            $table->foreignId('verified_by')->nullable()->after('verification_note')->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verified_by');
        });
    }

    public function down(): void
    {
        Schema::table('submission_tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('verified_by');
            $table->dropConstrainedForeignId('corrective_action_owner_id');
            $table->dropColumn(['corrective_action', 'corrective_action_due_at', 'corrective_action_completed_at', 'verification_note', 'verified_at']);
        });
        Schema::table('tasks', fn (Blueprint $table) => $table->dropColumn('norm_reference'));
        Schema::table('lists', fn (Blueprint $table) => $table->dropColumn(['hygiene_code', 'haccp_plan_reference']));
    }
};

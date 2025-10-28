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
        Schema::table('template_tasks', function (Blueprint $table) {
            $table->text('instructions')->nullable()->after('description');
            $table->enum('required_proof_type', ['none', 'photo', 'video', 'text', 'file', 'any'])->default('none')->after('instructions');
            $table->boolean('is_required')->default(true)->after('required_proof_type');
            $table->json('attachments')->nullable()->after('is_required');
            $table->json('validation_rules')->nullable()->after('attachments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('template_tasks', function (Blueprint $table) {
            $table->dropColumn(['instructions', 'required_proof_type', 'is_required', 'attachments', 'validation_rules']);
        });
    }
};

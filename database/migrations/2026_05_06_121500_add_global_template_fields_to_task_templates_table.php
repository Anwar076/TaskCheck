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
        Schema::table('task_templates', function (Blueprint $table) {
            $table->foreignId('source_template_id')
                ->nullable()
                ->after('company_id')
                ->constrained('task_templates')
                ->nullOnDelete();
            $table->timestamp('source_updated_at')->nullable()->after('source_template_id');
            $table->string('target_company_type', 50)->nullable()->after('source_updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_templates', function (Blueprint $table) {
            $table->dropForeign(['source_template_id']);
            $table->dropColumn(['source_template_id', 'source_updated_at', 'target_company_type']);
        });
    }
};


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
        Schema::table('submission_tasks', function (Blueprint $table) {
            $table->text('redo_reason')->nullable()->after('redo_requested');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submission_tasks', function (Blueprint $table) {
            $table->dropColumn('redo_reason');
        });
    }
};

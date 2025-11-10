<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL, we need to use DB statements to alter the enum
        DB::statement("ALTER TABLE submission_tasks MODIFY COLUMN status ENUM('pending', 'completed', 'approved', 'rejected', 'redo_requested') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE submission_tasks MODIFY COLUMN status ENUM('pending', 'completed', 'approved', 'rejected') DEFAULT 'pending'");
    }
};

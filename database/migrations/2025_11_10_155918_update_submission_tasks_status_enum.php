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
        // SQLite doesn't support ENUM modifications, so we'll check if it's MySQL
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'mysql') {
            // For MySQL, we can use ALTER TABLE MODIFY
            DB::statement("ALTER TABLE submission_tasks MODIFY COLUMN status ENUM('pending', 'completed', 'approved', 'rejected', 'redo_requested') DEFAULT 'pending'");
        } else {
            // For SQLite and other databases, the enum is actually stored as TEXT
            // So we don't need to modify the column structure, just ensure our application logic handles the new status
            // We can add a check constraint if needed
            try {
                DB::statement("UPDATE submission_tasks SET status = 'pending' WHERE status NOT IN ('pending', 'completed', 'approved', 'rejected', 'redo_requested')");
            } catch (\Exception $e) {
                // Ignore if no invalid values exist
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();
        
        if ($driver === 'mysql') {
            // Revert back to original enum values
            DB::statement("ALTER TABLE submission_tasks MODIFY COLUMN status ENUM('pending', 'completed', 'approved', 'rejected') DEFAULT 'pending'");
        } else {
            // For SQLite, we'll update any redo_requested status back to rejected
            try {
                DB::statement("UPDATE submission_tasks SET status = 'rejected' WHERE status = 'redo_requested'");
            } catch (\Exception $e) {
                // Ignore if no values to update
            }
        }
    }
};

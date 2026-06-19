<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE submission_tasks
                MODIFY COLUMN status ENUM('pending', 'completed', 'approved', 'rejected', 'redo_requested')
                NOT NULL DEFAULT 'pending'
            ");
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE submission_tasks
                MODIFY COLUMN status ENUM('pending', 'completed', 'approved', 'rejected')
                NOT NULL DEFAULT 'pending'
            ");
        }
    }
};

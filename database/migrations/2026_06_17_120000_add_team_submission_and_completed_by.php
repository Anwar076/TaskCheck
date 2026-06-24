<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->boolean('is_team_submission')->default(false)->after('status');
        });

        Schema::table('submission_tasks', function (Blueprint $table) {
            $table->foreignId('completed_by_user_id')
                ->nullable()
                ->after('completed_at')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('submission_tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('completed_by_user_id');
        });

        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('is_team_submission');
        });
    }
};

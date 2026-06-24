<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submission_tasks', function (Blueprint $table) {
            $table->text('employee_comment')
                ->nullable()
                ->after('proof_text');
        });
    }

    public function down(): void
    {
        Schema::table('submission_tasks', function (Blueprint $table) {
            $table->dropColumn('employee_comment');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lists', function (Blueprint $table) {
            $table->renameColumn('hygiene_code', 'compliance_framework');
            $table->renameColumn('haccp_plan_reference', 'policy_reference');
        });
        Schema::table('tasks', function (Blueprint $table) {
            $table->renameColumn('norm_reference', 'control_reference');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->renameColumn('control_reference', 'norm_reference');
        });
        Schema::table('lists', function (Blueprint $table) {
            $table->renameColumn('policy_reference', 'haccp_plan_reference');
            $table->renameColumn('compliance_framework', 'hygiene_code');
        });
    }
};

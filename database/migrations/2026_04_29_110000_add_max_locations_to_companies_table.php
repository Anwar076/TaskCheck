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
        if (!Schema::hasColumn('companies', 'max_locations')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->integer('max_locations')->default(1)->after('max_users');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('companies', 'max_locations')) {
            Schema::table('companies', function (Blueprint $table) {
                $table->dropColumn('max_locations');
            });
        }
    }
};

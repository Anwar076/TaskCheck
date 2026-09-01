<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('company_report_recipients', function (Blueprint $table) {
            $table->json('sections')->nullable()->after('delivery_format');
        });
    }

    public function down(): void
    {
        Schema::table('company_report_recipients', function (Blueprint $table) {
            $table->dropColumn('sections');
        });
    }
};

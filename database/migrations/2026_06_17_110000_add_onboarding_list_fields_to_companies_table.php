<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('onboarding_list_mode', 16)->nullable()->after('onboarding_step');
            $table->foreignId('onboarding_list_id')->nullable()->after('onboarding_list_mode')->constrained('lists')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('onboarding_list_id');
            $table->dropColumn('onboarding_list_mode');
        });
    }
};

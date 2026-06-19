<?php

use App\Models\Organisation\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->json('working_hours')->nullable()->after('departments');
        });

        Company::query()->whereNull('working_hours')->update([
            'working_hours' => json_encode(Company::defaultWorkingHours()),
        ]);
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('working_hours');
        });
    }
};

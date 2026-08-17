<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lists', function (Blueprint $table) {
            $table->unsignedInteger('display_order')->nullable()->after('location_id')->index();
        });

        DB::table('lists')->orderBy('company_id')->orderBy('id')->get(['id', 'company_id'])
            ->groupBy('company_id')
            ->each(function ($lists) {
                foreach ($lists->values() as $index => $list) {
                    DB::table('lists')->where('id', $list->id)->update(['display_order' => $index + 1]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('lists', function (Blueprint $table) {
            $table->dropIndex(['display_order']);
            $table->dropColumn('display_order');
        });
    }
};

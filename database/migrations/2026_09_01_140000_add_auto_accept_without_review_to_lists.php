<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('lists', 'auto_accept_without_review')) {
            Schema::table('lists', function (Blueprint $table) {
                $table->boolean('auto_accept_without_review')
                    ->default(false)
                    ->after('requires_review');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('lists', 'auto_accept_without_review')) {
            Schema::table('lists', function (Blueprint $table) {
                $table->dropColumn('auto_accept_without_review');
            });
        }
    }
};

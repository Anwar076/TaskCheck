<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('locations')) {
            return;
        }

        Schema::table('locations', function (Blueprint $table) {
            if (! Schema::hasColumn('locations', 'street')) {
                $table->string('street')->nullable()->after('address');
            }

            if (! Schema::hasColumn('locations', 'house_number')) {
                $table->string('house_number', 30)->nullable()->after('street');
            }

            if (! Schema::hasColumn('locations', 'postal_code')) {
                $table->string('postal_code', 20)->nullable()->after('house_number');
            }

            if (! Schema::hasColumn('locations', 'city')) {
                $table->string('city')->nullable()->after('postal_code');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('locations')) {
            return;
        }

        Schema::table('locations', function (Blueprint $table) {
            $columns = array_values(array_filter([
                Schema::hasColumn('locations', 'street') ? 'street' : null,
                Schema::hasColumn('locations', 'house_number') ? 'house_number' : null,
                Schema::hasColumn('locations', 'postal_code') ? 'postal_code' : null,
                Schema::hasColumn('locations', 'city') ? 'city' : null,
            ]));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_templates', function (Blueprint $table) {
            if (!Schema::hasColumn('task_templates', 'category')) {
                $table->string('category', 100)->nullable()->after('target_company_type');
            }
            if (!Schema::hasColumn('task_templates', 'icon')) {
                $table->string('icon', 100)->nullable()->after('category');
            }
            if (!Schema::hasColumn('task_templates', 'frequency_label')) {
                $table->string('frequency_label', 100)->nullable()->after('icon');
            }
            if (!Schema::hasColumn('task_templates', 'frequency_type')) {
                $table->string('frequency_type', 50)->nullable()->after('frequency_label');
            }
            if (!Schema::hasColumn('task_templates', 'is_starter_pack')) {
                $table->boolean('is_starter_pack')->default(false)->after('frequency_type');
            }
            if (!Schema::hasColumn('task_templates', 'starter_pack_group')) {
                $table->string('starter_pack_group', 100)->nullable()->after('is_starter_pack');
            }
            if (!Schema::hasColumn('task_templates', 'khn_reference')) {
                $table->string('khn_reference', 255)->nullable()->after('starter_pack_group');
            }
            if (!Schema::hasColumn('task_templates', 'compliance_rules')) {
                $table->json('compliance_rules')->nullable()->after('khn_reference');
            }
        });
    }

    public function down(): void
    {
        Schema::table('task_templates', function (Blueprint $table) {
            $drop = [];
            foreach ([
                'category',
                'icon',
                'frequency_label',
                'frequency_type',
                'is_starter_pack',
                'starter_pack_group',
                'khn_reference',
                'compliance_rules',
            ] as $column) {
                if (Schema::hasColumn('task_templates', $column)) {
                    $drop[] = $column;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};


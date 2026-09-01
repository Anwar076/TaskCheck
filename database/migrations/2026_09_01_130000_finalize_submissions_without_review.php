<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $listIds = DB::table('lists')
            ->where('requires_review', false)
            ->pluck('id');

        DB::table('submissions')
            ->whereIn('list_id', $listIds)
            ->where('status', 'completed')
            ->update(['status' => 'reviewed']);
    }

    public function down(): void
    {
        // This status correction is intentionally not reversed.
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Decouple all existing task lists from their templates.
     *
     * Lists were previously created from templates but kept the template_id,
     * which caused syncToLists() to overwrite customer edits whenever the
     * template was updated. Setting template_id = null makes every list
     * fully independent and editable by the customer.
     */
    public function up(): void
    {
        DB::table('lists')->whereNotNull('template_id')->update(['template_id' => null]);
    }

    /**
     * The original template_id values are lost after this migration.
     * A rollback cannot restore them without a backup.
     */
    public function down(): void
    {
        // Intentionally left empty — the original links cannot be restored.
    }
};

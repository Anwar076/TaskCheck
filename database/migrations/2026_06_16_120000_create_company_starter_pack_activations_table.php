<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_starter_pack_activations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('pack_slug', 50);
            $table->foreignId('activated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('templates_imported')->default(0);
            $table->unsignedInteger('lists_created')->default(0);
            $table->timestamps();

            $table->unique(['company_id', 'pack_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_starter_pack_activations');
    }
};

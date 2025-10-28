<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('template_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->constrained('task_templates')->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('checklist_items')->nullable(); // For checklist tasks
            $table->integer('sort_order')->default(0); // For drag & drop ordering
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_tasks');
    }
};

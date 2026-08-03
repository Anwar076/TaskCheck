<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('platform_broadcasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('channel', 30);
            $table->string('subject', 200)->nullable();
            $table->string('title', 200)->nullable();
            $table->text('message');
            $table->string('audience', 40)->default('all');
            $table->unsignedInteger('recipients_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->string('status', 30)->default('sent');
            $table->json('meta')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('platform_broadcasts');
    }
};

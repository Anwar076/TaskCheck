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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('domain')->nullable();
            $table->string('subscription_plan')->nullable(); // e.g. 'starter', 'professional', 'business', 'custom'
            $table->enum('subscription_status', ['trial', 'active', 'cancelled', 'expired'])->default('trial');
            $table->dateTime('trial_ends_at')->nullable();
            $table->dateTime('subscription_ends_at')->nullable();
            $table->integer('max_users')->default(5); // Based on plan
            $table->integer('max_storage_gb')->default(5); // Based on plan
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

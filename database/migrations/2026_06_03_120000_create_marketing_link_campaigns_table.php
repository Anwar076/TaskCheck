<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_link_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('code', 64)->unique();
            $table->string('name');
            $table->string('destination_url', 2048);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('clicks_count')->default(0);
            $table->unsignedInteger('unique_clicks_count')->default(0);
            $table->timestamp('last_clicked_at')->nullable();
            $table->timestamps();
        });

        Schema::create('marketing_link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketing_link_campaign_id')->constrained()->cascadeOnDelete();
            $table->string('visitor_hash', 64);
            $table->string('user_agent', 512)->nullable();
            $table->string('referer', 2048)->nullable();
            $table->timestamp('clicked_at');

            $table->index(['marketing_link_campaign_id', 'visitor_hash'], 'ml_clicks_campaign_visitor_idx');
            $table->index('clicked_at', 'ml_clicks_clicked_at_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_link_clicks');
        Schema::dropIfExists('marketing_link_campaigns');
    }
};

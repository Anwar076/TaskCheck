<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submission_task_audit_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_task_id')->constrained('submission_tasks')->cascadeOnDelete();
            $table->foreignId('submission_id')->constrained('submissions')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event_type', 40);
            $table->string('from_status', 40)->nullable();
            $table->string('to_status', 40)->nullable();
            $table->json('snapshot')->nullable();
            $table->timestamps();
            $table->index(['submission_id', 'created_at']);
        });

        DB::table('submission_tasks')
            ->join('submissions', 'submissions.id', '=', 'submission_tasks.submission_id')
            ->select('submission_tasks.*', 'submissions.company_id')
            ->orderBy('submission_tasks.id')
            ->chunkById(500, function ($tasks) {
                $now = now();
                DB::table('submission_task_audit_events')->insert($tasks->map(fn ($task) => [
                    'submission_task_id' => $task->id,
                    'submission_id' => $task->submission_id,
                    'company_id' => $task->company_id,
                    'actor_id' => $task->reviewed_by ?: $task->completed_by_user_id,
                    'event_type' => 'legacy_snapshot',
                    'from_status' => null,
                    'to_status' => $task->status,
                    'snapshot' => json_encode([
                        'proof_text' => $task->proof_text,
                        'rejection_reason' => $task->rejection_reason,
                        'corrective_action' => $task->corrective_action,
                        'verification_note' => $task->verification_note,
                    ], JSON_UNESCAPED_UNICODE),
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->all());
            }, 'submission_tasks.id', 'id');
    }

    public function down(): void
    {
        Schema::dropIfExists('submission_task_audit_events');
    }
};

<?php

namespace App\Models\Submissions;

use App\Models\Checklist\Task;
use App\Models\Organisation\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubmissionTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'task_id',
        'proof_text',
        'employee_comment',
        'proof_files',
        'checklist_progress',
        'digital_signature',
        'signature_date',
        'status',
        'manager_comment',
        'rejection_reason',
        'corrective_action',
        'corrective_action_owner_id',
        'corrective_action_due_at',
        'corrective_action_completed_at',
        'verification_note',
        'verified_by',
        'verified_at',
        'redo_requested',
        'redo_reason',
        'completed_at',
        'completed_by_user_id',
        'reviewed_at',
        'rejected_at',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'proof_files' => 'json',
            'checklist_progress' => 'json',
            'completed_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'rejected_at' => 'datetime',
            'corrective_action_due_at' => 'datetime',
            'corrective_action_completed_at' => 'datetime',
            'verified_at' => 'datetime',
            'signature_date' => 'datetime',
            'redo_requested' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::created(function (SubmissionTask $task) {
            $task->recordAuditEvent('created', null, $task->status);
        });

        static::updated(function (SubmissionTask $task) {
            $tracked = [
                'status', 'proof_text', 'employee_comment', 'proof_files',
                'manager_comment', 'completed_at', 'completed_by_user_id',
                'rejection_reason', 'corrective_action', 'corrective_action_owner_id',
                'corrective_action_due_at', 'corrective_action_completed_at',
                'reviewed_at', 'reviewed_by', 'verified_at', 'verified_by',
                'verification_note',
            ];
            if (! collect($tracked)->contains(fn ($field) => $task->wasChanged($field))) {
                return;
            }

            $from = $task->getOriginal('status');
            $to = $task->status;
            $event = match (true) {
                $task->wasChanged('verified_at') && $task->verified_at !== null => 'verified',
                $task->wasChanged('status') && $to === 'approved' => 'approved',
                $task->wasChanged('status') && in_array($to, ['rejected', 'redo_requested'], true) => 'rejected',
                $task->wasChanged('status') && $to === 'pending' && filled($task->rejection_reason) => 'rejected',
                $task->wasChanged('status') && $to === 'completed' && filled($task->rejection_reason) => 'resubmitted',
                $task->wasChanged('status') && $to === 'completed' => 'submitted',
                default => 'updated',
            };

            $task->recordAuditEvent($event, $from, $to);
        });
    }

    public function auditEvents()
    {
        return $this->hasMany(SubmissionTaskAuditEvent::class)->oldest('created_at');
    }

    private function recordAuditEvent(string $event, ?string $from, ?string $to): void
    {
        $submission = $this->submission()->first();
        SubmissionTaskAuditEvent::create([
            'submission_task_id' => $this->id,
            'submission_id' => $this->submission_id,
            'company_id' => $submission?->company_id,
            'actor_id' => auth()->id() ?: ($this->reviewed_by ?: $this->completed_by_user_id),
            'event_type' => $event,
            'from_status' => $from,
            'to_status' => $to,
            'snapshot' => [
                'proof_text' => $this->proof_text,
                'employee_comment' => $this->employee_comment,
                'proof_files' => $this->proof_files,
                'manager_comment' => $this->manager_comment,
                'completed_at' => $this->completed_at?->toIso8601String(),
                'completed_by_user_id' => $this->completed_by_user_id,
                'rejection_reason' => $this->rejection_reason,
                'corrective_action' => $this->corrective_action,
                'corrective_action_owner_id' => $this->corrective_action_owner_id,
                'corrective_action_due_at' => $this->corrective_action_due_at?->toIso8601String(),
                'corrective_action_completed_at' => $this->corrective_action_completed_at?->toIso8601String(),
                'verification_note' => $this->verification_note,
                'verified_by' => $this->verified_by,
                'verified_at' => $this->verified_at?->toIso8601String(),
            ],
        ]);
    }

    // Relationships
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function completedBy()
    {
        return $this->belongsTo(User::class, 'completed_by_user_id');
    }

    public function correctiveActionOwner()
    {
        return $this->belongsTo(User::class, 'corrective_action_owner_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeRedoRequested($query)
    {
        return $query->where('status', 'redo_requested');
    }

    // Helper methods
    public function reject($reason, $reviewerId)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_at' => now(),
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
        ]);

        $notifyUserId = app(\App\Services\CollaborativeSubmissionService::class)
            ->notifyUserIdForTask($this);

        return Notification::createTaskRejected(
            $notifyUserId,
            $this->task->title,
            $reason,
            $this->submission_id
        );
    }

    public function requestRedo($reviewerId, $reason = null)
    {
        $this->update([
            'status' => 'redo_requested',
            'redo_requested' => true,
            'redo_reason' => $reason,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
        ]);

        $notifyUserId = app(\App\Services\CollaborativeSubmissionService::class)
            ->notifyUserIdForTask($this);

        return Notification::createRedoRequested(
            $notifyUserId,
            $this->task->title,
            $this->submission_id,
            $reason
        );
    }

    public function approve($reviewerId, $comment = null)
    {
        $this->update([
            'status' => 'approved',
            'manager_comment' => $comment,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
            'redo_requested' => false,
        ]);
    }
}

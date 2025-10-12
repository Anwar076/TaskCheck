<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubmissionTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'task_id',
        'proof_text',
        'proof_files',
        'checklist_progress',
        'digital_signature',
        'signature_date',
        'status',
        'manager_comment',
        'rejection_reason',
        'redo_requested',
        'completed_at',
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
            'signature_date' => 'datetime',
            'redo_requested' => 'boolean',
        ];
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
        return $query->where('redo_requested', true);
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

        // Create notification for the employee
        Notification::createTaskRejected(
            $this->submission->user_id,
            $this->task->title,
            $reason,
            $this->submission_id
        );
    }

    public function requestRedo($reviewerId)
    {
        $this->update([
            'status' => 'pending',
            'redo_requested' => true,
            'reviewed_by' => $reviewerId,
            'reviewed_at' => now(),
        ]);

        // Create notification for the employee
        Notification::createRedoRequested(
            $this->submission->user_id,
            $this->task->title,
            $this->submission_id
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

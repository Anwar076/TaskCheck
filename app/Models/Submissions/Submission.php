<?php

namespace App\Models\Submissions;

use App\Models\Checklist\TaskList;
use App\Models\Organisation\User;
use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Submission extends Model
{
    use BelongsToCompany, HasFactory;

    protected $fillable = [
        'user_id',
        'list_id',
        'started_at',
        'completed_at',
        'status',
        'is_team_submission',
        'employee_signature',
        'manager_signature',
        'digital_signature',
        'signature_date',
        'notes',
        'metadata',
        'company_id',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'signature_date' => 'datetime',
            'metadata' => 'json',
            'is_team_submission' => 'boolean',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function taskList()
    {
        return $this->belongsTo(TaskList::class, 'list_id');
    }

    public function submissionTasks()
    {
        return $this->hasMany(SubmissionTask::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    // Helper methods
    public function getCompletionPercentageAttribute()
    {
        $totalTasks = $this->submissionTasks()->count();
        if ($totalTasks === 0) {
            return 0;
        }

        $completedTasks = $this->submissionTasks()->where('status', 'completed')->count();

        return round(($completedTasks / $totalTasks) * 100);
    }

    public function requiresSignature()
    {
        return $this->taskList->requires_signature ||
               $this->taskList->tasks()->where('requires_signature', true)->exists();
    }

    public function hasDigitalSignature()
    {
        return ! empty($this->digital_signature);
    }

    public function completedStatus(): string
    {
        $this->loadMissing('taskList');

        return ! $this->taskList?->requires_review && $this->taskList?->auto_accept_without_review
            ? 'reviewed'
            : 'completed';
    }

    public function addDigitalSignature($signatureData)
    {
        $this->update([
            'digital_signature' => $signatureData,
            'signature_date' => now(),
        ]);
    }

    public function hasRejectedTasks()
    {
        return $this->submissionTasks()->where('status', 'rejected')->exists();
    }

    public function getRejectedTasksAttribute()
    {
        return $this->submissionTasks()->where('status', 'rejected')->with('task')->get();
    }

    public function teamContributors(): Collection
    {
        $this->loadMissing('submissionTasks.completedBy');

        return $this->submissionTasks
            ->map(fn (SubmissionTask $submissionTask) => $submissionTask->completedBy)
            ->filter()
            ->unique('id')
            ->values();
    }

    /**
     * @return array<int, array{id: int, name: string, initials: string, completed_tasks: int}>
     */
    public function contributorTaskSummary(): array
    {
        $this->loadMissing('submissionTasks.completedBy');

        $summary = [];

        foreach ($this->submissionTasks as $submissionTask) {
            $userId = (int) ($submissionTask->completed_by_user_id ?? 0);
            if ($userId === 0) {
                continue;
            }

            if (! isset($summary[$userId])) {
                $user = $submissionTask->completedBy;
                $summary[$userId] = [
                    'id' => $userId,
                    'name' => (string) ($user?->name ?? 'Onbekend'),
                    'initials' => mb_strtoupper(mb_substr((string) ($user?->name ?? '?'), 0, 1)),
                    'completed_tasks' => 0,
                ];
            }

            if (in_array($submissionTask->status, ['completed', 'approved'], true)) {
                $summary[$userId]['completed_tasks']++;
            }
        }

        return collect($summary)
            ->sortByDesc('completed_tasks')
            ->values()
            ->all();
    }

    public function participantLabel(): string
    {
        if (! $this->is_team_submission) {
            return (string) ($this->user?->name ?? 'Onbekend');
        }

        $contributors = $this->teamContributors();

        if ($contributors->isEmpty()) {
            return 'Team · '.($this->user?->name ?? 'checklist');
        }

        $names = $contributors->pluck('name')->filter()->values();

        if ($names->count() <= 2) {
            return 'Team · '.$names->join(', ');
        }

        return 'Team · '.$names->take(2)->join(', ').' +'.($names->count() - 2);
    }
}

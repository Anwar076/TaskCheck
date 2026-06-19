<?php

namespace App\Models\Submissions;

use App\Models\Checklist\TaskList;
use App\Models\Organisation\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToCompany;

class Submission extends Model
{
    use HasFactory, BelongsToCompany;

    protected $fillable = [
        'user_id',
        'list_id',
        'started_at',
        'completed_at',
        'status',
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
        if ($totalTasks === 0) return 0;
        
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
        return !empty($this->digital_signature);
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
}

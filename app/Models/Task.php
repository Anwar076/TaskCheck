<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'list_id',
        'title',
        'description',
        'instructions',
        'required_proof_type',
        'is_required',
        'order_index',
        'order',
        'attachments',
        'validation_rules',
        'checklist_items',
        'requires_signature',
        'weekday',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'attachments' => 'json',
            'validation_rules' => 'json',
            'checklist_items' => 'json',
            'requires_signature' => 'boolean',
        ];
    }

    // Relationships
    public function taskList()
    {
        return $this->belongsTo(TaskList::class, 'list_id');
    }

    public function submissionTasks()
    {
        return $this->hasMany(SubmissionTask::class);
    }

    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_assignments')->withPivot('assigned_at', 'due_at', 'is_active')->withTimestamps();
    }

    // Scopes
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    public function scopeByProofType($query, $type)
    {
        return $query->where('required_proof_type', $type);
    }

    public function scopeByWeekday($query, $weekday)
    {
        return $query->where('weekday', $weekday);
    }

    public function scopeWithWeekday($query)
    {
        return $query->whereNotNull('weekday');
    }

    public function scopeWithoutWeekday($query)
    {
        return $query->whereNull('weekday');
    }
}

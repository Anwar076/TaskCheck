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

    public function scopeForToday($query)
    {
        $today = strtolower(now()->format('l')); // 'monday', 'tuesday', etc.
        return $query->where(function($q) use ($today) {
            $q->whereNull('weekday') // Tasks without specific day assignment
              ->orWhere('weekday', $today); // Tasks assigned to today
        });
    }

    public function scopeAvailableToday($query)
    {
        return $query->forToday();
    }

    // Helper methods
    public function isAvailableOnDay($day)
    {
        // If no weekday is set, task is available every day
        if (is_null($this->weekday)) {
            return true;
        }
        
        return strtolower($this->weekday) === strtolower($day);
    }

    public function isAvailableToday()
    {
        $today = strtolower(now()->format('l'));
        return $this->isAvailableOnDay($today);
    }

    public function getWeekdayDisplayName()
    {
        return $this->weekday ? ucfirst($this->weekday) : 'Every day';
    }
}

<?php

namespace App\Models\Checklist;

use App\Models\Organisation\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ListAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'list_id',
        'user_id',
        'department',
        'role',
        'assigned_date',
        'due_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'assigned_date' => 'date',
            'due_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function taskList()
    {
        return $this->belongsTo(TaskList::class, 'list_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today());
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', today());
    }
}

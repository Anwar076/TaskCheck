<?php
/**
 * @property int $id
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaskList extends Model
{
    use HasFactory;

    protected $table = 'lists';

    protected $fillable = [
        'title',
        'description',
        'created_by',
        'parent_list_id',
        'weekday',
        'schedule_type',
        'schedule_config',
        'priority',
        'due_date',
        'tags',
        'category',
        'requires_signature',
        'is_template',
        'is_active',
        'template_id',
    ];

    protected function casts(): array
    {
        return [
            'schedule_config' => 'json',
            'tags' => 'json',
            'due_date' => 'datetime',
            'requires_signature' => 'boolean',
            'is_template' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parentList()
    {
        return $this->belongsTo(TaskList::class, 'parent_list_id');
    }

    public function subLists()
    {
        return $this->hasMany(TaskList::class, 'parent_list_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'list_id')->orderBy('order')->orderBy('order_index');
    }

    public function assignments()
    {
        return $this->hasMany(ListAssignment::class, 'list_id')->where('is_active', true);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'list_id');
    }

    public function template()
    {
        return $this->belongsTo(TaskTemplate::class, 'template_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeTemplates($query)
    {
        return $query->where('is_template', true);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeMainLists($query)
    {
        return $query->whereNull('parent_list_id');
    }

    public function scopeDailySubLists($query)
    {
        return $query->whereNotNull('parent_list_id')->whereNotNull('weekday');
    }

    public function scopeForWeekday($query, $weekday)
    {
        return $query->where('weekday', strtolower($weekday));
    }

    public function scopeForToday($query)
    {
        $today = strtolower(now()->format('l')); // 'monday', 'tuesday', etc.
        return $query->where(function($q) use ($today) {
            // Include one-time lists
            $q->where('schedule_type', 'once')
              // Include daily lists
              ->orWhere('schedule_type', 'daily')
              // Include weekly/custom lists that are scheduled for today
              ->orWhere(function($subQ) use ($today) {
                  $subQ->whereIn('schedule_type', ['weekly', 'custom'])
                       ->whereRaw("JSON_CONTAINS(JSON_EXTRACT(schedule_config, '$.show_on_days'), ?)", ['"'.$today.'"']);
              });
        });
    }

    public function scopeAvailableToday($query)
    {
        return $query->forToday();
    }

    // Helper methods
    public function isMainList()
    {
        return is_null($this->parent_list_id);
    }

    public function isDailySubList()
    {
        return !is_null($this->parent_list_id) && !is_null($this->weekday);
    }

    public function getTodaySubList()
    {
        if (!$this->isMainList()) {
            return null;
        }

        $today = strtolower(now()->format('l'));
        return $this->subLists()->where('weekday', $today)->first();
    }

    /**
     * Check if this list should be shown on a specific day
     */
    public function isAvailableOnDay($day)
    {
        $day = strtolower($day);
        
        // One-time lists are always available
        if ($this->schedule_type === 'once') {
            return true;
        }
        
        // Daily lists are available every day
        if ($this->schedule_type === 'daily') {
            return true;
        }
        
        // Weekly and custom lists check schedule_config
        if (in_array($this->schedule_type, ['weekly', 'custom'])) {
            $config = is_array($this->schedule_config) ? $this->schedule_config : [];
            $showOnDays = $config['show_on_days'] ?? [];
            return in_array($day, $showOnDays);
        }
        
        return false;
    }

    /**
     * Get the days this list should be shown on
     */
    public function getShowOnDays()
    {
        if ($this->schedule_type === 'daily') {
            return ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        }
        
        if (in_array($this->schedule_type, ['weekly', 'custom'])) {
            $config = is_array($this->schedule_config) ? $this->schedule_config : [];
            return $config['show_on_days'] ?? [];
        }
        
        return [];
    }

    /**
     * Check if this list is available today
     */
    public function isAvailableToday()
    {
        $today = strtolower(now()->format('l'));
        return $this->isAvailableOnDay($today);
    }

    public function createDailySubLists()
    {
        $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        foreach ($weekdays as $weekday) {
            $existingSubList = $this->subLists()->where('weekday', $weekday)->first();
            
            if (!$existingSubList) {
                $this->subLists()->create([
                    'title' => $this->title . ' – ' . ucfirst($weekday),
                    'description' => $this->description,
                    'weekday' => $weekday,
                    'schedule_type' => 'daily',
                    'priority' => $this->priority,
                    'category' => $this->category,
                    'requires_signature' => $this->requires_signature,
                    'is_active' => true,
                    'created_by' => $this->created_by,
                ]);
            }
        }
    }

    // Weekly Structure methods
    public function hasWeeklyStructure()
    {
        $config = is_array($this->schedule_config) ? $this->schedule_config : [];
        return isset($config['weekly_structure']['enabled']) && 
               $config['weekly_structure']['enabled'];
    }

    public function getWeeklyStructure()
    {
        $config = is_array($this->schedule_config) ? $this->schedule_config : [];
        return $config['weekly_structure'] ?? null;
    }

    public function getSelectedDays()
    {
        $config = is_array($this->schedule_config) ? $this->schedule_config : [];
        return $config['weekly_structure']['selected_days'] ?? ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    }

    public function getTasksForDay($day)
    {
        return $this->tasks()->where('weekday', $day)->orderBy('order')->orderBy('order_index')->get();
    }

    public function getTasksByDay()
    {
        $tasksByDay = [];
        $selectedDays = $this->getSelectedDays();
        
        foreach ($selectedDays as $day) {
            $tasksByDay[$day] = $this->getTasksForDay($day);
        }
        
        return $tasksByDay;
    }
}

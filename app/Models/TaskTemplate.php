<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class TaskTemplate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the template tasks for this template
     */
    public function templateTasks(): HasMany
    {
        return $this->hasMany(TemplateTask::class, 'template_id')->orderBy('sort_order');
    }

    /**
     * Get the task lists that use this template
     */
    public function taskLists(): HasMany
    {
        return $this->hasMany(TaskList::class, 'template_id');
    }

    /**
     * Create a new task list from this template
     */
    public function createTaskList(array $listData): TaskList
    {
        $taskList = TaskList::create([
            'title' => $listData['title'],
            'description' => $listData['description'] ?? null,
            'template_id' => $this->id,
            'is_active' => $listData['is_active'] ?? true,
        ]);

        // Copy all template tasks to the new list
        foreach ($this->templateTasks as $templateTask) {
            Task::create([
                'list_id' => $taskList->id,
                'title' => $templateTask->title,
                'description' => $templateTask->description,
                'instructions' => $templateTask->instructions,
                'required_proof_type' => $templateTask->required_proof_type,
                'is_required' => $templateTask->is_required,
                'attachments' => $templateTask->attachments,
                'validation_rules' => $templateTask->validation_rules,
                'order_index' => $templateTask->sort_order,
            ]);
        }

        return $taskList;
    }
}

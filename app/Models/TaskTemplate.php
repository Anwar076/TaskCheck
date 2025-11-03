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
                'checklist_items' => $templateTask->checklist_items,
                'required_proof_type' => $templateTask->required_proof_type,
                'is_required' => $templateTask->is_required,
                'attachments' => $templateTask->attachments,
                'validation_rules' => $templateTask->validation_rules,
                'order_index' => $templateTask->sort_order,
            ]);
        }

        return $taskList;
    }

    /**
     * Sync this template's tasks to all TaskLists that were created from it.
     * This will create missing tasks, update existing ones by order_index or title,
     * and remove tasks that no longer exist in the template (based on order_index).
     */
    public function syncToLists(): void
    {
        $templateTasks = $this->templateTasks()->orderBy('sort_order')->get();
        $templateOrderIndexes = $templateTasks->pluck('sort_order')->filter()->values()->all();

        foreach ($this->taskLists()->with('tasks')->get() as $list) {
            \DB::beginTransaction();
            try {
                $matchedTaskIds = [];

                foreach ($templateTasks as $tt) {
                    // If the template task has no checklist_items but the instructions
                    // contain multiple lines, auto-convert those lines into checklist items.
                    if ((empty($tt->checklist_items) || !is_array($tt->checklist_items) || count($tt->checklist_items) === 0)
                        && !empty($tt->instructions) && preg_match('/\r|\n/', $tt->instructions)) {
                        $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $tt->instructions))));
                        if (!empty($lines)) {
                            $tt->checklist_items = $lines;
                            // Persist so subsequent list sync will copy them
                            $tt->save();
                        }
                    }

                    // Try to find by order_index first
                    $task = $list->tasks->firstWhere('order_index', $tt->sort_order);

                    // Fallback to title match
                    if (!$task) {
                        $task = $list->tasks->firstWhere('title', $tt->title);
                    }

                    $data = [
                        'title' => $tt->title,
                        'description' => $tt->description,
                        'instructions' => $tt->instructions,
                        'required_proof_type' => $tt->required_proof_type,
                        'is_required' => $tt->is_required,
                        'checklist_items' => $tt->checklist_items,
                        'attachments' => $tt->attachments,
                        'validation_rules' => $tt->validation_rules,
                        'order_index' => $tt->sort_order,
                    ];

                    if ($task) {
                        $task->update($data);
                    } else {
                        $data['list_id'] = $list->id;
                        $task = Task::create($data);
                    }

                    $matchedTaskIds[] = $task->id;
                }

                // Remove tasks that are part of this list but no longer present in template (by order_index)
                $toDelete = $list->tasks->filter(function ($task) use ($templateOrderIndexes, $matchedTaskIds) {
                    // If task was matched above, skip
                    if (in_array($task->id, $matchedTaskIds)) return false;

                    // If task has an order_index that is in the template, keep it
                    if ($task->order_index !== null && in_array($task->order_index, $templateOrderIndexes)) return false;

                    // Otherwise consider it for deletion (it was likely created from template previously)
                    return true;
                });

                foreach ($toDelete as $del) {
                    $del->delete();
                }

                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error('Failed to sync template to list', ['template_id' => $this->id, 'list_id' => $list->id, 'error' => $e->getMessage()]);
            }
        }
    }
}

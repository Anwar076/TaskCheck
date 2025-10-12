<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskList;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, TaskList $list)
    {
        $selectedWeekday = $request->get('weekday');
        
        // If creating for a specific weekday, find or create the sublist
        $targetList = $list;
        if ($selectedWeekday && $list->hasWeeklyStructure()) {
            // For weekly structure lists, we don't create sublists
            // Instead, we just pass the weekday parameter to the form
            $targetList = $list;
        }
        
        return view('admin.tasks.create', compact('list', 'targetList', 'selectedWeekday'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TaskList $list)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'required_proof_type' => 'required|in:none,photo,video,text,file,any',
            'is_required' => 'boolean',
            'requires_signature' => 'boolean',
            'order_index' => 'nullable|integer|min:1',
            'target_list_id' => 'nullable|exists:lists,id', // For weekday specific creation
            'weekdays' => 'nullable|array', // For weekly structure
            'weekdays.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'checklist_items' => 'nullable|array',
            'checklist_items.*' => 'string|max:500',
        ]);

        // Determine the target list (weekday sublist or main list)
        $targetList = $list;
        if (isset($validated['target_list_id']) && $validated['target_list_id'] && $validated['target_list_id'] !== $list->id) {
            $targetList = TaskList::findOrFail($validated['target_list_id']);
        }

        $validated['list_id'] = $targetList->id;
        $validated['is_required'] = $request->has('is_required');
        $validated['requires_signature'] = $request->has('requires_signature');
        $validated['order_index'] = $validated['order_index'] ?? ($targetList->tasks()->max('order_index') + 1);
        
        // Clean up checklist items (remove empty items)
        if (isset($validated['checklist_items']) && is_array($validated['checklist_items'])) {
            $validated['checklist_items'] = array_values(array_filter($validated['checklist_items'], function($item) {
                return !empty(trim($item));
            }));
            if (empty($validated['checklist_items'])) {
                $validated['checklist_items'] = null;
            }
        }
        
        // Remove target_list_id from validated data as it's not a Task field
        unset($validated['target_list_id']);

        // Handle weekday assignment for tasks
        if ($list->hasWeeklyStructure() && isset($validated['weekdays']) && !empty($validated['weekdays'])) {
            // Multiple weekdays selected - create task for each day
            $weekdays = $validated['weekdays'];
            unset($validated['weekdays']); // Remove from main data
            
            $createdTasks = [];
            foreach ($weekdays as $weekday) {
                $taskData = $validated;
                $taskData['weekday'] = $weekday;
                $taskData['order'] = $validated['order_index']; // Use order_index as order
                $taskData['created_by'] = auth()->id();
                
                $createdTasks[] = Task::create($taskData);
            }
            
            return redirect()->route('admin.lists.show', ['list' => $list->id, 'updated' => time()])
                ->with('success', 'Tasks created successfully for ' . count($weekdays) . ' day(s).');
        } else {
            // Single task creation
            $validated['created_by'] = auth()->id();
            
            // If no weekdays selected, this is a general task (weekday = null)
            if (!isset($validated['weekdays']) || empty($validated['weekdays'])) {
                $validated['weekday'] = null;
            }
            
            $task = Task::create($validated);
            
            return redirect()->route('admin.lists.show', ['list' => $list->id, 'updated' => time()])
                ->with('success', 'Task added successfully.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('admin.tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'required_proof_type' => 'required|in:none,photo,video,text,file,any',
            'is_required' => 'boolean',
            'requires_signature' => 'boolean',
            'order_index' => 'nullable|integer|min:1',
            'weekdays' => 'nullable|array', // For weekly structure
            'weekdays.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'checklist_items' => 'nullable|array',
            'checklist_items.*' => 'string|max:500',
        ]);

        $validated['is_required'] = $request->has('is_required');
        $validated['requires_signature'] = $request->has('requires_signature');

        // Clean up checklist items (remove empty items)
        if (isset($validated['checklist_items']) && is_array($validated['checklist_items'])) {
            $validated['checklist_items'] = array_values(array_filter($validated['checklist_items'], function($item) {
                return !empty(trim($item));
            }));
            if (empty($validated['checklist_items'])) {
                $validated['checklist_items'] = null;
            }
        }

        // Handle weekdays for weekly structure
        if ($task->taskList->hasWeeklyStructure() && isset($validated['weekdays']) && !empty($validated['weekdays'])) {
            $weekdays = $validated['weekdays'];
            unset($validated['weekdays']); // Remove from main data
            
            // Update the task with the first selected weekday (for single task editing)
            $validated['weekday'] = $weekdays[0];
            $validated['order'] = $validated['order_index']; // Use order_index as order
        } else {
            // Clear weekday if no days selected (general task)
            $validated['weekday'] = null;
        }

        $task->update($validated);

        return redirect()->route('admin.lists.show', ['list' => $task->taskList->id, 'updated' => time()])
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $list = $task->taskList;
        
        // Delete all related submission tasks first to avoid foreign key constraints
        $task->submissionTasks()->delete();
        
        // Delete the task itself
        $task->delete();

        return redirect()->route('admin.lists.show', ['list' => $list->id, 'updated' => time()])
            ->with('success', 'Task deleted successfully.');
    }
}

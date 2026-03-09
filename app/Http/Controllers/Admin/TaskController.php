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
        
        // With the new agenda system, we always work with the main list
        // Tasks can be assigned to specific days using the weekday field
        $targetList = $list;
        
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
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
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
        $validated['start_time'] = !empty($validated['start_time']) ? $validated['start_time'] : null;
        $validated['end_time'] = !empty($validated['end_time']) ? $validated['end_time'] : null;
        
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

        // Handle weekday assignment for tasks in the new agenda system
        if (in_array($list->schedule_type, ['daily', 'weekly', 'custom']) && isset($validated['weekdays']) && !empty($validated['weekdays'])) {
            // Multiple weekdays selected - create separate task for each day
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
            
            $dayLabels = ['monday'=>'Maandag','tuesday'=>'Dinsdag','wednesday'=>'Woensdag','thursday'=>'Donderdag','friday'=>'Vrijdag','saturday'=>'Zaterdag','sunday'=>'Zondag'];
            $daysList = implode(', ', array_map(fn($d) => $dayLabels[$d] ?? ucfirst($d), $weekdays));
            return redirect()->route('admin.lists.show', ['list' => $list->id, 'updated' => time()])
                ->with('success', "Taak '{$validated['title']}' is aangemaakt voor: {$daysList}. Deze taak verschijnt ALLEEN op deze geselecteerde dagen.");
        } else {
            // Single task creation (general task available every day the list is active)
            $validated['created_by'] = auth()->id();
            $validated['order'] = $validated['order_index']; // Use order_index as order
            
            // If no weekdays selected, this is a general task (weekday = null)
            // This means the task will appear every day the list is available
            $validated['weekday'] = null;
            
            // Remove weekdays from validated data as it's not a Task field
            unset($validated['weekdays']);
            
            $task = Task::create($validated);
            
            return redirect()->route('admin.lists.show', ['list' => $list->id, 'updated' => time()])
                ->with('success', "Algemene taak '{$validated['title']}' is toegevoegd. Deze taak verschijnt ELKE dag dat deze lijst actief is (geen specifieke dagen geselecteerd).");
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
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'weekdays' => 'nullable|array', // For weekly structure
            'weekdays.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'checklist_items' => 'nullable|array',
            'checklist_items.*' => 'string|max:500',
        ]);

        $validated['is_required'] = $request->has('is_required');
        $validated['requires_signature'] = $request->has('requires_signature');
        $validated['start_time'] = !empty($validated['start_time']) ? $validated['start_time'] : null;
        $validated['end_time'] = !empty($validated['end_time']) ? $validated['end_time'] : null;

        // Clean up checklist items (remove empty items)
        if (isset($validated['checklist_items']) && is_array($validated['checklist_items'])) {
            $validated['checklist_items'] = array_values(array_filter($validated['checklist_items'], function($item) {
                return !empty(trim($item));
            }));
            if (empty($validated['checklist_items'])) {
                $validated['checklist_items'] = null;
            }
        }

        // Handle weekdays for the new agenda system
        if (in_array($task->taskList->schedule_type, ['daily', 'weekly', 'custom']) && isset($validated['weekdays']) && !empty($validated['weekdays'])) {
            $weekdays = $validated['weekdays'];
            unset($validated['weekdays']); // Remove from main data
            
            // Update the task with the first selected weekday (for single task editing)
            // Note: If multiple days are selected, only the first one is used for updates
            // For multiple days, users should create separate tasks
            $validated['weekday'] = $weekdays[0];
        } else {
            // Clear weekday if no days selected (general task)
            $validated['weekday'] = null;
        }
        
        // Remove weekdays from validated data as it's not a Task field
        unset($validated['weekdays']);

        // Order/position is managed via drag-and-drop in the list view, not in the form
        unset($validated['order_index']);

        $task->update($validated);

        return redirect()->route('admin.lists.show', ['list' => $task->taskList->id, 'updated' => time()])
            ->with('success', 'Taak succesvol bijgewerkt.');
    }

    /**
     * Reorder tasks via drag-and-drop.
     */
    public function reorder(Request $request, TaskList $list)
    {
        $validated = $request->validate([
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|exists:tasks,id',
            'tasks.*.order_index' => 'required|integer|min:0',
        ]);

        \DB::beginTransaction();
        try {
            foreach ($validated['tasks'] as $taskData) {
                $updated = Task::where('id', $taskData['id'])
                    ->where('list_id', $list->id)
                    ->update([
                        'order_index' => $taskData['order_index'],
                        'order' => $taskData['order_index'], // TaskList orders by order first, then order_index
                    ]);
                if ($updated === 0) {
                    \DB::rollBack();
                    if ($request->expectsJson()) {
                        return response()->json(['success' => false, 'message' => 'Taak niet gevonden of hoort niet bij deze lijst'], 422);
                    }
                    return redirect()->back()->with('error', 'Fout: taak niet gevonden bij deze lijst');
                }
            }
            \DB::commit();

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Volgorde opgeslagen']);
            }
            return redirect()->route('admin.lists.show', $list)->with('success', 'Volgorde opgeslagen');
        } catch (\Exception $e) {
            \DB::rollBack();
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Fout bij opslaan'], 500);
            }
            return redirect()->route('admin.lists.show', $list)->with('error', 'Fout bij opslaan volgorde');
        }
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
            ->with('success', 'Taak succesvol verwijderd.');
    }
}

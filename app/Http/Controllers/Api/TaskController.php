<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    /**
     * Display tasks for a specific list
     */
    public function index(Request $request, $listId): JsonResponse
    {
        try {
            $list = TaskList::findOrFail($listId);
            
            $query = $list->tasks()->orderBy('order_index');

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = $request->get('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%");
                });
            }

            // Status filter
            if ($request->filled('is_active')) {
                $query->where('is_active', $request->get('is_active') === 'true');
            }

            // Required filter
            if ($request->filled('is_required')) {
                $query->where('is_required', $request->get('is_required') === 'true');
            }

            $tasks = $query->get();

            return response()->json([
                'success' => true,
                'data' => $tasks,
                'message' => 'Tasks retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve tasks: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created task
     */
    public function store(Request $request, $listId): JsonResponse
    {
        try {
            $list = TaskList::findOrFail($listId);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'instructions' => 'nullable|string',
                'required_proof_type' => 'required|in:none,photo,video,text,file,any',
                'is_required' => 'boolean',
                'checklist_items' => 'nullable|array',
                'checklist_items.*' => 'string|max:255',
                'order_index' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
                'attachments' => 'nullable|array',
                'validation_rules' => 'nullable|array',
            ]);

            \DB::beginTransaction();

            // Get next order index if not provided
            if (!isset($validated['order_index'])) {
                $maxOrder = $list->tasks()->max('order_index') ?? 0;
                $validated['order_index'] = $maxOrder + 1;
            }

            $task = Task::create([
                'list_id' => $list->id,
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'instructions' => $validated['instructions'] ?? null,
                'required_proof_type' => $validated['required_proof_type'],
                'is_required' => $validated['is_required'] ?? true,
                'checklist_items' => $validated['checklist_items'] ?? null,
                'order_index' => $validated['order_index'],
                'is_active' => $validated['is_active'] ?? true,
                'attachments' => $validated['attachments'] ?? null,
                'validation_rules' => $validated['validation_rules'] ?? null,
            ]);

            \DB::commit();

            return response()->json([
                'success' => true,
                'data' => $task,
                'message' => 'Task created successfully'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified task
     */
    public function show($listId, $taskId): JsonResponse
    {
        try {
            $list = TaskList::findOrFail($listId);
            $task = $list->tasks()->findOrFail($taskId);

            return response()->json([
                'success' => true,
                'data' => $task,
                'message' => 'Task retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified task
     */
    public function update(Request $request, $listId, $taskId): JsonResponse
    {
        try {
            $list = TaskList::findOrFail($listId);
            $task = $list->tasks()->findOrFail($taskId);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'instructions' => 'nullable|string',
                'required_proof_type' => 'required|in:none,photo,video,text,file,any',
                'is_required' => 'boolean',
                'checklist_items' => 'nullable|array',
                'checklist_items.*' => 'string|max:255',
                'order_index' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
                'attachments' => 'nullable|array',
                'validation_rules' => 'nullable|array',
            ]);

            \DB::beginTransaction();

            $task->update($validated);

            \DB::commit();

            return response()->json([
                'success' => true,
                'data' => $task,
                'message' => 'Task updated successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified task
     */
    public function destroy($listId, $taskId): JsonResponse
    {
        try {
            $list = TaskList::findOrFail($listId);
            $task = $list->tasks()->findOrFail($taskId);

            \DB::beginTransaction();

            $task->delete();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reorder tasks
     */
    public function reorder(Request $request, $listId): JsonResponse
    {
        try {
            $list = TaskList::findOrFail($listId);

            $validated = $request->validate([
                'tasks' => 'required|array',
                'tasks.*.id' => 'required|exists:tasks,id',
                'tasks.*.order_index' => 'required|integer|min:0',
            ]);

            \DB::beginTransaction();

            foreach ($validated['tasks'] as $taskData) {
                Task::where('id', $taskData['id'])
                    ->where('list_id', $list->id)
                    ->update(['order_index' => $taskData['order_index']]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tasks reordered successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder tasks: ' . $e->getMessage()
            ], 500);
        }
    }
}

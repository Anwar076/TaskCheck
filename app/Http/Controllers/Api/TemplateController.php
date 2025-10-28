<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskTemplate;
use App\Models\TemplateTask;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates
     */
    public function index(): JsonResponse
    {
        try {
            $templates = TaskTemplate::with(['templateTasks' => function($query) {
                $query->orderBy('sort_order');
            }])->orderBy('name')->get();

            return response()->json([
                'success' => true,
                'data' => $templates,
                'message' => 'Templates retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve templates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
                'tasks' => 'required|array|min:1',
                'tasks.*.title' => 'required|string|max:255',
                'tasks.*.description' => 'nullable|string',
                'tasks.*.instructions' => 'nullable|string',
                'tasks.*.required_proof_type' => 'required|in:none,photo,video,text,file,any',
                'tasks.*.is_required' => 'boolean',
                'tasks.*.checklist_items' => 'nullable|array',
            ]);

            // Start database transaction
            \DB::beginTransaction();

            // Create template
            $template = TaskTemplate::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // Create template tasks
            foreach ($validated['tasks'] as $index => $taskData) {
                TemplateTask::create([
                    'template_id' => $template->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'] ?? null,
                    'instructions' => $taskData['instructions'] ?? null,
                    'required_proof_type' => $taskData['required_proof_type'],
                    'is_required' => $taskData['is_required'] ?? true,
                    'checklist_items' => $taskData['checklist_items'] ?? null,
                    'sort_order' => $index,
                    'is_active' => true,
                ]);
            }

            \DB::commit();

            // Reload with relationships
            $template->load('templateTasks');

            return response()->json([
                'success' => true,
                'data' => $template,
                'message' => 'Template created successfully'
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
                'message' => 'Failed to create template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified template
     */
    public function show($id): JsonResponse
    {
        try {
            $template = TaskTemplate::with(['templateTasks' => function($query) {
                $query->orderBy('sort_order');
            }])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $template,
                'message' => 'Template retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Template not found: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $template = TaskTemplate::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
                'tasks' => 'required|array|min:1',
                'tasks.*.id' => 'nullable|exists:template_tasks,id',
                'tasks.*.title' => 'required|string|max:255',
                'tasks.*.description' => 'nullable|string',
                'tasks.*.instructions' => 'nullable|string',
                'tasks.*.required_proof_type' => 'required|in:none,photo,video,text,file,any',
                'tasks.*.is_required' => 'boolean',
                'tasks.*.checklist_items' => 'nullable|array',
            ]);

            // Start database transaction
            \DB::beginTransaction();

            // Update template
            $template->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            // Get existing task IDs
            $existingTaskIds = $template->templateTasks->pluck('id')->toArray();
            $newTaskIds = collect($validated['tasks'])->pluck('id')->filter()->toArray();
            
            // Delete tasks that are no longer in the request
            $tasksToDelete = array_diff($existingTaskIds, $newTaskIds);
            if (!empty($tasksToDelete)) {
                TemplateTask::whereIn('id', $tasksToDelete)->delete();
            }

            // Update or create tasks
            foreach ($validated['tasks'] as $index => $taskData) {
                if (isset($taskData['id']) && $taskData['id']) {
                    // Update existing task
                    $templateTask = TemplateTask::find($taskData['id']);
                    if ($templateTask) {
                        $templateTask->update([
                            'title' => $taskData['title'],
                            'description' => $taskData['description'] ?? null,
                            'instructions' => $taskData['instructions'] ?? null,
                            'required_proof_type' => $taskData['required_proof_type'],
                            'is_required' => $taskData['is_required'] ?? true,
                            'checklist_items' => $taskData['checklist_items'] ?? null,
                            'sort_order' => $index,
                        ]);
                    }
                } else {
                    // Create new task
                    TemplateTask::create([
                        'template_id' => $template->id,
                        'title' => $taskData['title'],
                        'description' => $taskData['description'] ?? null,
                        'instructions' => $taskData['instructions'] ?? null,
                        'required_proof_type' => $taskData['required_proof_type'],
                        'is_required' => $taskData['is_required'] ?? true,
                        'checklist_items' => $taskData['checklist_items'] ?? null,
                        'sort_order' => $index,
                        'is_active' => true,
                    ]);
                }
            }

            \DB::commit();

            // Reload with relationships
            $template->load('templateTasks');

            return response()->json([
                'success' => true,
                'data' => $template,
                'message' => 'Template updated successfully'
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
                'message' => 'Failed to update template: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified template
     */
    public function destroy($id): JsonResponse
    {
        try {
            $template = TaskTemplate::findOrFail($id);

            // Start database transaction
            \DB::beginTransaction();

            // Delete template tasks first (cascade should handle this, but being explicit)
            $template->templateTasks()->delete();
            
            // Delete template
            $template->delete();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Template deleted successfully'
            ]);

        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete template: ' . $e->getMessage()
            ], 500);
        }
    }
}

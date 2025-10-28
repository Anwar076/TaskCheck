<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskList;
use App\Models\TaskTemplate;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TaskListController extends Controller
{
    /**
     * Display a listing of task lists
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = TaskList::with(['creator', 'tasks', 'template'])
                ->withCount('submissions');

            // Search functionality
            if ($request->filled('search')) {
                $searchTerm = $request->get('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%")
                      ->orWhere('category', 'like', "%{$searchTerm}%");
                });
            }

            // Category filter
            if ($request->filled('category')) {
                $query->where('category', $request->get('category'));
            }

            // Priority filter
            if ($request->filled('priority')) {
                $query->where('priority', $request->get('priority'));
            }

            $lists = $query->latest()->paginate(15);

            return response()->json([
                'success' => true,
                'data' => $lists,
                'message' => 'Task lists retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve task lists: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created task list
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'parent_list_id' => 'nullable|exists:lists,id',
                'template_id' => 'nullable|exists:task_templates,id',
                'schedule_type' => 'required|in:once,daily,weekly,monthly,custom',
                'schedule_config' => 'nullable|array',
                'priority' => 'required|in:low,medium,high,urgent',
                'due_date' => 'nullable|date',
                'category' => 'nullable|string|max:100',
                'requires_signature' => 'boolean',
                'is_template' => 'boolean',
                'is_active' => 'boolean',
            ]);

            // Start database transaction
            \DB::beginTransaction();

            // Create task list
            $list = TaskList::create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'parent_list_id' => $validated['parent_list_id'] ?? null,
                'template_id' => $validated['template_id'] ?? null,
                'schedule_type' => $validated['schedule_type'],
                'schedule_config' => $validated['schedule_config'] ?? null,
                'priority' => $validated['priority'],
                'due_date' => $validated['due_date'] ?? null,
                'category' => $validated['category'] ?? null,
                'requires_signature' => $validated['requires_signature'] ?? false,
                'is_template' => $validated['is_template'] ?? false,
                'is_active' => $validated['is_active'] ?? true,
                'created_by' => auth()->id(),
            ]);

            // Copy tasks from template if provided
            if (!empty($validated['template_id'])) {
                $template = TaskTemplate::with('templateTasks')->find($validated['template_id']);
                if ($template) {
                    foreach ($template->templateTasks as $templateTask) {
                        Task::create([
                            'list_id' => $list->id,
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
                }
            }

            \DB::commit();

            // Reload with relationships
            $list->load(['creator', 'tasks', 'template']);

            return response()->json([
                'success' => true,
                'data' => $list,
                'message' => 'Task list created successfully with ' . $list->tasks()->count() . ' tasks'
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
                'message' => 'Failed to create task list: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified task list
     */
    public function show($id): JsonResponse
    {
        try {
            $list = TaskList::with(['creator', 'tasks', 'template', 'assignments.user', 'submissions.user'])
                ->withCount('submissions')
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $list,
                'message' => 'Task list retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Task list not found: ' . $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get available templates for creating lists
     */
    public function getTemplates(): JsonResponse
    {
        try {
            $templates = TaskTemplate::with('templateTasks')
                ->where('is_active', true)
                ->orderBy('name')
                ->get();

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
}
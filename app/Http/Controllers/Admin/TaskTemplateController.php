<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\MetricValidationHelper;
use App\Models\Checklist\TaskTemplate;
use App\Models\Checklist\TemplateTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TaskTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Force fresh data from database
        $templates = TaskTemplate::with(['templateTasks', 'taskLists'])->orderBy('name')->get();
        $globalTemplatesQuery = TaskTemplate::withoutGlobalScopes()
            ->publishedGlobal();

        if (Schema::hasColumn('task_templates', 'target_company_type')) {
            $globalTemplatesQuery->where(function ($query) {
                $companyType = auth()->user()->company->company_type ?? null;
                $query->whereNull('target_company_type');
                if ($companyType) {
                    $query->orWhere('target_company_type', $companyType);
                }
            });
        }

        $globalTemplates = $globalTemplatesQuery
            ->with('templateTasks')
            ->orderBy('name')
            ->get();
        
        // Debug: Always log what we're doing
        \Log::info('TaskTemplateController@index called', [
            'is_ajax' => $request->ajax(),
            'expects_json' => $request->expectsJson(),
            'accept_header' => $request->header('Accept'),
            'templates_count' => $templates->count()
        ]);

        // If this is an AJAX request, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'data' => $templates,
                'total' => $templates->count(),
                'current_page' => 1,
                'last_page' => 1,
                'per_page' => $templates->count(),
                'from' => 1,
                'to' => $templates->count()
            ]);
        }

        // Otherwise, return the regular view
        $companyTemplatesBySource = TaskTemplate::withoutGlobalScopes()
            ->where('company_id', auth()->user()->company_id)
            ->whereNotNull('source_template_id')
            ->get()
            ->keyBy('source_template_id');

        $templateLibrary = $globalTemplates->map(function (TaskTemplate $globalTemplate) use ($companyTemplatesBySource) {
            $linked = $companyTemplatesBySource->get($globalTemplate->id);
            $hasUpdate = $linked && (
                !$linked->source_updated_at || $globalTemplate->updated_at->gt($linked->source_updated_at)
            );

            return [
                'global' => $globalTemplate,
                'linked_template_id' => $linked?->id,
                'is_imported' => (bool) $linked,
                'has_update' => (bool) $hasUpdate,
            ];
        });

        return view('admin.templates.index', compact('templates', 'templateLibrary'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.templates.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tasks' => 'required|array|min:1',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.instructions' => 'nullable|string',
            'tasks.*.required_proof_type' => 'required|in:none,photo,video,text,file,any',
            'tasks.*.is_required' => 'boolean',
            'tasks.*.checklist_items' => 'nullable|array',
            'tasks.*.start_time' => 'nullable|date_format:H:i',
            'tasks.*.end_time' => 'nullable|date_format:H:i|after:tasks.*.start_time',
            'tasks.*.metric_type' => 'nullable|in:temperature,ph',
            'tasks.*.metric_unit' => 'nullable|string|max:20',
            'tasks.*.metric_min' => 'nullable|numeric',
            'tasks.*.metric_max' => 'nullable|numeric',
            'tasks.*.metric_comparison' => 'nullable|in:lt,lte',
        ]);

        $metricErrors = [];
        foreach ($validated['tasks'] as $index => $taskData) {
            foreach (MetricValidationHelper::validateFormData($taskData, "tasks.{$index}") as $key => $message) {
                $metricErrors[$key] = $message;
            }
        }
        if ($metricErrors !== []) {
            return back()->withErrors($metricErrors)->withInput();
        }

        $template = TaskTemplate::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_active' => true,
        ]);

        // Create template tasks
        foreach ($validated['tasks'] as $index => $taskData) {
            // Filter out empty checklist items and ensure proper format
            $checklistItems = isset($taskData['checklist_items']) ? array_values(array_filter($taskData['checklist_items'], function($item) {
                return !empty(trim($item));
            })) : null;
            
            // Convert to null if empty array
            if (is_array($checklistItems) && empty($checklistItems)) {
                $checklistItems = null;
            }
            
            TemplateTask::create([
                'validation_rules' => MetricValidationHelper::buildFromFormData($taskData),
                'template_id' => $template->id,
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'instructions' => $taskData['instructions'] ?? null,
                'required_proof_type' => $taskData['required_proof_type'],
                'is_required' => $taskData['is_required'] ?? true,
                'checklist_items' => $checklistItems,
                'start_time' => !empty($taskData['start_time']) ? $taskData['start_time'] : null,
                'end_time' => !empty($taskData['end_time']) ? $taskData['end_time'] : null,
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template succesvol aangemaakt!');
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskTemplate $template)
    {
        $template->load('templateTasks');
        
        return view('admin.templates.show', compact('template'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskTemplate $template)
    {
        $template->load('templateTasks');
        
        return view('admin.templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskTemplate $template)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tasks' => 'required|array|min:1',
            'tasks.*.id' => 'nullable|exists:template_tasks,id',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.instructions' => 'nullable|string',
            'tasks.*.required_proof_type' => 'required|in:none,photo,video,text,file,any',
            'tasks.*.is_required' => 'boolean',
            'tasks.*.checklist_items' => 'nullable|array',
            'tasks.*.start_time' => 'nullable|date_format:H:i',
            'tasks.*.end_time' => 'nullable|date_format:H:i|after:tasks.*.start_time',
            'tasks.*.metric_type' => 'nullable|in:temperature,ph',
            'tasks.*.metric_unit' => 'nullable|string|max:20',
            'tasks.*.metric_min' => 'nullable|numeric',
            'tasks.*.metric_max' => 'nullable|numeric',
            'tasks.*.metric_comparison' => 'nullable|in:lt,lte',
        ]);

        $metricErrors = [];
        foreach ($validated['tasks'] as $index => $taskData) {
            foreach (MetricValidationHelper::validateFormData($taskData, "tasks.{$index}") as $key => $message) {
                $metricErrors[$key] = $message;
            }
        }
        if ($metricErrors !== []) {
            return back()->withErrors($metricErrors)->withInput();
        }

        // Update template
        $template->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
        ]);

        // Get existing task IDs
        $existingTaskIds = collect($validated['tasks'])->pluck('id')->filter()->toArray();
        
        // Delete removed tasks
        $template->templateTasks()->whereNotIn('id', $existingTaskIds)->delete();

        // Update or create tasks
        foreach ($validated['tasks'] as $index => $taskData) {
            // Filter out empty checklist items and ensure proper format
            $checklistItems = isset($taskData['checklist_items']) ? array_values(array_filter($taskData['checklist_items'], function($item) {
                return !empty(trim($item));
            })) : null;
            
            // Convert to null if empty array
            if (is_array($checklistItems) && empty($checklistItems)) {
                $checklistItems = null;
            }
            
            if (isset($taskData['id']) && $taskData['id']) {
                // Update existing task
                TemplateTask::where('id', $taskData['id'])
                    ->update([
                        'validation_rules' => MetricValidationHelper::buildFromFormData($taskData),
                        'title' => $taskData['title'],
                        'description' => $taskData['description'],
                        'instructions' => $taskData['instructions'] ?? null,
                        'required_proof_type' => $taskData['required_proof_type'],
                        'is_required' => $taskData['is_required'] ?? true,
                        'checklist_items' => $checklistItems,
                        'start_time' => !empty($taskData['start_time']) ? $taskData['start_time'] : null,
                        'end_time' => !empty($taskData['end_time']) ? $taskData['end_time'] : null,
                        'sort_order' => $index,
                    ]);
            } else {
                // Create new task
                TemplateTask::create([
                    'validation_rules' => MetricValidationHelper::buildFromFormData($taskData),
                    'template_id' => $template->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'],
                    'instructions' => $taskData['instructions'] ?? null,
                    'required_proof_type' => $taskData['required_proof_type'],
                    'is_required' => $taskData['is_required'] ?? true,
                    'checklist_items' => $checklistItems,
                    'start_time' => !empty($taskData['start_time']) ? $taskData['start_time'] : null,
                    'end_time' => !empty($taskData['end_time']) ? $taskData['end_time'] : null,
                    'sort_order' => $index,
                    'is_active' => true,
                ]);
            }
        }

        // After updating template tasks, sync changes to any existing lists created from this template
        try {
            $template->load('templateTasks');
            $template->syncToLists();
        } catch (\Exception $e) {
            \Log::error('Failed to sync template to lists after update', ['template_id' => $template->id, 'error' => $e->getMessage()]);
        }

        // Check if this is coming from the show page (if referer contains /templates/{id})
        $referer = request()->headers->get('referer');
        if ($referer && strpos($referer, "/admin/templates/{$template->id}") !== false) {
            return redirect()->route('admin.templates.show', $template)
                ->with('success', 'Template succesvol bijgewerkt!')
                ->with('template_updated', true);
        }

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template succesvol bijgewerkt!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, TaskTemplate $template)
    {
        // Allow a forced unlink or delete via query param `force`
        // force=unlink -> set template_id = null on lists that reference this template, then delete template
        // force=delete -> delete related lists (dangerous)
        $force = $request->query('force');

        $listsCount = $template->taskLists()->count();

        if ($listsCount > 0 && $force !== 'unlink' && $force !== 'delete') {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kan template niet verwijderen: wordt nog gebruikt door bestaande lijsten.'
                ], 422);
            }

            return redirect()->route('admin.templates.index')
                ->with('error', 'Kan template niet verwijderen: wordt nog gebruikt door bestaande lijsten.');
        }

        if ($listsCount > 0 && $force === 'unlink') {
            // Unlink template from all lists (safe)
            foreach ($template->taskLists as $list) {
                $list->template_id = null;
                $list->save();
            }
            $template->delete();
        } elseif ($listsCount > 0 && $force === 'delete') {
            // Dangerous: delete lists that reference this template (and optionally cascade)
            foreach ($template->taskLists as $list) {
                // Delete list - this will also delete tasks via model relationships if configured
                $list->delete();
            }
            $template->delete();
        } else {
            // No lists reference it, safe to delete
            $template->delete();
        }

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Template succesvol verwijderd!'
            ]);
        }

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template succesvol verwijderd!');
    }

    /**
     * Create a new task list from template
     */
    public function createFromTemplate(Request $request, TaskTemplate $template)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $taskList = $template->createTaskList($validated);

        $company = auth()->user()->company;
        if ($company) {
            app(\App\Services\Platform\AdminOnboardingService::class)->handleListCreated($company, $taskList);
        }

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Takenlijst succesvol aangemaakt uit template!',
                'redirect' => route('admin.lists.show', $taskList)
            ]);
        }

        return redirect()->route('admin.lists.show', $taskList)
            ->with('success', 'Takenlijst succesvol aangemaakt uit template!');
    }

    public function importGlobalTemplate(TaskTemplate $template)
    {
        if ($template->company_id !== null) {
            abort(404);
        }

        $companyId = auth()->user()->company_id;
        $existing = TaskTemplate::withoutGlobalScopes()
            ->where('company_id', $companyId)
            ->where('source_template_id', $template->id)
            ->first();

        if ($existing) {
            return redirect()->route('admin.templates.index')->with('success', 'Template is al geimporteerd.');
        }

        $template->load('templateTasks');

        DB::transaction(function () use ($template, $companyId) {
            $newTemplate = TaskTemplate::withoutGlobalScopes()->create([
                'name' => $template->name,
                'description' => $template->description,
                'is_active' => true,
                'company_id' => $companyId,
                'source_template_id' => $template->id,
                'source_updated_at' => $template->updated_at,
                'category' => $template->category,
                'icon' => $template->icon,
                'frequency_label' => $template->frequency_label,
                'frequency_type' => $template->frequency_type,
                'is_starter_pack' => (bool) $template->is_starter_pack,
                'starter_pack_group' => $template->starter_pack_group,
                'khn_reference' => $template->khn_reference,
                'compliance_rules' => $template->compliance_rules,
            ]);

            foreach ($template->templateTasks as $task) {
                TemplateTask::create([
                    'template_id' => $newTemplate->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'instructions' => $task->instructions,
                    'required_proof_type' => $task->required_proof_type,
                    'is_required' => $task->is_required,
                    'checklist_items' => $task->checklist_items,
                    'attachments' => $task->attachments,
                    'validation_rules' => $task->validation_rules,
                    'start_time' => $task->start_time,
                    'end_time' => $task->end_time,
                    'sort_order' => $task->sort_order,
                    'is_active' => true,
                ]);
            }
        });

        return redirect()->route('admin.templates.index')->with('success', 'Nieuwe template geimporteerd.');
    }

    public function applyGlobalTemplateUpdate(TaskTemplate $template)
    {
        $companyId = auth()->user()->company_id;
        if ($template->company_id !== $companyId || !$template->source_template_id) {
            abort(404);
        }

        $sourceTemplate = TaskTemplate::withoutGlobalScopes()
            ->whereNull('company_id')
            ->with('templateTasks')
            ->findOrFail($template->source_template_id);

        DB::transaction(function () use ($template, $sourceTemplate) {
            $template->update([
                'name' => $sourceTemplate->name,
                'description' => $sourceTemplate->description,
                'source_updated_at' => $sourceTemplate->updated_at,
                'category' => $sourceTemplate->category,
                'icon' => $sourceTemplate->icon,
                'frequency_label' => $sourceTemplate->frequency_label,
                'frequency_type' => $sourceTemplate->frequency_type,
                'is_starter_pack' => (bool) $sourceTemplate->is_starter_pack,
                'starter_pack_group' => $sourceTemplate->starter_pack_group,
                'khn_reference' => $sourceTemplate->khn_reference,
                'compliance_rules' => $sourceTemplate->compliance_rules,
            ]);

            $existingBySortOrder = $template->templateTasks()->get()->keyBy('sort_order');
            $incomingSortOrders = [];

            foreach ($sourceTemplate->templateTasks as $sourceTask) {
                $incomingSortOrders[] = $sourceTask->sort_order;

                $payload = [
                    'title' => $sourceTask->title,
                    'description' => $sourceTask->description,
                    'instructions' => $sourceTask->instructions,
                    'required_proof_type' => $sourceTask->required_proof_type,
                    'is_required' => $sourceTask->is_required,
                    'checklist_items' => $sourceTask->checklist_items,
                    'attachments' => $sourceTask->attachments,
                    'validation_rules' => $sourceTask->validation_rules,
                    'start_time' => $sourceTask->start_time,
                    'end_time' => $sourceTask->end_time,
                    'sort_order' => $sourceTask->sort_order,
                    'is_active' => true,
                ];

                $existingTask = $existingBySortOrder->get($sourceTask->sort_order);
                if ($existingTask) {
                    $existingTask->update($payload);
                } else {
                    $payload['template_id'] = $template->id;
                    TemplateTask::create($payload);
                }
            }

            $template->templateTasks()
                ->whereNotIn('sort_order', $incomingSortOrders)
                ->delete();

            $template->load('templateTasks');
            $template->syncToLists();
        });

        return redirect()->route('admin.templates.index')
            ->with('success', 'Template update toegepast op je takenlijsten.');
    }

}

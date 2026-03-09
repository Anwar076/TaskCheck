<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskTemplate;
use App\Models\TemplateTask;
use Illuminate\Http\Request;

class TaskTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Force fresh data from database
        $templates = TaskTemplate::with(['templateTasks', 'taskLists'])->orderBy('name')->get();
        
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
        return view('admin.templates.index', compact('templates'));
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
        ]);

        $template = TaskTemplate::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_active' => true,
        ]);

        // Create template tasks
        foreach ($validated['tasks'] as $index => $taskData) {
            // Filter out empty checklist items and ensure proper format
            $checklistItems = isset($taskData['checklist_items']) ? array_filter($taskData['checklist_items'], function($item) {
                return !empty(trim($item));
            }) : null;
            
            // Convert to null if empty array
            if (is_array($checklistItems) && empty($checklistItems)) {
                $checklistItems = null;
            }
            
            TemplateTask::create([
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
            ->with('success', 'Sjabloon succesvol aangemaakt!');
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
        ]);

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
            $checklistItems = isset($taskData['checklist_items']) ? array_filter($taskData['checklist_items'], function($item) {
                return !empty(trim($item));
            }) : null;
            
            // Convert to null if empty array
            if (is_array($checklistItems) && empty($checklistItems)) {
                $checklistItems = null;
            }
            
            if (isset($taskData['id']) && $taskData['id']) {
                // Update existing task
                TemplateTask::where('id', $taskData['id'])
                    ->update([
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
                ->with('success', 'Sjabloon succesvol bijgewerkt!')
                ->with('template_updated', true);
        }

        return redirect()->route('admin.templates.index')
            ->with('success', 'Sjabloon succesvol bijgewerkt!');
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
                    'message' => 'Kan sjabloon niet verwijderen: wordt nog gebruikt door bestaande lijsten.'
                ], 422);
            }

            return redirect()->route('admin.templates.index')
                ->with('error', 'Kan sjabloon niet verwijderen: wordt nog gebruikt door bestaande lijsten.');
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
                'message' => 'Sjabloon succesvol verwijderd!'
            ]);
        }

        return redirect()->route('admin.templates.index')
            ->with('success', 'Sjabloon succesvol verwijderd!');
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

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Takenlijst succesvol aangemaakt uit sjabloon!',
                'redirect' => route('admin.lists.show', $taskList)
            ]);
        }

        return redirect()->route('admin.lists.show', $taskList)
            ->with('success', 'Takenlijst succesvol aangemaakt uit sjabloon!');
    }
}

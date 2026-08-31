<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\MetricValidationHelper;
use App\Models\Checklist\TaskList;
use App\Models\Checklist\Task;
use App\Models\Organisation\User;
use App\Models\Checklist\TaskAssignment;
use App\Services\Ai\AiUsageLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class TaskController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, TaskList $list)
    {
        if ($list->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }

        $params = ['list' => $list, 'addTask' => 1];

        if ($request->filled('weekday')) {
            $params['weekday'] = $request->query('weekday');
        }

        return redirect()->route('admin.lists.show', $params);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, TaskList $list)
    {
        if ($list->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'control_reference' => 'nullable|string|max:255',
            'acceptance_criteria' => 'nullable|string|max:2000',
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
            'metric_type' => 'nullable|in:temperature,ph',
            'metric_unit' => 'nullable|string|max:20',
            'metric_min' => 'nullable|numeric',
            'metric_max' => 'nullable|numeric',
            'metric_comparison' => 'nullable|in:lt,lte',
        ]);

        // Determine the target list (weekday sublist or main list)
        $targetList = $list;
        if (isset($validated['target_list_id']) && $validated['target_list_id'] && $validated['target_list_id'] !== $list->id) {
            $targetList = TaskList::findOrFail($validated['target_list_id']);
        }

        $validated['list_id'] = $targetList->id;
        $validated['is_required'] = $request->boolean('is_required', true);
        $validated['requires_signature'] = $request->boolean('requires_signature');
        $validated['order_index'] = $validated['order_index'] ?? (($targetList->tasks()->max('order_index') ?? 0) + 1);
        $validated['start_time'] = null;
        $validated['end_time'] = null;

        // Clean up checklist items (remove empty items)
        if (isset($validated['checklist_items']) && is_array($validated['checklist_items'])) {
            $validated['checklist_items'] = array_values(array_filter($validated['checklist_items'], function($item) {
                return !empty(trim($item));
            }));
            if (empty($validated['checklist_items'])) {
                $validated['checklist_items'] = null;
            }
        }

        $metricErrors = MetricValidationHelper::validateFormData($validated);
        if ($metricErrors !== []) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => collect($metricErrors)->flatten()->first() ?? 'Validatiefout.',
                    'errors' => $metricErrors,
                ], 422);
            }

            return back()->withErrors($metricErrors)->withInput();
        }

        if (!empty($validated['metric_type'])) {
            $validated['validation_rules'] = MetricValidationHelper::buildFromFormData($validated);
        } else {
            $validated['validation_rules'] = null;
        }
        
        // Remove target_list_id from validated data as it's not a Task field
        unset($validated['target_list_id']);
        unset($validated['metric_type'], $validated['metric_unit'], $validated['metric_min'], $validated['metric_max'], $validated['metric_comparison']);

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
            $daysList = implode(', ', array_map(fn ($d) => $dayLabels[$d] ?? ucfirst($d), $weekdays));
            $message = "Taak '{$validated['title']}' is aangemaakt voor: {$daysList}.";

            return $this->storeTaskResponse($request, $list, $message);
        }

        // Single task creation (general task available every day the list is active)
        $validated['created_by'] = auth()->id();
        $validated['order'] = $validated['order_index'];

        // If no weekdays selected, this is a general task (weekday = null)
        $validated['weekday'] = null;
        unset($validated['weekdays']);

        Task::create($validated);

        $message = "Taak '{$validated['title']}' is toegevoegd.";

        return $this->storeTaskResponse($request, $list, $message);
    }

    private function storeTaskResponse(Request $request, TaskList $list, string $message)
    {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.lists.show', ['list' => $list->id, 'updated' => time()]),
            ]);
        }

        return redirect()->route('admin.lists.show', ['list' => $list->id, 'updated' => time()])
            ->with('success', $message);
    }

    public function quickStore(Request $request, TaskList $list)
    {
        if ($list->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task list.');
        }

        $validated = $request->validate([
            'task_id' => 'nullable|integer|exists:tasks,id',
            'title' => 'required_without:task_id|nullable|string|max:255',
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'required_proof_type' => 'nullable|in:none,photo,video,text,file,any',
            'is_required' => 'boolean',
            'requires_signature' => 'boolean',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'weekday' => 'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        ]);

        $validated['start_time'] = ! empty($validated['start_time']) ? $validated['start_time'] : null;
        $validated['end_time'] = ! empty($validated['end_time']) ? $validated['end_time'] : null;

        if ($validated['start_time'] && $validated['end_time'] && $validated['end_time'] <= $validated['start_time']) {
            return response()->json([
                'success' => false,
                'message' => 'Eindtijd moet na starttijd liggen.',
                'errors' => ['end_time' => ['Eindtijd moet na starttijd liggen.']],
            ], 422);
        }

        if (! empty($validated['task_id'])) {
            $task = Task::where('id', $validated['task_id'])
                ->where('list_id', $list->id)
                ->first();

            if (! $task || $list->company_id !== auth()->user()->company_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Taak niet gevonden.',
                ], 404);
            }

            $task->update([
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'weekday' => $validated['weekday'] ?? $task->weekday,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Taak '{$task->title}' is gekoppeld aan het tijdslot.",
                'task' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'edit_url' => route('admin.tasks.edit', $task),
                ],
            ]);
        }

        $validated['required_proof_type'] = $validated['required_proof_type'] ?? 'none';
        $validated['is_required'] = $request->boolean('is_required', true);
        $validated['requires_signature'] = $request->boolean('requires_signature');

        $orderIndex = ($list->tasks()->max('order_index') ?? 0) + 1;

        $task = Task::create([
            'list_id' => $list->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'required_proof_type' => $validated['required_proof_type'],
            'is_required' => $validated['is_required'],
            'requires_signature' => $validated['requires_signature'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'weekday' => $validated['weekday'] ?? null,
            'order_index' => $orderIndex,
            'order' => $orderIndex,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Taak '{$task->title}' is toegevoegd.",
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'edit_url' => route('admin.tasks.edit', $task),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        if ($task->taskList->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task.');
        }

        return redirect()->route('admin.lists.show', [
            'list' => $task->taskList,
            'editTask' => $task->id,
        ]);
    }

    public function formData(Task $task)
    {
        if ($task->taskList->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task.');
        }

        $rules = is_array($task->validation_rules) ? $task->validation_rules : [];

        return response()->json([
            'success' => true,
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'instructions' => $task->instructions,
                'required_proof_type' => $task->required_proof_type,
                'is_required' => (bool) $task->is_required,
                'requires_signature' => (bool) $task->requires_signature,
                'weekdays' => $task->weekday ? [$task->weekday] : [],
                'checklist_items' => $task->checklist_items ?? [],
                'metric_type' => $rules['metric'] ?? null,
                'metric_unit' => $rules['unit'] ?? null,
                'metric_min' => array_key_exists('min', $rules) ? $rules['min'] : null,
                'metric_max' => array_key_exists('max', $rules) ? $rules['max'] : null,
                'metric_comparison' => $rules['comparison'] ?? 'lte',
                'control_reference' => $task->control_reference,
                'acceptance_criteria' => $task->acceptance_criteria,
                'update_url' => route('admin.tasks.update', $task),
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if ($task->taskList->company_id !== auth()->user()->company_id) {
            abort(403, 'Unauthorized access to task.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'control_reference' => 'nullable|string|max:255',
            'acceptance_criteria' => 'nullable|string|max:2000',
            'instructions' => 'nullable|string',
            'required_proof_type' => 'required|in:none,photo,video,text,file,any',
            'is_required' => 'boolean',
            'requires_signature' => 'boolean',
            'order_index' => 'nullable|integer|min:1',
            'weekdays' => 'nullable|array', // For weekly structure
            'weekdays.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'checklist_items' => 'nullable|array',
            'checklist_items.*' => 'string|max:500',
            'metric_type' => 'nullable|in:temperature,ph',
            'metric_unit' => 'nullable|string|max:20',
            'metric_min' => 'nullable|numeric',
            'metric_max' => 'nullable|numeric',
            'metric_comparison' => 'nullable|in:lt,lte',
        ]);

        $validated['is_required'] = $request->boolean('is_required');
        $validated['requires_signature'] = $request->boolean('requires_signature');

        // Clean up checklist items (remove empty items)
        if (isset($validated['checklist_items']) && is_array($validated['checklist_items'])) {
            $validated['checklist_items'] = array_values(array_filter($validated['checklist_items'], function($item) {
                return !empty(trim($item));
            }));
            if (empty($validated['checklist_items'])) {
                $validated['checklist_items'] = null;
            }
        }

        $metricErrors = MetricValidationHelper::validateFormData($validated);
        if ($metricErrors !== []) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => collect($metricErrors)->flatten()->first() ?? 'Validatiefout.',
                    'errors' => $metricErrors,
                ], 422);
            }

            return back()->withErrors($metricErrors)->withInput();
        }

        if (!empty($validated['metric_type'])) {
            $validated['validation_rules'] = MetricValidationHelper::buildFromFormData($validated);
        } else {
            $validated['validation_rules'] = null;
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
        unset($validated['metric_type'], $validated['metric_unit'], $validated['metric_min'], $validated['metric_max'], $validated['metric_comparison']);

        // Order/position is managed via drag-and-drop in the list view, not in the form
        unset($validated['order_index']);

        $task->update($validated);

        $message = 'Taak succesvol bijgewerkt.';

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect' => route('admin.lists.show', ['list' => $task->taskList->id, 'updated' => time()]),
            ]);
        }

        return redirect()->route('admin.lists.show', ['list' => $task->taskList->id, 'updated' => time()])
            ->with('success', $message);
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

    public function aiSuggest(Request $request)
    {
        $company = auth()->user()->company;
        if (! $company?->hasPlanFeature('ai_suggestions')) {
            abort(403, 'AI is beschikbaar vanaf Professional.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'context' => 'nullable|string|max:2000',
        ]);

        $apiKey = Config::get('services.openai.key');
        $model = Config::get('services.openai.model', 'gpt-4.1-mini');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'AI is niet geconfigureerd (OPENAI_API_KEY ontbreekt).',
            ], 422);
        }

        $systemPrompt = <<<'PROMPT'
Je bent een Nederlandse assistent die helpt bij het definiëren van taken voor een checklist-/inspectiesysteem.

Je taak:
- Je krijgt een taaktitel en eventueel wat context.
- Schrijf een korte, duidelijke omschrijving (1-3 zinnen) in het Nederlands.
- Schrijf daarna concrete, genummerde stap-voor-stap instructies (minimaal 3, maximaal 8 stappen).
- Stel 5-10 checklist-items voor als korte bullets die ja/nee gecontroleerd kunnen worden.

Geef je ANTWOORD ALLEEN als JSON in dit formaat:
{
  "description": "korte omschrijving",
  "instructions": "1. stap\n2. stap\n3. stap...",
  "checklist_items": ["item 1", "item 2", "..."]
}

Schrijf alles in duidelijk, praktisch Nederlands. Vermijd overbodige uitleg.
PROMPT;

        $userPrompt = [
            'title' => $data['title'],
            'context' => $data['context'] ?? '',
        ];

        try {
            $response = Http::withToken($apiKey)
                ->timeout(20)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'response_format' => ['type' => 'json_object'],
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => 'Genereer voorstel voor deze taak: ' . json_encode($userPrompt, JSON_UNESCAPED_UNICODE)],
                    ],
                ]);

            if (!$response->ok()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI-verzoek mislukt: ' . $response->body(),
                ], 500);
            }

            AiUsageLogger::logChatCompletion(
                $response,
                AiUsageLogger::FEATURE_TASK_AI_SUGGEST,
                $company?->id,
                auth()->id(),
                $model
            );

            $content = $response->json('choices.0.message.content');
            $decoded = is_string($content) ? json_decode($content, true) : null;

            if (!is_array($decoded)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ongeldig AI-antwoord ontvangen.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'description' => $decoded['description'] ?? '',
                    'instructions' => $decoded['instructions'] ?? '',
                    'checklist_items' => $decoded['checklist_items'] ?? [],
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error('AI task suggest failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'AI-verzoek is mislukt: ' . $e->getMessage(),
            ], 500);
        }
    }
}

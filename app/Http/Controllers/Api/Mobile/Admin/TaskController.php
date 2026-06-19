<?php

namespace App\Http\Controllers\Api\Mobile\Admin;

use App\Http\Controllers\Api\Mobile\MobileController;
use App\Models\Checklist\Task;
use App\Models\Checklist\TaskList;
use Illuminate\Http\Request;

class TaskController extends MobileController
{
    public function store(Request $request, int $listId)
    {
        $list = $this->findCompanyList($request, $listId);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'required_proof_type' => ['required', 'in:none,photo,video,text,file,any'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $order = (int) Task::where('list_id', $list->id)->max('order_index') + 1;

        $task = Task::create([
            ...collect($validated)->except('is_active')->all(),
            'list_id' => $list->id,
            'created_by' => $request->user()->id,
            'order_index' => $order,
            'order' => $order,
            'is_required' => $validated['is_required'] ?? true,
        ]);

        if (array_key_exists('is_active', $validated)) {
            $task->forceFill(['is_active' => (bool) $validated['is_active']])->save();
        }

        return $this->success($this->formatTask($task), 'Taak aangemaakt.', 201);
    }

    public function update(Request $request, int $listId, int $taskId)
    {
        $task = $this->findCompanyTask($request, $listId, $taskId);

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'required_proof_type' => ['sometimes', 'in:none,photo,video,text,file,any'],
            'is_required' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $task->update($validated);

        return $this->success($this->formatTask($task->fresh()), 'Taak bijgewerkt.');
    }

    public function destroy(Request $request, int $listId, int $taskId)
    {
        $task = $this->findCompanyTask($request, $listId, $taskId);
        $task->update(['is_active' => false]);

        return $this->success(null, 'Taak verwijderd.');
    }

    protected function findCompanyList(Request $request, int $listId): TaskList
    {
        return TaskList::query()
            ->where('company_id', $request->user()->company_id)
            ->findOrFail($listId);
    }

    protected function findCompanyTask(Request $request, int $listId, int $taskId): Task
    {
        $this->findCompanyList($request, $listId);

        return Task::query()
            ->where('list_id', $listId)
            ->findOrFail($taskId);
    }

    protected function formatTask(Task $task): array
    {
        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'instructions' => $task->instructions,
            'is_required' => (bool) $task->is_required,
            'required_proof_type' => $task->required_proof_type,
            'is_active' => (bool) $task->is_active,
        ];
    }
}

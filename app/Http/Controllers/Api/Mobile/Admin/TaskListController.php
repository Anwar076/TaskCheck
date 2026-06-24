<?php

namespace App\Http\Controllers\Api\Mobile\Admin;

use App\Http\Controllers\Api\Mobile\MobileController;
use App\Models\Checklist\ListAssignment;
use App\Models\Communication\Notification;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\User;
use App\Services\Mobile\MobileSerializer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TaskListController extends MobileController
{
    public function index(Request $request)
    {
        $companyId = $request->user()->company_id;

        $query = TaskList::query()
            ->with(['location'])
            ->where('company_id', $companyId)
            ->where('is_template', false);

        if ($request->filled('search')) {
            $search = '%'.$request->get('search').'%';
            $query->where('title', 'like', $search);
        }

        $lists = $query->orderByDesc('updated_at')->get()->map(fn ($list) => [
            'id' => $list->id,
            'title' => $list->title,
            'description' => $list->description,
            'priority' => $list->priority,
            'category' => $list->category,
            'is_active' => (bool) $list->is_active,
            'location' => $list->location ? ['id' => $list->location->id, 'name' => $list->location->name] : null,
            'task_count' => $list->tasks()->count(),
        ])->values();

        return $this->success($lists);
    }

    public function show(Request $request, int $id)
    {
        $list = $this->findCompanyList($request, $id);

        return $this->success(MobileSerializer::adminTaskListDetail($list));
    }

    public function store(Request $request)
    {
        $companyId = $request->user()->company_id;

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'schedule_type' => ['required', 'in:once,daily,weekly,monthly,custom'],
            'due_date' => ['nullable', 'date'],
            'requires_signature' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'schedule_config' => ['nullable', 'array'],
            'selected_days' => ['nullable', 'array'],
            'location_id' => [
                'nullable',
                Rule::exists('locations', 'id')->where(fn ($q) => $q->where('company_id', $companyId)),
            ],
        ]);

        $validated['created_by'] = $request->user()->id;
        $validated['company_id'] = $companyId;
        $validated['is_active'] = $validated['is_active'] ?? true;
        $validated['requires_signature'] = $validated['requires_signature'] ?? false;

        if (in_array($validated['schedule_type'], ['daily', 'weekly', 'custom'], true)) {
            $scheduleConfig = $validated['schedule_config'] ?? [];
            if ($validated['schedule_type'] === 'daily') {
                $scheduleConfig['show_on_days'] = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            } elseif ($validated['schedule_type'] === 'weekly' && !empty($validated['selected_days'])) {
                $scheduleConfig['show_on_days'] = $validated['selected_days'];
            }
            $validated['schedule_config'] = $scheduleConfig;
        }
        unset($validated['selected_days']);

        $list = TaskList::create($validated);

        return $this->success(MobileSerializer::adminTaskListDetail($list), 'Takenlijst aangemaakt.', 201);
    }

    public function update(Request $request, int $id)
    {
        $list = $this->findCompanyList($request, $id);
        $companyId = $request->user()->company_id;

        $validated = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'priority' => ['sometimes', 'in:low,medium,high,urgent'],
            'schedule_type' => ['sometimes', 'in:once,daily,weekly,monthly,custom'],
            'due_date' => ['nullable', 'date'],
            'requires_signature' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'schedule_config' => ['nullable', 'array'],
            'selected_days' => ['nullable', 'array'],
            'location_id' => [
                'nullable',
                Rule::exists('locations', 'id')->where(fn ($q) => $q->where('company_id', $companyId)),
            ],
        ]);

        if (isset($validated['selected_days'])) {
            $scheduleConfig = $validated['schedule_config'] ?? (is_array($list->schedule_config) ? $list->schedule_config : []);
            $scheduleConfig['show_on_days'] = $validated['selected_days'];
            $validated['schedule_config'] = $scheduleConfig;
            unset($validated['selected_days']);
        }

        $list->update($validated);

        return $this->success(MobileSerializer::adminTaskListDetail($list->fresh(['location', 'assignments.user', 'tasks'])), 'Takenlijst bijgewerkt.');
    }

    public function assign(Request $request, int $id)
    {
        $list = $this->findCompanyList($request, $id);
        $companyId = $request->user()->company_id;

        $rules = [
            'assignment_type' => ['required', 'in:user,department,role'],
            'assigned_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:assigned_date'],
        ];

        if ($request->input('assignment_type') === 'user') {
            $rules['user_ids'] = ['required', 'array', 'min:1'];
            $rules['user_ids.*'] = ['integer'];
            $rules['user_id'] = ['sometimes', 'integer'];
        } elseif ($request->input('assignment_type') === 'department') {
            $rules['department'] = ['required', 'string', 'max:100'];
        } elseif ($request->input('assignment_type') === 'role') {
            $rules['role'] = ['required', 'in:admin,employee'];
        }

        $validated = $request->validate($rules);

        $payload = [
            'list_id' => $list->id,
            'assigned_date' => $validated['assigned_date'],
            'due_date' => $validated['due_date'] ?? null,
            'is_active' => true,
            'user_id' => null,
            'department' => null,
            'role' => null,
        ];

        if ($validated['assignment_type'] === 'user') {
            $userIds = array_values(array_unique(array_map(
                'intval',
                $validated['user_ids'] ?? (isset($validated['user_id']) ? [$validated['user_id']] : [])
            )));

            foreach ($userIds as $userId) {
                $selectedUser = User::query()
                    ->where('id', $userId)
                    ->where('company_id', $companyId)
                    ->whereIn('role', ['employee', 'admin'])
                    ->where('is_active', true)
                    ->first();

                if (!$selectedUser) {
                    throw ValidationException::withMessages(['user_ids' => 'Ongeldige medewerker geselecteerd.']);
                }

                $exists = ListAssignment::where('list_id', $list->id)
                    ->where('user_id', $selectedUser->id)
                    ->where('is_active', true)
                    ->exists();

                if (!$exists) {
                    ListAssignment::create(array_merge($payload, ['user_id' => $selectedUser->id]));
                    if ($selectedUser->isEmployee()) {
                        Notification::createListAssigned($selectedUser->id, $list->id, $list->title, 'user');
                    }
                }
            }
        } elseif ($validated['assignment_type'] === 'department') {
            $payload['department'] = $validated['department'];
            ListAssignment::create($payload);
        } else {
            $payload['role'] = $validated['role'];
            ListAssignment::create($payload);
        }

        return $this->success(MobileSerializer::adminTaskListDetail($list->fresh(['location', 'assignments.user', 'tasks'])), 'Toewijzing opgeslagen.');
    }

    protected function findCompanyList(Request $request, int $id): TaskList
    {
        return TaskList::query()
            ->with(['location', 'assignments.user', 'tasks'])
            ->where('company_id', $request->user()->company_id)
            ->findOrFail($id);
    }
}

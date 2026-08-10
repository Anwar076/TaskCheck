<?php

namespace Tests\Feature;

use App\Models\Organisation\User;
use App\Models\Checklist\TaskList;
use App\Models\Checklist\Task;
use App\Models\Checklist\ListAssignment;
use App\Models\Submissions\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TaskListTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(); // Seed the database with test data
    }

    public function test_admin_can_create_task_list(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->post('/admin/lists', [
            'title' => 'Test Cleaning List',
            'description' => 'A test cleaning checklist',
            'priority' => 'medium',
            'schedule_type' => 'daily',
            'category' => 'Cleaning',
            'is_active' => true,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('lists', [
            'title' => 'Test Cleaning List',
            'created_by' => $admin->id,
        ]);
    }

    public function test_ai_import_generates_one_bounded_request_and_preserves_file_name(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $admin->company->update([
            'subscription_plan' => 'professional',
            'onboarding_completed_at' => now(),
        ]);

        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [[
                    'message' => [
                        'content' => json_encode(['lists' => [[
                            'title' => 'Door AI gewijzigde titel',
                            'description' => 'Eerste lijst',
                            'category' => 'Test',
                            'priority' => 'medium',
                            'schedule_type' => 'once',
                            'tasks' => [['title' => 'Openen', 'description' => 'De zaak openen']],
                        ]]]),
                    ],
                ]],
                'usage' => ['prompt_tokens' => 10, 'completion_tokens' => 10, 'total_tokens' => 20],
            ], 200),
        ]);

        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
        $response = $this->actingAs($admin)->postJson(route('admin.lists.ai-import.generate'), [
            'source_files' => [
                UploadedFile::fake()->createWithContent('Openingslijst 2026.png', $png),
            ],
        ]);

        $response->assertOk()
            ->assertJsonPath('data.lists.0.title', 'Openingslijst 2026')
            ->assertJsonPath('data.lists.0.schedule_type', 'daily');

        Http::assertSent(function ($request) {
            $content = json_encode($request['messages'][1]['content']);

            return str_contains($content, 'Openingslijst 2026')
                && $request['max_tokens'] === 8000;
        });
    }

    public function test_ai_import_endpoint_rejects_multiple_files_in_one_ai_request(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $admin->company->update(['subscription_plan' => 'professional', 'onboarding_completed_at' => now()]);
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');

        $this->actingAs($admin)->postJson(route('admin.lists.ai-import.generate'), [
            'source_files' => [
                UploadedFile::fake()->createWithContent('Een.png', $png),
                UploadedFile::fake()->createWithContent('Twee.png', $png),
            ],
        ])->assertUnprocessable()->assertJsonValidationErrors('source_files');
    }

    public function test_employee_can_view_assigned_lists(): void
    {
        $employee = User::where('role', 'employee')->first();

        $response = $this->actingAs($employee)->get('/employee/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('employee.dashboard');
    }

    public function test_employee_can_start_submission(): void
    {
        $employee = User::where('role', 'employee')->first();
        $list = TaskList::first();

        // Ensure the employee has access to this list
        ListAssignment::create([
            'list_id' => $list->id,
            'user_id' => $employee->id,
            'assigned_date' => today(),
        ]);

        $response = $this->actingAs($employee)->post("/employee/lists/{$list->id}/start");

        $response->assertRedirect();
        $this->assertDatabaseHas('submissions', [
            'user_id' => $employee->id,
            'list_id' => $list->id,
            'status' => 'in_progress',
        ]);
    }

    public function test_employee_can_complete_task(): void
    {
        $employee = User::where('role', 'employee')->first();
        $list = TaskList::first();
        $task = $list->tasks->first();

        // Create a submission
        $submission = Submission::create([
            'user_id' => $employee->id,
            'list_id' => $list->id,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        // Create submission task
        $submissionTask = $submission->submissionTasks()->create([
            'task_id' => $task->id,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($employee)->post("/employee/submissions/{$submission->id}/tasks/{$task->id}", [
            'proof_text' => 'Task completed successfully',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('submission_tasks', [
            'id' => $submissionTask->id,
            'status' => 'completed',
            'proof_text' => 'Task completed successfully',
        ]);
    }

    public function test_admin_can_review_submission(): void
    {
        $admin = User::where('role', 'admin')->first();
        $employee = User::where('role', 'employee')->first();
        $list = TaskList::first();

        // Create completed submission
        $submission = Submission::create([
            'user_id' => $employee->id,
            'list_id' => $list->id,
            'started_at' => now(),
            'completed_at' => now(),
            'status' => 'completed',
        ]);

        $task = $list->tasks->first();
        $submissionTask = $submission->submissionTasks()->create([
            'task_id' => $task->id,
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post("/admin/submissions/{$submission->id}/review", [
            'task_reviews' => [
                $task->id => [
                    'status' => 'approved',
                    'comment' => 'Well done!',
                ]
            ]
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('submission_tasks', [
            'id' => $submissionTask->id,
            'status' => 'approved',
            'manager_comment' => 'Well done!',
            'reviewed_by' => $admin->id,
        ]);
    }

    public function test_admin_can_approve_all_completed_tasks_at_once(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $employee = User::where('role', 'employee')->firstOrFail();
        $list = TaskList::where('company_id', $admin->company_id)->firstOrFail();
        $list->update(['requires_review' => true]);

        $tasks = $list->tasks()->take(2)->get();
        if ($tasks->count() < 2) {
            $tasks->push($list->tasks()->create([
                'title' => 'Tweede bulktaak',
                'description' => 'Testtaak voor bulkgoedkeuring',
                'is_required' => true,
                'order' => 999,
            ]));
        }

        $submission = Submission::create([
            'company_id' => $admin->company_id,
            'user_id' => $employee->id,
            'list_id' => $list->id,
            'started_at' => now(),
            'completed_at' => now(),
            'status' => 'completed',
        ]);

        foreach ($tasks->take(2) as $task) {
            $submission->submissionTasks()->create([
                'task_id' => $task->id,
                'status' => 'completed',
                'completed_at' => now(),
            ]);
        }

        $response = $this->actingAs($admin)
            ->post(route('admin.submissions.approve-all', $submission));

        $response->assertRedirect();
        $this->assertSame(2, $submission->submissionTasks()->where('status', 'approved')->count());
        $this->assertSame('reviewed', $submission->fresh()->status);
        $this->assertSame(2, $submission->submissionTasks()->where('reviewed_by', $admin->id)->count());
    }

    public function test_bulk_approval_leaves_corrective_actions_for_manual_verification(): void
    {
        $admin = User::where('role', 'admin')->firstOrFail();
        $employee = User::where('role', 'employee')->firstOrFail();
        $list = TaskList::where('company_id', $admin->company_id)->firstOrFail();
        $list->update(['requires_review' => true]);
        $tasks = $list->tasks()->take(2)->get();

        if ($tasks->count() < 2) {
            $tasks->push($list->tasks()->create([
                'title' => 'Taak met corrigerende actie',
                'is_required' => true,
                'order' => 999,
            ]));
        }

        $submission = Submission::create([
            'company_id' => $admin->company_id,
            'user_id' => $employee->id,
            'list_id' => $list->id,
            'started_at' => now(),
            'completed_at' => now(),
            'status' => 'completed',
        ]);

        $submission->submissionTasks()->create([
            'task_id' => $tasks[0]->id,
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        $manualTask = $submission->submissionTasks()->create([
            'task_id' => $tasks[1]->id,
            'status' => 'completed',
            'completed_at' => now(),
            'corrective_action' => 'Controleer eerst aantoonbaar of de oplossing werkt.',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.submissions.approve-all', $submission))
            ->assertRedirect();

        $this->assertSame('approved', $submission->submissionTasks()->where('task_id', $tasks[0]->id)->value('status'));
        $this->assertSame('completed', $manualTask->fresh()->status);
        $this->assertSame('completed', $submission->fresh()->status);
    }

    public function test_personal_assignment_is_visible_even_when_schedule_blocks_other_users(): void
    {
        $employees = User::where('role', 'employee')->take(2)->get();
        $this->assertCount(2, $employees, 'Need at least two employees in seed data.');

        [$assignedEmployee, $otherEmployee] = $employees;

        $list = TaskList::create([
            'title' => 'Weekly visibility test',
            'description' => 'Should only show for assigned employee',
            'created_by' => $assignedEmployee->id,
            'company_id' => $assignedEmployee->company_id,
            'schedule_type' => 'weekly',
            'schedule_config' => ['show_on_days' => ['monday']],
            'priority' => 'medium',
            'is_active' => true,
        ]);

        ListAssignment::create([
            'list_id' => $list->id,
            'user_id' => $assignedEmployee->id,
            'assigned_date' => today(),
            'is_active' => true,
        ]);

        $scheduleService = app(\App\Services\ScheduleService::class);

        $assignedLists = $scheduleService->getScheduledTasksForUser($assignedEmployee);
        $otherLists = $scheduleService->getScheduledTasksForUser($otherEmployee);

        $this->assertTrue($assignedLists->contains('id', $list->id));
        $this->assertFalse($otherLists->contains('id', $list->id));
    }

    public function test_user_assignment_is_not_visible_to_other_employees(): void
    {
        $employees = User::where('role', 'employee')->take(2)->get();
        $this->assertCount(2, $employees, 'Need at least two employees in seed data.');

        [$assignedEmployee, $otherEmployee] = $employees;

        $list = TaskList::create([
            'title' => 'Assignment visibility test',
            'description' => 'Only assigned employee should see this list',
            'created_by' => $assignedEmployee->id,
            'company_id' => $assignedEmployee->company_id,
            'schedule_type' => 'daily',
            'priority' => 'medium',
            'is_active' => true,
        ]);

        ListAssignment::create([
            'list_id' => $list->id,
            'user_id' => $assignedEmployee->id,
            'department' => null,
            'role' => null,
            'assigned_date' => today(),
            'is_active' => true,
        ]);

        $scheduleService = app(\App\Services\ScheduleService::class);

        $assignedLists = $scheduleService->getScheduledTasksForUser($assignedEmployee);
        $otherLists = $scheduleService->getScheduledTasksForUser($otherEmployee);

        $this->assertTrue($assignedLists->contains('id', $list->id));
        $this->assertFalse($otherLists->contains('id', $list->id));
    }

    public function test_api_returns_assigned_lists(): void
    {
        $employee = User::where('role', 'employee')->first();
        $token = $employee->createToken('test')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/lists');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'description',
                    'priority',
                    'category',
                    'tasks_count',
                ]
            ]
        ]);
    }

    public function test_unauthorized_user_cannot_access_admin_routes(): void
    {
        $employee = User::where('role', 'employee')->first();

        $response = $this->actingAs($employee)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_unauthorized_user_cannot_access_employee_routes(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/employee/dashboard');

        $response->assertStatus(403);
    }
}

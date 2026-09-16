<?php

namespace Tests\Feature;

use App\Models\Checklist\Task;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DossierTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_dossier_and_see_filled_and_missing_lists(): void
    {
        [$admin, $filledList, $missingList] = $this->setupCompany();
        $employee = User::factory()->create([
            'company_id' => $admin->company_id,
            'role' => 'employee',
        ]);
        $submission = Submission::query()->create([
            'company_id' => $admin->company_id,
            'user_id' => $employee->id,
            'list_id' => $filledList->id,
            'status' => 'completed',
        ]);
        $task = Task::query()->create(['list_id' => $filledList->id, 'title' => 'Koelkast meten']);
        SubmissionTask::query()->create([
            'submission_id' => $submission->id,
            'task_id' => $task->id,
            'status' => 'completed',
            'proof_text' => '4 °C',
            'employee_comment' => 'Alles ok',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.weekly-overview'))
            ->assertOk()
            ->assertSee('Dossier');

        $this->actingAs($admin)
            ->get(route('admin.reports.dossier', ['date' => now()->toDateString()]))
            ->assertOk()
            ->assertSee('Opening keuken')
            ->assertSee('Sluitronde')
            ->assertSee('Ingevuld')
            ->assertSee('Niet ingevuld')
            ->assertSee('4 °C')
            ->assertSee('Alles ok');
    }

    public function test_task_history_and_pdf_export(): void
    {
        [$admin, $list] = $this->setupCompany();
        $employee = User::factory()->create([
            'company_id' => $admin->company_id,
            'role' => 'employee',
        ]);
        $task = Task::query()->create(['list_id' => $list->id, 'title' => 'Vloer dweilen']);
        $submission = Submission::query()->create([
            'company_id' => $admin->company_id,
            'user_id' => $employee->id,
            'list_id' => $list->id,
            'status' => 'completed',
        ]);
        SubmissionTask::query()->create([
            'submission_id' => $submission->id,
            'task_id' => $task->id,
            'status' => 'completed',
            'employee_comment' => 'Nat nagelopen',
        ]);

        $query = [
            'mode' => 'task',
            'list_id' => $list->id,
            'task_id' => $task->id,
            'start_date' => now()->subDays(2)->toDateString(),
            'end_date' => now()->toDateString(),
        ];

        $this->actingAs($admin)
            ->get(route('admin.reports.dossier', $query))
            ->assertOk()
            ->assertSee('Vloer dweilen')
            ->assertSee('Nat nagelopen');

        $this->actingAs($admin)
            ->get(route('admin.reports.dossier.pdf', $query))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');

        $this->actingAs($admin)
            ->get(route('admin.reports.dossier.pdf', ['mode' => 'day', 'date' => now()->toDateString()]))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    private function setupCompany(): array
    {
        $company = Company::query()->create([
            'name' => 'Dossier test',
            'subscription_status' => 'active',
            'subscription_plan' => 'professional',
            'subscription_ends_at' => now()->addMonth(),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'address' => 'Teststraat 1',
            'phone' => '0101234567',
            'email' => 'dossier@example.test',
        ]);
        $admin = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $filledList = TaskList::query()->create([
            'title' => 'Opening keuken',
            'created_by' => $admin->id,
            'company_id' => $company->id,
            'schedule_type' => 'daily',
            'is_active' => true,
        ]);
        $missingList = TaskList::query()->create([
            'title' => 'Sluitronde',
            'created_by' => $admin->id,
            'company_id' => $company->id,
            'schedule_type' => 'daily',
            'is_active' => true,
        ]);

        return [$admin, $filledList, $missingList];
    }
}

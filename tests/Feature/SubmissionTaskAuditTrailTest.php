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

class SubmissionTaskAuditTrailTest extends TestCase
{
    use RefreshDatabase;

    public function test_review_cycles_are_kept_as_immutable_events(): void
    {
        $company = Company::query()->create([
            'name' => 'Auditbedrijf',
            'subscription_status' => 'active',
            'subscription_plan' => 'professional',
            'subscription_ends_at' => now()->addMonth(),
            'is_active' => true,
        ]);
        $admin = User::factory()->create(['company_id' => $company->id, 'role' => 'admin']);
        $employee = User::factory()->create(['company_id' => $company->id, 'role' => 'employee']);
        $list = TaskList::query()->create([
            'title' => 'Temperatuurcontrole',
            'created_by' => $admin->id,
            'company_id' => $company->id,
        ]);
        $task = Task::query()->create([
            'list_id' => $list->id,
            'title' => 'Meet koelcel',
            'created_by' => $admin->id,
        ]);
        $submission = Submission::query()->create([
            'user_id' => $employee->id,
            'list_id' => $list->id,
            'company_id' => $company->id,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        $this->actingAs($employee);
        $submissionTask = SubmissionTask::query()->create([
            'submission_id' => $submission->id,
            'task_id' => $task->id,
            'status' => 'pending',
        ]);
        $submissionTask->update([
            'status' => 'completed',
            'proof_text' => 'Eerste meting: 9 °C',
            'completed_at' => now(),
            'completed_by_user_id' => $employee->id,
        ]);

        $this->actingAs($admin);
        $submissionTask->update([
            'status' => 'pending',
            'rejection_reason' => 'Temperatuur boven de norm',
            'corrective_action' => 'Koeling bijstellen en opnieuw meten',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
        ]);

        $this->actingAs($employee);
        $submissionTask->update([
            'status' => 'completed',
            'proof_text' => 'Nieuwe meting: 4 °C',
            'completed_at' => now()->addMinute(),
            'completed_by_user_id' => $employee->id,
        ]);

        $this->actingAs($admin);
        $submissionTask->update([
            'status' => 'approved',
            'manager_comment' => 'Herstel gecontroleerd en akkoord',
            'reviewed_by' => $admin->id,
            'reviewed_at' => now()->addMinutes(2),
        ]);

        $events = $submissionTask->auditEvents()->get();

        $this->assertSame(
            ['created', 'submitted', 'rejected', 'resubmitted', 'approved'],
            $events->pluck('event_type')->all()
        );
        $this->assertSame('Temperatuur boven de norm', $events->firstWhere('event_type', 'rejected')->snapshot['rejection_reason']);
        $this->assertSame('Eerste meting: 9 °C', $events->firstWhere('event_type', 'rejected')->snapshot['proof_text']);
        $this->assertSame('Nieuwe meting: 4 °C', $events->firstWhere('event_type', 'resubmitted')->snapshot['proof_text']);
        $this->assertSame($admin->id, $events->firstWhere('event_type', 'approved')->actor_id);
    }
}

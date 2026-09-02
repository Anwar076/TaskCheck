<?php

namespace Tests\Feature;

use App\Models\Checklist\TaskList;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SubmissionApiTenantScopeTest extends TestCase
{
    use RefreshDatabase;

    public function test_submission_index_only_returns_submissions_for_authenticated_company(): void
    {
        $company = $this->createCompany('Acme');
        $otherCompany = $this->createCompany('Other');

        $admin = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'admin',
            'is_active' => true,
        ]);
        $employee = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'employee',
            'is_active' => true,
        ]);
        $otherEmployee = User::factory()->create([
            'company_id' => $otherCompany->id,
            'role' => 'employee',
            'is_active' => true,
        ]);

        $list = TaskList::query()->create([
            'title' => 'Eigen lijst',
            'created_by' => $admin->id,
            'company_id' => $company->id,
        ]);
        $otherList = TaskList::query()->create([
            'title' => 'Andere lijst',
            'created_by' => $otherEmployee->id,
            'company_id' => $otherCompany->id,
        ]);

        $ownSubmission = Submission::query()->create([
            'user_id' => $employee->id,
            'list_id' => $list->id,
            'company_id' => $company->id,
            'status' => 'completed',
        ]);
        $otherSubmission = Submission::query()->create([
            'user_id' => $otherEmployee->id,
            'list_id' => $otherList->id,
            'company_id' => $otherCompany->id,
            'status' => 'completed',
        ]);

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/submissions');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('total', 1)
            ->assertJsonPath('meta.completed_count', 1)
            ->assertJsonPath('meta.to_review_count', 1);

        $this->assertSame($ownSubmission->id, $response->json('data.0.id'));
        $this->assertNotContains($otherSubmission->id, collect($response->json('data'))->pluck('id'));
    }

    public function test_submission_store_rejects_users_and_lists_from_another_company(): void
    {
        $company = $this->createCompany('Acme');
        $otherCompany = $this->createCompany('Other');

        $admin = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'admin',
            'is_active' => true,
        ]);
        $otherEmployee = User::factory()->create([
            'company_id' => $otherCompany->id,
            'role' => 'employee',
            'is_active' => true,
        ]);
        $otherList = TaskList::query()->create([
            'title' => 'Andere lijst',
            'created_by' => $otherEmployee->id,
            'company_id' => $otherCompany->id,
        ]);

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/submissions', [
            'user_id' => $otherEmployee->id,
            'list_id' => $otherList->id,
            'status' => 'in_progress',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['user_id', 'list_id']);
    }

    public function test_work_controls_only_show_lists_that_require_review_in_status_and_oldest_first_order(): void
    {
        $company = $this->createCompany('Acme');
        $admin = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'admin',
            'is_active' => true,
        ]);
        $employee = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'employee',
            'is_active' => true,
        ]);

        $reviewList = TaskList::query()->create([
            'title' => 'Met controle',
            'created_by' => $admin->id,
            'company_id' => $company->id,
            'requires_review' => true,
        ]);
        $regularList = TaskList::query()->create([
            'title' => 'Zonder controle',
            'created_by' => $admin->id,
            'company_id' => $company->id,
            'requires_review' => false,
        ]);

        $reviewed = $this->createSubmission($employee, $reviewList, 'reviewed', now()->subDays(4));
        $inProgress = $this->createSubmission($employee, $reviewList, 'in_progress', now()->subDays(3));
        $newerCompleted = $this->createSubmission($employee, $reviewList, 'completed', now()->subDay());
        $olderCompleted = $this->createSubmission($employee, $reviewList, 'completed', now()->subDays(2));
        $excluded = $this->createSubmission($employee, $regularList, 'completed', now()->subDays(5));

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/submissions');

        $response->assertOk()
            ->assertJsonPath('total', 4)
            ->assertJsonPath('meta.to_review_count', 2);

        $this->assertSame(
            [$olderCompleted->id, $newerCompleted->id, $inProgress->id, $reviewed->id],
            collect($response->json('data'))->pluck('id')->all()
        );
        $this->assertSame(100, collect($response->json('data'))->firstWhere('id', $reviewed->id)['progress_percentage']);
        $this->assertNotContains($excluded->id, collect($response->json('data'))->pluck('id'));
    }

    private function createSubmission(User $user, TaskList $list, string $status, $createdAt): Submission
    {
        $submission = Submission::query()->create([
            'user_id' => $user->id,
            'list_id' => $list->id,
            'company_id' => $user->company_id,
            'status' => $status,
        ]);

        $submission->timestamps = false;
        $submission->forceFill([
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ])->save();

        return $submission;
    }

    private function createCompany(string $name): Company
    {
        return Company::query()->create([
            'name' => $name,
            'subscription_status' => 'active',
            'subscription_ends_at' => now()->addMonth(),
            'is_active' => true,
        ]);
    }
}

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

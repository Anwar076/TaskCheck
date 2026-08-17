<?php

namespace Tests\Feature;

use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class IdentityManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_scim_requires_the_correct_tenant_token(): void
    {
        $company = $this->company(['scim_endpoint_key' => Str::uuid(), 'scim_token_hash' => hash('sha256', 'correct')]);
        $this->getJson("/api/scim/v2/{$company->scim_endpoint_key}/Users")->assertUnauthorized();
        $this->withToken('wrong')->getJson("/api/scim/v2/{$company->scim_endpoint_key}/Users")->assertUnauthorized();
        $this->withToken('correct')->getJson("/api/scim/v2/{$company->scim_endpoint_key}/Users")->assertOk()->assertJsonPath('totalResults', 0);
    }

    public function test_scim_provisions_filters_and_deprovisions_a_user(): void
    {
        $company = $this->company(['scim_endpoint_key' => Str::uuid(), 'scim_token_hash' => hash('sha256', 'secret')]);
        $base = "/api/scim/v2/{$company->scim_endpoint_key}/Users";
        $created = $this->withToken('secret')->postJson($base, ['schemas' => ['urn:ietf:params:scim:schemas:core:2.0:User'],
            'externalId' => 'entra-123', 'userName' => 'person@example.com', 'displayName' => 'Test Persoon', 'active' => true])
            ->assertCreated()->assertJsonPath('userName', 'person@example.com')->json();
        $this->withToken('secret')->getJson($base.'?filter='.urlencode('userName eq "person@example.com"'))
            ->assertOk()->assertJsonPath('totalResults', 1);
        $this->withToken('secret')->patchJson($base.'/'.$created['id'], ['schemas' => ['urn:ietf:params:scim:api:messages:2.0:PatchOp'],
            'Operations' => [['op' => 'Replace', 'path' => 'active', 'value' => false]]])->assertOk()->assertJsonPath('active', false);
        $this->assertDatabaseHas('users', ['id' => $created['id'], 'company_id' => $company->id, 'is_active' => false]);
    }

    public function test_required_sso_blocks_local_password_login(): void
    {
        $company = $this->company(['entra_enabled' => true, 'entra_sso_required' => true]);
        $user = User::factory()->create(['company_id' => $company->id, 'password' => bcrypt('Password123!'), 'is_active' => true]);
        $this->post('/login', ['email' => $user->email, 'password' => 'Password123!'])->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_security_headers_are_added(): void
    {
        $this->get('/')->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('X-Frame-Options', 'DENY');
    }

    private function company(array $attributes = []): Company
    {
        $attributes = array_merge([
            'name' => 'Identity Test', 'subscription_status' => 'active', 'is_active' => true,
        ], $attributes);
        $company = new Company();
        $company->forceFill($attributes)->save();
        return $company;
    }
}

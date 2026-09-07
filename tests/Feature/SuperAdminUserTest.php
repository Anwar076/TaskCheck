<?php

namespace Tests\Feature;

use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SuperAdminUserTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $target;

    protected function setUp(): void
    {
        parent::setUp();
        $platform = Company::create(['name' => 'Platform']);
        $customer = Company::create(['name' => 'Klant']);
        $this->admin = User::factory()->create(['company_id' => $platform->id, 'role' => 'admin']);
        $this->target = User::factory()->create(['company_id' => $customer->id, 'role' => 'employee']);
        config()->set('app.super_admin_emails', [$this->admin->email]);
    }

    public function test_user_details_and_edit_dialog_are_available_to_superadmins(): void
    {
        $this->actingAs($this->admin)->get(route('super-admin.users.show', ['user' => $this->target, 'edit' => 1]))
            ->assertOk()->assertViewIs('super-admin.users.show')
            ->assertSee($this->target->email)
            ->assertSee('edit-company-user-'.$this->target->id)
            ->assertSee(route('super-admin.users.destroy', $this->target), false);
    }

    public function test_overviews_link_rows_to_details_and_offer_user_actions(): void
    {
        $this->actingAs($this->admin)->get(route('super-admin.dashboard', ['tab' => 'users']))
            ->assertOk()
            ->assertSee('data-record-url="'.route('super-admin.users.show', $this->target).'"', false)
            ->assertSee('data-record-url="'.route('super-admin.companies.show', $this->target->company).'"', false)
            ->assertSee($this->target->name.' verwijderen')
            ->assertSee($this->target->name.' bewerken');
    }

    public function test_editing_from_user_details_returns_to_the_user(): void
    {
        $this->actingAs($this->admin)->put(route('super-admin.companies.users.update', [$this->target->company, $this->target]), [
            'name' => 'Bijgewerkte gebruiker',
            'email' => $this->target->email,
            'role' => 'employee',
            'return_to_user_detail' => 1,
        ])->assertRedirect(route('super-admin.users.show', $this->target));
        $this->assertDatabaseHas('users', ['id' => $this->target->id, 'name' => 'Bijgewerkte gebruiker']);
    }

    public function test_user_deletion_revokes_tokens_and_keeps_other_accounts(): void
    {
        $tokenId = $this->target->createToken('test')->accessToken->id;
        $this->actingAs($this->admin)->delete(route('super-admin.users.destroy', $this->target), ['confirmation_name' => $this->target->name])
            ->assertRedirect(route('super-admin.dashboard', ['tab' => 'users']));
        $this->assertDatabaseMissing('users', ['id' => $this->target->id]);
        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $tokenId]);
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
        $this->assertDatabaseHas('companies', ['id' => $this->target->company_id]);
    }

    public function test_user_deletion_requires_the_exact_name(): void
    {
        foreach (['wrong', ''] as $name) {
            $this->actingAs($this->admin)->delete(route('super-admin.users.destroy', $this->target), ['confirmation_name' => $name])
                ->assertSessionHasErrors('confirmation_name');
        }
        $this->assertDatabaseHas('users', ['id' => $this->target->id]);
    }

    public function test_superadmin_cannot_delete_their_own_account(): void
    {
        $this->actingAs($this->admin)->delete(route('super-admin.users.destroy', $this->admin), ['confirmation_name' => $this->admin->name])
            ->assertStatus(422);
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_regular_admin_cannot_view_or_delete_users_through_superadmin(): void
    {
        $this->target->update(['role' => 'admin']);
        $this->actingAs($this->target)->get(route('super-admin.users.show', $this->admin))->assertForbidden();
        $this->delete(route('super-admin.users.destroy', $this->admin), ['confirmation_name' => $this->admin->name])->assertForbidden();
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    public function test_deletion_transfers_lists_within_the_company_and_removes_submissions(): void
    {
        $replacement = User::factory()->create(['company_id' => $this->target->company_id, 'role' => 'admin']);
        $listId = DB::table('lists')->insertGetId([
            'company_id' => $this->target->company_id, 'title' => 'Bewaren', 'created_by' => $this->target->id,
        ]);
        $submissionId = DB::table('submissions')->insertGetId([
            'company_id' => $this->target->company_id, 'list_id' => $listId, 'user_id' => $this->target->id,
        ]);
        $this->actingAs($this->admin)->delete(route('super-admin.users.destroy', $this->target), ['confirmation_name' => $this->target->name])
            ->assertSessionHasNoErrors()->assertRedirect();
        $this->assertDatabaseHas('lists', ['id' => $listId, 'created_by' => $replacement->id]);
        $this->assertDatabaseMissing('submissions', ['id' => $submissionId]);
        $this->assertDatabaseMissing('users', ['id' => $this->target->id]);
    }

    public function test_last_list_owner_is_not_deleted_without_a_replacement(): void
    {
        DB::table('lists')->insert(['company_id' => $this->target->company_id, 'title' => 'Bewaren', 'created_by' => $this->target->id]);
        $this->actingAs($this->admin)->delete(route('super-admin.users.destroy', $this->target), ['confirmation_name' => $this->target->name])
            ->assertSessionHasErrors('user');
        $this->assertDatabaseHas('users', ['id' => $this->target->id]);
    }
}

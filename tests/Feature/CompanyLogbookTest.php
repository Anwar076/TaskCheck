<?php

namespace Tests\Feature;

use App\Models\Organisation\Company;
use App\Models\Organisation\Location;
use App\Models\Organisation\User;
use App\Models\Platform\CompanyLogEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CompanyLogbookTest extends TestCase
{
    use RefreshDatabase;

    private Company $company;

    private Company $otherCompany;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->company = Company::create(['name' => 'Klant A', 'email' => 'old@example.com']);
        $this->otherCompany = Company::create(['name' => 'Klant B']);
        $this->admin = User::factory()->create(['company_id' => $this->otherCompany->id, 'role' => 'admin']);
        config()->set('app.super_admin_emails', [$this->admin->email]);
        CompanyLogEntry::query()->delete();
    }

    private function logUrl(array $query = []): string
    {
        return route('super-admin.companies.show', array_merge(['company' => $this->company, 'section' => 'logbook'], $query));
    }

    public function test_company_changes_record_the_actor_and_before_after_values(): void
    {
        $this->actingAs($this->admin);
        $this->company->update(['name' => 'Nieuwe klantnaam', 'email' => 'new@example.com']);
        $entry = CompanyLogEntry::sole();
        $this->assertSame($this->company->id, $entry->company_id);
        $this->assertSame($this->admin->id, $entry->actor_id);
        $this->assertSame('Klant A', $entry->changes['name']['before']);
        $this->assertSame('Nieuwe klantnaam', $entry->changes['name']['after']);
        $this->assertStringContainsString('old@example.com → new@example.com', $entry->body);
    }

    public function test_new_people_changes_and_deletions_remain_in_the_company_history(): void
    {
        $this->actingAs($this->admin);
        $user = User::factory()->create(['company_id' => $this->company->id, 'role' => 'employee', 'name' => 'Nieuwe medewerker']);
        $user->update(['role' => 'admin']);
        $user->delete();
        $entries = CompanyLogEntry::where('company_id', $this->company->id)->orderBy('id')->get();
        $this->assertSame(['created', 'updated', 'deleted'], $entries->pluck('event')->all());
        $this->assertSame('Medewerker', $entries[1]->changes['role']['before']);
        $this->assertSame('Beheerder', $entries[1]->changes['role']['after']);
        $this->assertSame('Gebruiker verwijderd: Nieuwe medewerker', $entries[2]->title);
    }

    public function test_locations_and_subscription_changes_are_logged(): void
    {
        $location = Location::create(['company_id' => $this->company->id, 'name' => 'Vestiging A']);
        $location->update(['name' => 'Vestiging B']);
        $this->company->update(['subscription_plan' => 'professional']);
        $this->assertDatabaseHas('company_log_entries', ['category' => 'location', 'title' => 'Locatie gewijzigd: Vestiging B']);
        $this->assertNotNull(CompanyLogEntry::where('category', 'company')->first()->changes['subscription_plan']);
    }

    public function test_credentials_and_routine_updates_are_not_logged(): void
    {
        $user = User::factory()->create(['company_id' => $this->company->id]);
        CompanyLogEntry::query()->delete();
        $user->update(['password' => 'private-password-123', 'remember_token' => 'private-token', 'last_sso_at' => now()]);
        $this->company->update(['entra_client_secret' => 'private-secret', 'scim_endpoint_key' => 'private-scim-token']);
        $user->touch();
        $this->assertDatabaseCount('company_log_entries', 0);
        $user->update(['name' => 'Gewijzigd', 'password' => 'another-private-password']);
        $entry = CompanyLogEntry::sole();
        $this->assertSame(['name'], array_keys($entry->changes));
        $this->assertStringNotContainsString('private', $entry->toJson());
    }

    public function test_email_paste_keeps_newlines_and_is_displayed_as_escaped_text(): void
    {
        $body = "Van: klant@example.com\nOnderwerp: afspraak\n\n<script>alert('x')</script>\nAkkoord met de offerte.";
        $this->actingAs($this->admin)->post(route('super-admin.companies.logbook.store', $this->company), [
            'category' => 'email', 'title' => 'Gekopieerde mail', 'body' => $body,
            'occurred_at' => now()->subDay()->format('Y-m-d H:i'),
            'company_id' => $this->otherCompany->id, 'actor_id' => 999, 'event' => 'created',
        ])->assertRedirect($this->logUrl());
        $entry = CompanyLogEntry::sole();
        $this->assertSame($body, $entry->body);
        $this->assertSame($this->company->id, $entry->company_id);
        $this->assertSame($this->admin->id, $entry->actor_id);
        $this->assertNull($entry->event);
        $this->assertTrue($entry->occurred_at->isBefore($entry->created_at));
        $this->get($this->logUrl())->assertOk()->assertSee('Gekopieerde mail')
            ->assertSee(e("<script>alert('x')</script>"), false)
            ->assertDontSee("<script>alert('x')</script>", false);
    }

    public function test_regular_admins_cannot_read_or_write_the_superadmin_logbook(): void
    {
        $user = User::factory()->create(['company_id' => $this->company->id, 'role' => 'admin']);
        $count = CompanyLogEntry::count();
        $this->actingAs($user)->get($this->logUrl())->assertForbidden();
        $this->post(route('super-admin.companies.logbook.store', $this->company), ['category' => 'note', 'body' => 'Verboden'])->assertForbidden();
        $this->assertDatabaseCount('company_log_entries', $count);
    }

    public function test_search_filters_and_pagination_stay_within_the_company(): void
    {
        $this->actingAs($this->admin);
        foreach (range(1, 27) as $index) {
            $this->post(route('super-admin.companies.logbook.store', $this->company), ['category' => 'note', 'title' => 'Notitie '.$index, 'body' => 'Afspraak over vestiging'])->assertRedirect();
        }
        $this->post(route('super-admin.companies.logbook.store', $this->otherCompany), ['category' => 'email', 'title' => 'Ander bedrijf geheim', 'body' => 'Afspraak over vestiging']);
        $this->post(route('super-admin.companies.logbook.store', $this->company), ['category' => 'email', 'title' => 'Mail in eigen logboek', 'body' => 'Unieke zoektekst']);
        $this->get($this->logUrl())->assertOk()->assertDontSee('Ander bedrijf geheim')
            ->assertViewHas('logEntries', fn ($entries) => $entries->total() === 28 && $entries->count() === 25);
        $this->get($this->logUrl(['log_type' => 'email', 'log_search' => 'Unieke zoektekst']))->assertOk()
            ->assertSee('Mail in eigen logboek')->assertDontSee('Notitie 27')->assertDontSee('Ander bedrijf geheim')
            ->assertViewHas('logEntries', fn ($entries) => $entries->total() === 1);
        $this->get($this->logUrl(['page' => 2]))->assertOk()->assertViewHas('logEntries', fn ($entries) => $entries->count() === 3);
    }

    public function test_note_validation_prevents_forged_automatic_events_and_empty_content(): void
    {
        $this->actingAs($this->admin);
        $this->post(route('super-admin.companies.logbook.store', $this->company), ['category' => 'company', 'body' => 'Fake wijziging'])->assertSessionHasErrors('category');
        $this->post(route('super-admin.companies.logbook.store', $this->company), ['category' => 'note', 'body' => '  '])->assertSessionHasErrors('body');
        $this->post(route('super-admin.companies.logbook.store', $this->company), ['category' => 'note', 'body' => 'Tekst', 'occurred_at' => now()->addDay()->toDateTimeString()])->assertSessionHasErrors('occurred_at');
        $this->assertDatabaseCount('company_log_entries', 0);
    }

    public function test_a_rolled_back_change_does_not_leave_a_log_entry(): void
    {
        DB::beginTransaction();
        $this->company->update(['name' => 'Tijdelijk']);
        $this->assertDatabaseCount('company_log_entries', 1);
        DB::rollBack();
        $this->assertDatabaseCount('company_log_entries', 0);
        $this->assertSame('Klant A', $this->company->fresh()->name);
    }

    public function test_moving_a_user_records_departure_and_arrival_without_cross_company_edits(): void
    {
        $user = User::factory()->create(['company_id' => $this->company->id]);
        CompanyLogEntry::query()->delete();
        $user->update(['company_id' => $this->otherCompany->id]);
        $this->assertDatabaseHas('company_log_entries', ['company_id' => $this->company->id, 'event' => 'moved_out']);
        $this->assertDatabaseHas('company_log_entries', ['company_id' => $this->otherCompany->id, 'event' => 'moved_in']);
    }

    public function test_deleting_an_author_preserves_the_name_and_old_notes(): void
    {
        $this->actingAs($this->admin)->post(route('super-admin.companies.logbook.store', $this->company), ['category' => 'note', 'body' => 'Bewaar dit gesprek']);
        $name = $this->admin->name;
        $this->admin->delete();
        $entry = CompanyLogEntry::where('category', 'note')->sole();
        $this->assertNull($entry->actor_id);
        $this->assertSame($name, $entry->actor_name);
        $this->assertSame('Bewaar dit gesprek', $entry->body);
    }
}

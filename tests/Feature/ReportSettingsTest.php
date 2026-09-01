<?php

namespace Tests\Feature;

use App\Models\Organisation\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_configure_report_sections(): void
    {
        $this->seed();
        $admin = User::where('role', 'admin')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('Onderdelen in deze rapportage')
            ->assertSee('Samenvatting')
            ->assertSee('Meest gebruikte lijsten')
            ->assertSee('Prestaties medewerkers');
    }
}

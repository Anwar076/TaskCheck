<?php

namespace Tests\Feature;

use App\Mail\CompanyReportMail;
use App\Models\Organisation\Company;
use App\Models\Organisation\CompanyReportRecipient;
use App\Models\Organisation\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
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

    public function test_admin_can_send_one_scheduled_report_immediately(): void
    {
        $this->seed();
        Mail::fake();
        $admin = User::where('role', 'admin')->firstOrFail();
        $recipient = $admin->company->reportRecipients()->create([
            'email' => 'rapport@example.test',
            'frequency' => Company::REPORTING_FREQUENCY_DAILY,
            'send_time' => '18:00',
            'delivery_format' => 'email',
            'sections' => CompanyReportRecipient::DEFAULT_SECTIONS,
            'is_enabled' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('Rapportage nu versturen');

        $this->actingAs($admin)
            ->postJson(route('admin.settings.reporting.send-now', $recipient))
            ->assertOk()
            ->assertJsonPath('success', true);

        Mail::assertSent(CompanyReportMail::class, fn (CompanyReportMail $mail) => $mail->hasTo('rapport@example.test')
            && $mail->report['frequency'] === Company::REPORTING_FREQUENCY_DAILY
        );
        $this->assertNull($recipient->fresh()->last_sent_at, 'Handmatig versturen mag de normale planning niet overslaan.');
    }

    public function test_super_admin_can_send_one_company_report_immediately(): void
    {
        $this->seed();
        Mail::fake();
        $admin = User::where('role', 'admin')->firstOrFail();
        config()->set('app.super_admin_emails', [$admin->email]);
        $recipient = $admin->company->reportRecipients()->create([
            'email' => 'week@example.test',
            'frequency' => Company::REPORTING_FREQUENCY_WEEKLY,
            'send_time' => '18:00',
            'weekly_day' => 1,
            'delivery_format' => 'both',
            'sections' => CompanyReportRecipient::DEFAULT_SECTIONS,
            'is_enabled' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('super-admin.companies.show', ['company' => $admin->company, 'section' => 'reporting']))
            ->assertOk()
            ->assertSee('Rapportage nu versturen');

        $this->actingAs($admin)
            ->postJson(route('super-admin.companies.reporting.send-now', [$admin->company, $recipient]))
            ->assertOk()
            ->assertJsonPath('success', true);

        Mail::assertSent(CompanyReportMail::class, fn (CompanyReportMail $mail) => $mail->hasTo('week@example.test')
            && $mail->report['frequency'] === Company::REPORTING_FREQUENCY_WEEKLY
        );
    }
}

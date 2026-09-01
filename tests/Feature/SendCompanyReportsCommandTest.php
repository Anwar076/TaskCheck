<?php

namespace Tests\Feature;

use App\Mail\CompanyReportMail;
use App\Models\Checklist\Task;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use App\Models\Submissions\Submission;
use App\Models\Submissions\SubmissionTask;
use App\Services\Admin\WeeklyOverviewService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendCompanyReportsCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_each_recipient_can_have_an_independent_schedule_and_delivery_format(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-08-31 18:00:00', 'Europe/Amsterdam'));
        Mail::fake();

        $company = Company::query()->create([
            'name' => 'Rapportagebedrijf',
            'email' => 'bedrijf@example.test',
            'is_active' => true,
        ]);

        $daily = $company->reportRecipients()->create([
            'email' => 'dag@example.test',
            'frequency' => Company::REPORTING_FREQUENCY_DAILY,
            'send_time' => '18:00',
            'delivery_format' => 'pdf',
            'sections' => ['summary' => true, 'top_lists' => false, 'employee_performance' => false, 'attention_points' => false, 'task_overview' => false],
            'is_enabled' => true,
        ]);
        $weekly = $company->reportRecipients()->create([
            'email' => 'week@example.test',
            'frequency' => Company::REPORTING_FREQUENCY_WEEKLY,
            'send_time' => '18:00',
            'weekly_day' => 1,
            'delivery_format' => 'both',
            'is_enabled' => true,
        ]);

        $this->artisan('reports:send-company')->assertSuccessful();

        Mail::assertSent(CompanyReportMail::class, 2);
        Mail::assertSent(CompanyReportMail::class, fn (CompanyReportMail $mail) => $mail->hasTo('dag@example.test')
            && $mail->report['frequency'] === Company::REPORTING_FREQUENCY_DAILY
            && $mail->report['sections'] === ['summary' => true, 'top_lists' => false, 'employee_performance' => false, 'attention_points' => false, 'task_overview' => false]
            && $mail->deliveryFormat === 'pdf'
        );
        Mail::assertSent(CompanyReportMail::class, fn (CompanyReportMail $mail) => $mail->hasTo('week@example.test')
            && $mail->report['frequency'] === Company::REPORTING_FREQUENCY_WEEKLY
            && $mail->deliveryFormat === 'both'
        );

        $this->assertNotNull($daily->fresh()->last_sent_at);
        $this->assertNotNull($weekly->fresh()->last_sent_at);

        $this->artisan('reports:send-company')->assertSuccessful();
        Mail::assertSent(CompanyReportMail::class, 2);
    }

    public function test_a_pdf_delivery_contains_a_pdf_attachment(): void
    {
        $company = Company::query()->create([
            'name' => 'PDF Bedrijf',
            'email' => 'bedrijf@example.test',
            'is_active' => true,
        ]);
        $date = Carbon::parse('2026-08-30 18:00:00', 'Europe/Amsterdam');
        $report = [
            'title' => 'Dagrapportage',
            'period_start' => $date->copy()->startOfDay(),
            'period_end' => $date->copy()->endOfDay(),
            'generated_at' => $date,
            'summary' => [],
            'employee_overview' => [],
            'top_lists' => [],
        ];

        $mail = new CompanyReportMail($company, $report, 'pdf');

        $this->assertCount(1, $mail->attachments());
        $this->assertCount(0, (new CompanyReportMail($company, $report, 'email'))->attachments());
    }

    public function test_report_views_only_render_enabled_sections(): void
    {
        $company = Company::query()->create([
            'name' => 'Rapportagebedrijf',
            'email' => 'bedrijf@example.test',
            'is_active' => true,
        ]);
        $date = Carbon::parse('2026-08-30 18:00:00', 'Europe/Amsterdam');
        $report = [
            'title' => 'Weekrapportage',
            'frequency' => Company::REPORTING_FREQUENCY_WEEKLY,
            'period_description' => 'Vorige week',
            'period_start' => $date->copy()->startOfWeek(),
            'period_end' => $date->copy()->endOfWeek(),
            'generated_at' => $date,
            'has_data' => true,
            'summary' => ['total_lists' => 2],
            'employee_overview' => [['name' => 'Gedeeld account', 'total_submissions' => 2, 'completion_rate' => 100]],
            'top_lists' => [['title' => 'Openingslijst', 'submissions_count' => 2]],
            'sections' => ['summary' => true, 'top_lists' => true, 'employee_performance' => false, 'attention_points' => false, 'task_overview' => false],
            'overview_url' => 'https://example.test/reports',
        ];

        $email = view('emails.company-report', compact('company', 'report'))->render();
        $pdf = view('reports.company-report-pdf', compact('company', 'report'))->render();

        $this->assertStringContainsString('Meest gebruikte lijsten', $email);
        $this->assertStringContainsString('Meest gebruikte lijsten', $pdf);
        $this->assertStringNotContainsString('Prestaties medewerkers', $email);
        $this->assertStringNotContainsString('Prestaties medewerkers', $pdf);
    }

    public function test_attention_points_only_include_comments_and_deviations(): void
    {
        $company = Company::query()->create([
            'name' => 'Testbedrijf',
            'email' => 'bedrijf@example.test',
            'is_active' => true,
        ]);
        $employee = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'employee',
            'is_active' => true,
        ]);
        $list = TaskList::query()->create([
            'title' => 'Keuken openen',
            'company_id' => $company->id,
            'created_by' => $employee->id,
        ]);
        $normalTask = Task::query()->create(['list_id' => $list->id, 'title' => 'Werkbank reinigen']);
        $commentTask = Task::query()->create(['list_id' => $list->id, 'title' => 'Frituurvet bijvullen']);
        $metricTask = Task::query()->create([
            'list_id' => $list->id,
            'title' => 'Koelkast meten',
            'validation_rules' => ['metric' => 'temperature', 'unit' => '°C', 'max' => 7, 'comparison' => 'lte'],
        ]);
        $openTask = Task::query()->create(['list_id' => $list->id, 'title' => 'Vloer dweilen']);
        $submission = Submission::query()->create([
            'company_id' => $company->id,
            'user_id' => $employee->id,
            'list_id' => $list->id,
            'status' => 'completed',
        ]);

        SubmissionTask::query()->create(['submission_id' => $submission->id, 'task_id' => $normalTask->id, 'status' => 'completed']);
        SubmissionTask::query()->create([
            'submission_id' => $submission->id,
            'task_id' => $commentTask->id,
            'status' => 'completed',
            'employee_comment' => 'Frituurvet was op',
        ]);
        SubmissionTask::query()->create([
            'submission_id' => $submission->id,
            'task_id' => $metricTask->id,
            'status' => 'completed',
            'proof_text' => '9 °C',
        ]);
        SubmissionTask::query()->create(['submission_id' => $submission->id, 'task_id' => $openTask->id, 'status' => 'pending']);

        $result = app(WeeklyOverviewService::class)->buildAttentionPoints(
            $company->id,
            now()->subDay(),
            now()->addDay(),
        );

        $this->assertCount(1, $result);
        $this->assertSame('Keuken openen', $result[0]['list_title']);
        $this->assertSame(['Frituurvet bijvullen', 'Koelkast meten'], array_column($result[0]['items'], 'task_title'));
        $this->assertSame(['Frituurvet was op'], $result[0]['items'][0]['messages']);
        $this->assertStringContainsString('Afwijkende meting: 9 °C', $result[0]['items'][1]['messages'][0]);
        $this->assertNotContains('Werkbank reinigen', array_column($result[0]['items'], 'task_title'));

        $overview = app(WeeklyOverviewService::class)->buildTaskOverview(
            $company->id,
            now()->subDay(),
            now()->addDay(),
        );

        $this->assertSame(
            ['Werkbank reinigen', 'Frituurvet bijvullen', 'Koelkast meten', 'Vloer dweilen'],
            array_column($overview[0]['tasks'], 'title'),
        );
        $this->assertSame(
            ['Afgerond', 'Afgerond', 'Afgerond', 'Niet afgerond'],
            array_column($overview[0]['tasks'], 'status_label'),
        );
    }
}

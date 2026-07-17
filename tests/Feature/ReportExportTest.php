<?php

namespace Tests\Feature;

use App\Models\Checklist\TaskList;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use App\Services\Exports\RawDataXlsxExporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use ZipArchive;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_own_list_to_excel_and_pdf(): void
    {
        [$admin, $list] = $this->adminAndList('Eigen bedrijf');
        $query = [
            'list_id' => $list->id,
            'start_date' => now()->subWeek()->toDateString(),
            'end_date' => now()->toDateString(),
        ];

        $this->actingAs($admin)
            ->get(route('admin.weekly-overview'))
            ->assertOk()
            ->assertSee('Rapportages')
            ->assertSee($list->title);

        $excel = $this->actingAs($admin)->get(route('admin.reports.export.excel', $query));
        $excel->assertOk()->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        $pdf = $this->actingAs($admin)->get(route('admin.reports.export.pdf', $query));
        $pdf->assertOk()->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_cannot_export_a_list_from_another_company(): void
    {
        [$admin] = $this->adminAndList('Eigen bedrijf');
        [, $otherList] = $this->adminAndList('Ander bedrijf');

        $this->actingAs($admin)->get(route('admin.reports.export.excel', [
            'list_id' => $otherList->id,
            'start_date' => now()->subWeek()->toDateString(),
            'end_date' => now()->toDateString(),
        ]))->assertNotFound();
    }

    public function test_excel_export_is_a_valid_xlsx_package(): void
    {
        [, $list] = $this->adminAndList('Excel test');
        $path = app(RawDataXlsxExporter::class)->create($list, collect());
        $zip = new ZipArchive();

        $this->assertTrue($zip->open($path) === true);
        $this->assertNotFalse($zip->locateName('xl/workbook.xml'));
        $this->assertNotFalse($zip->locateName('xl/worksheets/sheet1.xml'));
        $this->assertStringContainsString('Inzending ID', $zip->getFromName('xl/worksheets/sheet1.xml'));

        $zip->close();
        unlink($path);
    }

    private function adminAndList(string $name): array
    {
        $company = Company::query()->create([
            'name' => $name,
            'subscription_status' => 'active',
            'subscription_plan' => 'professional',
            'subscription_ends_at' => now()->addMonth(),
            'is_active' => true,
            'onboarding_completed_at' => now(),
            'address' => 'Teststraat 1',
            'phone' => '0101234567',
            'email' => str($name)->slug().'@example.test',
        ]);
        $admin = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $list = TaskList::query()->create([
            'title' => 'Controlelijst '.$name,
            'created_by' => $admin->id,
            'company_id' => $company->id,
        ]);

        return [$admin, $list];
    }
}

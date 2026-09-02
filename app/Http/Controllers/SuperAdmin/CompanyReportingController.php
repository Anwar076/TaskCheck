<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateReportingSettingsRequest;
use App\Models\Organisation\Company;
use App\Models\Organisation\CompanyReportRecipient;
use App\Services\Admin\CompanyReportingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class CompanyReportingController extends Controller
{
    public function sendNow(Company $company, CompanyReportRecipient $recipient, CompanyReportingService $reportingService): JsonResponse
    {
        abort_unless((int) $recipient->company_id === (int) $company->id, 404);
        $reportingService->sendNow($recipient);

        return response()->json(['success' => true, 'message' => "{$recipient->frequencyLabel()} verstuurd naar {$recipient->email}."]);
    }

    public function update(UpdateReportingSettingsRequest $request, Company $company): RedirectResponse
    {
        $rows = $request->validated('report_recipients', []);

        DB::transaction(function () use ($company, $rows) {
            $keptIds = [];
            foreach ($rows as $row) {
                $recipient = ! empty($row['id']) ? $company->reportRecipients()->find($row['id']) : null;
                $recipient ??= $company->reportRecipients()->make();
                $recipient->fill([
                    'email' => $row['email'],
                    'frequency' => $row['frequency'],
                    'send_time' => $row['send_time'],
                    'weekly_day' => $row['frequency'] === Company::REPORTING_FREQUENCY_WEEKLY ? ($row['weekly_day'] ?? 1) : null,
                    'delivery_format' => $row['delivery_format'],
                    'sections' => CompanyReportRecipient::normalizeSections($row['sections'] ?? null),
                    'is_enabled' => true,
                ])->save();
                $keptIds[] = $recipient->id;
            }

            $company->reportRecipients()
                ->when($keptIds !== [], fn ($query) => $query->whereNotIn('id', $keptIds))
                ->delete();
        });

        return redirect()->route('super-admin.companies.show', ['company' => $company, 'section' => 'reporting'])
            ->with('success', 'Rapportageplanning opgeslagen.');
    }
}

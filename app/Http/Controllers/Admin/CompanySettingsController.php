<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organisation\Company;
use App\Models\Organisation\CompanyReportRecipient;
use App\Services\Admin\CompanyReportingService;
use App\Services\Platform\AdminOnboardingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class CompanySettingsController extends Controller
{
    public function sendReportNow(CompanyReportRecipient $recipient, CompanyReportingService $reportingService): JsonResponse
    {
        $company = auth()->user()->company;
        abort_unless($company && (int) $recipient->company_id === (int) $company->id, 404);

        $reportingService->sendNow($recipient);

        return response()->json([
            'success' => true,
            'message' => "{$recipient->frequencyLabel()} verstuurd naar {$recipient->email}.",
        ]);
    }

    /**
     * Show the company settings form.
     */
    public function edit()
    {
        $company = auth()->user()->company;

        if (! $company) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Geen organisatie gekoppeld.');
        }

        $company->load(['reportRecipients' => fn ($query) => $query->orderBy('id')]);

        return view('admin.settings.edit', compact('company'));
    }

    /**
     * Update the company settings.
     */
    public function update(Request $request)
    {
        $company = auth()->user()->company;

        if (! $company) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Geen organisatie gekoppeld.');
        }

        // Ensure user is admin of this company
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Geen toegang tot organisatie-instellingen.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'phone' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:7', 'max:15'],
            'email' => ['required', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'departments_text' => ['nullable', 'string', 'max:4000'],
            'departments' => ['nullable', 'array'],
            'departments.*' => ['nullable', 'string', 'max:100'],
            'calendar_time_mode' => ['required', 'in:working_hours,24_hours'],
            'working_hours' => ['nullable', 'array'],
            'working_hours.*.enabled' => ['nullable', 'boolean'],
            'working_hours.*.start' => ['required', 'date_format:H:i'],
            'working_hours.*.end' => ['required', 'date_format:H:i'],
            'reporting_enabled' => ['nullable', 'boolean'],
            'reporting_frequency' => ['nullable', 'in:daily,weekly'],
            'reporting_send_time' => ['nullable', 'date_format:H:i'],
            'reporting_weekly_day' => ['nullable', 'integer', 'between:1,7'],
            'report_recipients' => ['nullable', 'array', 'max:20'],
            'report_recipients.*.id' => ['nullable', 'integer'],
            'report_recipients.*.email' => ['required', 'email', 'max:255'],
            'report_recipients.*.frequency' => ['required', 'in:daily,weekly'],
            'report_recipients.*.send_time' => ['required', 'date_format:H:i'],
            'report_recipients.*.weekly_day' => ['nullable', 'integer', 'between:1,7'],
            'report_recipients.*.delivery_format' => ['required', 'in:email,pdf,both'],
            'report_recipients.*.sections' => ['nullable', 'array'],
            'report_recipients.*.sections.summary' => ['nullable', 'boolean'],
            'report_recipients.*.sections.top_lists' => ['nullable', 'boolean'],
            'report_recipients.*.sections.employee_performance' => ['nullable', 'boolean'],
            'report_recipients.*.sections.attention_points' => ['nullable', 'boolean'],
            'report_recipients.*.sections.task_overview' => ['nullable', 'boolean'],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,webp',
                'max:2048', // 2MB
            ],
            'remove_logo' => ['nullable', 'boolean'],
        ]);

        foreach ($validated['report_recipients'] ?? [] as $index => $recipient) {
            if (! in_array(true, CompanyReportRecipient::normalizeSections($recipient['sections'] ?? null), true)) {
                throw ValidationException::withMessages([
                    "report_recipients.{$index}.sections" => 'Kies minimaal één onderdeel voor deze rapportage.',
                ]);
            }
        }

        foreach (Company::WEEKDAYS as $day => $label) {
            $defaults = Company::defaultWorkingHours()[$day];
            $hours = array_merge($defaults, $validated['working_hours'][$day] ?? []);
            if ($hours['end'] <= $hours['start']) {
                throw ValidationException::withMessages([
                    "working_hours.{$day}.end" => "De eindtijd voor {$label} moet na de starttijd liggen.",
                ]);
            }
        }

        $removeLogo = $request->boolean('remove_logo');

        // Handle logo removal before uploads, so an explicit remove action cannot be overridden.
        if ($removeLogo) {
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }

            $validated['logo_path'] = null;
        }

        // Enforce normalized phone storage (digits only).
        $validated['phone'] = preg_replace('/\D+/', '', (string) ($validated['phone'] ?? ''));

        $departmentInput = [];
        if (! empty($validated['departments']) && is_array($validated['departments'])) {
            $departmentInput = $validated['departments'];
        } else {
            $departmentInput = preg_split('/\r\n|\r|\n/', (string) ($validated['departments_text'] ?? ''));
        }

        $departments = collect($departmentInput)
            ->map(fn ($item) => trim((string) $item))
            ->filter()
            ->map(fn ($item) => mb_substr($item, 0, 100))
            ->unique()
            ->values()
            ->all();

        $validated['departments'] = ! empty($departments) ? $departments : null;
        $validated['working_hours'] = collect(Company::defaultWorkingHours())
            ->mapWithKeys(function (array $defaults, string $day) use ($validated) {
                $input = $validated['working_hours'][$day] ?? [];

                return [$day => [
                    'enabled' => (bool) ($input['enabled'] ?? false),
                    'start' => $input['start'] ?? $defaults['start'],
                    'end' => $input['end'] ?? $defaults['end'],
                ]];
            })
            ->all();

        $validated['reporting_enabled'] = $request->boolean('reporting_enabled');
        if ($validated['reporting_enabled']) {
            $validated['reporting_frequency'] = $validated['reporting_frequency'] ?? Company::REPORTING_FREQUENCY_DAILY;
            $validated['reporting_send_time'] = $validated['reporting_send_time'] ?? '09:00';
            $validated['reporting_weekly_day'] = (int) ($validated['reporting_weekly_day'] ?? 1);
        } else {
            $validated['reporting_frequency'] = null;
            $validated['reporting_send_time'] = null;
            $validated['reporting_weekly_day'] = 1;
        }

        // Handle logo upload
        if (! $removeLogo && $request->hasFile('logo')) {
            // Delete old logo if exists
            if ($company->logo_path) {
                Storage::disk('public')->delete($company->logo_path);
            }

            $path = $request->file('logo')->store('company-logos', 'public');
            $validated['logo_path'] = $path;
        }

        $recipientRows = $validated['report_recipients'] ?? [];
        unset($validated['logo'], $validated['remove_logo'], $validated['departments_text'], $validated['report_recipients']);
        $company->update($validated);
        $keptIds = [];
        foreach ($recipientRows as $row) {
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
        $company->reportRecipients()->when($keptIds !== [], fn ($query) => $query->whereNotIn('id', $keptIds))->delete();
        $company->refresh();

        app(AdminOnboardingService::class)->handleOrganizationSaved($company);

        if ($company->needsOnboarding() && $company->onboarding_step === \App\Models\Organisation\Company::ONBOARDING_STEP_USERS) {
            return redirect()
                ->route('admin.users.index')
                ->with('success', 'Organisatiegegevens opgeslagen. Voeg nu je team toe.');
        }

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Organisatie-instellingen succesvol bijgewerkt.');
    }
}

<?php

namespace App\Http\Requests;

use App\Models\Organisation\CompanyReportRecipient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateReportingSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'report_recipients' => ['nullable', 'array', 'max:20'],
            'report_recipients.*.id' => ['nullable', 'integer'],
            'report_recipients.*.email' => ['required', 'email', 'max:255'],
            'report_recipients.*.frequency' => ['required', Rule::in(['daily', 'weekly'])],
            'report_recipients.*.send_time' => ['required', 'date_format:H:i'],
            'report_recipients.*.weekly_day' => ['nullable', 'integer', 'between:1,7'],
            'report_recipients.*.delivery_format' => ['required', Rule::in(['email', 'pdf', 'both'])],
            'report_recipients.*.sections' => ['nullable', 'array'],
            'report_recipients.*.sections.summary' => ['nullable', 'boolean'],
            'report_recipients.*.sections.top_lists' => ['nullable', 'boolean'],
            'report_recipients.*.sections.employee_performance' => ['nullable', 'boolean'],
            'report_recipients.*.sections.attention_points' => ['nullable', 'boolean'],
        ];
    }

    protected function passedValidation(): void
    {
        foreach ($this->validated('report_recipients', []) as $index => $recipient) {
            if (! in_array(true, CompanyReportRecipient::normalizeSections($recipient['sections'] ?? null), true)) {
                throw ValidationException::withMessages([
                    "report_recipients.{$index}.sections" => 'Kies minimaal één onderdeel voor deze rapportage.',
                ]);
            }
        }
    }
}

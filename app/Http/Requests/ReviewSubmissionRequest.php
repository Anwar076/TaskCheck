<?php

namespace App\Http\Requests;

use App\Enums\SubmissionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(SubmissionStatus::closedValues())],
            'admin_notes' => ['nullable', 'string'],
        ];
    }
}

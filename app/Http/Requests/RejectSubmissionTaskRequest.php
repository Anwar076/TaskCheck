<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RejectSubmissionTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string'],
            'corrective_action' => ['required', 'string', 'max:2000'],
            'corrective_action_owner_id' => ['required', Rule::exists('users', 'id')->where(fn ($query) => $query->where('company_id', $this->user()->company_id))],
            'corrective_action_due_at' => ['required', 'date', 'after_or_equal:today'],
        ];
    }
}

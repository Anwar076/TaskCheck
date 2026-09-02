<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApproveSubmissionTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        $hasCorrectiveAction = filled($this->route('submissionTask')?->corrective_action);

        return [
            'manager_comment' => ['nullable', 'string'],
            'verification_note' => [$hasCorrectiveAction ? 'required' : 'nullable', 'string', 'max:2000'],
            'confirm_corrective_action_closed' => $hasCorrectiveAction
                ? ['required', 'accepted']
                : ['nullable', 'boolean'],
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

abstract class SaveTaskListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->company_id !== null;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'compliance_framework' => ['nullable', 'string', 'max:255'],
            'policy_reference' => ['nullable', 'string', 'max:255'],
            'priority' => ['required', Rule::in(['low', 'medium', 'high', 'urgent'])],
            'schedule_type' => ['required', Rule::in(['once', 'daily', 'weekly', 'monthly'])],
            'due_date' => ['nullable', 'date'],
            'parent_list_id' => ['nullable', 'exists:task_lists,id'],
            'requires_signature' => ['boolean'],
            'requires_review' => ['boolean'],
            'auto_accept_without_review' => ['boolean'],
            'is_template' => ['boolean'],
            'is_active' => ['boolean'],
            'schedule_config' => ['nullable', 'array'],
            'template_id' => ['nullable', 'exists:task_templates,id'],
            'selected_days' => ['nullable', 'array'],
            'selected_days.*' => ['string', Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])],
            'location_id' => [
                'nullable',
                Rule::exists('locations', 'id')->where('company_id', $this->user()->company_id),
            ],
            'default_time_slot_enabled' => ['boolean'],
            'default_time_slot_start' => ['nullable', 'date_format:H:i', 'required_if:default_time_slot_enabled,1'],
            'default_time_slot_end' => ['nullable', 'date_format:H:i'],
            'time_slots' => ['nullable', 'array'],
            'time_slots.*.weekday' => ['required', Rule::in(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])],
            'time_slots.*.start_time' => ['required', 'date_format:H:i'],
            'time_slots.*.end_time' => ['nullable', 'date_format:H:i'],
            'ai_tasks' => ['nullable', 'json'],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            if ($this->boolean('default_time_slot_enabled')
                && $this->filled('default_time_slot_start')
                && $this->filled('default_time_slot_end')
                && $this->input('default_time_slot_end') <= $this->input('default_time_slot_start')) {
                $validator->errors()->add('default_time_slot_end', 'Eindtijd moet na starttijd liggen.');
            }

            foreach ($this->input('time_slots', []) as $index => $slot) {
                if (is_array($slot) && ! empty($slot['end_time']) && ($slot['end_time'] <= ($slot['start_time'] ?? ''))) {
                    $validator->errors()->add("time_slots.{$index}.end_time", 'Eindtijd moet na starttijd liggen.');
                }
            }

            if ($this->input('schedule_type') === 'weekly' && $this->input('selected_days', []) === []) {
                $validator->errors()->add('selected_days', 'Selecteer minimaal één dag voor een wekelijkse lijst.');
            }
        }];
    }
}

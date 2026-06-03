<?php

namespace App\Services\Templates;

use App\Models\Company;
use App\Models\ListAssignment;
use App\Models\Location;
use App\Models\Task;
use App\Models\TaskList;
use App\Models\TaskTemplate;
use App\Models\User;
use Illuminate\Support\Collection;

class HorecaStarterPackService
{
    public function applyForCompany(Company $company, User $adminUser): void
    {
        if (($company->company_type ?? null) !== 'horeca') {
            return;
        }

        $location = $this->resolvePrimaryLocation($company);

        $templates = TaskTemplate::withoutGlobalScopes()
            ->where('company_id', $company->id)
            ->where('is_starter_pack', true)
            ->where(function ($query) {
                $query->where('category', 'Horeca')
                    ->orWhere('starter_pack_group', 'horeca');
            })
            ->with('templateTasks')
            ->orderBy('name')
            ->get();

        foreach ($templates as $template) {
            $list = TaskList::firstOrCreate(
                [
                    'company_id' => $company->id,
                    'location_id' => $location->id,
                    'title' => $template->name,
                    'category' => 'Horeca',
                ],
                [
                    'description' => $template->description,
                    'created_by' => $adminUser->id,
                    'schedule_type' => $this->mapScheduleType($template->frequency_type),
                    'schedule_config' => $this->buildScheduleConfig($template->frequency_type),
                    'priority' => 'high',
                    'is_active' => true,
                    'template_id' => null,
                ]
            );

            if ($list->wasRecentlyCreated) {
                $this->seedListTasks($list, $template->templateTasks, $adminUser->id);
                ListAssignment::firstOrCreate([
                    'list_id' => $list->id,
                    'user_id' => $adminUser->id,
                    'is_active' => true,
                ], [
                    'department' => 'Kwaliteit',
                    'role' => 'admin',
                    'assigned_date' => now()->toDateString(),
                ]);
            }
        }
    }

    private function resolvePrimaryLocation(Company $company): Location
    {
        return Location::firstOrCreate(
            [
                'company_id' => $company->id,
                'name' => 'Hoofdlocatie',
            ],
            [
                'address' => null,
                'notes' => 'Automatisch aangemaakt door Horeca Starter Pack',
                'is_active' => true,
            ]
        );
    }

    private function seedListTasks(TaskList $list, Collection $templateTasks, int $createdBy): void
    {
        foreach ($templateTasks as $index => $templateTask) {
            Task::create([
                'list_id' => $list->id,
                'title' => $templateTask->title,
                'description' => $templateTask->description,
                'instructions' => $templateTask->instructions,
                'required_proof_type' => $templateTask->required_proof_type,
                'is_required' => (bool) $templateTask->is_required,
                'checklist_items' => $templateTask->checklist_items,
                'validation_rules' => $templateTask->validation_rules,
                'order_index' => $index,
                'created_by' => $createdBy,
            ]);
        }
    }

    private function mapScheduleType(?string $frequencyType): string
    {
        return match ($frequencyType) {
            'daily' => 'daily',
            'weekly' => 'weekly',
            'quarterly', 'per_batch', 'per_production' => 'custom',
            default => 'once',
        };
    }

    private function buildScheduleConfig(?string $frequencyType): ?array
    {
        return match ($frequencyType) {
            'quarterly' => ['interval' => 'quarterly', 'show_on_days' => ['monday']],
            'per_batch' => ['mode' => 'manual_batch'],
            'per_production' => ['mode' => 'manual_production'],
            default => null,
        };
    }
}


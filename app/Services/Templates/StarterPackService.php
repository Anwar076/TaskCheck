<?php

namespace App\Services\Templates;

use App\Data\StarterPacks\PackRegistry;
use App\Models\Checklist\TaskTemplate;
use App\Models\Checklist\TemplateTask;
use App\Models\Organisation\Company;
use App\Models\Organisation\CompanyStarterPackActivation;
use App\Models\Organisation\User;
use Illuminate\Support\Facades\DB;

class StarterPackService
{
    /**
     * @return array{templates_imported: int, already_active: bool}
     */
    public function activate(Company $company, User $adminUser, string $packSlug): array
    {
        $pack = StarterPackCatalog::find($packSlug);
        if (! $pack) {
            throw new \InvalidArgumentException("Onbekend starterpack: {$packSlug}");
        }

        $existing = CompanyStarterPackActivation::query()
            ->where('company_id', $company->id)
            ->where('pack_slug', $packSlug)
            ->first();

        if ($existing) {
            return [
                'templates_imported' => $existing->templates_imported,
                'already_active' => true,
            ];
        }

        return DB::transaction(function () use ($company, $packSlug, $adminUser) {
            $this->ensureGlobalTemplates($packSlug);

            $globalTemplates = TaskTemplate::withoutGlobalScopes()
                ->whereNull('company_id')
                ->where('is_starter_pack', true)
                ->where('starter_pack_group', $packSlug)
                ->with('templateTasks')
                ->orderBy('name')
                ->get();

            $templatesImported = 0;

            foreach ($globalTemplates as $globalTemplate) {
                $companyTemplate = $this->importTemplateToCompany($globalTemplate, $company->id);
                if ($companyTemplate->wasRecentlyCreated) {
                    $templatesImported++;
                }
            }

            CompanyStarterPackActivation::create([
                'company_id' => $company->id,
                'pack_slug' => $packSlug,
                'activated_by' => $adminUser->id,
                'templates_imported' => $templatesImported,
                'lists_created' => 0,
            ]);

            return [
                'templates_imported' => $templatesImported,
                'already_active' => false,
            ];
        });
    }

    /**
     * @return array{templates_removed: int, already_inactive: bool}
     */
    public function deactivate(Company $company, string $packSlug): array
    {
        $pack = StarterPackCatalog::find($packSlug);
        if (! $pack) {
            throw new \InvalidArgumentException("Onbekend starterpack: {$packSlug}");
        }

        $activation = CompanyStarterPackActivation::query()
            ->where('company_id', $company->id)
            ->where('pack_slug', $packSlug)
            ->first();

        if (! $activation) {
            return [
                'templates_removed' => 0,
                'already_inactive' => true,
            ];
        }

        return DB::transaction(function () use ($company, $packSlug, $activation) {
            $templates = TaskTemplate::withoutGlobalScopes()
                ->where('company_id', $company->id)
                ->where('starter_pack_group', $packSlug)
                ->where('is_starter_pack', true)
                ->get();

            $templatesRemoved = $templates->count();

            foreach ($templates as $template) {
                $template->delete();
            }

            $activation->delete();

            return [
                'templates_removed' => $templatesRemoved,
                'already_inactive' => false,
            ];
        });
    }

    public function ensureGlobalTemplates(string $packSlug): void
    {
        $definitions = PackRegistry::templatesFor($packSlug);
        if ($definitions === []) {
            return;
        }

        DB::transaction(function () use ($definitions, $packSlug) {
            foreach ($definitions as $templateData) {
                $template = TaskTemplate::withoutGlobalScopes()->updateOrCreate(
                    [
                        'company_id' => null,
                        'name' => $templateData['name'],
                        'starter_pack_group' => $packSlug,
                    ],
                    [
                        'description' => $templateData['description'] ?? null,
                        'is_active' => true,
                        'target_company_type' => $packSlug,
                        'category' => $this->mapTemplateCategory($templateData['category'] ?? 'food_safety'),
                        'icon' => $templateData['icon'] ?? 'clipboard-outline',
                        'frequency_label' => $templateData['frequency_label'] ?? null,
                        'frequency_type' => $templateData['frequency_type'] ?? 'daily',
                        'is_starter_pack' => true,
                        'compliance_rules' => [
                            'source_basis' => $templateData['source_basis'] ?? 'NVWA / HACCP',
                            'pack_slug' => $packSlug,
                        ],
                        'source_updated_at' => now(),
                    ]
                );

                $template->templateTasks()->delete();
                foreach ($templateData['tasks'] as $index => $task) {
                    TemplateTask::create([
                        'template_id' => $template->id,
                        'title' => $task['title'],
                        'description' => $task['description'] ?? null,
                        'instructions' => $task['instructions'] ?? null,
                        'required_proof_type' => $task['required_proof_type'] ?? 'none',
                        'is_required' => (bool) ($task['is_required'] ?? true),
                        'checklist_items' => $task['checklist_items'] ?? null,
                        'validation_rules' => $task['validation_rules'] ?? null,
                        'sort_order' => $index,
                        'is_active' => true,
                    ]);
                }
            }
        });
    }

    /**
     * @return array<int, string>
     */
    public function activatedSlugsForCompany(int $companyId): array
    {
        return CompanyStarterPackActivation::query()
            ->where('company_id', $companyId)
            ->pluck('pack_slug')
            ->all();
    }

    private function importTemplateToCompany(TaskTemplate $globalTemplate, int $companyId): TaskTemplate
    {
        $existing = TaskTemplate::withoutGlobalScopes()
            ->where('company_id', $companyId)
            ->where('source_template_id', $globalTemplate->id)
            ->with('templateTasks')
            ->first();

        if ($existing) {
            return $existing;
        }

        $newTemplate = TaskTemplate::withoutGlobalScopes()->create([
            'name' => $globalTemplate->name,
            'description' => $globalTemplate->description,
            'is_active' => true,
            'company_id' => $companyId,
            'source_template_id' => $globalTemplate->id,
            'source_updated_at' => $globalTemplate->updated_at,
            'target_company_type' => $globalTemplate->target_company_type,
            'category' => $globalTemplate->category,
            'icon' => $globalTemplate->icon,
            'frequency_label' => $globalTemplate->frequency_label,
            'frequency_type' => $globalTemplate->frequency_type,
            'is_starter_pack' => true,
            'starter_pack_group' => $globalTemplate->starter_pack_group,
            'compliance_rules' => $globalTemplate->compliance_rules,
        ]);

        foreach ($globalTemplate->templateTasks as $task) {
            TemplateTask::create([
                'template_id' => $newTemplate->id,
                'title' => $task->title,
                'description' => $task->description,
                'instructions' => $task->instructions,
                'required_proof_type' => $task->required_proof_type,
                'is_required' => $task->is_required,
                'checklist_items' => $task->checklist_items,
                'attachments' => $task->attachments,
                'validation_rules' => $task->validation_rules,
                'start_time' => $task->start_time,
                'end_time' => $task->end_time,
                'sort_order' => $task->sort_order,
                'is_active' => true,
            ]);
        }

        $newTemplate->setRelation('templateTasks', $newTemplate->templateTasks()->get());

        return $newTemplate;
    }

    private function mapTemplateCategory(string $category): string
    {
        return match ($category) {
            'cleaning' => 'Schoonmaak',
            'allergens' => 'Allergenen',
            'temperature' => 'Temperatuur',
            'safety' => 'Veiligheid',
            'operations' => 'Operationeel',
            default => 'Voedselveiligheid',
        };
    }
}

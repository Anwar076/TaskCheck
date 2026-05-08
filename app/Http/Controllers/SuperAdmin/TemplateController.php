<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\TaskCheckNotificationMail;
use App\Models\Company;
use App\Models\Notification;
use App\Models\TaskTemplate;
use App\Models\TemplateTask;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class TemplateController extends Controller
{
    public function index()
    {
        $this->ensureDefaultGlobalTemplatesExist();

        $filterType = request()->query('company_type', 'all');

        $templates = TaskTemplate::withoutGlobalScopes()
            ->whereNull('company_id')
            ->when($filterType === 'cleaning', fn ($query) => $query->where('target_company_type', 'cleaning'))
            ->when($filterType === 'horeca', fn ($query) => $query->where('target_company_type', 'horeca'))
            ->when($filterType === 'other', fn ($query) => $query->where('target_company_type', 'other'))
            ->with('templateTasks')
            ->orderBy('name')
            ->get();

        return view('super-admin.templates.index', compact('templates', 'filterType'));
    }

    private function ensureDefaultGlobalTemplatesExist(): void
    {
        $defaults = [
            'cleaning' => [
                [
                    'name' => 'Dagelijkse schoonmaak ronde',
                    'description' => 'Dagelijkse controle en schoonmaak van entree, sanitair en algemene ruimtes.',
                    'tasks' => ['Entree en receptie schoonmaken', 'Sanitair reinigen en aanvullen', 'Algemene ruimtes nalopen'],
                ],
                [
                    'name' => 'Periodieke dieptereiniging',
                    'description' => 'Wekelijkse of maandelijkse dieptereiniging van kritieke zones.',
                    'tasks' => ['Dieptereiniging keuken/pantry', 'Ramen en glaspartijen', 'Contactpunten desinfecteren'],
                ],
                [
                    'name' => 'Oplevercontrole locatie',
                    'description' => 'Eindcontrole op kwaliteit en complete oplevering.',
                    'tasks' => ['Visuele kwaliteitscontrole per zone', 'Afwijkingen registreren', 'Eindoplevering bevestigen'],
                ],
            ],
            'horeca' => [
                [
                    'name' => 'Dagelijkse openingscheck horeca',
                    'description' => 'Controle van hygiene, voorraad en apparatuur voor opening.',
                    'tasks' => ['Koelingen en vriezers controleren', 'Keukenwerkplekken hygieneklaar maken', 'Mise-en-place basiscontrole'],
                ],
                [
                    'name' => 'HACCP controlelijst',
                    'description' => 'Dagelijkse HACCP-routine voor voedselveiligheid en traceerbaarheid.',
                    'tasks' => ['Goederenontvangst HACCP check', 'Bewaarcondities en etikettering', 'Kruisbesmetting preventie', 'Schoonmaak- en desinfectielog'],
                ],
                [
                    'name' => 'Sluitronde horeca',
                    'description' => 'Einde-dag checklist voor veilige en schone afsluiting.',
                    'tasks' => ['Keuken en bar eindschoonmaak', 'Afval en retourstromen afronden', 'Apparatuur en veiligheid afsluiten'],
                ],
            ],
            'other' => [
                [
                    'name' => 'Dagelijkse basiscontrole',
                    'description' => 'Algemene start- en netheidscontrole voor teams zonder branchespecifiek protocol.',
                    'tasks' => ['Werkplek en materialen voorbereiden', 'Dagstart en prioriteiten afstemmen', 'Kwaliteits- en voortgangscheck'],
                ],
                [
                    'name' => 'Einde-dag afronding',
                    'description' => 'Standaard afsluitronde voor nette overdracht en duidelijke status.',
                    'tasks' => ['Werkplek afsluiten', 'Resultaten en aandachtspunten registreren', 'Overdracht bevestigen'],
                ],
            ],
        ];

        DB::transaction(function () use ($defaults) {
            foreach ($defaults as $companyType => $templates) {
                foreach ($templates as $templateData) {
                    $alreadyExists = TaskTemplate::withoutGlobalScopes()
                        ->whereNull('company_id')
                        ->where('name', $templateData['name'])
                        ->when(
                            Schema::hasColumn('task_templates', 'target_company_type'),
                            fn ($query) => $query->where('target_company_type', $companyType)
                        )
                        ->exists();

                    $template = TaskTemplate::withoutGlobalScopes()
                        ->whereNull('company_id')
                        ->where('name', $templateData['name'])
                        ->when(
                            Schema::hasColumn('task_templates', 'target_company_type'),
                            fn ($query) => $query->where('target_company_type', $companyType)
                        )
                        ->first();

                    if (!$template) {
                        $templatePayload = [
                            'name' => $templateData['name'],
                            'description' => $templateData['description'],
                            'is_active' => true,
                            'company_id' => null,
                        ];

                        if (Schema::hasColumn('task_templates', 'target_company_type')) {
                            $templatePayload['target_company_type'] = $companyType;
                        }

                        $template = $this->createGlobalTemplate($templatePayload);

                        foreach ($templateData['tasks'] as $index => $taskTitle) {
                            TemplateTask::create([
                                'template_id' => $template->id,
                                'title' => $taskTitle,
                                'description' => null,
                                'instructions' => null,
                                'required_proof_type' => 'photo',
                                'is_required' => true,
                                'checklist_items' => null,
                                'sort_order' => $index,
                                'is_active' => true,
                            ]);
                        }
                    }

                    $this->hydrateGlobalTemplateChecklistData($template);
                }
            }
        });
    }

    public function create()
    {
        return view('super-admin.templates.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_company_type' => 'nullable|in:cleaning,horeca,other',
            'tasks' => 'required|array|min:1',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.instructions' => 'nullable|string',
            'tasks.*.required_proof_type' => 'required|in:none,photo,video,text,file,any',
            'tasks.*.is_required' => 'nullable|boolean',
            'tasks.*.checklist_items' => 'nullable|array',
            'tasks.*.checklist_items_text' => 'nullable|string',
            'tasks.*.start_time' => 'nullable|date_format:H:i',
            'tasks.*.end_time' => 'nullable|date_format:H:i',
        ]);

        DB::transaction(function () use ($validated) {
            $payload = [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => true,
                'company_id' => null,
            ];

            if (Schema::hasColumn('task_templates', 'target_company_type')) {
                $payload['target_company_type'] = $validated['target_company_type'] ?? null;
            }

            $template = $this->createGlobalTemplate($payload);

            foreach ($validated['tasks'] as $index => $taskData) {
                $checklistItems = is_array($taskData['checklist_items'] ?? null)
                    ? collect($taskData['checklist_items'])->map(fn ($item) => trim((string) $item))->filter()->values()->all()
                    : collect(preg_split('/\r\n|\r|\n/', (string) ($taskData['checklist_items_text'] ?? '')))
                        ->map(fn ($item) => trim($item))
                        ->filter()
                        ->values()
                        ->all();

                TemplateTask::create([
                    'template_id' => $template->id,
                    'title' => $taskData['title'],
                    'description' => $taskData['description'] ?? null,
                    'instructions' => $taskData['instructions'] ?? null,
                    'required_proof_type' => $taskData['required_proof_type'],
                    'is_required' => (bool) ($taskData['is_required'] ?? true),
                    'checklist_items' => !empty($checklistItems) ? $checklistItems : null,
                    'start_time' => !empty($taskData['start_time']) ? $taskData['start_time'] : null,
                    'end_time' => !empty($taskData['end_time']) ? $taskData['end_time'] : null,
                    'sort_order' => $index,
                    'is_active' => true,
                ]);
            }
        });

        return redirect()->route('super-admin.templates.index')->with('success', 'Global template aangemaakt.');
    }

    public function edit($template)
    {
        $template = TaskTemplate::withoutGlobalScopes()->findOrFail($template);
        abort_if($template->company_id !== null, 404);
        $this->hydrateGlobalTemplateChecklistData($template);
        $template->load('templateTasks');

        return view('super-admin.templates.edit', compact('template'));
    }

    public function update(Request $request, $template): RedirectResponse
    {
        $template = TaskTemplate::withoutGlobalScopes()->findOrFail($template);
        abort_if($template->company_id !== null, 404);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_company_type' => 'nullable|in:cleaning,horeca,other',
            'tasks' => 'required|array|min:1',
            'tasks.*.id' => 'nullable|exists:template_tasks,id',
            'tasks.*.title' => 'required|string|max:255',
            'tasks.*.description' => 'nullable|string',
            'tasks.*.instructions' => 'nullable|string',
            'tasks.*.required_proof_type' => 'required|in:none,photo,video,text,file,any',
            'tasks.*.is_required' => 'nullable|boolean',
            'tasks.*.checklist_items' => 'nullable|array',
            'tasks.*.checklist_items_text' => 'nullable|string',
            'tasks.*.start_time' => 'nullable|date_format:H:i',
            'tasks.*.end_time' => 'nullable|date_format:H:i',
        ]);

        DB::transaction(function () use ($template, $validated) {
            $templatePayload = [
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ];

            if (Schema::hasColumn('task_templates', 'target_company_type')) {
                $templatePayload['target_company_type'] = $validated['target_company_type'] ?? null;
            }

            $template->update($templatePayload);

            $incomingIds = collect($validated['tasks'])->pluck('id')->filter()->all();
            $template->templateTasks()->whereNotIn('id', $incomingIds)->delete();

            foreach ($validated['tasks'] as $index => $taskData) {
                $checklistItems = is_array($taskData['checklist_items'] ?? null)
                    ? collect($taskData['checklist_items'])->map(fn ($item) => trim((string) $item))->filter()->values()->all()
                    : collect(preg_split('/\r\n|\r|\n/', (string) ($taskData['checklist_items_text'] ?? '')))
                        ->map(fn ($item) => trim($item))
                        ->filter()
                        ->values()
                        ->all();

                $payload = [
                    'title' => $taskData['title'],
                    'description' => $taskData['description'] ?? null,
                    'instructions' => $taskData['instructions'] ?? null,
                    'required_proof_type' => $taskData['required_proof_type'],
                    'is_required' => (bool) ($taskData['is_required'] ?? true),
                    'checklist_items' => !empty($checklistItems) ? $checklistItems : null,
                    'start_time' => !empty($taskData['start_time']) ? $taskData['start_time'] : null,
                    'end_time' => !empty($taskData['end_time']) ? $taskData['end_time'] : null,
                    'sort_order' => $index,
                    'is_active' => true,
                ];

                if (!empty($taskData['id'])) {
                    TemplateTask::where('id', $taskData['id'])->update($payload);
                } else {
                    $payload['template_id'] = $template->id;
                    TemplateTask::create($payload);
                }
            }

            // Markeer het global template altijd als "nieuwere conceptwijziging",
            // ook wanneer alleen onderliggende taken zijn aangepast.
            $draftUpdatedAt = now();
            if ($template->source_updated_at && $draftUpdatedAt->lte($template->source_updated_at)) {
                $draftUpdatedAt = $template->source_updated_at->copy()->addSecond();
            }
            $template->forceFill([
                'updated_at' => $draftUpdatedAt,
            ])->saveQuietly();
        });

        return redirect()->route('super-admin.templates.index')->with('success', 'Global template opgeslagen als concept. Publiceer om wijzigingen door te zetten.');
    }

    public function publish($template): RedirectResponse
    {
        $template = TaskTemplate::withoutGlobalScopes()->findOrFail($template);
        abort_if($template->company_id !== null, 404);
        $template->load('templateTasks');

        $publishedAt = now();
        $template->update([
            'updated_at' => $publishedAt,
            'source_updated_at' => $publishedAt,
        ]);

        // Push wijzigingen direct door naar admin-templates en lijsten.
        $this->syncGlobalTemplateToLinkedCompanyTemplates($template);

        $companies = Company::query()
            ->where('is_active', true)
            ->when(
                Schema::hasColumn('companies', 'company_type') && !empty($template->target_company_type),
                fn ($q) => $q->where('company_type', $template->target_company_type)
            )
            ->get();

        foreach ($companies as $company) {
            if (!$company instanceof Company) {
                continue;
            }

            $existingLinkedTemplate = TaskTemplate::withoutGlobalScopes()
                ->where('company_id', $company->id)
                ->where('source_template_id', $template->id)
                ->first();

            $isUpdate = (bool) $existingLinkedTemplate;
            if (!$existingLinkedTemplate) {
                $existingLinkedTemplate = $this->createCompanyTemplateFromGlobal($template, $company);
            }

            $this->notifyCompanyAboutTemplate($company, $template, $isUpdate);
        }

        return redirect()->route('super-admin.templates.index')
            ->with('success', 'Template gepubliceerd naar bedrijven. Zij ontvangen een melding en e-mail.');
    }

    private function notifyCompanyAboutTemplate(Company $company, TaskTemplate $template, bool $isUpdate): void
    {
        $admins = $company->users()->where('role', 'admin')->where('is_active', true)->get();
        $title = $isUpdate ? 'Template update beschikbaar' : 'Nieuwe template beschikbaar';
        $message = $isUpdate
            ? "De template '{$template->name}' is bijgewerkt door super admin. Controleer en pas toe."
            : "Er is een nieuwe template '{$template->name}' beschikbaar gesteld door super admin.";

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => $isUpdate ? 'template_update_available' : 'template_new_available',
                'title' => $title,
                'message' => $message,
                'data' => [
                    'global_template_id' => $template->id,
                    'url' => route('admin.templates.index'),
                ],
            ]);
        }

        $recipient = $company->email ?: optional($admins->first())->email;
        if (!$recipient) {
            return;
        }

        Mail::to($recipient)->send(new TaskCheckNotificationMail(
            subjectLine: $title,
            greetingName: $company->name,
            title: $title,
            bodyText: $message,
            ctaLabel: 'Bekijk templates',
            ctaUrl: route('admin.templates.index'),
            metaText: 'Dit is een automatische melding vanuit TaskCheck super admin.'
        ));
    }

    private function syncGlobalTemplateToLinkedCompanyTemplates(TaskTemplate $globalTemplate): void
    {
        $targetCompanyIds = Company::query()
            ->when(
                Schema::hasColumn('companies', 'company_type') && !empty($globalTemplate->target_company_type),
                fn ($q) => $q->where('company_type', $globalTemplate->target_company_type)
            )
            ->pluck('id')
            ->all();

        $linkedTemplates = TaskTemplate::withoutGlobalScopes()
            ->whereNotNull('company_id')
            ->when(!empty($targetCompanyIds), fn ($q) => $q->whereIn('company_id', $targetCompanyIds))
            ->where(function ($q) use ($globalTemplate) {
                $q->where('source_template_id', $globalTemplate->id)
                    // Fallback: oudere bedrijfstemplates (zonder source_template_id)
                    // met dezelfde naam ook automatisch bijwerken.
                    ->orWhere(function ($nameMatch) use ($globalTemplate) {
                        $nameMatch->whereNull('source_template_id')
                            ->where('name', $globalTemplate->name);
                    });
            })
            ->with('templateTasks')
            ->get();

        foreach ($linkedTemplates as $linkedTemplate) {
            if (!$linkedTemplate instanceof TaskTemplate) {
                continue;
            }

            $linkedTemplate->update([
                'name' => $globalTemplate->name,
                'description' => $globalTemplate->description,
                'source_template_id' => $globalTemplate->id,
                'source_updated_at' => $globalTemplate->updated_at,
            ]);

            $linkedTemplate->templateTasks()->delete();

            foreach ($globalTemplate->templateTasks as $index => $task) {
                TemplateTask::create([
                    'template_id' => $linkedTemplate->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'instructions' => $task->instructions,
                    'required_proof_type' => $task->required_proof_type,
                    'is_required' => (bool) $task->is_required,
                    'checklist_items' => $task->checklist_items,
                    'attachments' => $task->attachments,
                    'validation_rules' => $task->validation_rules,
                    'start_time' => $task->start_time,
                    'end_time' => $task->end_time,
                    'sort_order' => $index,
                    'is_active' => true,
                ]);
            }

            $linkedTemplate->load('templateTasks');
            $linkedTemplate->syncToLists();
        }
    }

    private function createGlobalTemplate(array $payload): TaskTemplate
    {
        $payload['company_id'] = null;

        return TaskTemplate::withoutEvents(function () use ($payload) {
            return TaskTemplate::withoutGlobalScopes()->create($payload);
        });
    }

    private function createCompanyTemplateFromGlobal(TaskTemplate $globalTemplate, Company $company): TaskTemplate
    {
        $companyTemplate = TaskTemplate::withoutGlobalScopes()->create([
            'name' => $globalTemplate->name,
            'description' => $globalTemplate->description,
            'is_active' => true,
            'company_id' => $company->id,
            'source_template_id' => $globalTemplate->id,
            'source_updated_at' => $globalTemplate->updated_at,
        ]);

        foreach ($globalTemplate->templateTasks as $task) {
            TemplateTask::create([
                'template_id' => $companyTemplate->id,
                'title' => $task->title,
                'description' => $task->description,
                'instructions' => $task->instructions,
                'required_proof_type' => $task->required_proof_type,
                'is_required' => (bool) $task->is_required,
                'checklist_items' => $task->checklist_items,
                'attachments' => $task->attachments,
                'validation_rules' => $task->validation_rules,
                'start_time' => $task->start_time,
                'end_time' => $task->end_time,
                'sort_order' => $task->sort_order,
                'is_active' => true,
            ]);
        }

        return $companyTemplate;
    }

    private function hydrateGlobalTemplateChecklistData(TaskTemplate $template): void
    {
        $hasChecklistData = $template->templateTasks()
            ->whereNotNull('checklist_items')
            ->exists();

        if ($hasChecklistData) {
            return;
        }

        $sourceTemplate = TaskTemplate::withoutGlobalScopes()
            ->whereNotNull('company_id')
            ->where('name', $template->name)
            ->whereHas('templateTasks', fn ($q) => $q->whereNotNull('checklist_items'))
            ->with('templateTasks')
            ->latest('id')
            ->first();

        if (!$sourceTemplate) {
            return;
        }

        $template->update([
            'description' => $sourceTemplate->description ?: $template->description,
        ]);

        $template->templateTasks()->delete();

        foreach ($sourceTemplate->templateTasks as $index => $task) {
            TemplateTask::create([
                'template_id' => $template->id,
                'title' => $task->title,
                'description' => $task->description,
                'instructions' => $task->instructions,
                'required_proof_type' => $task->required_proof_type ?: 'photo',
                'is_required' => (bool) $task->is_required,
                'checklist_items' => $task->checklist_items,
                'start_time' => $task->start_time,
                'end_time' => $task->end_time,
                'sort_order' => $index,
                'is_active' => true,
            ]);
        }
    }
}


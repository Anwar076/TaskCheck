<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\TaskCheckNotificationMail;
use App\Models\Company;
use App\Models\Notification;
use App\Models\TaskTemplate;
use App\Models\TemplateTask;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

    public function destroy($template): RedirectResponse
    {
        $template = TaskTemplate::withoutGlobalScopes()->findOrFail($template);
        abort_if($template->company_id !== null, 404);

        $template->templateTasks()->delete();
        $template->delete();

        return redirect()->route('super-admin.templates.index')
            ->with('success', 'Template verwijderd.');
    }

    public function aiImportPage()
    {
        return view('super-admin.templates.ai-import');
    }

    public function aiImportGenerate(Request $request): JsonResponse
    {
        $request->validate([
            'prompt'       => 'nullable|string|max:4000',
            'company_type' => 'nullable|in:cleaning,horeca,other',
            'source_file'  => 'nullable|file|max:12288|mimes:pdf,doc,docx,jpg,jpeg,png,webp',
        ]);

        $apiKey = Config::get('services.openai.key');
        $model  = Config::get('services.openai.model', 'gpt-4.1-mini');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'AI is niet geconfigureerd (OPENAI_API_KEY ontbreekt).',
            ], 422);
        }

        $prompt      = trim((string) ($request->input('prompt') ?? ''));
        $companyType = $request->input('company_type', 'other');
        $file        = $request->file('source_file');

        if ($prompt === '' && !$file) {
            return response()->json([
                'success' => false,
                'message' => 'Geef een beschrijving of upload een bestand.',
            ], 422);
        }

        $messages = [];
        $typeLabel = match ($companyType) {
            'cleaning' => 'schoonmaakbedrijven',
            'horeca'   => 'horeca (restaurants, hotels, cafés)',
            default    => 'algemene bedrijven',
        };

        $systemPrompt = <<<PROMPT
Je bent een Nederlandse assistent die documenten en beschrijvingen omzet naar professionele taaksjablonen voor TaskCheck.

Dit sjabloon is specifiek bedoeld voor: {$typeLabel}.
Gebruik "target_company_type": "{$companyType}" in ALLE sjablonen die je genereert.

Lees de aangeleverde tekst en/of afbeelding en genereer 1 of meerdere complete sjablonen.
Output ALLEEN JSON in exact dit formaat:
{
  "templates": [
    {
      "name": "string",
      "description": "string",
      "target_company_type": "{$companyType}",
      "tasks": [
        {
          "title": "string",
          "description": "string",
          "instructions": "string",
          "required_proof_type": "none|photo|video|text|file|any",
          "is_required": true,
          "checklist_items": ["string", "string"],
          "start_time": "HH:MM or null",
          "end_time": "HH:MM or null"
        }
      ]
    }
  ]
}

Regels:
- Max 5 sjablonen.
- Max 20 taken per sjabloon.
- Kort en praktisch Nederlands, afgestemd op {$typeLabel}.
- Gebruik altijd "target_company_type": "{$companyType}".
- Kies per taak logisch bewijs type:
  - photo voor zichtbaar resultaat (bv schoonmaak, controle op uiterlijk),
  - text voor korte toelichting of metingen,
  - file voor document-bewijs,
  - none als geen bewijs nodig is.
- Gebruik checklist_items wanneer subcontroles logisch zijn (2-8 items per taak).
- Stel start_time/end_time in als het document tijdvensters vermeldt, anders null.
PROMPT;

        $messages[] = [
            'role'    => 'system',
            'content' => $systemPrompt,
        ];

        $content = [];
        if ($prompt !== '') {
            $content[] = ['type' => 'text', 'text' => "Context:\n" . $prompt];
        }

        if ($file) {
            $ext     = strtolower($file->getClientOriginalExtension());
            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);

            if ($isImage) {
                $bytes = file_get_contents($file->getRealPath());
                $mime  = $file->getMimeType() ?: 'image/png';
                $content[] = [
                    'type'      => 'image_url',
                    'image_url' => ['url' => 'data:' . $mime . ';base64,' . base64_encode($bytes)],
                ];
                $content[] = ['type' => 'text', 'text' => 'Gebruik de afbeelding om taken en checklistpunten te herkennen en zet die om naar sjablonen.'];
            } else {
                $extractedText = $this->extractAiImportSourceText($file);
                if (trim($extractedText) === '') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kon onvoldoende tekst uit dit bestand halen. Probeer een duidelijkere PDF/DOCX of voeg extra context toe.',
                    ], 422);
                }
                $content[] = ['type' => 'text', 'text' => "Documenttekst:\n" . mb_substr($extractedText, 0, 12000)];
            }
        }

        if (empty($content)) {
            $content[] = ['type' => 'text', 'text' => 'Maak een algemeen taaksjabloon op basis van de context.'];
        }

        $messages[] = ['role' => 'user', 'content' => $content];

        try {
            $response = Http::withToken($apiKey)
                ->timeout(60)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model'           => $model,
                    'response_format' => ['type' => 'json_object'],
                    'messages'        => $messages,
                ]);

            if (!$response->ok()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI-verzoek mislukt: ' . $response->body(),
                ], 500);
            }

            $contentText = $response->json('choices.0.message.content');
            $decoded     = is_string($contentText) ? json_decode($contentText, true) : null;

            if (!is_array($decoded) || !isset($decoded['templates']) || !is_array($decoded['templates'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI gaf geen geldig sjabloon-formaat terug.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'data'    => ['templates' => $this->normalizeAiTemplates($decoded['templates'], $companyType)],
            ]);
        } catch (\Throwable $e) {
            Log::error('Super admin AI template import mislukt', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'AI-import is mislukt: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function aiImportStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'import_payload'    => 'required|string',
            'selected_indices'  => 'required|array|min:1',
            'selected_indices.*' => 'integer|min:0',
        ]);

        $payload = json_decode($validated['import_payload'], true);
        if (!is_array($payload) || !isset($payload['templates']) || !is_array($payload['templates'])) {
            return redirect()->back()->with('error', 'Ongeldige import-payload.');
        }

        $createdCount = 0;

        DB::transaction(function () use ($payload, $validated, &$createdCount) {
            foreach ($validated['selected_indices'] as $idx) {
                $tplData = $payload['templates'][$idx] ?? null;
                if (!is_array($tplData)) {
                    continue;
                }

                $templatePayload = [
                    'name'        => $tplData['name'] ?? 'AI sjabloon',
                    'description' => $tplData['description'] ?? null,
                    'is_active'   => true,
                    'company_id'  => null,
                ];

                if (Schema::hasColumn('task_templates', 'target_company_type')) {
                    $templatePayload['target_company_type'] = $tplData['target_company_type'] ?? 'other';
                }

                $template = $this->createGlobalTemplate($templatePayload);

                foreach (($tplData['tasks'] ?? []) as $index => $taskData) {
                    TemplateTask::create([
                        'template_id'         => $template->id,
                        'title'               => $taskData['title'] ?? 'Taak',
                        'description'         => $taskData['description'] ?? null,
                        'instructions'        => $taskData['instructions'] ?? null,
                        'required_proof_type' => $taskData['required_proof_type'] ?? 'none',
                        'is_required'         => (bool) ($taskData['is_required'] ?? true),
                        'checklist_items'     => !empty($taskData['checklist_items']) ? $taskData['checklist_items'] : null,
                        'start_time'          => !empty($taskData['start_time']) ? $taskData['start_time'] : null,
                        'end_time'            => !empty($taskData['end_time']) ? $taskData['end_time'] : null,
                        'sort_order'          => $index,
                        'is_active'           => true,
                    ]);
                }

                $createdCount++;
            }
        });

        return redirect()->route('super-admin.templates.index')
            ->with('success', "{$createdCount} sjabloon(en) aangemaakt via AI. Controleer en publiceer ze.");
    }

    private function normalizeAiTemplates(array $templates, string $forcedType = 'other'): array
    {
        $allowedTypes       = ['cleaning', 'horeca', 'other'];
        $allowedProofTypes  = ['none', 'photo', 'video', 'text', 'file', 'any'];

        $normalized = [];
        foreach ($templates as $tpl) {
            if (!is_array($tpl)) {
                continue;
            }

            $tasks = [];
            foreach (($tpl['tasks'] ?? []) as $task) {
                if (!is_array($task)) {
                    continue;
                }
                $tasks[] = [
                    'title'               => trim((string) ($task['title'] ?? '')) ?: 'Taak',
                    'description'         => trim((string) ($task['description'] ?? '')),
                    'instructions'        => trim((string) ($task['instructions'] ?? '')),
                    'required_proof_type' => in_array($task['required_proof_type'] ?? 'none', $allowedProofTypes) ? $task['required_proof_type'] : 'none',
                    'is_required'         => (bool) ($task['is_required'] ?? true),
                    'checklist_items'     => is_array($task['checklist_items'] ?? null)
                        ? collect($task['checklist_items'])->map(fn ($i) => trim((string) $i))->filter()->values()->all()
                        : [],
                    'start_time' => !empty($task['start_time']) && preg_match('/^\d{2}:\d{2}$/', $task['start_time']) ? $task['start_time'] : null,
                    'end_time'   => !empty($task['end_time']) && preg_match('/^\d{2}:\d{2}$/', $task['end_time']) ? $task['end_time'] : null,
                ];
            }

            $normalized[] = [
                'name'                => trim((string) ($tpl['name'] ?? '')) ?: 'AI Sjabloon',
                'description'         => trim((string) ($tpl['description'] ?? '')),
                'target_company_type' => in_array($forcedType, $allowedTypes) ? $forcedType : 'other',
                'tasks'               => $tasks,
            ];
        }

        return $normalized;
    }

    private function extractAiImportSourceText(\Illuminate\Http\UploadedFile $file): string
    {
        $ext  = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();

        if ($ext === 'pdf') {
            if (class_exists(\Smalot\PdfParser\Parser::class)) {
                try {
                    $parser = new \Smalot\PdfParser\Parser();
                    $pdf    = $parser->parseFile($path);
                    $text   = $pdf->getText();
                    if (trim($text) !== '') {
                        return $text;
                    }
                } catch (\Throwable) {
                }
            }
            return $this->extractPdfTextFallback($path);
        }

        if (in_array($ext, ['doc', 'docx'])) {
            return $this->extractDocxText($path);
        }

        return '';
    }

    private function extractPdfTextFallback(string $path): string
    {
        $content = (string) file_get_contents($path);
        preg_match_all('/\(([^)]{2,200})\)/', $content, $matches);
        $text = implode(' ', $matches[1] ?? []);
        return trim((string) preg_replace('/\s+/', ' ', $text));
    }

    private function extractDocxText(string $path): string
    {
        if (!class_exists(\ZipArchive::class)) {
            return '';
        }
        $zip = new \ZipArchive();
        if ($zip->open($path) !== true) {
            return '';
        }
        $xml = (string) $zip->getFromName('word/document.xml');
        $zip->close();
        if ($xml === '') {
            return '';
        }
        $text = strip_tags(str_replace('</w:p>', "\n", $xml));
        return trim((string) preg_replace('/\s+/', ' ', $text));
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


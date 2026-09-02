<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Checklist\TaskList;
use App\Models\Organisation\Company;
use App\Services\Ai\AiUsageLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class AiChecklistImportController extends Controller
{
    public function aiGenerate(Request $request)
    {
        $this->ensurePlanFeatureAvailable();

        $validated = $request->validate([
            'prompt' => 'nullable|string|max:2000',
            'source_file' => 'nullable|file|max:8192|mimes:jpg,jpeg,png,webp,pdf',
        ]);

        $apiKey = Config::get('services.openai.key');
        $model = Config::get('services.openai.model', 'gpt-4.1-mini');

        if (! $apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'AI is niet geconfigureerd (OPENAI_API_KEY ontbreekt).',
            ], 422);
        }

        $hasPrompt = ! empty($validated['prompt']);
        $hasFile = $request->hasFile('source_file');

        if (! $hasPrompt && ! $hasFile) {
            return response()->json([
                'success' => false,
                'message' => 'Geef een korte beschrijving of upload een bestand.',
            ], 422);
        }

        $fileUrl = null;
        $fileType = null;

        if ($hasFile) {
            $file = $request->file('source_file');
            $fileType = strtolower($file->getClientOriginalExtension());

            // Voor nu ondersteunen we alleen echte afbeeldingen voor AI-visie
            if (! in_array($fileType, ['jpg', 'jpeg', 'png', 'webp'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Alleen afbeeldingsbestanden (jpg, jpeg, png, webp) worden momenteel ondersteund voor AI-lijstbouw. PDF/Word volgt later.',
                ], 422);
            }

            // In een lokale ontwikkelomgeving (127.0.0.1 / localhost) kan OpenAI deze URL niet bereiken.
            // Daarom blokkeren we fotogebruik lokaal en vragen we om alleen tekst te gebruiken.
            $appUrl = config('app.url');
            if ($appUrl && (str_contains($appUrl, '127.0.0.1') || str_contains($appUrl, 'localhost'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Foto-gebaseerde AI lijstbouw werkt niet in de lokale omgeving. Gebruik hier een tekstbeschrijving; in productie met een publiek bereikbare URL kan de foto wel worden gelezen.',
                ], 422);
            }

            $path = $file->store('ai-list-sources', 'public');
            $fileUrl = asset('storage/'.$path);
        }

        $systemPrompt = <<<'PROMPT'
Je bent een Nederlandse assistent die op basis van een korte beschrijving of een foto van een papieren checklist een digitale takenlijst maakt.

Je taak:
- Bedenk een heldere titel voor de lijst.
- Schrijf een korte beschrijving (1-3 zinnen) in het Nederlands.
- Bedenk een korte categorie (bijv. "Schoonmaak", "Veiligheid", "Keuken", "Kantoor").
- Haal uit de tekst/foto de afzonderlijke taken en maak daarvan een reeks concrete taken.
- Per taak: geef een korte titel en optionele korte toelichting.

Geef je ANTWOORD ALLEEN als JSON in dit formaat:
{
  "title": "lijsttitel",
  "description": "korte beschrijving",
  "category": "categorie of leeg",
  "tasks": [
    {
      "title": "taaktitel",
      "description": "optionele korte toelichting of leeg"
    }
  ]
}

Maak maximaal 25 taken. Schrijf alles in duidelijk, praktisch Nederlands.
PROMPT;

        $userParts = [];
        if ($hasPrompt) {
            $userParts[] = "Beschrijving van de lijst:\n".$validated['prompt'];
        }
        if ($hasFile && $fileUrl) {
            $userParts[] = 'Gebruik ook de informatie van de meegestuurde foto van een checklist.';
        }

        $messages = [
            ['role' => 'system', 'content' => $systemPrompt],
        ];

        if ($hasFile && $fileUrl) {
            $messages[] = [
                'role' => 'user',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => implode("\n\n", $userParts),
                    ],
                    [
                        'type' => 'image_url',
                        'image_url' => [
                            'url' => $fileUrl,
                        ],
                    ],
                ],
            ];
        } else {
            $messages[] = [
                'role' => 'user',
                'content' => implode("\n\n", $userParts),
            ];
        }

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
                ->timeout(30)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'response_format' => ['type' => 'json_object'],
                    'messages' => $messages,
                ]);

            if (! $response->ok()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI-verzoek mislukt: '.$response->body(),
                ], 500);
            }

            AiUsageLogger::logChatCompletion(
                $response,
                AiUsageLogger::FEATURE_LIST_AI_GENERATE,
                auth()->user()->company_id,
                auth()->id(),
                $model
            );

            $content = $response->json('choices.0.message.content');
            $decoded = is_string($content) ? json_decode($content, true) : null;

            if (! is_array($decoded)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ongeldig AI-antwoord ontvangen.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'title' => $decoded['title'] ?? '',
                    'description' => $decoded['description'] ?? '',
                    'category' => $decoded['category'] ?? '',
                    'tasks' => $decoded['tasks'] ?? [],
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error('AI list generate failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'AI-verzoek is mislukt: '.$e->getMessage(),
            ], 500);
        }
    }

    public function aiImportPage(?Company $company = null)
    {
        if (! $company) {
            $this->ensurePlanFeatureAvailable();
        }

        return view('admin.lists.ai-import', compact('company'));
    }

    public function aiImportGenerate(Request $request, ?Company $company = null)
    {
        if (! $company) {
            $this->ensurePlanFeatureAvailable();
        }

        $validated = $request->validate([
            'prompt' => 'nullable|string|max:4000',
            // The UI keeps multi-upload UX but sends each document separately.
            // This bounds latency and prevents a single oversized AI generation.
            'source_files' => 'nullable|array|max:1',
            'source_files.*' => 'file|max:12288|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,webp',
        ]);

        $apiKey = Config::get('services.openai.key');
        $model = Config::get('services.openai.model', 'gpt-4.1-mini');

        if (! $apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'AI is niet geconfigureerd (OPENAI_API_KEY ontbreekt).',
            ], 422);
        }

        $prompt = trim((string) ($validated['prompt'] ?? ''));
        $files = array_values(array_filter((array) $request->file('source_files', [])));
        if ($prompt === '' && empty($files)) {
            return response()->json([
                'success' => false,
                'message' => 'Geef een beschrijving of upload maximaal 5 bestanden.',
            ], 422);
        }

        $messages = [];
        $messages[] = [
            'role' => 'system',
            'content' => <<<'PROMPT'
Je bent een nauwkeurige Nederlandse assistent die operationele documenten omzet naar digitale takenlijsten.

Maak voor ieder aangeleverd document precies één lijst, in dezelfde volgorde als de documenten. Splits een document nooit op in meerdere lijsten en voeg verschillende documenten nooit samen. Als er geen document is, maak precies één lijst van de gebruikersbeschrijving.
Output ALLEEN JSON in exact dit formaat:
{
  "lists": [
    {
      "title": "string",
      "description": "string",
      "category": "string",
      "priority": "low|medium|high|urgent",
      "schedule_type": "once|daily|weekly|monthly|custom",
      "tasks": [
        {
          "title": "string",
          "description": "string",
          "required_proof_type": "none|photo|video|text|file|any",
          "is_required": true,
          "requires_signature": false,
          "checklist_items": ["string", "string"]
        }
      ]
    }
  ]
}

Regels:
- Het aantal lijsten moet exact gelijk zijn aan het aantal aangeleverde documenten.
- Neem als title exact de opgegeven gewenste lijstnaam over.
- Max 40 taken per lijst.
- Gebruik iedere herkenbare taakregel of ieder vinkje precies één keer als afzonderlijke taak. Sla niets over en voeg geen nieuwe werkzaamheden toe.
- Houd de taaknaam kort. Plaats uitleg, aantallen, tijden, temperaturen, dagverdelingen en voorwaarden volledig in description.
- Behoud belangrijke termen en corrigeer alleen onmiskenbare HTML-codes of evidente woordafbrekingen. Ga niet raden als de bron onduidelijk is.
- Gebruik schedule_type daily voor operationele dag-, openings-, sluitings-, schoonmaak-, voorbereidings- en bijvullijsten, tenzij de bron of gebruiker expliciet iets anders zegt.
- Gebruik priority medium als de bron geen prioriteit noemt.
- Gebruik standaard required_proof_type none. Kies alleen photo als de bron of gebruiker expliciet om een foto/zichtbaar bewijs vraagt, text als werkelijk een waarde of toelichting geregistreerd moet worden, en file als een document moet worden aangeleverd.
- Zet is_required op true voor reguliere en kritieke taken. Voorwaardelijke taken blijven taken; vermeld de voorwaarde duidelijk in description.
- Zet requires_signature alleen op true wanneer de bron expliciet om een handtekening, paraaf of akkoord vraagt.
- Gebruik checklist_items alleen voor echte subhandelingen of meerdere controlepunten (2-8 items), niet om de taakomschrijving te herhalen.
PROMPT,
        ];

        $userParts = [];
        if ($prompt !== '') {
            $userParts[] = "Extra context van gebruiker:\n".$prompt;
        }

        $content = [];
        if (! empty($userParts)) {
            $content[] = [
                'type' => 'text',
                'text' => implode("\n\n", $userParts),
            ];
        }

        $documentTitles = [];
        foreach ($files as $index => $file) {
            $ext = strtolower($file->getClientOriginalExtension());
            $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']);
            $title = Str::limit(trim((string) pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)), 255, '');
            $title = $title !== '' ? $title : 'Geïmporteerde lijst '.($index + 1);
            $documentTitles[] = $title;

            $content[] = [
                'type' => 'text',
                'text' => 'DOCUMENT '.($index + 1)." — gewenste lijstnaam: {$title}",
            ];

            if ($isImage) {
                $bytes = file_get_contents($file->getRealPath());
                $mime = $file->getMimeType() ?: 'image/png';
                $content[] = [
                    'type' => 'image_url',
                    'image_url' => [
                        'url' => 'data:'.$mime.';base64,'.base64_encode($bytes),
                    ],
                ];
                $content[] = [
                    'type' => 'text',
                    'text' => 'Lees uitsluitend de direct voorafgaande afbeelding als document '.($index + 1).'.',
                ];
            } else {
                $extractedText = $this->extractImportSourceText($file);
                if (trim($extractedText) === '') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kon onvoldoende tekst halen uit '.$file->getClientOriginalName().'. Probeer een duidelijker bestand of voeg extra context toe.',
                    ], 422);
                }
                $content[] = [
                    'type' => 'text',
                    'text' => 'Tekst van document '.($index + 1).":\n".mb_substr($extractedText, 0, 12000),
                ];
            }
        }

        if (empty($content)) {
            $content[] = ['type' => 'text', 'text' => 'Maak een algemene takenlijst op basis van de context.'];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $content,
        ];

        try {
            // Stay below the common 60-second nginx/FastCGI limit. The browser
            // submits one document per request so one slow file cannot block all imports.
            $timeout = max(15, min(50, (int) Config::get('services.openai.ai_import_timeout', 45)));
            $maxTokens = max(1000, min(12000, (int) Config::get('services.openai.ai_import_max_tokens', 8000)));
            $response = \Illuminate\Support\Facades\Http::withToken($apiKey)
                ->connectTimeout(10)
                ->timeout($timeout)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $model,
                    'response_format' => ['type' => 'json_object'],
                    'max_tokens' => $maxTokens,
                    'messages' => $messages,
                ]);

            if (! $response->ok()) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI-verzoek mislukt: '.$response->body(),
                ], 500);
            }

            AiUsageLogger::logChatCompletion(
                $response,
                AiUsageLogger::FEATURE_LIST_AI_IMPORT,
                $company?->id ?? auth()->user()->company_id,
                auth()->id(),
                $model
            );

            $contentText = $response->json('choices.0.message.content');
            $decoded = is_string($contentText) ? json_decode($contentText, true) : null;

            if (! is_array($decoded) || ! isset($decoded['lists']) || ! is_array($decoded['lists'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI gaf geen geldig lijst-formaat terug.',
                ], 500);
            }

            if (! empty($documentTitles) && count($decoded['lists']) !== count($documentTitles)) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI maakte niet voor ieder bestand precies één lijst. Probeer het opnieuw of upload minder bestanden tegelijk.',
                ], 500);
            }

            $lists = $this->normalizeAiImportLists($decoded['lists']);
            foreach ($documentTitles as $index => $title) {
                $lists[$index]['title'] = $title;
                $lists[$index]['schedule_type'] = 'daily';
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'lists' => $lists,
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error('AI import generate failed', [
                'error' => $e->getMessage(),
                'exception' => $e::class,
                'company_id' => $company?->id ?? auth()->user()?->company_id,
                'file_count' => count($files),
                'model' => $model,
            ]);

            return response()->json([
                'success' => false,
                'message' => $e instanceof \Illuminate\Http\Client\ConnectionException
                    ? 'OpenAI reageerde niet op tijd. Probeer dit document opnieuw; andere documenten kunnen gewoon doorgaan.'
                    : 'AI-import is mislukt. Probeer dit document opnieuw.',
            ], $e instanceof \Illuminate\Http\Client\ConnectionException ? 504 : 500);
        }
    }

    public function aiImportStore(Request $request, ?Company $company = null)
    {
        if (! $company) {
            $this->ensurePlanFeatureAvailable();
        }

        $validated = $request->validate([
            'import_payload' => 'required|string',
            'selected_indices' => 'required|array|min:1',
            'selected_indices.*' => 'integer|min:0',
        ]);

        $payload = json_decode($validated['import_payload'], true);
        if (! is_array($payload) || ! isset($payload['lists']) || ! is_array($payload['lists'])) {
            return redirect()->back()->with('error', 'Ongeldige import-payload.');
        }

        $allowedPriority = ['low', 'medium', 'high', 'urgent'];
        $allowedSchedule = ['once', 'daily', 'weekly', 'monthly', 'custom'];
        $allowedProofTypes = ['none', 'photo', 'video', 'text', 'file', 'any'];

        $createdLists = 0;
        $createdTasks = 0;

        foreach ($validated['selected_indices'] as $idx) {
            if (! isset($payload['lists'][$idx]) || ! is_array($payload['lists'][$idx])) {
                continue;
            }
            $item = $payload['lists'][$idx];
            $title = trim((string) ($item['title'] ?? ''));
            if ($title === '') {
                continue;
            }

            $requestedPriority = (string) ($item['priority'] ?? 'medium');
            $requestedSchedule = (string) ($item['schedule_type'] ?? 'once');
            $priority = in_array($requestedPriority, $allowedPriority, true) ? $requestedPriority : 'medium';
            $scheduleType = in_array($requestedSchedule, $allowedSchedule, true) ? $requestedSchedule : 'once';

            $list = TaskList::create([
                'title' => $title,
                'description' => trim((string) ($item['description'] ?? '')) ?: null,
                'category' => trim((string) ($item['category'] ?? '')) ?: null,
                'priority' => $priority,
                'schedule_type' => $scheduleType,
                'due_date' => null,
                'parent_list_id' => null,
                'requires_signature' => false,
                'is_template' => false,
                'is_active' => true,
                'schedule_config' => null,
                'created_by' => auth()->id(),
                'company_id' => $company?->id ?? auth()->user()->company_id,
            ]);
            $createdLists++;

            $tasks = is_array($item['tasks'] ?? null) ? $item['tasks'] : [];
            $order = 1;
            foreach ($tasks as $taskItem) {
                if (! is_array($taskItem)) {
                    continue;
                }
                $taskTitle = trim((string) ($taskItem['title'] ?? ''));
                if ($taskTitle === '') {
                    continue;
                }

                $proofType = (string) ($taskItem['required_proof_type'] ?? 'none');
                \App\Models\Checklist\Task::create([
                    'list_id' => $list->id,
                    'title' => $taskTitle,
                    'description' => trim((string) ($taskItem['description'] ?? '')) ?: null,
                    'instructions' => null,
                    'checklist_items' => $this->normalizeChecklistItems($taskItem['checklist_items'] ?? null),
                    'required_proof_type' => in_array($proofType, $allowedProofTypes, true) ? $proofType : 'none',
                    'is_required' => (bool) ($taskItem['is_required'] ?? false),
                    'attachments' => [],
                    'validation_rules' => [],
                    'start_time' => null,
                    'end_time' => null,
                    'order_index' => $order,
                    'order' => $order,
                    'created_by' => auth()->id(),
                    'weekday' => null,
                    'requires_signature' => (bool) ($taskItem['requires_signature'] ?? false),
                ]);
                $order++;
                $createdTasks++;
            }
        }

        $redirect = $company
            ? redirect()->route('super-admin.companies.show', $company)
            : redirect()->route('admin.lists.index');

        return $redirect
            ->with('success', "AI-import voltooid: {$createdLists} lijst(en) en {$createdTasks} taak/taken aangemaakt.");
    }

    private function extractImportSourceText(\Illuminate\Http\UploadedFile $file): string
    {
        $ext = strtolower($file->getClientOriginalExtension());
        $path = $file->getRealPath();
        if (! $path) {
            return '';
        }

        if ($ext === 'pdf') {
            return $this->extractPdfTextFallback($path);
        }
        if ($ext === 'docx') {
            return $this->extractDocxText($path);
        }
        if (in_array($ext, ['xlsx', 'xls'])) {
            return $this->extractXlsxText($path);
        }
        if ($ext === 'doc') {
            return '';
        }

        return (string) file_get_contents($path);
    }

    private function normalizeAiImportLists(array $lists): array
    {
        $allowedPriority = ['low', 'medium', 'high', 'urgent'];
        $allowedSchedule = ['once', 'daily', 'weekly', 'monthly', 'custom'];
        $allowedProofTypes = ['none', 'photo', 'video', 'text', 'file', 'any'];

        $normalized = [];
        foreach ($lists as $list) {
            if (! is_array($list)) {
                continue;
            }

            $tasks = [];
            foreach (array_slice((array) ($list['tasks'] ?? []), 0, 40) as $task) {
                if (! is_array($task)) {
                    continue;
                }
                $title = trim((string) ($task['title'] ?? ''));
                if ($title === '') {
                    continue;
                }
                $proofType = (string) ($task['required_proof_type'] ?? 'none');
                $tasks[] = [
                    'title' => $title,
                    'description' => trim((string) ($task['description'] ?? '')),
                    'required_proof_type' => in_array($proofType, $allowedProofTypes, true) ? $proofType : 'none',
                    'is_required' => (bool) ($task['is_required'] ?? false),
                    'requires_signature' => (bool) ($task['requires_signature'] ?? false),
                    'checklist_items' => $this->normalizeChecklistItems($task['checklist_items'] ?? null) ?? [],
                ];
            }

            $priority = (string) ($list['priority'] ?? 'medium');
            $scheduleType = (string) ($list['schedule_type'] ?? 'once');
            $normalized[] = [
                'title' => trim((string) ($list['title'] ?? '')) ?: 'Nieuwe AI lijst',
                'description' => trim((string) ($list['description'] ?? '')),
                'category' => trim((string) ($list['category'] ?? '')),
                'priority' => in_array($priority, $allowedPriority, true) ? $priority : 'medium',
                'schedule_type' => in_array($scheduleType, $allowedSchedule, true) ? $scheduleType : 'once',
                'tasks' => $tasks,
            ];
        }

        return $normalized;
    }

    private function normalizeChecklistItems($items): ?array
    {
        if (! is_array($items)) {
            return null;
        }

        $clean = [];
        foreach ($items as $item) {
            $value = trim((string) $item);
            if ($value !== '') {
                $clean[] = $value;
            }
        }

        return empty($clean) ? null : array_values($clean);
    }

    private function extractPdfTextFallback(string $path): string
    {
        $content = (string) file_get_contents($path);
        preg_match_all('/\(([^)]{2,200})\)/', $content, $matches);
        $chunks = $matches[1] ?? [];
        $text = implode(' ', $chunks);
        $text = preg_replace('/\s+/', ' ', (string) $text);

        return trim((string) $text);
    }

    private function extractDocxText(string $path): string
    {
        if (! class_exists(\ZipArchive::class)) {
            return '';
        }

        $zip = new \ZipArchive;
        if ($zip->open($path) !== true) {
            return '';
        }

        $xml = (string) $zip->getFromName('word/document.xml');
        $zip->close();
        if ($xml === '') {
            return '';
        }

        $xml = str_replace(['</w:tc>', '</w:tr>', '</w:p>'], ["\t", "\n", "\n"], $xml);
        $text = html_entity_decode(strip_tags($xml), ENT_QUOTES | ENT_XML1, 'UTF-8');
        $text = preg_replace('/[ \t]+/', ' ', (string) $text);
        $text = preg_replace('/\s*\n\s*/', "\n", (string) $text);

        return trim((string) $text);
    }

    private function extractXlsxText(string $path): string
    {
        if (! class_exists(\ZipArchive::class)) {
            return '';
        }

        $zip = new \ZipArchive;
        if ($zip->open($path) !== true) {
            return '';
        }

        $sharedStringsXml = (string) $zip->getFromName('xl/sharedStrings.xml');
        $sharedStrings = [];
        if ($sharedStringsXml !== '') {
            $sx = @simplexml_load_string($sharedStringsXml);
            if ($sx && isset($sx->si)) {
                foreach ($sx->si as $item) {
                    $sharedStrings[] = trim((string) $item->t);
                }
            }
        }

        $textParts = [];
        for ($i = 1; $i <= 5; $i++) {
            $sheetXml = (string) $zip->getFromName("xl/worksheets/sheet{$i}.xml");
            if ($sheetXml === '') {
                continue;
            }
            $sheet = @simplexml_load_string($sheetXml);
            if (! $sheet || ! isset($sheet->sheetData->row)) {
                continue;
            }
            foreach ($sheet->sheetData->row as $row) {
                foreach ($row->c as $cell) {
                    $type = (string) ($cell['t'] ?? '');
                    $raw = (string) ($cell->v ?? '');
                    if ($raw === '') {
                        continue;
                    }
                    if ($type === 's') {
                        $idx = (int) $raw;
                        $textParts[] = $sharedStrings[$idx] ?? '';
                    } else {
                        $textParts[] = $raw;
                    }
                }
            }
        }

        $zip->close();
        $text = implode("\n", array_filter($textParts));
        $text = preg_replace('/\s+/', ' ', (string) $text);

        return trim((string) $text);
    }

    private function ensurePlanFeatureAvailable(): void
    {
        if (! auth()->user()->company?->hasPlanFeature('ai_import')) {
            abort(403, 'AI-import is beschikbaar vanaf Professional.');
        }
    }
}

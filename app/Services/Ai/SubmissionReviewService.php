<?php

namespace App\Services\Ai;

use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class SubmissionReviewService
{
    public function isEnabled(): bool
    {
        return !empty(Config::get('services.openai.key'));
    }

    /**
     * Analyse a submission with AI and return a structured review array.
     *
     * @return array{summary:string,overall_status:string,missing_required_tasks:array,notes:array}
     */
    public function review(Submission $submission): array
    {
        if (!$this->isEnabled()) {
            throw new \RuntimeException('OpenAI is niet geconfigureerd (OPENAI_API_KEY ontbreekt).');
        }

        $submission->loadMissing(['taskList', 'submissionTasks.task']);

        $tasksPayload = [];
        foreach ($submission->submissionTasks as $st) {
            $task = $st->task;

            $fileEntries = [];
            if (is_array($st->proof_files)) {
                foreach ($st->proof_files as $file) {
                    $path = is_array($file) ? ($file['path'] ?? null) : $file;
                    $mime = is_array($file) ? ($file['mime_type'] ?? null) : null;
                    if (!$path) {
                        continue;
                    }
                    // Voor nu: alleen afbeeldingen meesturen naar AI
                    if ($mime && str_starts_with($mime, 'image/') || (!$mime && preg_match('/\.(jpg|jpeg|png|webp)$/i', $path))) {
                        $url = url('storage/' . ltrim($path, '/'));
                        $fileEntries[] = [
                            'url' => $url,
                            'mime_type' => $mime ?? 'image/*',
                            'original_name' => is_array($file) ? ($file['original_name'] ?? basename($path)) : basename($path),
                        ];
                    }
                }
            }

            $tasksPayload[] = [
                'task_id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'instructions' => $task->instructions,
                'is_required' => (bool) ($task->is_required ?? false),
                'required_proof_type' => $task->required_proof_type ?? null,
                'status' => $st->status,
                'proof_text' => $st->proof_text,
                'has_files' => !empty($st->proof_files),
                'image_files' => $fileEntries,
                'checklist_progress' => $st->checklist_progress,
            ];
        }

        $model = Config::get('services.openai.model', 'gpt-4.1-mini');

        $systemPrompt = <<<'PROMPT'
Je bent een kwaliteitscontrole-assistent voor inspectielijsten.
Je taak:
- controleer of ALLE verplichte taken (is_required = true) zijn uitgevoerd (status completed/approved)
- gebruik proof_text, checklist_progress EN de meegegeven image_files per taak om te beoordelen of het bewijs logisch en voldoende lijkt (bijv. schoon/opgeruimd/voltooid of juist rommelig/onaf)
- geef GEEN harde juridische conclusies, maar praktische signalen voor de manager

Geef ANTWOORD ALLEEN als JSON met de velden:
{
  "overall_status": "ok" | "waarschuwing" | "nakijken",
  "summary": "korte Nederlandse samenvatting",
  "missing_required_tasks": [
    {"task_title": "...", "reason": "..."}
  ],
  "task_reviews": [
    {
      "task_id": 123,
      "status": "ok" | "waarschuwing" | "nakijken",
      "comment": "korte NL toelichting over deze taak en bewijs",
      "image_feedback": "optionele opmerking specifiek over de foto's, of leeg als niet relevant"
    }
  ],
  "notes": [
    "korte opmerking 1",
    "korte opmerking 2"
  ]
}
PROMPT;

        $userPayload = [
            'submission' => [
                'id' => $submission->id,
                'list_title' => $submission->taskList->title ?? null,
                'status' => $submission->status,
                'started_at' => optional($submission->started_at)->toIso8601String(),
                'completed_at' => optional($submission->completed_at)->toIso8601String(),
            ],
            'tasks' => $tasksPayload,
        ];

        $response = Http::withToken(Config::get('services.openai.key'))
            ->timeout(20)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    [
                        'role' => 'user',
                        'content' => 'Analyseer deze inzending: ' . json_encode($userPayload, JSON_UNESCAPED_UNICODE),
                    ],
                ],
            ]);

        if (!$response->ok()) {
            throw new \RuntimeException('AI-review mislukt: ' . $response->body());
        }

        AiUsageLogger::logChatCompletion(
            $response,
            AiUsageLogger::FEATURE_SUBMISSION_AI_REVIEW,
            $submission->company_id,
            Auth::id(),
            $model
        );

        $content = $response->json('choices.0.message.content');
        if (!is_string($content)) {
            throw new \RuntimeException('Ongeldig AI-antwoord ontvangen.');
        }

        $decoded = json_decode($content, true);
        if (!is_array($decoded)) {
            throw new \RuntimeException('AI-antwoord kon niet als JSON worden gelezen.');
        }

        return [
            'overall_status' => $decoded['overall_status'] ?? 'nakijken',
            'summary' => $decoded['summary'] ?? '',
            'missing_required_tasks' => $decoded['missing_required_tasks'] ?? [],
            'task_reviews' => $decoded['task_reviews'] ?? [],
            'notes' => $decoded['notes'] ?? [],
            '_raw' => $decoded,
            '_model' => $model,
        ];
    }
}


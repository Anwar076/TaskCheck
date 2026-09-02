<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Platform\IncidentTicket;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class IncidentController extends Controller
{
    public function errorsFeed(): JsonResponse
    {
        return response()->json([
            'errors' => $this->parsedErrors(30),
            'generated_at' => now()->toIso8601String(),
        ]);
    }

    public function createIncidentTicket(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fingerprint' => ['required', 'string', 'max:64'],
            'title' => ['required', 'string', 'max:255'],
            'error_message' => ['required', 'string', 'max:5000'],
            'context' => ['nullable', 'string', 'max:10000'],
            'error_occurred_at' => ['nullable', 'string', 'max:255'],
            'company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'request_url' => ['nullable', 'string', 'max:2048'],
            'http_method' => ['nullable', 'string', 'max:10'],
            'user_agent' => ['nullable', 'string', 'max:2000'],
            'device_type' => ['nullable', 'string', 'max:20'],
            'ip_address' => ['nullable', 'ip'],
        ]);

        $occurredAt = null;
        if (! empty($validated['error_occurred_at'])) {
            try {
                $occurredAt = Carbon::parse($validated['error_occurred_at']);
            } catch (\Throwable) {
                $occurredAt = null;
            }
        }

        $existing = IncidentTicket::query()
            ->where('fingerprint', $validated['fingerprint'])
            ->where('created_at', '>=', now()->subHours(24))
            ->first();

        if ($existing) {
            return response()->json([
                'created' => false,
                'ticket_id' => $existing->id,
                'ticket' => $existing,
                'message' => 'Ticket bestond al.',
            ]);
        }

        $userAgent = $validated['user_agent'] ?? (string) $request->userAgent();
        $deviceType = $validated['device_type'] ?? $this->detectDeviceType($userAgent);

        $ticket = IncidentTicket::create([
            'company_id' => $validated['company_id'] ?? null,
            'reported_by_user_id' => Auth::id(),
            'fingerprint' => $validated['fingerprint'],
            'status' => 'open',
            'title' => $validated['title'],
            'error_message' => $validated['error_message'],
            'context' => $validated['context'] ?? null,
            'request_url' => $validated['request_url'] ?? null,
            'http_method' => isset($validated['http_method']) ? strtoupper($validated['http_method']) : null,
            'user_agent' => $userAgent,
            'device_type' => $deviceType,
            'ip_address' => $validated['ip_address'] ?? $request->ip(),
            'error_occurred_at' => $occurredAt,
        ]);

        return response()->json([
            'created' => true,
            'ticket_id' => $ticket->id,
            'ticket' => $ticket,
            'message' => 'Ticket aangemaakt.',
        ]);
    }

    public function showIncidentTicket(IncidentTicket $incident): JsonResponse
    {
        $displayTimezone = 'Europe/Amsterdam';
        $ticket = $incident->load(['company:id,name', 'reportedBy:id,name,email']);

        $occurredAt = $ticket->error_occurred_at?->copy()->timezone($displayTimezone)?->format('d-m-Y H:i:s');
        $createdAt = $ticket->created_at?->copy()->timezone($displayTimezone)?->format('d-m-Y H:i:s');

        return response()->json([
            'ticket' => $ticket,
            'display' => [
                'occurred_at' => $occurredAt,
                'created_at' => $createdAt,
            ],
        ]);
    }

    public function updateIncidentTicketStatus(Request $request, IncidentTicket $incident): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['open', 'resolved', 'ignored'])],
        ]);

        $incident->update([
            'status' => $validated['status'],
        ]);

        $label = match ($validated['status']) {
            'resolved' => 'afgerond',
            'ignored' => 'gearchiveerd',
            default => 'heropend',
        };

        return redirect()->route('super-admin.dashboard')
            ->with('success', "Ticket #{$incident->id} is {$label}.");
    }

    public function analyzeIncidentTicket(IncidentTicket $incident): JsonResponse
    {
        $apiKey = Config::get('services.openai.key');
        $model = Config::get('services.openai.model', 'gpt-4.1-mini');

        if (! $apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'OPENAI_API_KEY ontbreekt.',
            ], 422);
        }

        $codeContext = $this->collectStackTraceCodeContext($incident->context ?? '');
        $heuristicCodeContext = $this->collectHeuristicCodeContext($incident->error_message ?? '', $incident->context ?? '');

        $systemPrompt = <<<'PROMPT'
Je bent een senior Laravel debugging engineer.
Analyseer incidentdata en geef:
1) vermoedelijke root cause
2) exacte code-locaties (bestandsnamen/functies) waar het waarschijnlijk misgaat
3) korte concrete fix-stappen
4) regressie checks/tests

Schrijf in het Nederlands, compact en technisch.
Als code_context beschikbaar is:
- noem concrete bestanden + regels/functies
- geef een patch-richting (wat exact aanpassen)
- benoem welke checks/tests dit bevestigen
PROMPT;

        $userPayload = [
            'ticket' => [
                'id' => $incident->id,
                'title' => $incident->title,
                'status' => $incident->status,
                'error_message' => $incident->error_message,
                'context' => $incident->context,
                'request_url' => $incident->request_url,
                'http_method' => $incident->http_method,
                'device_type' => $incident->device_type,
                'user_agent' => $incident->user_agent,
            ],
            'code_context' => $codeContext,
            'heuristic_code_context' => $heuristicCodeContext,
        ];

        $response = Http::withToken($apiKey)
            ->timeout(45)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => 'Analyseer dit incident en geef concrete fix-richting: '.json_encode($userPayload, JSON_UNESCAPED_UNICODE)],
                ],
            ]);

        if (! $response->ok()) {
            return response()->json([
                'success' => false,
                'message' => 'AI analyse mislukt: '.$response->body(),
            ], 500);
        }

        $analysis = (string) ($response->json('choices.0.message.content') ?? '');
        $incident->update([
            'ai_analysis' => $analysis,
            'ai_model' => $model,
            'ai_analyzed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'analysis' => $analysis,
            'model' => $model,
            'analyzed_at' => optional($incident->fresh()->ai_analyzed_at)?->toIso8601String(),
        ]);
    }

    private function detectDeviceType(?string $userAgent): string
    {
        $ua = strtolower((string) $userAgent);
        if ($ua === '') {
            return 'unknown';
        }
        if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
            return 'mobile';
        }
        if (str_contains($ua, 'ipad') || str_contains($ua, 'tablet')) {
            return 'tablet';
        }

        return 'desktop';
    }

    private function collectStackTraceCodeContext(string $context): array
    {
        if ($context === '') {
            return [];
        }

        preg_match_all('/((?:[A-Za-z]:\\\\|\/)[^:\n]+\.php|(?:app|routes|database|resources|tests)\/[^:\n]+\.php):(\d+)/', $context, $matches, PREG_SET_ORDER);
        $snippets = [];
        $seen = [];

        foreach ($matches as $match) {
            $rawPath = $match[1] ?? null;
            $line = isset($match[2]) ? (int) $match[2] : null;
            if (! $rawPath || ! $line) {
                continue;
            }
            $filePath = $this->resolveCodePath($rawPath);
            if (! $filePath || isset($seen[$filePath.':'.$line])) {
                continue;
            }
            $seen[$filePath.':'.$line] = true;
            if (! File::exists($filePath)) {
                continue;
            }

            $allLines = @file($filePath);
            if (! is_array($allLines) || empty($allLines)) {
                continue;
            }

            $start = max(1, $line - 8);
            $end = min(count($allLines), $line + 8);
            $chunk = [];
            for ($i = $start; $i <= $end; $i++) {
                $prefix = $i === $line ? '>>' : '  ';
                $chunk[] = $prefix.$i.': '.rtrim((string) ($allLines[$i - 1] ?? ''), "\r\n");
            }

            $snippets[] = [
                'file' => $filePath,
                'line' => $line,
                'snippet' => implode("\n", $chunk),
            ];

            if (count($snippets) >= 5) {
                break;
            }
        }

        return $snippets;
    }

    private function resolveCodePath(string $rawPath): ?string
    {
        $normalized = str_replace('/', DIRECTORY_SEPARATOR, $rawPath);

        if (preg_match('/^[A-Za-z]:\\\\/', $normalized) || str_starts_with($normalized, DIRECTORY_SEPARATOR)) {
            return $normalized;
        }

        $projectPath = base_path($normalized);
        if (File::exists($projectPath)) {
            return $projectPath;
        }

        return null;
    }

    private function collectHeuristicCodeContext(string $errorMessage, string $context): array
    {
        $haystack = trim($errorMessage."\n".$context);
        if ($haystack === '') {
            return [];
        }

        preg_match_all('/\b([A-Z][A-Za-z0-9_]{4,})\b/', $haystack, $matches);
        $keywords = collect($matches[1] ?? [])
            ->map(fn (string $value) => trim($value))
            ->filter(fn (string $value) => ! in_array($value, ['Exception', 'Error', 'Class', 'Illuminate', 'Laravel'], true))
            ->unique()
            ->take(8)
            ->values();

        if ($keywords->isEmpty()) {
            return [];
        }

        $searchRoots = [
            app_path(),
            base_path('routes'),
            base_path('resources/views'),
            base_path('database'),
        ];

        $snippets = [];
        foreach ($searchRoots as $root) {
            if (! File::isDirectory($root)) {
                continue;
            }

            foreach (File::allFiles($root) as $file) {
                if ($file->getExtension() !== 'php' && $file->getExtension() !== 'blade.php') {
                    continue;
                }

                $contents = @file_get_contents($file->getPathname());
                if (! is_string($contents) || $contents === '') {
                    continue;
                }

                foreach ($keywords as $keyword) {
                    if (! str_contains($contents, $keyword)) {
                        continue;
                    }

                    $snippets[] = [
                        'file' => $file->getPathname(),
                        'keyword' => $keyword,
                    ];

                    if (count($snippets) >= 8) {
                        return $snippets;
                    }
                    break;
                }
            }
        }

        return $snippets;
    }

    public function parsedErrors(int $limit = 30): array
    {
        $logPath = storage_path('logs/laravel.log');
        if (! File::exists($logPath)) {
            return [];
        }

        $lines = @file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (! $lines) {
            return [];
        }

        $displayTimezone = 'Europe/Amsterdam';

        return collect($lines)
            ->filter(fn ($line) => str_contains($line, '.ERROR:') || str_contains($line, 'local.ERROR:'))
            ->take(-max($limit * 5, 100))
            ->reverse()
            ->values()
            ->map(function (string $line) use ($displayTimezone) {
                $timestamp = null;
                $level = 'ERROR';
                $message = $line;

                if (preg_match('/^\[(.*?)\]\s+\w+\.([A-Z]+):\s*(.*)$/', $line, $matches)) {
                    $rawTimestamp = trim($matches[1]);
                    try {
                        $timestamp = Carbon::parse($rawTimestamp, 'UTC')
                            ->setTimezone($displayTimezone)
                            ->format('Y-m-d H:i:s');
                    } catch (\Throwable) {
                        $timestamp = $rawTimestamp;
                    }
                    $level = trim($matches[2]);
                    $message = trim($matches[3]);
                }

                $companyId = null;
                if (preg_match('/"company_id"\s*:\s*(\d+)/', $line, $companyMatches)) {
                    $companyId = (int) $companyMatches[1];
                }

                $requestUrl = null;
                if (preg_match('/"url"\s*:\s*"([^"]+)"/', $line, $urlMatches)) {
                    $requestUrl = $urlMatches[1];
                } elseif (preg_match('/https?:\/\/[^\s"]+/', $line, $directUrlMatch)) {
                    $requestUrl = $directUrlMatch[0];
                }

                $httpMethod = null;
                if (preg_match('/"method"\s*:\s*"([A-Z]+)"/', $line, $methodMatches)) {
                    $httpMethod = strtoupper($methodMatches[1]);
                }

                $userAgent = null;
                if (preg_match('/"user_agent"\s*:\s*"([^"]+)"/', $line, $uaMatches)) {
                    $userAgent = $uaMatches[1];
                }

                $deviceType = $this->detectDeviceType($userAgent);

                $safeMessage = str_replace(base_path(), '[app]', mb_substr($message, 0, 1200));
                $safeRaw = str_replace(base_path(), '[app]', $line);
                $signature = preg_replace(['/\{.*$/s', '/\b\d+\b/', '/\s+/'], ['', '#', ' '], $safeMessage);

                return [
                    'fingerprint' => sha1((string) $signature),
                    'timestamp' => $timestamp,
                    'level' => $level,
                    'message' => $safeMessage,
                    'raw' => $safeRaw,
                    'company_id' => $companyId,
                    'request_url' => $requestUrl,
                    'http_method' => $httpMethod,
                    'user_agent' => $userAgent,
                    'device_type' => $deviceType,
                ];
            })
            ->groupBy('fingerprint')
            ->map(function ($items) {
                $latest = $items->first();
                $oldest = $items->last();
                $latest['count'] = $items->count();
                $latest['first_seen'] = $oldest['timestamp'] ?? null;
                $latest['last_seen'] = $latest['timestamp'] ?? null;

                return $latest;
            })
            ->take($limit)
            ->values()
            ->all();
    }
}

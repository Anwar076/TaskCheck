<?php

namespace App\Services\Ai;

use App\Models\Ai\AiUsageLog;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class AiUsageLogger
{
    public const FEATURE_LIST_AI_GENERATE = 'list_ai_generate';

    public const FEATURE_LIST_AI_IMPORT = 'list_ai_import';

    public const FEATURE_TASK_AI_SUGGEST = 'task_ai_suggest';

    public const FEATURE_SUBMISSION_AI_REVIEW = 'submission_ai_review';

    /**
     * Persist token usage from a successful OpenAI chat/completions response.
     */
    public static function logChatCompletion(
        Response $response,
        string $feature,
        ?int $companyId,
        ?int $userId,
        ?string $model = null
    ): void {
        if (!$response->successful()) {
            return;
        }

        if (!$companyId) {
            return;
        }

        try {
            $usage = $response->json('usage');
            if (!is_array($usage)) {
                return;
            }

            $prompt = (int) ($usage['prompt_tokens'] ?? 0);
            $completion = (int) ($usage['completion_tokens'] ?? 0);
            $total = (int) ($usage['total_tokens'] ?? 0);
            if ($total <= 0) {
                $total = $prompt + $completion;
            }
            if ($total <= 0 && $prompt <= 0 && $completion <= 0) {
                return;
            }

            $resolvedModel = $model ?? $response->json('model');

            AiUsageLog::create([
                'company_id' => $companyId,
                'user_id' => $userId,
                'feature' => $feature,
                'model' => is_string($resolvedModel) ? substr($resolvedModel, 0, 80) : null,
                'prompt_tokens' => $prompt,
                'completion_tokens' => $completion,
                'total_tokens' => $total,
            ]);
        } catch (\Throwable $e) {
            Log::warning('AiUsageLogger: kon usage niet wegschrijven', [
                'feature' => $feature,
                'company_id' => $companyId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

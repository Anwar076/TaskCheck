<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Services\Mobile\MobileTaskAccess;
use Illuminate\Http\Request;
class TaskController extends MobileController
{
    public function __construct(
        protected MobileTaskAccess $taskAccess,
    ) {}

    public function complete(Request $request, int $id)
    {
        $submissionTask = $this->taskAccess->findOwnedSubmissionTask($request, $id);

        if (!$submissionTask) {
            return $this->error('Taak niet gevonden.', 404);
        }

        $submission = $submissionTask->submission;
        $task = $submissionTask->task;

        $rules = ['proof_text' => 'nullable|string'];

        if (in_array($task->required_proof_type, ['photo', 'video', 'file', 'any'], true)) {
            $rules['proof_files'] = $task->required_proof_type === 'any' ? 'nullable|array' : 'required|array|min:1';
            $rules['proof_files.*'] = 'file|max:10240';
        }

        if ($task->required_proof_type === 'photo') {
            $rules['proof_files.*'] = 'image|max:5120';
        }

        if ($task->required_proof_type === 'text') {
            $rules['proof_text'] = 'required|string|min:3';
        }

        if ($task->requires_signature) {
            $rules['digital_signature'] = 'required|string';
        }

        $validated = $request->validate($rules);

        $proofFiles = $this->storeProofFiles($request, $submission->id, $submissionTask->proof_files);

        $update = [
            'proof_text' => $validated['proof_text'] ?? null,
            'proof_files' => $proofFiles,
            'status' => 'completed',
            'completed_at' => now(),
            'redo_requested' => false,
        ];

        if (!empty($validated['digital_signature'])) {
            $update['digital_signature'] = $validated['digital_signature'];
            $update['signature_date'] = now();
        }

        $submissionTask->update($update);

        return $this->success(null, 'Taak afgerond.');
    }

    public function uploadProof(Request $request, int $id)
    {
        $submissionTask = $this->taskAccess->findOwnedSubmissionTask($request, $id);

        if (!$submissionTask) {
            return $this->error('Taak niet gevonden.', 404);
        }

        $validated = $request->validate([
            'proof_files' => 'required|array|min:1',
            'proof_files.*' => 'file|max:10240',
            'proof_text' => 'nullable|string',
        ]);

        $existing = is_array($submissionTask->proof_files) ? $submissionTask->proof_files : [];
        $newFiles = $this->storeProofFiles($request, $submissionTask->submission_id, $existing);

        $submissionTask->update([
            'proof_files' => $newFiles,
            'proof_text' => $validated['proof_text'] ?? $submissionTask->proof_text,
        ]);

        return $this->success(null, 'Bewijs geüpload.');
    }

    /**
     * @param  array<int, array<string, mixed>>|null  $existing
     * @return array<int, array<string, mixed>>
     */
    protected function storeProofFiles(Request $request, int $submissionId, ?array $existing = []): array
    {
        $proofFiles = $existing ?? [];

        if (!$request->hasFile('proof_files')) {
            return $proofFiles;
        }

        foreach ($request->file('proof_files') as $file) {
            $path = $file->store('submissions/'.$submissionId, 'public');
            $proofFiles[] = [
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ];
        }

        return $proofFiles;
    }
}

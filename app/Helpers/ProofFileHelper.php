<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProofFileHelper
{
    /**
     * Append newly uploaded proof files to any existing entries.
     *
     * @param  array<int, array<string, mixed>>|null  $existing
     * @return array<int, array<string, mixed>>
     */
    public static function mergeUploadedProofFiles(Request $request, int $submissionId, ?array $existing = []): array
    {
        $proofFiles = is_array($existing) ? $existing : [];

        if (! $request->hasFile('proof_files')) {
            return $proofFiles;
        }

        foreach ($request->file('proof_files') as $file) {
            if (! $file->isValid()) {
                continue;
            }

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

    /**
     * @param  array<int, array<string, mixed>>|null  $files
     * @return array<int, array<string, mixed>>
     */
    public static function withAbsoluteUrls(?array $files): array
    {
        if (empty($files)) {
            return [];
        }

        return collect($files)->map(function ($file) {
            if (!is_array($file)) {
                return $file;
            }

            $path = $file['path'] ?? null;
            if (is_string($path) && $path !== '') {
                $file['url'] = Storage::disk('public')->url($path);
            }

            return $file;
        })->values()->all();
    }
}

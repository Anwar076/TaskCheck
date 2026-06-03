<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ProofFileHelper
{
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

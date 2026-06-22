<?php

namespace App\Services\Templates;

use App\Data\StarterPacks\PackRegistry;

class StarterPackCatalog
{
    public const DISCLAIMER = 'Deze templates zijn digitale compliance-checklists gebaseerd op HACCP-principes, NVWA-richtlijnen en erkende branche-hygiënecodes. De ondernemer blijft zelf verantwoordelijk voor toepassing van de actuele wet- en regelgeving en de juiste hygiënecode voor zijn bedrijf.';

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function packs(): array
    {
        return PackRegistry::packs();
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function find(string $slug): ?array
    {
        foreach (self::packs() as $pack) {
            if ($pack['slug'] === $slug) {
                return $pack;
            }
        }

        return null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function templatesFor(string $slug): array
    {
        return PackRegistry::templatesFor($slug);
    }
}

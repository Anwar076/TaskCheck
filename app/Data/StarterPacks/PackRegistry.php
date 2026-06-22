<?php

namespace App\Data\StarterPacks;

final class PackRegistry
{
    /** @var array<class-string> */
    private const PACK_CLASSES = [
        RestaurantLunchroomPack::class,
        FastfoodPack::class,
        ShishaLoungePack::class,
        HotelPack::class,
        BakkerijPack::class,
        SlagerijPack::class,
    ];

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function packs(): array
    {
        return array_map(static function (string $class): array {
            $meta = $class::meta();
            $templates = $class::templates();

            return array_merge($meta, [
                'template_count' => count($templates),
                'templates' => $templates,
                'cover_image' => 'images/starter-packs/'.$meta['slug'].'.jpg',
            ]);
        }, self::PACK_CLASSES);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function templatesFor(string $slug): array
    {
        foreach (self::PACK_CLASSES as $class) {
            if ($class::meta()['slug'] === $slug) {
                return $class::templates();
            }
        }

        return [];
    }
}

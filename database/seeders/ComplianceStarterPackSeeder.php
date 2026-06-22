<?php

namespace Database\Seeders;

use App\Data\StarterPacks\PackRegistry;
use App\Services\Templates\StarterPackService;
use Illuminate\Database\Seeder;

class ComplianceStarterPackSeeder extends Seeder
{
    public function run(): void
    {
        $service = app(StarterPackService::class);

        foreach (PackRegistry::packs() as $pack) {
            $service->ensureGlobalTemplates($pack['slug']);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Templates\StarterPackCatalog;
use App\Services\Templates\StarterPackService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StarterPackController extends Controller
{
    public function index(StarterPackService $starterPackService): View
    {
        $company = auth()->user()->company;
        $activatedSlugs = $starterPackService->activatedSlugsForCompany($company->id);

        $packs = collect(StarterPackCatalog::packs())->map(function (array $pack) use ($activatedSlugs) {
            $pack['is_active'] = in_array($pack['slug'], $activatedSlugs, true);
            $pack['templates_preview'] = collect($pack['templates'] ?? [])->pluck('name')->take(5)->all();

            return $pack;
        });

        return view('admin.starter-packs.index', [
            'packs' => $packs,
            'disclaimer' => StarterPackCatalog::DISCLAIMER,
        ]);
    }

    public function activate(Request $request, string $slug, StarterPackService $starterPackService): RedirectResponse
    {
        $pack = StarterPackCatalog::find($slug);
        if (! $pack) {
            abort(404);
        }

        $availableTemplateNames = collect($pack['templates'] ?? [])->pluck('name')->all();
        $validated = $request->validate([
            'templates' => ['required', 'array', 'min:1'],
            'templates.*' => ['required', 'string', Rule::in($availableTemplateNames)],
        ]);

        $result = $starterPackService->activate(
            auth()->user()->company,
            auth()->user(),
            $slug,
            $validated['templates'],
        );

        if ($result['already_active']) {
            return redirect()
                ->route('admin.starter-packs.index')
                ->with('info', "Starterpack \"{$pack['name']}\" is al geactiveerd.");
        }

        return redirect()
            ->route('admin.starter-packs.index')
            ->with('success', "Starterpack \"{$pack['name']}\" geactiveerd: {$result['templates_imported']} templates toegevoegd aan je bibliotheek.");
    }

    public function deactivate(Request $request, string $slug, StarterPackService $starterPackService): RedirectResponse
    {
        $pack = StarterPackCatalog::find($slug);
        if (! $pack) {
            abort(404);
        }

        $result = $starterPackService->deactivate(
            auth()->user()->company,
            $slug,
        );

        if ($result['already_inactive']) {
            return redirect()
                ->route('admin.starter-packs.index')
                ->with('info', "Starterpack \"{$pack['name']}\" is niet actief.");
        }

        return redirect()
            ->route('admin.starter-packs.index')
            ->with('success', "Starterpack \"{$pack['name']}\" gedeactiveerd: {$result['templates_removed']} templates verwijderd.");
    }
}

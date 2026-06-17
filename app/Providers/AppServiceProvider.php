<?php

namespace App\Providers;

use App\Services\Platform\AdminOnboardingService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.admin', function ($view) {
            $user = auth()->user();
            $company = $user?->company;
            $service = app(AdminOnboardingService::class);
            $routeName = request()->route()?->getName();

            $view->with([
                'onboarding' => $service->context($company),
                'adminHelp' => $service->helpContext($company, $routeName),
            ]);
        });
    }
}

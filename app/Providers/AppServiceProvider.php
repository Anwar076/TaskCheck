<?php

namespace App\Providers;

use App\Services\Platform\AdminOnboardingService;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
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
        if (! $this->app->runningInConsole() && request()->getHost() === '10.0.2.2') {
            URL::forceRootUrl('http://10.0.2.2:8000');

            if (is_file(public_path('hot'))) {
                $emulatorHot = storage_path('framework/vite-hot-emulator');
                file_put_contents($emulatorHot, 'http://10.0.2.2:5173');
                Vite::useHotFile($emulatorHot);
            }
        }

        $shareSubscriptionLock = function ($view) {
            $company = auth()->user()?->company;

            $view->with([
                'subscriptionLocked' => $company && ! $company->canAccess(),
                'subscriptionLockMessage' => $company?->accessLockMessage(),
            ]);
        };

        View::composer('layouts.admin', function ($view) use ($shareSubscriptionLock) {
            $user = auth()->user();
            $company = $user?->company;
            $service = app(AdminOnboardingService::class);
            $routeName = request()->route()?->getName();

            $shareSubscriptionLock($view);
            $view->with([
                'onboarding' => $service->context($company),
                'adminHelp' => $service->helpContext($company, $routeName),
            ]);
        });

        View::composer([
            'layouts.employee',
            'partials.subscription-lock-banner',
            'admin.settings.tabs',
        ], $shareSubscriptionLock);
    }
}

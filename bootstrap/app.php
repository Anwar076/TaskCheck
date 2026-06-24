<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'subscription/mollie/webhook',
        ]);

        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'employee' => \App\Http\Middleware\EmployeeMiddleware::class,
            'super_admin' => \App\Http\Middleware\SuperAdminMiddleware::class,
            'pwa' => \App\Http\Middleware\RedirectPwaToLogin::class,
            'subscription' => \App\Http\Middleware\CheckSubscription::class,
            'company_profile_complete' => \App\Http\Middleware\EnsureCompanyInvoiceDetailsComplete::class,
            'onboarding_complete' => \App\Http\Middleware\EnsureOnboardingComplete::class,
            'mobile.admin' => \App\Http\Middleware\EnsureMobileAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('platform:check-alerts')->everyFiveMinutes();
        $schedule->command('reports:send-company')->everyMinute();
        $schedule->command('subscriptions:notify-trial-expired')->hourly();
    })
    ->create();

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\TaskListController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TaskTemplateController;
use App\Http\Controllers\Admin\StarterPackController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\CompanySettingsController;
use App\Http\Controllers\Admin\OnboardingController;
use App\Http\Controllers\Admin\SubmissionController as AdminSubmissionController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
use App\Http\Controllers\SuperAdmin\TemplateController as SuperAdminTemplateController;
use App\Services\Ai\SubmissionReviewService;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboardController;
use App\Http\Controllers\Employee\NotificationController as EmployeeNotificationController;
use App\Http\Controllers\Employee\SubmissionController;
use App\Http\Controllers\Employee\SettingsController as EmployeeSettingsController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Always show welcome page for web browsers
    return view('welcome');
})->name('welcome');

// CSRF token refresh route (for handling expired sessions)
Route::get('/refresh-csrf', function () {
    return response()->json(['token' => csrf_token()]);
})->name('refresh-csrf');

// Public pages (only for web browsers)
// Route::get('/features', function () {
//     return redirect()->route('welcome');
// })->name('features');

Route::get('/pricing', function () {
    return view('pricing');
})->name('pricing');

// Route::get('/about', function () {
//     return view('about');
// })->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/go/{code}', \App\Http\Controllers\MarketingLinkRedirectController::class)
    ->name('marketing-link.redirect');

Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'send'])
    ->middleware('throttle:10,1')
    ->name('contact.send');

// Route::get('/help', function () {
//     return view('help');
// })->name('help');

// Route::get('/privacy', function () {
//     return view('privacy');
// })->name('privacy');

// Route::get('/terms', function () {
//     return view('terms');
// })->name('terms');

// Route::get('/status', function () {
//     return view('status');
// })->name('status');

// Route::get('/security', function () {
//     return view('security');
// })->name('security');

// Route::get('/api', function () {
//     return view('api');
// })->name('api');

// Route::get('/integrations', function () {
//     return view('integrations');
// })->name('integrations');

Route::get('/blog', function () {
    return view('blog');
})->name('blog');
Route::get('/blog/horeca-personeel-controleren-checklist-app', function () {
    return view('blog.horeca-personeel-controleren-checklist-app');
})->name('blog.horeca-personeel-controleren-checklist-app');
Route::get('/blog/beste-checklist-app-voor-schoonmaakbedrijven', function () {
    return view('blog.beste-checklist-app-voor-schoonmaakbedrijven');
})->name('blog.beste-checklist-app-voor-schoonmaakbedrijven');
Route::get('/blog/waarom-bedrijven-stoppen-met-excel-checklists', function () {
    return view('blog.waarom-bedrijven-stoppen-met-excel-checklists');
})->name('blog.waarom-bedrijven-stoppen-met-excel-checklists');
Route::get('/blog/waarom-horeca-stopt-met-papieren-checklists', function () {
    return view('blog.waarom-horeca-stopt-met-papieren-checklists');
})->name('blog.waarom-horeca-stopt-met-papieren-checklists');
Route::get('/blog/nvwa-spoedsluitingen-plaagdieren-2026', function () {
    return view('blog.nvwa-spoedsluitingen-plaagdieren-2026');
})->name('blog.nvwa-spoedsluitingen-plaagdieren-2026');
Route::get('/blog/waarom-restaurants-steeds-vaker-werken-met-digitale-checklists', function () {
    return view('blog.waarom-restaurants-steeds-vaker-werken-met-digitale-checklists');
})->name('blog.waarom-restaurants-steeds-vaker-werken-met-digitale-checklists');

Route::get('/haccp-app', function () {
    return view('seo.haccp-app');
})->name('seo.haccp-app');
Route::get('/haccp-checklist-app', function () {
    return view('seo.haccp-checklist-app');
})->name('seo.haccp-checklist-app');
Route::get('/digitale-haccp-registratie', function () {
    return view('seo.digitale-haccp-registratie');
})->name('seo.digitale-haccp-registratie');
Route::get('/haccp-formulieren', function () {
    return view('seo.haccp-formulieren');
})->name('seo.haccp-formulieren');
Route::redirect('/sluitingschecklist-horeca', '/sluitings-checklist-horeca', 301);
Route::get('/temperatuurregistratie-horeca', function () {
    return view('seo.temperatuurregistratie-horeca');
})->name('seo.temperatuurregistratie-horeca');
Route::get('/temperatuurregistratie-app', function () {
    return view('seo.temperatuurregistratie-app');
})->name('seo.temperatuurregistratie-app');
Route::get('/schoonmaakrooster-horeca', function () {
    return view('seo.coming-soon', [
        'seoTitle' => 'Schoonmaakrooster horeca | TaskCheck',
        'seoDescription' => 'Digitaal schoonmaakrooster voor horeca — binnenkort beschikbaar. Beheer schoonmaaktaken en bewijs met TaskCheck.',
        'seoUrl' => route('seo.schoonmaakrooster-horeca'),
        'pageTitle' => 'Schoonmaakrooster horeca',
    ]);
})->name('seo.schoonmaakrooster-horeca');
Route::get('/horeca-checklist-app', function () {
    return view('seo.horeca-checklist-app');
})->name('seo.horeca-checklist-app');
Route::get('/horeca-app', function () {
    return view('seo.horeca-app');
})->name('seo.horeca-app');
Route::get('/restaurant-checklist-app', function () {
    return view('seo.restaurant-checklist-app');
})->name('seo.restaurant-checklist-app');
Route::get('/mise-en-place-lijst-maken', function () {
    return view('seo.mise-en-place-lijst-maken');
})->name('seo.mise-en-place-lijst-maken');
Route::get('/schoonmaak-checklist-app', function () {
    return redirect()->route('seo.checklist-app-schoonmaak', [], 301);
})->name('seo.schoonmaak-checklist-app');
Route::get('/werkcontrole-app', function () {
    return view('seo.werkcontrole-app');
})->name('seo.werkcontrole-app');
Route::get('/horeca-app-personeel', function () {
    return view('seo.horeca-app-personeel');
})->name('seo.horeca-app-personeel');
Route::get('/checklist-app-voor-bedrijven', function () {
    return view('seo.checklist-app-voor-bedrijven');
})->name('seo.checklist-app-voor-bedrijven');
Route::get('/wat-is-een-checklist-app', function () {
    return view('seo.wat-is-een-checklist-app');
})->name('seo.wat-is-een-checklist-app');
Route::get('/schoonmaak-checklist', function () {
    return view('seo.schoonmaak-checklist');
})->name('seo.schoonmaak-checklist');
Route::get('/schoonmaak-controle-app', function () {
    return view('seo.coming-soon', [
        'seoTitle' => 'Schoonmaak controle app | TaskCheck',
        'seoDescription' => 'Schoonmaak controle app — binnenkort beschikbaar. Controleer schoonmaakwerkzaamheden digitaal met TaskCheck.',
        'seoUrl' => route('seo.schoonmaak-controle-app'),
        'pageTitle' => 'Schoonmaak controle app',
    ]);
})->name('seo.schoonmaak-controle-app');
Route::get('/checklist-app-met-foto-bewijs', function () {
    return view('seo.coming-soon', [
        'seoTitle' => 'Checklist app met foto bewijs | TaskCheck',
        'seoDescription' => 'Checklist app met foto bewijs — binnenkort beschikbaar. Leg werkzaamheden aantoonbaar vast met TaskCheck.',
        'seoUrl' => route('seo.checklist-app-met-foto-bewijs'),
        'pageTitle' => 'Checklist app met foto bewijs',
    ]);
})->name('seo.checklist-app-met-foto-bewijs');
Route::get('/digitale-checklist-app', function () {
    return view('seo.coming-soon', [
        'seoTitle' => 'Digitale checklist app | TaskCheck',
        'seoDescription' => 'Digitale checklist app — binnenkort beschikbaar. Werk met digitale checklists en bewijs per taak via TaskCheck.',
        'seoUrl' => route('seo.digitale-checklist-app'),
        'pageTitle' => 'Digitale checklist app',
    ]);
})->name('seo.digitale-checklist-app');
Route::get('/checklist-app-schoonmaak', function () {
    return view('seo.checklist-app-schoonmaak');
})->name('seo.checklist-app-schoonmaak');
Route::get('/app-schoonmaakbedrijf', function () {
    return view('seo.app-schoonmaakbedrijf');
})->name('seo.app-schoonmaakbedrijf');
Route::get('/beste-checklist-app-2026', function () {
    return view('seo.beste-checklist-app-2026');
})->name('seo.beste-checklist-app-2026');
Route::get('/takenlijst-personeel', function () {
    return view('seo.takenlijst-personeel');
})->name('seo.takenlijst-personeel');
Route::get('/opening-checklist-horeca', function () {
    return view('seo.opening-checklist-horeca');
})->name('seo.opening-checklist-horeca');
Route::get('/sluitings-checklist-horeca', function () {
    return view('seo.sluitings-checklist-horeca');
})->name('seo.sluitings-checklist-horeca');
Route::get('/schoonmaak-checklist-voorbeeld', function () {
    return view('seo.schoonmaak-checklist-voorbeeld');
})->name('seo.schoonmaak-checklist-voorbeeld');

Route::get('/horeca-check-app', function () {
    return view('seo.horeca-check-app');
})->name('seo.horeca-check-app');

Route::get('/temperatuurregistratie-systeem-vriezer', function () {
    return view('seo.temperatuurregistratie-systeem-vriezer');
})->name('seo.temperatuurregistratie-systeem-vriezer');

// Route::get('/careers', function () {
//     return view('careers');
// })->name('careers');

// Route::get('/documentation', function () {
//     return view('documentation');
// })->name('documentation');

// Subscription routes (accessible even without active subscription for choosing plans)
Route::post('/subscription/mollie/webhook', [SubscriptionController::class, 'mollieWebhook'])->name('subscription.mollie.webhook');

Route::middleware(['auth'])->prefix('subscription')->name('subscription.')->group(function () {
    Route::get('/choose-plan', [SubscriptionController::class, 'choosePlan'])->name('choose-plan');
    Route::get('/', [SubscriptionController::class, 'show'])->name('show');
    Route::get('/invoices/{invoice}/download', [SubscriptionController::class, 'downloadInvoice'])->name('invoices.download');
    Route::get('/payment-return', [SubscriptionController::class, 'paymentReturn'])->name('payment-return');
    Route::post('/activate', [SubscriptionController::class, 'activate'])->name('activate');
    Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
});

// Redirect dashboard based on user role
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isSuperAdmin()) {
        return redirect()->route('super-admin.dashboard');
    }

    if ($user->isAdmin()) {
        $preferredDashboard = session('dashboard_mode', 'admin');
        if ($preferredDashboard === 'employee') {
            return redirect()->route('employee.dashboard');
        }

        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('employee.dashboard');
})->middleware(['auth', 'verified', 'subscription'])->name('dashboard');

Route::post('/dashboard/switch', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'mode' => ['required', 'in:admin,employee'],
    ]);

    $user = auth()->user();
    $targetMode = $request->string('mode')->toString();

    if (!$user || !$user->isAdmin() || $user->isSuperAdmin()) {
        abort(403);
    }

    session(['dashboard_mode' => $targetMode]);

    return redirect()->route($targetMode === 'employee' ? 'employee.dashboard' : 'admin.dashboard');
})->middleware(['auth', 'verified', 'subscription'])->name('dashboard.switch');

Route::middleware(['auth', 'verified', 'subscription', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::post('/onboarding/start', [OnboardingController::class, 'start'])->name('onboarding.start');
    Route::post('/onboarding/users/continue', [OnboardingController::class, 'continueUsers'])->name('onboarding.users.continue');
    Route::post('/onboarding/starter-pack/continue', [OnboardingController::class, 'continueStarterPack'])->name('onboarding.starter-pack.continue');
    Route::post('/onboarding/list-choice', [OnboardingController::class, 'chooseList'])->name('onboarding.list-choice');
    Route::post('/onboarding/skip', [OnboardingController::class, 'skip'])->name('onboarding.skip');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'subscription', 'admin', 'onboarding_complete', 'company_profile_complete'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/live-monitoring', [AdminDashboardController::class, 'liveMonitoring'])->name('live-monitoring');
    Route::get('/team-performance', [AdminDashboardController::class, 'teamPerformance'])->name('team-performance');
    
    // API-powered routes
    Route::get('/lists', [TaskListController::class, 'index'])->name('lists.index');
    
    Route::get('/users', function() {
        return view('admin.users.index-api');
    })->name('users.index');
    
    Route::get('/submissions', [AdminSubmissionController::class, 'index'])->name('submissions.index');
    
    // AI import routes must come before resource route with {list}
    Route::get('/lists/ai-import', [TaskListController::class, 'aiImportPage'])->name('lists.ai-import');
    Route::post('/lists/ai-import/generate', [TaskListController::class, 'aiImportGenerate'])->name('lists.ai-import.generate');
    Route::post('/lists/ai-import/store', [TaskListController::class, 'aiImportStore'])->name('lists.ai-import.store');
    Route::get('/lists/calendar', [TaskListController::class, 'calendar'])->name('lists.calendar');
    Route::put('/lists/{list}/schedule-slot/{slot}', [TaskListController::class, 'updateScheduleTimeSlot'])->name('lists.schedule-slot.update');
    Route::delete('/lists/{list}/schedule-slot/{slot}', [TaskListController::class, 'destroyScheduleTimeSlot'])->name('lists.schedule-slot.destroy');
    Route::post('/lists/{list}/schedule-day', [TaskListController::class, 'scheduleDay'])->name('lists.schedule-day');
    Route::post('/lists/{list}/schedule-slot', [TaskListController::class, 'scheduleTimeSlot'])->name('lists.schedule-slot');
    Route::post('/lists/{list}/tasks/quick', [TaskController::class, 'quickStore'])->name('lists.tasks.quick-store');
    Route::get('/tasks/{task}/form-data', [TaskController::class, 'formData'])->name('tasks.form-data');

    // Regular routes for create/edit/show
    Route::resource('lists', TaskListController::class)->except(['index']);
    Route::resource('lists.tasks', TaskController::class)->shallow();
    Route::resource('templates', TaskTemplateController::class);
    Route::get('/starter-packs', [StarterPackController::class, 'index'])->name('starter-packs.index');
    Route::post('/starter-packs/{slug}/activate', [StarterPackController::class, 'activate'])->name('starter-packs.activate');
    Route::delete('/starter-packs/{slug}/deactivate', [StarterPackController::class, 'deactivate'])->name('starter-packs.deactivate');
    Route::resource('locations', LocationController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['index']);
        Route::resource('submissions', SubmissionController::class)->except(['index']);

    // Individual admin routes
    
    // Additional admin routes
    Route::post('/lists/{list}/assign', [TaskListController::class, 'assign'])->name('lists.assign');
    Route::post('/lists/{list}/tasks/reorder', [TaskController::class, 'reorder'])->name('lists.tasks.reorder');
    Route::delete('/assignments/{assignment}', [TaskListController::class, 'removeAssignment'])->name('assignments.destroy');
    Route::get('/submissions/{submission}', [TaskListController::class, 'showSubmission'])->name('submissions.show');
    Route::post('/submissions/{submission}/review', [TaskListController::class, 'reviewSubmission'])->name('submissions.review');
    Route::post('/submissions/{submission}/ai-review', [TaskListController::class, 'aiReviewSubmission'])->name('submissions.ai-review');
    Route::post('/submission-tasks/{submissionTask}/approve', [TaskListController::class, 'approveTask'])->name('submission-tasks.approve');
    Route::post('/submission-tasks/{submissionTask}/reject', [TaskListController::class, 'rejectTask'])->name('submission-tasks.reject');
    Route::post('/submission-tasks/{submissionTask}/redo', [TaskListController::class, 'requestRedo'])->name('submission-tasks.redo');
    
    // Weekly overview and daily sub-lists
    Route::get('/weekly-overview', [TaskListController::class, 'weeklyOverview'])->name('weekly-overview');
    Route::get('/settings', [CompanySettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [CompanySettingsController::class, 'update'])->name('settings.update');
    Route::post('/lists/{list}/create-daily-sublists', [TaskListController::class, 'createDailySubLists'])->name('lists.create-daily-sublists');
    Route::post('/lists/{list}/create-day-list', [TaskListController::class, 'createDayList'])->name('lists.create-day-list');
    Route::post('/lists/{list}/sync-template', [TaskListController::class, 'syncTemplate'])->name('lists.sync-template');
    Route::post('/tasks/ai-suggest', [TaskController::class, 'aiSuggest'])->name('tasks.ai-suggest');
    Route::post('/lists/ai-generate', [TaskListController::class, 'aiGenerate'])->name('lists.ai-generate');
    Route::get('/notifications/realtime-feed', [AdminNotificationController::class, 'realtimeFeed'])->name('notifications.realtime-feed');
    Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/create', [AdminNotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications', [AdminNotificationController::class, 'store'])->name('notifications.store');
    Route::post('/notifications/{notification}/mark-read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    
    // Template routes
    Route::post('/templates/{template}/create-list', [TaskTemplateController::class, 'createFromTemplate'])->name('templates.create-list');
    Route::post('/templates/global/{template}/import', [TaskTemplateController::class, 'importGlobalTemplate'])->name('templates.global.import');
    Route::post('/templates/{template}/apply-global-update', [TaskTemplateController::class, 'applyGlobalTemplateUpdate'])->name('templates.apply-global-update');
    
    // Debug routes
    Route::get('/debug/test-assignment', function() {
        return view('debug.test-assignment');
    })->name('debug.test-assignment');
});

Route::middleware(['auth', 'verified', 'super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/invoices/export/csv', [SuperAdminDashboardController::class, 'exportInvoicesCsv'])->name('invoices.export.csv');
    Route::post('/communications/broadcast-mail', [SuperAdminDashboardController::class, 'sendBroadcastMail'])->name('communications.broadcast-mail');
    Route::post('/communications/broadcast-notification', [SuperAdminDashboardController::class, 'sendBroadcastNotification'])->name('communications.broadcast-notification');
    Route::post('/marketing-links', [\App\Http\Controllers\SuperAdmin\MarketingLinkCampaignController::class, 'store'])->name('marketing-links.store');
    Route::delete('/marketing-links/{marketingLink}', [\App\Http\Controllers\SuperAdmin\MarketingLinkCampaignController::class, 'destroy'])->name('marketing-links.destroy');
    Route::post('/platform-alerts/test', [SuperAdminDashboardController::class, 'sendPlatformAlertTest'])->name('platform-alerts.test');
    Route::post('/companies', [SuperAdminDashboardController::class, 'storeCompany'])->name('companies.store');
    Route::put('/companies/{company}/subscription', [SuperAdminDashboardController::class, 'updateCompanySubscription'])->name('companies.subscription.update');
    Route::get('/errors/feed', [SuperAdminDashboardController::class, 'errorsFeed'])->name('errors.feed');
    Route::post('/incidents', [SuperAdminDashboardController::class, 'createIncidentTicket'])->name('incidents.store');
    Route::get('/incidents/{incident}', [SuperAdminDashboardController::class, 'showIncidentTicket'])->name('incidents.show');
    Route::post('/incidents/{incident}/analyze', [SuperAdminDashboardController::class, 'analyzeIncidentTicket'])->name('incidents.analyze');
    Route::put('/incidents/{incident}/status', [SuperAdminDashboardController::class, 'updateIncidentTicketStatus'])->name('incidents.status.update');
    Route::get('/templates/ai-import', [SuperAdminTemplateController::class, 'aiImportPage'])->name('templates.ai-import');
    Route::post('/templates/ai-import/generate', [SuperAdminTemplateController::class, 'aiImportGenerate'])->name('templates.ai-import.generate');
    Route::post('/templates/ai-import/store', [SuperAdminTemplateController::class, 'aiImportStore'])->name('templates.ai-import.store');
    Route::resource('templates', SuperAdminTemplateController::class)->except(['show']);
    Route::post('/templates/{template}/publish', [SuperAdminTemplateController::class, 'publish'])->name('templates.publish');
});

// Employee Routes
Route::middleware(['auth', 'verified', 'subscription', 'employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
    Route::get('/lists', [SubmissionController::class, 'index'])->name('lists.index');
    Route::get('/lists/{list}', [SubmissionController::class, 'show'])->name('lists.show');
    Route::post('/lists/{list}/start', [SubmissionController::class, 'start'])->name('submissions.start');
    Route::get('/submissions/{submission}', [SubmissionController::class, 'edit'])->name('submissions.edit');
    Route::put('/submissions/{submission}', [SubmissionController::class, 'update'])->name('submissions.update');
    Route::post('/submissions/{submission}/complete', [SubmissionController::class, 'complete'])->name('submissions.complete');
    Route::post('/submissions/{submission}/tasks/{task}', [SubmissionController::class, 'completeTask'])->name('submissions.tasks.complete');
    
    // Notification routes
    Route::get('/notifications', [EmployeeNotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/mark-read', [EmployeeNotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [EmployeeNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/task-overdue', [EmployeeNotificationController::class, 'createTaskOverdue'])->name('notifications.task-overdue');
    Route::delete('/notifications/{notification}', [EmployeeNotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::get('/notifications/realtime-feed', [EmployeeNotificationController::class, 'realtimeFeed'])->name('notifications.realtime-feed');

    // Instellingen
    Route::get('/settings', [EmployeeSettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings/profile', [EmployeeSettingsController::class, 'updateProfile'])->name('settings.update-profile');
    Route::put('/settings/password', [EmployeeSettingsController::class, 'updatePassword'])->name('settings.update-password');
});

Route::middleware('auth')->group(function () {
    Route::get('/push/vapid-public-key', [PushSubscriptionController::class, 'vapidPublicKey'])->name('push.vapid-public-key');
    Route::post('/push/subscribe', [PushSubscriptionController::class, 'store'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::fallback(function () {
    abort(404);
});
Route::get('/blog/nvwa-update-horeca-inspecties-juni-2026', function () {
    return view('blog.nvwa-update-horeca-inspecties-juni-2026');
})->name('blog.nvwa-update-horeca-inspecties-juni-2026');

Route::get('/blog/haccp-lijsten', function () {
    return view('blog.haccp-lijsten');
})->name('blog.haccp-lijsten');

Route::get('/blog/haccp-richtlijnen-checklist', function () {
    return view('blog.haccp-richtlijnen-checklist');
})->name('blog.haccp-richtlijnen-checklist');

Route::get('/blog/logboek-horeca', function () {
    return view('blog.logboek-horeca');
})->name('blog.logboek-horeca');


<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\TaskListController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TaskTemplateController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\CompanySettingsController;
use App\Http\Controllers\SuperAdmin\DashboardController as SuperAdminDashboardController;
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

Route::get('/horeca-checklist-app', function () {
    return view('seo.horeca-checklist-app');
})->name('seo.horeca-checklist-app');
Route::get('/schoonmaak-checklist-app', function () {
    return view('seo.schoonmaak-checklist-app');
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
Route::get('/checklist-app-schoonmaak', function () {
    return view('seo.checklist-app-schoonmaak');
})->name('seo.checklist-app-schoonmaak');
Route::get('/beste-checklist-app-2026', function () {
    return view('seo.beste-checklist-app-2026');
})->name('seo.beste-checklist-app-2026');
Route::get('/takenlijst-personeel', function () {
    return view('seo.takenlijst-personeel');
})->name('seo.takenlijst-personeel');

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
    Route::get('/payment-return', [SubscriptionController::class, 'paymentReturn'])->name('payment-return');
    Route::post('/activate', [SubscriptionController::class, 'activate'])->name('activate');
    Route::post('/cancel', [SubscriptionController::class, 'cancel'])->name('cancel');
});

// Redirect dashboard based on user role
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->isAdmin()) {
        if ($user->isSuperAdmin()) {
            return redirect()->route('super-admin.dashboard');
        }

        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('employee.dashboard');
})->middleware(['auth', 'verified', 'subscription'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'verified', 'subscription', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/live-monitoring', [AdminDashboardController::class, 'liveMonitoring'])->name('live-monitoring');
    
    // API-powered routes
    Route::get('/lists', [TaskListController::class, 'index'])->name('lists.index');
    
    Route::get('/users', function() {
        return view('admin.users.index-api');
    })->name('users.index');
    
    Route::get('/submissions', function() {
        return view('admin.submissions.index-api');
    })->name('submissions.index');
    
    // AI import routes must come before resource route with {list}
    Route::get('/lists/ai-import', [TaskListController::class, 'aiImportPage'])->name('lists.ai-import');
    Route::post('/lists/ai-import/generate', [TaskListController::class, 'aiImportGenerate'])->name('lists.ai-import.generate');
    Route::post('/lists/ai-import/store', [TaskListController::class, 'aiImportStore'])->name('lists.ai-import.store');

    // Regular routes for create/edit/show
    Route::resource('lists', TaskListController::class)->except(['index']);
    Route::resource('lists.tasks', TaskController::class)->shallow();
    Route::resource('templates', TaskTemplateController::class);
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
    Route::post('/tasks/ai-suggest', [TaskController::class, 'aiSuggest'])->name('tasks.ai-suggest');
    Route::post('/lists/ai-generate', [TaskListController::class, 'aiGenerate'])->name('lists.ai-generate');
    Route::get('/notifications/realtime-feed', [AdminNotificationController::class, 'realtimeFeed'])->name('notifications.realtime-feed');
    Route::post('/notifications/{notification}/mark-read', [AdminNotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [AdminNotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    
    // Template routes
    Route::post('/templates/{template}/create-list', [TaskTemplateController::class, 'createFromTemplate'])->name('templates.create-list');
    
    // Debug routes
    Route::get('/debug/test-assignment', function() {
        return view('debug.test-assignment');
    })->name('debug.test-assignment');
});

Route::middleware(['auth', 'verified', 'super_admin'])->prefix('super-admin')->name('super-admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/communications/broadcast-mail', [SuperAdminDashboardController::class, 'sendBroadcastMail'])->name('communications.broadcast-mail');
    Route::post('/companies', [SuperAdminDashboardController::class, 'storeCompany'])->name('companies.store');
    Route::put('/companies/{company}/subscription', [SuperAdminDashboardController::class, 'updateCompanySubscription'])->name('companies.subscription.update');
    Route::get('/errors/feed', [SuperAdminDashboardController::class, 'errorsFeed'])->name('errors.feed');
    Route::post('/incidents', [SuperAdminDashboardController::class, 'createIncidentTicket'])->name('incidents.store');
    Route::get('/incidents/{incident}', [SuperAdminDashboardController::class, 'showIncidentTicket'])->name('incidents.show');
    Route::post('/incidents/{incident}/analyze', [SuperAdminDashboardController::class, 'analyzeIncidentTicket'])->name('incidents.analyze');
    Route::put('/incidents/{incident}/status', [SuperAdminDashboardController::class, 'updateIncidentTicketStatus'])->name('incidents.status.update');
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

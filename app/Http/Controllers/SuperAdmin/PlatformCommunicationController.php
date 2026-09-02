<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Mail\TaskCheckNotificationMail;
use App\Models\Communication\Notification;
use App\Models\Organisation\Company;
use App\Models\Organisation\User;
use App\Models\Platform\PlatformBroadcast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PlatformCommunicationController extends Controller
{
    public function sendBroadcastMail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:20000'],
            'include_inactive' => ['nullable', 'boolean'],
            'send_mode' => ['nullable', Rule::in(['send', 'test'])],
        ]);

        $includeInactive = (bool) ($validated['include_inactive'] ?? false);

        if (($validated['send_mode'] ?? 'send') === 'test') {
            Mail::to($request->user()->email)->send(new TaskCheckNotificationMail(
                subjectLine: '[TEST] '.(string) $validated['subject'],
                greetingName: $request->user()->name,
                title: (string) $validated['subject'],
                bodyText: (string) $validated['message'],
                ctaLabel: 'Open TaskCheck',
                ctaUrl: config('app.url'),
                metaText: 'Dit is een testweergave; klanten hebben dit bericht niet ontvangen.',
                showMarketing: true
            ));

            return redirect()->route('super-admin.dashboard', ['tab' => 'communications'])
                ->with('success', "Testmail verstuurd naar {$request->user()->email}. Er zijn geen klanten gemaild.");
        }

        $companies = Company::query()
            ->with(['users' => fn ($q) => $q->where('role', 'admin')->where('is_active', true)->orderBy('id')])
            ->when(! $includeInactive, fn ($q) => $q->where('is_active', true))
            ->get();

        $sent = 0;
        $failed = 0;

        foreach ($companies as $company) {
            $recipient = $company->email ?: optional($company->users->first())->email;
            if (! $recipient) {
                $failed++;

                continue;
            }

            try {
                Mail::to($recipient)->send(new TaskCheckNotificationMail(
                    subjectLine: (string) $validated['subject'],
                    greetingName: $company->name,
                    title: (string) $validated['subject'],
                    bodyText: (string) $validated['message'],
                    ctaLabel: 'Open TaskCheck',
                    ctaUrl: config('app.url'),
                    metaText: 'Je ontvangt dit bericht omdat je beheercontact bent van een TaskCheck organisatie.',
                    showMarketing: true
                ));

                $sent++;
            } catch (\Throwable $e) {
                report($e);
                $failed++;
            }
        }

        if (Schema::hasTable('platform_broadcasts')) {
            PlatformBroadcast::create([
                'sent_by' => Auth::id(), 'channel' => 'email', 'subject' => $validated['subject'],
                'message' => $validated['message'], 'audience' => $includeInactive ? 'all_companies' : 'active_companies',
                'recipients_count' => $sent, 'failed_count' => $failed, 'status' => $failed ? 'partially_sent' : 'sent',
                'sent_at' => now(),
            ]);
        }

        return redirect()->route('super-admin.dashboard', ['tab' => 'communications'])->with(
            'success',
            "Bulkmail verzonden. Succes: {$sent}, mislukt: {$failed}."
        );
    }

    public function sendBroadcastNotification(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
            'audience' => ['required', Rule::in(['all', 'admins', 'employees'])],
            'severity' => ['required', Rule::in(['info', 'success', 'warning'])],
            'include_inactive' => ['nullable', 'boolean'],
        ]);

        $includeInactive = (bool) ($validated['include_inactive'] ?? false);
        $audience = (string) $validated['audience'];
        $campaignId = (string) Str::uuid();

        $usersQuery = User::query()
            ->when(! $includeInactive, fn ($q) => $q->where('is_active', true))
            ->when(
                $audience === 'admins',
                fn ($q) => $q->whereIn('role', ['admin', 'super_admin'])
            )
            ->when(
                $audience === 'employees',
                fn ($q) => $q->where('role', 'employee')
            );

        $users = $usersQuery->select('id', 'role')->get();
        $sent = 0;

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'platform_announcement',
                'title' => (string) $validated['title'],
                'message' => (string) $validated['message'],
                'data' => [
                    'campaign_id' => $campaignId,
                    'audience' => $audience,
                    'severity' => (string) $validated['severity'],
                    'sender' => 'super_admin',
                    'url' => '/dashboard',
                ],
            ]);
            $sent++;
        }

        if (Schema::hasTable('platform_broadcasts')) {
            PlatformBroadcast::create([
                'sent_by' => Auth::id(), 'channel' => 'in_app', 'title' => $validated['title'],
                'message' => $validated['message'], 'audience' => $audience,
                'recipients_count' => $sent, 'failed_count' => 0, 'status' => 'sent',
                'meta' => ['severity' => $validated['severity'], 'include_inactive' => $includeInactive], 'sent_at' => now(),
            ]);
        }

        return redirect()->route('super-admin.dashboard', ['tab' => 'communications'])->with(
            'success',
            "Platformmelding verstuurd naar {$sent} gebruikers."
        );
    }
}

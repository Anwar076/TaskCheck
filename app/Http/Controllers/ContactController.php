<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Services\Security\RecaptchaVerifier;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactController extends Controller
{
    public function send(Request $request, RecaptchaVerifier $recaptcha): RedirectResponse
    {
        if (! $recaptcha->isConfigured()) {
            Log::error('Contact form: reCAPTCHA is not configured. Set RECAPTCHA_SITE_KEY and RECAPTCHA_SECRET_KEY in .env.');

            return back()
                ->withInput()
                ->with('error', 'Versturen is tijdelijk niet mogelijk door een configuratiefout. Mail ons rechtstreeks op info@taskcheck.nl.');
        }

        $validated = $request->validate([
            'firstName' => 'required|string|max:100',
            'lastName' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
            'g-recaptcha-response' => [
                'required',
                'string',
                function (string $attribute, mixed $value, Closure $fail) use ($request, $recaptcha): void {
                    if (! $recaptcha->verify(is_string($value) ? $value : null, $request->ip(), 'contact')) {
                        $fail('De beveiligingscontrole is mislukt. Probeer het opnieuw.');
                    }
                },
            ],
        ], [
            'g-recaptcha-response.required' => 'Bevestig dat je geen robot bent.',
        ]);

        $subjectLabels = [
            'demo' => 'Demo aanvragen',
            'sales' => 'Verkoopvraag',
            'support' => 'Technische ondersteuning',
            'billing' => 'Facturatie',
            'other' => 'Overig',
        ];

        $subjectKey = $validated['subject'] ?? '';
        $subjectLabel = $subjectLabels[$subjectKey] ?? 'Contactformulier';
        $fromName = trim($validated['firstName'].' '.$validated['lastName']);

        $to = config('mail.contact_to');
        if (! is_string($to) || ! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            Log::error('Contact form: mail.contact_to is not a valid email. Set MAIL_CONTACT_TO or MAIL_FROM_ADDRESS in .env.');

            return back()
                ->withInput()
                ->with('error', 'Versturen is tijdelijk niet mogelijk door een configuratiefout. Mail ons rechtstreeks op info@taskcheck.nl.');
        }

        try {
            Mail::to($to)->send(new ContactFormMail(
                fromName: $fromName,
                fromEmail: $validated['email'],
                company: $validated['company'] ?? '',
                subjectLabel: $subjectLabel,
                messageBody: $validated['message'],
            ));
        } catch (Throwable $e) {
            Log::error('Contact form mail failed', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);

            return back()
                ->withInput()
                ->with('error', 'Versturen mislukt (e-mailserver). Probeer het later opnieuw of mail direct naar info@taskcheck.nl.');
        }

        return redirect()
            ->route('contact')
            ->with('success', 'Je bericht is verstuurd. We nemen zo snel mogelijk contact op.');
    }
}

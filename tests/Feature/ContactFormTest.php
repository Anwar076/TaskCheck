<?php

namespace Tests\Feature;

use App\Mail\ContactFormMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    public function test_contact_page_includes_recaptcha_script(): void
    {
        $response = $this->get('/contact');

        $response->assertOk()
            ->assertSee('recaptcha/api.js?render=', false)
            ->assertSee('data-recaptcha-sitekey', false)
            ->assertSee(config('services.recaptcha.site_key'), false);
    }

    public function test_contact_form_requires_recaptcha(): void
    {
        Mail::fake();
        Http::fake();

        $response = $this->from('/contact')->post('/contact', $this->validPayload());

        $response->assertRedirect('/contact')
            ->assertSessionHasErrors('g-recaptcha-response');
        Mail::assertNothingSent();
        Http::assertNothingSent();
    }

    public function test_contact_form_rejects_failed_recaptcha(): void
    {
        Mail::fake();
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
                'score' => 0.1,
                'action' => 'contact',
            ], 200),
        ]);

        $response = $this->from('/contact')->post('/contact', $this->validPayload([
            'g-recaptcha-response' => 'invalid-token',
        ]));

        $response->assertRedirect('/contact')
            ->assertSessionHasErrors('g-recaptcha-response');
        Mail::assertNothingSent();
    }

    public function test_contact_form_rejects_low_recaptcha_score(): void
    {
        Mail::fake();
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.2,
                'action' => 'contact',
            ], 200),
        ]);

        $response = $this->from('/contact')->post('/contact', $this->validPayload([
            'g-recaptcha-response' => 'low-score-token',
        ]));

        $response->assertRedirect('/contact')
            ->assertSessionHasErrors('g-recaptcha-response');
        Mail::assertNothingSent();
    }

    public function test_contact_form_sends_mail_when_recaptcha_succeeds(): void
    {
        Mail::fake();
        Http::fake([
            'www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.9,
                'action' => 'contact',
            ], 200),
        ]);

        $response = $this->post('/contact', $this->validPayload([
            'g-recaptcha-response' => 'valid-token',
        ]));

        $response->assertRedirect(route('contact'))
            ->assertSessionHas('success');
        Mail::assertSent(ContactFormMail::class);
        Http::assertSent(function ($request) {
            return $request->url() === 'https://www.google.com/recaptcha/api/siteverify'
                && $request['response'] === 'valid-token'
                && $request['secret'] === 'test-secret-key';
        });
    }

    /**
     * @param  array<string, string>  $overrides
     * @return array<string, string>
     */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'firstName' => 'Anwar',
            'lastName' => 'Test',
            'email' => 'anwar@example.com',
            'company' => 'TaskCheck',
            'subject' => 'demo',
            'message' => 'Ik wil graag een demo aanvragen.',
        ], $overrides);
    }
}

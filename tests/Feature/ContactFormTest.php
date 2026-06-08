<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_requires_valid_payload(): void
    {
        $response = $this->postJson(route('contact.submit'), [
            'email' => 'not-an-email',
            'message' => 'short',
        ]);

        $response->assertStatus(422);
    }

    public function test_contact_form_sends_mail_with_valid_payload(): void
    {
        Mail::fake();

        $response = $this->postJson(route('contact.submit'), [
            'firstname' => 'Mari',
            'lastname' => 'Maasikas',
            'email' => 'mari@example.com',
            'message' => 'Tere, soovin lisainfot teie toodete kohta.',
        ]);

        $response->assertOk()->assertJson(['success' => true]);
        Mail::assertSent(\App\Mail\ContactFormMail::class);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        $name = trim(($this->data['firstname'] ?? '') . ' ' . ($this->data['lastname'] ?? ''));

        return new Envelope(
            subject: 'Tarukoda kontakt: ' . ($name !== '' ? $name : $this->data['email']),
            replyTo: [$this->data['email']],
        );
    }

    public function content(): Content
    {
        $name = trim(($this->data['firstname'] ?? '') . ' ' . ($this->data['lastname'] ?? ''));

        return new Content(
            text: 'emails.contact',
            with: [
                'name' => $name !== '' ? $name : '—',
            ],
        );
    }
}

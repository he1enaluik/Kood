<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data) {}

    public function envelope(): Envelope
    {
        $name = trim($this->data['firstname'] . ' ' . $this->data['lastname']);

        return new Envelope(
            subject: 'Tarukoda tellimus: ' . $name,
            replyTo: [$this->data['email']],
        );
    }

    public function content(): Content
    {
        return new Content(
            text: 'emails.order',
        );
    }
}

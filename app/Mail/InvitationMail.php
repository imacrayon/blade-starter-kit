<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Invitation $invitation,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('app.name').' Invitation',
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.invitation');
    }
}

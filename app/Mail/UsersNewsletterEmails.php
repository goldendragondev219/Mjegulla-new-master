<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class UsersNewsletterEmails extends Mailable implements ShouldQueue 
{
    use Queueable, SerializesModels;

    public $emailMessage;
    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->emailMessage = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('kontakt@mjegulla.com', 'Mjegulla'),
            subject: '🌟 Ofertë 50% Zbritje! Përdorni Kodin "MJEGULLA50" Tani! 🎁 - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.users_newsletter_email',
            with: [
                'emailMessage' => $this->emailMessage,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class CJTokenExpired extends Mailable
{
    use Queueable, SerializesModels;

    public $lang;
    /**
     * Create a new message instance.
     */
    public function __construct($lang)
    {
        $this->lang = $lang;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        app()->setLocale($this->lang);
        
        return new Envelope(
            from: new Address('kontakt@mjegulla.com', 'Mjegulla'),
            replyTo: [
                new Address('kontakt@mjegulla.com', 'Mjegulla'),
            ],
            subject: trans('emails.cj_token_expired_subject'),
        );
    }

    

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.cjToken_expired',
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

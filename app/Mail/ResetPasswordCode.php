<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class ResetPasswordCode extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $lang;
    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->code = $data['code'];
        $this->lang = $data['lang'];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        app()->setLocale($this->lang);
    

        return new Envelope(
            from: new Address('no-reply@mjegulla.com', 'Mjegulla'),
            subject: trans('emails.password_code_subject', ['code' => $this->code]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset_password_code',
            with: [
                'code' => $this->code,
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

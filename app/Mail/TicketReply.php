<?php

namespace App\Mail;

use Illuminate\Support\Facades\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class TicketReply extends Mailable implements ShouldQueue 
{
    use Queueable, SerializesModels;

    public $name;
    public $lang;
    public $title;
    public $eMailmessage;
    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->lang = $data['lang'];
        $this->title = $data['title'];
        $this->eMailmessage = $data['message'];
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
                new Address('kontakt@mjegulla.com', 'Support'),
            ],
            subject: trans('general.unread_message') . ' ' . $this->title . ' - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.ticket_reply',
            with: [
                'name' => $this->name,
                'title' => $this->title,
                'eMailmessage' => $this->eMailmessage,
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

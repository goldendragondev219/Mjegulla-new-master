<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class OrderComplete extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;
    public $type;
    /**
     * Create a new message instance.
     */
    public function __construct($data, $type)
    {
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {

        if($this->type == 'owner'){
            app()->setLocale($this->data['user']->default_language);
    
            return new Envelope(
                from: new Address('kontakt@mjegulla.com', 'Mjegulla'),
                replyTo: [
                    new Address('kontakt@mjegulla.com', 'Mjegulla'),
                ],
                subject: trans('emails.new_sale_subject', ['shop_name' => $this->data['shop']->shop_name]),
            );
        }else{
            app()->setLocale('en');
    
            return new Envelope(
                from: new Address('kontakt@mjegulla.com', $this->data['shop']->shop_name),
                replyTo: [
                    new Address('kontakt@mjegulla.com', 'Mjegulla'),
                ],
                subject: trans('emails.order_complete_subject', ['shop_name' => $this->data['shop']->shop_name]),
            );
        }
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.order_complete',
            with: [
                'data' => $this->data,
                'type' => $this->type,
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

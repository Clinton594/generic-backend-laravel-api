<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LectureMail extends Mailable
{
    use Queueable, SerializesModels;
    public $message;

    /**
     * Create a new message instance.
     */
    public function __construct(array $message)
    {
        $this->message = $message;
    }

    /**
     * Get the message envelope.
     */

    public function envelope(): Envelope
    {
        $company = Company::get();
        return new Envelope(
            from: new Address($company->notifier, $company->name),
            replyTo: [
                new Address($company->email, $company->name),
            ],
            subject: $this->message['subject'],
        );
    }


    /**
     * Get the message content definition.
     */

    public function content(): Content
    {
        return new Content(
            html: 'email.lecture-template',
            with: $this->message,
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

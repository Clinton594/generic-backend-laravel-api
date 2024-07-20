<?php

namespace App\Mail;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;



class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    private array $message;

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
            view: 'email.notify',
            with: $this->message,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     return [
    //         Attachment::fromPath('https://mailtrap.io/wp-content/uploads/2021/04/mailtrap-new-logo.svg')
    //             ->as('logo.svg')
    //             ->withMime('image/svg+xml'),
    //     ];
    // }
}

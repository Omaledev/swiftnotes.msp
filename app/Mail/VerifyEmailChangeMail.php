<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\EmailChangeToken;

class VerifyEmailChangeMail extends Mailable
{
    use Queueable, SerializesModels;
     public $user;
     public $verificationUrl;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user, EmailChangeToken $token)
    {
        $this->user = $user;
        $this->verificationUrl = url('/settings/email/verify/' . $token->token);
    }


    /**
     * Build the message
     */
    public function build()
    {
            return $this->subject('Confirm Your Email Change')
                    ->view('email.verify-email-change');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Swiftnotes',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'email.verify-email-change',
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

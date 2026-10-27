<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class VerifyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verifikasi Email - Golday',
        );
    }

    public function content(): Content
    {
        $token = sha1($this->user->getEmailForVerification());
        $verificationUrl = URL::signedRoute('verify-email', [
            'user' => $this->user->id,
            'token' => $token,
            'email' => $this->user->email,
        ]);

        return new Content(
            view: 'emails.verify-email',
            with: [
                'user' => $this->user,
                'verificationUrl' => $verificationUrl,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

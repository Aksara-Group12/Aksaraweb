<?php

namespace App\Mail;

use App\Models\Subscribe;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionApproved extends Mailable
{
    use Queueable, SerializesModels;

    public Subscribe $subscription;
    public $user;

    public function __construct(Subscribe $subscription)
    {
        $this->subscription = $subscription;
        $this->user = $subscription->user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Subscription Anda Telah Disetujui! 🎉 - Golday',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.subscription-approved',
            with: [
                'subscription' => $this->subscription,
                'user' => $this->user,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}

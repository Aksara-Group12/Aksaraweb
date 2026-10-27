<?php

namespace App\Mail;

use App\Models\Subscribe;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentProofNotification extends Mailable
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
            subject: 'Bukti Pembayaran Subscription Diterima - Golday',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-proof-notification',
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

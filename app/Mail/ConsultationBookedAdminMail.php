<?php

namespace App\Mail;

use App\Models\ConsultationBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConsultationBookedAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ConsultationBooking $booking,
        public array $company,
        public string $adminUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Consultation Request | Rockmould Limited',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.consultations.booked-admin',
        );
    }
}

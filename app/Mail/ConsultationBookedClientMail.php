<?php

namespace App\Mail;

use App\Models\ConsultationBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConsultationBookedClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ConsultationBooking $booking,
        public array $company,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Consultation Request Received | Rockmould Limited',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.consultations.booked-client',
        );
    }
}

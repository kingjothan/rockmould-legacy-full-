<?php

namespace App\Mail;

use App\Models\ConsultationBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConsultationBookingUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ConsultationBooking $booking,
        public array $company,
        public bool $includeMeetingLink = false,
        public bool $scheduleChanged = false,
        public bool $statusChanged = false,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Consultation Update | Rockmould Limited',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.consultations.booking-updated',
        );
    }
}

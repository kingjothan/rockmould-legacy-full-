<?php

namespace App\Services\Consultations;

use App\Contracts\ConsultationMeetingLinkProvider;
use App\Models\ConsultationBooking;

class ManualConsultationMeetingLinkProvider implements ConsultationMeetingLinkProvider
{
    public function generate(ConsultationBooking $booking): ?string
    {
        /*
         * Manual mode intentionally returns no link. This keeps the booking
         * flow production-ready now while leaving a clean provider contract in
         * place for a future Google Meet or Zoom integration.
         */
        return null;
    }
}

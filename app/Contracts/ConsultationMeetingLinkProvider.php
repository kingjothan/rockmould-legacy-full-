<?php

namespace App\Contracts;

use App\Models\ConsultationBooking;

interface ConsultationMeetingLinkProvider
{
    public function generate(ConsultationBooking $booking): ?string;
}

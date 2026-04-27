<?php

namespace App\Services\Consultations;

use App\Contracts\ConsultationMeetingLinkProvider;
use App\Models\ConsultationBooking;

class MeetingLinkManager
{
    public function __construct(
        protected ConsultationMeetingLinkProvider $provider,
    ) {}

    public function initialLinkFor(ConsultationBooking $booking): ?string
    {
        return $this->provider->generate($booking);
    }
}

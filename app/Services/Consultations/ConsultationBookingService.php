<?php

namespace App\Services\Consultations;

use App\Models\ConsultationBooking;
use App\Support\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ConsultationBookingService
{
    public function __construct(
        protected ConsultationAvailabilityService $availability,
        protected MeetingLinkManager $meetingLinks,
    ) {}

    public function create(array $data): ConsultationBooking
    {
        return DB::transaction(function () use ($data) {
            $slot = $this->availability->assertSlotAvailable($data['consultation_date'], $data['start_time']);
            $timezone = $data['timezone'] ?? $this->availability->settings()->timezone;
            $this->ensureNoConflict($slot['date'], $slot['start_time'].':00', $slot['end_time'].':00');

            $booking = ConsultationBooking::query()->create([
                'reference' => $this->generateReference(),
                'consultation_type' => $data['consultation_type'],
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'company_name' => $data['company_name'] ?? null,
                'subject' => $data['subject'],
                'message' => $data['message'],
                'consultation_date' => $slot['date'],
                'start_time' => $slot['start_time'].':00',
                'end_time' => $slot['end_time'].':00',
                'timezone' => $timezone,
                'status' => ConsultationBooking::STATUS_PENDING,
                'client_ip' => request()->ip(),
            ]);

            $meetingLink = $this->meetingLinks->initialLinkFor($booking);

            if ($meetingLink) {
                $booking->forceFill(['meeting_link' => $meetingLink])->save();
            }

            Activity::log(
                'consultation.booking.created',
                'Consultation booking submitted by '.$booking->full_name.'.',
                null,
                $booking,
                [
                    'type' => $booking->consultation_type,
                    'date' => $booking->consultation_date?->toDateString(),
                    'time' => $booking->formattedTimeRange(),
                ]
            );

            return $booking->fresh('assignedStaff');
        });
    }

    public function updateFromAdmin(ConsultationBooking $booking, array $data): array
    {
        $originalMeetingLink = $booking->meeting_link;
        $originalDate = $booking->consultation_date?->toDateString();
        $originalStartTime = substr((string) $booking->start_time, 0, 5);
        $originalStatus = $booking->status;

        $updatedBooking = DB::transaction(function () use ($booking, $data, $originalDate, $originalStartTime) {
            $dateChanged = ($data['consultation_date'] ?? $originalDate) !== $originalDate;
            $timeChanged = ($data['start_time'] ?? $originalStartTime) !== $originalStartTime;
            $slot = null;

            if ($dateChanged || $timeChanged) {
                $slot = $this->availability->assertSlotAvailable(
                    $data['consultation_date'],
                    $data['start_time'],
                    $booking->id,
                );

                $this->ensureNoConflict(
                    $slot['date'],
                    $slot['start_time'].':00',
                    $slot['end_time'].':00',
                    $booking->id,
                );
            }

            $booking->fill([
                'consultation_type' => $data['consultation_type'],
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'company_name' => $data['company_name'] ?? null,
                'subject' => $data['subject'],
                'message' => $data['message'],
                'consultation_date' => $slot['date'] ?? $data['consultation_date'],
                'start_time' => isset($slot['start_time']) ? $slot['start_time'].':00' : $booking->start_time,
                'end_time' => isset($slot['end_time']) ? $slot['end_time'].':00' : $booking->end_time,
                'timezone' => $data['timezone'],
                'status' => $data['status'],
                'assigned_staff_id' => $data['assigned_staff_id'] ?? null,
                'meeting_link' => $data['meeting_link'] ?? null,
                'admin_notes' => $data['admin_notes'] ?? null,
            ]);

            $booking->save();

            Activity::log(
                'consultation.booking.updated',
                'Consultation booking '.$booking->reference.' updated by admin.',
                Auth::user(),
                $booking,
                [
                    'status' => $booking->status,
                    'assigned_staff_id' => $booking->assigned_staff_id,
                ]
            );

            return $booking->fresh('assignedStaff');
        });

        return [
            'booking' => $updatedBooking,
            'meeting_link_changed' => filled($updatedBooking->meeting_link) && $updatedBooking->meeting_link !== $originalMeetingLink,
            'schedule_changed' => $updatedBooking->consultation_date?->toDateString() !== $originalDate
                || substr((string) $updatedBooking->start_time, 0, 5) !== $originalStartTime,
            'status_changed' => $updatedBooking->status !== $originalStatus,
        ];
    }

    protected function generateReference(): string
    {
        do {
            $reference = 'CONS-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (ConsultationBooking::query()->where('reference', $reference)->exists());

        return $reference;
    }

    protected function ensureNoConflict(string $date, string $startTime, string $endTime, ?int $excludeBookingId = null): void
    {
        $conflictExists = ConsultationBooking::query()
            ->whereDate('consultation_date', $date)
            ->whereIn('status', ConsultationBooking::blockingStatuses())
            ->when($excludeBookingId, fn ($query) => $query->whereKeyNot($excludeBookingId))
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->exists();

        if ($conflictExists) {
            throw ValidationException::withMessages([
                'start_time' => 'The selected consultation slot is no longer available. Please choose another time.',
            ]);
        }
    }
}

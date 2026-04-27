<?php

namespace App\Services\Consultations;

use App\Models\ConsultationAvailability;
use App\Models\ConsultationBlockedDate;
use App\Models\ConsultationBooking;
use App\Models\ConsultationSetting;
use App\Support\SiteData;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ConsultationAvailabilityService
{
    public function settings(): ConsultationSetting
    {
        if (! Schema::hasTable('consultation_settings')) {
            return new ConsultationSetting([
                'timezone' => config('consultations.booking.timezone', config('app.timezone', 'Africa/Lagos')),
                'slot_duration_minutes' => (int) config('consultations.booking.slot_duration_minutes', 60),
                'buffer_minutes' => (int) config('consultations.booking.buffer_minutes', 15),
                'booking_window_days' => (int) config('consultations.booking.booking_window_days', 60),
                'minimum_notice_hours' => (int) config('consultations.booking.minimum_notice_hours', 12),
                'notification_email' => SiteData::company()['support_email'] ?? SiteData::company()['email'] ?? null,
            ]);
        }

        $settings = ConsultationSetting::query()->first();

        if ($settings) {
            $this->ensureDefaultAvailability();

            return $settings;
        }

        $company = SiteData::company();

        $settings = ConsultationSetting::query()->create([
            'timezone' => config('consultations.booking.timezone', config('app.timezone', 'Africa/Lagos')),
            'slot_duration_minutes' => (int) config('consultations.booking.slot_duration_minutes', 60),
            'buffer_minutes' => (int) config('consultations.booking.buffer_minutes', 15),
            'booking_window_days' => (int) config('consultations.booking.booking_window_days', 60),
            'minimum_notice_hours' => (int) config('consultations.booking.minimum_notice_hours', 12),
            'notification_email' => $company['support_email'] ?? $company['email'] ?? null,
        ]);

        $this->ensureDefaultAvailability();

        return $settings;
    }

    public function calendar(CarbonInterface|string|null $month = null, ?string $selectedDate = null, ?int $excludeBookingId = null): array
    {
        $settings = $this->settings();
        $monthDate = $this->resolveMonth($month, $settings->timezone);
        $gridStart = $monthDate->startOfMonth()->startOfWeek(CarbonImmutable::SUNDAY);
        $gridEnd = $monthDate->endOfMonth()->endOfWeek(CarbonImmutable::SATURDAY);

        $availabilities = $this->availabilityWindows()->groupBy('day_of_week');
        $blockedDates = $this->blockedDatesBetween($gridStart->toDateString(), $gridEnd->toDateString())
            ->groupBy(fn ($item) => $item->blocked_on instanceof CarbonInterface ? $item->blocked_on->toDateString() : (string) $item->blocked_on);
        $bookings = $this->bookingsBetween($gridStart->toDateString(), $gridEnd->toDateString(), $excludeBookingId)
            ->groupBy(fn ($item) => $item->consultation_date instanceof CarbonInterface ? $item->consultation_date->toDateString() : (string) $item->consultation_date);

        $days = [];
        $cursor = $gridStart;

        while ($cursor->lte($gridEnd)) {
            $dateKey = $cursor->toDateString();
            $slots = $this->buildSlots(
                $cursor,
                $settings,
                $availabilities->get($cursor->dayOfWeek, collect()),
                $blockedDates->get($dateKey, collect()),
                $bookings->get($dateKey, collect()),
            );

            $days[] = [
                'date' => $dateKey,
                'label' => $cursor->format('D, M j'),
                'day_number' => $cursor->day,
                'day_name' => $cursor->format('D'),
                'is_current_month' => $cursor->month === $monthDate->month,
                'is_today' => $cursor->isSameDay(CarbonImmutable::now($settings->timezone)),
                'is_selected' => $selectedDate === $dateKey,
                'is_available' => count($slots) > 0,
                'is_past' => $cursor->lt(CarbonImmutable::now($settings->timezone)->startOfDay()),
            ];

            $cursor = $cursor->addDay();
        }

        return [
            'month_label' => $monthDate->format('F Y'),
            'month_value' => $monthDate->format('Y-m'),
            'previous_month' => $monthDate->subMonth()->format('Y-m'),
            'next_month' => $monthDate->addMonth()->format('Y-m'),
            'selected_date' => $selectedDate,
            'days' => $days,
        ];
    }

    public function slotsForDate(CarbonInterface|string $date, ?int $excludeBookingId = null): array
    {
        $settings = $this->settings();
        $day = $this->resolveDate($date, $settings->timezone);
        $dateKey = $day->toDateString();

        $availabilities = $this->availabilityWindows()->where('day_of_week', $day->dayOfWeek)->values();
        $blockedDates = $this->blockedDatesBetween($dateKey, $dateKey);
        $bookings = $this->bookingsBetween($dateKey, $dateKey, $excludeBookingId);

        return $this->buildSlots($day, $settings, $availabilities, $blockedDates, $bookings);
    }

    public function assertSlotAvailable(string $date, string $startTime, ?int $excludeBookingId = null): array
    {
        $validator = Validator::make([
            'consultation_date' => $date,
            'start_time' => $startTime,
        ], [
            'consultation_date' => ['required', 'date_format:Y-m-d'],
            'start_time' => ['required', 'date_format:H:i'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $normalizedStart = CarbonImmutable::createFromFormat('H:i', $startTime)->format('H:i');
        $slot = collect($this->slotsForDate($date, $excludeBookingId))
            ->firstWhere('start_time', $normalizedStart);

        if (! $slot) {
            throw ValidationException::withMessages([
                'start_time' => 'The selected consultation slot is no longer available. Please choose another time.',
            ]);
        }

        return $slot;
    }

    protected function buildSlots(
        CarbonImmutable $date,
        ConsultationSetting $settings,
        Collection $availabilities,
        Collection $blockedDates,
        Collection $bookings,
    ): array {
        $now = CarbonImmutable::now($settings->timezone);
        $today = $now->startOfDay();
        $lastBookableDay = $today->addDays(max($settings->booking_window_days, 1));

        if ($date->lt($today) || $date->gt($lastBookableDay)) {
            return [];
        }

        if ($blockedDates->contains(fn (ConsultationBlockedDate $blockedDate) => $blockedDate->is_all_day)) {
            return [];
        }

        $minimumStart = $now->addHours(max($settings->minimum_notice_hours, 0));
        $duration = max($settings->slot_duration_minutes, 15);
        $buffer = max($settings->buffer_minutes, 0);
        $step = $duration + $buffer;
        $slots = [];

        foreach ($availabilities as $availability) {
            $windowStart = $date->setTimeFromTimeString($availability->start_time);
            $windowEnd = $date->setTimeFromTimeString($availability->end_time);
            $cursor = $windowStart;

            while ($cursor->addMinutes($duration)->lte($windowEnd)) {
                $slotStart = $cursor;
                $slotEnd = $cursor->addMinutes($duration);

                if ($slotStart->lt($minimumStart)) {
                    $cursor = $cursor->addMinutes($step);

                    continue;
                }

                if ($this->overlapsBlockedDate($slotStart, $slotEnd, $blockedDates)) {
                    $cursor = $cursor->addMinutes($step);

                    continue;
                }

                if ($this->overlapsExistingBooking($slotStart, $slotEnd, $bookings, $buffer)) {
                    $cursor = $cursor->addMinutes($step);

                    continue;
                }

                $slots[] = [
                    'date' => $date->toDateString(),
                    'start_time' => $slotStart->format('H:i'),
                    'end_time' => $slotEnd->format('H:i'),
                    'start_label' => $slotStart->format('g:i A'),
                    'end_label' => $slotEnd->format('g:i A'),
                    'summary' => $slotStart->format('g:i A').' - '.$slotEnd->format('g:i A'),
                ];

                $cursor = $cursor->addMinutes($step);
            }
        }

        return $slots;
    }

    protected function overlapsBlockedDate(CarbonImmutable $slotStart, CarbonImmutable $slotEnd, Collection $blockedDates): bool
    {
        foreach ($blockedDates as $blockedDate) {
            if ($blockedDate->is_all_day) {
                return true;
            }

            $blockStart = $slotStart->setTimeFromTimeString($blockedDate->start_time ?: '00:00:00');
            $blockEnd = $slotStart->setTimeFromTimeString($blockedDate->end_time ?: '23:59:59');

            if ($slotStart->lt($blockEnd) && $slotEnd->gt($blockStart)) {
                return true;
            }
        }

        return false;
    }

    protected function overlapsExistingBooking(
        CarbonImmutable $slotStart,
        CarbonImmutable $slotEnd,
        Collection $bookings,
        int $buffer,
    ): bool {
        foreach ($bookings as $booking) {
            $bookingStart = $slotStart->setTimeFromTimeString($booking->start_time)->subMinutes($buffer);
            $bookingEnd = $slotStart->setTimeFromTimeString($booking->end_time)->addMinutes($buffer);

            if ($slotStart->lt($bookingEnd) && $slotEnd->gt($bookingStart)) {
                return true;
            }
        }

        return false;
    }

    protected function resolveMonth(CarbonInterface|string|null $month, string $timezone): CarbonImmutable
    {
        if ($month instanceof CarbonInterface) {
            return CarbonImmutable::instance($month)->setTimezone($timezone)->startOfMonth();
        }

        if (is_string($month) && preg_match('/^\d{4}-\d{2}$/', $month) === 1) {
            return CarbonImmutable::createFromFormat('Y-m', $month, $timezone)->startOfMonth();
        }

        return CarbonImmutable::now($timezone)->startOfMonth();
    }

    protected function resolveDate(CarbonInterface|string $date, string $timezone): CarbonImmutable
    {
        if ($date instanceof CarbonInterface) {
            return CarbonImmutable::instance($date)->setTimezone($timezone)->startOfDay();
        }

        return CarbonImmutable::createFromFormat('Y-m-d', $date, $timezone)->startOfDay();
    }

    protected function ensureDefaultAvailability(): void
    {
        if (! Schema::hasTable('consultation_availabilities')) {
            return;
        }

        if (ConsultationAvailability::query()->exists()) {
            return;
        }

        collect([
            ['day_of_week' => 1, 'label' => 'Monday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1, 'is_active' => true],
            ['day_of_week' => 2, 'label' => 'Tuesday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1, 'is_active' => true],
            ['day_of_week' => 3, 'label' => 'Wednesday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1, 'is_active' => true],
            ['day_of_week' => 4, 'label' => 'Thursday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1, 'is_active' => true],
            ['day_of_week' => 5, 'label' => 'Friday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1, 'is_active' => true],
            ['day_of_week' => 6, 'label' => 'Saturday Consultations', 'start_time' => '10:00:00', 'end_time' => '14:00:00', 'sort_order' => 1, 'is_active' => true],
        ])->each(fn (array $window) => ConsultationAvailability::query()->create($window));
    }

    public function tablesExist(): bool
    {
        return Schema::hasTable('consultation_settings')
            && Schema::hasTable('consultation_availabilities')
            && Schema::hasTable('consultation_blocked_dates')
            && Schema::hasTable('consultation_bookings');
    }

    protected function availabilityWindows(): Collection
    {
        if (! Schema::hasTable('consultation_availabilities')) {
            return collect($this->defaultAvailabilityWindows())->map(fn (array $window) => (object) $window);
        }

        $this->ensureDefaultAvailability();

        return ConsultationAvailability::query()
            ->where('is_active', true)
            ->orderBy('day_of_week')
            ->orderBy('sort_order')
            ->orderBy('start_time')
            ->get();
    }

    protected function blockedDatesBetween(string $start, string $end): Collection
    {
        if (! Schema::hasTable('consultation_blocked_dates')) {
            return collect();
        }

        return ConsultationBlockedDate::query()
            ->whereBetween('blocked_on', [$start, $end])
            ->get();
    }

    protected function bookingsBetween(string $start, string $end, ?int $excludeBookingId = null): Collection
    {
        if (! Schema::hasTable('consultation_bookings')) {
            return collect();
        }

        return ConsultationBooking::query()
            ->whereBetween('consultation_date', [$start, $end])
            ->whereIn('status', ConsultationBooking::blockingStatuses())
            ->when($excludeBookingId, fn ($query) => $query->whereKeyNot($excludeBookingId))
            ->orderBy('consultation_date')
            ->orderBy('start_time')
            ->get();
    }

    protected function defaultAvailabilityWindows(): array
    {
        return [
            ['day_of_week' => 1, 'label' => 'Monday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1, 'is_active' => true],
            ['day_of_week' => 2, 'label' => 'Tuesday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1, 'is_active' => true],
            ['day_of_week' => 3, 'label' => 'Wednesday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1, 'is_active' => true],
            ['day_of_week' => 4, 'label' => 'Thursday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1, 'is_active' => true],
            ['day_of_week' => 5, 'label' => 'Friday Consultations', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'sort_order' => 1, 'is_active' => true],
            ['day_of_week' => 6, 'label' => 'Saturday Consultations', 'start_time' => '10:00:00', 'end_time' => '14:00:00', 'sort_order' => 1, 'is_active' => true],
        ];
    }
}

<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ConsultationBooking extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_COMPLETED = 'completed';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUS_NO_SHOW = 'no_show';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'consultation_date' => 'date',
        ];
    }

    public static function blockingStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
        ];
    }

    public static function availableStatuses(): array
    {
        return array_keys(config('consultations.statuses', []));
    }

    public function assignedStaff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (! Schema::hasTable($this->getTable())) {
            throw (new ModelNotFoundException)->setModel(static::class, [$value]);
        }

        return parent::resolveRouteBinding($value, $field);
    }

    public function getConsultationTypeLabelAttribute(): string
    {
        return (string) (config('consultations.types.'.$this->consultation_type)
            ?? Str::of($this->consultation_type)->replace('_', ' ')->title());
    }

    public function getStatusLabelAttribute(): string
    {
        return (string) (config('consultations.statuses.'.$this->status)
            ?? Str::of($this->status)->replace('_', ' ')->title());
    }

    public function formattedDate(string $format = 'D, M j, Y'): string
    {
        return $this->consultation_date?->timezone($this->timezone)->format($format) ?? '';
    }

    public function formattedTimeRange(): string
    {
        return trim($this->formattedTime($this->start_time).' - '.$this->formattedTime($this->end_time));
    }

    public function scheduledStart(): CarbonImmutable
    {
        return $this->buildMoment($this->start_time);
    }

    public function scheduledEnd(): CarbonImmutable
    {
        return $this->buildMoment($this->end_time);
    }

    protected function formattedTime(?string $time): string
    {
        if (! $time) {
            return '';
        }

        return CarbonImmutable::createFromFormat('H:i:s', $time)->format('g:i A');
    }

    protected function buildMoment(string $time): CarbonImmutable
    {
        $date = $this->consultation_date?->format('Y-m-d') ?? now()->toDateString();
        $normalizedTime = Str::length($time) === 5 ? $time.':00' : $time;

        return CarbonImmutable::createFromFormat(
            'Y-m-d H:i:s',
            $date.' '.$normalizedTime,
            $this->timezone ?: config('consultations.booking.timezone', config('app.timezone'))
        );
    }
}

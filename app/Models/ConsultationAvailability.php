<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ConsultationAvailability extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'day_of_week' => 'integer',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (! Schema::hasTable($this->getTable())) {
            throw (new ModelNotFoundException)->setModel(static::class, [$value]);
        }

        return parent::resolveRouteBinding($value, $field);
    }

    public function getDayLabelAttribute(): string
    {
        return Str::of(now()->startOfWeek(CarbonImmutable::SUNDAY)->addDays($this->day_of_week)->format('l'))->toString();
    }
}

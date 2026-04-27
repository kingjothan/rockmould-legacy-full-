<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'slot_duration_minutes' => 'integer',
            'buffer_minutes' => 'integer',
            'booking_window_days' => 'integer',
            'minimum_notice_hours' => 'integer',
        ];
    }
}

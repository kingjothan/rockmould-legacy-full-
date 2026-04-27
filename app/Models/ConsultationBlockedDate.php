<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Schema;

class ConsultationBlockedDate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'blocked_on' => 'date',
            'is_all_day' => 'boolean',
        ];
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (! Schema::hasTable($this->getTable())) {
            throw (new ModelNotFoundException)->setModel(static::class, [$value]);
        }

        return parent::resolveRouteBinding($value, $field);
    }
}

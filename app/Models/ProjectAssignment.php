<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectAssignment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
            'project_id' => 'integer',
            'contract_amount' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'outstanding_balance' => 'decimal:2',
            'next_payment_amount' => 'decimal:2',
            'next_payment_due_at' => 'date',
            'start_date' => 'date',
            'handover_date' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)->latest('paid_at');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function mediaAssets(): HasMany
    {
        return $this->hasMany(MediaAsset::class)->orderBy('sort_order');
    }

    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }
}

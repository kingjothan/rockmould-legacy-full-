@props(['value'])

@php
    $tone = match (strtolower((string) $value)) {
        'active', 'ongoing', 'open', 'paid', 'confirmed', 'portfolio' => 'status-active',
        'completed', 'delivered', 'published' => 'status-completed',
        'pending', 'upcoming', 'overdue', 'needs_update' => 'status-pending',
        'draft', 'standard' => 'status-inactive',
        default => 'status-inactive',
    };
@endphp

<span class="{{ $tone }}">{{ str($value)->replace('_', ' ')->title() }}</span>

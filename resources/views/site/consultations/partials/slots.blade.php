@php
    $selectedStartTime = $selectedStartTime ?? old('start_time');
@endphp

@if (! $selectedDate)
    <div class="rounded-2xl border border-dashed border-border bg-card/60 px-5 py-8 text-center text-sm text-muted-foreground">
        Pick a date to view the available time slots.
    </div>
@elseif (count($slots) === 0)
    <div class="rounded-2xl border border-dashed border-border bg-card/60 px-5 py-8 text-center">
        <p class="font-display text-2xl">No Available Slots</p>
        <p class="mt-3 text-sm leading-relaxed text-muted-foreground">
            That day is currently full or unavailable. Please choose another date.
        </p>
    </div>
@else
    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($slots as $slot)
            <button
                type="button"
                class="consultation-slot {{ $selectedStartTime === $slot['start_time'] ? 'is-selected' : '' }}"
                data-consultation-slot
                data-start="{{ $slot['start_time'] }}"
                data-end="{{ $slot['end_time'] }}"
            >
                <span class="consultation-slot-time">{{ $slot['start_label'] }}</span>
                <span class="consultation-slot-meta">{{ $slot['summary'] }}</span>
            </button>
        @endforeach
    </div>
@endif

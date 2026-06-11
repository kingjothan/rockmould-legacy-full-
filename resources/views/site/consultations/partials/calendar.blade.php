<div class="space-y-5">
    <div class="flex items-center justify-between gap-3 rounded-2xl border border-border bg-card/70 px-4 py-3">
        <button
            type="button"
            class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background/80 text-foreground transition hover:border-primary/40 hover:text-primary"
            data-calendar-nav
            data-month="{{ $calendar['previous_month'] }}"
            aria-label="View previous month"
        >
            <x-portal-icon name="chevron-left" class="h-4 w-4" />
        </button>
        <div class="text-center">
            <p class="text-[10px] uppercase tracking-[0.22em] text-primary/70">Available Dates</p>
            <p class="mt-1 font-display text-2xl">{{ $calendar['month_label'] }}</p>
        </div>
        <button
            type="button"
            class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-border bg-background/80 text-foreground transition hover:border-primary/40 hover:text-primary"
            data-calendar-nav
            data-month="{{ $calendar['next_month'] }}"
            aria-label="View next month"
        >
            <x-portal-icon name="chevron-right" class="h-4 w-4" />
        </button>
    </div>

    <div class="grid grid-cols-7 gap-1 text-center text-[8px] uppercase tracking-[0.1em] text-muted-foreground sm:gap-2 sm:text-[10px] sm:tracking-[0.22em]">
        @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $weekday)
            <div class="py-2">{{ $weekday }}</div>
        @endforeach
    </div>

    <div class="grid grid-cols-7 gap-1 sm:gap-2">
        @foreach ($calendar['days'] as $day)
            @php
                $classes = 'consultation-calendar-day';

                if ($day['is_selected']) {
                    $classes .= ' is-selected';
                }

                if (! $day['is_current_month']) {
                    $classes .= ' is-outside';
                }

                if ($day['is_available']) {
                    $classes .= ' is-available';
                }
            @endphp

            <button
                type="button"
                class="{{ $classes }}"
                data-calendar-day
                data-date="{{ $day['date'] }}"
                @disabled(! $day['is_available'] || ! $day['is_current_month'])
            >
                <span class="consultation-calendar-day-number">{{ $day['day_number'] }}</span>
                <span class="consultation-calendar-day-state">
                    {{ $day['is_available'] ? 'Open' : ($day['is_past'] ? 'Past' : 'Closed') }}
                </span>
            </button>
        @endforeach
    </div>
</div>

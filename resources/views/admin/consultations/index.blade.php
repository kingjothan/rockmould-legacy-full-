<x-layouts.admin :company="$company" page-heading="Consultations">
    <div class="space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.24em] text-primary/70">Consultation Management</p>
                <h1 class="mt-2 font-display text-3xl font-semibold">Client booking requests, scheduling rules, and availability controls.</h1>
                <p class="mt-2 max-w-3xl text-sm text-muted-foreground">
                    Manage incoming consultations, define working hours, block unavailable dates, and keep meeting details organized inside the existing Rockmould admin.
                </p>
            </div>
        </div>

        @if (! $moduleReady)
            <div class="luxury-card border-primary/20 bg-card/75">
                <p class="section-label">Module Setup Required</p>
                <h2 class="mt-3 font-display text-2xl">Consultation tables are not ready on this environment.</h2>
                <p class="mt-4 max-w-3xl text-sm leading-relaxed text-muted-foreground">
                    The website will stay online, but consultation booking management will remain in read-only fallback mode until the consultation migrations are applied.
                    Run <code class="rounded bg-background px-2 py-1 text-xs text-foreground">php artisan migrate</code> to finish enabling bookings, schedules, blocked dates, and meeting-link management here.
                </p>
            </div>
        @endif

        @if ($moduleReady)
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
            <div class="stat-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Total Bookings</p>
                <p class="mt-2 font-display text-3xl">{{ $stats['total'] }}</p>
            </div>
            <div class="stat-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Pending</p>
                <p class="mt-2 font-display text-3xl">{{ $stats['pending'] }}</p>
            </div>
            <div class="stat-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Confirmed</p>
                <p class="mt-2 font-display text-3xl">{{ $stats['confirmed'] }}</p>
            </div>
            <div class="stat-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Upcoming</p>
                <p class="mt-2 font-display text-3xl">{{ $stats['upcoming'] }}</p>
            </div>
            <div class="stat-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Completed</p>
                <p class="mt-2 font-display text-3xl">{{ $stats['completed'] }}</p>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.24fr_0.76fr]">
            <div class="min-w-0 space-y-8">
                <div class="luxury-card overflow-hidden p-0">
                    <div class="border-b border-border px-6 py-5">
                        <div class="flex flex-col gap-4 lg:flex-row">
                            <form method="GET" class="grid flex-1 gap-3 md:grid-cols-[1fr_180px_auto]">
                                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search by client, email, phone, type, or subject..." class="input-shell">
                                <select name="status" class="input-shell">
                                    <option value="">All statuses</option>
                                    @foreach ($statuses as $value => $label)
                                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <button class="luxury-btn-outline min-w-max">Filter</button>
                            </form>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="table-shell">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Type</th>
                                    <th>Schedule</th>
                                    <th>Status</th>
                                    <th>Assigned</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($bookings as $booking)
                                    <tr class="table-row-shell">
                                        <td>
                                            <div class="font-medium">{{ $booking->full_name }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $booking->email }} | {{ $booking->phone }}</div>
                                        </td>
                                        <td>
                                            <div class="text-sm">{{ $booking->consultation_type_label }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $booking->subject }}</div>
                                        </td>
                                        <td>
                                            <div class="text-sm">{{ $booking->formattedDate('M j, Y') }}</div>
                                            <div class="text-xs text-muted-foreground">{{ $booking->formattedTimeRange() }}</div>
                                        </td>
                                        <td><x-status-badge :value="$booking->status" /></td>
                                        <td class="text-sm text-muted-foreground">{{ $booking->assignedStaff?->name ?? 'Unassigned' }}</td>
                                        <td><a href="{{ route('admin.consultations.show', $booking) }}" class="text-primary transition hover:text-primary/80">Open</a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-12 text-center text-sm text-muted-foreground">No consultation bookings matched the current filters.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($bookings->hasPages())
                        <div class="border-t border-border px-6 py-4">
                            {{ $bookings->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="min-w-0 space-y-6">
                <div class="luxury-card">
                    <div class="flex items-center gap-2">
                        <x-portal-icon name="settings" class="h-4 w-4 text-primary" />
                        <h2 class="font-display text-xl font-semibold">Scheduling Settings</h2>
                    </div>
                    <form action="{{ route('admin.consultations.settings.update') }}" method="POST" class="mt-5 space-y-4">
                        @csrf
                        <div>
                            <label class="admin-label">Timezone</label>
                            <input name="timezone" value="{{ old('timezone', $settings->timezone) }}" class="input-shell">
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Slot Duration (Minutes)</label>
                                <input type="number" name="slot_duration_minutes" min="30" max="180" value="{{ old('slot_duration_minutes', $settings->slot_duration_minutes) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Buffer Between Meetings</label>
                                <input type="number" name="buffer_minutes" min="0" max="120" value="{{ old('buffer_minutes', $settings->buffer_minutes) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Booking Window (Days)</label>
                                <input type="number" name="booking_window_days" min="7" max="180" value="{{ old('booking_window_days', $settings->booking_window_days) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Minimum Notice (Hours)</label>
                                <input type="number" name="minimum_notice_hours" min="0" max="168" value="{{ old('minimum_notice_hours', $settings->minimum_notice_hours) }}" class="input-shell">
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">Notification Email</label>
                            <input type="email" name="notification_email" value="{{ old('notification_email', $settings->notification_email) }}" class="input-shell">
                        </div>
                        <button class="luxury-btn-primary w-full">Save Scheduling Settings</button>
                    </form>
                </div>

                <div class="luxury-card">
                    <div class="flex items-center gap-2">
                        <x-portal-icon name="calendar" class="h-4 w-4 text-primary" />
                        <h2 class="font-display text-xl font-semibold">Working Hours</h2>
                    </div>
                    <form action="{{ route('admin.consultations.availability.store') }}" method="POST" class="mt-5 grid gap-4">
                        @csrf
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Day of Week</label>
                                <select name="day_of_week" class="input-shell">
                                    @foreach ([0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'] as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="admin-label">Label</label>
                                <input name="label" placeholder="e.g. Morning Session" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Start Time</label>
                                <input type="time" name="start_time" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">End Time</label>
                                <input type="time" name="end_time" class="input-shell">
                            </div>
                        </div>
                        <button class="luxury-btn-outline">Add Availability Window</button>
                    </form>

                    <div class="mt-6 space-y-3">
                        @forelse ($availabilityWindows as $window)
                            <form action="{{ route('admin.consultations.availability.update', $window) }}" method="POST" class="rounded-2xl border border-border bg-card/60 p-4">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="sort_order" value="{{ $window->sort_order }}">
                                <input type="hidden" name="is_active" value="1">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="admin-label">Day</label>
                                        <select name="day_of_week" class="input-shell text-sm">
                                            @foreach ([0 => 'Sunday', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday'] as $value => $label)
                                                <option value="{{ $value }}" @selected($window->day_of_week === $value)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="admin-label">Label</label>
                                        <input name="label" value="{{ $window->label }}" class="input-shell text-sm">
                                    </div>
                                    <div>
                                        <label class="admin-label">Start</label>
                                        <input type="time" name="start_time" value="{{ substr($window->start_time, 0, 5) }}" class="input-shell text-sm">
                                    </div>
                                    <div>
                                        <label class="admin-label">End</label>
                                        <input type="time" name="end_time" value="{{ substr($window->end_time, 0, 5) }}" class="input-shell text-sm">
                                    </div>
                                    <div class="col-span-2 flex gap-2">
                                        <button class="luxury-btn-outline min-w-max flex-1 px-4 py-3 text-xs">Save</button>
                                        <button form="availability-delete-{{ $window->id }}" class="flex-1 rounded-full border border-destructive/30 px-4 py-3 text-xs uppercase tracking-[0.18em] text-destructive transition hover:bg-destructive/10">Delete</button>
                                    </div>
                                </div>
                            </form>
                            <form id="availability-delete-{{ $window->id }}" action="{{ route('admin.consultations.availability.destroy', $window) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        @empty
                            <p class="text-sm text-muted-foreground">No availability windows have been configured yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="luxury-card">
                    <div class="flex items-center gap-2">
                        <x-portal-icon name="x" class="h-4 w-4 text-primary" />
                        <h2 class="font-display text-xl font-semibold">Blocked Dates</h2>
                    </div>
                    <form action="{{ route('admin.consultations.blocked-dates.store') }}" method="POST" class="mt-5 grid gap-4">
                        @csrf
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Date</label>
                                <input type="date" name="blocked_on" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Reason</label>
                                <input name="reason" placeholder="Holiday, retreat, unavailable..." class="input-shell">
                            </div>
                        </div>
                        <label class="flex items-center gap-3 text-sm text-muted-foreground">
                            <input type="checkbox" name="is_all_day" value="1" checked>
                            Block the full day
                        </label>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Start Time</label>
                                <input type="time" name="start_time" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">End Time</label>
                                <input type="time" name="end_time" class="input-shell">
                            </div>
                        </div>
                        <button class="luxury-btn-outline">Save Blocked Date</button>
                    </form>

                    <div class="mt-6 space-y-3">
                        @forelse ($blockedDates as $blockedDate)
                            <div class="flex flex-col gap-4 rounded-2xl border border-border bg-card/60 p-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="font-medium">{{ $blockedDate->blocked_on->format('l, F j, Y') }}</p>
                                    <p class="mt-1 text-xs text-muted-foreground">
                                        {{ $blockedDate->is_all_day ? 'All day' : substr((string) $blockedDate->start_time, 0, 5).' - '.substr((string) $blockedDate->end_time, 0, 5) }}
                                        @if ($blockedDate->reason)
                                            | {{ $blockedDate->reason }}
                                        @endif
                                    </p>
                                </div>
                                <form action="{{ route('admin.consultations.blocked-dates.destroy', $blockedDate) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-full border border-destructive/30 px-4 py-3 text-xs uppercase tracking-[0.18em] text-destructive transition hover:bg-destructive/10">Delete</button>
                                </form>
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">No blocked dates have been added yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @else
            <div class="grid gap-6 xl:grid-cols-2">
                <div class="luxury-card">
                    <p class="section-label">Current State</p>
                    <h2 class="mt-3 font-display text-2xl">Booking management is waiting on database setup.</h2>
                    <p class="mt-4 text-sm leading-relaxed text-muted-foreground">
                        As soon as the consultation tables are available, this page will automatically show booking records, search filters, availability controls, blocked dates, staff assignment, and meeting-link management.
                    </p>
                </div>
                <div class="luxury-card">
                    <p class="section-label">What's Missing</p>
                    <div class="mt-5 space-y-3 text-sm text-muted-foreground">
                        <p>Consultation bookings table</p>
                        <p>Consultation settings table</p>
                        <p>Consultation availability windows</p>
                        <p>Consultation blocked dates</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.admin>


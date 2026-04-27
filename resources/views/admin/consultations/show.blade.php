<x-layouts.admin :company="$company" page-heading="Consultation Detail">
    <div class="space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.24em] text-primary/70">Consultation Booking</p>
                <h1 class="mt-2 font-display text-3xl font-semibold">{{ $booking->full_name }}</h1>
                <p class="mt-2 text-sm text-muted-foreground">{{ $booking->consultation_type_label }} | {{ $booking->reference }}</p>
            </div>
            <div class="flex items-center gap-3">
                <x-status-badge :value="$booking->status" />
                <a href="{{ route('admin.consultations.index') }}" class="luxury-btn-outline">Back to Consultations</a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="stat-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Scheduled Date</p>
                <p class="mt-2 font-display text-2xl">{{ $booking->formattedDate('M j, Y') }}</p>
            </div>
            <div class="stat-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Scheduled Time</p>
                <p class="mt-2 font-display text-2xl">{{ $booking->formattedTimeRange() }}</p>
            </div>
            <div class="stat-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Assigned Staff</p>
                <p class="mt-2 font-display text-2xl">{{ $booking->assignedStaff?->name ?? 'Unassigned' }}</p>
            </div>
            <div class="stat-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Meeting Link</p>
                <p class="mt-2 text-sm text-muted-foreground break-all">{{ $booking->meeting_link ?: 'Not attached yet' }}</p>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="luxury-card">
                <h2 class="font-display text-2xl">Booking Details</h2>
                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="consultation-summary-row">
                        <span class="consultation-summary-label">Email</span>
                        <span class="consultation-summary-value">{{ $booking->email }}</span>
                    </div>
                    <div class="consultation-summary-row">
                        <span class="consultation-summary-label">Phone</span>
                        <span class="consultation-summary-value">{{ $booking->phone }}</span>
                    </div>
                    <div class="consultation-summary-row">
                        <span class="consultation-summary-label">Company</span>
                        <span class="consultation-summary-value">{{ $booking->company_name ?: 'Not provided' }}</span>
                    </div>
                    <div class="consultation-summary-row">
                        <span class="consultation-summary-label">Timezone</span>
                        <span class="consultation-summary-value">{{ $booking->timezone }}</span>
                    </div>
                    <div class="consultation-summary-row md:col-span-2">
                        <span class="consultation-summary-label">Subject</span>
                        <span class="consultation-summary-value">{{ $booking->subject }}</span>
                    </div>
                    <div class="consultation-summary-row md:col-span-2">
                        <span class="consultation-summary-label">Client Message</span>
                        <span class="consultation-summary-value leading-relaxed">{{ $booking->message }}</span>
                    </div>
                </div>
            </div>

            <div class="luxury-card">
                <h2 class="font-display text-2xl">Admin Actions</h2>
                <form action="{{ route('admin.consultations.update', $booking) }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Full Name</label>
                            <input name="full_name" value="{{ old('full_name', $booking->full_name) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $booking->email) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Phone Number</label>
                            <input name="phone" value="{{ old('phone', $booking->phone) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Company Name</label>
                            <input name="company_name" value="{{ old('company_name', $booking->company_name) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Consultation Type</label>
                            <select name="consultation_type" class="input-shell">
                                @foreach ($consultationTypes as $value => $label)
                                    <option value="{{ $value }}" @selected(old('consultation_type', $booking->consultation_type) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="admin-label">Status</label>
                            <select name="status" class="input-shell">
                                @foreach ($statuses as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $booking->status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="admin-label">Date</label>
                            <input type="date" name="consultation_date" value="{{ old('consultation_date', $booking->consultation_date?->format('Y-m-d')) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Start Time</label>
                            <input type="time" name="start_time" value="{{ old('start_time', substr((string) $booking->start_time, 0, 5)) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Timezone</label>
                            <input name="timezone" value="{{ old('timezone', $booking->timezone ?: $settings->timezone) }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Assigned Staff</label>
                            <select name="assigned_staff_id" class="input-shell">
                                <option value="">Unassigned</option>
                                @foreach ($staffMembers as $staff)
                                    <option value="{{ $staff->id }}" @selected((string) old('assigned_staff_id', $booking->assigned_staff_id) === (string) $staff->id)>{{ $staff->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="admin-label">Subject</label>
                            <input name="subject" value="{{ old('subject', $booking->subject) }}" class="input-shell">
                        </div>
                        <div class="md:col-span-2">
                            <label class="admin-label">Meeting Link</label>
                            <input name="meeting_link" value="{{ old('meeting_link', $booking->meeting_link) }}" placeholder="https://meet.google.com/..." class="input-shell">
                        </div>
                        <div class="md:col-span-2">
                            <label class="admin-label">Internal Notes</label>
                            <textarea name="admin_notes" rows="4" class="input-shell">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="admin-label">Client Message</label>
                            <textarea name="message" rows="6" class="input-shell">{{ old('message', $booking->message) }}</textarea>
                        </div>
                    </div>

                    <button class="luxury-btn-primary w-full">Update Booking</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>

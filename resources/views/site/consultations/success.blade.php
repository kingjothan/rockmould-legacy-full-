<x-layouts.public :company="$company" title="Consultation Booked | Rockmould Limited">
    <section class="section-padding">
        <div class="container-luxury">
            <div class="mx-auto max-w-4xl luxury-card">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
                    <x-portal-icon name="check-circle" class="h-8 w-8 text-primary" />
                </div>
                <div class="mt-6 text-center">
                    <p class="section-label">Consultation Booked</p>
                    <h1 class="mt-4 font-display text-4xl md:text-5xl">Your Consultation Request Is In</h1>
                    <p class="mx-auto mt-5 max-w-2xl text-lg leading-relaxed text-muted-foreground">
                        Thank you for booking with Rockmould. Your request is in our system and a confirmation email has been sent with the consultation summary and next steps.
                    </p>
                </div>

                <div class="mt-10 grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="rounded-2xl border border-border bg-card/60 p-6">
                        <p class="text-[10px] uppercase tracking-[0.22em] text-primary/70">Booking Summary</p>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            <div class="consultation-summary-row">
                                <span class="consultation-summary-label">Reference</span>
                                <span class="consultation-summary-value">{{ $booking->reference }}</span>
                            </div>
                            <div class="consultation-summary-row">
                                <span class="consultation-summary-label">Status</span>
                                <span class="consultation-summary-value">{{ $booking->status_label }}</span>
                            </div>
                            <div class="consultation-summary-row">
                                <span class="consultation-summary-label">Consultation Type</span>
                                <span class="consultation-summary-value">{{ $booking->consultation_type_label }}</span>
                            </div>
                            <div class="consultation-summary-row">
                                <span class="consultation-summary-label">Date</span>
                                <span class="consultation-summary-value">{{ $booking->formattedDate('l, F j, Y') }}</span>
                            </div>
                            <div class="consultation-summary-row sm:col-span-2">
                                <span class="consultation-summary-label">Time</span>
                                <span class="consultation-summary-value">{{ $booking->formattedTimeRange() }} ({{ $booking->timezone }})</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-border bg-card/60 p-6">
                        <p class="text-[10px] uppercase tracking-[0.22em] text-primary/70">Next Steps</p>
                        <div class="mt-5 space-y-4">
                            @foreach ([
                                'A Rockmould team member will review your request and confirm the session status.',
                                'If a meeting link is required, it will be attached and emailed to you once finalized.',
                                'If anything changes, you can reach the team directly using the contact details below.',
                            ] as $item)
                                <div class="check-item">
                                    <svg class="check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    <p class="text-sm text-muted-foreground">{{ $item }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('projects.index') }}" class="luxury-btn-primary">Explore Projects</a>
                    <a href="{{ route('contact') }}" class="luxury-btn-outline">Contact Rockmould</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

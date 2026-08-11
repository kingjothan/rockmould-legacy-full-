<x-layouts.public :company="$company" title="Schedule Consultation | Rockmould Limited">
    <section class="border-b border-border bg-card/40">
        <div class="container-luxury py-12 md:py-16">
            <div class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr] xl:items-end">
                <div>
                    <span class="section-label">Consultation Booking</span>
                    <h1 class="mt-5 font-display text-4xl leading-tight md:text-5xl xl:text-6xl">
                        Schedule a Premium
                        <span class="text-gold-gradient">Rockmould Consultation</span>
                    </h1>
                    <p class="mt-6 max-w-3xl text-lg leading-relaxed text-muted-foreground">
                        Choose the right consultation type, select an available time, and share the context we need to prepare a focused conversation.
                    </p>
                </div>

                <div class="luxury-card">
                    <p class="text-[10px] uppercase tracking-[0.22em] text-primary/70">What to Expect</p>
                    <div class="mt-5 space-y-4">
                        @foreach ([
                            'Choose the consultation format that best fits your enquiry.',
                            'Pick a live availability slot from the Rockmould calendar.',
                            'Receive a branded confirmation instantly by email.',
                        ] as $item)
                            <div class="check-item">
                                <svg class="check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <p class="text-sm text-muted-foreground">{{ $item }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding pt-10">
        @if (! $moduleReady)
            <div class="container-luxury grid gap-6 xl:grid-cols-[1.12fr_0.88fr]">
                <div class="luxury-card border-primary/20 bg-card/70">
                    <span class="section-label">Scheduling Update</span>
                    <h2 class="mt-4 font-display text-3xl md:text-4xl">Consultation scheduling is temporarily unavailable.</h2>
                    <p class="mt-5 max-w-2xl text-base leading-relaxed text-muted-foreground">
                        The booking calendar is being finalized on this environment right now. You can still reach Rockmould directly and our team will help you arrange the right consultation manually.
                    </p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('contact') }}" class="luxury-btn-primary justify-center sm:w-auto">Go to Contact</a>
                        <a href="mailto:{{ $company['email'] }}" class="luxury-btn-outline justify-center sm:w-auto">Email Rockmould</a>
                    </div>
                </div>

                <div class="luxury-card">
                    <p class="section-label">Direct Support</p>
                    <h3 class="mt-3 font-display text-2xl">Speak with the team now</h3>
                    <div class="mt-6 space-y-4 text-sm leading-relaxed">
                        <p><span class="text-muted-foreground">Phone:</span> <a href="tel:{{ preg_replace('/\s+/', '', $company['phone']) }}" class="text-primary hover:text-primary/80">{{ $company['phone'] }}</a></p>
                        <p><span class="text-muted-foreground">Email:</span> <a href="mailto:{{ $company['email'] }}" class="text-primary hover:text-primary/80">{{ $company['email'] }}</a></p>
                        @if (! empty($company['address']))
                            <p><span class="text-muted-foreground">Office:</span> <a href="https://maps.google.com/?q={{ urlencode($company['address']) }}" target="_blank" rel="noopener noreferrer" class="text-primary hover:text-primary/80">{{ $company['address'] }}</a></p>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div
                class="container-luxury grid gap-8 xl:grid-cols-[1.08fr_0.92fr]"
                data-consultation-booking
                data-availability-url="{{ route('consultations.availability') }}"
                data-initial-type="{{ $selectedType }}"
                data-initial-date="{{ $selectedDate }}"
                data-initial-time="{{ $selectedStartTime }}"
            >
                <div class="space-y-6">
                    <div class="luxury-card">
                        <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                            <div>
                                <p class="section-label">Simple Booking Flow</p>
                                <h2 class="mt-3 font-display text-3xl">Choose a type, date, and time.</h2>
                            </div>
                            <p class="max-w-xl text-sm leading-relaxed text-muted-foreground">
                                Keep it simple: pick the consultation you need, choose an available slot, then leave your contact details.
                            </p>
                        </div>

                        <div class="mt-8 grid gap-4 md:grid-cols-2">
                            @foreach ($consultationTypes as $value => $label)
                                <button
                                    type="button"
                                    class="consultation-type-card {{ $selectedType === $value ? 'is-selected' : '' }}"
                                    data-consultation-type
                                    data-value="{{ $value }}"
                                >
                                    <span class="consultation-type-kicker">Consultation Type</span>
                                    <span class="consultation-type-title">{{ $label }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="luxury-card">
                        <div class="grid gap-8 xl:grid-cols-[0.92fr_1.08fr]">
                            <div>
                                <p class="section-label">Choose Date</p>
                                <h3 class="mt-3 font-display text-2xl">Available Days</h3>
                                <div class="mt-6" data-consultation-calendar>
                                    @include('site.consultations.partials.calendar', ['calendar' => $calendar])
                                </div>
                            </div>

                            <div>
                                <p class="section-label">Choose Time</p>
                                <h3 class="mt-3 font-display text-2xl">Available Slots</h3>
                                <div class="mt-6" data-consultation-slots>
                                    @include('site.consultations.partials.slots', ['slots' => $slots, 'selectedDate' => $selectedDate, 'selectedStartTime' => $selectedStartTime])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="space-y-6 xl:sticky xl:top-28 xl:self-start">
                    <form action="{{ route('consultations.store') }}" method="POST" class="luxury-card space-y-6">
                        @csrf

                        <input type="hidden" name="consultation_type" value="{{ $selectedType }}" data-consultation-type-input>
                        <input type="hidden" name="consultation_date" value="{{ $selectedDate }}" data-consultation-date-input>
                        <input type="hidden" name="start_time" value="{{ $selectedStartTime }}" data-consultation-time-input>
                        <input type="hidden" name="timezone" value="{{ old('timezone', $settings->timezone) }}" data-consultation-timezone-input>

                        <div class="sr-only" aria-hidden="true">
                            <label for="website">Website</label>
                            <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                        </div>

                        <div>
                            <p class="section-label">Your Booking</p>
                            <h2 class="mt-3 font-display text-3xl">Consultation Details</h2>
                            <p class="mt-3 text-sm leading-relaxed text-muted-foreground">
                                Complete your contact details and we’ll handle the rest.
                            </p>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="consultation-summary-row">
                                <span class="consultation-summary-label">Type</span>
                                <span class="consultation-summary-value" data-summary-type>{{ $selectedType ? ($consultationTypes[$selectedType] ?? 'Choose a type') : 'Choose a type' }}</span>
                            </div>
                            <div class="consultation-summary-row">
                                <span class="consultation-summary-label">Date</span>
                                <span class="consultation-summary-value" data-summary-date>{{ $selectedDate ?: 'Choose a date' }}</span>
                            </div>
                            <div class="consultation-summary-row">
                                <span class="consultation-summary-label">Time</span>
                                <span class="consultation-summary-value" data-summary-time>{{ $selectedStartTime ?: 'Choose a time' }}</span>
                            </div>
                            <div class="consultation-summary-row">
                                <span class="consultation-summary-label">Timezone</span>
                                <span class="consultation-summary-value" data-summary-timezone>{{ old('timezone', $settings->timezone) }}</span>
                            </div>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Full Name</label>
                                <input name="full_name" value="{{ old('full_name') }}" required class="input-shell" placeholder="Your full name">
                            </div>
                            <div>
                                <label class="admin-label">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="input-shell" placeholder="you@example.com">
                            </div>
                            <div>
                                <label class="admin-label">Phone Number</label>
                                <input name="phone" value="{{ old('phone') }}" required class="input-shell" placeholder="+234...">
                            </div>
                            <div>
                                <label class="admin-label">Company Name <span class="text-muted-foreground">(Optional)</span></label>
                                <input name="company_name" value="{{ old('company_name') }}" class="input-shell" placeholder="Company or organization">
                            </div>
                            <div class="md:col-span-2">
                                <label class="admin-label">Project or Inquiry Subject</label>
                                <input name="subject" value="{{ $subjectPrefill }}" required class="input-shell" placeholder="What would you like to discuss?">
                            </div>
                            <div class="md:col-span-2">
                                <label class="admin-label">Consultation Details</label>
                                <textarea name="message" rows="5" required class="input-shell" placeholder="Share a short summary so the team can prepare for the meeting.">{{ old('message') }}</textarea>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-border bg-card/50 px-4 py-4 text-sm leading-relaxed text-muted-foreground">
                            Once you submit, the slot is reserved in our system and Rockmould will send the confirmation details by email.
                        </div>

                        <button class="luxury-btn-primary w-full justify-center">
                            Schedule Consultation
                            <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </button>
                    </form>

                    <div class="luxury-card">
                        <p class="section-label">Need Help?</p>
                        <h3 class="mt-3 font-display text-2xl">Speak to Rockmould</h3>
                        <p class="mt-4 text-sm leading-relaxed text-muted-foreground">
                            If you’d rather speak with someone first, our team is available to help you choose the right consultation.
                        </p>
                        <div class="mt-6 space-y-3 text-sm">
                            <p><span class="text-muted-foreground">Phone:</span> <a href="tel:{{ preg_replace('/\s+/', '', $company['phone']) }}" class="text-primary hover:text-primary/80">{{ $company['phone'] }}</a></p>
                            <p><span class="text-muted-foreground">Email:</span> <a href="mailto:{{ $company['email'] }}" class="text-primary hover:text-primary/80">{{ $company['email'] }}</a></p>
                        </div>
                    </div>
                </aside>
            </div>
        @endif
    </section>
</x-layouts.public>

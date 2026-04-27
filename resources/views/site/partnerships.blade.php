<x-layouts.public :company="$company" title="Partnerships and Investment | Rockmould Limited">
    <x-hero
        :title="$page['hero_title'] ?? 'Partnerships and Investment'"
        :subtitle="$page['hero_subtitle'] ?? 'Strategic Capital and Project Relationships'"
        :description="$page['hero_description'] ?? 'Rockmould works with investors, landowners, and project partners who value clarity, governance, and premium execution.'"
        :image="$page['hero_media_path'] ?? 'images/project-4.jpg'"
    />

    <section class="section-padding">
        <div class="container-luxury grid gap-12 lg:grid-cols-[1fr_0.9fr]">
            <div class="space-y-8 reveal-left">
                <div class="luxury-card">
                    <h2 class="font-display text-2xl font-semibold">{{ $page['reasons_title'] ?? 'Why Invest With Rockmould' }}</h2>
                    <div class="mt-5 space-y-4">
                        @foreach ($page['reasons'] ?? [] as $reason)
                            <div class="check-item">
                                <svg class="check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                <p class="text-sm leading-relaxed text-muted-foreground">{{ $reason }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="grid gap-6 md:grid-cols-3">
                    @foreach ($metrics as $metric)
                        <x-metric-card :value="$metric['value']" :label="$metric['label']" />
                    @endforeach
                </div>
            </div>

            <div class="luxury-card reveal-right">
                <h3 class="mb-6 font-display text-2xl font-semibold">{{ $page['form_title'] ?? 'Request a Private Consultation' }}</h3>
                @if (session('success'))
                    <div class="mb-5 flex items-center gap-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-400">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('forms.partnership') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="admin-label">Full Name</label>
                        <input name="full_name" required class="input-shell" value="{{ old('full_name') }}">
                    </div>
                    <div>
                        <label class="admin-label">Company Name</label>
                        <input name="company_name" class="input-shell" value="{{ old('company_name') }}">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="admin-label">Email Address</label>
                            <input type="email" name="email" required class="input-shell" value="{{ old('email') }}">
                        </div>
                        <div>
                            <label class="admin-label">Phone Number</label>
                            <input name="phone" class="input-shell" value="{{ old('phone') }}">
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Interest Type</label>
                        <select name="interest_type" class="input-shell">
                            <option value="">Select an option</option>
                            <option>Co-Development</option>
                            <option>Strategic Capital</option>
                            <option>Project Mandate</option>
                            <option>Land-Led Opportunity</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Message</label>
                        <textarea name="message" rows="6" required class="input-shell">{{ old('message') }}</textarea>
                    </div>
                    <button class="luxury-btn-primary w-full">{{ $page['submit_button'] ?? 'Submit Inquiry' }}
                        <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="section-padding bg-card">
        <div class="container-luxury">
            <x-section-heading :label="$page['opportunities_label'] ?? 'Current Opportunities'" :title="$page['opportunities_title'] ?? 'Selected Projects'" />
            <div class="grid gap-8 md:grid-cols-3">
                @foreach ($projects as $project)
                    <div class="reveal">
                        <x-project-card :project="$project" />
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.public>

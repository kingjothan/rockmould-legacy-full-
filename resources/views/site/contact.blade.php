<x-layouts.public :company="$company" title="Contact | Rockmould Limited" description="Get in touch with Rockmould Limited. Enquire about projects, investment opportunities, or schedule a consultation with our team in Lagos.">
    <x-hero
        :title="$page['hero_title'] ?? 'Get in Touch'"
        :subtitle="$page['hero_subtitle'] ?? 'Contact Us'"
        :description="$page['hero_description'] ?? \"Whether you're interested in projects, services, investment opportunities, or partnerships, we'd love to hear from you.\""
        :image="$page['hero_media_path'] ?? 'images/project-1.jpg'"
        :title-size="$page['font_sizes']['hero_title'] ?? null"
        :description-size="$page['font_sizes']['hero_description'] ?? null"
    />

    <section class="section-padding">
        <div class="container-luxury grid gap-12 lg:grid-cols-[0.9fr_1.3fr]">
            <div class="space-y-6 reveal-left">
                <div class="luxury-card">
                    <h3 class="mb-6 font-display text-2xl font-semibold">{{ $page['info_title'] ?? 'Contact Information' }}</h3>
                    <div class="space-y-5">
                        <div class="info-row">
                            <svg class="info-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            <div>
                                <span class="block text-[10px] uppercase tracking-[0.18em] text-primary mb-0.5">Phone</span>
                                <span class="text-sm text-foreground">{{ $company['phone'] }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <svg class="info-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            <div>
                                <span class="block text-[10px] uppercase tracking-[0.18em] text-primary mb-0.5">Email</span>
                                <span class="text-sm text-foreground">{{ $company['email'] }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <svg class="info-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            <div>
                                <span class="block text-[10px] uppercase tracking-[0.18em] text-primary mb-0.5">Office</span>
                                @php $cleanAddress = rtrim(preg_replace('/[\x{2028}\x{2029}\x{200B}]/u', '', $company['address'] ?? ''), '. '); @endphp
                                <a href="https://maps.google.com/?q={{ urlencode($cleanAddress) }}" target="_blank" rel="noopener noreferrer" class="text-sm text-foreground hover:text-primary transition-colors">{{ $cleanAddress }}</a>
                            </div>
                        </div>
                        <div class="info-row">
                            <svg class="info-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <span class="block text-[10px] uppercase tracking-[0.18em] text-primary mb-0.5">Hours</span>
                                <span class="text-sm text-foreground">{{ $company['hours'] }}</span>
                            </div>
                        </div>
                        <a href="https://wa.me/message/UMPBJLP3KWF5L1" target="_blank" rel="noopener noreferrer" class="info-row group">
                            <svg class="info-icon text-primary group-hover:text-primary" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.122 1.532 5.855L.058 23.447a.5.5 0 00.613.605l5.748-1.505A11.944 11.944 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.9a9.893 9.893 0 01-5.031-1.374l-.36-.214-3.733.977.998-3.645-.235-.374A9.862 9.862 0 012.1 12C2.1 6.533 6.533 2.1 12 2.1S21.9 6.533 21.9 12 17.467 21.9 12 21.9z"/></svg>
                            <div>
                                <span class="block text-[10px] uppercase tracking-[0.18em] text-primary mb-0.5">WhatsApp</span>
                                <span class="text-sm text-foreground transition-colors group-hover:text-primary">Chat with us on WhatsApp</span>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="luxury-card">
                    <h3 class="mb-4 font-display text-xl font-semibold">{{ $page['quick_access_title'] ?? 'Quick Access' }}</h3>
                    <div class="space-y-2">
                        <a href="{{ route('portal.login') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm text-muted-foreground transition-colors hover:bg-muted/40 hover:text-foreground">
                            <svg class="h-4 w-4 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            Client Portal
                        </a>
                        <a href="{{ route('projects.index') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm text-muted-foreground transition-colors hover:bg-muted/40 hover:text-foreground">
                            <svg class="h-4 w-4 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                            View Projects
                        </a>
                        <a href="{{ route('services') }}" class="flex items-center gap-2 rounded-md px-3 py-2 text-sm text-muted-foreground transition-colors hover:bg-muted/40 hover:text-foreground">
                            <svg class="h-4 w-4 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                            Our Services
                        </a>
                    </div>
                </div>
            </div>

            <div class="luxury-card reveal-right">
                <h3 class="mb-6 font-display text-2xl font-semibold">{{ $page['form_title'] ?? 'Send Us a Message' }}</h3>
                @if (session('success'))
                    <div class="mb-6 flex items-center gap-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-400">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('forms.contact') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Full Name</label>
                            <input name="full_name" required class="input-shell" value="{{ old('full_name') }}">
                        </div>
                        <div>
                            <label class="admin-label">Email Address</label>
                            <input type="email" name="email" required class="input-shell" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="admin-label">Phone Number</label>
                            <input name="phone" class="input-shell" value="{{ old('phone') }}">
                        </div>
                        <div>
                            <label class="admin-label">Department</label>
                            <select name="department" class="input-shell">
                                <option>General Inquiry</option>
                                <option>Sales and Projects</option>
                                <option>Partnerships and Investment</option>
                                <option>Construction Services</option>
                                <option>Client Support</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Message</label>
                        <textarea name="message" rows="6" required class="input-shell">{{ old('message') }}</textarea>
                    </div>
                    <button class="luxury-btn-primary">Send Message
                        <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-layouts.public>

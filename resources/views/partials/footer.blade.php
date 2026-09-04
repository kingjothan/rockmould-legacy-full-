@php
    $footerData = \App\Support\SiteData::footer();
    $footerGroups = \App\Support\SiteData::footerGroups();
    $footerTagline = $footerData['tagline'] ?? 'Building legacies in real estate and construction. Premium developments, enduring quality, and unwavering commitment to excellence.';
    $newsletterTitle = $footerData['newsletter_title'] ?? 'Stay Informed';
    $newsletterDescription = $footerData['newsletter_description'] ?? 'Subscribe for project updates and market insights.';
@endphp

<footer class="border-t border-border bg-card">
    <div class="container-luxury section-padding">
        <div class="grid gap-12 lg:grid-cols-6 lg:gap-8">
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="mb-6 flex items-center gap-3">
                    <x-brand-mark class="h-10 w-auto" />
                </a>
                <p class="mb-8 max-w-sm text-sm leading-relaxed text-muted-foreground">
                    {{ $footerTagline }}
                </p>
                <div class="space-y-3">
                    <a href="tel:{{ preg_replace('/\s+/', '', $company['phone']) }}" class="flex items-center gap-3 text-sm text-muted-foreground transition-colors hover:text-primary">
                        <span class="text-primary">&bull;</span>
                        <span>{{ $company['phone'] }}</span>
                    </a>
                    <a href="mailto:{{ $company['email'] }}" class="flex items-center gap-3 text-sm text-muted-foreground transition-colors hover:text-primary">
                        <span class="text-primary">&bull;</span>
                        <span>{{ $company['email'] }}</span>
                    </a>
                    <a href="https://wa.me/message/UMPBJLP3KWF5L1" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 text-sm text-primary/70 transition-colors hover:text-primary">
                        <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.122 1.532 5.855L.058 23.447a.5.5 0 00.613.605l5.748-1.505A11.944 11.944 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.9a9.893 9.893 0 01-5.031-1.374l-.36-.214-3.733.977.998-3.645-.235-.374A9.862 9.862 0 012.1 12C2.1 6.533 6.533 2.1 12 2.1S21.9 6.533 21.9 12 17.467 21.9 12 21.9z"/></svg>
                        <span>Chat on WhatsApp</span>
                    </a>
                    <div class="flex items-start gap-3 text-sm text-muted-foreground">
                        <span class="mt-0.5 text-primary">&bull;</span>
                        <a href="https://maps.google.com/?q={{ urlencode($company['address']) }}" target="_blank" rel="noopener noreferrer" class="hover:text-primary transition-colors">{{ $company['address'] }}</a>
                    </div>
                </div>
            </div>

            @foreach ($footerGroups as $group)
                <div>
                    <h4 class="mb-6 text-xs font-medium uppercase tracking-[0.2em] text-foreground">{{ $group['title'] }}</h4>
                    <ul class="space-y-3">
                        @foreach ($group['links'] as $link)
                            <li><a href="{{ $link['url'] }}" class="text-sm text-muted-foreground transition-colors hover:text-primary">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="luxury-divider my-12"></div>
        <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
            <div>
                <h4 class="font-display text-lg">{{ $newsletterTitle }}</h4>
                <p class="text-sm text-muted-foreground">{{ $newsletterDescription }}</p>
            </div>
            <form action="{{ route('forms.newsletter') }}" method="POST" class="flex w-full gap-2 md:w-auto">
                @csrf
                <input type="email" name="email" required placeholder="Enter your email" class="input-shell w-full md:w-72">
                <button type="submit" class="luxury-btn-primary">Subscribe</button>
            </form>
        </div>

        <div class="luxury-divider my-12"></div>
        <div class="flex flex-col items-center justify-between gap-4 md:flex-row">
            <p class="text-xs text-muted-foreground">
                Copyright {{ now()->year }} All rights reserved.
            </p>
            <div class="flex items-center gap-6">
                <a href="{{ route('privacy') }}" class="text-xs text-muted-foreground hover:text-primary">Privacy Policy</a>
                <a href="{{ route('terms') }}" class="text-xs text-muted-foreground hover:text-primary">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

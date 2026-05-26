@php($footerGroups = \App\Support\SiteData::footerGroups())

<footer class="border-t border-border bg-card">
    <div class="container-luxury section-padding">
        <div class="grid gap-12 lg:grid-cols-6 lg:gap-8">
            <div class="lg:col-span-2">
                <a href="{{ route('home') }}" class="mb-6 flex items-center gap-3">
                    <x-brand-mark class="h-10 w-auto" />
                </a>
                <p class="mb-8 max-w-sm text-sm leading-relaxed text-muted-foreground">
                    Building legacies in real estate and construction. Premium developments, enduring quality, and unwavering commitment to excellence.
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
                    <div class="flex items-start gap-3 text-sm text-muted-foreground">
                        <span class="mt-0.5 text-primary">&bull;</span>
                        <span>{{ $company['address'] }}</span>
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
                <h4 class="font-display text-lg">Stay Informed</h4>
                <p class="text-sm text-muted-foreground">Subscribe for project updates and market insights.</p>
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
                Copyright {{ now()->year }}
                <a href="https://kingjothan.netlify.app/" target="_blank" rel="noopener noreferrer" class="text-primary transition-colors hover:text-primary/80">KJ</a><a href="https://www.instagram.com/king_jothan/" target="_blank" rel="noopener noreferrer" class="text-primary/70 transition-colors hover:text-primary" aria-label="KJ on Instagram">.</a>
                All rights reserved.
            </p>
            <div class="flex items-center gap-6">
                <a href="{{ route('privacy') }}" class="text-xs text-muted-foreground hover:text-primary">Privacy Policy</a>
                <a href="{{ route('terms') }}" class="text-xs text-muted-foreground hover:text-primary">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

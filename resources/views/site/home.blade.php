<x-layouts.public :company="$company" title="Rockmould Limited | Luxury Real Estate and Construction">
    @php
        $heroMediaPath = $homepage['hero_media_path'] ?? 'images/project-1.jpg';
        $featuredSection = $homepage['featured_section'] ?? [];
        $servicesSection = $homepage['services_section'] ?? [];
        $whySection = $homepage['why_section'] ?? [];
        $processSection = $homepage['process_section'] ?? [];
        $testimonialsSection = $homepage['testimonials_section'] ?? [];
        $insightsSection = $homepage['insights_section'] ?? [];
        $portalSection = $homepage['portal_section'] ?? [];
        $finalCta = $homepage['final_cta'] ?? [];
        $fontSizes = $homepage['font_sizes'] ?? [];
    @endphp
    <section class="hero-shell relative flex min-h-screen items-center overflow-hidden">
        <x-media :path="$heroMediaPath" fallback="images/project-1.jpg" alt="Rockmould luxury development" class="hero-media" :background="true" :lightbox="false" />
        <div class="hero-soft-overlay"></div>
        <div class="hero-overlay"></div>
        <div class="container-luxury w-full relative z-10 py-32">
            <div class="max-w-4xl">
                <span class="section-label reveal">{{ $homepage['hero_label'] }}</span>
                <h1 class="public-hero-title reveal font-display font-bold leading-[1.1]">
                    @if (!empty($homepage['hero_lines']))
                        @foreach ($homepage['hero_lines'] as $line)
                            <span class="block {{ $line['size'] ?? 'text-5xl' }}">{{ $line['text'] }}</span>
                        @endforeach
                    @else
                        <span class="block text-4xl md:text-5xl lg:text-6xl xl:text-7xl">{{ $homepage['hero_title'] }}</span>
                    @endif
                </h1>
                <p class="reveal mt-8 max-w-2xl {{ $homepage['hero_description_size'] ?? 'text-lg' }} leading-relaxed text-muted-foreground">{{ $homepage['hero_description'] }}</p>
                <div class="reveal mt-10 flex flex-wrap gap-4">
                    <a href="{{ route('projects.index') }}" class="luxury-btn-primary">
                        {{ $homepage['hero_primary_cta'] }}
                        <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('consultations.create') }}" class="luxury-btn-outline">{{ $homepage['hero_secondary_cta'] }}</a>
                    <a href="{{ route('portal.login') }}" class="luxury-btn-ghost text-primary">{{ $homepage['hero_portal_cta'] }} &rsaquo;</a>
                </div>
            </div>
        </div>
    </section>

    <section class="section-padding bg-background">
        <div class="container-luxury">
            <x-section-heading
                :label="$featuredSection['label'] ?? 'Featured Developments'"
                :title="$featuredSection['title'] ?? 'Exceptional Projects, Extraordinary Standards'"
                :description="$featuredSection['description'] ?? 'From premium residences to landmark commercial spaces, every Rockmould project embodies craftsmanship and vision.'"
                :title-size="$fontSizes['featured_title'] ?? null"
                :description-size="$fontSizes['featured_description'] ?? null"
            />
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredProjects as $project)
                    <div class="reveal">
                        <x-project-card :project="$project" />
                    </div>
                @endforeach
            </div>
            <div class="mt-12 text-center">
                <a href="{{ route('projects.index') }}" class="luxury-btn-outline">
                    {{ $featuredSection['cta'] ?? 'View All Projects' }}
                    <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>

    <section class="section-padding bg-card">
        <div class="container-luxury">
            <x-section-heading
                :label="$servicesSection['label'] ?? 'Our Services'"
                :title="$servicesSection['title'] ?? 'Comprehensive Real Estate & Construction Solutions'"
                :description="$servicesSection['description'] ?? 'End-to-end expertise across every facet of property development.'"
                :title-size="$fontSizes['services_title'] ?? null"
                :description-size="$fontSizes['services_description'] ?? null"
            />
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($services as $service)
                    <div class="luxury-card reveal">
                        <p class="section-label">{{ $service->tagline }}</p>
                        <h3 class="mt-3 font-display text-lg font-semibold">{{ $service->title }}</h3>
                        <p class="mt-3 text-sm leading-relaxed text-muted-foreground">{{ $service->summary }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container-luxury grid grid-cols-1 gap-16 lg:grid-cols-2 lg:items-center">
            <div class="reveal-left">
                <x-section-heading
                    :label="$whySection['label'] ?? 'Why Rockmould'"
                    :title="$whySection['title'] ?? 'A Legacy of Excellence in Every Foundation'"
                    align="left"
                    :title-size="$fontSizes['why_title'] ?? null"
                />
                <div class="space-y-4">
                    @foreach ($homepage['why_choose'] as $point)
                        <div class="check-item">
                            <svg class="check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <p class="text-muted-foreground">{{ $point }}</p>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('about') }}" class="luxury-btn-primary mt-10 inline-flex items-center gap-2">
                    {{ $whySection['cta'] ?? 'Learn More' }}
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
            <div class="reveal-right img-zoom overflow-hidden rounded-md">
                <x-media :path="$homepage['feature_image'] ?? null" fallback="images/project-4.jpg" alt="Luxury interior" class="aspect-[4/3] w-full object-cover" />
            </div>
        </div>
    </section>

    <section class="border-y border-border bg-card py-20">
        <div class="container-luxury">
            <div class="grid grid-cols-2 gap-8 md:grid-cols-4" style="align-items:start;justify-items:center">
                @foreach ($homepage['stats'] as $stat)
                    <x-metric-card :value="$stat['value']" :label="$stat['label']" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container-luxury">
            <x-section-heading
                :label="$processSection['label'] ?? 'Our Process'"
                :title="$processSection['title'] ?? 'From Vision to Reality'"
                :description="$processSection['description'] ?? 'A refined process that ensures every project is delivered with precision and excellence.'"
                :title-size="$fontSizes['process_title'] ?? null"
                :description-size="$fontSizes['process_description'] ?? null"
            />
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($homepage['process'] as $step)
                    <div class="relative reveal animation-delay-{{ ($loop->index * 100) > 400 ? 400 : $loop->index * 100 }}">
                        <span class="font-display text-6xl font-bold text-primary/10">{{ $step['number'] }}</span>
                        <h3 class="-mt-4 mb-3 font-display text-xl font-semibold">{{ $step['title'] }}</h3>
                        <p class="text-sm leading-relaxed text-muted-foreground">{{ $step['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-padding bg-card">
        <div class="container-luxury">
            <x-section-heading
                :label="$testimonialsSection['label'] ?? 'Client Stories'"
                :title="$testimonialsSection['title'] ?? 'Trusted by Visionary Clients'"
                :title-size="$fontSizes['testimonials_title'] ?? null"
            />
            <div class="mx-auto grid max-w-4xl grid-cols-1 gap-8 md:grid-cols-2">
                @foreach ($testimonials as $testimonial)
                    <div class="luxury-card reveal">
                        <div class="star-rating mb-4">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="h-4 w-4 star-filled" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <div class="quote-card">
                            <span class="quote-mark">&ldquo;</span>
                            <p class="relative mb-6 pt-2 text-muted-foreground">{{ $testimonial->quote }}</p>
                        </div>
                        <div>
                            <div class="mb-3 flex items-center gap-3">
                                @if ($testimonial->image_url)
                                    <x-media :path="$testimonial->image_path" alt="{{ $testimonial->client_name }}" class="h-10 w-10 rounded-full object-cover" />
                                @else
                                    <div class="avatar text-xs">
                                        {{ strtoupper(substr($testimonial->client_name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-semibold">{{ $testimonial->client_name }}</p>
                                    <p class="text-xs text-muted-foreground">{{ $testimonial->role }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container-luxury">
            <x-section-heading
                :label="$insightsSection['label'] ?? 'Insights'"
                :title="$insightsSection['title'] ?? 'Latest from Rockmould'"
                :description="$insightsSection['description'] ?? 'Market insights, project updates, and thought leadership from our team.'"
                :title-size="$fontSizes['insights_title'] ?? null"
                :description-size="$fontSizes['insights_description'] ?? null"
            />
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                @foreach ($posts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="group block overflow-hidden rounded-lg border border-border bg-card reveal transition-colors hover:border-primary/30">
                        <div class="aspect-[16/9] bg-muted overflow-hidden">
                            <x-media :path="$post->cover_image" fallback="images/project-3.jpg" alt="{{ $post->title }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />
                        </div>
                        <div class="p-6">
                            <div class="mb-3 flex items-center gap-3">
                                <span class="text-[11px] uppercase tracking-[0.15em] text-primary">{{ $post->category }}</span>
                                <span class="text-[11px] text-muted-foreground">{{ optional($post->published_at)->format('M d, Y') }}</span>
                            </div>
                            <h3 class="font-display text-lg font-semibold transition-colors group-hover:text-primary">{{ $post->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-y border-border bg-card py-20">
        <div class="container-luxury text-center">
            <div class="mx-auto mb-5 flex h-14 w-14 items-center justify-center rounded-full bg-primary/10">
                <svg class="h-7 w-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h2 class="font-display text-4xl">{{ $portalSection['title'] ?? 'Client Portal' }}</h2>
            <p class="mx-auto mt-4 max-w-xl text-muted-foreground">
                {{ $portalSection['description'] ?? 'Track your projects, view documents, monitor payments, and stay updated in one secure, premium portal.' }}
            </p>
            <a href="{{ route('portal.login') }}" class="luxury-btn-primary mt-8">
                {{ $portalSection['cta'] ?? 'Access Portal' }}
                <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </section>

    <section class="section-padding">
        <div class="container-luxury">
            <div class="luxury-card bg-gradient-to-br from-card to-secondary p-12 text-center md:p-16">
                <h2 class="font-display text-4xl md:text-5xl">{{ $finalCta['title'] ?? 'Ready to Build Your Legacy?' }}</h2>
                <p class="mx-auto mt-4 max-w-xl text-lg text-muted-foreground">
                    {{ $finalCta['description'] ?? "Let's discuss your next premium development or investment opportunity." }}
                </p>
                <div class="mt-10 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('consultations.create') }}" class="luxury-btn-primary">{{ $finalCta['primary_cta'] ?? 'Schedule Consultation' }}
                        <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                    <a href="{{ route('projects.index') }}" class="luxury-btn-outline">{{ $finalCta['secondary_cta'] ?? 'Browse Projects' }}</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

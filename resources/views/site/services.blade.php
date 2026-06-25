<x-layouts.public :company="$company" title="Services | Rockmould Limited">
    <x-hero
        :title="$page['hero_title'] ?? 'Comprehensive Solutions for Premium Properties'"
        :subtitle="$page['hero_subtitle'] ?? 'Our Services'"
        :description="$page['hero_description'] ?? 'From vision to delivery, Rockmould offers a complete suite of real estate and construction services engineered for excellence.'"
        :image="$page['hero_media_path'] ?? 'images/project-3.jpg'"
        :title-size="$page['font_sizes']['hero_title'] ?? null"
        :description-size="$page['font_sizes']['hero_description'] ?? null"
    />

    <section class="section-padding">
        <div class="container-luxury space-y-24">
            @foreach ($services as $service)
                <div id="{{ $service->slug }}" class="grid grid-cols-1 gap-16 lg:grid-cols-2 lg:items-start">
                    <div class="{{ $loop->odd ? 'reveal-left' : 'reveal-right lg:order-2' }}">
                        <div class="mb-6 flex items-center gap-4">
                            <div class="feature-item-icon">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
                            </div>
                            <span class="section-label">Service</span>
                        </div>
                        <h2 class="mb-4 font-display text-3xl font-semibold md:text-4xl">{{ $service->title }}</h2>
                        <p class="leading-relaxed text-muted-foreground">{{ $service->description ?: $service->summary }}</p>
                        @if (!empty($service->benefits))
                            <div class="mb-8 mt-8 space-y-3">
                                @foreach ($service->benefits as $benefit)
                                    <div class="check-item">
                                        <svg class="check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        <span class="text-sm text-muted-foreground">{{ $benefit }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <a href="{{ route('contact') }}" class="luxury-btn-primary text-xs">
                            Inquire About This Service
                            <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                    <div class="{{ $loop->odd ? 'reveal-right' : 'reveal-left lg:order-1' }}">
                        <div class="img-zoom aspect-[4/3] overflow-hidden rounded-md bg-muted">
                            <x-media :path="$service->image_path" fallback="images/project-3.jpg" alt="{{ $service->title }}" class="h-full w-full object-cover" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="section-padding bg-card">
        <div class="container-luxury text-center">
            <h2 class="font-display text-3xl font-semibold md:text-4xl">{{ $page['cta_title'] ?? 'Need a Tailored Solution?' }}</h2>
            <p class="mx-auto mt-4 max-w-xl text-muted-foreground">
                {{ $page['cta_description'] ?? 'Every project is unique. Let us understand your needs and craft the perfect approach.' }}
            </p>
            <a href="{{ route('consultations.create') }}" class="luxury-btn-primary mt-8">{{ $page['cta_button'] ?? 'Book a Consultation' }}
                <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </section>
</x-layouts.public>

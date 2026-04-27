<x-layouts.public :company="$company" title="Testimonials | Rockmould Limited">
    <x-hero
        :title="$page['hero_title'] ?? 'Trust Built Through Delivery'"
        :subtitle="$page['hero_subtitle'] ?? 'Testimonials'"
        :description="$page['hero_description'] ?? 'Selected stories from clients who value clarity, quality, and disciplined project execution.'"
        :image="$page['hero_media_path'] ?? 'images/project-2.jpg'"
    />

    <section class="section-padding">
        <div class="container-luxury grid gap-8 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($testimonials as $testimonial)
                <div class="luxury-card reveal">
                    <div class="star-rating mb-4">
                        @for ($i = 0; $i < 5; $i++)
                            <svg class="h-4 w-4 star-filled" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <div class="quote-card">
                        <span class="quote-mark">&ldquo;</span>
                        <p class="relative pt-2 text-sm leading-relaxed text-muted-foreground">{{ $testimonial->quote }}</p>
                    </div>
                    <div class="mt-6 flex items-center gap-3 border-t border-border pt-5">
                        @if ($testimonial->image_url)
                            <x-media :path="$testimonial->image_path" alt="{{ $testimonial->client_name }}" class="h-11 w-11 rounded-full object-cover" />
                        @else
                            <div class="avatar text-xs">
                                {{ strtoupper(substr($testimonial->client_name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-foreground">{{ $testimonial->client_name }}</p>
                            <p class="text-xs uppercase tracking-[0.15em] text-primary">{{ $testimonial->role }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</x-layouts.public>

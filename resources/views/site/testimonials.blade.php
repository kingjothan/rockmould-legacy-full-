<x-layouts.public :company="$company" title="Testimonials | Rockmould Limited">
    <x-hero
        :title="$page['hero_title'] ?? 'Trust Built Through Delivery'"
        :subtitle="$page['hero_subtitle'] ?? 'Testimonials'"
        :description="$page['hero_description'] ?? 'Selected stories from clients who value clarity, quality, and disciplined project execution.'"
        :image="$page['hero_media_path'] ?? 'images/project-2.jpg'"
        :title-size="$page['font_sizes']['hero_title'] ?? null"
        :description-size="$page['font_sizes']['hero_description'] ?? null"
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

    <section class="section-padding bg-card">
        <div class="container-luxury max-w-2xl">
            <div class="text-center mb-10">
                <span class="section-label">Share Your Experience</span>
                <h2 class="font-display text-3xl font-semibold mt-3">Leave a Review</h2>
                <p class="mt-3 text-sm text-muted-foreground">We value your feedback. Tell us about your experience with Rockmould.</p>
            </div>
            <form action="{{ route('forms.review') }}" method="POST" class="luxury-card space-y-6 p-8">
                @csrf
                <div>
                    <label class="admin-label">Your Name <span class="text-destructive">*</span></label>
                    <input type="text" name="client_name" value="{{ old('client_name') }}" required class="input-shell w-full" placeholder="Full name">
                </div>
                <div>
                    <label class="admin-label">Title / Role</label>
                    <input type="text" name="role" value="{{ old('role') }}" class="input-shell w-full" placeholder="e.g. Property Investor, Homeowner">
                </div>
                <div>
                    <label class="admin-label">Rating <span class="text-destructive">*</span></label>
                    <div class="flex gap-1" id="star-rating">
                        @for ($i = 1; $i <= 5; $i++)
                            <button type="button" data-star="{{ $i }}" class="star-btn text-muted-foreground/30 transition-colors hover:text-primary">
                                <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </button>
                        @endfor
                        <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', '') }}">
                    </div>
                </div>
                <div>
                    <label class="admin-label">Your Review <span class="text-destructive">*</span></label>
                    <textarea name="quote" required rows="4" class="input-shell w-full" placeholder="Share your experience working with Rockmould...">{{ old('quote') }}</textarea>
                </div>
                <button type="submit" class="luxury-btn-primary w-full justify-center">Submit Review</button>
            </form>
        </div>
    </section>
</x-layouts.public>

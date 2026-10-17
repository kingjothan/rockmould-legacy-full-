<x-layouts.public :company="$company" title="Portfolio | Rockmould Limited">
    <x-hero :title="$portfolio['hero_title']" :subtitle="$portfolio['hero_subtitle']" :description="$portfolio['hero_description']" :image="$page['hero_media_path'] ?? 'images/project-4.jpg'" height="medium" :title-size="$portfolio['font_sizes']['hero_title'] ?? null" :description-size="$portfolio['font_sizes']['hero_description'] ?? null" />

    <section class="section-padding">
        <div class="container-luxury space-y-16">
            @forelse ($projects as $project)
                <div class="grid grid-cols-1 gap-12 items-center reveal lg:grid-cols-2">
                    <div class="{{ $loop->iteration % 2 === 0 ? 'lg:order-2' : '' }}">
                        <div class="img-zoom overflow-hidden rounded-md aspect-[16/10]">
                            <x-media :path="$project->cover_image" fallback="images/project-1.jpg" alt="{{ $project->title }}" class="h-full w-full object-cover" />
                        </div>
                    </div>

                    <div class="{{ $loop->iteration % 2 === 0 ? 'lg:order-1' : '' }}">
                        <x-status-badge :value="$project->status" class="mb-3" />
                        <h2 class="font-display text-3xl font-semibold mt-3">{{ $project->title }}</h2>
                        <p class="mt-2 text-sm text-muted-foreground">{{ $project->location }}</p>
                        <p class="mt-4 leading-relaxed text-muted-foreground">{{ $project->residence_profile ?: ($project->description ?: $project->overview) }}</p>

                        @php
                            $cards = [];
                            if ($project->built_area)      $cards[] = ['label' => 'Built Area',       'value' => $project->built_area];
                            if ($project->completion_date) $cards[] = ['label' => 'Completion',       'value' => \Carbon\Carbon::parse($project->completion_date)->format('M Y')];
                        @endphp

                        @if (!empty($cards))
                            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                @foreach (array_slice($cards, 0, 4) as $card)
                                    <div class="rounded-md border border-border bg-muted/30 px-4 py-3">
                                        <p class="text-[10px] uppercase tracking-[0.15em] text-muted-foreground">{{ $card['label'] }}</p>
                                        <p class="mt-1.5 text-sm font-medium text-foreground">{{ $card['value'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <a href="{{ route('projects.show', $project->slug) }}" class="luxury-btn-primary mt-6 inline-flex items-center gap-2">
                            View Project
                            <x-portal-icon name="arrow-right" class="h-4 w-4" />
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <x-portal-icon name="building" class="h-7 w-7" />
                    </div>
                    <h3 class="font-display text-2xl font-semibold">No Portfolio Projects Yet</h3>
                    <p class="mt-2 text-muted-foreground">Project case studies will appear here once they are published to the portfolio.</p>
                </div>
            @endforelse
        </div>
    </section>

    <section class="border-y border-border bg-card py-20">
        <div class="container-luxury">
            <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                @foreach ($portfolio['metrics'] as $metric)
                    <div class="luxury-card text-center">
                        <p class="font-display text-3xl font-bold text-primary">{{ $metric['value'] }}</p>
                        <p class="mt-2 text-xs uppercase tracking-[0.18em] text-muted-foreground">{{ $metric['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-padding bg-card">
        <div class="container-luxury">
            <x-section-heading :label="$portfolio['client_success_label']" :title="$portfolio['client_success_title']" :title-size="$portfolio['font_sizes']['client_success_title'] ?? null" />
            <div class="grid gap-8 md:grid-cols-3">
                @foreach ($testimonials as $testimonial)
                    <div class="luxury-card reveal">
                        <div class="star-rating mb-4">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="h-4 w-4 star-filled" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <p class="mb-5 text-sm leading-relaxed text-muted-foreground">"{{ $testimonial->quote }}"</p>
                        <div class="flex items-center gap-3 border-t border-border pt-4">
                            <div class="avatar text-xs">{{ strtoupper(substr($testimonial->client_name, 0, 1)) }}</div>
                            <div>
                                <p class="text-sm font-medium">{{ $testimonial->client_name }}</p>
                                <p class="text-xs uppercase tracking-[0.15em] text-primary">{{ $testimonial->role }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.public>

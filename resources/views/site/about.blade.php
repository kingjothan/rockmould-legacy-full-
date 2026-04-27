<x-layouts.public :company="$company" title="About Rockmould Limited">
    <x-hero
        :title="$about['hero_title']"
        :subtitle="$about['hero_subtitle']"
        :description="$about['hero_description']"
        :image="$about['hero_media_path']"
        height="large"
    />

    <section class="section-padding">
        <div class="container-luxury grid grid-cols-1 gap-16 lg:grid-cols-2 lg:items-center">
            <div class="reveal-left">
                <x-section-heading :label="$about['story_label'] ?? 'Our Story'" :title="$about['story_title']" align="left" />
                <div class="space-y-4 leading-relaxed text-muted-foreground">
                    @foreach ($about['story'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            </div>
            <div class="reveal-right img-zoom overflow-hidden rounded-md">
                <x-media :path="$about['story_image'] ?? null" fallback="images/project-2.jpg" alt="Rockmould excellence" class="aspect-[4/3] w-full object-cover" />
            </div>
        </div>
    </section>

    <section class="section-padding bg-card">
        <div class="container-luxury grid grid-cols-1 gap-12 md:grid-cols-2">
            <div class="luxury-card reveal-left">
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                    <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="font-display text-2xl font-semibold">{{ $about['vision_title'] ?? 'Our Vision' }}</h3>
                <p class="mt-4 leading-relaxed text-muted-foreground">{{ $about['vision'] }}</p>
            </div>
            <div class="luxury-card reveal-right">
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                    <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                </div>
                <h3 class="font-display text-2xl font-semibold">{{ $about['mission_title'] ?? 'Our Mission' }}</h3>
                <p class="mt-4 leading-relaxed text-muted-foreground">{{ $about['mission'] }}</p>
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container-luxury">
            <x-section-heading :label="$about['values_label'] ?? 'Core Values'" :title="$about['values_title'] ?? 'The Pillars of Our Identity'" />
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($about['values'] as $value)
                    <div class="luxury-card text-center reveal">
                        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                            <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                        </div>
                        <h3 class="font-display text-lg font-semibold">{{ $value['title'] }}</h3>
                        <p class="mt-2 text-sm text-muted-foreground">{{ $value['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-padding bg-card">
        <div class="container-luxury">
            <x-section-heading :label="$about['team_label'] ?? 'Leadership'" :title="$about['team_title'] ?? 'The Team Behind the Vision'" />
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                @foreach ($team as $member)
                    <div class="luxury-card text-center reveal">
                        @if ($member->image_path)
                            <div class="mx-auto mb-6 aspect-[4/5] max-w-[220px] overflow-hidden rounded-md border border-border bg-muted">
                                <x-media :path="$member->image_path" alt="{{ $member->name }}" class="h-full w-full object-cover" :controls="true" :autoplay="false" :loop="false" />
                            </div>
                        @else
                            <div class="avatar-lg mx-auto mb-6" style="background: linear-gradient(135deg, hsl(var(--primary)/0.2), hsl(var(--primary)/0.05));">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                        @endif
                        <h3 class="font-display text-lg font-semibold">{{ $member->name }}</h3>
                        <p class="mb-3 mt-1 text-xs uppercase tracking-[0.15em] text-primary">{{ $member->role }}</p>
                        <p class="text-sm text-muted-foreground">{{ $member->bio }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container-luxury">
            <x-section-heading :label="$about['timeline_label'] ?? 'Our Journey'" :title="$about['timeline_title'] ?? 'Milestones of Excellence'" />
            <div class="mx-auto max-w-3xl">
                @foreach ($timeline as $entry)
                    <div class="timeline-entry reveal">
                        <div class="timeline-dot"></div>
                        <span class="text-xs font-medium uppercase tracking-wider text-primary">{{ $entry->year }}</span>
                        <h3 class="mt-1 font-display text-lg font-semibold">{{ $entry->title }}</h3>
                        <p class="mt-1 text-sm text-muted-foreground">{{ $entry->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-y border-border bg-card py-20">
        <div class="container-luxury">
            <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                @foreach (($about['metrics'] ?? $metrics) as $metric)
                    <x-metric-card :value="$metric['value']" :label="$metric['label']" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container-luxury text-center">
            <h2 class="font-display text-3xl font-semibold md:text-4xl">{{ $about['cta_title'] ?? 'Partner with Excellence' }}</h2>
            <p class="mx-auto mt-4 max-w-xl text-muted-foreground">
                {{ $about['cta_description'] ?? "Whether you're an investor, client, or partner, let's create something extraordinary together." }}
            </p>
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a href="{{ route('consultations.create') }}" class="luxury-btn-primary">{{ $about['cta_primary'] ?? 'Schedule Consultation' }}
                    <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('projects.index') }}" class="luxury-btn-outline">{{ $about['cta_secondary'] ?? 'View Projects' }}</a>
            </div>
        </div>
    </section>
</x-layouts.public>

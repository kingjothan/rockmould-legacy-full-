<x-layouts.public :company="$company" title="Careers | Rockmould Limited">
    <x-hero
        :title="$page['hero_title'] ?? 'Build Your Career with Rockmould'"
        :subtitle="$page['hero_subtitle'] ?? 'Careers'"
        :description="$page['hero_description'] ?? 'Join a team of professionals dedicated to delivering excellence in real estate and construction.'"
        :image="$page['hero_media_path'] ?? 'images/project-4.jpg'"
        height="medium"
    />

    <section class="section-padding">
        <div class="container-luxury">
            <div class="mb-20 grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                @foreach ($page['benefits'] ?? [] as $benefit)
                    <div class="luxury-card text-center reveal">
                        <x-portal-icon :name="$benefit['icon']" class="mx-auto mb-4 h-8 w-8 text-primary" />
                        <h3 class="font-display text-lg font-semibold">{{ $benefit['title'] }}</h3>
                        <p class="mt-3 text-sm text-muted-foreground">{{ $benefit['description'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="mx-auto max-w-4xl">
                <div class="mb-8 text-center">
                    <h2 class="font-display text-3xl font-semibold">{{ $page['openings_title'] ?? 'Open Positions' }}</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-sm text-muted-foreground">{{ $page['openings_description'] ?? 'Explore current opportunities across projects, finance, client management, and operational delivery.' }}</p>
                </div>

                <div class="space-y-4">
                    @forelse ($roles as $role)
                        <a href="{{ route('careers.show', $role->slug) }}" class="group block rounded-lg border border-border bg-card p-6 transition-all duration-200 hover:border-primary/30 hover:shadow-[0_0_20px_hsl(var(--primary)/0.08)]">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-display text-xl font-semibold transition-colors group-hover:text-primary">{{ $role->title }}</h3>
                                    <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-muted-foreground">
                                        <span class="flex items-center gap-1">
                                            <x-portal-icon name="briefcase" class="h-3.5 w-3.5 text-primary/70" />
                                            {{ $role->department }}
                                        </span>
                                        <span aria-hidden="true" class="text-primary/60">&middot;</span>
                                        <span class="flex items-center gap-1">
                                            <x-portal-icon name="map-pin" class="h-3.5 w-3.5 text-primary/70" />
                                            {{ $role->location }}
                                        </span>
                                        <span aria-hidden="true" class="text-primary/60">&middot;</span>
                                        <span>{{ $role->employment_type }}</span>
                                    </div>
                                </div>
                                <x-status-badge :value="$role->is_open ? 'open' : 'closed'" />
                            </div>
                            <p class="mt-4 text-sm leading-relaxed text-muted-foreground">{{ $role->summary }}</p>
                            <span class="mt-4 inline-flex items-center gap-1 text-xs font-medium text-primary opacity-0 transition-opacity group-hover:opacity-100">
                                View Role
                                <x-portal-icon name="arrow-right" class="h-3.5 w-3.5" />
                            </span>
                        </a>
                    @empty
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <x-portal-icon name="briefcase" class="h-7 w-7" />
                            </div>
                            <h3 class="font-display text-2xl font-semibold">{{ $page['empty_title'] ?? 'No Open Positions' }}</h3>
                            <p class="mt-2 text-muted-foreground">{{ $page['empty_description'] ?? 'There are no active roles right now. Check back soon or send an open application.' }}</p>
                        </div>
                    @endforelse
                </div>

                <div class="luxury-card mt-10 text-center">
                    <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">{{ $page['open_application_kicker'] ?? "Can't find your role?" }}</p>
                    <h3 class="mt-3 font-display text-2xl font-semibold">{{ $page['open_application_title'] ?? 'Send an open application.' }}</h3>
                    <p class="mx-auto mt-3 max-w-xl text-sm text-muted-foreground">{{ $page['open_application_description'] ?? 'We welcome experienced professionals across development, construction, finance, design, and client operations.' }}</p>
                    <a href="{{ route('contact') }}" class="luxury-btn-outline mt-6 justify-center">{{ $page['open_application_button'] ?? 'Send Open Application' }}</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

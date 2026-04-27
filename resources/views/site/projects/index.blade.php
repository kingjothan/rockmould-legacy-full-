<x-layouts.public :company="$company" title="Projects | Rockmould Limited">
    <x-hero
        :title="$page['hero_title'] ?? 'Our Projects'"
        :subtitle="$page['hero_subtitle'] ?? 'Premium Developments'"
        :description="$page['hero_description'] ?? 'Explore our portfolio of exceptional real estate developments across prime locations.'"
        :image="$page['hero_media_path'] ?? 'images/project-2.jpg'"
        height="medium"
    />

    <section class="section-padding">
        <div class="container-luxury">
            <form method="GET" class="mb-12 flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <label class="admin-label">Project Type</label>
                    <div class="flex flex-wrap gap-2">
                        @php($activeType = $filters['type'] ?? 'all')
                        @foreach (['all' => 'All', 'residential' => 'Residential', 'commercial' => 'Commercial', 'mixed-use' => 'Mixed-Use', 'luxury-residential' => 'Luxury'] as $value => $label)
                            <button
                                type="submit"
                                name="type"
                                value="{{ $value === 'all' ? '' : $value }}"
                                class="filter-pill {{ ($value === 'all' && !filled($filters['type'] ?? null)) || $activeType === $value ? 'filter-pill-active' : 'filter-pill-inactive' }}"
                            >{{ $label }}</button>
                        @endforeach
                    </div>
                </div>
                <div class="flex flex-wrap items-end gap-3">
                    <div>
                        <label class="admin-label">Search</label>
                        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Search projects..." class="input-shell lg:w-52">
                    </div>
                    <div>
                        <label class="admin-label">Status</label>
                        <select name="status" class="input-shell lg:w-40">
                            <option value="">All Status</option>
                            @foreach (['ongoing' => 'Ongoing', 'completed' => 'Completed', 'upcoming' => 'Upcoming'] as $value => $label)
                                <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">View</label>
                        <select name="view" class="input-shell hidden md:block lg:w-28">
                            <option value="">Grid</option>
                            <option value="list" @selected(($filters['view'] ?? '') === 'list')>List</option>
                        </select>
                    </div>
                    <button class="luxury-btn-outline">Apply</button>
                </div>
            </form>

            @if ($projects->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                    </div>
                    <h3 class="font-display text-2xl font-semibold">{{ $page['empty_title'] ?? 'No Projects Found' }}</h3>
                    <p class="mt-2 text-muted-foreground">{{ $page['empty_description'] ?? 'Try adjusting your filters or search terms.' }}</p>
                    <a href="{{ route('projects.index') }}" class="luxury-btn-outline mt-6 text-xs">{{ $page['empty_button'] ?? 'Clear Filters' }}</a>
                </div>
            @elseif (($filters['view'] ?? '') === 'list')
                <div class="space-y-4">
                    @foreach ($projects as $project)
                        <a href="{{ route('projects.show', $project->slug) }}"
                           class="group flex gap-6 rounded-lg border border-border bg-card p-4 transition-colors hover:border-primary/30 reveal">
                            <div class="img-zoom h-24 w-32 shrink-0 overflow-hidden rounded-md">
                                <x-media :path="$project->cover_image" fallback="images/project-1.jpg" alt="{{ $project->title }}" class="h-full w-full object-cover" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="mb-1.5 flex flex-wrap items-center gap-3">
                                    <span class="section-label mb-0">{{ $project->category }}</span>
                                    <x-status-badge :value="$project->status" />
                                </div>
                                <h3 class="truncate font-display text-lg font-semibold transition-colors group-hover:text-primary">{{ $project->title }}</h3>
                                <p class="mt-1 flex items-center gap-1.5 text-sm text-muted-foreground">
                                    <svg class="h-3.5 w-3.5 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                    {{ $project->location }}
                                </p>
                            </div>
                            <div class="flex items-center">
                                <svg class="h-4 w-4 text-muted-foreground/40 transition-colors group-hover:text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($projects as $project)
                        <div class="reveal">
                            <x-project-card :project="$project" />
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>

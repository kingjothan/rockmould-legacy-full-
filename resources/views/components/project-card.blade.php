@props(['project'])

<a href="{{ route('projects.show', $project->slug) }}" class="group flex h-full flex-col overflow-hidden rounded-lg border border-border bg-card transition-all duration-500 hover:border-primary/30 hover:shadow-[0_0_40px_rgba(193,162,104,0.12)]">
    <div class="img-zoom relative aspect-[4/3] overflow-hidden">
        <x-media :path="$project->cover_image" fallback="images/project-1.jpg" alt="{{ $project->title }}" class="h-full w-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-background/80 via-transparent to-transparent"></div>
        <div class="absolute left-4 top-4">
            <x-status-badge :value="$project->status" />
        </div>
        <div class="absolute bottom-4 right-4 flex h-10 w-10 items-center justify-center rounded-full bg-primary/20 text-primary opacity-0 backdrop-blur-sm transition-opacity duration-500 group-hover:opacity-100">
            <x-portal-icon name="external-link" class="h-4 w-4" />
        </div>
    </div>
    <div class="flex flex-1 flex-col p-6">
        <p class="mb-2 text-[11px] uppercase tracking-[0.2em] text-primary">{{ $project->category }}</p>
        <h3 class="font-display text-xl font-semibold transition-colors duration-300 group-hover:text-primary">{{ $project->title }}</h3>
        <div class="mt-2 flex items-center gap-2 text-sm text-muted-foreground">
            <span aria-hidden="true" class="text-primary/60">&middot;</span>
            <span>{{ $project->location }}</span>
        </div>
    </div>
</a>

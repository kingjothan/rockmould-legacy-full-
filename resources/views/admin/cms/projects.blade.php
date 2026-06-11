<x-layouts.admin :company="$company" page-heading="Project Listings CMS">
    <div class="space-y-5">
        <div class="luxury-card border-primary/20 bg-primary/5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.22em] text-primary/70">Project Media Workflow</p>
                    <h2 class="mt-2 font-display text-3xl">Create projects and upload galleries from Project Management</h2>
                    <p class="mt-2 max-w-3xl text-sm text-muted-foreground">This CMS screen controls the public listing content. For new project creation, shared gallery images, videos, and client-facing documents, use the full Project Management module so the website and portal stay in sync.</p>
                </div>
                <a href="{{ route('admin.projects.index') }}" class="luxury-btn-primary shrink-0">
                    Open Project Management
                </a>
            </div>
        </div>

        @foreach ($projects as $project)
            <div class="luxury-card">
                <div class="flex items-center justify-between gap-4">
                    <h2 class="font-display text-3xl">{{ $project->title }}</h2>
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-status-badge :value="$project->status" />
                        <span class="rounded-full border border-border px-3 py-1 text-xs text-muted-foreground">{{ $project->units_count }} units</span>
                        <a href="{{ route('admin.media.index', ['project_id' => $project->id]) }}" class="luxury-btn-outline text-xs">Manage Gallery Media</a>
                        <a href="{{ route('admin.projects.show', $project) }}" class="luxury-btn-outline text-xs">Manage Units</a>
                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Delete {{ addslashes($project->title) }} permanently?');">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="confirm_title" value="{{ $project->title }}">
                            <button class="luxury-btn-outline border-destructive/50 text-xs text-destructive hover:border-destructive">Delete</button>
                        </form>
                    </div>
                </div>
                <form action="{{ route('admin.cms.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="mt-5 grid gap-4 md:grid-cols-2">
                    @csrf
                    @method('PUT')
                    @php
                        $highlightCollection = collect($project->highlights ?? []);
                        $highlightValue = fn (array $labels) => optional($highlightCollection->first(
                            fn ($highlight) => in_array(strtolower((string) ($highlight['label'] ?? '')), $labels, true)
                        ))['value'] ?? '';
                        $totalUnits = $highlightValue(['total units', 'units']);
                        $availableUnits = $highlightValue(['available units', 'available unit']);
                        $projectHighlights = $highlightCollection
                            ->reject(fn ($highlight) => in_array(strtolower((string) ($highlight['label'] ?? '')), ['total units', 'units', 'available units', 'available unit'], true))
                            ->pad(4, ['label' => '', 'value' => ''])
                            ->take(4)
                            ->values();
                    @endphp
                    <input name="title" value="{{ $project->title }}" class="input-shell">
                    <input name="slug" value="{{ $project->slug }}" class="input-shell">
                    <input name="location" value="{{ $project->location }}" class="input-shell">
                    <input name="category" value="{{ $project->category }}" class="input-shell">
                    <textarea name="overview" rows="3" class="input-shell md:col-span-2">{{ $project->overview }}</textarea>
                    <textarea name="description" rows="4" class="input-shell md:col-span-2">{{ $project->description }}</textarea>
                    <input name="cover_image" value="{{ $project->cover_image }}" class="input-shell" placeholder="CDN cover image/video URL or local path">
                    <div class="md:col-span-2">
                        <input type="file" name="cover_image_upload" accept="image/*,video/*" data-preview-target="#project-cover-preview-{{ $project->id }}" class="input-shell">
                        <p id="project-cover-preview-{{ $project->id }}" class="mt-2 text-xs text-muted-foreground">{{ $project->cover_image ?: 'No new file selected' }}</p>
                        <label class="mt-3 flex items-center gap-2 text-xs text-muted-foreground">
                            <input type="checkbox" name="remove_cover_image" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                            Remove current project cover if no new link or upload is provided
                        </label>
                    </div>
                    <select name="status" class="input-shell">
                        <option value="ongoing" @selected($project->status === 'ongoing')>Ongoing</option>
                        <option value="completed" @selected($project->status === 'completed')>Completed</option>
                        <option value="upcoming" @selected($project->status === 'upcoming')>Upcoming</option>
                    </select>
                    <div>
                        <label class="admin-label">Start Date</label>
                        <input type="date" name="launch_date" value="{{ optional($project->launch_date)->format('Y-m-d') }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Completion Date</label>
                        <input type="date" name="completion_date" value="{{ optional($project->completion_date)->format('Y-m-d') }}" class="input-shell">
                    </div>
                    <div class="md:col-span-2">
                        <label class="admin-label">Unit Highlight Cards</label>
                        <div class="mt-2 grid gap-3 md:grid-cols-2">
                            <div>
                                <input name="total_units_highlight" value="{{ $totalUnits }}" placeholder="Total units e.g. 120" class="input-shell">
                                <p class="mt-1 text-xs text-muted-foreground">Shows as Total Units on the project page.</p>
                            </div>
                            <div>
                                <input name="available_units_highlight" value="{{ $availableUnits }}" placeholder="Available units e.g. 18" class="input-shell">
                                <p class="mt-1 text-xs text-muted-foreground">Shows as Available Units on the project page.</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label class="admin-label">Other Project Highlight Cards</label>
                        <div class="mt-2 grid gap-3 md:grid-cols-2">
                            @foreach ($projectHighlights as $highlight)
                                <div class="grid gap-3 rounded-sm border border-border bg-muted/20 p-3 sm:grid-cols-2">
                                    <input name="highlight_values[]" value="{{ $highlight['value'] ?? '' }}" placeholder="Value e.g. 120" class="input-shell">
                                    <input name="highlight_labels[]" value="{{ $highlight['label'] ?? '' }}" placeholder="Label e.g. Units" class="input-shell">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_featured" value="1" @checked($project->is_featured)> Featured</label>
                    <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_portfolio" value="1" @checked($project->is_portfolio)> Show in Portfolio</label>
                    <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_published" value="1" @checked($project->is_published)> Published</label>
                    <button class="luxury-btn-primary md:col-span-2">Update Listing</button>
                </form>
            </div>
        @endforeach
    </div>
</x-layouts.admin>

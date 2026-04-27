@php
    $mediaCounts = [
        'total' => $media->count(),
        'images' => $media->where('media_type', 'image')->count(),
        'videos' => $media->where('media_type', 'video')->count(),
        'client_visible' => $media->where('visible_to_client', true)->count(),
    ];
@endphp

<x-layouts.admin :company="$company" page-heading="Media Library">
    <div class="space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.24em] text-primary/70">Visual Asset Control</p>
                <h1 class="mt-2 font-display text-3xl font-semibold">Project photography, videos, and featured media.</h1>
                <p class="mt-2 max-w-2xl text-sm text-muted-foreground">Manage the visuals used across the public website, portal, and internal project reporting.</p>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                    <x-portal-icon name="image" class="h-5 w-5 text-primary" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $mediaCounts['total'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Assets in library</p>
            </div>
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-500/10">
                    <x-portal-icon name="image" class="h-5 w-5 text-emerald-400" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $mediaCounts['images'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Photo assets</p>
            </div>
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-sky-500/10">
                    <x-portal-icon name="video" class="h-5 w-5 text-sky-400" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $mediaCounts['videos'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Video assets</p>
            </div>
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                    <x-portal-icon name="eye" class="h-5 w-5 text-primary" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $mediaCounts['client_visible'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Visible in client portal</p>
            </div>
        </div>

        <div class="luxury-card-flat border-primary/20 bg-primary/5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.24em] text-primary/70">Upload Capacity</p>
                    <h2 class="mt-2 font-display text-2xl">This server is configured for large project media.</h2>
                    <p class="mt-2 text-sm text-muted-foreground">
                        If an upload still fails, restart Apache or the `php artisan serve` process so the active PHP worker reloads the new limits.
                    </p>
                </div>
                <div class="grid gap-3 text-xs sm:grid-cols-2 lg:min-w-[520px]">
                    <div class="rounded-sm border border-border bg-card/70 px-4 py-3">
                        <p class="uppercase tracking-[0.18em] text-muted-foreground">File limit</p>
                        <p class="mt-1 font-medium text-foreground">{{ $uploadLimits['upload_max_filesize'] }}</p>
                    </div>
                    <div class="rounded-sm border border-border bg-card/70 px-4 py-3">
                        <p class="uppercase tracking-[0.18em] text-muted-foreground">POST limit</p>
                        <p class="mt-1 font-medium text-foreground">{{ $uploadLimits['post_max_size'] }}</p>
                    </div>
                    <div class="rounded-sm border border-border bg-card/70 px-4 py-3">
                        <p class="uppercase tracking-[0.18em] text-muted-foreground">Max files</p>
                        <p class="mt-1 font-medium text-foreground">{{ $uploadLimits['max_file_uploads'] }}</p>
                    </div>
                    <div class="rounded-sm border border-border bg-card/70 px-4 py-3">
                        <p class="uppercase tracking-[0.18em] text-muted-foreground">Temp folder</p>
                        <p class="mt-1 truncate font-medium text-foreground">{{ $uploadLimits['upload_tmp_dir'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="space-y-6">
                <div class="luxury-card-flat overflow-hidden p-0">
                    <div class="border-b border-border p-5">
                        <form method="GET" class="grid gap-4 md:grid-cols-[1fr_170px_200px_200px_120px]">
                            <div class="portal-search-shell">
                                <x-portal-icon name="search" class="h-4 w-4" />
                                <input type="search" name="q" value="{{ request('q') }}" placeholder="Search media title or project..." class="input-shell py-3">
                            </div>
                            <select name="type" class="input-shell">
                                <option value="">All types</option>
                                <option value="image" @selected(request('type') === 'image')>Images</option>
                                <option value="video" @selected(request('type') === 'video')>Videos</option>
                            </select>
                            <select name="project_id" class="input-shell">
                                <option value="">All projects</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" @selected((string) request('project_id') === (string) $project->id)>{{ $project->title }}</option>
                                @endforeach
                            </select>
                            <select name="gallery_section" class="input-shell">
                                <option value="">All gallery sections</option>
                                @foreach ($gallerySections as $value => $label)
                                    <option value="{{ $value }}" @selected(request('gallery_section') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <button class="luxury-btn-outline">Filter</button>
                        </form>
                    </div>

                    @if ($media->isEmpty())
                        <div class="flex items-center gap-3 px-6 py-12 text-sm text-muted-foreground">
                            <x-portal-icon name="info" class="h-4 w-4 shrink-0" />
                            No media matched the current search or filters.
                        </div>
                    @else
                        <div class="grid gap-5 p-5 md:grid-cols-2">
                            @foreach ($media as $asset)
                                <article class="overflow-hidden rounded-lg border border-border bg-card">
                                    <div class="relative aspect-[16/10] bg-muted">
                                        <x-media :path="$asset->thumbnail_path ?: $asset->file_path" fallback="images/project-1.jpg" alt="{{ $asset->title }}" class="h-full w-full object-cover" :autoplay="false" :loop="false" />
                                        <div class="absolute left-4 top-4 flex flex-wrap items-center gap-2">
                                            <x-status-badge :value="$asset->media_type" />
                                            @if ($asset->is_featured)
                                                <span class="rounded-full bg-primary/10 px-2 py-1 text-[10px] uppercase tracking-[0.18em] text-primary">Featured</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="space-y-4 p-5">
                                        <div>
                                            <h2 class="font-display text-2xl">{{ $asset->title }}</h2>
                                            <p class="mt-2 text-sm text-muted-foreground">{{ $asset->project?->title ?: 'Unassigned project' }}</p>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-full bg-muted px-2 py-1 text-[10px] uppercase tracking-[0.18em] text-muted-foreground">
                                                {{ $asset->gallery_section_label }}
                                            </span>
                                            @if ($asset->visible_to_client)
                                                <span class="rounded-full bg-primary/10 px-2 py-1 text-[10px] uppercase tracking-[0.18em] text-primary">
                                                    Client visible
                                                </span>
                                            @endif
                                        </div>

                                        <div class="space-y-2 text-xs text-muted-foreground">
                                            @if ($asset->assignment?->user)
                                                <p>Assigned client: {{ $asset->assignment->user->name }} &middot; {{ $asset->assignment->unit_label }}</p>
                                            @else
                                                <p>Shared project gallery asset</p>
                                            @endif
                                            <p>Added {{ optional($asset->created_at)->format('M j, Y g:i A') ?: 'Pending date' }}</p>
                                            @if ($asset->notes)
                                                <p>{{ $asset->notes }}</p>
                                            @endif
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-full px-2 py-1 text-[10px] uppercase tracking-[0.18em] {{ $asset->visible_to_client ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground' }}">
                                                {{ $asset->visible_to_client ? 'Client visible' : 'Internal only' }}
                                            </span>
                                            @if ($asset->file_url)
                                                <a href="{{ $asset->file_url }}" target="_blank" class="luxury-btn-outline text-xs">Preview</a>
                                                <a href="{{ $asset->file_url }}" target="_blank" class="luxury-btn-primary text-xs">Open Asset</a>
                                            @else
                                                <span class="text-xs uppercase tracking-[0.18em] text-muted-foreground">Awaiting upload</span>
                                            @endif
                                        </div>

                                        <details class="rounded-sm border border-border/60 bg-muted/10">
                                            <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3 text-sm font-medium">
                                                <span>Edit Media Asset</span>
                                                <x-portal-icon name="pen" class="h-4 w-4 text-primary" />
                                            </summary>

                                            <div class="space-y-4 border-t border-border/60 p-4">
                                                <form action="{{ route('admin.media.update', $asset) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="grid gap-4 md:grid-cols-2">
                                                        <div>
                                                            <label class="admin-label">Project</label>
                                                            <select name="project_id" class="input-shell">
                                                                <option value="">Select project</option>
                                                                @foreach ($projects as $project)
                                                                    <option value="{{ $project->id }}" @selected($asset->project_id === $project->id)>{{ $project->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="admin-label">Assignment</label>
                                                            <select name="project_assignment_id" class="input-shell">
                                                                <option value="">Shared project gallery</option>
                                                                @foreach ($assignments as $assignment)
                                                                    <option value="{{ $assignment->id }}" @selected($asset->project_assignment_id === $assignment->id)>{{ $assignment->user->name }} &middot; {{ $assignment->project->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="grid gap-4 md:grid-cols-2">
                                                        <div>
                                                            <label class="admin-label">Media title</label>
                                                            <input name="title" value="{{ $asset->title }}" required class="input-shell">
                                                        </div>
                                                        <div>
                                                            <label class="admin-label">Media type</label>
                                                            <select name="media_type" class="input-shell">
                                                                <option value="image" @selected($asset->media_type === 'image')>Image</option>
                                                                <option value="video" @selected($asset->media_type === 'video')>Video</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label class="admin-label">Gallery section</label>
                                                        <select name="gallery_section" class="input-shell">
                                                            @foreach ($gallerySections as $value => $label)
                                                                <option value="{{ $value }}" @selected($asset->gallery_section === $value)>{{ $label }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="grid gap-4 md:grid-cols-2">
                                                        <div>
                                                            <label class="admin-label">Display order</label>
                                                            <input type="number" min="0" name="sort_order" value="{{ $asset->sort_order }}" class="input-shell">
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-3 pt-6">
                                                            <label class="flex items-center gap-2 rounded-sm border border-border px-3 py-2 text-xs text-muted-foreground">
                                                                <input type="hidden" name="visible_to_client" value="0">
                                                                <input type="checkbox" name="visible_to_client" value="1" @checked($asset->visible_to_client) class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                                                                Client visible
                                                            </label>
                                                            <label class="flex items-center gap-2 rounded-sm border border-border px-3 py-2 text-xs text-muted-foreground">
                                                                <input type="hidden" name="is_featured" value="0">
                                                                <input type="checkbox" name="is_featured" value="1" @checked($asset->is_featured) class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                                                                Featured
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label class="admin-label">Notes</label>
                                                        <textarea name="notes" rows="3" class="input-shell">{{ $asset->notes }}</textarea>
                                                    </div>

                                                    <div class="grid gap-4 md:grid-cols-2">
                                                        <div class="rounded-sm border border-dashed border-border bg-muted/10 p-4">
                                                            <label class="admin-label">Replace asset file</label>
                                                            <input type="file" name="asset" accept="image/*,video/*" data-preview-target="#media-replace-file-preview-{{ $asset->id }}" class="input-shell">
                                                            <p id="media-replace-file-preview-{{ $asset->id }}" class="mt-3 text-xs text-muted-foreground">Leave empty to keep the current asset</p>
                                                        </div>
                                                        <div class="rounded-sm border border-dashed border-border bg-muted/10 p-4">
                                                            <label class="admin-label">Replace video thumbnail</label>
                                                            <input type="file" name="thumbnail" accept="image/*" data-preview-target="#media-thumbnail-preview-{{ $asset->id }}" class="input-shell">
                                                            <p id="media-thumbnail-preview-{{ $asset->id }}" class="mt-3 text-xs text-muted-foreground">Optional. Use for a cleaner video or hero preview.</p>
                                                        </div>
                                                    </div>

                                                    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-border/60 pt-4">
                                                        <div class="text-[11px] uppercase tracking-[0.18em] text-muted-foreground">
                                                            {{ $asset->project_assignment_id ? 'Private assignment asset' : 'Shared project asset' }}
                                                        </div>
                                                        <button class="luxury-btn-primary text-xs">Save Changes</button>
                                                    </div>
                                                </form>

                                                <form action="{{ route('admin.media.destroy', $asset) }}" method="POST" class="border-t border-border/60 pt-4">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="luxury-btn-destructive w-full justify-center text-xs">Delete Media Asset</button>
                                                </form>
                                            </div>
                                        </details>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="luxury-card">
                <div class="flex items-center gap-2">
                    <x-portal-icon name="plus" class="h-4 w-4 text-primary" />
                    <h2 class="font-display text-3xl">Upload Media</h2>
                </div>
                <p class="mt-2 text-sm text-muted-foreground">Add new images or videos for a project, assignment, or client-facing update.</p>

                <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="admin-label">Project</label>
                        <select name="project_id" class="input-shell">
                            <option value="">Select project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @selected((string) old('project_id') === (string) $project->id)>{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Assignment</label>
                        <select name="project_assignment_id" class="input-shell">
                            <option value="">Shared project gallery</option>
                            @foreach ($assignments as $assignment)
                                <option value="{{ $assignment->id }}" @selected((string) old('project_assignment_id') === (string) $assignment->id)>{{ $assignment->user->name }} | {{ $assignment->project->title }}</option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-muted-foreground">Leave assignment empty if the media should be visible to every client attached to this project.</p>
                    </div>
                    <div>
                        <label class="admin-label">Media title</label>
                        <input name="title" value="{{ old('title') }}" required placeholder="Media title" class="input-shell">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="admin-label">Media type</label>
                            <select name="media_type" class="input-shell">
                                <option value="image" @selected(old('media_type', 'image') === 'image')>Image</option>
                                <option value="video" @selected(old('media_type') === 'video')>Video</option>
                            </select>
                        </div>
                        <div>
                            <label class="admin-label">Gallery section</label>
                            <select name="gallery_section" class="input-shell">
                                @foreach ($gallerySections as $value => $label)
                                    <option value="{{ $value }}" @selected(old('gallery_section', 'general') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Display order</label>
                        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', 0) }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Notes</label>
                        <textarea name="notes" rows="3" placeholder="Add context for the media asset" class="input-shell">{{ old('notes') }}</textarea>
                    </div>
                    <div class="rounded-md border border-dashed border-border bg-muted/10 p-4">
                        <p class="text-xs text-muted-foreground">Use <span class="font-medium text-foreground">Exterior</span> for building facades, site progress, landscaping, and arrival views. Use <span class="font-medium text-foreground">Interior</span> for lobbies, living spaces, kitchens, amenities, and fit-out visuals. Use <span class="font-medium text-foreground">Gallery</span> for the project’s general mixed media collection.</p>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-md border border-dashed border-border bg-muted/10 p-4">
                            <label class="admin-label">Upload file</label>
                            <input type="file" name="asset" accept="image/*,video/*" data-preview-target="#media-file-preview" class="input-shell">
                            <p id="media-file-preview" class="mt-3 text-xs text-muted-foreground">No file selected</p>
                        </div>
                        <div class="rounded-md border border-dashed border-border bg-muted/10 p-4">
                            <label class="admin-label">Video thumbnail</label>
                            <input type="file" name="thumbnail" accept="image/*" data-preview-target="#media-thumbnail-upload-preview" class="input-shell">
                            <p id="media-thumbnail-upload-preview" class="mt-3 text-xs text-muted-foreground">Optional. Upload a cover image for video assets.</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 rounded-sm border border-border px-3 py-2 text-xs text-muted-foreground">
                            <input type="hidden" name="visible_to_client" value="0">
                            <input type="checkbox" name="visible_to_client" value="1" @checked(old('visible_to_client', true)) class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                            Client visible
                        </label>
                        <label class="flex items-center gap-2 rounded-sm border border-border px-3 py-2 text-xs text-muted-foreground">
                            <input type="hidden" name="is_featured" value="0">
                            <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured')) class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                            Mark featured
                        </label>
                    </div>
                    <button class="luxury-btn-primary w-full justify-center">Upload Media</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>

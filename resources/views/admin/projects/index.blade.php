<x-layouts.admin :company="$company" page-heading="Projects">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-display text-3xl">Project Management</h1>
                <p class="mt-2 text-sm text-muted-foreground">Create, review, and manage project records that power the public website and client portal.</p>
            </div>
            <div class="rounded-sm border border-border bg-card px-4 py-3 text-xs uppercase tracking-[0.18em] text-muted-foreground">
                {{ $projects->count() }} projects &middot; {{ $projects->sum('units_count') }} units
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Total Projects</p>
                <p class="mt-2 font-display text-3xl">{{ $projects->count() }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Ongoing</p>
                <p class="mt-2 font-display text-3xl">{{ $projects->where('status', 'ongoing')->count() }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Completed</p>
                <p class="mt-2 font-display text-3xl">{{ $projects->where('status', 'completed')->count() }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Units</p>
                <p class="mt-2 font-display text-3xl">{{ $projects->sum('units_count') }}</p>
                <p class="mt-1 text-xs text-muted-foreground">Owned units</p>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr]">
            <div class="luxury-card overflow-hidden p-0">
                <div class="border-b border-border p-5">
                    <form method="GET" class="flex flex-col gap-3 md:flex-row">
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search projects..." class="input-shell">
                        <button class="luxury-btn-outline">Search</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="table-shell">
                        <thead>
                            <tr>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Clients</th>
                                <th>Units</th>
                                <th>Progress</th>
                                <th>Budget</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($projects as $project)
                                <tr class="table-row-shell">
                                    <td>
                                        <div class="font-medium">{{ $project->title }}</div>
                                        <div class="text-xs text-muted-foreground">{{ $project->location }}</div>
                                    </td>
                                    <td><x-status-badge :value="$project->status" /></td>
                                    <td>{{ $project->assignments_count }}</td>
                                    <td>{{ $project->units_count }}</td>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="h-1.5 w-24 overflow-hidden rounded-full bg-muted">
                                                <div class="h-full rounded-full bg-primary" style="width: {{ $project->progress }}%"></div>
                                            </div>
                                            <span class="text-xs text-muted-foreground">{{ $project->progress }}%</span>
                                        </div>
                                    </td>
                                    <td>{{ $project->budget ? 'NGN '.number_format((float) $project->budget) : 'Pending' }}</td>
                                    <td>
                                        <div class="flex flex-wrap items-center gap-3">
                                            <a href="{{ route('admin.projects.show', $project) }}" class="text-primary transition hover:text-primary/80">Open</a>
                                            <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Delete {{ addslashes($project->title) }} permanently?');">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="confirm_title" value="{{ $project->title }}">
                                                <button class="text-destructive transition hover:text-destructive/80">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center text-sm text-muted-foreground">No projects matched the current search.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="luxury-card">
                <h2 class="font-display text-3xl">Create Project</h2>
                <p class="mt-2 text-sm text-muted-foreground">Add a new project record for the website, admin, and portal experience.</p>

                <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                    @csrf
                    <input name="title" required placeholder="Project title" class="input-shell">
                    <input name="location" required placeholder="Location" class="input-shell">
                    <input name="category" required placeholder="Category" class="input-shell">
                    <select name="status" class="input-shell">
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                        <option value="upcoming">Upcoming</option>
                    </select>
                    <textarea name="overview" rows="4" required placeholder="Overview" class="input-shell"></textarea>
                    <textarea name="description" rows="4" placeholder="Description" class="input-shell"></textarea>
                    <input name="cover_image" placeholder="CDN cover image/video URL or local path" class="input-shell">
                    <div>
                        <label class="admin-label">Upload Cover Media</label>
                        <input type="file" name="cover_image_upload" accept="image/*,video/*" class="input-shell">
                        <p class="mt-2 text-xs text-muted-foreground">Accepts image or video up to 200MB. Video covers render automatically on the public site and portal.</p>
                    </div>
                    <input type="number" name="progress" value="0" min="0" max="100" class="input-shell">
                    <input type="number" name="budget" placeholder="Budget" class="input-shell">
                    <input type="number" name="price_from" placeholder="Price from" class="input-shell">
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div>
                            <label class="admin-label">Start Date</label>
                            <input type="date" name="launch_date" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Completion Date</label>
                            <input type="date" name="completion_date" class="input-shell">
                        </div>
                    </div>
                    <textarea name="features_text" rows="4" placeholder="Features, one per line" class="input-shell"></textarea>
                    <textarea name="gallery_text" rows="4" placeholder="Gallery paths, one per line" class="input-shell"></textarea>
                    <textarea name="highlights_json" rows="4" placeholder='Highlights JSON, e.g. [{"label":"Units","value":"120"}]' class="input-shell"></textarea>
                    <div class="space-y-3 rounded-lg border border-border bg-card/50 p-4">
                        <div>
                            <label class="admin-label">Upload Project Media</label>
                            <input type="file" name="media_uploads[]" accept="image/*,video/*" multiple class="input-shell">
                            <p class="mt-2 text-xs text-muted-foreground">Upload photos and videos that should go into the shared project gallery for clients.</p>
                        </div>
                        <div>
                            <label class="admin-label">Project Media CDN Links</label>
                            <textarea name="media_urls_text" rows="4" placeholder="Paste one direct image/video URL per line" class="input-shell"></textarea>
                            <p class="mt-2 text-xs text-muted-foreground">Use this instead of uploading when media lives on a CDN, Cloudinary, S3, YouTube-hosted direct file, or another fast storage provider.</p>
                        </div>
                        <div>
                            <label class="admin-label">Gallery Section</label>
                            <select name="uploaded_media_gallery_section" class="input-shell">
                                @foreach (\App\Models\MediaAsset::gallerySectionOptions() as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="uploaded_media_visible_to_client" value="1" checked> Make uploaded media visible to clients</label>
                    </div>
                    <div class="space-y-3 rounded-lg border border-border bg-card/50 p-4">
                        <div>
                            <label class="admin-label">Upload Project Documents</label>
                            <input type="file" name="document_uploads[]" multiple class="input-shell">
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <input name="document_category" placeholder="Document category e.g. Brochure" class="input-shell">
                            <input name="document_version" value="v1" placeholder="Version" class="input-shell">
                        </div>
                        <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="uploaded_documents_visible_to_client" value="1" checked> Make uploaded documents visible to clients</label>
                    </div>
                    <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_featured" value="1"> Feature on website</label>
                    <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_portfolio" value="1" checked> Include in portfolio</label>
                    <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_published" value="1" checked> Publish now</label>
                    <button class="luxury-btn-primary w-full">Create Project</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>

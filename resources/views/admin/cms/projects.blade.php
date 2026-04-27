<x-layouts.admin :company="$company" page-heading="Project Listings CMS">
    <div class="space-y-5">
        <div class="luxury-card border-primary/20 bg-primary/5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-[10px] uppercase tracking-[0.22em] text-primary/70">Project Media Workflow</p>
                    <h2 class="mt-2 font-display text-3xl">Create projects and upload galleries from Project Management</h2>
                    <p class="mt-2 max-w-3xl text-sm text-muted-foreground">This CMS screen controls the public listing content. For new project creation, shared gallery images, videos, and client-facing documents, use the full Project Management module so the website and portal stay in sync.</p>
                </div>
                <a href="{{ route('admin.projects.index') }}" class="luxury-btn-primary">
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
                        <a href="{{ route('admin.media.index', ['project_id' => $project->id]) }}" class="luxury-btn-outline text-xs">Manage Gallery Media</a>
                    </div>
                </div>
                <form action="{{ route('admin.cms.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="mt-5 grid gap-4 md:grid-cols-2">
                    @csrf
                    @method('PUT')
                    <input name="title" value="{{ $project->title }}" class="input-shell">
                    <input name="slug" value="{{ $project->slug }}" class="input-shell">
                    <input name="location" value="{{ $project->location }}" class="input-shell">
                    <input name="category" value="{{ $project->category }}" class="input-shell">
                    <textarea name="overview" rows="3" class="input-shell md:col-span-2">{{ $project->overview }}</textarea>
                    <textarea name="description" rows="4" class="input-shell md:col-span-2">{{ $project->description }}</textarea>
                    <input name="cover_image" value="{{ $project->cover_image }}" class="input-shell">
                    <div class="md:col-span-2">
                        <input type="file" name="cover_image_upload" accept="image/*,video/*" data-preview-target="#project-cover-preview-{{ $project->id }}" class="input-shell">
                        <p id="project-cover-preview-{{ $project->id }}" class="mt-2 text-xs text-muted-foreground">{{ $project->cover_image ?: 'No new file selected' }}</p>
                    </div>
                    <input name="status" value="{{ $project->status }}" class="input-shell">
                    <div>
                        <label class="admin-label">Completion Date</label>
                        <input type="date" name="completion_date" value="{{ optional($project->completion_date)->format('Y-m-d') }}" class="input-shell">
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

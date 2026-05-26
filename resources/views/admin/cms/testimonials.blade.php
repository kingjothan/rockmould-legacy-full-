<x-layouts.admin :company="$company" page-heading="Testimonials CMS">
    <div class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="space-y-5">
            @foreach ($testimonials as $testimonial)
                <div class="luxury-card">
                    <h2 class="font-display text-3xl">{{ $testimonial->client_name }}</h2>
                    <form action="{{ route('admin.cms.testimonials.update', $testimonial) }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
                        @csrf @method('PUT')
                        <select name="project_id" class="input-shell">
                            <option value="">No linked project</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}" @selected($testimonial->project_id === $project->id)>{{ $project->title }}</option>
                            @endforeach
                        </select>
                        <input name="client_name" value="{{ $testimonial->client_name }}" class="input-shell">
                        <input name="role" value="{{ $testimonial->role }}" class="input-shell">
                        <textarea name="quote" rows="4" class="input-shell">{{ $testimonial->quote }}</textarea>
                        <input type="number" name="rating" min="1" max="5" value="{{ $testimonial->rating }}" class="input-shell">
                        <input type="number" name="sort_order" min="0" value="{{ $testimonial->sort_order }}" class="input-shell">
                        <input name="image_path" value="{{ $testimonial->image_path }}" class="input-shell" placeholder="CDN image/video URL or local path">
                        <input type="file" name="image_upload" accept="image/*,video/*" data-preview-target="#testimonial-image-preview-{{ $testimonial->id }}" class="input-shell">
                        <p id="testimonial-image-preview-{{ $testimonial->id }}" class="text-xs text-muted-foreground">{{ $testimonial->image_path ?: 'No new file selected' }}</p>
                        <label class="flex items-center gap-2 text-xs text-muted-foreground">
                            <input type="checkbox" name="remove_image_path" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                            Remove current testimonial media if no new link or upload is provided
                        </label>
                        <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_visible" value="1" @checked($testimonial->is_visible)> Visible on website</label>
                        <button class="luxury-btn-primary">Update Testimonial</button>
                    </form>
                </div>
            @endforeach
        </div>
        <div class="luxury-card">
            <h2 class="font-display text-3xl">Add Testimonial</h2>
            <form action="{{ route('admin.cms.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                @csrf
                <select name="project_id" class="input-shell">
                    <option value="">No linked project</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->title }}</option>
                    @endforeach
                </select>
                <input name="client_name" required placeholder="Client name" class="input-shell">
                <input name="role" placeholder="Role" class="input-shell">
                <textarea name="quote" rows="4" required placeholder="Quote" class="input-shell"></textarea>
                <input type="number" name="rating" min="1" max="5" value="5" class="input-shell">
                <input type="number" name="sort_order" min="0" value="0" class="input-shell">
                <input name="image_path" placeholder="CDN image/video URL or local path" class="input-shell">
                <input type="file" name="image_upload" accept="image/*,video/*" data-preview-target="#create-testimonial-image-preview" class="input-shell">
                <p id="create-testimonial-image-preview" class="text-xs text-muted-foreground">No new file selected</p>
                <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_visible" value="1" checked> Visible on website</label>
                <button class="luxury-btn-primary w-full">Create Testimonial</button>
            </form>
        </div>
    </div>
</x-layouts.admin>

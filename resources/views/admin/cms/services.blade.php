<x-layouts.admin :company="$company" page-heading="Services CMS">
    <div class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="space-y-5">
            @foreach ($services as $service)
                <div class="luxury-card">
                    <h2 class="font-display text-3xl">{{ $service->title }}</h2>
                    <form action="{{ route('admin.cms.services.update', $service) }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
                        @csrf
                        @method('PUT')
                        <input name="title" value="{{ $service->title }}" class="input-shell">
                        <input name="slug" value="{{ $service->slug }}" class="input-shell">
                        <input name="tagline" value="{{ $service->tagline }}" class="input-shell">
                        <textarea name="summary" rows="3" class="input-shell">{{ $service->summary }}</textarea>
                        <textarea name="description" rows="4" class="input-shell">{{ $service->description }}</textarea>
                        <input name="image_path" value="{{ $service->image_path }}" class="input-shell" placeholder="Image or video path">
                        <input type="file" name="image_upload" accept="image/*,video/*" data-preview-target="#service-image-preview-{{ $service->id }}" class="input-shell">
                        <p id="service-image-preview-{{ $service->id }}" class="text-xs text-muted-foreground">{{ $service->image_path ?: 'No new file selected' }}</p>
                        <textarea name="benefits_text" rows="4" class="input-shell">{{ implode("\n", $service->benefits ?? []) }}</textarea>
                        <textarea name="process_steps_text" rows="4" class="input-shell">{{ implode("\n", $service->process_steps ?? []) }}</textarea>
                        <textarea name="related_projects_text" rows="3" class="input-shell">{{ implode("\n", $service->related_projects ?? []) }}</textarea>
                        <input type="number" name="sort_order" value="{{ $service->sort_order }}" class="input-shell">
                        <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_featured" value="1" @checked($service->is_featured)> Featured</label>
                        <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_published" value="1" @checked($service->is_published)> Published</label>
                        <button class="luxury-btn-primary">Update Service</button>
                    </form>
                </div>
            @endforeach
        </div>
        <div class="luxury-card">
            <h2 class="font-display text-3xl">Create Service</h2>
            <form action="{{ route('admin.cms.services.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                @csrf
                <input name="title" required placeholder="Service title" class="input-shell">
                <input name="tagline" placeholder="Tagline" class="input-shell">
                <textarea name="summary" rows="3" required placeholder="Summary" class="input-shell"></textarea>
                <textarea name="description" rows="4" placeholder="Description" class="input-shell"></textarea>
                <input name="image_path" placeholder="Image or video path" class="input-shell">
                <input type="file" name="image_upload" accept="image/*,video/*" data-preview-target="#create-service-image-preview" class="input-shell">
                <p id="create-service-image-preview" class="text-xs text-muted-foreground">No new file selected</p>
                <textarea name="benefits_text" rows="4" placeholder="Benefits, one per line" class="input-shell"></textarea>
                <textarea name="process_steps_text" rows="4" placeholder="Process steps, one per line" class="input-shell"></textarea>
                <textarea name="related_projects_text" rows="3" placeholder="Related project slugs, one per line" class="input-shell"></textarea>
                <input type="number" name="sort_order" value="0" placeholder="Sort order" class="input-shell">
                <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_featured" value="1"> Featured</label>
                <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_published" value="1" checked> Published</label>
                <button class="luxury-btn-primary w-full">Create Service</button>
            </form>
        </div>
    </div>
</x-layouts.admin>

<x-layouts.admin :company="$company" page-heading="Services CMS">
    <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
        <div class="space-y-5">
            @foreach ($services as $service)
                <details class="luxury-card group">
                    <summary class="cursor-pointer list-none">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="font-display text-xl font-semibold">{{ $service->title }}</h2>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    {{ $service->tagline }}
                                    @if ($service->is_published) <span class="text-green-500">Published</span> @else <span class="text-yellow-500">Draft</span> @endif
                                    @if ($service->is_featured) · <span class="text-primary">Featured</span> @endif
                                </p>
                            </div>
                            <x-portal-icon name="chevron-down" class="h-4 w-4 transition-transform group-open:rotate-180" />
                        </div>
                    </summary>
                    <form action="{{ route('admin.cms.services.update', $service) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="admin-label">Title</label>
                                <input name="title" value="{{ $service->title }}" class="input-shell w-full" required>
                            </div>
                            <div>
                                <label class="admin-label">Slug</label>
                                <input name="slug" value="{{ $service->slug }}" class="input-shell w-full">
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">Tagline</label>
                            <input name="tagline" value="{{ $service->tagline }}" class="input-shell w-full">
                        </div>
                        <div>
                            <label class="admin-label">Summary</label>
                            <textarea name="summary" rows="3" class="input-shell w-full">{{ $service->summary }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Full Description</label>
                            <textarea name="description" rows="4" class="input-shell w-full">{{ $service->description }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Image</label>
                            <input name="image_path" value="{{ $service->image_path }}" class="input-shell w-full" placeholder="Paste URL or use upload below">
                            <input type="file" name="image_upload" accept="image/*,video/*" class="input-shell mt-2 w-full">
                            <label class="mt-2 flex items-center gap-2 text-xs text-muted-foreground">
                                <input type="checkbox" name="remove_image_path" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary">
                                Remove current image
                            </label>
                        </div>
                        <div>
                            <label class="admin-label">Benefits (one per line)</label>
                            <textarea name="benefits_text" rows="4" class="input-shell w-full">{{ implode("\n", $service->benefits ?? []) }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Process Steps (one per line)</label>
                            <textarea name="process_steps_text" rows="4" class="input-shell w-full">{{ implode("\n", $service->process_steps ?? []) }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Related Project Slugs (one per line)</label>
                            <textarea name="related_projects_text" rows="3" class="input-shell w-full">{{ implode("\n", $service->related_projects ?? []) }}</textarea>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <label class="admin-label">Sort Order</label>
                                <input type="number" name="sort_order" value="{{ $service->sort_order }}" class="input-shell w-full">
                            </div>
                            <label class="flex items-end gap-2 pb-2 text-sm text-muted-foreground"><input type="checkbox" name="is_featured" value="1" @checked($service->is_featured) class="h-4 w-4 rounded border-border bg-transparent text-primary"> Featured</label>
                            <label class="flex items-end gap-2 pb-2 text-sm text-muted-foreground"><input type="checkbox" name="is_published" value="1" @checked($service->is_published) class="h-4 w-4 rounded border-border bg-transparent text-primary"> Published</label>
                        </div>
                        <button class="luxury-btn-primary">Update Service</button>
                    </form>
                    <form action="{{ route('admin.cms.services.destroy', $service) }}" method="POST" class="mt-3" onsubmit="return confirm('Delete &quot;{{ $service->title }}&quot;? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-destructive hover:underline">Delete this service</button>
                    </form>
                </details>
            @endforeach
        </div>

        <div>
            <div class="luxury-card sticky top-20">
                <h2 class="font-display text-xl font-semibold">Create New Service</h2>
                <form action="{{ route('admin.cms.services.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="admin-label">Title</label>
                        <input name="title" required placeholder="Service title" class="input-shell w-full">
                    </div>
                    <div>
                        <label class="admin-label">Tagline</label>
                        <input name="tagline" placeholder="Short tagline" class="input-shell w-full">
                    </div>
                    <div>
                        <label class="admin-label">Summary</label>
                        <textarea name="summary" rows="3" required placeholder="Brief summary" class="input-shell w-full"></textarea>
                    </div>
                    <div>
                        <label class="admin-label">Full Description</label>
                        <textarea name="description" rows="4" placeholder="Detailed description" class="input-shell w-full"></textarea>
                    </div>
                    <div>
                        <label class="admin-label">Image</label>
                        <input name="image_path" placeholder="Paste URL or use upload below" class="input-shell w-full">
                        <input type="file" name="image_upload" accept="image/*,video/*" class="input-shell mt-2 w-full">
                    </div>
                    <div>
                        <label class="admin-label">Benefits (one per line)</label>
                        <textarea name="benefits_text" rows="4" placeholder="Each benefit on its own line" class="input-shell w-full"></textarea>
                    </div>
                    <div>
                        <label class="admin-label">Process Steps (one per line)</label>
                        <textarea name="process_steps_text" rows="4" placeholder="Each step on its own line" class="input-shell w-full"></textarea>
                    </div>
                    <div>
                        <label class="admin-label">Related Project Slugs (one per line)</label>
                        <textarea name="related_projects_text" rows="3" placeholder="Project slugs, one per line" class="input-shell w-full"></textarea>
                    </div>
                    <div>
                        <label class="admin-label">Sort Order</label>
                        <input type="number" name="sort_order" value="0" class="input-shell w-full">
                    </div>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2 text-sm text-muted-foreground"><input type="checkbox" name="is_featured" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary"> Featured</label>
                        <label class="flex items-center gap-2 text-sm text-muted-foreground"><input type="checkbox" name="is_published" value="1" checked class="h-4 w-4 rounded border-border bg-transparent text-primary"> Published</label>
                    </div>
                    <button class="luxury-btn-primary w-full justify-center">Create Service</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>

<x-layouts.admin :company="$company" page-heading="Timeline and Updates">
    <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
        <div class="space-y-5">
            @foreach ($updates as $update)
                @php($attachments = is_array($update->attachments) ? $update->attachments : [])
                <details class="luxury-card group">
                    <summary class="cursor-pointer list-none">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-primary">{{ $update->project?->title }}</p>
                                <h2 class="mt-1 font-display text-xl font-semibold">{{ $update->title }}</h2>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    {{ $update->update_date?->format('M j, Y') }}
                                    @if ($update->progress) &middot; {{ $update->progress }}% @endif
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <x-status-badge :value="$update->visible_to_client ? 'published' : 'internal'" />
                                <x-portal-icon name="chevron-down" class="h-4 w-4 transition-transform group-open:rotate-180" />
                            </div>
                        </div>
                    </summary>
                    <p class="mt-3 text-sm text-muted-foreground">{{ $update->body }}</p>
                    @if (count($attachments) > 0)
                        <div class="mt-4 grid grid-cols-3 gap-2">
                            @foreach ($attachments as $att)
                                @php($isVid = str_contains((string) $att, '.mp4') || str_contains((string) $att, '.webm') || str_contains((string) $att, '.mov'))
                                <div class="overflow-hidden rounded-md border border-border bg-muted">
                                    @if ($isVid)
                                        <video controls class="aspect-video w-full object-cover"><source src="{{ asset($att) }}"></video>
                                    @else
                                        <x-media :path="$att" alt="{{ $update->title }}" class="aspect-video w-full object-cover" />
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form action="{{ route('admin.updates.update', $update) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4 border-t border-border pt-4">
                        @csrf
                        @method('PUT')
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="admin-label">Project</label>
                                <select name="project_id" class="input-shell w-full">
                                    @foreach ($projects as $project)
                                        <option value="{{ $project->id }}" @selected($update->project_id === $project->id)>{{ $project->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="admin-label">Title</label>
                                <input name="title" value="{{ $update->title }}" required class="input-shell w-full">
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">Details</label>
                            <textarea name="body" rows="3" required class="input-shell w-full">{{ $update->body }}</textarea>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="admin-label">Date</label>
                                <input type="date" name="update_date" value="{{ $update->update_date?->format('Y-m-d') }}" class="input-shell w-full">
                            </div>
                            <div>
                                <label class="admin-label">Progress %</label>
                                <input type="number" name="progress" min="0" max="100" value="{{ $update->progress }}" class="input-shell w-full">
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">Add More Photos & Videos</label>
                            <input type="file" name="attachment_uploads[]" accept="image/*,video/*" multiple class="input-shell w-full">
                        </div>
                        <div>
                            <label class="admin-label">Add Media URLs (one per line)</label>
                            <textarea name="attachment_urls" rows="2" placeholder="Paste URLs, one per line" class="input-shell w-full"></textarea>
                        </div>
                        <label class="flex items-center gap-2 text-sm text-muted-foreground">
                            <input type="checkbox" name="visible_to_client" value="1" @checked($update->visible_to_client) class="h-4 w-4 rounded border-border bg-transparent text-primary">
                            Visible to client
                        </label>
                        <button class="luxury-btn-primary">Save Changes</button>
                    </form>
                    <form action="{{ route('admin.updates.destroy', $update) }}" method="POST" class="mt-3" onsubmit="return confirm('Delete this update? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-destructive hover:underline">Delete this update</button>
                    </form>
                </details>
            @endforeach
        </div>
        <div>
            <div class="luxury-card sticky top-20">
                <h2 class="font-display text-xl font-semibold">Add Update</h2>
                <form action="{{ route('admin.updates.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label class="admin-label">Project</label>
                        <select name="project_id" class="input-shell w-full">
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="admin-label">Title</label>
                        <input name="title" required placeholder="Update title" class="input-shell w-full">
                    </div>
                    <div>
                        <label class="admin-label">Details</label>
                        <textarea name="body" rows="4" required placeholder="Update details" class="input-shell w-full"></textarea>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="admin-label">Date</label>
                            <input type="date" name="update_date" value="{{ now()->toDateString() }}" class="input-shell w-full">
                        </div>
                        <div>
                            <label class="admin-label">Progress %</label>
                            <input type="number" name="progress" min="0" max="100" placeholder="e.g. 45" class="input-shell w-full">
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Photos & Videos (optional)</label>
                        <p class="mb-2 text-xs text-muted-foreground">Upload multiple images or videos to attach to this update.</p>
                        <input type="file" name="attachment_uploads[]" accept="image/*,video/*" multiple class="input-shell w-full">
                        <p class="mt-1 text-xs text-muted-foreground">Hold Ctrl/Cmd to select multiple files.</p>
                    </div>
                    <div>
                        <label class="admin-label">Media URLs (optional)</label>
                        <p class="mb-2 text-xs text-muted-foreground">Paste image or video URLs, one per line.</p>
                        <textarea name="attachment_urls" rows="3" placeholder="Paste URLs, one per line" class="input-shell w-full"></textarea>
                    </div>
                    <label class="flex items-center gap-3 text-sm text-muted-foreground">
                        <input type="checkbox" name="visible_to_client" value="1" checked class="h-4 w-4 rounded border-border bg-transparent text-primary">
                        Visible to client
                    </label>
                    <button class="luxury-btn-primary w-full justify-center">Publish Update</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>

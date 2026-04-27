<x-layouts.admin :company="$company" page-heading="Documents">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-display text-3xl">Documents</h1>
                <p class="mt-2 text-sm text-muted-foreground">Manage project records, contracts, offer letters, and client-facing documentation.</p>
            </div>
            <div class="rounded-sm border border-border bg-card px-4 py-3 text-xs uppercase tracking-[0.18em] text-muted-foreground">
                {{ $documents->count() }} documents
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Total Documents</p>
                <p class="mt-2 font-display text-3xl">{{ $documents->count() }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Client Visible</p>
                <p class="mt-2 font-display text-3xl">{{ $documents->where('visible_to_client', true)->count() }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Projects Covered</p>
                <p class="mt-2 font-display text-3xl">{{ $documents->pluck('project_id')->filter()->unique()->count() }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Latest Upload</p>
                <p class="mt-2 font-display text-3xl">{{ optional($documents->first()?->created_at)->format('M j') ?: 'Pending' }}</p>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.2fr_0.8fr]">
            <div class="luxury-card">
                <div class="space-y-3">
                    @forelse ($documents as $document)
                        @php
                            $size = $document->size ? number_format($document->size / 1024, 0).' KB' : 'Pending size';
                        @endphp
                        <div class="flex flex-col gap-4 rounded-sm border border-border/60 p-4 transition hover:bg-muted/20 md:flex-row md:items-center">
                            <div class="flex h-11 w-11 items-center justify-center rounded-sm bg-muted text-sm font-semibold text-muted-foreground">DOC</div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium">{{ $document->title }}</p>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    {{ $document->assignment?->user?->name ?: 'No client' }}
                                    @if ($document->project?->title)
                                        &middot; {{ $document->project->title }}
                                    @endif
                                    &middot; {{ $size }}
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 md:justify-end">
                                <span class="rounded-sm bg-primary/10 px-2 py-1 text-[10px] uppercase tracking-[0.18em] text-primary">{{ $document->category }}</span>
                                <span class="rounded-sm border border-border px-2 py-1 text-[10px] uppercase tracking-[0.18em] text-muted-foreground">{{ $document->version ?: 'v1' }}</span>
                                <span class="rounded-sm border border-border px-2 py-1 text-[10px] uppercase tracking-[0.18em] {{ $document->visible_to_client ? 'text-primary border-primary/30 bg-primary/10' : 'text-muted-foreground' }}">{{ $document->visible_to_client ? 'Client Visible' : 'Internal Only' }}</span>
                                @if ($document->file_url)
                                    <a href="{{ $document->file_url }}" target="_blank" class="luxury-btn-outline">Preview</a>
                                @endif
                            </div>
                        </div>

                        <details class="rounded-sm border border-border/60 bg-card/60">
                            <summary class="flex cursor-pointer list-none items-center justify-between px-4 py-3 text-sm font-medium">
                                <span>Edit Document</span>
                                <x-portal-icon name="pen" class="h-4 w-4 text-primary" />
                            </summary>
                            <div class="space-y-4 border-t border-border/60 p-4">
                                <form action="{{ route('admin.documents.update', $document) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <div>
                                            <label class="admin-label">Project</label>
                                            <select name="project_id" class="input-shell">
                                                <option value="">Select project</option>
                                                @foreach ($projects as $project)
                                                    <option value="{{ $project->id }}" @selected($document->project_id === $project->id)>{{ $project->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="admin-label">Client Assignment</label>
                                            <select name="project_assignment_id" class="input-shell">
                                                <option value="">Shared project document</option>
                                                @foreach ($assignments as $assignment)
                                                    <option value="{{ $assignment->id }}" @selected($document->project_assignment_id === $assignment->id)>{{ $assignment->user->name }} &middot; {{ $assignment->project->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid gap-4 md:grid-cols-2">
                                        <div>
                                            <label class="admin-label">Document Title</label>
                                            <input name="title" value="{{ $document->title }}" required class="input-shell">
                                        </div>
                                        <div>
                                            <label class="admin-label">Category</label>
                                            <input name="category" value="{{ $document->category }}" required class="input-shell">
                                        </div>
                                    </div>

                                    <div class="grid gap-4 md:grid-cols-2">
                                        <div>
                                            <label class="admin-label">Version</label>
                                            <input name="version" value="{{ $document->version }}" placeholder="v1" class="input-shell">
                                        </div>
                                        <div class="grid grid-cols-2 gap-3 pt-6">
                                            <label class="flex items-center gap-2 rounded-sm border border-border px-3 py-2 text-xs text-muted-foreground">
                                                <input type="hidden" name="visible_to_client" value="0">
                                                <input type="checkbox" name="visible_to_client" value="1" @checked($document->visible_to_client) class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                                                Client visible
                                            </label>
                                            <label class="flex items-center gap-2 rounded-sm border border-border px-3 py-2 text-xs text-muted-foreground">
                                                <input type="hidden" name="is_archived" value="0">
                                                <input type="checkbox" name="is_archived" value="1" @checked($document->is_archived) class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                                                Archived
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="admin-label">Notes</label>
                                        <textarea name="notes" rows="3" class="input-shell">{{ $document->notes }}</textarea>
                                    </div>

                                    <div class="rounded-md border border-dashed border-border bg-muted/10 p-4">
                                        <label class="admin-label">Replace file</label>
                                        <input type="file" name="file" data-preview-target="#document-file-preview-{{ $document->id }}" class="input-shell">
                                        <p id="document-file-preview-{{ $document->id }}" class="mt-1 text-xs text-muted-foreground">Leave empty to keep the current file</p>
                                    </div>

                                    <div class="flex flex-wrap items-center justify-between gap-3 border-t border-border/60 pt-4">
                                        <div class="text-[11px] uppercase tracking-[0.18em] text-muted-foreground">{{ $document->project_assignment_id ? 'Assignment document' : 'Shared project document' }}</div>
                                        <button class="luxury-btn-primary text-xs">Save Changes</button>
                                    </div>
                                </form>

                                <form action="{{ route('admin.documents.destroy', $document) }}" method="POST" class="border-t border-border/60 pt-4">
                                    @csrf
                                    @method('DELETE')
                                    <button class="luxury-btn-destructive w-full justify-center text-xs">Delete Document</button>
                                </form>
                            </div>
                        </details>
                    @empty
                        <div class="rounded-sm border border-dashed border-border px-5 py-12 text-center text-sm text-muted-foreground">
                            No documents have been uploaded yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="luxury-card">
                <h2 class="font-display text-3xl">Upload Document</h2>
                <p class="mt-2 text-sm text-muted-foreground">Attach a project or assignment, then publish the document to the admin and client experience.</p>

                <form action="{{ route('admin.documents.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
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
                        <label class="admin-label">Client Assignment</label>
                        <select name="project_assignment_id" class="input-shell">
                            <option value="">Shared project document</option>
                            @foreach ($assignments as $assignment)
                                <option value="{{ $assignment->id }}" @selected((string) old('project_assignment_id') === (string) $assignment->id)>{{ $assignment->user->name }} &middot; {{ $assignment->project->title }}</option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-muted-foreground">Leave assignment empty if the document should be available to every client attached to the selected project.</p>
                    </div>
                    <div>
                        <label class="admin-label">Document Title</label>
                        <input name="title" value="{{ old('title') }}" required class="input-shell">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="admin-label">Category</label>
                            <input name="category" value="{{ old('category') }}" required class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Version</label>
                            <input name="version" value="{{ old('version') }}" placeholder="v1" class="input-shell">
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Notes</label>
                        <textarea name="notes" rows="3" class="input-shell">{{ old('notes') }}</textarea>
                    </div>
                    <div>
                        <label class="admin-label">File Upload</label>
                        <input type="file" name="file" data-preview-target="#document-file-preview" class="input-shell">
                        <p id="document-file-preview" class="mt-1 text-xs text-muted-foreground">No file selected</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 rounded-sm border border-border px-3 py-2 text-xs text-muted-foreground">
                            <input type="hidden" name="visible_to_client" value="0">
                            <input type="checkbox" name="visible_to_client" value="1" @checked(old('visible_to_client', true)) class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                            Client visible
                        </label>
                        <label class="flex items-center gap-2 rounded-sm border border-border px-3 py-2 text-xs text-muted-foreground">
                            <input type="hidden" name="is_archived" value="0">
                            <input type="checkbox" name="is_archived" value="1" @checked(old('is_archived')) class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                            Archived
                        </label>
                    </div>
                    <button class="luxury-btn-primary w-full">Upload Document</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>

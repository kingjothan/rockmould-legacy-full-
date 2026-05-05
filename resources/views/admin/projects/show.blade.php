<x-layouts.admin :company="$company" :page-heading="$project->title">
    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-4">
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Status</p>
                <p class="mt-2 font-display text-3xl">{{ str($project->status)->title() }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Progress</p>
                <p class="mt-2 font-display text-3xl">{{ $project->progress }}%</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Assignments</p>
                <p class="mt-2 font-display text-3xl">{{ $project->assignments->count() }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Documents</p>
                <p class="mt-2 font-display text-3xl">{{ $project->documentsRelation->count() }}</p>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[0.95fr_1.05fr]">
            <div class="space-y-8">
                <div class="luxury-card">
                    <h2 class="font-display text-3xl">Edit Project</h2>
                    <form action="{{ route('admin.projects.update', $project) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <input name="title" value="{{ $project->title }}" class="input-shell">
                        <input name="slug" value="{{ $project->slug }}" class="input-shell">
                        <input name="location" value="{{ $project->location }}" class="input-shell">
                        <input name="category" value="{{ $project->category }}" class="input-shell">
                        <input name="status" value="{{ $project->status }}" class="input-shell">
                        <textarea name="overview" rows="3" class="input-shell">{{ $project->overview }}</textarea>
                        <textarea name="description" rows="4" class="input-shell">{{ $project->description }}</textarea>
                        <input name="cover_image" value="{{ $project->cover_image }}" class="input-shell">
                        <div>
                            <label class="admin-label">Upload New Cover Media</label>
                            <input type="file" name="cover_image_upload" accept="image/*,video/*" class="input-shell">
                            <p class="mt-2 text-xs text-muted-foreground">Accepts image or video up to 200MB. This cover is used on public listings, detail pages, portfolio, and portal.</p>
                        </div>
                        <input type="number" name="progress" value="{{ $project->progress }}" class="input-shell">
                        <input type="number" name="budget" value="{{ $project->budget }}" class="input-shell">
                        <input type="number" name="price_from" value="{{ $project->price_from }}" class="input-shell">
                        <textarea name="features_text" rows="4" class="input-shell">{{ implode("\n", $project->features ?? []) }}</textarea>
                        <textarea name="gallery_text" rows="4" class="input-shell">{{ implode("\n", $project->gallery ?? []) }}</textarea>
                        <textarea name="highlights_json" rows="5" class="input-shell">{{ json_encode($project->highlights ?? [], JSON_PRETTY_PRINT) }}</textarea>
                        <div class="space-y-3 rounded-lg border border-border bg-card/50 p-4">
                            <div>
                                <label class="admin-label">Add More Project Media</label>
                                <input type="file" name="media_uploads[]" accept="image/*,video/*" multiple class="input-shell">
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
                                <label class="admin-label">Add More Project Documents</label>
                                <input type="file" name="document_uploads[]" multiple class="input-shell">
                            </div>
                            <div class="grid gap-3 sm:grid-cols-2">
                                <input name="document_category" placeholder="Document category e.g. Brochure" class="input-shell">
                                <input name="document_version" value="v1" placeholder="Version" class="input-shell">
                            </div>
                            <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="uploaded_documents_visible_to_client" value="1" checked> Make uploaded documents visible to clients</label>
                        </div>
                        <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_featured" value="1" @checked($project->is_featured)> Feature on website</label>
                        <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_portfolio" value="1" @checked($project->is_portfolio)> Include in portfolio</label>
                        <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_published" value="1" @checked($project->is_published)> Published</label>
                        <button class="luxury-btn-primary">Save Project</button>
                    </form>
                </div>

                <div class="luxury-card">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="font-display text-3xl">Client Assignments</h2>
                        <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">{{ $project->assignments->count() }} clients</p>
                    </div>
                    <div class="mt-5 space-y-4">
                        @forelse ($project->assignments as $assignment)
                            <div class="rounded-sm border border-border bg-muted/20 p-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-medium">{{ $assignment->user->name }}</p>
                                        <p class="text-sm text-muted-foreground">{{ $assignment->unit_label }}</p>
                                    </div>
                                    <x-status-badge :value="$assignment->status" />
                                </div>
                                <div class="mt-4 grid gap-2 text-sm">
                                    <div class="flex items-center justify-between"><span class="text-muted-foreground">Progress</span><span>{{ $assignment->progress_percentage }}%</span></div>
                                    <div class="flex items-center justify-between"><span class="text-muted-foreground">Paid</span><span>NGN {{ number_format((float) $assignment->amount_paid) }}</span></div>
                                    <div class="flex items-center justify-between"><span class="text-muted-foreground">Outstanding</span><span>NGN {{ number_format((float) $assignment->outstanding_balance) }}</span></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">No clients have been assigned to this project yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-8">
                <div class="luxury-card">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="font-display text-3xl">Project Gallery Assets</h2>
                            <p class="mt-2 text-sm text-muted-foreground">Exterior, interior, and general gallery media connected to this public project and the client portal.</p>
                        </div>
                        <a href="{{ route('admin.media.index', ['project_id' => $project->id]) }}" class="luxury-btn-outline text-xs">Manage Media Library</a>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-3">
                        @foreach (\App\Models\MediaAsset::gallerySectionOptions() as $sectionValue => $sectionLabel)
                            @php($sectionCount = $project->mediaAssets->where('gallery_section', $sectionValue)->count())
                            <div class="rounded-sm border border-border bg-muted/20 px-4 py-3">
                                <p class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">{{ $sectionLabel }}</p>
                                <p class="mt-2 font-display text-2xl">{{ $sectionCount }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                        @forelse ($project->mediaAssets->sortBy([['gallery_section', 'asc'], ['sort_order', 'asc']])->take(6) as $asset)
                            <a href="{{ route('admin.media.index', ['project_id' => $project->id, 'q' => $asset->title]) }}" class="group overflow-hidden rounded-sm border border-border/60 bg-card transition hover:border-primary/30">
                                <div class="relative aspect-[5/4] bg-muted">
                                    <x-media :path="$asset->media_type === 'video' ? $asset->file_path : ($asset->thumbnail_path ?: $asset->file_path)" fallback="images/project-1.jpg" alt="{{ $asset->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" :loop="false" />
                                    <div class="absolute left-3 top-3 flex flex-wrap gap-2">
                                        <span class="rounded-full border border-white/15 bg-black/55 px-2 py-1 text-[10px] uppercase tracking-[0.18em] text-white/80">{{ $asset->media_type }}</span>
                                        <span class="rounded-full border border-white/15 bg-black/55 px-2 py-1 text-[10px] uppercase tracking-[0.18em] text-white/80">{{ $asset->gallery_section_label }}</span>
                                    </div>
                                </div>
                                <div class="p-4">
                                    <p class="truncate text-sm font-medium">{{ $asset->title }}</p>
                                    <p class="mt-1 text-xs text-muted-foreground">{{ $asset->visible_to_client ? 'Visible to clients/public when shared' : 'Hidden from clients' }}</p>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-sm border border-dashed border-border px-5 py-10 text-center text-sm text-muted-foreground sm:col-span-2 xl:col-span-3">
                                No media has been added yet. Use the edit form or the Media Library to upload exterior, interior, or general project assets.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="luxury-card">
                    <h2 class="font-display text-3xl">Assign Client</h2>
                    <form action="{{ route('admin.projects.assignments.store', $project) }}" method="POST" class="mt-6 space-y-4">
                        @csrf
                        <select name="user_id" class="input-shell">
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                        <input name="unit_label" required placeholder="Unit or allocation label" class="input-shell">
                        <select name="status" class="input-shell"><option value="active">Active</option><option value="completed">Completed</option><option value="upcoming">Upcoming</option></select>
                        <input type="number" name="progress_percentage" value="0" min="0" max="100" class="input-shell">
                        <input type="number" name="contract_amount" required placeholder="Contract amount" class="input-shell">
                        <input type="number" name="amount_paid" required placeholder="Amount paid" class="input-shell">
                        <input type="number" name="next_payment_amount" placeholder="Next payment amount" class="input-shell">
                        <input type="date" name="next_payment_due_at" class="input-shell">
                        <button class="luxury-btn-primary w-full">Assign Client</button>
                    </form>
                </div>

                <div class="luxury-card">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="font-display text-3xl">Recent Updates</h2>
                        <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">{{ $project->updates->count() }} updates</p>
                    </div>
                    <div class="mt-5 space-y-4">
                        @forelse ($project->updates as $update)
                            <div class="rounded-sm border border-border bg-muted/20 p-4">
                                <p class="font-medium">{{ $update->title }}</p>
                                <p class="mt-2 text-sm text-muted-foreground">{{ $update->body }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">No updates have been published for this project yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

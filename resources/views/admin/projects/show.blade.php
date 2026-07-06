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
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Client Units</p>
                <p class="mt-2 font-display text-3xl">{{ $project->units->count() }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.22em] text-muted-foreground">Documents</p>
                <p class="mt-2 font-display text-3xl">{{ $project->documentsRelation->count() }}</p>
            </div>
        </div>

        <div class="grid gap-8 lg:grid-cols-[0.95fr_1.05fr]">
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
                        <select name="status" class="input-shell">
                            <option value="ongoing" @selected($project->status === 'ongoing')>Ongoing</option>
                            <option value="completed" @selected($project->status === 'completed')>Completed</option>
                            <option value="upcoming" @selected($project->status === 'upcoming')>Upcoming</option>
                        </select>
                        <textarea name="overview" rows="3" class="input-shell">{{ $project->overview }}</textarea>
                        <textarea name="description" rows="4" class="input-shell">{{ $project->description }}</textarea>
                        <input name="cover_image" value="{{ $project->cover_image }}" class="input-shell" placeholder="CDN cover image/video URL or local path">
                        <div>
                            <label class="admin-label">Upload New Cover Media</label>
                            <input type="file" name="cover_image_upload" accept="image/*,video/*" class="input-shell">
                            <p class="mt-2 text-xs text-muted-foreground">Accepts image or video up to 200MB. This cover is used on public listings, detail pages, portfolio, and portal.</p>
                            <label class="mt-3 flex items-center gap-2 text-xs text-muted-foreground">
                                <input type="checkbox" name="remove_cover_image" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                                Remove current cover media if no new link or upload is provided
                            </label>
                        </div>
                        <input type="number" name="progress" value="{{ $project->progress }}" placeholder="Progress %" class="input-shell">
                        <input type="number" name="budget" value="{{ $project->budget }}" placeholder="Budget (NGN)" class="input-shell">
                        <input type="number" name="price_from" value="{{ $project->price_from }}" placeholder="Price From (NGN)" class="input-shell">
                        <div class="grid gap-3 sm:grid-cols-3">
                            <div>
                                <label class="admin-label">Total Units</label>
                                <input type="number" name="total_units" value="{{ $project->total_units }}" placeholder="e.g. 24" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Available Units</label>
                                <input type="number" name="available_units" value="{{ $project->available_units }}" placeholder="e.g. 8" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Built Area</label>
                                <input name="built_area" value="{{ $project->built_area }}" placeholder="e.g. 450 sqm per unit" class="input-shell">
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">Residence Profile</label>
                            <textarea name="residence_profile" rows="4" placeholder="Describe the residence — layout types, finishes, lifestyle positioning…" class="input-shell">{{ $project->residence_profile }}</textarea>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label class="admin-label">Start Date</label>
                                <input type="date" name="launch_date" value="{{ optional($project->launch_date)->format('Y-m-d') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Completion Date</label>
                                <input type="date" name="completion_date" value="{{ optional($project->completion_date)->format('Y-m-d') }}" class="input-shell">
                            </div>
                        </div>
                        <textarea name="features_text" rows="4" class="input-shell">{{ implode("\n", $project->features ?? []) }}</textarea>
                        <textarea name="gallery_text" rows="4" class="input-shell">{{ implode("\n", $project->gallery ?? []) }}</textarea>
                        <textarea name="highlights_json" rows="5" class="input-shell">{{ json_encode($project->highlights ?? [], JSON_PRETTY_PRINT) }}</textarea>
                        <div class="space-y-3 rounded-lg border border-border bg-card/50 p-4">
                            <div>
                                <label class="admin-label">Add More Project Media</label>
                                <input type="file" name="media_uploads[]" accept="image/*,video/*" multiple class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Add Media CDN Links</label>
                                <textarea name="media_urls_text" rows="4" placeholder="Paste one direct image/video URL per line" class="input-shell"></textarea>
                                <p class="mt-2 text-xs text-muted-foreground">Each link becomes a real project gallery asset in the selected section.</p>
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

                <div class="luxury-card border-destructive/30">
                    <h2 class="font-display text-3xl text-destructive">Delete Project</h2>
                    <p class="mt-2 text-sm text-muted-foreground">
                        This permanently removes the project, client unit assignments, units, milestones, updates, project media, documents, and related payment records linked through assignments.
                    </p>
                    <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="mt-5 space-y-4" onsubmit="return confirm('Delete this project permanently? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <div>
                            <label class="admin-label">Type "{{ $project->title }}" to confirm</label>
                            <input name="confirm_title" class="input-shell" placeholder="{{ $project->title }}">
                        </div>
                        <button class="luxury-btn-outline border-destructive/50 text-destructive hover:border-destructive">Delete Project Permanently</button>
                    </form>
                </div>

                <div class="luxury-card">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="font-display text-3xl">Client Units</h2>
                        <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">{{ $project->units->count() }} units</p>
                    </div>
                    <div class="mt-5 space-y-4">
                        @forelse ($project->assignments as $assignment)
                            <form action="{{ route('admin.projects.assignments.update', [$project, $assignment]) }}" method="POST" class="rounded-sm border border-border bg-muted/20 p-4">
                                @csrf
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="min-w-0">
                                        <p class="font-medium">{{ $assignment->reference }}</p>
                                        <p class="text-sm text-muted-foreground">{{ $assignment->user?->name ?? 'Unassigned client' }} &middot; {{ $assignment->unit?->label ?? $assignment->unit_label }}</p>
                                    </div>
                                    <x-status-badge :value="$assignment->status" />
                                </div>
                                <div class="mt-4 grid gap-3 md:grid-cols-2">
                                    <select name="user_id" class="input-shell">
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}" @selected($assignment->user_id === $client->id)>{{ $client->name }}</option>
                                        @endforeach
                                    </select>
                                    <input name="unit_label" value="{{ $assignment->unit?->label ?? $assignment->unit_label }}" class="input-shell">
                                    <select name="status" class="input-shell">
                                        @foreach (['active' => 'Active', 'completed' => 'Completed', 'upcoming' => 'Upcoming', 'inactive' => 'Inactive'] as $value => $label)
                                            <option value="{{ $value }}" @selected($assignment->status === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="progress_percentage" value="{{ $assignment->progress_percentage }}" min="0" max="100" class="input-shell">
                                    <input type="number" name="contract_amount" value="{{ $assignment->contract_amount }}" class="input-shell">
                                    <input type="number" name="amount_paid" value="{{ $assignment->amount_paid }}" class="input-shell">
                                    <input type="number" name="next_payment_amount" value="{{ $assignment->next_payment_amount }}" placeholder="Next payment amount" class="input-shell">
                                    <input type="date" name="next_payment_due_at" value="{{ optional($assignment->next_payment_due_at)->format('Y-m-d') }}" class="input-shell">
                                    <textarea name="client_notes" rows="2" placeholder="Client-facing notes" class="input-shell md:col-span-2">{{ $assignment->client_notes }}</textarea>
                                    <textarea name="internal_notes" rows="2" placeholder="Internal notes" class="input-shell md:col-span-2">{{ $assignment->internal_notes }}</textarea>
                                </div>
                                <div class="mt-4 flex items-center justify-between gap-3">
                                    <p class="text-sm text-muted-foreground">Outstanding: NGN {{ number_format((float) $assignment->outstanding_balance) }}</p>
                                    <button class="luxury-btn-outline text-xs">Update Ownership</button>
                                </div>
                            </form>
                        @empty
                            <p class="text-sm text-muted-foreground">No clients have been assigned to this project yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="luxury-card">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="font-display text-3xl">Unit Inventory</h2>
                        <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">{{ $project->units->count() }} records</p>
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse ($project->units->sortBy('label') as $unit)
                            <div class="flex items-center justify-between gap-4 rounded-sm border border-border bg-muted/20 px-4 py-3">
                                <div>
                                    <p class="font-medium">{{ $unit->label }}</p>
                                    <p class="text-sm text-muted-foreground">{{ $unit->client?->name ?? 'No client assigned' }}</p>
                                </div>
                                <div class="text-right">
                                    <x-status-badge :value="$unit->status" />
                                    <p class="mt-1 text-xs text-muted-foreground">{{ $unit->price ? 'NGN '.number_format((float) $unit->price) : 'No price set' }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">No units have been created for this project yet.</p>
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

                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
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
                        <input name="unit_label" placeholder="Single unit or allocation label" class="input-shell">
                        <textarea name="unit_labels" rows="3" placeholder="Or paste multiple unit labels, one per line" class="input-shell"></textarea>
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

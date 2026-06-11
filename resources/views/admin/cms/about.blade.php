<x-layouts.admin :company="$company" page-heading="About Page CMS">
    @php
        $aboutValuesText = collect($about['values'] ?? [])
            ->map(fn ($value) => ($value['title'] ?? '').'|'.($value['description'] ?? ''))
            ->implode(PHP_EOL);
        $aboutMetricsText = collect($about['metrics'] ?? [])
            ->map(fn ($metric) => ($metric['label'] ?? '').'|'.($metric['value'] ?? ''))
            ->implode(PHP_EOL);
    @endphp

    <div class="space-y-8">
        <div class="luxury-card border-primary/20 bg-primary/5">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="section-label">Full About Page Control</p>
                    <h1 class="font-display text-4xl">About page content, leadership, media, timeline, and CTA.</h1>
                    <p class="mt-3 max-w-3xl text-sm leading-6 text-muted-foreground">
                        Edit every public About page section here. Leadership media supports images or videos up to 200MB, and timeline entries can be reordered, hidden, edited, added, or deleted.
                    </p>
                </div>
                <a href="{{ route('about') }}" target="_blank" class="luxury-btn-outline shrink-0 text-xs">Preview About Page</a>
            </div>
        </div>

        <form action="{{ route('admin.cms.about.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <div class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
                <div class="space-y-8">
                    <div class="luxury-card space-y-5">
                        <div>
                            <p class="section-label">Hero and Story</p>
                            <h2 class="font-display text-3xl">Opening Narrative</h2>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Hero Subtitle</label>
                                <input name="hero_subtitle" value="{{ old('hero_subtitle', $about['hero_subtitle'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Hero Title</label>
                                <input name="hero_title" value="{{ old('hero_title', $about['hero_title'] ?? '') }}" class="input-shell">
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">Hero Description</label>
                            <textarea name="hero_description" rows="4" class="input-shell">{{ old('hero_description', $about['hero_description'] ?? '') }}</textarea>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Hero Media Link or Path</label>
                                <input name="hero_media_path" value="{{ old('hero_media_path', $about['hero_media_path'] ?? '') }}" class="input-shell" placeholder="https://cdn.example.com/about-hero.mp4">
                                <p class="mt-2 text-xs text-muted-foreground">Paste a CDN/direct image or video URL, or keep a local path.</p>
                            </div>
                            <div>
                                <label class="admin-label">Story Media Link or Path</label>
                                <input name="story_image_path" value="{{ old('story_image_path', $about['story_image'] ?? '') }}" class="input-shell" placeholder="https://cdn.example.com/about-story.jpg">
                                <p class="mt-2 text-xs text-muted-foreground">Use a CDN/direct image or video for the company story block.</p>
                            </div>
                            <div>
                                <label class="admin-label">Upload Hero Media</label>
                                <input type="file" name="hero_media" accept="image/*,video/*" data-preview-target="#about-hero-media-preview" class="input-shell">
                                <p id="about-hero-media-preview" class="mt-2 text-xs text-muted-foreground">{{ $about['hero_media_path'] ?? 'No new file selected' }}</p>
                                <label class="mt-3 flex items-center gap-2 text-xs text-muted-foreground">
                                    <input type="checkbox" name="remove_hero_media" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                                    Remove current hero media if no new link or upload is provided
                                </label>
                            </div>
                            <div>
                                <label class="admin-label">Upload Story Media</label>
                                <input type="file" name="story_image" accept="image/*,video/*" data-preview-target="#about-story-image-preview" class="input-shell">
                                <p id="about-story-image-preview" class="mt-2 text-xs text-muted-foreground">{{ $about['story_image'] ?? 'No new file selected' }}</p>
                                <label class="mt-3 flex items-center gap-2 text-xs text-muted-foreground">
                                    <input type="checkbox" name="remove_story_image" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                                    Remove current story media if no new link or upload is provided
                                </label>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-[0.5fr_1fr]">
                            <div>
                                <label class="admin-label">Story Label</label>
                                <input name="story_label" value="{{ old('story_label', $about['story_label'] ?? 'Our Story') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Story Title</label>
                                <input name="story_title" value="{{ old('story_title', $about['story_title'] ?? '') }}" class="input-shell">
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">Story Body</label>
                            <textarea name="story" rows="7" class="input-shell">{{ old('story', implode("\n", $about['story'] ?? [])) }}</textarea>
                            <p class="mt-2 text-xs text-muted-foreground">One paragraph per line.</p>
                        </div>
                    </div>

                    <div class="luxury-card space-y-5">
                        <div>
                            <p class="section-label">Vision, Mission and Values</p>
                            <h2 class="font-display text-3xl">Principles Section</h2>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Vision Title</label>
                                <input name="vision_title" value="{{ old('vision_title', $about['vision_title'] ?? 'Our Vision') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Mission Title</label>
                                <input name="mission_title" value="{{ old('mission_title', $about['mission_title'] ?? 'Our Mission') }}" class="input-shell">
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">Vision</label>
                            <textarea name="vision" rows="4" class="input-shell">{{ old('vision', $about['vision'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Mission</label>
                            <textarea name="mission" rows="4" class="input-shell">{{ old('mission', $about['mission'] ?? '') }}</textarea>
                        </div>
                        <div class="grid gap-4 md:grid-cols-[0.5fr_1fr]">
                            <div>
                                <label class="admin-label">Values Label</label>
                                <input name="values_label" value="{{ old('values_label', $about['values_label'] ?? 'Core Values') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Values Title</label>
                                <input name="values_title" value="{{ old('values_title', $about['values_title'] ?? 'The Pillars of Our Identity') }}" class="input-shell">
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">Core Values</label>
                            <textarea name="values_text" rows="6" class="input-shell">{{ old('values_text', $aboutValuesText) }}</textarea>
                            <p class="mt-2 text-xs text-muted-foreground">Use Title|Description per line.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    <div class="luxury-card space-y-5">
                        <div>
                            <p class="section-label">Section Titles</p>
                            <h2 class="font-display text-3xl">Leadership, Timeline and Metrics</h2>
                        </div>
                        <div>
                            <label class="admin-label">Leadership Label</label>
                            <input name="team_label" value="{{ old('team_label', $about['team_label'] ?? 'Leadership') }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Leadership Title</label>
                            <input name="team_title" value="{{ old('team_title', $about['team_title'] ?? 'The Team Behind the Vision') }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Timeline Label</label>
                            <input name="timeline_label" value="{{ old('timeline_label', $about['timeline_label'] ?? 'Our Journey') }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Timeline Title</label>
                            <input name="timeline_title" value="{{ old('timeline_title', $about['timeline_title'] ?? 'Milestones of Excellence') }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">About Metrics</label>
                            <textarea name="metrics_text" rows="5" class="input-shell">{{ old('metrics_text', $aboutMetricsText) }}</textarea>
                            <p class="mt-2 text-xs text-muted-foreground">Use Label|Value per line.</p>
                        </div>
                    </div>

                    <div class="luxury-card space-y-5">
                        <div>
                            <p class="section-label">Final CTA</p>
                            <h2 class="font-display text-3xl">Bottom Call To Action</h2>
                        </div>
                        <div>
                            <label class="admin-label">CTA Title</label>
                            <input name="cta_title" value="{{ old('cta_title', $about['cta_title'] ?? 'Partner with Excellence') }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">CTA Description</label>
                            <textarea name="cta_description" rows="4" class="input-shell">{{ old('cta_description', $about['cta_description'] ?? '') }}</textarea>
                        </div>
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="admin-label">Primary Button</label>
                                <input name="cta_primary" value="{{ old('cta_primary', $about['cta_primary'] ?? 'Schedule Consultation') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Secondary Button</label>
                                <input name="cta_secondary" value="{{ old('cta_secondary', $about['cta_secondary'] ?? 'View Projects') }}" class="input-shell">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sticky bottom-4 z-20 flex justify-end">
                <button class="luxury-btn-primary">Save About Page Content</button>
            </div>
        </form>

        <div class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="luxury-card">
                <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="section-label">Leadership CMS</p>
                        <h2 class="font-display text-3xl">{{ $about['team_title'] ?? 'The Team Behind the Vision' }}</h2>
                    </div>
                    <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">{{ $team->count() }} members</p>
                </div>

                <div class="space-y-5">
                    @forelse ($team as $member)
                        <div class="rounded-lg border border-border bg-card/60 p-4">
                            <div class="grid gap-4 lg:grid-cols-[160px_1fr]">
                                <div class="overflow-hidden rounded-md border border-border bg-muted">
                                    <x-media :path="$member->image_path" fallback="images/project-2.jpg" alt="{{ $member->name }}" class="aspect-[4/5] w-full object-cover" :controls="true" :loop="false" />
                                </div>
                                <div class="space-y-4">
                                    <form action="{{ route('admin.cms.about.team.update', $member) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div class="grid gap-3 md:grid-cols-2">
                                            <input name="name" value="{{ $member->name }}" class="input-shell">
                                            <input name="role" value="{{ $member->role }}" class="input-shell">
                                        </div>
                                        <textarea name="bio" rows="3" class="input-shell">{{ $member->bio }}</textarea>
                                        <div class="grid gap-3 md:grid-cols-[1fr_140px]">
                                            <input name="image_path" value="{{ $member->image_path }}" placeholder="Existing image/video path" class="input-shell">
                                            <input type="number" min="0" name="sort_order" value="{{ $member->sort_order }}" class="input-shell">
                                        </div>
                                        <div class="rounded-sm border border-dashed border-border bg-muted/10 p-4">
                                            <label class="admin-label">Replace Leadership Photo or Video</label>
                                            <input type="file" name="image_upload" accept="image/*,video/*" data-preview-target="#team-member-preview-{{ $member->id }}" class="input-shell">
                                            <p id="team-member-preview-{{ $member->id }}" class="mt-2 text-xs text-muted-foreground">Leave empty to keep current media. Accepts image or video.</p>
                                            <label class="mt-3 flex items-center gap-2 text-xs text-muted-foreground">
                                                <input type="checkbox" name="remove_image_path" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                                                Remove current member media if no new link or upload is provided
                                            </label>
                                        </div>
                                        <div class="flex flex-wrap items-center justify-between gap-3">
                                            <label class="flex items-center gap-2 text-sm text-muted-foreground">
                                                <input type="checkbox" name="is_visible" value="1" @checked($member->is_visible)> Visible on About page
                                            </label>
                                            <button class="luxury-btn-primary text-xs">Save Member</button>
                                        </div>
                                    </form>

                                    <form action="{{ route('admin.cms.about.team.destroy', $member) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="luxury-btn-destructive w-full justify-center text-xs">Delete Member</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-muted-foreground">No leadership members yet. Add the first one from the form beside this list.</p>
                    @endforelse
                </div>
            </div>

            <div class="luxury-card">
                <p class="section-label">Add Leadership Member</p>
                <h2 class="font-display text-3xl">New Team Member</h2>
                <form action="{{ route('admin.cms.about.team.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                    @csrf
                    <input name="name" required placeholder="Full name" class="input-shell">
                    <input name="role" required placeholder="Role or title" class="input-shell">
                    <textarea name="bio" rows="4" placeholder="Short biography" class="input-shell"></textarea>
                    <input name="image_path" placeholder="Optional existing image/video path" class="input-shell">
                    <input type="file" name="image_upload" accept="image/*,video/*" data-preview-target="#new-team-member-preview" class="input-shell">
                    <p id="new-team-member-preview" class="text-xs text-muted-foreground">No file selected</p>
                    <input type="number" min="0" name="sort_order" value="0" class="input-shell">
                    <label class="flex items-center gap-2 text-sm text-muted-foreground">
                        <input type="checkbox" name="is_visible" value="1" checked> Visible on About page
                    </label>
                    <button class="luxury-btn-primary w-full justify-center">Add Member</button>
                </form>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="luxury-card">
                <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="section-label">Timeline CMS</p>
                        <h2 class="font-display text-3xl">{{ $about['timeline_title'] ?? 'Milestones of Excellence' }}</h2>
                    </div>
                    <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">{{ $timeline->count() }} milestones</p>
                </div>

                <div class="space-y-4">
                    @forelse ($timeline as $entry)
                        <div class="rounded-lg border border-border bg-card/60 p-4">
                            <form action="{{ route('admin.cms.about.timeline.update', $entry) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')
                                <div class="grid gap-3 md:grid-cols-[120px_1fr_120px]">
                                    <input name="year" value="{{ $entry->year }}" class="input-shell">
                                    <input name="title" value="{{ $entry->title }}" class="input-shell">
                                    <input type="number" min="0" name="sort_order" value="{{ $entry->sort_order }}" class="input-shell">
                                </div>
                                <textarea name="description" rows="3" class="input-shell">{{ $entry->description }}</textarea>
                                <div class="flex flex-wrap items-center justify-between gap-3">
                                    <label class="flex items-center gap-2 text-sm text-muted-foreground">
                                        <input type="checkbox" name="is_visible" value="1" @checked($entry->is_visible)> Visible on About page
                                    </label>
                                    <button class="luxury-btn-primary text-xs">Save Milestone</button>
                                </div>
                            </form>
                            <form action="{{ route('admin.cms.about.timeline.destroy', $entry) }}" method="POST" class="mt-3">
                                @csrf
                                @method('DELETE')
                                <button class="luxury-btn-destructive w-full justify-center text-xs">Delete Milestone</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-sm text-muted-foreground">No milestones yet. Add the first one from the form beside this list.</p>
                    @endforelse
                </div>
            </div>

            <div class="luxury-card">
                <p class="section-label">Add Milestone</p>
                <h2 class="font-display text-3xl">New Timeline Entry</h2>
                <form action="{{ route('admin.cms.about.timeline.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <input name="year" required placeholder="Year or date label" class="input-shell">
                    <input name="title" required placeholder="Milestone title" class="input-shell">
                    <textarea name="description" rows="4" required placeholder="Milestone description" class="input-shell"></textarea>
                    <input type="number" min="0" name="sort_order" value="0" class="input-shell">
                    <label class="flex items-center gap-2 text-sm text-muted-foreground">
                        <input type="checkbox" name="is_visible" value="1" checked> Visible on About page
                    </label>
                    <button class="luxury-btn-primary w-full justify-center">Add Milestone</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>

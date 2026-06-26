<x-layouts.public :company="$company" title="Gallery | Rockmould Limited">
    <x-hero
        :title="$page['hero_title'] ?? 'Project and Media Gallery'"
        :subtitle="$page['hero_subtitle'] ?? 'Gallery'"
        :description="$page['hero_description'] ?? 'Construction photography, project visuals, and client-facing media updates from across the Rockmould portfolio.'"
        :image="$page['hero_media_path'] ?? 'images/project-3.jpg'"
        :title-size="$page['font_sizes']['hero_title'] ?? null"
        :description-size="$page['font_sizes']['hero_description'] ?? null"
    />

    @if ($liveUpdates->isNotEmpty())
        <section class="section-padding bg-card">
            <div class="container-luxury">
                <x-section-heading
                    label="Live Updates"
                    title="Project Progress"
                    description="The latest construction progress and development updates direct from our project sites."
                />

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($liveUpdates as $update)
                        <div class="luxury-card reveal overflow-hidden p-0">
                            @if (!empty($update->attachments))
                                <div class="aspect-[16/10] bg-muted">
                                    @php($firstAttachment = $update->attachments[0] ?? null)
                                    @if ($firstAttachment)
                                        @if (str_contains($firstAttachment, '.mp4') || str_contains($firstAttachment, '.webm') || str_contains($firstAttachment, '.mov'))
                                            <video controls class="h-full w-full object-cover">
                                                <source src="{{ \App\Support\AssetPath::url($firstAttachment) }}">
                                            </video>
                                        @else
                                            <x-media :path="$firstAttachment" alt="{{ $update->title }}" class="h-full w-full object-cover" />
                                        @endif
                                    @endif
                                </div>
                            @endif
                            <div class="p-5">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    @if ($update->project)
                                        <span class="text-[10px] uppercase tracking-[0.15em] text-primary">{{ $update->project->title }}</span>
                                    @endif
                                    @if ($update->update_date)
                                        <span class="text-[10px] text-muted-foreground">{{ $update->update_date->format('M j, Y') }}</span>
                                    @endif
                                </div>
                                <h3 class="font-display text-lg font-semibold">{{ $update->title }}</h3>
                                <p class="mt-2 text-sm leading-relaxed text-muted-foreground line-clamp-3">{{ $update->body }}</p>
                                @if ($update->progress)
                                    <div class="mt-4">
                                        <div class="flex items-center justify-between text-xs text-muted-foreground">
                                            <span>Progress</span>
                                            <span class="font-medium text-primary">{{ $update->progress }}%</span>
                                        </div>
                                        <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-muted">
                                            <div class="h-full rounded-full bg-primary" style="width: {{ $update->progress }}%"></div>
                                        </div>
                                    </div>
                                @endif
                                @if (count($update->attachments ?? []) > 1)
                                    <p class="mt-3 text-xs text-muted-foreground">+{{ count($update->attachments) - 1 }} more {{ count($update->attachments) - 1 === 1 ? 'photo' : 'photos' }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section-padding">
        <div class="container-luxury">
            @php
                $sectionDescriptions = [
                    'exterior' => 'Facade views, site progress, landscape moments, and the arrival experience.',
                    'interior' => 'Lobbies, living areas, kitchens, amenities, and interior fit-out visuals.',
                    'general' => 'Complete media gallery for each project, including mixed updates and supporting visuals.',
                ];
            @endphp

            <form method="GET" class="mb-10 grid gap-4 rounded-lg border border-border bg-card p-6 lg:grid-cols-[1fr_220px_220px_160px]">
                <div>
                    <label class="admin-label">Project</label>
                    <select name="project" class="input-shell">
                        <option value="">All Projects</option>
                        @foreach ($projects as $project)
                            <option value="{{ $project->slug }}" @selected(request('project') === $project->slug)>{{ $project->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="admin-label">Media Type</label>
                    <select name="type" class="input-shell">
                        <option value="">All Media</option>
                        <option value="image" @selected(request('type') === 'image')>Images</option>
                        <option value="video" @selected(request('type') === 'video')>Videos</option>
                    </select>
                </div>
                <div>
                    <label class="admin-label">Gallery Section</label>
                    <select name="section" class="input-shell">
                        <option value="">All Sections</option>
                        @foreach ($gallerySections as $value => $label)
                            <option value="{{ $value }}" @selected(request('section') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button class="luxury-btn-primary w-full">Filter</button>
                </div>
            </form>

            @if ($media->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                    </div>
                    <h3 class="font-display text-2xl font-semibold">{{ $page['empty_title'] ?? 'No Media Found' }}</h3>
                    <p class="mt-2 text-muted-foreground">{{ $page['empty_description'] ?? 'There are no gallery assets matching your selected filter.' }}</p>
                </div>
            @else
                <div class="space-y-12">
                    @foreach ($gallerySections as $sectionKey => $sectionLabel)
                        @php
                            $sectionMedia = $groupedMedia[$sectionKey] ?? collect();
                        @endphp

                        @continue($sectionMedia->isEmpty())

                        <div class="space-y-6">
                            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                                <div>
                                    <span class="section-label mb-2">{{ $sectionLabel }}</span>
                                    <h2 class="font-display text-3xl font-semibold">
                                        {{ $sectionKey === \App\Models\MediaAsset::SECTION_GENERAL ? 'Gallery' : $sectionLabel.' Gallery' }}
                                    </h2>
                                </div>
                                <p class="max-w-2xl text-sm text-muted-foreground">{{ $sectionDescriptions[$sectionKey] ?? 'Curated project media from the Rockmould portfolio.' }}</p>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                                @foreach ($sectionMedia as $asset)
                                    @php
                                        $isVideoAsset = $asset->media_type === 'video';
                                        $previewPath = $isVideoAsset ? $asset->file_path : ($asset->thumbnail_path ?: $asset->file_path);
                                    @endphp
                                    @if ($isVideoAsset && $asset->file_url)
                                        <a href="{{ $asset->file_url }}" data-video-lightbox-trigger data-video-src="{{ $asset->file_url }}" data-video-title="{{ $asset->title }}" class="gallery-item group reveal border border-border bg-card">
                                    @elseif ($asset->file_url)
                                        <a href="{{ $asset->file_url }}" data-image-lightbox-trigger data-image-src="{{ $asset->file_url }}" data-image-title="{{ $asset->title }}" class="gallery-item group reveal border border-border bg-card">
                                    @else
                                        <a href="#" class="gallery-item group reveal border border-border bg-card">
                                    @endif
                                        <div class="relative aspect-[16/10] w-full bg-muted">
                                            <x-media
                                                :path="$previewPath"
                                                fallback="images/project-1.jpg"
                                                alt="{{ $asset->title }}"
                                                class="aspect-[16/10] w-full object-cover"
                                                :loop="false"
                                            />
                                            <div class="gallery-item-overlay">
                                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-black/55 opacity-0 transition duration-300 group-hover:opacity-100">
                                                    <x-portal-icon :name="$asset->media_type === 'video' ? 'video' : 'eye'" class="h-4 w-4 text-white" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="p-5">
                                            <div class="flex items-center justify-between gap-4">
                                                <h3 class="font-display text-lg font-semibold">{{ $asset->title }}</h3>
                                                <x-status-badge :value="$asset->media_type" />
                                            </div>
                                            @if ($asset->project)
                                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-muted-foreground">
                                                    <svg class="h-3.5 w-3.5 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                                                    {{ $asset->project->title }}
                                                </p>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</x-layouts.public>

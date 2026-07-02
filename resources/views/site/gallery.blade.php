<x-layouts.public :company="$company" title="Gallery | Rockmould Limited">
    <x-hero
        :title="$page['hero_title'] ?? 'Project and Media Gallery'"
        :subtitle="$page['hero_subtitle'] ?? 'Gallery'"
        :description="$page['hero_description'] ?? 'Construction photography, project visuals, and client-facing media updates from across the Rockmould portfolio.'"
        :image="$page['hero_media_path'] ?? 'images/project-3.jpg'"
        :title-size="$page['font_sizes']['hero_title'] ?? null"
        :description-size="$page['font_sizes']['hero_description'] ?? null"
    />

    <section class="section-padding">
        <div class="container-luxury">
            @php
                $sectionDescriptions = [
                    'exterior' => 'Facade views, site progress, landscape moments, and the arrival experience.',
                    'interior' => 'Lobbies, living areas, kitchens, amenities, and interior fit-out visuals.',
                    'general' => 'Complete media gallery for each project, including mixed updates and supporting visuals.',
                    'live-updates' => 'Real-time construction progress, site visits, and development milestones from our active projects.',
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

    @if ($timeline->isNotEmpty())
        <section class="section-padding bg-card">
            <div class="container-luxury">
                <x-section-heading
                    label="Project Journey"
                    title="Live Construction Timeline"
                    description="Follow the progress of our developments from ground-breaking to completion."
                />

                @php
                    $groupedTimeline = $timeline->groupBy(fn ($u) => $u->project?->title ?? 'Project');
                @endphp

                @foreach ($groupedTimeline as $projectTitle => $updates)
                    <div class="mb-20 last:mb-0">
                        <div class="mb-10 flex items-center justify-between gap-4 border-b border-border pb-6">
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                                    <svg class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/></svg>
                                </div>
                                <h3 class="font-display text-2xl font-semibold">{{ $projectTitle }}</h3>
                            </div>
                            @if ($updates->last()->progress)
                                <div class="hidden items-center gap-3 sm:flex">
                                    <div class="h-2 w-32 overflow-hidden rounded-full bg-muted">
                                        <div class="h-full rounded-full bg-primary" style="width: {{ $updates->last()->progress }}%"></div>
                                    </div>
                                    <span class="text-sm font-medium text-primary">{{ $updates->last()->progress }}%</span>
                                </div>
                            @endif
                        </div>

                        <div class="relative ml-5 border-l-2 border-primary/20 pl-8 md:ml-6 md:pl-10">
                            @foreach ($updates as $update)
                                @php
                                    $attachments = is_array($update->attachments) ? $update->attachments : [];
                                    $hasMedia = count($attachments) > 0;
                                @endphp
                                <div class="reveal relative mb-14 last:mb-0">
                                    <div class="absolute -left-[2.85rem] top-0 flex h-6 w-6 items-center justify-center rounded-full border-2 border-primary bg-card md:-left-[3.35rem]">
                                        <div class="h-2.5 w-2.5 rounded-full bg-primary"></div>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-3 text-xs">
                                        @if ($update->update_date)
                                            <span class="rounded-full bg-primary/10 px-3 py-1 font-medium text-primary">{{ $update->update_date->format('F j, Y') }}</span>
                                        @endif
                                        @if ($update->progress)
                                            <span class="text-muted-foreground">{{ $update->progress }}%</span>
                                        @endif
                                    </div>

                                    <h4 class="mt-2 font-display text-lg font-semibold">{{ $update->title }}</h4>

                                    @if ($update->body)
                                        <p class="mt-1 text-sm leading-relaxed text-muted-foreground line-clamp-2">{{ $update->body }}</p>
                                    @endif

                                    @if ($hasMedia)
                                        @if (count($attachments) === 1)
                                            @php
                                                $att = $attachments[0];
                                                $isVid = str_contains((string) $att, '.mp4') || str_contains((string) $att, '.webm') || str_contains((string) $att, '.mov');
                                            @endphp
                                            <div class="mt-4 max-w-2xl overflow-hidden rounded-xl border border-border">
                                                @if ($isVid)
                                                    <video controls class="aspect-video w-full object-cover"><source src="{{ asset($att) }}"></video>
                                                @else
                                                    <x-media :path="$att" alt="{{ $update->title }}" class="aspect-[16/9] w-full object-cover" />
                                                @endif
                                            </div>
                                        @elseif (count($attachments) === 2)
                                            <div class="mt-4 grid grid-cols-2 gap-3">
                                                @foreach ($attachments as $att)
                                                    @php
                                                        $attStr = (string) $att;
                                                        $isVid = str_contains($attStr, '.mp4') || str_contains($attStr, '.webm') || str_contains($attStr, '.mov');
                                                    @endphp
                                                    <div class="overflow-hidden rounded-xl border border-border">
                                                        @if ($isVid)
                                                            <video controls class="aspect-[4/3] w-full object-cover"><source src="{{ asset($att) }}"></video>
                                                        @else
                                                            <x-media :path="$att" alt="{{ $update->title }}" class="aspect-[4/3] w-full object-cover" />
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            @php
                                                $firstAtt = $attachments[0];
                                                $isFirstVid = str_contains((string) $firstAtt, '.mp4') || str_contains((string) $firstAtt, '.webm') || str_contains((string) $firstAtt, '.mov');
                                                $remainingAtts = array_slice($attachments, 1);
                                            @endphp
                                            <div class="mt-4 grid gap-3 md:grid-cols-[1.4fr_0.6fr]">
                                                <div class="overflow-hidden rounded-xl border border-border">
                                                    @if ($isFirstVid)
                                                        <video controls class="aspect-[16/10] w-full object-cover"><source src="{{ asset($firstAtt) }}"></video>
                                                    @else
                                                        <x-media :path="$firstAtt" alt="{{ $update->title }}" class="aspect-[16/10] w-full object-cover" />
                                                    @endif
                                                </div>
                                                <div class="grid gap-3" style="grid-template-rows: repeat({{ min(count($remainingAtts), 3) }}, 1fr);">
                                                    @foreach (array_slice($remainingAtts, 0, 3) as $idx => $att)
                                                        @php
                                                            $attStr = (string) $att;
                                                            $isVid = str_contains($attStr, '.mp4') || str_contains($attStr, '.webm') || str_contains($attStr, '.mov');
                                                        @endphp
                                                        <div class="relative overflow-hidden rounded-xl border border-border">
                                                            @if ($isVid)
                                                                <video controls class="h-full w-full object-cover"><source src="{{ asset($att) }}"></video>
                                                            @else
                                                                <x-media :path="$att" alt="{{ $update->title }}" class="h-full w-full object-cover" />
                                                            @endif
                                                            @if ($idx === 2 && count($remainingAtts) > 3)
                                                                <div class="absolute inset-0 flex items-center justify-center bg-black/50 text-lg font-bold text-white">+{{ count($remainingAtts) - 3 }}</div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</x-layouts.public>

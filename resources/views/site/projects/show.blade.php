<x-layouts.public :company="$company" :title="$project->title.' | Rockmould Limited'">
    @php
        $sectionDescriptions = [
            'exterior' => 'Site photography, building facades, landscape progress, and arrival moments.',
            'interior' => 'Lobbies, living spaces, amenities, and fit-out details from within the project.',
            'general' => 'Complete media gallery for this project, including mixed updates and supporting visuals.',
            'floor-plan' => 'Detailed floor plans and layout drawings for this project.',
        ];
        $projectTimeline = $project->timelineMilestones();
    @endphp

    <section class="hero-shell relative flex min-h-[60vh] items-end overflow-hidden pt-20">
        <x-media :path="$project->cover_image" fallback="images/project-1.jpg" alt="{{ $project->title }}" class="absolute inset-0 h-full w-full object-cover" :background="true" :lightbox="false" />
        <div class="absolute inset-0 bg-gradient-to-t from-background via-background/40 to-transparent"></div>
        <div class="container-luxury relative z-10 pb-12">
            <a href="{{ route('projects.index') }}" class="mb-6 inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Projects
            </a>
            <div class="mb-3 flex flex-wrap items-center gap-3">
                <x-status-badge :value="$project->status" />
            </div>
            <h1 class="font-display text-4xl font-bold md:text-5xl lg:text-6xl">{{ $project->title }}</h1>
            <div class="mt-4 flex items-center gap-2 text-muted-foreground">
                <svg class="h-4 w-4 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                {{ $project->location }}
            </div>
        </div>
    </section>

    @php
        $hasLiveUpdates = $project->updates->where('visible_to_client', true)->isNotEmpty();
    @endphp

    <section class="section-padding">
        <div class="container-luxury">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
                <div class="space-y-14 lg:col-span-2">
                    <div>
                        <h2 class="mb-4 font-display text-2xl font-semibold">Project Overview</h2>
                        <p class="leading-relaxed text-muted-foreground">{{ $project->description ?: $project->overview }}</p>
                    </div>

                    @if ($project->residence_profile)
                        <div>
                            <h2 class="mb-4 font-display text-2xl font-semibold">Residence Profile</h2>
                            <p class="leading-relaxed text-muted-foreground">{{ $project->residence_profile }}</p>
                        </div>
                    @endif

                    @if (!empty($project->features))
                        <div>
                            <h2 class="mb-6 font-display text-2xl font-semibold">Features and Amenities</h2>
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                @foreach ($project->features as $feature)
                                    <div class="check-item">
                                        <svg class="check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        <span class="text-sm text-muted-foreground">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @php
                        if ($project->status === 'completed') {
                            $computedProgress = 100;
                        } elseif ($project->launch_date && $project->completion_date) {
                            $start = $project->launch_date->timestamp;
                            $end   = $project->completion_date->timestamp;
                            $now   = now()->timestamp;
                            $computedProgress = $end > $start
                                ? (int) min(99, max(0, round(($now - $start) / ($end - $start) * 100)))
                                : $project->progress;
                        } else {
                            $computedProgress = $project->progress;
                        }
                    @endphp
                    <div>
                        <h2 class="mb-6 font-display text-2xl font-semibold">Construction Progress</h2>
                        <div class="mb-6">
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="text-muted-foreground">Overall Completion</span>
                                <span class="font-semibold text-primary">{{ $computedProgress }}%</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-muted">
                                <div class="h-full rounded-full bg-primary transition-all duration-1000" style="width: {{ $computedProgress }}%"></div>
                            </div>
                        </div>
                        <div class="space-y-0">
                            @forelse ($projectTimeline as $milestone)
                                <div class="flex gap-4 pb-6">
                                    <div class="flex flex-col items-center">
                                        <div class="h-3 w-3 rounded-full border-2 {{ $milestone->is_completed ? 'border-primary bg-primary' : 'border-border bg-background' }}"></div>
                                        @if (! $loop->last)
                                            <div class="w-px flex-1 bg-border"></div>
                                        @endif
                                    </div>
                                    <div class="-mt-0.5">
                                        <h4 class="text-sm font-medium {{ $milestone->is_completed ? 'text-foreground' : 'text-muted-foreground' }}">{{ $milestone->title }}</h4>
                                        <span class="text-xs text-muted-foreground">{{ optional($milestone->milestone_date)->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-muted-foreground">Project timeline dates have not been published yet.</p>
                            @endforelse
                        </div>
                    </div>

                    @php
                        $mediaTabs = [];
                        if (($publicMedia ?? collect())->isNotEmpty()) {
                            foreach ($gallerySections as $sectionKey => $sectionLabel) {
                                $sm = $publicMediaSections[$sectionKey] ?? collect();
                                if ($sm->isNotEmpty()) $mediaTabs[$sectionKey] = ['label' => $sectionLabel, 'media' => $sm];
                            }
                        }
                        $hasLegacyGallery = empty($mediaTabs) && !empty($project->gallery);
                    @endphp

                    @if (!empty($mediaTabs) || $hasLegacyGallery || $hasLiveUpdates)
                        <div>
                            <h2 class="mb-5 font-display text-2xl font-semibold">Project Media</h2>

                            {{-- Tab pills --}}
                            <div class="mb-6 flex flex-wrap items-center gap-2">
                                @foreach ($mediaTabs as $key => $tab)
                                    <button
                                        type="button"
                                        data-media-tab="{{ $key }}"
                                        class="media-tab-pill rounded-full border border-border px-4 py-1.5 text-xs font-medium uppercase tracking-[0.16em] text-muted-foreground transition-all duration-200 hover:border-primary/40 hover:text-primary"
                                    >{{ $tab['label'] }}</button>
                                @endforeach
                                @if ($hasLegacyGallery)
                                    <button type="button" data-media-tab="legacy" class="media-tab-pill rounded-full border border-border px-4 py-1.5 text-xs font-medium uppercase tracking-[0.16em] text-muted-foreground transition-all duration-200 hover:border-primary/40 hover:text-primary">Gallery</button>
                                @endif
                                @if ($hasLiveUpdates)
                                    <button type="button" data-media-tab="live-updates" class="media-tab-pill rounded-full border border-border px-4 py-1.5 text-xs font-medium uppercase tracking-[0.16em] text-muted-foreground transition-all duration-200 hover:border-primary/40 hover:text-primary">
                                        Live Updates
                                    </button>
                                @endif
                            </div>

                            {{-- Section panels (hidden until tab clicked) --}}
                            @foreach ($mediaTabs as $key => $tab)
                                <div data-media-panel="{{ $key }}" class="hidden">
                                    <p class="mb-4 text-sm text-muted-foreground">{{ $sectionDescriptions[$key] ?? '' }}</p>
                                    <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                                        @foreach ($tab['media'] as $asset)
                                            @php
                                                $isVideoAsset = $asset->media_type === 'video';
                                                $previewPath = $isVideoAsset ? $asset->file_path : ($asset->thumbnail_path ?: $asset->file_path);
                                            @endphp
                                            @if ($isVideoAsset && $asset->file_url)
                                                <a href="{{ $asset->file_url }}" data-video-lightbox-trigger data-video-src="{{ $asset->file_url }}" data-video-title="{{ $asset->title }}" aria-label="{{ $asset->title }}" class="group gallery-item aspect-square border border-border bg-card">
                                            @elseif ($asset->file_url)
                                                <a href="{{ $asset->file_url }}" data-image-lightbox-trigger data-image-src="{{ $asset->file_url }}" data-image-title="{{ $asset->title }}" aria-label="{{ $asset->title }}" class="group gallery-item aspect-square border border-border bg-card">
                                            @else
                                                <a href="#" aria-label="{{ $asset->title }}" class="group gallery-item aspect-square border border-border bg-card">
                                            @endif
                                                @php $assetAlt = (strlen($asset->title ?? '') > 4) ? $asset->title : $project->title.' – '.($tab['label'] ?? ucfirst($key)).' photo'; @endphp
                                <x-media :path="$previewPath" fallback="images/project-1.jpg" alt="{{ $assetAlt }}" class="h-full w-full object-cover" :loop="false" />
                                                <div class="gallery-item-overlay">
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-black/55 opacity-0 transition duration-300 group-hover:opacity-100">
                                                        <x-portal-icon :name="$asset->media_type === 'video' ? 'video' : 'eye'" class="h-4 w-4 text-white" />
                                                    </div>
                                                </div>
                                                <span class="sr-only">{{ $asset->title }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach

                            @if ($hasLegacyGallery)
                                <div data-media-panel="legacy" class="hidden">
                                    <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                                        @foreach ($project->gallery as $image)
                                            <div class="gallery-item aspect-square">
                                                <x-media :path="$image" fallback="images/project-1.jpg" alt="{{ $project->title }}" class="h-full w-full object-cover" />
                                                <div class="gallery-item-overlay"></div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($hasLiveUpdates)
                                @php
                                    $liveUpdates = $project->updates->where('visible_to_client', true)->sortBy('update_date');
                                    $updatesLocked = $project->live_updates_locked ?? true;
                                    $isClient = auth()->check() && auth()->user()?->role === 'client';
                                @endphp
                                <div data-media-panel="live-updates" class="hidden">
                                    @if ($updatesLocked && ! $isClient)
                                        {{-- Locked state for public visitors --}}
                                        <div class="relative overflow-hidden rounded-sm">
                                            {{-- Blurred preview of first update --}}
                                            @if ($liveUpdates->isNotEmpty())
                                                @php $preview = $liveUpdates->last(); @endphp
                                                <div style="filter:blur(6px);pointer-events:none;user-select:none;opacity:0.4;" aria-hidden="true">
                                                    <div style="position:relative;display:flex;gap:1rem;align-items:flex-start;">
                                                        <div style="flex-shrink:0;width:2.5rem;height:2.5rem;display:flex;align-items:center;justify-content:center;border-radius:9999px;background:hsl(var(--card));border:2px solid hsl(var(--background));">
                                                            <div style="width:10px;height:10px;border-radius:9999px;border:1.5px solid hsl(var(--primary));"></div>
                                                        </div>
                                                        <div class="flex-1 rounded-sm border border-border bg-card p-5">
                                                            <p class="text-[10px] uppercase tracking-[0.18em] text-primary">{{ $preview->update_date?->format('F j, Y') }}</p>
                                                            <h3 class="mt-1 font-display text-lg font-semibold">{{ $preview->title }}</h3>
                                                            <p class="mt-2 text-sm text-muted-foreground">{{ Str::limit($preview->body, 120) }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            {{-- Lock overlay --}}
                                            <div class="absolute inset-0 flex flex-col items-center justify-center gap-4 rounded-sm bg-background/80 py-10 text-center backdrop-blur-sm">
                                                <svg class="h-8 w-8 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                                <div>
                                                    <p class="font-display text-base font-semibold">Live Updates — Clients Only</p>
                                                    <p class="mt-1 text-sm text-muted-foreground">These updates are reserved for clients of this project.</p>
                                                </div>
                                                @if (auth()->check())
                                                    <a href="{{ route('portal.updates') }}" class="luxury-btn-primary inline-flex">View in Client Portal</a>
                                                @else
                                                    <a href="{{ route('portal.login') }}" class="luxury-btn-primary inline-flex">Log In to View</a>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        {{-- Full content: client is logged in, or updates are unlocked --}}
                                        <div style="position:relative;">
                                            <div style="position:absolute;top:0;bottom:0;left:20px;width:1px;background:hsl(var(--border));"></div>
                                            <div class="space-y-6">
                                            @foreach ($liveUpdates as $liveUpdate)
                                                @php
                                                    $luAttachments = is_array($liveUpdate->attachments) ? $liveUpdate->attachments : [];
                                                    $luProgress = (int) ($liveUpdate->progress_percentage ?? $liveUpdate->progress ?? 0);
                                                @endphp
                                                <div style="position:relative;display:flex;gap:1rem;align-items:flex-start;">
                                                    <div style="position:relative;z-index:10;flex-shrink:0;width:2.5rem;height:2.5rem;display:flex;align-items:center;justify-content:center;border-radius:9999px;background:hsl(var(--card));border:2px solid hsl(var(--background));">
                                                        <div style="width:10px;height:10px;border-radius:9999px;border:1.5px solid hsl(var(--primary));"></div>
                                                    </div>
                                                    <div class="flex-1 rounded-sm border border-border bg-card p-5" style="min-width:0;">
                                                        <div class="flex items-start justify-between gap-4">
                                                            <div>
                                                                <p class="text-[10px] uppercase tracking-[0.18em] text-primary">{{ $liveUpdate->update_date?->format('F j, Y') }}</p>
                                                                <h3 class="mt-1 font-display text-lg font-semibold">{{ $liveUpdate->title }}</h3>
                                                            </div>
                                                            @if ($luProgress)
                                                                <span class="shrink-0 text-xs text-muted-foreground">{{ $luProgress }}%</span>
                                                            @endif
                                                        </div>
                                                        <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $liveUpdate->body }}</p>
                                                        @if ($luProgress)
                                                            <div class="mt-3 h-1 w-full rounded-full bg-muted">
                                                                <div class="h-full rounded-full bg-primary" style="width:{{ $luProgress }}%"></div>
                                                            </div>
                                                        @endif
                                                        @if (count($luAttachments) > 0)
                                                            <div class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-3">
                                                                @foreach ($luAttachments as $att)
                                                                    @php
                                                                        $attUrl = str_starts_with($att, 'http') ? $att : asset('storage/'.$att);
                                                                        $attExt = strtolower(pathinfo(strtok($att,'?'), PATHINFO_EXTENSION));
                                                                        $attIsVid = in_array($attExt, ['mp4','webm','mov','ogg','m4v']);
                                                                    @endphp
                                                                    @if ($attIsVid)
                                                                        <video src="{{ $attUrl }}" controls playsinline class="w-full rounded-md aspect-video bg-muted"></video>
                                                                    @else
                                                                        <img src="{{ $attUrl }}" alt="Update photo" class="w-full cursor-zoom-in rounded-md aspect-square object-cover hover:opacity-90 transition-opacity" onclick="openPublicLightbox(this.src)">
                                                                    @endif
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                            </div>
                                        </div>
                                        @if (! $isClient)
                                            <div class="mt-6 rounded-sm border border-primary/20 bg-primary/5 p-4 text-center">
                                                <p class="text-sm text-muted-foreground">Log in to your client portal to see all updates and receive notifications.</p>
                                                <a href="{{ route('portal.updates') }}" class="luxury-btn-primary mt-3 inline-flex">View in Portal</a>
                                            </div>
                                        @endif
                                    @endif
                                </div>

                                <script>
                                    (function () {
                                        var lb = document.createElement('div');
                                        lb.id = 'public-lightbox';
                                        lb.style.cssText = 'display:none;position:fixed;top:0;left:0;width:100%;height:100%;z-index:99999;background:rgba(0,0,0,0.92);cursor:pointer;';
                                        lb.innerHTML = '<button style="position:absolute;top:1rem;right:1rem;background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.8);font-size:2rem;line-height:1;z-index:100001;" id="public-lb-close">&times;</button><img id="public-lightbox-img" src="" alt="" style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);max-height:90vh;max-width:90vw;object-fit:contain;border-radius:4px;box-shadow:0 25px 50px rgba(0,0,0,0.6);cursor:default;">';
                                        document.body.appendChild(lb);
                                        lb.addEventListener('click', function () { closePublicLightbox(); });
                                        document.getElementById('public-lightbox-img').addEventListener('click', function (e) { e.stopPropagation(); });
                                        document.getElementById('public-lb-close').addEventListener('click', function (e) { e.stopPropagation(); closePublicLightbox(); });
                                    })();
                                    function openPublicLightbox(src) {
                                        var lb = document.getElementById('public-lightbox');
                                        document.getElementById('public-lightbox-img').src = src;
                                        lb.style.display = 'block';
                                        document.body.style.overflow = 'hidden';
                                    }
                                    function closePublicLightbox() {
                                        document.getElementById('public-lightbox').style.display = 'none';
                                        document.body.style.overflow = '';
                                    }
                                    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closePublicLightbox(); });
                                </script>
                            @endif

                            <script>
                            (function () {
                                const tabs = document.querySelectorAll('[data-media-tab]');
                                const panels = document.querySelectorAll('[data-media-panel]');

                                const activate = (key) => {
                                    tabs.forEach(t => {
                                        const active = t.dataset.mediaTab === key;
                                        t.classList.toggle('border-primary', active);
                                        t.classList.toggle('bg-primary/10', active);
                                        t.classList.toggle('text-primary', active);
                                        t.classList.toggle('border-border', !active);
                                        t.classList.toggle('text-muted-foreground', !active);
                                    });
                                    panels.forEach(p => {
                                        p.classList.toggle('hidden', p.dataset.mediaPanel !== key);
                                    });
                                };

                                tabs.forEach(tab => {
                                    tab.addEventListener('click', () => activate(tab.dataset.mediaTab));
                                });

                                // Auto-open first tab on load
                                const firstTab = Array.from(tabs).find(t => t.dataset.mediaTab);
                                if (firstTab) activate(firstTab.dataset.mediaTab);
                            })();
                            </script>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="luxury-card sticky top-24">
                        <h3 class="mb-5 font-display text-lg font-semibold">Project Summary</h3>
                        <div class="mb-6 space-y-3 text-sm">
                            <div class="flex justify-between gap-2">
                                <span class="text-muted-foreground">Location</span>
                                <span class="text-right font-medium">{{ $project->location }}</span>
                            </div>
                            <div class="luxury-divider"></div>
                            <div class="flex justify-between gap-2">
                                <span class="text-muted-foreground">Status</span>
                                <span class="font-medium text-primary">{{ str($project->status)->title() }}</span>
                            </div>
                            <div class="luxury-divider"></div>
                            <div class="flex justify-between gap-2">
                                <span class="text-muted-foreground">Progress</span>
                                <span class="font-medium">{{ $computedProgress }}%</span>
                            </div>
                            @if ($project->total_units)
                                <div class="luxury-divider"></div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-muted-foreground">Total Units</span>
                                    <span class="font-medium">{{ $project->total_units }}</span>
                                </div>
                            @endif
                            @if ($project->available_units !== null && $project->total_units)
                                <div class="luxury-divider"></div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-muted-foreground">Available Units</span>
                                    <span class="font-medium text-primary">{{ $project->available_units }}</span>
                                </div>
                            @endif
                            @if ($project->built_area)
                                <div class="luxury-divider"></div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-muted-foreground">Built Area</span>
                                    <span class="text-right font-medium">{{ $project->built_area }}</span>
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('contact') }}" class="luxury-btn-primary w-full justify-center text-xs">
                            Inquire Now
                            <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <a href="https://wa.me/message/UMPBJLP3KWF5L1" target="_blank" rel="noopener noreferrer" class="mt-2 flex w-full items-center justify-center gap-2 rounded-md border border-primary/40 bg-primary/10 px-4 py-2 text-xs font-medium text-primary transition hover:border-primary hover:bg-primary/20">
                            <svg class="h-3.5 w-3.5 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.122 1.532 5.855L.058 23.447a.5.5 0 00.613.605l5.748-1.505A11.944 11.944 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.9a9.893 9.893 0 01-5.031-1.374l-.36-.214-3.733.977.998-3.645-.235-.374A9.862 9.862 0 012.1 12C2.1 6.533 6.533 2.1 12 2.1S21.9 6.533 21.9 12 17.467 21.9 12 21.9z"/></svg>
                            WhatsApp Us
                        </a>
                        <div class="mt-3 flex gap-3">
                            @if ($project->brochure_path)
                                <a href="{{ asset('storage/'.$project->brochure_path) }}" target="_blank" download class="luxury-btn-outline flex-1 justify-center py-2 text-xs">
                                    <svg class="mr-1.5 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                    Brochure
                                </a>
                            @endif
                            <button type="button" onclick="shareProject()" class="luxury-btn-outline flex-1 justify-center py-2 text-xs" id="share-btn">
                                <svg class="mr-1.5 h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                                Share
                            </button>
                            <script>
                            function shareProject() {
                                const url  = '{{ route('projects.show', $project->slug) }}';
                                const title = '{{ addslashes($project->title) }} — Rockmould Limited';
                                const text  = '{{ addslashes($project->location) }}';
                                if (navigator.share) {
                                    navigator.share({ title, text, url }).catch(() => {});
                                } else {
                                    navigator.clipboard.writeText(url).then(function() {
                                        const btn = document.getElementById('share-btn');
                                        const orig = btn.innerHTML;
                                        btn.textContent = 'Link Copied!';
                                        setTimeout(() => { btn.innerHTML = orig; }, 2000);
                                    });
                                }
                            }
                            </script>
                        </div>
                    </div>

                    <div class="luxury-card">
                        <h3 class="mb-5 font-display text-lg font-semibold">Send an Inquiry</h3>
                        <form action="{{ route('forms.project-inquiry', $project->slug) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="admin-label">Full Name</label>
                                <input name="full_name" required class="input-shell" value="{{ old('full_name') }}">
                            </div>
                            <div>
                                <label class="admin-label">Email Address</label>
                                <input type="email" name="email" required class="input-shell" value="{{ old('email') }}">
                            </div>
                            <div>
                                <label class="admin-label">Phone Number</label>
                                <input name="phone" class="input-shell" value="{{ old('phone') }}">
                            </div>
                            <div>
                                <label class="admin-label">Message</label>
                                <textarea name="message" rows="4" required class="input-shell">{{ old('message') }}</textarea>
                            </div>
                            <button class="luxury-btn-primary w-full">Send Inquiry</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @if ($relatedProjects->isNotEmpty())
        <section class="section-padding bg-card">
            <div class="container-luxury">
                <h2 class="mb-8 font-display text-2xl font-semibold">Related Projects</h2>
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    @foreach ($relatedProjects as $relatedProject)
                        <x-project-card :project="$relatedProject" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</x-layouts.public>

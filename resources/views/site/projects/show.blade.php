<x-layouts.public :company="$company" :title="$project->title.' | Rockmould Limited'">
    @php
        $sectionDescriptions = [
            'exterior' => 'Site photography, building facades, landscape progress, and arrival moments.',
            'interior' => 'Lobbies, living spaces, amenities, and fit-out details from within the project.',
            'general' => 'Complete media gallery for this project, including mixed updates and supporting visuals.',
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
                                    <a href="{{ route('portal.login') }}" class="inline-flex items-center gap-1.5 rounded-full border border-primary/30 px-4 py-1.5 text-xs font-medium uppercase tracking-[0.16em] text-primary transition-all duration-200 hover:border-primary hover:bg-primary/10">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                        Live Updates
                                    </a>
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
                                                <x-media :path="$previewPath" fallback="images/project-1.jpg" alt="{{ $asset->title }}" class="h-full w-full object-cover" :loop="false" />
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

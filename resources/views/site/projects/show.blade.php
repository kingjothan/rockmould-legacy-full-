<x-layouts.public :company="$company" :title="$project->title.' | Rockmould Limited'">
    @php
        $sectionDescriptions = [
            'exterior' => 'Site photography, building facades, landscape progress, and arrival moments.',
            'interior' => 'Lobbies, living spaces, amenities, and fit-out details from within the project.',
            'general' => 'Complete media gallery for this project, including mixed updates and supporting visuals.',
        ];
    @endphp

    <section class="relative flex min-h-[60vh] items-end overflow-hidden pt-20">
        <x-media :path="$project->cover_image" fallback="images/project-1.jpg" alt="{{ $project->title }}" class="absolute inset-0 h-full w-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-background via-background/40 to-transparent"></div>
        <div class="container-luxury relative z-10 pb-12">
            <a href="{{ route('projects.index') }}" class="mb-6 inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-primary">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Projects
            </a>
            <div class="mb-3 flex flex-wrap items-center gap-3">
                <span class="section-label mb-0">{{ $project->category }}</span>
                <x-status-badge :value="$project->status" />
            </div>
            <h1 class="font-display text-4xl font-bold md:text-5xl lg:text-6xl">{{ $project->title }}</h1>
            <div class="mt-4 flex items-center gap-2 text-muted-foreground">
                <svg class="h-4 w-4 text-primary/60" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                {{ $project->location }}
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container-luxury">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
                <div class="space-y-14 lg:col-span-2">
                    <div>
                        <h2 class="mb-4 font-display text-2xl font-semibold">Project Overview</h2>
                        <p class="leading-relaxed text-muted-foreground">{{ $project->description ?: $project->overview }}</p>
                    </div>

                    @if (!empty($project->highlights))
                        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                            @foreach ($project->highlights as $highlight)
                                <div class="luxury-card text-center">
                                    <p class="font-display text-2xl font-bold text-primary">{{ $highlight['value'] }}</p>
                                    <p class="mt-1 text-xs uppercase tracking-wider text-muted-foreground">{{ $highlight['label'] }}</p>
                                </div>
                            @endforeach
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

                    <div>
                        <h2 class="mb-6 font-display text-2xl font-semibold">Construction Progress</h2>
                        <div class="mb-6">
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="text-muted-foreground">Overall Completion</span>
                                <span class="font-semibold text-primary">{{ $project->progress }}%</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-muted">
                                <div class="h-full rounded-full bg-primary transition-all duration-1000" style="width: {{ $project->progress }}%"></div>
                            </div>
                        </div>
                        <div class="space-y-0">
                            @foreach ($project->milestones as $milestone)
                                <div class="flex gap-4 pb-6">
                                    <div class="flex flex-col items-center">
                                        <div class="h-3 w-3 rounded-full border-2 {{ $milestone->is_completed ? 'border-primary bg-primary' : 'border-border bg-background' }}"></div>
                                        @if (!$loop->last)
                                            <div class="w-px flex-1 bg-border"></div>
                                        @endif
                                    </div>
                                    <div class="-mt-0.5">
                                        <h4 class="text-sm font-medium {{ $milestone->is_completed ? 'text-foreground' : 'text-muted-foreground' }}">{{ $milestone->title }}</h4>
                                        <span class="text-xs text-muted-foreground">{{ optional($milestone->milestone_date)->format('M d, Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if (($publicMedia ?? collect())->isNotEmpty())
                        <div>
                            <h2 class="mb-6 font-display text-2xl font-semibold">Project Media</h2>
                            <div class="space-y-8">
                                @foreach ($gallerySections as $sectionKey => $sectionLabel)
                                    @php
                                        $sectionMedia = $publicMediaSections[$sectionKey] ?? collect();
                                    @endphp

                                    @continue($sectionMedia->isEmpty())

                                    <div class="space-y-4">
                                        <div class="flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
                                            <div>
                                                <span class="section-label mb-2">{{ $sectionLabel }}</span>
                                                <h3 class="font-display text-xl font-semibold">
                                                    {{ $sectionKey === \App\Models\MediaAsset::SECTION_GENERAL ? 'Gallery' : $sectionLabel.' Gallery' }}
                                                </h3>
                                            </div>
                                            <p class="max-w-2xl text-sm text-muted-foreground">{{ $sectionDescriptions[$sectionKey] ?? 'Curated visuals from the project.' }}</p>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3 md:grid-cols-4">
                                            @foreach ($sectionMedia as $asset)
                                                @php
                                                    $isVideoAsset = $asset->media_type === 'video';
                                                    $previewPath = $isVideoAsset ? $asset->file_path : ($asset->thumbnail_path ?: $asset->file_path);
                                                @endphp
                                                @if ($isVideoAsset && $asset->file_url)
                                                    <a href="{{ $asset->file_url }}" data-video-lightbox-trigger data-video-src="{{ $asset->file_url }}" data-video-title="{{ $asset->title }}" aria-label="{{ $asset->title }}" class="group gallery-item aspect-square border border-border bg-card">
                                                @else
                                                    <a href="{{ $asset->file_url ?: '#' }}" target="{{ $asset->file_url ? '_blank' : '_self' }}" aria-label="{{ $asset->title }}" class="group gallery-item aspect-square border border-border bg-card">
                                                @endif
                                                    <x-media
                                                        :path="$previewPath"
                                                        fallback="images/project-1.jpg"
                                                        alt="{{ $asset->title }}"
                                                        class="h-full w-full object-cover"
                                                        :loop="false"
                                                    />
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
                            </div>
                        </div>
                    @elseif (!empty($project->gallery))
                        <div>
                            <h2 class="mb-6 font-display text-2xl font-semibold">Gallery</h2>
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
                                <span class="text-muted-foreground">Category</span>
                                <span class="font-medium">{{ $project->category }}</span>
                            </div>
                            <div class="luxury-divider"></div>
                            <div class="flex justify-between gap-2">
                                <span class="text-muted-foreground">Status</span>
                                <span class="font-medium text-primary">{{ str($project->status)->title() }}</span>
                            </div>
                            <div class="luxury-divider"></div>
                            <div class="flex justify-between gap-2">
                                <span class="text-muted-foreground">Progress</span>
                                <span class="font-medium">{{ $project->progress }}%</span>
                            </div>
                        </div>
                        <a href="{{ route('contact') }}" class="luxury-btn-primary w-full justify-center text-xs">
                            Inquire Now
                            <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                        <div class="mt-3 flex gap-3">
                            <a href="{{ route('contact') }}" class="luxury-btn-outline flex-1 justify-center py-2 text-xs">Brochure</a>
                            <a href="{{ route('contact') }}" class="luxury-btn-outline flex-1 justify-center py-2 text-xs">Share</a>
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

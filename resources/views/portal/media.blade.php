@php
    $currentType = request('type', 'all');
    $currentProject = request('project', 'all');
    $mediaCounts = [
        'total' => $media->count(),
        'images' => $media->where('media_type', 'image')->count(),
        'videos' => $media->where('media_type', 'video')->count(),
    ];
@endphp

<x-layouts.portal :company="$company" :page-heading="$content['title'] ?? 'Media Gallery'">
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.24em] text-primary/70">{{ $content['kicker'] ?? 'Client Portal Media' }}</p>
                <h1 class="mt-2 font-display text-2xl font-semibold md:text-3xl">{{ $content['title'] ?? 'Project gallery and progress visuals' }}</h1>
                <p class="mt-2 max-w-2xl text-sm text-muted-foreground">{{ $content['description'] ?? 'Browse client-visible photos and videos for each assigned development, including shared project updates and assignment-specific media.' }}</p>
            </div>
            <div class="grid grid-cols-3 gap-3 sm:min-w-[320px]">
                <div class="rounded-sm border border-border bg-card px-4 py-3">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">{{ $content['assets_label'] ?? 'Assets' }}</p>
                    <p class="mt-2 font-display text-2xl">{{ $mediaCounts['total'] }}</p>
                </div>
                <div class="rounded-sm border border-border bg-card px-4 py-3">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">{{ $content['photos_label'] ?? 'Photos' }}</p>
                    <p class="mt-2 font-display text-2xl">{{ $mediaCounts['images'] }}</p>
                </div>
                <div class="rounded-sm border border-border bg-card px-4 py-3">
                    <p class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">{{ $content['videos_label'] ?? 'Videos' }}</p>
                    <p class="mt-2 font-display text-2xl">{{ $mediaCounts['videos'] }}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-wrap gap-2">
                @foreach ([
                    ['label' => 'All', 'value' => 'all'],
                    ['label' => 'Photos', 'value' => 'image'],
                    ['label' => 'Videos', 'value' => 'video'],
                ] as $filter)
                    @php
                        $query = array_filter([
                            'type' => $filter['value'] !== 'all' ? $filter['value'] : null,
                            'project' => $currentProject !== 'all' ? $currentProject : null,
                        ]);
                    @endphp
                    <a
                        href="{{ route('portal.media', $query) }}"
                        class="rounded-full border px-4 py-2 text-xs uppercase tracking-[0.18em] transition-colors {{ $currentType === $filter['value'] ? 'border-primary bg-primary/5 text-primary' : 'border-border text-muted-foreground hover:text-foreground' }}"
                    >
                        {{ $filter['label'] }}
                    </a>
                @endforeach
            </div>

            <form method="GET" class="flex w-full gap-3 lg:w-auto">
                @if ($currentType !== 'all')
                    <input type="hidden" name="type" value="{{ $currentType }}">
                @endif
                <select name="project" class="input-shell py-2 text-xs sm:min-w-64" onchange="this.form.submit()">
                    <option value="all">All Projects</option>
                    @foreach ($projects as $project)
                        <option value="{{ $project->id }}" @selected((string) $currentProject === (string) $project->id)>{{ $project->title }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if ($media->isEmpty())
            <div class="luxury-card py-16 text-center">
                <x-portal-icon name="image" class="mx-auto mb-4 h-12 w-12 text-muted-foreground" />
                <h3 class="mb-2 font-display text-lg font-semibold">{{ $content['empty_title'] ?? 'No Media Found' }}</h3>
                <p class="text-sm text-muted-foreground">{{ $content['empty_description'] ?? 'No photos or videos match your current filters.' }}</p>
            </div>
        @else
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($media as $asset)
                    @php
                        $previewQuery = array_filter([
                            'type' => $currentType !== 'all' ? $currentType : null,
                            'project' => $currentProject !== 'all' ? $currentProject : null,
                            'preview' => $asset->id,
                        ]);
                    @endphp
                    <a
                        href="{{ route('portal.media', $previewQuery) }}"
                        class="group overflow-hidden rounded-sm border border-border/50 transition-colors hover:border-primary/30"
                    >
                        <div class="relative aspect-square bg-muted">
                            <x-media
                                :path="$asset->thumbnail_path ?: $asset->file_path"
                                fallback="images/project-1.jpg"
                                alt="{{ $asset->title }}"
                                class="h-full w-full object-cover"
                                :autoplay="false"
                                :loop="false"
                            />
                            <div class="absolute inset-0 flex items-center justify-center bg-black/50 opacity-0 transition-opacity group-hover:opacity-100">
                                <x-portal-icon name="eye" class="h-6 w-6 text-white" />
                            </div>
                        </div>
                        <div class="p-3">
                            <p class="truncate text-xs font-medium">{{ $asset->title }}</p>
                            <p class="text-[10px] text-muted-foreground">
                                {{ $asset->project?->title ?: 'Project media' }}
                                &middot;
                                {{ $asset->project_assignment_id ? 'Private assignment update' : 'Shared project gallery' }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        @if ($activeMedia)
            @php
                $closeQuery = array_filter([
                    'type' => $currentType !== 'all' ? $currentType : null,
                    'project' => $currentProject !== 'all' ? $currentProject : null,
                ]);
                $assetUrl = $activeMedia->file_url;
            @endphp
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 p-4">
                <div class="w-full max-w-3xl rounded-sm border border-border bg-card p-6">
                    <div class="mb-4 flex items-center justify-between">
                        <p class="text-sm font-medium">{{ $activeMedia->title }}</p>
                        <a href="{{ route('portal.media', $closeQuery) }}" class="rounded-sm p-1 hover:bg-muted">
                            <x-portal-icon name="x" class="h-5 w-5" />
                        </a>
                    </div>

                    <div class="mb-4 aspect-video overflow-hidden rounded-sm bg-muted">
                        @if ($assetUrl && $activeMedia->media_type === 'video')
                            <x-media :path="$activeMedia->file_path" alt="{{ $activeMedia->title }}" class="h-full w-full object-cover" :controls="true" :autoplay="false" :loop="false" />
                        @elseif ($assetUrl)
                            <x-media :path="$activeMedia->file_path" alt="{{ $activeMedia->title }}" class="h-full w-full object-cover" :autoplay="false" :loop="false" />
                        @else
                            <div class="flex h-full items-center justify-center">
                                <x-portal-icon :name="$activeMedia->media_type === 'video' ? 'video' : 'image'" class="h-16 w-16 text-muted-foreground" />
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-muted-foreground">{{ $activeMedia->project?->title ?: 'Project media' }} &middot; {{ $activeMedia->created_at?->format('M j, Y') ?: 'Pending date' }}</p>
                            <p class="mt-1 text-[10px] uppercase tracking-[0.18em] text-primary/70">{{ $activeMedia->project_assignment_id ? 'Private assignment update' : 'Shared project gallery' }}</p>
                        </div>
                        @if ($assetUrl)
                            <a href="{{ route('portal.media.download', $activeMedia) }}" class="luxury-btn-outline text-xs">
                                <x-portal-icon name="download" class="h-3.5 w-3.5" />
                                Download
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.portal>

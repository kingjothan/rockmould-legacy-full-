<x-layouts.admin :company="$company" page-heading="Portfolio CMS">
    @php
        $portfolioMetricsText = collect($portfolio['metrics'] ?? [])
            ->map(fn ($metric) => ($metric['label'] ?? '').'|'.($metric['value'] ?? ''))
            ->implode(PHP_EOL);
    @endphp

    <div class="grid gap-8 xl:grid-cols-[0.95fr_1.05fr]">
        <div class="luxury-card space-y-6">
            <div>
                <h2 class="font-display text-3xl">Portfolio Page Content</h2>
                <p class="mt-2 text-sm text-muted-foreground">Control the public portfolio hero, metrics, and client-success section from one place.</p>
            </div>

            <form action="{{ route('admin.cms.portfolio.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="admin-label">Hero Subtitle</label>
                        <input name="hero_subtitle" value="{{ $portfolio['hero_subtitle'] ?? '' }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Hero Title</label>
                        <input name="hero_title" value="{{ $portfolio['hero_title'] ?? '' }}" class="input-shell">
                    </div>
                </div>

                <div>
                    <label class="admin-label">Hero Description</label>
                    <textarea name="hero_description" rows="4" class="input-shell">{{ $portfolio['hero_description'] ?? '' }}</textarea>
                </div>

                <div>
                    <label class="admin-label">Portfolio Metrics</label>
                    <textarea name="metrics_text" rows="6" class="input-shell">{{ $portfolioMetricsText }}</textarea>
                    <p class="mt-2 text-xs text-muted-foreground">Use <code>Label|Value</code> per line. Example: <code>Portfolio Value|NGN 50B</code></p>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="admin-label">Client Success Label</label>
                        <input name="client_success_label" value="{{ $portfolio['client_success_label'] ?? '' }}" class="input-shell">
                    </div>
                    <div>
                        <label class="admin-label">Client Success Title</label>
                        <input name="client_success_title" value="{{ $portfolio['client_success_title'] ?? '' }}" class="input-shell">
                    </div>
                </div>

                <button class="luxury-btn-primary">Save Portfolio Settings</button>
            </form>
        </div>

        <div class="luxury-card">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="font-display text-3xl">Portfolio Project Selection</h2>
                    <p class="mt-2 text-sm text-muted-foreground">Choose which published projects should appear on the public portfolio page.</p>
                </div>
                <a href="{{ route('admin.cms.projects') }}" class="luxury-btn-outline">Open Projects CMS</a>
            </div>

            <div class="mt-6 space-y-3">
                @foreach ($projects as $project)
                    <div class="rounded-lg border border-border bg-card/50 px-4 py-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="font-display text-xl">{{ $project->title }}</h3>
                                <p class="mt-1 text-sm text-muted-foreground">{{ $project->location }} &middot; {{ $project->category }}</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2">
                                <x-status-badge :value="$project->is_published ? 'published' : 'draft'" />
                                <x-status-badge :value="$project->is_portfolio ? 'portfolio' : 'standard'" />
                            </div>
                        </div>

                        <form action="{{ route('admin.cms.projects.update', $project) }}" method="POST" class="mt-4 grid gap-4 md:grid-cols-2">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="title" value="{{ $project->title }}">
                            <input type="hidden" name="slug" value="{{ $project->slug }}">
                            <input type="hidden" name="location" value="{{ $project->location }}">
                            <input type="hidden" name="category" value="{{ $project->category }}">
                            <input type="hidden" name="status" value="{{ $project->status }}">
                            <input type="hidden" name="overview" value="{{ $project->overview }}">
                            <input type="hidden" name="description" value="{{ $project->description }}">
                            <input type="hidden" name="cover_image" value="{{ $project->cover_image }}">
                            @if ($project->completion_date)
                                <input type="hidden" name="completion_date" value="{{ optional($project->completion_date)->format('Y-m-d') }}">
                            @endif
                            @if ($project->is_featured)
                                <input type="hidden" name="is_featured" value="1">
                            @endif
                            @if ($project->is_published)
                                <input type="hidden" name="is_published" value="1">
                            @endif

                            <label class="inline-flex items-center gap-3 rounded-lg border border-border bg-background/50 px-4 py-3 text-sm text-foreground">
                                <input type="checkbox" name="is_portfolio" value="1" @checked($project->is_portfolio) class="h-4 w-4 rounded border-border bg-background text-primary focus:ring-primary">
                                <span>Show this project on the public portfolio page</span>
                            </label>

                            <button class="luxury-btn-primary">Save Portfolio Visibility</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.admin>

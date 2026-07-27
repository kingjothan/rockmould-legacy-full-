@php
    $categories = $posts
        ->pluck('category')
        ->prepend('All')
        ->filter()
        ->unique()
        ->values();

    $currentCategory = request('category', 'All');
    $listingPosts = $featured ? $posts->reject(fn ($post) => $post->is($featured))->values() : $posts;
@endphp

<x-layouts.public :company="$company" title="Insights | Rockmould Limited">
    <x-hero
        :title="$page['hero_title'] ?? 'Insights & Resources'"
        :subtitle="$page['hero_subtitle'] ?? 'Blog'"
        :description="$page['hero_description'] ?? 'Expert perspectives on real estate, construction, and investment from the Rockmould team.'"
        :image="$page['hero_media_path'] ?? 'images/project-3.jpg'"
        height="medium"
        :title-size="$page['font_sizes']['hero_title'] ?? null"
        :description-size="$page['font_sizes']['hero_description'] ?? null"
    />

    <section class="section-padding">
        <div class="container-luxury">
            @if ($featured)
                <a href="{{ route('blog.show', $featured->slug) }}" class="group mb-16 block overflow-hidden rounded-lg border border-border bg-card transition-colors hover:border-primary/30">
                    <div class="grid grid-cols-1 lg:grid-cols-[1.05fr_0.95fr]">
                        <div class="overflow-hidden">
                            <x-media :path="$featured->cover_image" fallback="images/project-3.jpg" alt="{{ $featured->title }}" class="aspect-[16/10] h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />
                        </div>
                        <div class="flex flex-col justify-center p-8 lg:p-12">
                            <span class="section-label">{{ $page['featured_label'] ?? 'Featured Article' }}</span>
                            <h2 class="font-display text-3xl font-semibold transition-colors group-hover:text-primary">{{ $featured->title }}</h2>
                            <p class="mt-4 leading-relaxed text-muted-foreground">{{ $featured->excerpt }}</p>
                            <div class="mt-6 flex flex-wrap items-center gap-3 text-xs text-muted-foreground">
                                @if ($featured->published_at)
                                    <span>{{ $featured->published_at->format('F j, Y') }}</span>
                                @endif
                                @if ($featured->read_time)
                                    <span aria-hidden="true" class="text-primary/60">&middot;</span>
                                    <span>{{ $featured->read_time }} read</span>
                                @endif
                                @if ($featured->author_name)
                                    <span aria-hidden="true" class="text-primary/60">&middot;</span>
                                    <span class="flex items-center gap-2.5">
                                        @if ($featured->author_image)
                                            <img src="{{ \App\Support\AssetPath::url($featured->author_image) }}" alt="{{ $featured->author_name }}" class="h-9 w-9 rounded-full object-cover border border-border">
                                        @else
                                            <span class="flex h-9 w-9 items-center justify-center rounded-full border border-border bg-muted text-xs font-semibold uppercase text-muted-foreground">{{ mb_substr($featured->author_name, 0, 1) }}</span>
                                        @endif
                                        <span>{{ $featured->author_name }}</span>
                                    </span>
                                @endif
                            </div>
                            <span class="mt-6 inline-flex items-center gap-2 text-xs uppercase tracking-[0.18em] text-primary">
                                Read Article
                                <x-portal-icon name="arrow-right" class="h-3.5 w-3.5" />
                            </span>
                        </div>
                    </div>
                </a>
            @endif

            <div class="mb-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div class="flex flex-wrap gap-2">
                    @foreach ($categories as $category)
                        @php
                            $query = array_filter([
                                'category' => $category !== 'All' ? $category : null,
                                'q' => request('q'),
                            ]);
                        @endphp
                        <a href="{{ route('blog.index', $query) }}" class="filter-pill {{ $currentCategory === $category || ($category === 'All' && ! request('category')) ? 'filter-pill-active' : 'filter-pill-inactive' }}">
                            {{ $category }}
                        </a>
                    @endforeach
                </div>

                <form method="GET" class="w-full md:max-w-sm">
                    @if ($currentCategory !== 'All')
                        <input type="hidden" name="category" value="{{ $currentCategory }}">
                    @endif
                    <div class="portal-search-shell">
                        <x-portal-icon name="search" class="h-4 w-4" />
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search articles..." class="input-shell py-3">
                    </div>
                </form>
            </div>

            @if ($listingPosts->isEmpty())
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <x-portal-icon name="search" class="h-7 w-7" />
                    </div>
                    <h3 class="font-display text-2xl font-semibold">{{ $page['empty_title'] ?? 'No Insights Found' }}</h3>
                    <p class="mt-2 text-muted-foreground">{{ $page['empty_description'] ?? 'Try a different keyword or switch categories to explore more Rockmould insights.' }}</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($listingPosts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="group block overflow-hidden rounded-lg border border-border bg-card reveal transition-colors hover:border-primary/30">
                            <div class="overflow-hidden">
                                <x-media :path="$post->cover_image" fallback="images/project-3.jpg" alt="{{ $post->title }}" class="aspect-[16/10] w-full object-cover transition-transform duration-700 group-hover:scale-105" />
                            </div>
                            <div class="p-6">
                                <div class="mb-3 flex flex-wrap items-center gap-3">
                                    <span class="text-[11px] uppercase tracking-[0.15em] text-primary">{{ $post->category }}</span>
                                    @if ($post->read_time)
                                        <span class="text-[11px] text-muted-foreground">{{ $post->read_time }}</span>
                                    @endif
                                </div>
                                <h3 class="font-display text-xl font-semibold transition-colors group-hover:text-primary">{{ $post->title }}</h3>
                                <p class="mt-3 text-sm leading-relaxed text-muted-foreground line-clamp-2">{{ $post->excerpt }}</p>
                                @if ($post->published_at)
                                    <p class="mt-4 text-[11px] text-muted-foreground">{{ $post->published_at->format('M d, Y') }}</p>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

            <div class="luxury-card mt-16 p-12 text-center">
                <h3 class="font-display text-2xl font-semibold">{{ $page['subscribe_title'] ?? 'Subscribe to Our Insights' }}</h3>
                <p class="mx-auto mt-3 max-w-md text-sm text-muted-foreground">{{ $page['subscribe_description'] ?? 'Get exclusive market intelligence, project updates, and advisory notes delivered to your inbox.' }}</p>
                <form action="{{ route('forms.newsletter') }}" method="POST" class="mx-auto mt-6 flex max-w-md flex-col gap-3 sm:flex-row">
                    @csrf
                    <input type="email" name="email" placeholder="Your email address" class="input-shell flex-1" required>
                    <button class="luxury-btn-primary justify-center text-xs">{{ $page['subscribe_button'] ?? 'Subscribe' }}</button>
                </form>
            </div>
        </div>
    </section>
</x-layouts.public>

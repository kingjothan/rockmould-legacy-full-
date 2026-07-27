<x-layouts.public :company="$company" :title="$post->title.' | Rockmould Insights'">
    <div class="pt-20"></div>

    <section class="section-padding">
        <div class="container-luxury max-w-5xl">
            <a href="{{ route('blog.index') }}" class="mb-8 inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-primary">
                <x-portal-icon name="chevron-left" class="h-4 w-4" />
                Back to Insights
            </a>

            <span class="section-label">{{ $post->category }}</span>
            <h1 class="max-w-4xl font-display text-4xl font-bold leading-tight md:text-5xl">{{ $post->title }}</h1>

            <div class="mt-6 flex flex-wrap items-center gap-4 text-sm text-muted-foreground">
                @if ($post->published_at)
                    <span>{{ $post->published_at->format('F j, Y') }}</span>
                @endif
                @if ($post->read_time)
                    <span aria-hidden="true" class="text-primary/60">&middot;</span>
                    <span>{{ $post->read_time }} read</span>
                @endif
                @if ($post->author_name)
                    <span aria-hidden="true" class="text-primary/60">&middot;</span>
                    <span class="flex items-center gap-2">
                        @if ($post->author_image)
                            <img src="{{ \App\Support\AssetPath::url($post->author_image) }}" alt="{{ $post->author_name }}" class="h-7 w-7 rounded-full object-cover border border-border">
                        @else
                            <span class="flex h-7 w-7 items-center justify-center rounded-full border border-border bg-muted text-[10px] font-semibold uppercase text-muted-foreground">
                                {{ mb_substr($post->author_name, 0, 1) }}
                            </span>
                        @endif
                        <span>{{ $post->author_name }}</span>
                    </span>
                @endif
            </div>

            <div class="mt-10 aspect-[16/9] overflow-hidden rounded-lg border border-border bg-card">
                <x-media :path="$post->cover_image" fallback="images/project-3.jpg" alt="{{ $post->title }}" class="h-full w-full object-cover" />
            </div>

            <div class="mt-12 grid gap-10 lg:grid-cols-[1.7fr_0.75fr]">
                <article class="space-y-8">
                    @if ($post->intro)
                        <p class="text-lg leading-relaxed text-muted-foreground">{{ $post->intro }}</p>
                    @endif

                    @foreach ($post->content ?? [] as $section)
                        <section>
                            @if (!empty($section['heading']))
                                <h2 class="mb-4 font-display text-2xl font-semibold">{{ $section['heading'] }}</h2>
                            @endif
                            <div class="space-y-4 leading-relaxed text-muted-foreground">
                                @foreach ($section['paragraphs'] ?? [] as $paragraph)
                                    <p>{{ $paragraph }}</p>
                                @endforeach
                            </div>
                            @if (!empty($section['media']))
                                <div class="mt-6 overflow-hidden rounded-lg border border-border">
                                    @if (str_contains($section['media'], '.mp4') || str_contains($section['media'], '.webm') || str_contains($section['media'], '.mov') || str_contains($section['media'], 'youtube') || str_contains($section['media'], 'vimeo'))
                                        @if (str_contains($section['media'], 'youtube.com') || str_contains($section['media'], 'youtu.be'))
                                            @php
                                                preg_match('/(?:v=|youtu\.be\/)([\w-]+)/', $section['media'], $m);
                                                $ytId = $m[1] ?? '';
                                            @endphp
                                            <div class="aspect-video">
                                                <iframe src="https://www.youtube.com/embed/{{ $ytId }}" class="h-full w-full" frameborder="0" allowfullscreen></iframe>
                                            </div>
                                        @elseif (str_contains($section['media'], 'vimeo.com'))
                                            @php
                                                preg_match('/vimeo\.com\/(\d+)/', $section['media'], $m);
                                                $vimeoId = $m[1] ?? '';
                                            @endphp
                                            <div class="aspect-video">
                                                <iframe src="https://player.vimeo.com/video/{{ $vimeoId }}" class="h-full w-full" frameborder="0" allowfullscreen></iframe>
                                            </div>
                                        @else
                                            <video controls class="w-full"><source src="{{ $section['media'] }}"></video>
                                        @endif
                                    @else
                                        <x-media :path="$section['media']" alt="{{ $section['media_caption'] ?? $post->title }}" class="w-full object-cover" />
                                    @endif
                                    @if (!empty($section['media_caption']))
                                        <p class="bg-card px-4 py-2 text-xs text-muted-foreground">{{ $section['media_caption'] }}</p>
                                    @endif
                                </div>
                            @endif
                        </section>
                    @endforeach

                    @if ($post->quote)
                        <blockquote class="border-l-2 border-primary pl-6 font-display text-2xl leading-relaxed text-foreground">
                            "{{ $post->quote }}"
                        </blockquote>
                    @endif
                </article>

                <aside class="space-y-6">
                    <div class="luxury-card">
                        <h3 class="font-display text-lg font-semibold">Share This Insight</h3>
                        <p class="mt-3 text-sm text-muted-foreground">Use this article in investor conversations, planning sessions, or advisory briefings.</p>
                    </div>

                    @if ($relatedPosts->isNotEmpty())
                        <div class="luxury-card">
                            <h3 class="mb-4 font-display text-lg font-semibold">Related Posts</h3>
                            <div class="space-y-2">
                                @foreach ($relatedPosts as $relatedPost)
                                    <a href="{{ route('blog.show', $relatedPost->slug) }}" class="flex items-start justify-between rounded-md border border-border px-4 py-3 text-sm text-muted-foreground transition-colors hover:border-primary/30 hover:text-foreground">
                                        <span class="flex-1 leading-snug">{{ $relatedPost->title }}</span>
                                        <x-portal-icon name="chevron-right" class="ml-2 mt-0.5 h-3.5 w-3.5 shrink-0 text-primary/50" />
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="luxury-card">
                        <h3 class="font-display text-lg font-semibold">Stay Informed</h3>
                        <p class="mt-3 text-sm text-muted-foreground">Get project updates and premium market insights directly from Rockmould.</p>
                        <a href="{{ route('contact') }}" class="luxury-btn-outline mt-5 w-full justify-center text-xs">Get in Touch</a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.public>

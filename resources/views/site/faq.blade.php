<x-layouts.public :company="$company" title="FAQ | Rockmould Limited">
    <x-hero
        :title="$page['hero_title'] ?? 'Frequently Asked Questions'"
        :subtitle="$page['hero_subtitle'] ?? 'Support and Clarity'"
        :description="$page['hero_description'] ?? 'Answers to common questions about our projects, services, portal access, and delivery model.'"
        :image="$page['hero_media_path'] ?? 'images/project-2.jpg'"
    />

    <section class="section-padding">
        <div class="container-luxury">
            <form method="GET" class="mb-10 grid gap-4 rounded-lg border border-border bg-card p-6 md:grid-cols-[1fr_220px_160px]">
                <div>
                    <label class="admin-label">Search Questions</label>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Type a keyword..." class="input-shell">
                </div>
                <div>
                    <label class="admin-label">Category</label>
                    <input type="text" name="category" value="{{ request('category') }}" placeholder="e.g. Projects" class="input-shell">
                </div>
                <div class="flex items-end">
                    <button class="luxury-btn-primary w-full">Search</button>
                </div>
            </form>

            <div class="space-y-10">
                @forelse ($faqs as $category => $items)
                    <section class="reveal">
                        <h2 class="mb-5 font-display text-2xl font-semibold">{{ $category }}</h2>
                        <div class="space-y-3">
                            @foreach ($items as $faq)
                                <details class="faq-item">
                                    <summary>
                                        {{ $faq->question }}
                                        <svg class="faq-chevron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                                    </summary>
                                    <div class="faq-body">{{ $faq->answer }}</div>
                                </details>
                            @endforeach
                        </div>
                    </section>
                @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.024 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
                        </div>
                        <h3 class="font-display text-2xl font-semibold">{{ $page['empty_title'] ?? 'No Results Found' }}</h3>
                        <p class="mt-2 text-muted-foreground">{{ $page['empty_description'] ?? 'Try a different search term or browse another category.' }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.public>

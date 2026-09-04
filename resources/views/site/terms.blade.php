<x-layouts.public :company="$company" title="Terms of Service | Rockmould Limited">
    @php
        $sections = $legal['terms_sections'] ?? [];
        $heroMedia = $legal['terms_hero_media_path'] ?? 'images/project-4.jpg';
    @endphp

    <x-hero
        title="{{ $legal['terms_hero_title'] ?? 'Terms of Service' }}"
        subtitle="{{ $legal['terms_hero_subtitle'] ?? 'Legal' }}"
        description="{{ $legal['terms_hero_description'] ?? 'The terms that govern your use of the Rockmould Limited website and related services.' }}"
        :image="$heroMedia"
        :titleSize="$legal['terms_title_size'] ?? null"
        :descriptionSize="$legal['terms_description_size'] ?? null" />

    <section class="section-padding">
        <div class="container-luxury">
            <div class="mx-auto max-w-3xl space-y-10">

                {{-- Table of Contents --}}
                @if (count($sections) > 0)
                <div class="rounded-lg border border-border bg-card p-6">
                    <p class="mb-4 text-xs font-semibold uppercase tracking-[0.18em] text-muted-foreground">On this page</p>
                    <ol class="grid gap-x-6 gap-y-1 sm:grid-cols-2" style="list-style:none;padding:0;margin:0;">
                        @foreach ($sections as $i => $section)
                        <li class="text-sm">
                            <a href="#terms-section-{{ $i + 1 }}" class="text-foreground/70 hover:text-primary transition-colors">
                                <span class="mr-1 font-semibold" style="color:hsl(var(--primary));">{{ $i + 1 }}.</span>{{ $section['title'] }}
                            </a>
                        </li>
                        @endforeach
                    </ol>
                </div>
                @endif

                {{-- Sections --}}
                <div class="luxury-card space-y-10">
                    @foreach ($sections as $i => $section)
                    <div id="terms-section-{{ $i + 1 }}" @if($i > 0) class="border-t border-border pt-8" @endif style="scroll-margin-top:1.5rem;">
                        <h2 class="mb-4 flex items-baseline gap-3 font-display text-lg font-semibold">
                            <span class="shrink-0 font-bold text-sm tabular-nums" style="color:hsl(var(--primary));">{{ $i + 1 }}</span>
                            {{ $section['title'] }}
                        </h2>
                        <div class="prose-legal text-sm leading-relaxed text-muted-foreground">
                            {!! $section['body'] !!}
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="flex justify-center">
                    <a href="{{ route('contact') }}" class="luxury-btn-outline">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <style>
        .prose-legal p { margin: 0 0 0.85rem; }
        .prose-legal p:last-child { margin-bottom: 0; }
        .prose-legal ul { margin: 0 0 0.85rem; padding-left: 1.25rem; }
        .prose-legal li { margin: 0.35rem 0; }
        .prose-legal strong { color: hsl(var(--foreground)); }
        .prose-legal a { color: hsl(var(--primary)); text-underline-offset: 2px; }
        .prose-legal a:hover { text-decoration: underline; }
        .prose-legal table { width: 100%; border-collapse: collapse; font-size: 0.82rem; margin-bottom: 0.85rem; }
        .prose-legal thead th { background: hsl(var(--secondary)); color: hsl(var(--foreground)); text-align: left; font-weight: 600; padding: 0.6rem 0.85rem; }
        .prose-legal tbody td { padding: 0.55rem 0.85rem; border-top: 1px solid hsl(var(--border)); vertical-align: top; }
        .prose-legal tbody tr:nth-child(even) { background: hsl(var(--muted)/0.4); }
        .prose-legal .tbl-wrap { overflow-x: auto; border: 1px solid hsl(var(--border)); border-radius: 0.375rem; margin-bottom: 0.85rem; }
    </style>
</x-layouts.public>

<x-layouts.admin :company="$company" page-heading="Homepage CMS">
    @php
        $homepageStatsText = collect($homepage['stats'] ?? [])
            ->map(fn ($stat) => ($stat['label'] ?? '').'|'.($stat['value'] ?? ''))
            ->implode(PHP_EOL);
        $homepageProcessText = collect($homepage['process'] ?? [])
            ->map(fn ($step) => ($step['number'] ?? '').'|'.($step['title'] ?? '').'|'.($step['description'] ?? ''))
            ->implode(PHP_EOL);
        $heroMediaPath = $homepage['hero_media_path'] ?? null;
        $featureImagePath = $homepage['feature_image'] ?? null;
        $featuredSection = $homepage['featured_section'] ?? [];
        $servicesSection = $homepage['services_section'] ?? [];
        $whySection = $homepage['why_section'] ?? [];
        $processSection = $homepage['process_section'] ?? [];
        $testimonialsSection = $homepage['testimonials_section'] ?? [];
        $insightsSection = $homepage['insights_section'] ?? [];
        $portalSection = $homepage['portal_section'] ?? [];
        $finalCta = $homepage['final_cta'] ?? [];
    @endphp

    <form action="{{ route('admin.cms.homepage.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid gap-8 lg:grid-cols-[1.08fr_0.92fr]">
            <div class="min-w-0 space-y-8">
                <div class="luxury-card space-y-6">
                    <div>
                        <p class="section-label">Hero Control</p>
                        <h2 class="font-display text-3xl">Homepage Background and Hero Copy</h2>
                        <p class="mt-2 text-sm text-muted-foreground">This controls the homepage background image or video, the “Building Legacies That Endure” headline, and the hero call-to-actions.</p>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
                        <div class="min-w-0 space-y-4">
                            <div>
                                <label class="admin-label">Hero Label</label>
                                <input name="hero_label" value="{{ old('hero_label', $homepage['hero_label']) }}" class="input-shell">
                            </div>

                            <div>
                                <label class="admin-label">Hero Title Lines</label>
                                <p class="mb-2 text-xs text-muted-foreground">Each line appears stacked. Adjust the size per line (sm, base, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl).</p>
                                <div data-hero-lines data-initial='@json($homepage["hero_lines"] ?? [])' class="space-y-2"></div>
                                <button type="button" data-hero-add-line class="luxury-btn-outline mt-2 text-xs">+ Add Line</button>
                                <input type="hidden" name="hero_lines_json" data-hero-lines-output>
                                <input type="hidden" name="hero_title" value="{{ $homepage['hero_title'] }}">
                            </div>

                            <div>
                                <label class="admin-label">Hero Description</label>
                                <div class="flex gap-2">
                                    <textarea name="hero_description" rows="4" class="input-shell flex-1">{{ old('hero_description', $homepage['hero_description']) }}</textarea>
                                    <select name="hero_description_size" class="input-shell w-24 self-start" title="Font size">
                                        @foreach (['text-sm' => 'SM', 'text-base' => 'BASE', 'text-lg' => 'LG', 'text-xl' => 'XL', 'text-2xl' => '2XL', 'text-3xl' => '3XL'] as $val => $label)
                                            <option value="{{ $val }}" @selected(($homepage['font_sizes']['hero_description'] ?? 'text-lg') === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid gap-4 md:grid-cols-3">
                                <div>
                                    <label class="admin-label">Primary CTA</label>
                                    <input name="hero_primary_cta" value="{{ old('hero_primary_cta', $homepage['hero_primary_cta']) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Secondary CTA</label>
                                    <input name="hero_secondary_cta" value="{{ old('hero_secondary_cta', $homepage['hero_secondary_cta']) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Portal CTA</label>
                                    <input name="hero_portal_cta" value="{{ old('hero_portal_cta', $homepage['hero_portal_cta']) }}" class="input-shell">
                                </div>
                            </div>
                        </div>

                        <div class="min-w-0 space-y-4">
                            <div class="overflow-hidden rounded-lg border border-border bg-card">
                                <div class="aspect-[16/9] bg-muted">
                                    @if ($heroMediaPath)
                                        <x-media :path="$heroMediaPath" alt="Current homepage hero media" class="h-full w-full object-cover" :controls="true" :loop="false" />
                                    @else
                                        <div class="flex h-full items-center justify-center text-sm text-muted-foreground">No hero media selected</div>
                                    @endif
                                </div>
                                <div class="space-y-2 border-t border-border px-4 py-4">
                                    <p class="text-xs uppercase tracking-[0.18em] text-primary/70">Current Background Media</p>
                                    <p class="text-sm text-muted-foreground">{{ $heroMediaPath ?: 'Not set yet' }}</p>
                                </div>
                            </div>

                            <div>
                                <label class="admin-label">Hero Background Link or Path</label>
                                <input name="hero_media_path" value="{{ old('hero_media_path', $homepage['hero_media_path'] ?? '') }}" class="input-shell" placeholder="images/project-1.jpg or storage/cms/homepage/hero.mp4">
                                <p class="mt-2 text-xs text-muted-foreground">Paste a CDN/direct image or video URL, or use a local path. Uploading a new file below overrides this value.</p>
                            </div>

                            <div>
                                <label class="admin-label">Upload New Hero Background</label>
                                <input type="file" name="hero_media" accept="image/*,video/*" data-preview-target="#homepage-hero-media-preview" class="input-shell">
                                <p id="homepage-hero-media-preview" class="mt-2 text-xs text-muted-foreground">Accepted: image or video. Recommended: 1920x1080 or better.</p>
                                <label class="mt-3 flex items-center gap-2 text-xs text-muted-foreground">
                                    <input type="checkbox" name="remove_hero_media" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                                    Remove current hero media if no new link or upload is provided
                                </label>
                            </div>

                            <div>
                                <label class="admin-label">Feature Media Link or Path</label>
                                <input name="feature_image_path" value="{{ old('feature_image_path', $featureImagePath ?? '') }}" class="input-shell" placeholder="https://cdn.example.com/feature.mp4">
                                <p class="mt-2 text-xs text-muted-foreground">Use this for CDN-hosted homepage feature images or videos.</p>
                            </div>

                            <div>
                                <label class="admin-label">Upload Feature Media</label>
                                <input type="file" name="feature_image" accept="image/*,video/*" data-preview-target="#homepage-feature-image-preview" class="input-shell">
                                <p id="homepage-feature-image-preview" class="mt-2 text-xs text-muted-foreground">Accepted: image or video up to 200MB. Recommended visual crop: 4:3 or better.</p>
                                <label class="mt-3 flex items-center gap-2 text-xs text-muted-foreground">
                                    <input type="checkbox" name="remove_feature_image" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                                    Remove current feature media if no new link or upload is provided
                                </label>
                            </div>

                            @if ($featureImagePath)
                                <div class="overflow-hidden rounded-lg border border-border bg-card">
                                    <div class="aspect-[4/3] bg-muted">
                                        <x-media :path="$featureImagePath" alt="Current homepage feature media" class="h-full w-full object-cover" :controls="true" :loop="false" />
                                    </div>
                                    <div class="border-t border-border px-4 py-4">
                                        <p class="text-xs uppercase tracking-[0.18em] text-primary/70">Current Feature Media</p>
                                        <p class="mt-1 text-sm text-muted-foreground">{{ $featureImagePath }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="luxury-card space-y-6">
                    <div>
                        <p class="section-label">Homepage Story Blocks</p>
                        <h2 class="font-display text-3xl">Homepage Section Write-Ups</h2>
                        <p class="mt-2 text-sm text-muted-foreground">Edit the section titles, labels, and supporting copy used across the homepage so the full page reads consistently from admin.</p>
                    </div>

                    <div class="grid gap-5 lg:grid-cols-2">
                        @php
                            $sizeOptions = ['text-xs' => 'XS', 'text-sm' => 'SM', 'text-base' => 'BASE', 'text-lg' => 'LG', 'text-xl' => 'XL', 'text-2xl' => '2XL', 'text-3xl' => '3XL', 'text-4xl' => '4XL', 'text-5xl' => '5XL'];
                            $fontSizes = $homepage['font_sizes'] ?? [];
                        @endphp

                        <div class="form-section">
                            <p class="form-section-title">Featured Developments</p>
                            <div>
                                <label class="admin-label">Label</label>
                                <input name="featured_label" value="{{ old('featured_label', $featuredSection['label'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Title</label>
                                <div class="flex gap-2">
                                    <input name="featured_title" value="{{ old('featured_title', $featuredSection['title'] ?? '') }}" class="input-shell flex-1">
                                    <select name="font_sizes[featured_title]" class="input-shell w-24" title="Font size">
                                        @foreach ($sizeOptions as $val => $label)
                                            <option value="{{ $val }}" @selected(($fontSizes['featured_title'] ?? 'text-3xl') === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="admin-label">Description</label>
                                <div class="flex gap-2">
                                    <textarea name="featured_description" rows="4" class="input-shell flex-1">{{ old('featured_description', $featuredSection['description'] ?? '') }}</textarea>
                                    <select name="font_sizes[featured_description]" class="input-shell w-24 self-start" title="Font size">
                                        @foreach ($sizeOptions as $val => $label)
                                            <option value="{{ $val }}" @selected(($fontSizes['featured_description'] ?? 'text-base') === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="admin-label">CTA Label</label>
                                <input name="featured_cta" value="{{ old('featured_cta', $featuredSection['cta'] ?? '') }}" class="input-shell">
                            </div>
                        </div>

                        <div class="form-section">
                            <p class="form-section-title">Services Section</p>
                            <div>
                                <label class="admin-label">Label</label>
                                <input name="services_label" value="{{ old('services_label', $servicesSection['label'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Title</label>
                                <div class="flex gap-2">
                                    <input name="services_title" value="{{ old('services_title', $servicesSection['title'] ?? '') }}" class="input-shell flex-1">
                                    <select name="font_sizes[services_title]" class="input-shell w-24" title="Font size">
                                        @foreach ($sizeOptions as $val => $label)
                                            <option value="{{ $val }}" @selected(($fontSizes['services_title'] ?? 'text-3xl') === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="admin-label">Description</label>
                                <div class="flex gap-2">
                                    <textarea name="services_description" rows="4" class="input-shell flex-1">{{ old('services_description', $servicesSection['description'] ?? '') }}</textarea>
                                    <select name="font_sizes[services_description]" class="input-shell w-24 self-start" title="Font size">
                                        @foreach ($sizeOptions as $val => $label)
                                            <option value="{{ $val }}" @selected(($fontSizes['services_description'] ?? 'text-base') === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <p class="form-section-title">Why Rockmould</p>
                            <div>
                                <label class="admin-label">Label</label>
                                <input name="why_label" value="{{ old('why_label', $whySection['label'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Title</label>
                                <div class="flex gap-2">
                                    <input name="why_title" value="{{ old('why_title', $whySection['title'] ?? '') }}" class="input-shell flex-1">
                                    <select name="font_sizes[why_title]" class="input-shell w-24" title="Font size">
                                        @foreach ($sizeOptions as $val => $label)
                                            <option value="{{ $val }}" @selected(($fontSizes['why_title'] ?? 'text-3xl') === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="admin-label">CTA Label</label>
                                <input name="why_cta" value="{{ old('why_cta', $whySection['cta'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Why Choose Rockmould</label>
                                <textarea name="why_choose_text" rows="6" class="input-shell">{{ old('why_choose_text', implode("\n", $homepage['why_choose'] ?? [])) }}</textarea>
                                <p class="mt-2 text-xs text-muted-foreground">One point per line.</p>
                            </div>
                        </div>

                        <div class="form-section">
                            <p class="form-section-title">Process Section</p>
                            <div>
                                <label class="admin-label">Label</label>
                                <input name="process_label" value="{{ old('process_label', $processSection['label'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Title</label>
                                <div class="flex gap-2">
                                    <input name="process_title" value="{{ old('process_title', $processSection['title'] ?? '') }}" class="input-shell flex-1">
                                    <select name="font_sizes[process_title]" class="input-shell w-24" title="Font size">
                                        @foreach ($sizeOptions as $val => $label)
                                            <option value="{{ $val }}" @selected(($fontSizes['process_title'] ?? 'text-3xl') === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="admin-label">Description</label>
                                <div class="flex gap-2">
                                    <textarea name="process_description" rows="4" class="input-shell flex-1">{{ old('process_description', $processSection['description'] ?? '') }}</textarea>
                                    <select name="font_sizes[process_description]" class="input-shell w-24 self-start" title="Font size">
                                        @foreach ($sizeOptions as $val => $label)
                                            <option value="{{ $val }}" @selected(($fontSizes['process_description'] ?? 'text-base') === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="admin-label">Process Steps</label>
                                <textarea name="process_text" rows="6" class="input-shell">{{ old('process_text', $homepageProcessText) }}</textarea>
                                <p class="mt-2 text-xs text-muted-foreground">Use <code>Number|Title|Description</code> per line.</p>
                            </div>
                        </div>

                        <div class="form-section">
                            <p class="form-section-title">Insights and Testimonials</p>
                            <div>
                                <label class="admin-label">Testimonials Label</label>
                                <input name="testimonials_label" value="{{ old('testimonials_label', $testimonialsSection['label'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Testimonials Title</label>
                                <div class="flex gap-2">
                                    <input name="testimonials_title" value="{{ old('testimonials_title', $testimonialsSection['title'] ?? '') }}" class="input-shell flex-1">
                                    <select name="font_sizes[testimonials_title]" class="input-shell w-24" title="Font size">
                                        @foreach ($sizeOptions as $val => $label)
                                            <option value="{{ $val }}" @selected(($fontSizes['testimonials_title'] ?? 'text-3xl') === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="admin-label">Insights Label</label>
                                <input name="insights_label" value="{{ old('insights_label', $insightsSection['label'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Insights Title</label>
                                <div class="flex gap-2">
                                    <input name="insights_title" value="{{ old('insights_title', $insightsSection['title'] ?? '') }}" class="input-shell flex-1">
                                    <select name="font_sizes[insights_title]" class="input-shell w-24" title="Font size">
                                        @foreach ($sizeOptions as $val => $label)
                                            <option value="{{ $val }}" @selected(($fontSizes['insights_title'] ?? 'text-3xl') === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="admin-label">Insights Description</label>
                                <div class="flex gap-2">
                                    <textarea name="insights_description" rows="4" class="input-shell flex-1">{{ old('insights_description', $insightsSection['description'] ?? '') }}</textarea>
                                    <select name="font_sizes[insights_description]" class="input-shell w-24 self-start" title="Font size">
                                        @foreach ($sizeOptions as $val => $label)
                                            <option value="{{ $val }}" @selected(($fontSizes['insights_description'] ?? 'text-base') === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <p class="form-section-title">Client Portal and Final CTA</p>
                            <div>
                                <label class="admin-label">Client Portal Title</label>
                                <input name="portal_title" value="{{ old('portal_title', $portalSection['title'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Client Portal Description</label>
                                <textarea name="portal_description" rows="4" class="input-shell">{{ old('portal_description', $portalSection['description'] ?? '') }}</textarea>
                            </div>
                            <div>
                                <label class="admin-label">Client Portal CTA</label>
                                <input name="portal_cta" value="{{ old('portal_cta', $portalSection['cta'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Final CTA Title</label>
                                <input name="final_cta_title" value="{{ old('final_cta_title', $finalCta['title'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Final CTA Description</label>
                                <textarea name="final_cta_description" rows="4" class="input-shell">{{ old('final_cta_description', $finalCta['description'] ?? '') }}</textarea>
                            </div>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="admin-label">Final CTA Primary Button</label>
                                    <input name="final_cta_primary" value="{{ old('final_cta_primary', $finalCta['primary_cta'] ?? '') }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Final CTA Secondary Button</label>
                                    <input name="final_cta_secondary" value="{{ old('final_cta_secondary', $finalCta['secondary_cta'] ?? '') }}" class="input-shell">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="min-w-0 space-y-8">
                <div class="luxury-card space-y-6">
                    <div>
                        <p class="section-label">Live Summary</p>
                        <h2 class="font-display text-3xl">What the Homepage Is Saying</h2>
                        <p class="mt-2 text-sm text-muted-foreground">A quick summary of the core homepage messaging you’re controlling from this screen.</p>
                    </div>

                    <div class="space-y-4 rounded-lg border border-border bg-card/50 p-5">
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.18em] text-primary/70">Main Hero</p>
                            <h3 class="mt-2 font-display text-3xl">{{ $homepage['hero_title'] }}</h3>
                            <p class="mt-3 text-sm leading-relaxed text-muted-foreground">{{ $homepage['hero_description'] }}</p>
                        </div>
                        <div class="luxury-divider"></div>
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.18em] text-primary/70">Featured Developments</p>
                            <p class="mt-2 font-subheader text-lg text-foreground">{{ $featuredSection['title'] ?? '' }}</p>
                        </div>
                        <div class="luxury-divider"></div>
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.18em] text-primary/70">Client Portal Block</p>
                            <p class="mt-2 font-subheader text-lg text-foreground">{{ $portalSection['title'] ?? '' }}</p>
                            <p class="mt-2 text-sm text-muted-foreground">{{ $portalSection['description'] ?? '' }}</p>
                        </div>
                        <div class="luxury-divider"></div>
                        <div>
                            <p class="text-[10px] uppercase tracking-[0.18em] text-primary/70">Final Call To Action</p>
                            <p class="mt-2 font-subheader text-lg text-foreground">{{ $finalCta['title'] ?? '' }}</p>
                            <p class="mt-2 text-sm text-muted-foreground">{{ $finalCta['description'] ?? '' }}</p>
                        </div>
                    </div>
                </div>

                <div class="luxury-card space-y-4">
                    <div>
                        <p class="section-label">Numbers and Process</p>
                        <h2 class="font-display text-3xl">Homepage Stats and Milestones</h2>
                    </div>

                    <div>
                        <label class="admin-label">Homepage Stats</label>
                        <textarea name="stats_text" rows="6" class="input-shell">{{ old('stats_text', $homepageStatsText) }}</textarea>
                        <p class="mt-2 text-xs text-muted-foreground">Use <code>Label|Value</code> per line. Example: <code>Projects Delivered|150+</code></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="sticky bottom-4 z-20 flex justify-end">
            <button class="luxury-btn-primary">Save Homepage Settings</button>
        </div>
    </form>
</x-layouts.admin>

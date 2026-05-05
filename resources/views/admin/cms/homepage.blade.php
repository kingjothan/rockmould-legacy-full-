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

        <div class="grid gap-8 xl:grid-cols-[1.08fr_0.92fr]">
            <div class="space-y-8">
                <div class="luxury-card space-y-6">
                    <div>
                        <p class="section-label">Hero Control</p>
                        <h2 class="font-display text-3xl">Homepage Background and Hero Copy</h2>
                        <p class="mt-2 text-sm text-muted-foreground">This controls the homepage background image or video, the “Building Legacies That Endure” headline, and the hero call-to-actions.</p>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
                        <div class="space-y-4">
                            <div>
                                <label class="admin-label">Hero Label</label>
                                <input name="hero_label" value="{{ old('hero_label', $homepage['hero_label']) }}" class="input-shell">
                            </div>

                            <div>
                                <label class="admin-label">Hero Title</label>
                                <input name="hero_title" value="{{ old('hero_title', $homepage['hero_title']) }}" class="input-shell">
                            </div>

                            <div>
                                <label class="admin-label">Hero Description</label>
                                <textarea name="hero_description" rows="4" class="input-shell">{{ old('hero_description', $homepage['hero_description']) }}</textarea>
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

                        <div class="space-y-4">
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
                                <label class="admin-label">Hero Background Path</label>
                                <input name="hero_media_path" value="{{ old('hero_media_path', $homepage['hero_media_path'] ?? '') }}" class="input-shell" placeholder="images/project-1.jpg or storage/cms/homepage/hero.mp4">
                                <p class="mt-2 text-xs text-muted-foreground">Use this if you want to point the hero to an existing image or video path. Uploading a new file below overrides it.</p>
                            </div>

                            <div>
                                <label class="admin-label">Upload New Hero Background</label>
                                <input type="file" name="hero_media" accept="image/*,video/*" data-preview-target="#homepage-hero-media-preview" class="input-shell">
                                <p id="homepage-hero-media-preview" class="mt-2 text-xs text-muted-foreground">Accepted: image or video. Recommended: 1920x1080 or better.</p>
                            </div>

                            <div>
                                <label class="admin-label">Upload Feature Media</label>
                                <input type="file" name="feature_image" accept="image/*,video/*" data-preview-target="#homepage-feature-image-preview" class="input-shell">
                                <p id="homepage-feature-image-preview" class="mt-2 text-xs text-muted-foreground">Accepted: image or video up to 200MB. Recommended visual crop: 4:3 or better.</p>
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

                    <div class="grid gap-5 xl:grid-cols-2">
                        <div class="form-section">
                            <p class="form-section-title">Featured Developments</p>
                            <div>
                                <label class="admin-label">Label</label>
                                <input name="featured_label" value="{{ old('featured_label', $featuredSection['label'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Title</label>
                                <input name="featured_title" value="{{ old('featured_title', $featuredSection['title'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Description</label>
                                <textarea name="featured_description" rows="4" class="input-shell">{{ old('featured_description', $featuredSection['description'] ?? '') }}</textarea>
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
                                <input name="services_title" value="{{ old('services_title', $servicesSection['title'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Description</label>
                                <textarea name="services_description" rows="4" class="input-shell">{{ old('services_description', $servicesSection['description'] ?? '') }}</textarea>
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
                                <input name="why_title" value="{{ old('why_title', $whySection['title'] ?? '') }}" class="input-shell">
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
                                <input name="process_title" value="{{ old('process_title', $processSection['title'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Description</label>
                                <textarea name="process_description" rows="4" class="input-shell">{{ old('process_description', $processSection['description'] ?? '') }}</textarea>
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
                                <input name="testimonials_title" value="{{ old('testimonials_title', $testimonialsSection['title'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Insights Label</label>
                                <input name="insights_label" value="{{ old('insights_label', $insightsSection['label'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Insights Title</label>
                                <input name="insights_title" value="{{ old('insights_title', $insightsSection['title'] ?? '') }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Insights Description</label>
                                <textarea name="insights_description" rows="4" class="input-shell">{{ old('insights_description', $insightsSection['description'] ?? '') }}</textarea>
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

            <div class="space-y-8">
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

<x-layouts.admin :company="$company" page-heading="Public Pages CMS">
    @php
        $pageKeys = ['services', 'projects', 'blog', 'faq', 'careers', 'contact', 'partnerships', 'testimonials', 'gallery'];
        $pageHeroPreview = collect($pageKeys)->mapWithKeys(function (string $key) use ($pages) {
            $path = data_get($pages, $key.'.hero_media_path');
            $url = $path ? \App\Support\AssetPath::url($path) : null;
            $extension = strtolower(pathinfo((string) parse_url((string) $path, PHP_URL_PATH), PATHINFO_EXTENSION));

            return [$key => [
                'path' => $path,
                'url' => $url,
                'is_video' => in_array($extension, ['mp4', 'webm', 'ogg', 'mov'], true),
            ]];
        })->all();

        $pageMeta = [
            'services' => ['label' => 'Services Page', 'title' => 'Services Hero and CTA', 'description' => 'Update the services hero background, intro copy, and the closing consultation banner.'],
            'projects' => ['label' => 'Projects Page', 'title' => 'Projects Listing Intro', 'description' => 'Control the projects catalog hero and the empty-state messaging.'],
            'blog' => ['label' => 'Insights Page', 'title' => 'Blog Hero and Subscribe Block', 'description' => 'Edit the blog intro, featured label, empty state, and newsletter prompt.'],
            'faq' => ['label' => 'FAQ Page', 'title' => 'FAQ Hero and Empty State', 'description' => 'Update the support hero and the fallback copy shown when no FAQ matches the search.'],
            'careers' => ['label' => 'Careers Page', 'title' => 'Careers Story and Benefits', 'description' => 'Manage the careers hero, benefit cards, open-position intro, and the open-application banner.'],
            'contact' => ['label' => 'Contact Page', 'title' => 'Contact Hero and Panel Labels', 'description' => 'Adjust the contact intro and the labels used for the information panel, quick links, and form.'],
            'partnerships' => ['label' => 'Partnerships Page', 'title' => 'Investment Messaging and Opportunities', 'description' => 'Control the investment hero, reasons, metrics, form heading, and project-opportunities section.'],
            'testimonials' => ['label' => 'Testimonials Page', 'title' => 'Testimonials Hero', 'description' => 'Edit the trust-page intro and swap the hero background image or video.'],
            'gallery' => ['label' => 'Gallery Page', 'title' => 'Gallery Hero and Empty State', 'description' => 'Manage the gallery intro and the fallback message used when no public assets match the filters.'],
        ];

        $careerBenefitsText = collect(data_get($pages, 'careers.benefits', []))
            ->map(fn ($benefit) => ($benefit['title'] ?? '').'|'.($benefit['description'] ?? ''))
            ->implode(PHP_EOL);
        $partnershipReasonsText = collect(data_get($pages, 'partnerships.reasons', []))->implode(PHP_EOL);
        $partnershipMetricsText = collect(data_get($pages, 'partnerships.metrics', []))
            ->map(fn ($metric) => ($metric['label'] ?? '').'|'.($metric['value'] ?? ''))
            ->implode(PHP_EOL);
    @endphp

    <form action="{{ route('admin.cms.pages.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="grid gap-8 xl:grid-cols-2">
            @foreach ($pageKeys as $key)
                <div class="luxury-card space-y-6">
                    <div>
                        <p class="section-label">{{ $pageMeta[$key]['label'] }}</p>
                        <h2 class="font-display text-3xl">{{ $pageMeta[$key]['title'] }}</h2>
                        <p class="mt-2 text-sm text-muted-foreground">{{ $pageMeta[$key]['description'] }}</p>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
                        <div class="space-y-4">
                            <div>
                                <label class="admin-label">Hero Subtitle</label>
                                <input name="{{ $key }}_hero_subtitle" value="{{ old($key.'_hero_subtitle', data_get($pages, $key.'.hero_subtitle')) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Hero Title</label>
                                <input name="{{ $key }}_hero_title" value="{{ old($key.'_hero_title', data_get($pages, $key.'.hero_title')) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Hero Description</label>
                                <textarea name="{{ $key }}_hero_description" rows="4" class="input-shell">{{ old($key.'_hero_description', data_get($pages, $key.'.hero_description')) }}</textarea>
                            </div>

                            @if ($key === 'services')
                                <div>
                                    <label class="admin-label">CTA Title</label>
                                    <input name="services_cta_title" value="{{ old('services_cta_title', data_get($pages, 'services.cta_title')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">CTA Description</label>
                                    <textarea name="services_cta_description" rows="3" class="input-shell">{{ old('services_cta_description', data_get($pages, 'services.cta_description')) }}</textarea>
                                </div>
                                <div>
                                    <label class="admin-label">CTA Button Label</label>
                                    <input name="services_cta_button" value="{{ old('services_cta_button', data_get($pages, 'services.cta_button')) }}" class="input-shell">
                                </div>
                            @elseif ($key === 'projects')
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="admin-label">Empty State Title</label>
                                        <input name="projects_empty_title" value="{{ old('projects_empty_title', data_get($pages, 'projects.empty_title')) }}" class="input-shell">
                                    </div>
                                    <div>
                                        <label class="admin-label">Empty State Button</label>
                                        <input name="projects_empty_button" value="{{ old('projects_empty_button', data_get($pages, 'projects.empty_button')) }}" class="input-shell">
                                    </div>
                                </div>
                                <div>
                                    <label class="admin-label">Empty State Description</label>
                                    <textarea name="projects_empty_description" rows="3" class="input-shell">{{ old('projects_empty_description', data_get($pages, 'projects.empty_description')) }}</textarea>
                                </div>
                            @elseif ($key === 'blog')
                                <div>
                                    <label class="admin-label">Featured Label</label>
                                    <input name="blog_featured_label" value="{{ old('blog_featured_label', data_get($pages, 'blog.featured_label')) }}" class="input-shell">
                                </div>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <label class="admin-label">Empty State Title</label>
                                        <input name="blog_empty_title" value="{{ old('blog_empty_title', data_get($pages, 'blog.empty_title')) }}" class="input-shell">
                                    </div>
                                    <div>
                                        <label class="admin-label">Subscribe Button</label>
                                        <input name="blog_subscribe_button" value="{{ old('blog_subscribe_button', data_get($pages, 'blog.subscribe_button')) }}" class="input-shell">
                                    </div>
                                </div>
                                <div>
                                    <label class="admin-label">Empty State Description</label>
                                    <textarea name="blog_empty_description" rows="3" class="input-shell">{{ old('blog_empty_description', data_get($pages, 'blog.empty_description')) }}</textarea>
                                </div>
                                <div>
                                    <label class="admin-label">Subscribe Title</label>
                                    <input name="blog_subscribe_title" value="{{ old('blog_subscribe_title', data_get($pages, 'blog.subscribe_title')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Subscribe Description</label>
                                    <textarea name="blog_subscribe_description" rows="3" class="input-shell">{{ old('blog_subscribe_description', data_get($pages, 'blog.subscribe_description')) }}</textarea>
                                </div>
                            @elseif ($key === 'faq')
                                <div>
                                    <label class="admin-label">Empty State Title</label>
                                    <input name="faq_empty_title" value="{{ old('faq_empty_title', data_get($pages, 'faq.empty_title')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Empty State Description</label>
                                    <textarea name="faq_empty_description" rows="3" class="input-shell">{{ old('faq_empty_description', data_get($pages, 'faq.empty_description')) }}</textarea>
                                </div>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <div class="overflow-hidden rounded-lg border border-border bg-card">
                                <div class="aspect-[16/10] bg-muted">
                                    @if ($pageHeroPreview[$key]['url'])
                                        @if ($pageHeroPreview[$key]['is_video'])
                                            <video controls muted playsinline class="h-full w-full object-cover">
                                                <source src="{{ $pageHeroPreview[$key]['url'] }}">
                                            </video>
                                        @else
                                            <img src="{{ $pageHeroPreview[$key]['url'] }}" alt="{{ $pageMeta[$key]['label'] }} hero media" class="h-full w-full object-cover">
                                        @endif
                                    @else
                                        <div class="flex h-full items-center justify-center text-sm text-muted-foreground">No hero media selected</div>
                                    @endif
                                </div>
                                <div class="border-t border-border px-4 py-4">
                                    <p class="text-xs uppercase tracking-[0.18em] text-primary/70">Current Hero Media</p>
                                    <p class="mt-1 text-sm text-muted-foreground">{{ $pageHeroPreview[$key]['path'] ?: 'Not set yet' }}</p>
                                </div>
                            </div>
                            <div>
                                <label class="admin-label">Hero Background Path</label>
                                <input name="{{ $key }}_hero_media_path" value="{{ old($key.'_hero_media_path', data_get($pages, $key.'.hero_media_path')) }}" class="input-shell">
                            </div>
                            <div>
                                <label class="admin-label">Upload Hero Media</label>
                                <input type="file" name="{{ $key }}_hero_media_upload" accept="image/*,video/*" class="input-shell">
                                <p class="mt-2 text-xs text-muted-foreground">Accepted: image or video. Recommended: 1920x1080 or better.</p>
                            </div>

                            @if ($key === 'careers')
                                <div>
                                    <label class="admin-label">Benefit Cards</label>
                                    <textarea name="careers_benefits_text" rows="6" class="input-shell">{{ old('careers_benefits_text', $careerBenefitsText) }}</textarea>
                                    <p class="mt-2 text-xs text-muted-foreground">Use <code>Title|Description</code> per line. Existing icons stay mapped in order.</p>
                                </div>
                                <div>
                                    <label class="admin-label">Open Positions Title</label>
                                    <input name="careers_openings_title" value="{{ old('careers_openings_title', data_get($pages, 'careers.openings_title')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Open Positions Description</label>
                                    <textarea name="careers_openings_description" rows="3" class="input-shell">{{ old('careers_openings_description', data_get($pages, 'careers.openings_description')) }}</textarea>
                                </div>
                                <div>
                                    <label class="admin-label">Open Application Kicker</label>
                                    <input name="careers_open_application_kicker" value="{{ old('careers_open_application_kicker', data_get($pages, 'careers.open_application_kicker')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Open Application Title</label>
                                    <input name="careers_open_application_title" value="{{ old('careers_open_application_title', data_get($pages, 'careers.open_application_title')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Open Application Description</label>
                                    <textarea name="careers_open_application_description" rows="3" class="input-shell">{{ old('careers_open_application_description', data_get($pages, 'careers.open_application_description')) }}</textarea>
                                </div>
                                <div>
                                    <label class="admin-label">Open Application Button</label>
                                    <input name="careers_open_application_button" value="{{ old('careers_open_application_button', data_get($pages, 'careers.open_application_button')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Empty State Title</label>
                                    <input name="careers_empty_title" value="{{ old('careers_empty_title', data_get($pages, 'careers.empty_title')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Empty State Description</label>
                                    <textarea name="careers_empty_description" rows="3" class="input-shell">{{ old('careers_empty_description', data_get($pages, 'careers.empty_description')) }}</textarea>
                                </div>
                            @elseif ($key === 'contact')
                                <div>
                                    <label class="admin-label">Information Panel Title</label>
                                    <input name="contact_info_title" value="{{ old('contact_info_title', data_get($pages, 'contact.info_title')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Quick Access Title</label>
                                    <input name="contact_quick_access_title" value="{{ old('contact_quick_access_title', data_get($pages, 'contact.quick_access_title')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Form Title</label>
                                    <input name="contact_form_title" value="{{ old('contact_form_title', data_get($pages, 'contact.form_title')) }}" class="input-shell">
                                </div>
                            @elseif ($key === 'partnerships')
                                <div>
                                    <label class="admin-label">Why Invest Title</label>
                                    <input name="partnerships_reasons_title" value="{{ old('partnerships_reasons_title', data_get($pages, 'partnerships.reasons_title')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Trust Reasons</label>
                                    <textarea name="partnerships_reasons_text" rows="5" class="input-shell">{{ old('partnerships_reasons_text', $partnershipReasonsText) }}</textarea>
                                    <p class="mt-2 text-xs text-muted-foreground">Use one point per line.</p>
                                </div>
                                <div>
                                    <label class="admin-label">Metrics</label>
                                    <textarea name="partnerships_metrics_text" rows="4" class="input-shell">{{ old('partnerships_metrics_text', $partnershipMetricsText) }}</textarea>
                                    <p class="mt-2 text-xs text-muted-foreground">Use <code>Label|Value</code> per line.</p>
                                </div>
                                <div>
                                    <label class="admin-label">Form Title</label>
                                    <input name="partnerships_form_title" value="{{ old('partnerships_form_title', data_get($pages, 'partnerships.form_title')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Opportunities Label</label>
                                    <input name="partnerships_opportunities_label" value="{{ old('partnerships_opportunities_label', data_get($pages, 'partnerships.opportunities_label')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Opportunities Title</label>
                                    <input name="partnerships_opportunities_title" value="{{ old('partnerships_opportunities_title', data_get($pages, 'partnerships.opportunities_title')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Submit Button</label>
                                    <input name="partnerships_submit_button" value="{{ old('partnerships_submit_button', data_get($pages, 'partnerships.submit_button')) }}" class="input-shell">
                                </div>
                            @elseif ($key === 'gallery')
                                <div>
                                    <label class="admin-label">Empty State Title</label>
                                    <input name="gallery_empty_title" value="{{ old('gallery_empty_title', data_get($pages, 'gallery.empty_title')) }}" class="input-shell">
                                </div>
                                <div>
                                    <label class="admin-label">Empty State Description</label>
                                    <textarea name="gallery_empty_description" rows="3" class="input-shell">{{ old('gallery_empty_description', data_get($pages, 'gallery.empty_description')) }}</textarea>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="sticky bottom-4 z-20 flex justify-end">
            <button class="luxury-btn-primary">Save Public Page Settings</button>
        </div>
    </form>
</x-layouts.admin>

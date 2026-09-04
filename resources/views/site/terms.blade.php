<x-layouts.public :company="$company" title="Terms of Service | Rockmould Limited">
    @php
        $sections = $legal['terms_sections'] ?? [
            ['title' => 'Website Use', 'body' => 'Content on this website is provided for general information, project marketing, and relationship management purposes. Public site materials do not constitute a final commercial offer unless confirmed by Rockmould in writing.'],
            ['title' => 'Client Portal Access', 'body' => 'Client portal access is intended only for recognized clients and approved users. Misuse of project data, documents, media, or support channels is prohibited.'],
            ['title' => 'Project Information', 'body' => 'Project timelines, visuals, payment schedules, and availability may change. Official transaction documents, agreements, and internal approvals govern final commercial terms.'],
            ['title' => 'Acceptance of Terms', 'body' => 'By using this website or portal, you agree to engage with Rockmould lawfully and in good faith. For questions about these terms, contact us.'],
        ];
        $heroMedia = $legal['terms_hero_media_path'] ?? 'images/project-4.jpg';
    @endphp

    <x-hero
        title="{{ $legal['terms_hero_title'] ?? 'Terms of Service' }}"
        subtitle="{{ $legal['terms_hero_subtitle'] ?? 'Legal' }}"
        description="{{ $legal['terms_hero_description'] ?? 'The core terms governing the use of the Rockmould website, portal, and client-facing digital services.' }}"
        :image="$heroMedia"
        :titleSize="$legal['terms_title_size'] ?? null"
        :descriptionSize="$legal['terms_description_size'] ?? null" />

    <section class="section-padding">
        <div class="container-luxury">
            <div class="mx-auto max-w-3xl">
                <div class="luxury-card space-y-8">
                    @foreach ($sections as $i => $section)
                    <div @if($i > 0) class="border-t border-border pt-6" @endif>
                        <h2 class="mb-3 font-display text-xl font-semibold">{{ $section['title'] }}</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">{{ $section['body'] }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('contact') }}" class="luxury-btn-outline">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

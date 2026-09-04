<x-layouts.public :company="$company" title="Privacy Policy | Rockmould Limited">
    @php
        $sections = $legal['privacy_sections'] ?? [
            ['title' => 'Data Collection', 'body' => 'Rockmould Limited collects contact details, project inquiry information, client portal records, uploaded documents, and support communications to deliver services and maintain operational records.'],
            ['title' => 'How We Use Your Data', 'body' => 'Information provided through contact forms, partnerships, newsletters, KYC requests, or the client portal may be used for relationship management, project reporting, account review, and compliance workflows.'],
            ['title' => 'Data Protection', 'body' => 'We do not sell personal information. Access to sensitive records is restricted to authorized Rockmould personnel who require it for legitimate operational, finance, project, or support purposes.'],
            ['title' => 'Contact & Corrections', 'body' => 'If you need corrections, account review, or record clarification, contact us or use the portal support channel.'],
        ];
        $heroMedia = $legal['privacy_hero_media_path'] ?? 'images/project-1.jpg';
    @endphp

    <x-hero title="Privacy Policy" subtitle="Legal"
        description="{{ $legal['privacy_hero_description'] ?? 'How Rockmould collects, uses, and protects information shared across the website, portal, and client interactions.' }}"
        :image="$heroMedia"
        :titleSize="$legal['privacy_title_size'] ?? null"
        :descriptionSize="$legal['privacy_description_size'] ?? null" />

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

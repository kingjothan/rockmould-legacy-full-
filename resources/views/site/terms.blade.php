<x-layouts.public :company="$company" title="Terms of Service | Rockmould Limited">
    <x-hero title="Terms of Service" subtitle="Legal"
        description="{{ $legal['terms_hero_description'] ?? 'The core terms governing the use of the Rockmould website, portal, and client-facing digital services.' }}"
        image="images/project-4.jpg" />

    <section class="section-padding">
        <div class="container-luxury">
            <div class="mx-auto max-w-3xl">
                <div class="luxury-card space-y-8">
                    <div>
                        <h2 class="mb-3 font-display text-xl font-semibold">{{ $legal['terms_s1_title'] ?? 'Website Use' }}</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">{{ $legal['terms_s1_body'] ?? 'Content on this website is provided for general information, project marketing, and relationship management purposes. Public site materials do not constitute a final commercial offer unless confirmed by Rockmould in writing.' }}</p>
                    </div>
                    <div class="border-t border-border pt-6">
                        <h2 class="mb-3 font-display text-xl font-semibold">{{ $legal['terms_s2_title'] ?? 'Client Portal Access' }}</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">{{ $legal['terms_s2_body'] ?? 'Client portal access is intended only for recognized clients and approved users. Misuse of project data, documents, media, or support channels is prohibited.' }}</p>
                    </div>
                    <div class="border-t border-border pt-6">
                        <h2 class="mb-3 font-display text-xl font-semibold">{{ $legal['terms_s3_title'] ?? 'Project Information' }}</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">{{ $legal['terms_s3_body'] ?? 'Project timelines, visuals, payment schedules, and availability may change. Official transaction documents, agreements, and internal approvals govern final commercial terms.' }}</p>
                    </div>
                    <div class="border-t border-border pt-6">
                        <h2 class="mb-3 font-display text-xl font-semibold">{{ $legal['terms_s4_title'] ?? 'Acceptance of Terms' }}</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">{{ $legal['terms_s4_body'] ?? 'By using this website or portal, you agree to engage with Rockmould lawfully and in good faith. For questions about these terms, contact us.' }}</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('contact') }}" class="luxury-btn-outline">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

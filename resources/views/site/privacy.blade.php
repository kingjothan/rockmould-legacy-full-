<x-layouts.public :company="$company" title="Privacy Policy | Rockmould Limited">
    <x-hero title="Privacy Policy" subtitle="Legal"
        description="{{ $legal['privacy_hero_description'] ?? 'How Rockmould collects, uses, and protects information shared across the website, portal, and client interactions.' }}"
        image="images/project-1.jpg" />

    <section class="section-padding">
        <div class="container-luxury">
            <div class="mx-auto max-w-3xl">
                <div class="luxury-card space-y-8">
                    <div>
                        <h2 class="mb-3 font-display text-xl font-semibold">{{ $legal['privacy_s1_title'] ?? 'Data Collection' }}</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">{{ $legal['privacy_s1_body'] ?? 'Rockmould Limited collects contact details, project inquiry information, client portal records, uploaded documents, and support communications to deliver services and maintain operational records.' }}</p>
                    </div>
                    <div class="border-t border-border pt-6">
                        <h2 class="mb-3 font-display text-xl font-semibold">{{ $legal['privacy_s2_title'] ?? 'How We Use Your Data' }}</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">{{ $legal['privacy_s2_body'] ?? 'Information provided through contact forms, partnerships, newsletters, KYC requests, or the client portal may be used for relationship management, project reporting, account review, and compliance workflows.' }}</p>
                    </div>
                    <div class="border-t border-border pt-6">
                        <h2 class="mb-3 font-display text-xl font-semibold">{{ $legal['privacy_s3_title'] ?? 'Data Protection' }}</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">{{ $legal['privacy_s3_body'] ?? 'We do not sell personal information. Access to sensitive records is restricted to authorized Rockmould personnel who require it for legitimate operational, finance, project, or support purposes.' }}</p>
                    </div>
                    <div class="border-t border-border pt-6">
                        <h2 class="mb-3 font-display text-xl font-semibold">{{ $legal['privacy_s4_title'] ?? 'Contact & Corrections' }}</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">{{ $legal['privacy_s4_body'] ?? 'If you need corrections, account review, or record clarification, contact us or use the portal support channel.' }}</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('contact') }}" class="luxury-btn-outline">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

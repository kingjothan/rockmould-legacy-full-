<x-layouts.public :company="$company" title="Privacy Policy | Rockmould Limited">
    <x-hero title="Privacy Policy" subtitle="Legal" description="How Rockmould collects, uses, and protects information shared across the website, portal, and client interactions." image="images/project-1.jpg" />

    <section class="section-padding">
        <div class="container-luxury">
            <div class="mx-auto max-w-3xl">
                <div class="luxury-card space-y-8">
                    <div>
                        <h2 class="mb-3 font-display text-xl font-semibold">Data Collection</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">Rockmould Limited collects contact details, project inquiry information, client portal records, uploaded documents, and support communications to deliver services and maintain operational records.</p>
                    </div>
                    <div class="border-t border-border pt-6">
                        <h2 class="mb-3 font-display text-xl font-semibold">How We Use Your Data</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">Information provided through contact forms, partnerships, newsletters, KYC requests, or the client portal may be used for relationship management, project reporting, account review, and compliance workflows.</p>
                    </div>
                    <div class="border-t border-border pt-6">
                        <h2 class="mb-3 font-display text-xl font-semibold">Data Protection</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">We do not sell personal information. Access to sensitive records is restricted to authorized Rockmould personnel who require it for legitimate operational, finance, project, or support purposes.</p>
                    </div>
                    <div class="border-t border-border pt-6">
                        <h2 class="mb-3 font-display text-xl font-semibold">Contact & Corrections</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">If you need corrections, account review, or record clarification, contact <a href="mailto:{{ $company['email'] }}" class="text-primary hover:underline">{{ $company['email'] }}</a> or use the portal support channel.</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('contact') }}" class="luxury-btn-outline">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

<x-layouts.public :company="$company" title="Terms of Service | Rockmould Limited">
    <x-hero title="Terms of Service" subtitle="Legal" description="The core terms governing the use of the Rockmould website, portal, and client-facing digital services." image="images/project-4.jpg" />

    <section class="section-padding">
        <div class="container-luxury">
            <div class="mx-auto max-w-3xl">
                <div class="luxury-card space-y-8">
                    <div>
                        <h2 class="mb-3 font-display text-xl font-semibold">Website Use</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">Content on this website is provided for general information, project marketing, and relationship management purposes. Public site materials do not constitute a final commercial offer unless confirmed by Rockmould in writing.</p>
                    </div>
                    <div class="border-t border-border pt-6">
                        <h2 class="mb-3 font-display text-xl font-semibold">Client Portal Access</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">Client portal access is intended only for recognized clients and approved users. Misuse of project data, documents, media, or support channels is prohibited.</p>
                    </div>
                    <div class="border-t border-border pt-6">
                        <h2 class="mb-3 font-display text-xl font-semibold">Project Information</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">Project timelines, visuals, payment schedules, and availability may change. Official transaction documents, agreements, and internal approvals govern final commercial terms.</p>
                    </div>
                    <div class="border-t border-border pt-6">
                        <h2 class="mb-3 font-display text-xl font-semibold">Acceptance of Terms</h2>
                        <p class="text-sm leading-relaxed text-muted-foreground">By using this website or portal, you agree to engage with Rockmould lawfully and in good faith. For questions about these terms, contact <a href="mailto:{{ $company['email'] }}" class="text-primary hover:underline">{{ $company['email'] }}</a>.</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('contact') }}" class="luxury-btn-outline">Contact Us</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

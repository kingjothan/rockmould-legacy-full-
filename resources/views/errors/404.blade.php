<x-layouts.public :company="\App\Support\SiteData::company()" title="Page Not Found | Rockmould Limited">
    <section class="section-padding">
        <div class="container-luxury">
            <div class="luxury-card mx-auto max-w-3xl text-center">
                <p class="text-xs uppercase tracking-[0.3em] text-primary">404</p>
                <h1 class="mt-4 font-display text-5xl">Page Not Found</h1>
                <p class="mx-auto mt-6 max-w-2xl text-muted-foreground">The page you requested is unavailable or may have moved. Use the links below to continue exploring Rockmould.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('home') }}" class="luxury-btn-primary">Go Home</a>
                    <a href="{{ route('projects.index') }}" class="luxury-btn-outline">View Projects</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

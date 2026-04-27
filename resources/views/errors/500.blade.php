<x-layouts.public :company="\App\Support\SiteData::company()" title="Server Error | Rockmould Limited">
    <section class="section-padding">
        <div class="container-luxury">
            <div class="luxury-card mx-auto max-w-3xl text-center">
                <p class="text-xs uppercase tracking-[0.3em] text-primary">500</p>
                <h1 class="mt-4 font-display text-5xl">Something Went Wrong</h1>
                <p class="mx-auto mt-6 max-w-2xl text-muted-foreground">A server error interrupted the request. Please try again shortly or contact Rockmould support if the issue persists.</p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('home') }}" class="luxury-btn-primary">Go Home</a>
                    <a href="{{ route('contact') }}" class="luxury-btn-outline">Contact Support</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

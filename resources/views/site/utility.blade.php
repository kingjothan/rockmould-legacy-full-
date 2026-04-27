<x-layouts.public :company="$company" :title="$title.' | Rockmould Limited'">
    <section class="flex min-h-[70vh] items-center section-padding">
        <div class="container-luxury">
            <div class="luxury-card mx-auto max-w-2xl text-center">
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
                    <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                </div>
                <span class="section-label">Notice</span>
                <h1 class="mt-2 font-display text-4xl font-semibold">{{ $title }}</h1>
                <p class="mx-auto mt-5 max-w-xl leading-relaxed text-muted-foreground">{{ $message }}</p>
                <div class="mt-8 flex flex-wrap justify-center gap-4">
                    <a href="{{ route('home') }}" class="luxury-btn-primary">Return Home</a>
                    <a href="{{ route('contact') }}" class="luxury-btn-outline">Contact Rockmould</a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.public>

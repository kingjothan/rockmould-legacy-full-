@props([
    'title',
    'subtitle' => null,
    'description' => null,
    'image' => 'images/project-1.jpg',
    'height' => 'large',
    'align' => 'center',
    'overlay' => true,
    'titleSize' => null,
    'descriptionSize' => null,
])

@php
    $heightClass = match ($height) {
        'full' => 'min-h-screen',
        'medium' => 'min-h-[50vh]',
        default => 'min-h-[80vh]',
    };
    $alignClass = $align === 'left' ? 'hero-left text-left' : 'hero-center';
    $imageUrl = $image ? \App\Support\AssetPath::url($image) : null;
    $isVideo = \App\Support\AssetPath::isVideo($image);
@endphp

<section class="hero-shell {{ $heightClass }} {{ $alignClass }} flex items-center" data-hero-shell>
    @if ($imageUrl)
        @if ($isVideo)
            <video autoplay muted loop playsinline preload="auto" class="hero-media" data-hero-media data-background-video aria-hidden="true">
                <source src="{{ $imageUrl }}">
            </video>
        @else
            <img src="{{ $imageUrl }}" alt="{{ $title }}" class="hero-media" data-hero-media aria-hidden="true">
        @endif
        @if ($overlay)
            <div class="hero-soft-overlay"></div>
        @endif
        <div class="hero-overlay"></div>
    @else
        <div class="absolute inset-0 bg-gradient-to-b from-secondary/50 to-background"></div>
    @endif
    <div class="container-luxury relative z-10 py-32">
        <div class="hero-copy" data-hero-copy>
            @if ($subtitle)
                <p class="section-label mb-6">{{ $subtitle }}</p>
            @endif
            <h1 class="public-hero-title font-display font-bold leading-[1.1] {{ $titleSize ?? 'text-4xl md:text-5xl lg:text-7xl' }}">{{ $title }}</h1>
            @if ($description)
                <p class="mx-auto mt-6 max-w-2xl leading-relaxed text-muted-foreground {{ $descriptionSize ?? 'text-lg md:text-xl' }} {{ $align === 'left' ? 'mx-0' : '' }}">{{ $description }}</p>
            @endif
            @if (trim($slot))
                <div class="mt-10 flex flex-wrap gap-4 {{ $align === 'left' ? 'justify-start' : 'justify-center' }}">
                    {{ $slot }}
                </div>
            @endif
        </div>
    </div>
</section>

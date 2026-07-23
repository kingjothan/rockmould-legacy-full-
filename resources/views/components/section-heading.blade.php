@props([
    'label' => null,
    'title',
    'description' => null,
    'align' => 'center',
    'titleSize' => null,
    'labelSize' => null,
    'descriptionSize' => null,
])

<div class="mb-12 {{ $align === 'left' ? 'text-left' : 'text-center' }}">
    @if ($label)
        @if ($labelSize)
            <p class="mb-4 inline-block uppercase text-primary" style="font-family:var(--font-subheader);font-size:2rem;font-weight:800;letter-spacing:0.18em;line-height:1.2;">{{ $label }}</p>
        @else
            <p class="section-label mb-4">{{ $label }}</p>
        @endif
    @endif
    <h2 class="font-display font-semibold leading-tight {{ $titleSize ?? 'text-3xl md:text-4xl lg:text-5xl' }}">{{ $title }}</h2>
    @if ($description)
        <p class="mx-auto mt-5 max-w-3xl leading-relaxed text-muted-foreground {{ $descriptionSize ?? 'text-base md:text-lg' }} {{ $align === 'left' ? 'mx-0 max-w-2xl' : '' }}">{{ $description }}</p>
    @endif
</div>

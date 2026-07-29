@props(['value', 'label', 'suffix' => ''])

<div style="padding:1.5rem;text-align:center;width:100%">
    <p class="font-display text-4xl font-bold text-foreground md:text-5xl">
        {{ $value }}@if($suffix)<span class="text-primary">{{ $suffix }}</span>@endif
    </p>
    <p class="mt-2 text-sm uppercase tracking-[0.15em] text-muted-foreground">{{ $label }}</p>
</div>

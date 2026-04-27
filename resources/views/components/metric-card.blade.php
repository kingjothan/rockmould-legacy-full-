@props(['value', 'label', 'suffix' => ''])

<div class="p-6 text-center">
    <p class="font-display text-4xl font-bold text-foreground md:text-5xl">
        {{ $value }}@if($suffix)<span class="text-primary">{{ $suffix }}</span>@endif
    </p>
    <p class="mt-2 text-sm uppercase tracking-[0.15em] text-muted-foreground">{{ $label }}</p>
</div>

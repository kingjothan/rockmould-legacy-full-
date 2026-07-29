@props(['value', 'label', 'suffix' => ''])

<div class="stat-card" style="padding:2rem 1rem;text-align:center;width:100%">
    <p class="font-display font-bold text-foreground" style="font-size:clamp(2.2rem,4.5vw,3.5rem);line-height:1;letter-spacing:-0.01em">
        {{ $value }}@if($suffix)<span class="text-primary">{{ $suffix }}</span>@endif
    </p>
    <div style="width:2rem;height:2px;background:hsl(var(--primary));margin:0.75rem auto 0.875rem"></div>
    <p style="font-size:0.625rem;letter-spacing:0.2em;text-transform:uppercase;color:hsl(var(--muted-foreground));font-weight:500">{{ $label }}</p>
</div>

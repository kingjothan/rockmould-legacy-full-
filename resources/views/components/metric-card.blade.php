@props(['value', 'label', 'suffix' => ''])

<div class="rk-stat" data-rk-stat
     style="text-align:center;padding:4rem 2rem;width:100%;position:relative;cursor:default;
            transition:background 0.4s ease;opacity:0;transform:translateY(24px)">
    <p class="font-display rk-stat-number" data-val="{{ $value }}"
       style="font-size:clamp(1rem,3.5vw,2.1rem);line-height:0.88;font-weight:800;letter-spacing:-0.03em;
              color:hsl(var(--foreground));margin:0;transition:color 0.3s ease">
        {{ $value }}@if($suffix)<span style="color:hsl(var(--primary))">{{ $suffix }}</span>@endif
    </p>
    <div class="rk-stat-line"
         style="width:0;height:1px;margin:1.75rem auto 1.5rem;
                background:linear-gradient(90deg,transparent,hsl(var(--primary)),transparent);
                transition:width 0.7s cubic-bezier(0.16,1,0.3,1)"></div>
    <p style="font-size:0.5875rem;letter-spacing:0.28em;text-transform:uppercase;
              color:hsl(var(--muted-foreground));font-weight:600;margin:0;line-height:1.6;
              transition:color 0.3s ease">{{ $label }}</p>
</div>

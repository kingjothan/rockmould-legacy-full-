<x-layouts.public :company="$company" title="About Rockmould Limited" description="Learn about Rockmould Limited — our founding story, mission, values, and the leadership behind Lagos's most distinguished property developments.">
    <x-hero
        :title="$about['hero_title']"
        :subtitle="$about['hero_subtitle']"
        :description="$about['hero_description']"
        :image="$about['hero_media_path']"
        height="large"
        :title-size="$about['font_sizes']['hero_title'] ?? null"
        :description-size="$about['font_sizes']['hero_description'] ?? null"
    />

    <section class="section-padding">
        <div class="container-luxury grid grid-cols-1 gap-16 lg:grid-cols-2 lg:items-center">
            <div class="reveal-left">
                <x-section-heading :label="$about['story_label'] ?? 'Our Story'" :title="$about['story_title']" align="left" :title-size="$about['font_sizes']['story_title'] ?? null" />
                <div class="space-y-4 leading-relaxed text-muted-foreground">
                    @foreach ($about['story'] as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            </div>
            <div class="reveal-right img-zoom overflow-hidden rounded-md">
                <x-media :path="$about['story_image'] ?? null" fallback="images/project-2.jpg" alt="Rockmould excellence" class="aspect-[4/3] w-full object-cover" />
            </div>
        </div>
    </section>

    <section class="section-padding bg-card">
        <div class="container-luxury grid grid-cols-1 gap-12 md:grid-cols-2">
            <div class="luxury-card reveal-left">
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                    <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="font-display text-2xl font-semibold">{{ $about['vision_title'] ?? 'Our Vision' }}</h3>
                <p class="mt-4 leading-relaxed text-muted-foreground">{{ $about['vision'] }}</p>
            </div>
            <div class="luxury-card reveal-right">
                <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                    <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                </div>
                <h3 class="font-display text-2xl font-semibold">{{ $about['mission_title'] ?? 'Our Mission' }}</h3>
                <p class="mt-4 leading-relaxed text-muted-foreground">{{ $about['mission'] }}</p>
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container-luxury">
            <x-section-heading :label="$about['values_label'] ?? 'Core Values'" :title="$about['values_title'] ?? 'The Pillars of Our Identity'" :title-size="$about['font_sizes']['values_title'] ?? null" />
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3 lg:grid-cols-5">
                @foreach ($about['values'] as $value)
                    <div class="luxury-card text-center reveal">
                        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10">
                            <svg class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                        </div>
                        <h3 class="font-display text-lg font-semibold">{{ $value['title'] }}</h3>
                        <p class="mt-2 text-sm text-muted-foreground">{{ $value['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-padding bg-card">
        <div class="container-luxury">
            <x-section-heading :label="$about['team_label'] ?? 'Leadership'" :title="$about['team_title'] ?? 'The Team Behind the Vision'" :title-size="$about['font_sizes']['team_title'] ?? null" />
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                @foreach ($team as $member)
                    <div class="luxury-card text-center reveal">
                        @if ($member->image_path)
                            <div class="mx-auto mb-6 aspect-[4/5] max-w-[220px] overflow-hidden rounded-md border border-border bg-muted">
                                <x-media :path="$member->image_path" alt="{{ $member->name }}" class="h-full w-full object-cover" :controls="true" :loop="false" />
                            </div>
                        @else
                            <div class="avatar-lg mx-auto mb-6" style="background: linear-gradient(135deg, hsl(var(--primary)/0.2), hsl(var(--primary)/0.05));">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                        @endif
                        <h3 class="font-display text-lg font-semibold">{{ $member->name }}</h3>
                        <p class="mt-1 text-xs uppercase tracking-[0.15em] text-primary">{{ $member->role }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="section-padding">
        <div class="container-luxury">
            <x-section-heading :label="$about['timeline_label'] ?? 'Our Journey'" :title="$about['timeline_title'] ?? 'Milestones of Excellence'" :title-size="$about['font_sizes']['timeline_title'] ?? null" />
            <div class="mx-auto max-w-3xl">
                @foreach ($timeline as $entry)
                    <div class="timeline-entry reveal">
                        <div class="timeline-dot"></div>
                        <span class="text-xs font-medium uppercase tracking-wider text-primary">{{ $entry->year }}</span>
                        <h3 class="mt-1 font-display text-lg font-semibold">{{ $entry->title }}</h3>
                        <p class="mt-1 text-sm text-muted-foreground">{{ $entry->description }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Metrics Section --}}
    <style>
        .rk-stats-row { display:grid; grid-template-columns:repeat(4,1fr); }
        .rk-stat + .rk-stat { border-left:1px solid hsl(var(--border)/0.8); }
        .rk-stat:hover { background:radial-gradient(ellipse at 50% 100%,hsl(var(--primary)/0.06) 0%,transparent 70%) !important; }
        .rk-stat:hover .rk-stat-number { color:hsl(var(--primary)) !important; }
        @media(max-width:767px){
            .rk-stats-row { grid-template-columns:repeat(2,1fr); }
            .rk-stat:nth-child(2n+1) { border-left:none; }
            .rk-stat:nth-child(n+3) { border-top:1px solid hsl(var(--border)/0.8); }
        }
    </style>
    <section class="relative overflow-hidden" style="background:hsl(var(--card));padding:6rem 0">
        <div class="absolute" style="top:-120px;left:-80px;width:500px;height:500px;background:radial-gradient(ellipse at center,hsl(var(--primary)/0.07) 0%,transparent 65%);pointer-events:none" aria-hidden="true"></div>
        <div class="absolute" style="bottom:-120px;right:-80px;width:500px;height:500px;background:radial-gradient(ellipse at center,hsl(var(--primary)/0.06) 0%,transparent 65%);pointer-events:none" aria-hidden="true"></div>
        <div class="absolute top-0 inset-x-0" style="height:1px;background:linear-gradient(90deg,transparent 0%,hsl(var(--primary)/0.5) 25%,hsl(var(--primary)/0.5) 75%,transparent 100%)" aria-hidden="true"></div>
        <div class="absolute bottom-0 inset-x-0" style="height:1px;background:linear-gradient(90deg,transparent 0%,hsl(var(--primary)/0.5) 25%,hsl(var(--primary)/0.5) 75%,transparent 100%)" aria-hidden="true"></div>

        <div class="container-luxury relative">
            <div style="display:flex;align-items:center;justify-content:center;gap:1.25rem;margin-bottom:4.5rem">
                <div style="flex:1;max-width:100px;height:1px;background:linear-gradient(to right,transparent,hsl(var(--primary)/0.65))"></div>
                <span style="font-size:0.5875rem;letter-spacing:0.38em;text-transform:uppercase;color:hsl(var(--primary));font-weight:700;white-space:nowrap">{{ $about['metrics_label'] ?? 'By the Numbers' }}</span>
                <div style="flex:1;max-width:100px;height:1px;background:linear-gradient(to left,transparent,hsl(var(--primary)/0.65))"></div>
            </div>

            <div class="rk-stats-row" id="rk-stats-about">
                @foreach (($about['metrics'] ?? $metrics) as $metric)
                    <x-metric-card :value="$metric['value']" :label="$metric['label']" />
                @endforeach
            </div>
        </div>

        <script>
        (function(){
            function rkAnimateStats(rowId) {
                var row = document.getElementById(rowId);
                if (!row) return;
                var stats = row.querySelectorAll('[data-rk-stat]');
                var done = false;
                function parseVal(str) {
                    var m = str.match(/^([^\d]*)([0-9][0-9,]*)([^]*)$/);
                    if (!m) return null;
                    return { pre: m[1], num: parseInt(m[2].replace(/,/g,'')), suf: m[3] };
                }
                function fmtNum(n) { return n >= 1000 ? n.toLocaleString('en') : String(n); }
                function countUp(el, parsed, delay) {
                    var duration = 1400, start = null;
                    setTimeout(function(){
                        requestAnimationFrame(function tick(ts){
                            if (!start) start = ts;
                            var prog = Math.min((ts - start) / duration, 1);
                            var ease = 1 - Math.pow(1 - prog, 3);
                            el.textContent = parsed.pre + fmtNum(Math.round(ease * parsed.num)) + parsed.suf;
                            if (prog < 1) requestAnimationFrame(tick);
                        });
                    }, delay);
                }
                function run() {
                    if (done) return; done = true;
                    stats.forEach(function(stat, i) {
                        var delay = i * 120;
                        setTimeout(function(){ stat.style.opacity='1'; stat.style.transform='translateY(0)'; stat.style.transition='opacity 0.55s ease,transform 0.55s cubic-bezier(0.16,1,0.3,1),background 0.4s ease'; }, delay);
                        var line = stat.querySelector('.rk-stat-line');
                        if (line) setTimeout(function(){ line.style.width='2.5rem'; }, delay + 300);
                        var numEl = stat.querySelector('.rk-stat-number');
                        if (numEl) { var p = parseVal(numEl.getAttribute('data-val')||numEl.textContent.trim()); if(p) countUp(numEl, p, delay+200); }
                    });
                }
                var io = new IntersectionObserver(function(e){ if(e[0].isIntersecting){run();io.disconnect();} },{threshold:0.25});
                io.observe(row);
            }
            document.addEventListener('DOMContentLoaded', function(){ rkAnimateStats('rk-stats-about'); });
        })();
        </script>
    </section>

    <section class="section-padding">
        <div class="container-luxury text-center">
            <h2 class="font-display text-3xl font-semibold md:text-4xl">{{ $about['cta_title'] ?? 'Partner with Excellence' }}</h2>
            <p class="mx-auto mt-4 max-w-xl text-muted-foreground">
                {{ $about['cta_description'] ?? "Whether you're an investor, client, or partner, let's create something extraordinary together." }}
            </p>
            <div class="mt-10 flex flex-wrap justify-center gap-4">
                <a href="{{ route('consultations.create') }}" class="luxury-btn-primary">{{ $about['cta_primary'] ?? 'Schedule Consultation' }}
                    <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                <a href="{{ route('projects.index') }}" class="luxury-btn-outline">{{ $about['cta_secondary'] ?? 'View Projects' }}</a>
            </div>
        </div>
    </section>
</x-layouts.public>

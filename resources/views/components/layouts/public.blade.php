<x-layouts.base :title="$title ?? 'Rockmould Limited'" :description="$description ?? null">
    @php
        $formatScale = function ($value) {
            $number = is_numeric($value) ? (float) $value : 1.0;
            $number = max(0.7, min(2.5, $number));

            return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
        };
        $publicCopyScale = $formatScale($company['public_copy_scale'] ?? '1');
        $publicHeadingScale = $formatScale($company['public_heading_scale'] ?? '1');
    @endphp

    {{-- Splash Screen --}}
    <div id="rk-splash" aria-hidden="true" style="
        position:fixed;inset:0;z-index:99999;
        background:#000;
        display:flex;align-items:center;justify-content:center;
        flex-direction:column;
        transition:opacity 0.9s cubic-bezier(0.4,0,0.2,1),transform 0.9s cubic-bezier(0.4,0,0.2,1);
    ">
        {{-- Ripple rings --}}
        <div id="rk-splash-ring1" style="
            position:absolute;width:180px;height:180px;border-radius:50%;
            border:1px solid rgba(255,255,255,0.06);
            opacity:0;transform:scale(0.6);
            transition:opacity 0.6s ease,transform 1.8s cubic-bezier(0.16,1,0.3,1);
        "></div>
        <div id="rk-splash-ring2" style="
            position:absolute;width:280px;height:280px;border-radius:50%;
            border:1px solid rgba(255,255,255,0.04);
            opacity:0;transform:scale(0.6);
            transition:opacity 0.6s ease,transform 2.2s cubic-bezier(0.16,1,0.3,1);
        "></div>

        {{-- Logo --}}
        <div id="rk-splash-logo" style="
            position:relative;z-index:2;
            opacity:0;transform:scale(0.82) translateY(10px);
            transition:opacity 0.8s ease,transform 1s cubic-bezier(0.16,1,0.3,1);
        ">
            <img src="{{ asset('images/rockmould_logo_white.png') }}"
                 alt="Rockmould Limited"
                 style="width:200px;max-width:55vw;display:block;filter:drop-shadow(0 0 40px rgba(255,255,255,0.08))">
        </div>

        {{-- Gold accent line --}}
        <div id="rk-splash-line" style="
            position:relative;z-index:2;
            width:0;height:1px;margin-top:2rem;
            background:linear-gradient(90deg,transparent,#c9a96e,transparent);
            transition:width 0.9s cubic-bezier(0.16,1,0.3,1);
        "></div>

        {{-- Tagline --}}
        <p id="rk-splash-tag" style="
            position:relative;z-index:2;
            margin-top:1.25rem;
            font-size:0.52rem;letter-spacing:0.38em;text-transform:uppercase;
            color:rgba(255,255,255,0);font-weight:500;
            font-family:sans-serif;
            transition:color 0.8s ease 0.4s;
        ">Building Legacies That Endure</p>
    </div>

    <style>
        #rk-splash.rk-splash-exit {
            opacity:0 !important;
            transform:scale(1.04) !important;
            pointer-events:none;
        }
        body.rk-splash-active { overflow:hidden; }
    </style>

    <script>
    (function(){
        var splash = document.getElementById('rk-splash');

        document.body.classList.add('rk-splash-active');

        requestAnimationFrame(function(){
            setTimeout(function(){
                var logo = document.getElementById('rk-splash-logo');
                var line = document.getElementById('rk-splash-line');
                var tag  = document.getElementById('rk-splash-tag');
                var r1   = document.getElementById('rk-splash-ring1');
                var r2   = document.getElementById('rk-splash-ring2');

                if(logo){ logo.style.opacity='1'; logo.style.transform='scale(1) translateY(0)'; }
                if(r1){ r1.style.opacity='1'; r1.style.transform='scale(1)'; }
                if(r2){ r2.style.opacity='1'; r2.style.transform='scale(1)'; }
                setTimeout(function(){
                    if(line) line.style.width='120px';
                    if(tag)  tag.style.color='rgba(201,169,110,0.75)';
                }, 500);
            }, 80);

            setTimeout(function(){
                if(splash){ splash.classList.add('rk-splash-exit'); }
                document.body.classList.remove('rk-splash-active');
                setTimeout(function(){ if(splash) splash.style.display='none'; }, 950);
            }, 1800);
        });
    })();
    </script>

    <div class="public-site-shell min-h-screen" style="--public-copy-scale: {{ $publicCopyScale }}; --public-heading-scale: {{ $publicHeadingScale }};">
        @include('partials.public-header', ['company' => $company])
        <main class="public-main">{{ $slot }}</main>
        @include('partials.footer', ['company' => $company])
    </div>
</x-layouts.base>

@php
    $navItems = \App\Support\SiteData::publicNavigation();
@endphp

<header class="site-header" data-site-header>
    <div class="container-luxury flex h-20 items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="site-brand shrink-0">
            <x-brand-mark class="site-brand-mark" />
        </a>

        <nav class="site-header-nav hidden lg:flex">
            @foreach ($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route'] ?? '');
                @endphp
                <div class="group relative">
                    <a href="{{ isset($item['href']) ? $item['href'] : route($item['route']) }}"
                        class="site-nav-link {{ $isActive ? 'is-active' : '' }}">
                        {{ $item['label'] }}
                        @if (! empty($item['children']))
                            <x-portal-icon name="chevron-right" class="h-3 w-3 rotate-90 opacity-50 transition-transform duration-300 group-hover:translate-x-0.5 group-hover:rotate-[135deg]" />
                        @endif
                    </a>

                    @if (! empty($item['children']))
                        <div class="site-dropdown-panel invisible absolute left-0 top-full mt-2 w-80 opacity-0 group-hover:visible group-hover:opacity-100">
                            <div class="rounded-2xl border border-border bg-card/95 p-2 backdrop-blur-xl" style="box-shadow: 0 18px 50px hsl(0 0% 0% / 0.35);">
                                @foreach ($item['children'] as $child)
                                    <a href="{{ $child['href'] }}" class="site-dropdown-link">
                                        <span>{{ $child['label'] }}</span>
                                        <x-portal-icon name="arrow-right" class="h-3.5 w-3.5 text-primary/60" />
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </nav>

        <div class="site-header-actions hidden lg:flex">
            <a href="{{ route('portal.login') }}" class="luxury-btn-outline px-5 py-2">Client Portal</a>
            <a href="{{ route('contact') }}" class="luxury-btn-primary px-5 py-2">Get in Touch</a>
        </div>

        <button type="button"
                class="mobile-menu-trigger lg:hidden"
                aria-label="Open navigation"
                aria-expanded="false"
                data-mobile-open
                data-mobile-trigger>
            <x-portal-icon name="menu" class="h-6 w-6" />
        </button>
    </div>

</header>

<div class="mobile-drawer lg:hidden" data-mobile-drawer data-open="false">
    <button type="button" class="mobile-drawer-overlay" aria-label="Close navigation" data-mobile-close></button>

    <div class="mobile-drawer-panel">
        <div class="mobile-drawer-header border-b border-border px-6 py-5" data-mobile-stagger style="transition-delay: 40ms;">
            <div class="flex items-center justify-between gap-4">
                <span class="mobile-drawer-title">Menu</span>
                <button type="button"
                        class="mobile-drawer-close"
                        aria-label="Close navigation"
                        data-mobile-close>
                    <x-portal-icon name="x" class="h-5 w-5" />
                </button>
            </div>
        </div>

        <nav class="mobile-drawer-nav flex-1 overflow-y-auto py-4">
            @foreach ($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route'] ?? '');
                @endphp

                <div class="mobile-nav-group" data-mobile-stagger style="transition-delay: {{ min(($loop->iteration + 1) * 45, 280) }}ms;">
                    <a href="{{ isset($item['href']) ? $item['href'] : route($item['route']) }}"
                        class="mobile-nav-link {{ $isActive ? 'is-active' : '' }}"
                        data-mobile-close>
                        {{ $item['label'] }}
                    </a>

                    @if (! empty($item['children']))
                        @foreach ($item['children'] as $child)
                            <a href="{{ $child['href'] }}" class="mobile-subnav-link" data-mobile-close>
                                {{ $child['label'] }}
                            </a>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </nav>

        <div class="mobile-drawer-footer space-y-3 border-t border-border px-4 py-5" data-mobile-stagger style="transition-delay: 420ms;">
            <a href="{{ route('portal.login') }}" class="luxury-btn-outline w-full justify-center" data-mobile-close>Client Portal</a>
            <a href="{{ route('contact') }}" class="luxury-btn-primary w-full justify-center" data-mobile-close>Get in Touch</a>
        </div>
    </div>
</div>

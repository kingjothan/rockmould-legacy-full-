@php
    $navItems = \App\Support\SiteData::publicNavigation();
@endphp

<header class="site-header" data-site-header>
    <div class="container-luxury flex h-20 items-center justify-between gap-6">
        <a href="{{ route('home') }}" class="site-brand shrink-0">
            <x-brand-mark class="site-brand-mark" />
        </a>

        <div class="site-header-actions ml-auto hidden lg:flex">
            <a href="{{ route('portal.login') }}" class="luxury-btn-outline px-5 py-2">Client Portal</a>
            <a href="{{ route('contact') }}" class="luxury-btn-primary px-5 py-2">Get in Touch</a>
        </div>

        <button type="button"
                class="mobile-menu-trigger site-menu-trigger"
                aria-label="Open navigation"
                aria-expanded="false"
                data-mobile-open
                data-mobile-trigger>
            <x-portal-icon name="menu" class="h-6 w-6" />
        </button>
    </div>

</header>

<div class="mobile-drawer" data-mobile-drawer data-open="false">
    <button type="button" class="mobile-drawer-overlay" aria-label="Close navigation" data-mobile-close></button>

    <div class="mobile-drawer-panel">
        <div class="mobile-drawer-header border-b border-border px-6 py-6" data-mobile-stagger style="transition-delay: 40ms;">
            <a href="{{ route('home') }}" class="flex items-center gap-3" data-mobile-close>
                <x-brand-mark class="h-10 w-auto" />
            </a>
            <button type="button"
                    class="mobile-drawer-close"
                    aria-label="Close navigation"
                    data-mobile-close>
                <x-portal-icon name="x" class="h-5 w-5" />
            </button>
        </div>

        <div class="px-6 pt-6" data-mobile-stagger style="transition-delay: 80ms;">
            <p class="section-label mb-2">Navigation</p>
            <h2 class="font-display text-3xl leading-tight">Explore Rockmould</h2>
        </div>

        <nav class="mobile-drawer-nav flex-1 overflow-y-auto py-6">
            @foreach ($navItems as $item)
                @php
                    $isActive = request()->routeIs($item['route'] ?? '');
                    $hasChildren = ! empty($item['children']);
                    $subnavId = 'mobile-subnav-'.$loop->index;
                @endphp

                <div class="mobile-nav-group" data-mobile-stagger style="transition-delay: {{ min(($loop->iteration + 1) * 45, 280) }}ms;">
                    @if ($hasChildren)
                        <button type="button"
                                class="mobile-nav-link mobile-nav-expand {{ $isActive ? 'is-active' : '' }}"
                                aria-expanded="false"
                                aria-controls="{{ $subnavId }}"
                                data-mobile-expand
                                data-target="{{ $subnavId }}">
                            <span>{{ $item['label'] }}</span>
                            <x-portal-icon name="chevron-right" class="mobile-nav-chevron" />
                        </button>

                        <div id="{{ $subnavId }}" class="mobile-subnav" data-mobile-subnav data-open="false">
                            <div class="mobile-subnav-inner">
                                <a href="{{ isset($item['href']) ? $item['href'] : route($item['route']) }}"
                                    class="mobile-subnav-link"
                                    data-mobile-close>
                                    All {{ $item['label'] }}
                                </a>

                                @foreach ($item['children'] as $child)
                                    <a href="{{ $child['href'] }}" class="mobile-subnav-link" data-mobile-close>
                                        {{ $child['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ isset($item['href']) ? $item['href'] : route($item['route']) }}"
                            class="mobile-nav-link {{ $isActive ? 'is-active' : '' }}"
                            data-mobile-close>
                            {{ $item['label'] }}
                        </a>
                    @endif
                </div>
            @endforeach
        </nav>

        <div class="mobile-drawer-footer space-y-3 border-t border-border px-6 py-6" data-mobile-stagger style="transition-delay: 420ms;">
            <a href="{{ route('portal.login') }}" class="luxury-btn-outline w-full justify-center" data-mobile-close>Client Portal</a>
            <a href="{{ route('contact') }}" class="luxury-btn-primary w-full justify-center" data-mobile-close>Get in Touch</a>
        </div>
    </div>
</div>

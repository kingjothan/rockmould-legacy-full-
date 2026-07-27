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
            <a href="https://wa.me/message/UMPBJLP3KWF5L1" target="_blank" rel="noopener noreferrer" aria-label="Chat on WhatsApp" class="luxury-btn-outline flex items-center px-3 py-2 border-[#25D366]/40 text-[#25D366] hover:border-[#25D366] hover:bg-[#25D366]/10">
                <svg class="h-6 w-6 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.122 1.532 5.855L.058 23.447a.5.5 0 00.613.605l5.748-1.505A11.944 11.944 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.9a9.893 9.893 0 01-5.031-1.374l-.36-.214-3.733.977.998-3.645-.235-.374A9.862 9.862 0 012.1 12C2.1 6.533 6.533 2.1 12 2.1S21.9 6.533 21.9 12 17.467 21.9 12 21.9z"/></svg>
            </a>
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
            <a href="https://wa.me/message/UMPBJLP3KWF5L1" target="_blank" rel="noopener noreferrer" class="flex w-full items-center justify-center gap-2 rounded-md border border-[#25D366]/40 bg-[#25D366]/10 px-4 py-2.5 text-sm font-medium text-[#25D366] transition hover:border-[#25D366] hover:bg-[#25D366]/20">
                <svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.122 1.532 5.855L.058 23.447a.5.5 0 00.613.605l5.748-1.505A11.944 11.944 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.9a9.893 9.893 0 01-5.031-1.374l-.36-.214-3.733.977.998-3.645-.235-.374A9.862 9.862 0 012.1 12C2.1 6.533 6.533 2.1 12 2.1S21.9 6.533 21.9 12 17.467 21.9 12 21.9z"/></svg>
                Chat on WhatsApp
            </a>
            <a href="{{ route('contact') }}" class="luxury-btn-primary w-full justify-center" data-mobile-close>Get in Touch</a>
        </div>
    </div>
</div>

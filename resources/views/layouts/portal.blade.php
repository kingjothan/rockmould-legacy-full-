@php($navItems = \App\Support\SiteData::portalNavigation())

<x-layouts.base :title="$title ?? 'Client Portal | Rockmould Limited'">
    <style>
        @media (min-width: 768px) {
            #portal-sidebar {
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                bottom: 0 !important;
                width: 16rem !important;
                z-index: 40 !important;
                display: flex !important;
                flex-direction: column !important;
            }
        }
    </style>
    <div class="min-h-screen bg-background">

        {{-- Desktop sidebar --}}
        <aside id="portal-sidebar" class="hidden border-r border-border bg-card md:flex" style="position:fixed;top:0;left:0;bottom:0;width:16rem;z-index:40;flex-direction:column;">
            {{-- Brand --}}
            <div class="border-b border-border p-5">
                <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-3">
                    <x-brand-mark class="h-10 w-auto" alt="Rockmould Limited" />
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 py-5">
                @foreach ($navItems as $item)
                    @php($isActive = request()->routeIs($item['route']))
                    <a href="{{ route($item['route']) }}"
                       class="admin-nav-link {{ $isActive ? 'is-active' : '' }}">
                        <x-portal-icon :name="$item['icon'] ?? 'circle'" class="h-4 w-4 shrink-0 {{ $isActive ? 'text-primary' : 'text-muted-foreground/70' }}" />
                        <span>{{ $item['label'] }}</span>
                        @if ($isActive)
                            <span class="ml-auto h-1.5 w-1.5 rounded-full bg-primary"></span>
                        @endif
                    </a>
                @endforeach
            </nav>

            {{-- Logout pinned at bottom --}}
            <div class="border-t border-border px-3 py-3">
                <form action="{{ route('portal.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-nav-link w-full">
                        <x-portal-icon name="log-out" class="h-4 w-4 shrink-0 text-muted-foreground/70" />
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Mobile sidebar drawer --}}
        <div class="sidebar-drawer md:hidden" data-sidebar-drawer data-open="false">
            <button type="button" class="sidebar-drawer-overlay" data-sidebar-close></button>
            <div class="sidebar-drawer-panel">
                <div class="flex items-center justify-between border-b border-border px-5 py-4">
                    <div class="flex items-center gap-3">
                        <x-brand-mark class="h-8 w-auto" alt="Rockmould Limited" />
                    </div>
                    <button type="button" class="flex h-8 w-8 items-center justify-center rounded-md text-muted-foreground hover:bg-muted/50 hover:text-foreground" data-sidebar-close>
                        <x-portal-icon name="x" class="h-4 w-4" />
                    </button>
                </div>
                <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 py-4">
                    @foreach ($navItems as $item)
                        @php($isActive = request()->routeIs($item['route']))
                        <a href="{{ route($item['route']) }}"
                           class="admin-nav-link {{ $isActive ? 'is-active' : '' }}"
                           data-sidebar-close>
                            <x-portal-icon :name="$item['icon'] ?? 'circle'" class="h-4 w-4 shrink-0 {{ $isActive ? 'text-primary' : 'text-muted-foreground/70' }}" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
                <div class="border-t border-border px-3 py-3">
                    <div class="mb-1 flex items-center gap-3 rounded-md bg-muted/30 px-3 py-2">
                        <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary/15 text-[11px] font-semibold text-primary">
                            {{ strtoupper(substr(auth('portal')->user()->name ?? 'C', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <div class="truncate text-xs font-medium text-foreground">{{ auth('portal')->user()->name }}</div>
                            <div class="truncate text-[10px] text-muted-foreground">{{ auth('portal')->user()->phone }}</div>
                        </div>
                    </div>
                    <form action="{{ route('portal.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="admin-nav-link w-full">
                            <x-portal-icon name="log-out" class="h-4 w-4 shrink-0 text-muted-foreground/70" />
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Main content --}}
        <div class="flex min-h-screen flex-col md:pl-64">
            {{-- Top bar --}}
            <header class="sticky top-0 z-30 border-b border-border bg-card/95 backdrop-blur-md">
                <div class="flex items-center gap-4 px-5 py-3.5">
                    {{-- Mobile hamburger --}}
                    <button type="button"
                            class="flex h-9 w-9 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-muted/50 hover:text-foreground md:hidden"
                            aria-label="Open sidebar"
                            data-sidebar-open>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h10M4 17h16"></path>
                        </svg>
                    </button>

                    <div class="min-w-0 flex-1">
                        <p class="text-[10px] uppercase tracking-[0.2em] text-muted-foreground">Rockmould Portal</p>
                        <h1 class="truncate font-display text-xl font-semibold text-foreground">{{ $pageHeading ?? 'Client Dashboard' }}</h1>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden text-right sm:block">
                            <div class="text-sm font-medium text-foreground">{{ auth('portal')->user()->name }}</div>
                            <div class="text-[11px] text-muted-foreground">{{ auth('portal')->user()->phone }}</div>
                        </div>
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary/15 text-xs font-semibold text-primary">
                            {{ strtoupper(substr(auth('portal')->user()->name ?? 'C', 0, 1)) }}
                        </div>
                        <form action="{{ route('portal.logout') }}" method="POST">
                            @csrf
                            <button type="submit" title="Sign Out" class="flex h-8 w-8 items-center justify-center rounded-md text-muted-foreground transition-colors hover:bg-muted/50 hover:text-foreground">
                                <x-portal-icon name="log-out" class="h-4 w-4" />
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-5 md:p-7">{{ $slot }}</main>
        </div>
    </div>
</x-layouts.base>

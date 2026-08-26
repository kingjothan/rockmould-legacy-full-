@php
    $user = auth()->user();
    $firstName = str($user->name)->before(' ');
    $lastInitial = strtoupper(substr((string) str($user->name)->after(' '), 0, 1));
    $initials = collect(explode(' ', (string) $user->name))
        ->filter()
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->take(2)
        ->implode('');
    $navItems = collect(\App\Support\SiteData::portalNavigation())
        ->map(function (array $item) {
            $item['icon'] = match ($item['route']) {
                'portal.dashboard' => 'layout-dashboard',
                'portal.projects.index' => 'folder-kanban',
                'portal.documents' => 'file-text',
                'portal.media' => 'image',
                'portal.balance' => 'receipt',
                'portal.updates' => 'clock',
                'portal.profile.show' => 'user',
                'portal.support' => 'help-circle',
                default => 'circle',
            };

            return $item;
        })
        ->all();
    $formatScale = function ($value) {
        $number = is_numeric($value) ? (float) $value : 1.0;
        $number = max(0.7, min(2.5, $number));

        return rtrim(rtrim(number_format($number, 2, '.', ''), '0'), '.');
    };
    $portalCopyScale = $formatScale($company['portal_copy_scale'] ?? '1');
    $portalHeadingScale = $formatScale($company['portal_heading_scale'] ?? '1');
@endphp

<x-layouts.base :title="$title ?? 'Client Portal | Rockmould Limited'" body-class="portal-shell">
    <div class="portal-shell" data-portal-shell data-collapsed="false" style="--shell-copy-scale: {{ $portalCopyScale }}; --shell-heading-scale: {{ $portalHeadingScale }};">
        <aside class="portal-sidebar" data-portal-sidebar>
            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-border bg-card px-4 py-5">
                <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2" data-portal-brand-link>
                    <x-brand-mark class="h-8 w-auto" alt="Rockmould Limited" />
                </a>
                <button type="button" class="portal-sidebar-toggle rounded-md p-1 text-muted-foreground hover:bg-muted hover:text-foreground transition-colors" aria-label="Collapse portal navigation" data-portal-toggle>
                    <x-portal-icon name="chevron-left" class="h-4 w-4" />
                </button>
            </div>

            {{-- User card --}}
            <div class="border-b border-border px-4 py-4" data-portal-user-card>
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-primary/10 text-xs font-semibold text-primary ring-1 ring-primary/20">
                        {{ $initials ?: 'RM' }}
                    </div>
                    <div class="min-w-0 flex-1" data-portal-label>
                        <p class="truncate text-sm font-medium text-foreground">{{ $user->name }}</p>
                        <p class="truncate text-[10px] text-muted-foreground">Client Portal</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4">
                <p class="mb-2 px-3 text-[9px] font-semibold uppercase tracking-[0.2em] text-muted-foreground/60" data-portal-label>Navigation</p>
                <div class="space-y-0.5">
                @foreach ($navItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        class="portal-nav-link {{ request()->routeIs($item['route']) ? 'is-active' : '' }}"
                    >
                        <x-portal-icon :name="$item['icon']" class="portal-nav-icon h-4 w-4 flex-shrink-0" />
                        <span data-portal-label>{{ $item['label'] }}</span>
                    </a>
                @endforeach
                </div>
            </nav>

            <div class="sticky bottom-0 border-t border-border bg-card px-3 py-3">
                <form action="{{ route('portal.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="portal-nav-link w-full text-muted-foreground hover:bg-destructive/10 hover:text-destructive" data-portal-logout>
                        <x-portal-icon name="log-out" class="h-4 w-4 flex-shrink-0" />
                        <span data-portal-label>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col md:overflow-hidden">
            <header class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b border-border bg-card/80 px-6 backdrop-blur-sm">
                <button class="text-muted-foreground md:hidden" type="button" aria-label="Open portal navigation" data-mobile-open>
                    <x-portal-icon name="menu" class="h-5 w-5" />
                </button>
                <div>
                    <p class="text-xs text-muted-foreground">Welcome back,</p>
                    <h1 class="text-sm font-semibold text-foreground">{{ $firstName }}{{ $lastInitial ? ' '.$lastInitial.'.' : '' }}</h1>
                </div>
                <div class="ml-auto flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-xs font-semibold text-primary ring-1 ring-primary/20">
                        {{ $initials ?: 'RM' }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                {{ $slot }}
            </main>
        </div>

        <div class="mobile-drawer md:hidden" data-mobile-drawer data-open="false">
            <button type="button" class="mobile-drawer-overlay" aria-label="Close portal navigation" data-mobile-close></button>
            <aside class="mobile-drawer-panel">
                <div class="flex items-center justify-between border-b border-border px-5 py-5">
                    <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2" data-mobile-close>
                        <x-brand-mark class="h-8 w-auto" alt="Rockmould Limited" />
                    </a>
                    <button type="button" aria-label="Close portal navigation" class="rounded-md p-1 text-muted-foreground hover:bg-muted" data-mobile-close>
                        <x-portal-icon name="x" class="h-5 w-5" />
                    </button>
                </div>

                {{-- User card --}}
                <div class="border-b border-border px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-semibold text-primary ring-1 ring-primary/20">
                            {{ $initials ?: 'RM' }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-foreground">{{ $user->name }}</p>
                            <p class="text-[10px] text-muted-foreground">Client Portal</p>
                        </div>
                    </div>
                </div>

                <nav class="flex-1 overflow-y-auto px-3 py-4">
                    <p class="mb-2 px-3 text-[9px] font-semibold uppercase tracking-[0.2em] text-muted-foreground/60">Navigation</p>
                    <div class="space-y-0.5">
                    @foreach ($navItems as $item)
                        <a
                            href="{{ route($item['route']) }}"
                            class="portal-nav-link {{ request()->routeIs($item['route']) ? 'is-active' : '' }}"
                            data-mobile-close
                        >
                            <x-portal-icon :name="$item['icon']" class="h-4 w-4 flex-shrink-0" />
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                    </div>
                </nav>

                <div class="border-t border-border px-3 py-3">
                    <form action="{{ route('portal.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="portal-nav-link w-full text-muted-foreground hover:bg-destructive/10 hover:text-destructive">
                            <x-portal-icon name="log-out" class="h-4 w-4 flex-shrink-0" />
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</x-layouts.base>

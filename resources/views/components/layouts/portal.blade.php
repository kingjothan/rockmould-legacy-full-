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
    $portalCopyScale = in_array((string) ($company['portal_copy_scale'] ?? '1'), ['1', '1.08', '1.16', '1.24'], true)
        ? (string) $company['portal_copy_scale']
        : '1';
    $portalHeadingScale = in_array((string) ($company['portal_heading_scale'] ?? '1'), ['1', '1.08', '1.16', '1.24'], true)
        ? (string) $company['portal_heading_scale']
        : '1';
@endphp

<x-layouts.base :title="$title ?? 'Client Portal | Rockmould Limited'">
    <div class="portal-shell" data-portal-shell data-collapsed="false" style="--shell-copy-scale: {{ $portalCopyScale }}; --shell-heading-scale: {{ $portalHeadingScale }};">
        <aside class="portal-sidebar" data-portal-sidebar>
            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-border bg-card p-4">
                <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2" data-portal-brand-link>
                    <x-brand-mark class="h-8 w-auto" alt="Rockmould Limited" />
                </a>
                <button type="button" class="portal-sidebar-toggle" aria-label="Collapse portal navigation" data-portal-toggle>
                    <x-portal-icon name="chevron-left" class="h-4 w-4" />
                </button>
            </div>

            <nav class="flex-1 space-y-1 py-4">
                @foreach ($navItems as $item)
                    <a
                        href="{{ route($item['route']) }}"
                        class="portal-nav-link {{ request()->routeIs($item['route']) ? 'is-active' : '' }}"
                    >
                        <x-portal-icon :name="$item['icon']" class="portal-nav-icon h-4 w-4 flex-shrink-0" />
                        <span data-portal-label>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-border p-4">
                <form action="{{ route('portal.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="portal-nav-link w-full px-0 text-muted-foreground hover:text-destructive" data-portal-logout>
                        <x-portal-icon name="log-out" class="h-4 w-4 flex-shrink-0" />
                        <span data-portal-label>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 flex h-14 items-center gap-4 border-b border-border bg-card/50 px-6 backdrop-blur-sm">
                <button class="text-muted-foreground md:hidden" type="button" aria-label="Open portal navigation" data-mobile-open>
                    <x-portal-icon name="menu" class="h-5 w-5" />
                </button>
                <h1 class="text-sm font-medium text-foreground">Welcome, {{ $firstName }}{{ $lastInitial ? ' '.$lastInitial.'.' : '' }}</h1>
                <div class="ml-auto flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-muted text-xs font-medium">
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
                <div class="flex items-center justify-between border-b border-border p-4">
                    <a href="{{ route('portal.dashboard') }}" class="flex items-center gap-2">
                        <x-brand-mark class="h-8 w-auto" alt="Rockmould Limited" />
                    </a>
                    <button type="button" aria-label="Close portal navigation" class="text-muted-foreground" data-mobile-close>
                        <x-portal-icon name="x" class="h-4 w-4" />
                    </button>
                </div>

                <nav class="space-y-1 py-4">
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
                </nav>

                <div class="border-t border-border p-4">
                    <form action="{{ route('portal.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="portal-nav-link w-full px-0 text-muted-foreground hover:text-destructive">
                            <x-portal-icon name="log-out" class="h-4 w-4 flex-shrink-0" />
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</x-layouts.base>

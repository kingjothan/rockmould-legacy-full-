@props([
    'company',
    'pageHeading' => 'Admin Dashboard',
])

@php
    $navGroups = \App\Support\SiteData::adminNavigation();
    $fullName = trim((string) auth()->user()->name);
    $nameParts = preg_split('/\s+/', $fullName, -1, PREG_SPLIT_NO_EMPTY) ?: [$fullName];
    $firstName = $nameParts[0] ?? $fullName;
    $lastInitial = count($nameParts) > 1 ? strtoupper(substr(end($nameParts), 0, 1)).'.' : '';
    $adminCopyScale = in_array((string) ($company['admin_copy_scale'] ?? '1'), ['1', '1.08', '1.16', '1.24'], true)
        ? (string) $company['admin_copy_scale']
        : '1';
    $adminHeadingScale = in_array((string) ($company['admin_heading_scale'] ?? '1'), ['1', '1.08', '1.16', '1.24'], true)
        ? (string) $company['admin_heading_scale']
        : '1';
@endphp

<x-layouts.base :title="$title ?? 'Admin CMS | Rockmould Limited'">
    <div class="admin-shell" data-admin-shell data-collapsed="false" style="--shell-copy-scale: {{ $adminCopyScale }}; --shell-heading-scale: {{ $adminHeadingScale }};">
        <aside class="admin-sidebar">
            <div class="sticky top-0 z-10 border-b border-border bg-card p-4">
                <div class="flex items-center justify-between gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3" data-admin-brand-link>
                        <x-brand-mark class="h-9 w-auto shrink-0" alt="Rockmould Limited" />
                    </a>
                    <button type="button" class="admin-sidebar-toggle" aria-label="Toggle admin sidebar" data-admin-toggle>
                        <x-portal-icon name="chevron-left" class="h-4 w-4" />
                    </button>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4">
                @foreach ($navGroups as $label => $links)
                    <div class="mb-5">
                        <p class="px-3 pb-2 text-[10px] uppercase tracking-[0.22em] text-muted-foreground" data-admin-section>{{ $label }}</p>
                        <div class="space-y-1">
                            @foreach ($links as $item)
                                @php
                                    $baseRoute = \Illuminate\Support\Str::replaceEnd('.index', '', $item['route']);
                                    $baseRoute = \Illuminate\Support\Str::replaceEnd('.edit', '', $baseRoute);
                                    $isActive = request()->routeIs($item['route']) || request()->routeIs($baseRoute.'.*');
                                @endphp
                                <a href="{{ route($item['route']) }}" class="admin-nav-link {{ $isActive ? 'is-active' : '' }}">
                                    <x-portal-icon :name="$item['icon']" class="h-4 w-4 shrink-0" />
                                    <span data-admin-label>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </nav>

            <div class="sticky bottom-0 border-t border-border bg-card p-3">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-nav-link w-full text-left text-muted-foreground hover:text-destructive" data-admin-logout>
                        <x-portal-icon name="log-out" class="h-4 w-4 shrink-0" />
                        <span data-admin-label>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 flex h-16 items-center gap-4 border-b border-border bg-card/80 px-4 backdrop-blur-sm md:px-6">
                <button class="text-muted-foreground md:hidden" type="button" aria-label="Open admin navigation" data-sidebar-open>
                    <x-portal-icon name="menu" class="h-5 w-5" />
                </button>

                <div class="min-w-0">
                    <p class="truncate text-sm font-medium text-foreground">{{ $pageHeading }}</p>
                    <p class="hidden text-xs text-muted-foreground md:block" data-admin-welcome>Welcome back, {{ trim($firstName.' '.$lastInitial) }}.</p>
                </div>

                <div class="ml-auto flex items-center gap-3">
                    <button type="button" class="relative flex h-9 w-9 items-center justify-center rounded-full border border-border text-muted-foreground transition-colors hover:border-primary/30 hover:text-foreground">
                        <x-portal-icon name="bell" class="h-4 w-4" />
                        <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-destructive"></span>
                    </button>
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-primary text-xs font-bold text-primary-foreground">
                        {{ strtoupper(substr($fullName, 0, 1)) }}
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                {{ $slot }}
            </main>
        </div>

        <div class="sidebar-drawer" data-sidebar-drawer data-open="false">
            <button type="button" class="sidebar-drawer-overlay" aria-label="Close admin navigation" data-sidebar-close></button>
            <aside class="sidebar-drawer-panel overflow-y-auto">
                <div class="sticky top-0 flex items-center justify-between border-b border-border bg-card p-4">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3" data-sidebar-close>
                        <x-brand-mark class="h-9 w-auto" alt="Rockmould Limited" />
                    </a>
                    <button type="button" aria-label="Close admin navigation" class="text-muted-foreground" data-sidebar-close>
                        <x-portal-icon name="x" class="h-5 w-5" />
                    </button>
                </div>

                <nav class="px-3 py-4">
                    @foreach ($navGroups as $label => $links)
                        <div class="mb-5">
                            <p class="px-3 pb-2 text-[10px] uppercase tracking-[0.22em] text-muted-foreground">{{ $label }}</p>
                            <div class="space-y-1">
                                @foreach ($links as $item)
                                    @php
                                        $baseRoute = \Illuminate\Support\Str::replaceEnd('.index', '', $item['route']);
                                        $baseRoute = \Illuminate\Support\Str::replaceEnd('.edit', '', $baseRoute);
                                        $isActive = request()->routeIs($item['route']) || request()->routeIs($baseRoute.'.*');
                                    @endphp
                                    <a href="{{ route($item['route']) }}" class="admin-nav-link {{ $isActive ? 'is-active' : '' }}" data-sidebar-close>
                                        <x-portal-icon :name="$item['icon']" class="h-4 w-4 shrink-0" />
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </nav>

                <div class="sticky bottom-0 border-t border-border bg-card p-3">
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="admin-nav-link w-full text-left text-muted-foreground hover:text-destructive">
                            <x-portal-icon name="log-out" class="h-4 w-4 shrink-0" />
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </aside>
        </div>
    </div>
</x-layouts.base>

<x-layouts.admin :company="$company" page-heading="Dashboard Overview">
    <div class="mx-auto max-w-7xl space-y-8">

        {{-- Page heading --}}
        <div>
            <h1 class="mb-1 font-display text-3xl font-semibold">Dashboard Overview</h1>
            <p class="text-sm text-muted-foreground">Welcome back, {{ str(auth()->user()->name)->before(' ') }}. Here's what's happening across the system.</p>
        </div>

        {{-- Stat cards --}}
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Clients --}}
            <div class="stat-card">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sky-500/10">
                        <x-portal-icon name="users" class="h-5 w-5 text-sky-400" />
                    </div>
                    <span class="text-[10px] uppercase tracking-[0.15em] text-emerald-400">Live</span>
                </div>
                <p class="font-display text-3xl font-bold text-foreground">{{ $stats['clients'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Total Clients</p>
                <div class="mt-3 flex items-center gap-1 text-xs text-sky-400">
                    <x-portal-icon name="trending-up" class="h-3 w-3" />
                    Active portfolio
                </div>
            </div>

            {{-- Projects --}}
            <div class="stat-card">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                        <x-portal-icon name="folder-kanban" class="h-5 w-5 text-primary" />
                    </div>
                    <span class="text-[10px] uppercase tracking-[0.15em] text-primary">Active</span>
                </div>
                <p class="font-display text-3xl font-bold text-foreground">{{ $stats['projects'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Active Projects</p>
                <div class="mt-3 flex items-center gap-1 text-xs text-primary">
                    <x-portal-icon name="refresh-cw" class="h-3 w-3" />
                    Delivery in progress
                </div>
            </div>

            {{-- KYC --}}
            <div class="stat-card">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-500/10">
                        <x-portal-icon name="shield-check" class="h-5 w-5 text-amber-400" />
                    </div>
                    @if ($stats['kyc'] > 0)
                        <span class="flex h-5 w-5 items-center justify-center rounded-full bg-amber-500/20 text-[10px] font-bold text-amber-400">{{ $stats['kyc'] }}</span>
                    @endif
                </div>
                <p class="font-display text-3xl font-bold text-foreground">{{ $stats['kyc'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Pending KYC</p>
                <div class="mt-3 flex items-center gap-1 text-xs text-amber-400">
                    <x-portal-icon name="alert-circle" class="h-3 w-3" />
                    {{ $stats['kyc'] > 0 ? 'Needs review' : 'All clear' }}
                </div>
            </div>

            {{-- Outstanding --}}
            <div class="stat-card">
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-500/10">
                        <x-portal-icon name="credit-card" class="h-5 w-5 text-emerald-400" />
                    </div>
                </div>
                <p class="font-display text-2xl font-bold text-foreground">{{ number_format($stats['outstanding']) }}</p>
                <p class="mt-0.5 text-[10px] uppercase tracking-wider text-muted-foreground">NGN Outstanding</p>
                <p class="mt-1 text-sm text-muted-foreground">Pending Payments</p>
                <div class="mt-3 flex items-center gap-1 text-xs text-emerald-400">
                    <x-portal-icon name="receipt" class="h-3 w-3" />
                    Across all clients
                </div>
            </div>
        </div>

        {{-- Activity + Quick Actions --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Recent activity --}}
            <div class="luxury-card-flat overflow-hidden p-0 lg:col-span-2">
                <div class="flex items-center justify-between border-b border-border px-6 py-4">
                    <div class="flex items-center gap-2">
                        <x-portal-icon name="activity" class="h-4 w-4 text-primary" />
                        <h2 class="font-display text-base font-semibold">Recent Activity</h2>
                    </div>
                    @if (\App\Support\SiteData::adminRouteAllowed(auth()->user()->role, 'admin.activity.index'))
                        <a href="{{ route('admin.activity.index') }}" class="flex items-center gap-1 text-xs text-primary hover:underline">
                            View All
                            <x-portal-icon name="chevron-right" class="h-3 w-3" />
                        </a>
                    @endif
                </div>
                <div class="divide-y divide-border/60">
                    @forelse ($recentActivity as $log)
                        <div class="flex items-start gap-4 px-6 py-4">
                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-muted">
                                <x-portal-icon name="activity" class="h-3.5 w-3.5 text-muted-foreground" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm leading-snug text-foreground">{{ $log->description }}</p>
                                <p class="mt-1 text-xs text-muted-foreground">{{ optional($log->created_at)->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center gap-3 px-6 py-8 text-sm text-muted-foreground">
                            <x-portal-icon name="info" class="h-4 w-4 shrink-0" />
                            No activity has been logged yet.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Quick actions --}}
            <div class="luxury-card-flat p-0 overflow-hidden">
                <div class="flex items-center gap-2 border-b border-border px-6 py-4">
                    <x-portal-icon name="plus" class="h-4 w-4 text-primary" />
                    <h2 class="font-display text-base font-semibold">Quick Actions</h2>
                </div>
                <div class="p-3">
                    @php
                        $quickActions = [
                            ['route' => 'admin.consultations.index', 'icon' => 'calendar', 'label' => 'Review Consultations', 'color' => 'text-primary', 'bg' => 'bg-primary/10'],
                            ['route' => 'admin.clients.index', 'icon' => 'users', 'label' => 'Add New Client', 'color' => 'text-sky-400', 'bg' => 'bg-sky-500/10'],
                            ['route' => 'admin.projects.index', 'icon' => 'folder-kanban', 'label' => 'Create Project', 'color' => 'text-primary', 'bg' => 'bg-primary/10'],
                            ['route' => 'admin.documents.index', 'icon' => 'file-text', 'label' => 'Upload Documents', 'color' => 'text-violet-400', 'bg' => 'bg-violet-500/10'],
                            ['route' => 'admin.payments.index', 'icon' => 'credit-card', 'label' => 'Record Payment', 'color' => 'text-emerald-400', 'bg' => 'bg-emerald-500/10'],
                            ['route' => 'admin.media.index', 'icon' => 'image', 'label' => 'Upload Media', 'color' => 'text-rose-400', 'bg' => 'bg-rose-500/10'],
                            ['route' => 'admin.cms.homepage', 'icon' => 'home', 'label' => 'Edit Website', 'color' => 'text-amber-400', 'bg' => 'bg-amber-500/10'],
                        ];
                        $quickActions = array_filter(
                            $quickActions,
                            fn ($action) => \App\Support\SiteData::adminRouteAllowed(auth()->user()->role, $action['route'])
                        );
                    @endphp
                    @forelse ($quickActions as $action)
                        <a href="{{ route($action['route']) }}"
                           class="flex items-center gap-3 rounded-md p-3 transition-colors hover:bg-muted/40 group">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg {{ $action['bg'] }}">
                                <x-portal-icon :name="$action['icon']" class="h-4 w-4 {{ $action['color'] }}" />
                            </div>
                            <span class="flex-1 text-sm text-muted-foreground transition-colors group-hover:text-foreground">{{ $action['label'] }}</span>
                            <x-portal-icon name="chevron-right" class="h-3.5 w-3.5 text-muted-foreground/40 transition-colors group-hover:text-primary" />
                        </a>
                    @empty
                        <div class="px-3 py-6 text-sm text-muted-foreground">No quick actions are available for your role.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

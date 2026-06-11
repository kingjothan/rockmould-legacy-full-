@php
    $statusCounts = [
        'pending' => $requests->where('status', 'pending')->count(),
        'approved' => $requests->where('status', 'approved')->count(),
        'needs_update' => $requests->where('status', 'needs_update')->count(),
        'rejected' => $requests->where('status', 'rejected')->count(),
    ];
@endphp

<x-layouts.admin :company="$company" page-heading="KYC Requests">
    <div class="space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.24em] text-primary/70">Client Verification</p>
                <h1 class="mt-2 font-display text-3xl font-semibold">Review, approve, and convert portal access requests.</h1>
                <p class="mt-2 max-w-2xl text-sm text-muted-foreground">KYC records submitted from the client portal land here for manual review before access is granted.</p>
            </div>
            <form method="GET" class="grid gap-3 sm:grid-cols-[220px_auto]">
                <select name="status" class="input-shell">
                    <option value="">All statuses</option>
                    @foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'needs_update' => 'Needs Update'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="luxury-btn-outline min-w-max">Filter Requests</button>
            </form>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-amber-500/10">
                    <x-portal-icon name="clock" class="h-5 w-5 text-amber-400" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $statusCounts['pending'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Pending review</p>
            </div>
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-500/10">
                    <x-portal-icon name="check-circle" class="h-5 w-5 text-emerald-400" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $statusCounts['approved'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Approved requests</p>
            </div>
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-sky-500/10">
                    <x-portal-icon name="refresh-cw" class="h-5 w-5 text-sky-400" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $statusCounts['needs_update'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Awaiting client update</p>
            </div>
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-destructive/10">
                    <x-portal-icon name="alert-circle" class="h-5 w-5 text-destructive" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $statusCounts['rejected'] }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Rejected submissions</p>
            </div>
        </div>

        <div class="space-y-6">
            @forelse ($requests as $requestItem)
                <div class="luxury-card-flat overflow-hidden p-0">
                    <div class="flex flex-wrap items-start justify-between gap-4 px-6 py-5">
                        <div class="flex items-start gap-4">
                            <div class="avatar">{{ strtoupper(substr($requestItem->full_name, 0, 1)) }}</div>
                            <div>
                                <h2 class="font-display text-xl font-semibold">{{ $requestItem->full_name }}</h2>
                                <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-muted-foreground">
                                    <span>{{ $requestItem->phone }}</span>
                                    @if ($requestItem->email)
                                        <span aria-hidden="true" class="text-primary/60">&middot;</span>
                                        <span>{{ $requestItem->email }}</span>
                                    @endif
                                    @if ($requestItem->created_at)
                                        <span aria-hidden="true" class="text-primary/60">&middot;</span>
                                        <span>{{ $requestItem->created_at->format('M j, Y') }}</span>
                                    @endif
                                </div>
                                @if ($requestItem->residential_address)
                                    <p class="mt-2 text-sm text-muted-foreground">{{ $requestItem->residential_address }}</p>
                                @endif
                            </div>
                        </div>
                        <x-status-badge :value="$requestItem->status" />
                    </div>

                    <div class="grid gap-6 border-t border-border px-6 py-5 lg:grid-cols-[1.1fr_0.9fr]">
                        <div class="space-y-4">
                            @if ($requestItem->project || $requestItem->project_of_interest)
                                <div>
                                    <p class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">Project of interest</p>
                                    <p class="mt-2 text-sm text-foreground">{{ $requestItem->project?->title ?? $requestItem->project_of_interest }}</p>
                                    @if ($requestItem->project)
                                        <p class="mt-1 text-xs text-muted-foreground">
                                            {{ $requestItem->project->location }}
                                            @if ($requestItem->project->status)
                                                <span aria-hidden="true" class="text-primary/60">&middot;</span>
                                                {{ str($requestItem->project->status)->replace('_', ' ')->title() }}
                                            @endif
                                        </p>
                                    @endif
                                </div>
                            @endif

                            @if ($requestItem->note)
                                <div>
                                    <p class="text-[10px] uppercase tracking-[0.18em] text-muted-foreground">Client note</p>
                                    <p class="mt-2 text-sm text-muted-foreground">{{ $requestItem->note }}</p>
                                </div>
                            @endif

                            @if ($requestItem->admin_note)
                                <div class="rounded-md border border-primary/20 bg-primary/5 px-4 py-3">
                                    <p class="text-[10px] uppercase tracking-[0.18em] text-primary">Internal note</p>
                                    <p class="mt-2 text-sm text-muted-foreground">{{ $requestItem->admin_note }}</p>
                                </div>
                            @endif

                            @if ($requestItem->reviewer)
                                <p class="text-xs text-muted-foreground">
                                    Reviewed by {{ $requestItem->reviewer->name }}{{ $requestItem->reviewed_at ? ' on '.$requestItem->reviewed_at->format('M j, Y g:i A') : '' }}
                                </p>
                            @endif
                        </div>

                        <div class="space-y-4">
                            @if ($requestItem->identification_url)
                                <a href="{{ $requestItem->identification_url }}" target="_blank" class="flex items-center justify-between rounded-md border border-border bg-muted/20 px-4 py-3 text-sm text-muted-foreground transition-colors hover:border-primary/30 hover:text-foreground">
                                    <span>Open uploaded identification</span>
                                    <x-portal-icon name="external-link" class="h-4 w-4 text-primary" />
                                </a>
                            @endif

                            <form action="{{ route('admin.kyc.update', $requestItem) }}" method="POST" class="grid gap-4 rounded-md border border-border bg-muted/10 p-4">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="admin-label">Status</label>
                                    <select name="status" class="input-shell">
                                        @foreach (['pending', 'approved', 'rejected', 'needs_update'] as $status)
                                            <option value="{{ $status }}" @selected($requestItem->status === $status)>{{ str($status)->replace('_', ' ')->title() }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="admin-label">Internal Note</label>
                                    <input name="admin_note" value="{{ $requestItem->admin_note }}" placeholder="Add an internal note..." class="input-shell">
                                </div>
                                <button class="luxury-btn-primary justify-center">Update Request</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <x-portal-icon name="shield-check" class="h-7 w-7" />
                    </div>
                    <h3 class="font-display text-xl font-semibold">No KYC Requests</h3>
                    <p class="mt-2 text-sm text-muted-foreground">All KYC submissions have been processed.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.admin>

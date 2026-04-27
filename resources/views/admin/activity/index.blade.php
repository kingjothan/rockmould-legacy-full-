<x-layouts.admin :company="$company" page-heading="Activity Log">
    <div class="mx-auto max-w-5xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.24em] text-primary/70">System Timeline</p>
                <h1 class="mt-2 font-display text-3xl font-semibold">Everything happening across the platform.</h1>
            </div>
            <div class="rounded-sm border border-border bg-card px-4 py-3 text-xs uppercase tracking-[0.18em] text-muted-foreground">
                {{ $logs->count() }} recorded events
            </div>
        </div>

        <div class="luxury-card-flat overflow-hidden p-0">
            <div class="border-b border-border px-6 py-4">
                <div class="flex items-center gap-2">
                    <x-portal-icon name="activity" class="h-4 w-4 text-primary" />
                    <h2 class="font-display text-base font-semibold">Recent Activity</h2>
                </div>
            </div>

            <div class="divide-y divide-border/60">
                @forelse ($logs as $log)
                    <div class="flex items-start gap-4 px-6 py-5">
                        <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-muted">
                            <x-portal-icon name="activity" class="h-4 w-4 text-muted-foreground" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm leading-snug text-foreground">{{ $log->description }}</p>
                                <span class="rounded-full bg-primary/10 px-2 py-0.5 text-[10px] uppercase tracking-[0.18em] text-primary">{{ str($log->action)->replace('.', ' ') }}</span>
                            </div>
                            <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-muted-foreground">
                                @if ($log->user)
                                    <span>{{ $log->user->name }}</span>
                                    <span aria-hidden="true" class="text-primary/60">&middot;</span>
                                @endif
                                <span>{{ optional($log->created_at)->format('M j, Y g:i A') }}</span>
                                @if ($log->created_at)
                                    <span aria-hidden="true" class="text-primary/60">&middot;</span>
                                    <span>{{ $log->created_at->diffForHumans() }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center gap-3 px-6 py-12 text-sm text-muted-foreground">
                        <x-portal-icon name="info" class="h-4 w-4 shrink-0" />
                        No activity has been logged yet.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>

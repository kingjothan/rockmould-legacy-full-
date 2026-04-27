@php
    $allSubmissions = collect([
        ...$contacts->map(fn ($item) => ['type' => 'Contact', 'icon' => 'mail', 'record' => $item]),
        ...$partnerships->map(fn ($item) => ['type' => 'Partnership', 'icon' => 'briefcase', 'record' => $item]),
        ...$supportTickets->map(fn ($item) => ['type' => 'Support', 'icon' => 'help-circle', 'record' => $item]),
        ...$newsletters->map(fn ($item) => ['type' => 'Newsletter', 'icon' => 'bell', 'record' => $item]),
    ])->sortByDesc(fn ($item) => $item['record']->created_at ?? $item['record']->subscribed_at ?? now())->values();

    $newThisWeek = $allSubmissions->filter(function ($item) {
        $date = $item['record']->created_at ?? $item['record']->subscribed_at;

        return $date && $date->greaterThanOrEqualTo(now()->subDays(7));
    })->count();

    $responseCount = $supportTickets->where('status', '!=', 'open')->count();
@endphp

<x-layouts.admin :company="$company" page-heading="Form Submissions">
    <div class="space-y-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-[10px] uppercase tracking-[0.24em] text-primary/70">Lead Inbox</p>
                <h1 class="mt-2 font-display text-3xl font-semibold">Public enquiries, support, and subscription activity.</h1>
                <p class="mt-2 max-w-2xl text-sm text-muted-foreground">This page consolidates every incoming website and portal submission so the team can follow up without missing context.</p>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                    <x-portal-icon name="inbox" class="h-5 w-5 text-primary" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $allSubmissions->count() }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Total submissions</p>
            </div>
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-sky-500/10">
                    <x-portal-icon name="mail" class="h-5 w-5 text-sky-400" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $contacts->count() + $partnerships->count() }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Sales and partnership leads</p>
            </div>
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-amber-500/10">
                    <x-portal-icon name="clock" class="h-5 w-5 text-amber-400" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $newThisWeek }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Received in the last 7 days</p>
            </div>
            <div class="stat-card">
                <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-500/10">
                    <x-portal-icon name="check-circle" class="h-5 w-5 text-emerald-400" />
                </div>
                <p class="font-display text-3xl font-bold">{{ $responseCount }}</p>
                <p class="mt-1 text-sm text-muted-foreground">Support items progressed</p>
            </div>
        </div>

        <div class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr]">
            <div class="luxury-card-flat overflow-hidden p-0">
                <div class="border-b border-border px-6 py-4">
                    <div class="flex items-center gap-2">
                        <x-portal-icon name="activity" class="h-4 w-4 text-primary" />
                        <h2 class="font-display text-base font-semibold">Recent Submission Feed</h2>
                    </div>
                </div>
                <div class="divide-y divide-border/60">
                    @forelse ($allSubmissions->take(12) as $submission)
                        @php($record = $submission['record'])
                        <div class="flex items-start gap-4 px-6 py-5">
                            <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-muted">
                                <x-portal-icon :name="$submission['icon']" class="h-4 w-4 text-muted-foreground" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="text-sm font-medium text-foreground">{{ $record->full_name ?? $record->email }}</p>
                                    <span class="rounded-full bg-primary/10 px-2 py-0.5 text-[10px] uppercase tracking-[0.18em] text-primary">{{ $submission['type'] }}</span>
                                </div>

                                @if (!empty($record->subject))
                                    <p class="mt-2 text-sm text-muted-foreground">{{ $record->subject }}</p>
                                @elseif (!empty($record->company_name))
                                    <p class="mt-2 text-sm text-muted-foreground">{{ $record->company_name }}{{ $record->interest_type ? ' | '.$record->interest_type : '' }}</p>
                                @elseif (!empty($record->department))
                                    <p class="mt-2 text-sm text-muted-foreground">{{ $record->department }}</p>
                                @elseif ($submission['type'] === 'Newsletter')
                                    <p class="mt-2 text-sm text-muted-foreground">Newsletter lead added to the audience list.</p>
                                @endif

                                @if (!empty($record->message))
                                    <p class="mt-2 text-sm leading-relaxed text-muted-foreground">{{ $record->message }}</p>
                                @endif

                                <div class="mt-3 flex flex-wrap items-center gap-3 text-xs text-muted-foreground">
                                    @if (!empty($record->email))
                                        <span>{{ $record->email }}</span>
                                    @endif
                                    @if (!empty($record->phone))
                                        <span aria-hidden="true" class="text-primary/60">&middot;</span>
                                        <span>{{ $record->phone }}</span>
                                    @endif
                                    @php($recordDate = $record->created_at ?? $record->subscribed_at)
                                    @if ($recordDate)
                                        <span aria-hidden="true" class="text-primary/60">&middot;</span>
                                        <span>{{ $recordDate->format('M j, Y g:i A') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center gap-3 px-6 py-12 text-sm text-muted-foreground">
                            <x-portal-icon name="info" class="h-4 w-4 shrink-0" />
                            No form submissions have been recorded yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-6">
                <div class="luxury-card">
                    <div class="flex items-center gap-2">
                        <x-portal-icon name="mail" class="h-4 w-4 text-primary" />
                        <h2 class="font-display text-xl font-semibold">Contact Requests</h2>
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse ($contacts->take(4) as $contact)
                            <div class="rounded-md border border-border bg-muted/20 p-4">
                                <p class="font-medium">{{ $contact->full_name }}</p>
                                <p class="mt-1 text-xs text-muted-foreground">{{ $contact->email }}{{ $contact->phone ? ' | '.$contact->phone : '' }}</p>
                                <p class="mt-3 text-sm text-muted-foreground">{{ $contact->message }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">No contact requests yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="luxury-card">
                    <div class="flex items-center gap-2">
                        <x-portal-icon name="briefcase" class="h-4 w-4 text-primary" />
                        <h2 class="font-display text-xl font-semibold">Partnership Inquiries</h2>
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse ($partnerships->take(4) as $item)
                            <div class="rounded-md border border-border bg-muted/20 p-4">
                                <p class="font-medium">{{ $item->full_name }}</p>
                                <p class="mt-1 text-xs text-muted-foreground">{{ $item->company_name ?: 'Private inquiry' }}{{ $item->interest_type ? ' | '.$item->interest_type : '' }}</p>
                                <p class="mt-3 text-sm text-muted-foreground">{{ $item->message }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">No partnership inquiries yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="luxury-card">
                    <div class="flex items-center gap-2">
                        <x-portal-icon name="help-circle" class="h-4 w-4 text-primary" />
                        <h2 class="font-display text-xl font-semibold">Support and Subscribers</h2>
                    </div>
                    <div class="mt-5 space-y-3">
                        @foreach ($supportTickets->take(2) as $ticket)
                            <div class="rounded-md border border-border bg-muted/20 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-medium">{{ $ticket->subject }}</p>
                                    <x-status-badge :value="$ticket->status" />
                                </div>
                                <p class="mt-3 text-sm text-muted-foreground">{{ $ticket->message }}</p>
                            </div>
                        @endforeach

                        @foreach ($newsletters->take(3) as $item)
                            <div class="rounded-md border border-border bg-muted/20 px-4 py-3 text-sm text-muted-foreground">
                                {{ $item->email }}
                            </div>
                        @endforeach

                        @if ($supportTickets->isEmpty() && $newsletters->isEmpty())
                            <p class="text-sm text-muted-foreground">No support tickets or newsletter leads yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>

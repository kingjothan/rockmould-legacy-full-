<x-layouts.portal :company="$company" page-heading="My Projects">
    <div class="space-y-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs uppercase tracking-[0.24em] text-primary/70">{{ $content['kicker'] ?? 'Client Portfolio' }}</p>
                <h1 class="mt-2 font-display text-4xl md:text-5xl">{{ $content['title'] ?? 'Track every Rockmould project in one place.' }}</h1>
                <p class="mt-3 max-w-2xl text-sm text-muted-foreground md:text-base">
                    {{ $content['description'] ?? 'Monitor delivery progress, payment position, and the latest activity across your assigned developments.' }}
                </p>
            </div>

            <form method="GET" class="grid gap-3 sm:grid-cols-[220px_auto]">
                <select name="status" class="input-shell">
                    <option value="">All statuses</option>
                    @foreach (['active' => 'Active', 'completed' => 'Completed', 'inactive' => 'Inactive', 'pending' => 'Pending'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="luxury-btn-outline">{{ $content['filter_button'] ?? 'Filter Projects' }}</button>
            </form>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.24em] text-muted-foreground">{{ $content['assigned_label'] ?? 'Assigned Projects' }}</p>
                <p class="mt-2 font-display text-4xl">{{ $projectCount ?? $assignments->pluck('project_id')->filter()->unique()->count() }}</p>
                <p class="mt-2 text-sm text-muted-foreground">{{ $content['assigned_description'] ?? 'Unique developments assigned to your account.' }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.24em] text-muted-foreground">Owned Units</p>
                <p class="mt-2 font-display text-4xl">{{ $unitCount ?? $assignments->count() }}</p>
                <p class="mt-2 text-sm text-muted-foreground">Individual properties linked to your portfolio.</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.24em] text-muted-foreground">{{ $content['paid_label'] ?? 'Amount Paid' }}</p>
                <p class="mt-2 font-display text-4xl">NGN {{ number_format((float) $assignments->sum('amount_paid')) }}</p>
                <p class="mt-2 text-sm text-muted-foreground">{{ $content['paid_description'] ?? 'Captured across all linked projects.' }}</p>
            </div>
            <div class="luxury-card">
                <p class="text-[10px] uppercase tracking-[0.24em] text-muted-foreground">{{ $content['outstanding_label'] ?? 'Outstanding' }}</p>
                <p class="mt-2 font-display text-4xl text-primary">NGN {{ number_format((float) $assignments->sum('outstanding_balance')) }}</p>
                <p class="mt-2 text-sm text-muted-foreground">{{ $content['outstanding_description'] ?? 'Current balance still open for settlement.' }}</p>
            </div>
        </div>

        @if ($assignments->isEmpty())
            <div class="luxury-card py-16 text-center">
                <p class="text-xs uppercase tracking-[0.24em] text-primary/70">{{ $content['empty_kicker'] ?? 'No Projects Yet' }}</p>
                <h2 class="mt-3 font-display text-3xl">{{ $content['empty_title'] ?? 'Your portfolio will appear here.' }}</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm text-muted-foreground">
                    {{ $content['empty_description'] ?? 'Once Rockmould assigns a development to your account, you will see the project summary, financial position, and latest updates here.' }}
                </p>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($assignments as $assignment)
                    <article class="luxury-card group">
                        <a href="{{ route('portal.projects.show', $assignment) }}" class="block">
                            <div class="relative overflow-hidden rounded-sm">
                                <x-media :path="$assignment->project?->cover_image" fallback="images/project-1.jpg" alt="{{ $assignment->project?->title ?? 'Project' }}" class="aspect-[16/9] w-full object-cover transition duration-700 group-hover:scale-[1.04]" />
                                <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-black/70 to-transparent"></div>
                                <div class="absolute left-4 top-4">
                                    <x-status-badge :value="$assignment->status" />
                                </div>
                                <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between gap-4">
                                    <div>
                                        <p class="text-[10px] uppercase tracking-[0.24em] text-white/70">{{ $assignment->project?->category ?? 'Project' }}</p>
                                        <p class="mt-1 text-sm text-white/80">{{ $assignment->project?->location ?? 'Location pending' }}</p>
                                    </div>
                                    <p class="text-xs uppercase tracking-[0.24em] text-white/80">{{ $assignment->progress_percentage }}%</p>
                                </div>
                            </div>
                        </a>

                        <div class="mt-5">
                            <h2 class="font-display text-3xl">{{ $assignment->project?->title ?? 'Project' }}</h2>
                            <p class="mt-2 text-sm text-muted-foreground">
                                {{ $assignment->unit?->label ?? $assignment->unit_label }} &middot; updated {{ optional($assignment->updated_at)->format('F j, Y') }}
                            </p>
                        </div>

                        <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-muted">
                            <div class="h-full rounded-full bg-primary transition-all duration-700" style="width: {{ $assignment->progress_percentage }}%"></div>
                        </div>

                        <div class="mt-3 flex items-center justify-between text-xs text-muted-foreground">
                            <span>{{ $assignment->progress_percentage }}% complete</span>
                            <span>{{ str($assignment->project?->status ?? $assignment->status)->title() }}</span>
                        </div>

                        <div class="my-5 h-px bg-border"></div>

                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">Paid</p>
                                <p class="mt-2 font-medium">NGN {{ number_format((float) $assignment->amount_paid) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">Outstanding</p>
                                <p class="mt-2 font-medium text-primary">NGN {{ number_format((float) $assignment->outstanding_balance) }}</p>
                            </div>
                        </div>

                        <a href="{{ route('portal.projects.show', $assignment) }}" class="luxury-btn-outline mt-5 w-full justify-center">
                            {{ $content['card_button'] ?? 'View Details' }}
                            <svg class="ml-1.5 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.portal>

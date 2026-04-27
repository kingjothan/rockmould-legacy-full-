<x-layouts.portal :company="$company" page-heading="Client Dashboard">
    <div class="mx-auto max-w-6xl space-y-8">
        <div>
            <h1 class="mb-1 font-display text-2xl font-semibold">{{ str($content['welcome_title'] ?? 'Welcome Back, :name')->replace(':name', str(auth()->user()->name)->before(' ')) }}</h1>
            <p class="text-sm text-muted-foreground">{{ $content['description'] ?? 'Here’s an overview of your projects and activity.' }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
            <div class="luxury-card">
                <x-portal-icon name="folder-kanban" class="portal-stat-icon h-5 w-5 text-emerald-400" />
                <p class="font-display text-2xl font-bold">{{ $totals['active'] }}</p>
                <p class="mt-1 text-xs text-muted-foreground">{{ $content['active_projects_label'] ?? 'Active Projects' }}</p>
            </div>
            <div class="luxury-card">
                <x-portal-icon name="receipt" class="portal-stat-icon h-5 w-5 text-primary" />
                <p class="font-display text-2xl font-bold">NGN {{ number_format($totals['paid']) }}</p>
                <p class="mt-1 text-xs text-muted-foreground">{{ $content['amount_paid_label'] ?? 'Amount Paid' }}</p>
            </div>
            <div class="luxury-card">
                <x-portal-icon name="trending-up" class="portal-stat-icon h-5 w-5 text-amber-400" />
                <p class="font-display text-2xl font-bold">NGN {{ number_format($totals['outstanding']) }}</p>
                <p class="mt-1 text-xs text-muted-foreground">{{ $content['outstanding_label'] ?? 'Outstanding' }}</p>
            </div>
            <div class="luxury-card">
                <x-portal-icon name="file-text" class="portal-stat-icon h-5 w-5 text-sky-400" />
                <p class="font-display text-2xl font-bold">{{ $recentDocuments->count() }}</p>
                <p class="mt-1 text-xs text-muted-foreground">{{ $content['documents_label'] ?? 'Documents' }}</p>
            </div>
        </div>

        <div>
            <div class="mb-4 flex items-center justify-between">
                <h2 class="font-display text-lg font-semibold">{{ $content['projects_title'] ?? 'My Projects' }}</h2>
                <a href="{{ route('portal.projects.index') }}" class="flex items-center gap-1 text-xs text-primary hover:underline">
                    {{ $content['projects_link'] ?? 'View All' }}
                    <x-portal-icon name="arrow-right" class="h-3 w-3" />
                </a>
            </div>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($assignments as $assignment)
                    <a href="{{ route('portal.projects.show', $assignment) }}" class="luxury-card group">
                        <div class="mb-3 flex items-center justify-between">
                            <x-status-badge :value="$assignment->status" />
                            <span class="text-xs text-muted-foreground">{{ $assignment->progress_percentage }}%</span>
                        </div>
                        <h3 class="mb-3 text-sm font-semibold transition-colors group-hover:text-primary">{{ $assignment->project->title }} - {{ $assignment->unit_label }}</h3>
                        <div class="mb-4 h-1.5 overflow-hidden rounded-full bg-muted">
                            <div class="h-full rounded-full bg-primary" style="width: {{ $assignment->progress_percentage }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-muted-foreground">
                            <span>Paid: NGN {{ number_format((float) $assignment->amount_paid) }}</span>
                            <span>Due: NGN {{ number_format((float) $assignment->outstanding_balance) }}</span>
                        </div>
                    </a>
                @empty
                    <div class="luxury-card text-sm text-muted-foreground">{{ $content['projects_empty'] ?? 'No projects have been assigned to your portal yet.' }}</div>
                @endforelse
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="luxury-card">
                <h3 class="mb-4 font-display text-lg font-semibold">{{ $content['updates_title'] ?? 'Recent Updates' }}</h3>
                <div class="space-y-4">
                    @forelse ($recentUpdates as $update)
                        <div class="flex items-start gap-3">
                            <x-portal-icon name="clock" class="mt-0.5 h-4 w-4 flex-shrink-0 text-primary" />
                            <div>
                                <p class="text-sm">{{ $update->title }}</p>
                                <p class="text-xs text-muted-foreground">{{ optional($update->update_date)->format('F j, Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-muted-foreground">{{ $content['updates_empty'] ?? 'No recent project updates yet.' }}</p>
                    @endforelse
                </div>
            </div>

            <div class="luxury-card">
                <h3 class="mb-4 font-display text-lg font-semibold">{{ $content['documents_title'] ?? 'Recent Documents' }}</h3>
                <div class="space-y-3">
                    @forelse ($recentDocuments as $document)
                        <div class="flex items-center justify-between rounded-sm bg-muted/30 p-3">
                            <div class="flex items-center gap-3">
                                <x-portal-icon name="file-text" class="h-4 w-4 text-primary" />
                                <span class="text-sm">{{ $document->title }}</span>
                            </div>
                            @if ($document->file_url)
                                <a href="{{ $document->file_url }}" target="_blank" class="text-xs text-primary hover:underline">Download</a>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-muted-foreground">{{ $content['documents_empty'] ?? 'No documents have been released yet.' }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-layouts.portal>

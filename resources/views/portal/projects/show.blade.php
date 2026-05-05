<x-layouts.portal :company="$company" :page-heading="$assignment->project->title">
    <div class="space-y-8">
        <section class="relative overflow-hidden rounded-sm border border-border bg-card">
            <x-media :path="$assignment->project->cover_image" fallback="images/project-1.jpg" alt="{{ $assignment->project->title }}" class="aspect-[16/8] w-full object-cover" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/15 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 p-6 md:p-10">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.24em] text-white/70">{{ $assignment->project->category }}</p>
                        <h1 class="mt-3 font-display text-4xl text-white md:text-6xl">{{ $assignment->project->title }}</h1>
                        <p class="mt-3 text-sm text-white/80 md:text-base">{{ $assignment->unit_label }} &middot; {{ $assignment->project->location }}</p>
                    </div>
                    <x-status-badge :value="$assignment->status" />
                </div>
            </div>
        </section>

        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="space-y-8">
                <div class="luxury-card">
                    <h2 class="font-display text-3xl">Project Overview</h2>
                    <p class="mt-4 text-sm leading-7 text-muted-foreground md:text-base">{{ $assignment->project->description ?: $assignment->project->overview }}</p>

                    @if (! empty($assignment->project->highlights))
                        <div class="mt-6 grid gap-3 md:grid-cols-2">
                            @foreach ($assignment->project->highlights as $highlight)
                                <div class="rounded-sm border border-border bg-muted/20 px-4 py-3">
                                    <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">{{ $highlight['label'] }}</p>
                                    <p class="mt-2 font-medium">{{ $highlight['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="luxury-card">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="font-display text-3xl">Milestones</h2>
                        <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">{{ $assignment->project->milestones->count() }} stages</p>
                    </div>
                    <div class="mt-6 space-y-4">
                        @forelse ($assignment->project->milestones as $milestone)
                            <div class="rounded-sm border border-border bg-muted/20 p-4">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h3 class="font-medium">{{ $milestone->title }}</h3>
                                        @if ($milestone->due_date)
                                            <p class="mt-1 text-xs text-muted-foreground">{{ optional($milestone->due_date)->format('F j, Y') }}</p>
                                        @endif
                                    </div>
                                    <x-status-badge :value="$milestone->is_completed ? 'completed' : 'pending'" />
                                </div>
                                @if ($milestone->description)
                                    <p class="mt-3 text-sm text-muted-foreground">{{ $milestone->description }}</p>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">Milestones have not been published for this project yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="luxury-card">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="font-display text-3xl">Recent Updates</h2>
                        <p class="text-xs uppercase tracking-[0.18em] text-muted-foreground">{{ $assignment->project->updates->count() }} entries</p>
                    </div>
                    <div class="mt-6 space-y-4">
                        @forelse ($assignment->project->updates->take(4) as $update)
                            <div class="rounded-sm border border-border bg-muted/20 p-4">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="font-medium">{{ $update->title }}</p>
                                    <span class="text-xs text-muted-foreground">{{ optional($update->update_date)->format('M j, Y') ?: 'Pending date' }}</span>
                                </div>
                                <p class="mt-3 text-sm text-muted-foreground">{{ $update->body }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">No project updates have been posted yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="luxury-card">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h2 class="font-display text-3xl">Project Media</h2>
                            <p class="mt-2 text-sm text-muted-foreground">Visual progress, delivery highlights, and client-visible media curated for this project.</p>
                        </div>
                        <a
                            href="{{ route('portal.media', ['project' => $assignment->project_id]) }}"
                            class="luxury-btn-outline text-xs"
                        >
                            Open Full Gallery
                            <x-portal-icon name="arrow-right" class="h-3.5 w-3.5" />
                        </a>
                    </div>

                    @if ($galleryMedia->isEmpty())
                        <div class="mt-6 rounded-sm border border-dashed border-border px-5 py-12 text-center">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-muted/30">
                                <x-portal-icon name="image" class="h-5 w-5 text-muted-foreground" />
                            </div>
                            <h3 class="mt-4 font-display text-xl">No media has been published yet</h3>
                            <p class="mt-2 text-sm text-muted-foreground">Project visuals will appear here as the Rockmould team uploads them for your portal access.</p>
                        </div>
                    @else
                        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach ($galleryMedia->take(6) as $asset)
                                <a
                                    href="{{ route('portal.media', ['project' => $assignment->project_id, 'preview' => $asset->id]) }}"
                                    class="group overflow-hidden rounded-sm border border-border/60 bg-card transition hover:border-primary/30"
                                >
                                    <div class="relative aspect-[5/4] bg-muted">
                                        <x-media
                                            :path="$asset->media_type === 'video' ? $asset->file_path : ($asset->thumbnail_path ?: $asset->file_path)"
                                            fallback="images/project-1.jpg"
                                            alt="{{ $asset->title }}"
                                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                            :loop="false"
                                        />
                                        <div class="absolute left-3 top-3 flex flex-wrap items-center gap-2">
                                            <span class="rounded-full border border-white/15 bg-black/55 px-2 py-1 text-[10px] uppercase tracking-[0.18em] text-white/80">{{ $asset->media_type }}</span>
                                            @if ($asset->is_featured)
                                                <span class="rounded-full bg-primary/85 px-2 py-1 text-[10px] uppercase tracking-[0.18em] text-primary-foreground">Featured</span>
                                            @endif
                                        </div>
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/0 transition-all duration-300 group-hover:bg-black/40">
                                            <span class="opacity-0 transition duration-300 group-hover:opacity-100">
                                                <x-portal-icon name="eye" class="h-6 w-6 text-white" />
                                            </span>
                                        </div>
                                    </div>
                                    <div class="space-y-2 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <p class="text-sm font-medium">{{ $asset->title }}</p>
                                            <x-portal-icon :name="$asset->media_type === 'video' ? 'video' : 'image'" class="h-4 w-4 shrink-0 text-primary" />
                                        </div>
                                        <p class="text-[11px] uppercase tracking-[0.18em] text-muted-foreground">
                                            {{ $asset->project_assignment_id ? 'Assignment update' : 'Shared project gallery' }}
                                        </p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="luxury-card">
                    <h2 class="font-display text-3xl">Documents</h2>
                    <div class="mt-6 space-y-3">
                        @forelse ($portalDocuments as $document)
                            <div class="flex flex-col gap-4 rounded-sm border border-border bg-muted/10 p-4 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="font-medium">{{ $document->title }}</p>
                                    <p class="mt-1 text-xs text-muted-foreground">
                                        {{ $document->category }} &middot; {{ $document->version ?: 'v1' }}
                                        &middot;
                                        {{ $document->project_assignment_id ? 'Assignment document' : 'Shared project document' }}
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if ($document->file_url)
                                        <a href="{{ $document->file_url }}" target="_blank" class="luxury-btn-outline">Preview</a>
                                        <a href="{{ route('portal.documents.download', $document) }}" class="luxury-btn-primary">Download</a>
                                    @else
                                        <span class="text-xs uppercase tracking-[0.18em] text-muted-foreground">Pending upload</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-muted-foreground">No project documents are available yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="luxury-card sticky top-24">
                    <h2 class="font-display text-3xl">Client Summary</h2>
                    <div class="mt-5 space-y-4 text-sm">
                        <div class="flex items-center justify-between"><span class="text-muted-foreground">Unit</span><span>{{ $assignment->unit_label }}</span></div>
                        <div class="flex items-center justify-between"><span class="text-muted-foreground">Project Status</span><span>{{ str($assignment->status)->title() }}</span></div>
                        <div class="flex items-center justify-between"><span class="text-muted-foreground">Progress</span><span>{{ $assignment->progress_percentage }}%</span></div>
                        <div class="flex items-center justify-between"><span class="text-muted-foreground">Paid</span><span>NGN {{ number_format((float) $assignment->amount_paid) }}</span></div>
                        <div class="flex items-center justify-between"><span class="text-muted-foreground">Outstanding</span><span>NGN {{ number_format((float) $assignment->outstanding_balance) }}</span></div>
                        @if ($assignment->next_payment_amount)
                            <div class="flex items-center justify-between"><span class="text-muted-foreground">Next Payment</span><span>NGN {{ number_format((float) $assignment->next_payment_amount) }}</span></div>
                        @endif
                        @if ($assignment->next_payment_due_at)
                            <div class="flex items-center justify-between"><span class="text-muted-foreground">Due Date</span><span>{{ optional($assignment->next_payment_due_at)->format('M j, Y') }}</span></div>
                        @endif
                    </div>

                    <div class="mt-6 h-1.5 overflow-hidden rounded-full bg-muted">
                        <div class="h-full rounded-full bg-primary" style="width: {{ $assignment->progress_percentage }}%"></div>
                    </div>

                    <div class="mt-8">
                        <h3 class="font-display text-2xl">Payment History</h3>
                        <div class="mt-4 space-y-3">
                            @forelse ($assignment->payments as $payment)
                                <div class="rounded-sm border border-border bg-muted/20 px-4 py-3">
                                    <div class="flex items-center justify-between gap-3">
                                        <div>
                                            <p class="text-sm font-medium">NGN {{ number_format((float) $payment->amount) }}</p>
                                            <p class="mt-1 text-xs text-muted-foreground">{{ $payment->reference ?: 'Project payment' }}</p>
                                        </div>
                                        <x-status-badge :value="$payment->status" />
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-muted-foreground">No payment records yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-layouts.portal>

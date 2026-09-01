<x-layouts.portal :company="$company" :page-heading="$content['title'] ?? 'Project Updates'">
    <div class="mx-auto max-w-5xl space-y-6">
        <div>
            <h1 class="font-display text-2xl font-semibold">{{ $content['title'] ?? 'Project Updates' }}</h1>
            <p class="mt-1 text-sm text-muted-foreground">{{ $content['description'] ?? 'Chronological updates across all your projects' }}</p>
        </div>

        @if ($updates->isEmpty())
            <div class="luxury-card py-16 text-center">
                <h2 class="font-display text-3xl">{{ $content['empty_title'] ?? 'No updates yet.' }}</h2>
                <p class="mx-auto mt-3 max-w-xl text-sm text-muted-foreground">{{ $content['empty_description'] ?? 'Project updates will appear here as soon as the Rockmould team publishes them to your account.' }}</p>
            </div>
        @else
            @php
                $grouped = $updates->groupBy(fn($u) => $u->project_id);
            @endphp

            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($grouped as $projectId => $projectUpdates)
                    @php
                        $project         = $projectUpdates->first()->project;
                        $count           = $projectUpdates->count();
                        $latest          = $projectUpdates->last();
                        $overallProgress = (int) ($project?->progress ?? 0);
                    @endphp

                    <div class="luxury-card overflow-hidden p-0">

                        {{-- Project thumbnail --}}
                        <div class="relative h-36 w-full overflow-hidden">
                            <img
                                src="{{ $project?->cover_image_url ?? asset('images/project-1.jpg') }}"
                                alt="{{ $project?->title }}"
                                class="h-full w-full object-cover"
                            >
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 px-4 pb-3">
                                <p class="truncate text-sm font-semibold text-white">{{ $project?->title ?? 'Project' }}</p>
                                <p class="text-[11px] text-white/70">
                                    {{ $count }} {{ Str::plural('update', $count) }}
                                    @if ($latest->update_date)
                                        &middot; Last: {{ $latest->update_date->format('M j, Y') }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Project header / trigger --}}
                        <button
                            type="button"
                            class="update-accordion-trigger flex w-full items-center justify-between gap-3 px-5 py-3 text-left transition-colors hover:bg-muted/30"
                            onclick="toggleUpdateAccordion(this)"
                        >
                            <span class="text-xs text-muted-foreground">View updates</span>

                            @if ($overallProgress)
                                <span class="shrink-0 rounded-full bg-primary/10 px-2.5 py-0.5 text-[10px] font-medium text-primary">{{ $overallProgress }}%</span>
                            @endif

                            <svg class="update-chevron h-4 w-4 shrink-0 text-muted-foreground transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- Progress bar --}}
                        @if ($overallProgress)
                            <div class="h-0.5 w-full bg-muted">
                                <div class="h-full bg-primary" style="width:{{ $overallProgress }}%"></div>
                            </div>
                        @endif

                        {{-- Updates panel --}}
                        <div class="update-accordion-panel hidden border-t border-border">
                            <div class="relative px-5 py-5">
                                <div class="absolute bottom-5 left-[2.75rem] top-5 w-px bg-border"></div>

                                <div class="space-y-5">
                                    @foreach ($projectUpdates as $update)
                                        @php
                                            $progress    = (int) ($update->progress_percentage ?? $update->project?->progress ?? 0);
                                            $isMilestone = (bool) ($update->is_milestone ?? false);
                                            $hasMedia    = ! empty($update->attachments);
                                        @endphp
                                        <div class="update-entry relative flex gap-4">
                                            <div class="z-10 flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full border-2 border-background {{ $isMilestone ? 'bg-primary/10' : 'bg-card' }}">
                                                @if ($progress === 100)
                                                    <x-portal-icon name="check-circle" class="h-4 w-4 text-primary" />
                                                @elseif ($isMilestone)
                                                    <x-portal-icon name="clock" class="h-3.5 w-3.5 text-primary" />
                                                @else
                                                    <x-portal-icon name="circle" class="h-2.5 w-2.5 text-muted-foreground" />
                                                @endif
                                            </div>

                                            <div class="flex-1 rounded-sm border border-border bg-background p-4">
                                                <div class="mb-1.5 flex items-start justify-between gap-2">
                                                    <h3 class="text-sm font-semibold leading-snug">{{ $update->title }}</h3>
                                                    <span class="shrink-0 text-[11px] text-muted-foreground">{{ optional($update->update_date)->format('M j, Y') }}</span>
                                                </div>
                                                <p class="text-xs leading-relaxed text-muted-foreground">{{ $update->body }}</p>

                                                @if ($progress)
                                                    <div class="mt-3 flex items-center gap-2">
                                                        <div class="h-1 flex-1 rounded-full bg-muted">
                                                            <div class="h-full rounded-full bg-primary" style="width:{{ $progress }}%"></div>
                                                        </div>
                                                        <span class="text-[11px] text-muted-foreground">{{ $progress }}%</span>
                                                    </div>
                                                @endif

                                                @if ($hasMedia)
                                                    <button type="button"
                                                        onclick="this.closest('.update-entry').querySelector('.update-media').classList.toggle('hidden')"
                                                        class="mt-2 flex items-center gap-1 text-[10px] text-primary transition-colors hover:text-primary/80">
                                                        <x-portal-icon name="image" class="h-3 w-3" />
                                                        {{ count($update->attachments) }} {{ count($update->attachments) === 1 ? 'Photo' : 'Photos/Videos' }}
                                                    </button>
                                                    <div class="update-media hidden mt-3 grid grid-cols-2 gap-2 sm:grid-cols-3">
                                                        @foreach ($update->attachments as $attachment)
                                                            @php
                                                                $url       = str_starts_with($attachment, 'http') ? $attachment : asset('storage/'.$attachment);
                                                                $cleanPath = strtok($attachment, '?');
                                                                $ext       = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));
                                                                $isVideo   = in_array($ext, ['mp4','webm','mov','ogg','avi','mkv','m4v'])
                                                                    || str_contains(strtolower($attachment), 'video');
                                                            @endphp
                                                            @if ($isVideo)
                                                                <video src="{{ $url }}" controls playsinline class="aspect-video w-full rounded-md bg-muted"></video>
                                                            @else
                                                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">
                                                                    <img src="{{ $url }}" alt="Update attachment" class="aspect-square w-full rounded-md bg-muted object-cover transition-opacity hover:opacity-90">
                                                                </a>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function toggleUpdateAccordion(btn) {
            const card   = btn.closest('.luxury-card');
            const panel  = card.querySelector('.update-accordion-panel');
            const chevron = btn.querySelector('.update-chevron');
            const isOpen  = !panel.classList.contains('hidden');
            panel.classList.toggle('hidden', isOpen);
            chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
        }
    </script>
</x-layouts.portal>

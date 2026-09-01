<x-layouts.portal :company="$company" :page-heading="$content['title'] ?? 'Project Updates'">
    <div class="mx-auto max-w-3xl space-y-6">
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
            @php $grouped = $updates->groupBy(fn($u) => $u->project_id); @endphp

            <div class="space-y-3">
                @foreach ($grouped as $projectId => $projectUpdates)
                    @php
                        $project         = $projectUpdates->first()->project;
                        $count           = $projectUpdates->count();
                        $latest          = $projectUpdates->last();
                        $overallProgress = (int) ($project?->progress ?? 0);
                    @endphp

                    <div class="luxury-card overflow-hidden p-0">

                        {{-- Trigger row --}}
                        <button
                            type="button"
                            class="flex w-full items-center gap-4 p-3 text-left transition-colors hover:bg-muted/20"
                            onclick="toggleUpdateAccordion(this)"
                        >
                            {{-- Thumbnail --}}
                            <div class="h-14 w-20 shrink-0 overflow-hidden rounded-sm">
                                <img
                                    src="{{ $project?->cover_image_url ?? asset('images/project-1.jpg') }}"
                                    alt="{{ $project?->title }}"
                                    class="h-full w-full object-cover"
                                >
                            </div>

                            {{-- Info --}}
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold">{{ $project?->title ?? 'Project' }}</p>
                                <p class="mt-0.5 text-[11px] text-muted-foreground">
                                    {{ $count }} {{ Str::plural('update', $count) }}
                                    @if ($latest->update_date)
                                        &middot; {{ $latest->update_date->format('M j, Y') }}
                                    @endif
                                </p>
                                @if ($overallProgress)
                                    <div class="mt-1.5 flex items-center gap-2">
                                        <div class="h-1 w-20 rounded-full bg-muted">
                                            <div class="h-full rounded-full bg-primary" style="width:{{ $overallProgress }}%"></div>
                                        </div>
                                        <span class="text-[10px] text-muted-foreground">{{ $overallProgress }}%</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Chevron --}}
                            <svg class="update-chevron mr-1 h-4 w-4 shrink-0 text-muted-foreground transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

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
                                                    <div class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-3">
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
                                                                <img src="{{ $url }}" alt="Update attachment" class="aspect-square w-full cursor-zoom-in rounded-md bg-muted object-cover transition-opacity hover:opacity-90" onclick="openLightbox(this.src)">
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

    {{-- Lightbox --}}
    <div id="update-lightbox" style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,0.92);padding:1rem;" onclick="closeLightbox()">
        <button style="position:absolute;top:1rem;right:1rem;background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.7);line-height:1;" onclick="closeLightbox()">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <img id="update-lightbox-img" src="" alt="" style="max-height:90vh;max-width:90vw;object-fit:contain;border-radius:4px;box-shadow:0 25px 50px rgba(0,0,0,0.6);" onclick="event.stopPropagation()">
    </div>

    <script>
        function openLightbox(src) {
            const lb = document.getElementById('update-lightbox');
            document.getElementById('update-lightbox-img').src = src;
            lb.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }
        function closeLightbox() {
            document.getElementById('update-lightbox').style.display = 'none';
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

        function toggleUpdateAccordion(btn) {
            const card    = btn.closest('.luxury-card');
            const panel   = card.querySelector('.update-accordion-panel');
            const chevron = btn.querySelector('.update-chevron');
            const isOpen  = !panel.classList.contains('hidden');
            panel.classList.toggle('hidden', isOpen);
            chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
        }
    </script>
</x-layouts.portal>

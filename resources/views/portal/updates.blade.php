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
            <div class="relative">
                <div class="absolute bottom-0 left-5 top-0 w-px bg-border"></div>

                <div class="space-y-6">
                    @foreach ($updates as $update)
                        @php
                            $progress = (int) ($update->progress_percentage ?? $update->project?->progress ?? 0);
                            $isMilestone = (bool) ($update->is_milestone ?? false);
                            $hasMedia = ! empty($update->attachments);
                        @endphp
                        <div class="relative flex gap-4">
                            <div class="z-10 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border-2 border-background {{ $isMilestone ? 'bg-primary/10' : 'bg-card' }}">
                                @if ($progress === 100)
                                    <x-portal-icon name="check-circle" class="h-5 w-5 text-primary" />
                                @elseif ($isMilestone)
                                    <x-portal-icon name="clock" class="h-4 w-4 text-primary" />
                                @else
                                    <x-portal-icon name="circle" class="h-3 w-3 text-muted-foreground" />
                                @endif
                            </div>

                            <div class="luxury-card flex-1">
                                <div class="mb-2 flex items-start justify-between gap-2">
                                    <span class="rounded-sm bg-primary/10 px-2 py-0.5 text-[10px] uppercase tracking-wider text-primary">{{ $update->project?->title ?: 'Project Update' }}</span>
                                    <span class="flex-shrink-0 text-xs text-muted-foreground">{{ optional($update->update_date)->format('F j, Y') }}</span>
                                </div>
                                <h3 class="mb-1 text-sm font-semibold">{{ $update->title }}</h3>
                                <p class="text-xs leading-relaxed text-muted-foreground">{{ $update->body }}</p>
                                <div class="mt-3 flex items-center gap-4">
                                    <div class="flex flex-1 items-center gap-2">
                                        <div class="h-1.5 flex-1 rounded-full bg-muted">
                                            <div class="h-full rounded-full bg-primary" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span class="text-xs text-muted-foreground">{{ $progress }}%</span>
                                    </div>
                                    @if ($hasMedia)
                                        <button type="button"
                                            onclick="this.closest('.luxury-card').querySelector('.update-media').classList.toggle('hidden')"
                                            class="flex items-center gap-1 text-[10px] text-primary hover:text-primary/80 transition-colors">
                                            <x-portal-icon name="image" class="h-3 w-3" />
                                            {{ count($update->attachments) }} {{ count($update->attachments) === 1 ? 'Photo' : 'Photos/Videos' }}
                                        </button>
                                    @endif
                                </div>

                                @if ($hasMedia)
                                    <div class="update-media hidden mt-4 grid grid-cols-2 gap-2 sm:grid-cols-3">
                                        @foreach ($update->attachments as $attachment)
                                            @php
                                                $url = str_starts_with($attachment, 'http') ? $attachment : asset('storage/'.$attachment);
                                                // Strip query string before checking extension
                                                $cleanPath = strtok($attachment, '?');
                                                $ext = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION));
                                                $isVideo = in_array($ext, ['mp4','webm','mov','ogg','avi','mkv','m4v'])
                                                    || str_contains(strtolower($attachment), 'video');
                                            @endphp
                                            @if ($isVideo)
                                                <video src="{{ $url }}" controls playsinline class="w-full rounded-lg aspect-video bg-muted"></video>
                                            @else
                                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">
                                                    <img src="{{ $url }}" alt="Update attachment" class="w-full rounded-lg aspect-square object-cover bg-muted hover:opacity-90 transition-opacity">
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
        @endif
    </div>
</x-layouts.portal>

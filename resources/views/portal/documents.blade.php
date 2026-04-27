<x-layouts.portal :company="$company" :page-heading="$content['title'] ?? 'Documents'">
    <div class="mx-auto max-w-6xl">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="font-display text-2xl font-semibold">{{ $content['title'] ?? 'Documents' }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">{{ $content['description'] ?? 'Search, review, preview, and download the documents tied to your Rockmould projects.' }}</p>
            </div>
            <form method="GET" class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row">
                <div class="portal-search-shell sm:w-64">
                    <x-portal-icon name="search" class="h-4 w-4" />
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Search documents..." class="input-shell py-2 text-sm">
                </div>
                <select name="category" class="input-shell py-2 text-sm sm:w-52">
                    <option value="">All categories</option>
                    @foreach ($documents->pluck('category')->filter()->unique()->sort()->values() as $category)
                        <option value="{{ $category }}" @selected(request('category') === $category)>{{ $category }}</option>
                    @endforeach
                </select>
                <button class="luxury-btn-outline">Filter</button>
            </form>
        </div>

        @if ($documents->isEmpty())
            <div class="luxury-card py-16 text-center">
                <x-portal-icon name="file-text" class="mx-auto mb-3 h-10 w-10 text-muted-foreground" />
                <p class="text-muted-foreground">{{ $content['empty_text'] ?? 'No documents found.' }}</p>
            </div>
        @else
            <div class="space-y-2">
                @foreach ($documents as $document)
                    @php
                        $size = $document->size ? number_format($document->size / 1024, 0).' KB' : 'Pending size';
                    @endphp
                    <div class="luxury-card flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex items-center gap-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-md bg-primary/10">
                                <x-portal-icon name="file-text" class="h-4 w-4 text-primary" />
                            </div>
                            <div>
                                <p class="text-sm font-medium">{{ $document->title }}</p>
                                <div class="mt-0.5 flex flex-wrap items-center gap-3 text-xs text-muted-foreground">
                                    <span>{{ $document->category }}</span>
                                    <span>&middot;</span>
                                    <span>{{ $document->project?->title ?: 'Project record' }}</span>
                                    <span>&middot;</span>
                                    <span>{{ $document->project_assignment_id ? 'Assignment document' : 'Shared project document' }}</span>
                                    <span>&middot;</span>
                                    <span>{{ $document->created_at?->format('M j, Y') ?: 'Pending date' }}</span>
                                    <span>&middot;</span>
                                    <span>{{ $size }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if ($document->file_url)
                                <a href="{{ $document->file_url }}" target="_blank" class="rounded-sm p-2 text-muted-foreground transition-colors hover:text-primary">
                                    <x-portal-icon name="eye" class="h-4 w-4" />
                                </a>
                                <a href="{{ route('portal.documents.download', $document) }}" class="rounded-sm p-2 text-muted-foreground transition-colors hover:text-primary">
                                    <x-portal-icon name="download" class="h-4 w-4" />
                                </a>
                            @else
                                <span class="text-xs text-muted-foreground">Pending upload</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-layouts.portal>

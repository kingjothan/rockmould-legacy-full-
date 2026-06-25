<x-layouts.admin :company="$company" page-heading="Blog CMS">
    <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
        <div class="space-y-5">
            @foreach ($posts as $post)
                <details class="luxury-card group">
                    <summary class="cursor-pointer list-none">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="font-display text-xl font-semibold">{{ $post->title }}</h2>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    {{ $post->category }} &middot; {{ $post->author_name }}
                                    @if ($post->published_at) &middot; {{ $post->published_at->format('M d, Y') }} @endif
                                    @if ($post->is_published) <span class="text-green-500">Published</span> @else <span class="text-yellow-500">Draft</span> @endif
                                </p>
                            </div>
                            <x-portal-icon name="chevron-down" class="h-4 w-4 transition-transform group-open:rotate-180" />
                        </div>
                    </summary>
                    <form action="{{ route('admin.cms.blog.update', $post) }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4" data-blog-form>
                        @csrf
                        @method('PUT')
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="admin-label">Title</label>
                                <input name="title" value="{{ $post->title }}" class="input-shell w-full" required>
                            </div>
                            <div>
                                <label class="admin-label">Slug</label>
                                <input name="slug" value="{{ $post->slug }}" class="input-shell w-full">
                            </div>
                        </div>
                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <label class="admin-label">Category</label>
                                <input name="category" value="{{ $post->category }}" class="input-shell w-full" required>
                            </div>
                            <div>
                                <label class="admin-label">Author</label>
                                <input name="author_name" value="{{ $post->author_name }}" class="input-shell w-full" required>
                            </div>
                            <div>
                                <label class="admin-label">Read Time</label>
                                <input name="read_time" value="{{ $post->read_time }}" class="input-shell w-full" placeholder="e.g. 5 min">
                            </div>
                        </div>
                        <div>
                            <label class="admin-label">Excerpt (short summary shown in listings)</label>
                            <textarea name="excerpt" rows="2" class="input-shell w-full" required>{{ $post->excerpt }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Intro Paragraph</label>
                            <textarea name="intro" rows="3" class="input-shell w-full" placeholder="Opening paragraph shown before the main content">{{ $post->intro }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Pull Quote (optional)</label>
                            <textarea name="quote" rows="2" class="input-shell w-full" placeholder="A standout quote displayed in the article">{{ $post->quote }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Cover Image</label>
                            <input name="cover_image" value="{{ $post->cover_image }}" class="input-shell w-full" placeholder="Paste URL or use upload below">
                            <input type="file" name="cover_image_upload" accept="image/*,video/*" class="input-shell mt-2 w-full">
                            <label class="mt-2 flex items-center gap-2 text-xs text-muted-foreground">
                                <input type="checkbox" name="remove_cover_image" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary">
                                Remove current cover
                            </label>
                        </div>

                        <div>
                            <label class="admin-label">Article Content</label>
                            <p class="mb-3 text-xs text-muted-foreground">Add sections below. Each section can have a heading, text paragraphs, and an optional image or video.</p>
                            <div data-content-builder data-initial='@json($post->content ?? [])' class="space-y-4"></div>
                            <button type="button" data-add-section class="luxury-btn-outline mt-3 text-xs">+ Add Section</button>
                            <input type="hidden" name="content_json" data-content-output>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="admin-label">Publish Date</label>
                                <input type="datetime-local" name="published_at" value="{{ optional($post->published_at)->format('Y-m-d\TH:i') }}" class="input-shell w-full">
                            </div>
                            <div class="flex items-end gap-6 pb-2">
                                <label class="flex items-center gap-2 text-sm text-muted-foreground"><input type="checkbox" name="featured" value="1" @checked($post->featured) class="h-4 w-4 rounded border-border bg-transparent text-primary"> Featured</label>
                                <label class="flex items-center gap-2 text-sm text-muted-foreground"><input type="checkbox" name="is_published" value="1" @checked($post->is_published) class="h-4 w-4 rounded border-border bg-transparent text-primary"> Published</label>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <button class="luxury-btn-primary">Update Post</button>
                        </div>
                    </form>
                    <form action="{{ route('admin.cms.blog.destroy', $post) }}" method="POST" class="mt-3" onsubmit="return confirm('Delete &quot;{{ $post->title }}&quot;? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-destructive hover:underline">Delete this post</button>
                    </form>
                </details>
            @endforeach
        </div>

        <div>
            <div class="luxury-card sticky top-20">
                <h2 class="font-display text-xl font-semibold">Create New Post</h2>
                <form action="{{ route('admin.cms.blog.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4" data-blog-form>
                    @csrf
                    <div>
                        <label class="admin-label">Title</label>
                        <input name="title" required placeholder="Post title" class="input-shell w-full">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="admin-label">Category</label>
                            <input name="category" required placeholder="e.g. Market Insights" class="input-shell w-full">
                        </div>
                        <div>
                            <label class="admin-label">Author</label>
                            <input name="author_name" required placeholder="Author name" class="input-shell w-full">
                        </div>
                    </div>
                    <div>
                        <label class="admin-label">Read Time</label>
                        <input name="read_time" placeholder="e.g. 5 min" class="input-shell w-full">
                    </div>
                    <div>
                        <label class="admin-label">Excerpt</label>
                        <textarea name="excerpt" rows="2" required placeholder="Short summary shown in listings" class="input-shell w-full"></textarea>
                    </div>
                    <div>
                        <label class="admin-label">Intro Paragraph</label>
                        <textarea name="intro" rows="3" placeholder="Opening paragraph" class="input-shell w-full"></textarea>
                    </div>
                    <div>
                        <label class="admin-label">Pull Quote (optional)</label>
                        <textarea name="quote" rows="2" placeholder="Standout quote" class="input-shell w-full"></textarea>
                    </div>
                    <div>
                        <label class="admin-label">Cover Image</label>
                        <input name="cover_image" placeholder="Paste URL or use upload below" class="input-shell w-full">
                        <input type="file" name="cover_image_upload" accept="image/*,video/*" class="input-shell mt-2 w-full">
                    </div>

                    <div>
                        <label class="admin-label">Article Content</label>
                        <p class="mb-3 text-xs text-muted-foreground">Add sections with headings, paragraphs, and media.</p>
                        <div data-content-builder data-initial='[]' class="space-y-4"></div>
                        <button type="button" data-add-section class="luxury-btn-outline mt-3 text-xs">+ Add Section</button>
                        <input type="hidden" name="content_json" data-content-output>
                    </div>

                    <div>
                        <label class="admin-label">Publish Date</label>
                        <input type="datetime-local" name="published_at" class="input-shell w-full">
                    </div>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2 text-sm text-muted-foreground"><input type="checkbox" name="featured" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary"> Featured</label>
                        <label class="flex items-center gap-2 text-sm text-muted-foreground"><input type="checkbox" name="is_published" value="1" checked class="h-4 w-4 rounded border-border bg-transparent text-primary"> Published</label>
                    </div>
                    <button class="luxury-btn-primary w-full justify-center">Create Post</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>

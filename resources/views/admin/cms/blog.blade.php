<x-layouts.admin :company="$company" page-heading="Blog CMS">
    <div class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="space-y-5">
            @foreach ($posts as $post)
                <div class="luxury-card">
                    <h2 class="font-display text-3xl">{{ $post->title }}</h2>
                    <form action="{{ route('admin.cms.blog.update', $post) }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
                        @csrf
                        @method('PUT')
                        <input name="title" value="{{ $post->title }}" class="input-shell">
                        <input name="slug" value="{{ $post->slug }}" class="input-shell">
                        <input name="category" value="{{ $post->category }}" class="input-shell">
                        <input name="author_name" value="{{ $post->author_name }}" class="input-shell">
                        <input name="read_time" value="{{ $post->read_time }}" class="input-shell">
                        <textarea name="excerpt" rows="3" class="input-shell">{{ $post->excerpt }}</textarea>
                        <textarea name="intro" rows="4" class="input-shell">{{ $post->intro }}</textarea>
                        <textarea name="quote" rows="3" class="input-shell">{{ $post->quote }}</textarea>
                        <input name="cover_image" value="{{ $post->cover_image }}" class="input-shell" placeholder="CDN image/video URL or local path">
                        <input type="file" name="cover_image_upload" accept="image/*,video/*" data-preview-target="#post-cover-preview-{{ $post->id }}" class="input-shell">
                        <p id="post-cover-preview-{{ $post->id }}" class="text-xs text-muted-foreground">{{ $post->cover_image ?: 'No new file selected' }}</p>
                        <label class="flex items-center gap-2 text-xs text-muted-foreground">
                            <input type="checkbox" name="remove_cover_image" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                            Remove current cover media if no new link or upload is provided
                        </label>
                        <textarea name="content_json" rows="6" class="input-shell">{{ json_encode($post->content ?? [], JSON_PRETTY_PRINT) }}</textarea>
                        <input type="datetime-local" name="published_at" value="{{ optional($post->published_at)->format('Y-m-d\TH:i') }}" class="input-shell">
                        <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="featured" value="1" @checked($post->featured)> Featured</label>
                        <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_published" value="1" @checked($post->is_published)> Published</label>
                        <button class="luxury-btn-primary">Update Post</button>
                    </form>
                </div>
            @endforeach
        </div>
        <div class="luxury-card">
            <h2 class="font-display text-3xl">Create Post</h2>
            <form action="{{ route('admin.cms.blog.store') }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                @csrf
                <input name="title" required placeholder="Post title" class="input-shell">
                <input name="category" required placeholder="Category" class="input-shell">
                <input name="author_name" required placeholder="Author name" class="input-shell">
                <input name="read_time" placeholder="Read time" class="input-shell">
                <textarea name="excerpt" rows="3" required placeholder="Excerpt" class="input-shell"></textarea>
                <textarea name="intro" rows="4" placeholder="Intro" class="input-shell"></textarea>
                <textarea name="quote" rows="3" placeholder="Quote" class="input-shell"></textarea>
                <input name="cover_image" placeholder="CDN cover image/video URL or local path" class="input-shell">
                <input type="file" name="cover_image_upload" accept="image/*,video/*" data-preview-target="#create-post-cover-preview" class="input-shell">
                <p id="create-post-cover-preview" class="text-xs text-muted-foreground">No new file selected</p>
                <textarea name="content_json" rows="6" placeholder='Article content JSON' class="input-shell"></textarea>
                <input type="datetime-local" name="published_at" class="input-shell">
                <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="featured" value="1"> Featured</label>
                <label class="flex items-center gap-3 text-sm text-muted-foreground"><input type="checkbox" name="is_published" value="1" checked> Published</label>
                <button class="luxury-btn-primary w-full">Create Post</button>
            </form>
        </div>
    </div>
</x-layouts.admin>

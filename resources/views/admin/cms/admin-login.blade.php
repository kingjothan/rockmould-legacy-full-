<x-layouts.admin :company="$company" page-heading="Admin Login CMS">
    @php
        $loginMediaPath = $loginContent['media_path'] ?? null;
        $loginMediaUrl = $loginMediaPath ? \App\Support\AssetPath::url($loginMediaPath) : null;
        $loginMediaExt = strtolower(pathinfo((string) parse_url((string) $loginMediaPath, PHP_URL_PATH), PATHINFO_EXTENSION));
        $loginMediaIsVideo = in_array($loginMediaExt, ['mp4', 'webm', 'ogg', 'mov'], true);
    @endphp

    <div class="max-w-4xl">
        <form action="{{ route('admin.cms.admin-login.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            @if (session('success'))
                <div class="flex items-center gap-3 rounded-lg border border-emerald-500/30 bg-emerald-500/10 px-4 py-3 text-sm text-emerald-400">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-destructive/25 bg-destructive/10 px-4 py-3 text-sm text-destructive">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="luxury-card space-y-6">
                <div>
                    <p class="section-label">Admin Login Page</p>
                    <h2 class="font-display text-3xl">Admin Login Branding</h2>
                    <p class="mt-2 text-sm text-muted-foreground">Set a background image or video for the left panel of the admin login page, and customise the title and description shown there.</p>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="space-y-4">
                        <div>
                            <label class="admin-label">Panel Title</label>
                            <input name="login_title" value="{{ old('login_title', $loginContent['title'] ?? 'Rockmould Admin') }}" class="input-shell">
                        </div>
                        <div>
                            <label class="admin-label">Panel Description</label>
                            <textarea name="login_description" rows="4" class="input-shell">{{ old('login_description', $loginContent['description'] ?? '') }}</textarea>
                        </div>
                        <div>
                            <label class="admin-label">Background Media Link or Path</label>
                            <input name="login_media_path" value="{{ old('login_media_path', $loginMediaPath) }}" class="input-shell" placeholder="https://cdn.example.com/admin-bg.mp4">
                            <p class="mt-2 text-xs text-muted-foreground">Paste a CDN/direct image or video URL, or use a local path.</p>
                        </div>
                        <div>
                            <label class="admin-label">Upload Background Media</label>
                            <input type="file" name="login_media_upload" accept="image/*,video/*" class="input-shell">
                            <label class="mt-3 flex items-center gap-2 text-xs text-muted-foreground">
                                <input type="checkbox" name="remove_login_media" value="1" class="h-4 w-4 rounded border-border bg-transparent text-primary focus:ring-primary">
                                Remove current background media if no new link or upload is provided
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="overflow-hidden rounded-lg border border-border bg-card">
                            <div class="aspect-[16/10] bg-muted">
                                @if ($loginMediaUrl)
                                    @if ($loginMediaIsVideo)
                                        <video controls autoplay muted playsinline preload="auto" class="h-full w-full object-cover">
                                            <source src="{{ $loginMediaUrl }}">
                                        </video>
                                    @else
                                        <img src="{{ $loginMediaUrl }}" alt="Admin login background" class="h-full w-full object-cover">
                                    @endif
                                @else
                                    <div class="flex h-full items-center justify-center text-sm text-muted-foreground">No background media set</div>
                                @endif
                            </div>
                            <div class="border-t border-border px-4 py-4">
                                <p class="text-xs uppercase tracking-[0.18em] text-primary/70">Current Background Media</p>
                                <p class="mt-1 text-sm text-muted-foreground">{{ $loginMediaPath ?: 'Not set yet' }}</p>
                            </div>
                        </div>
                        <div class="rounded-lg border border-border bg-background/40 p-4 text-sm text-muted-foreground">
                            <p class="mb-1 font-medium text-foreground">Preview</p>
                            <p>When a background is set, the left panel of the admin login page will show the image or video behind the title and description, with a dark gradient overlay for readability.</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button class="luxury-btn-primary">Save Admin Login</button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.admin>

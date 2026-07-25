<x-layouts.base title="Admin Login | Rockmould Limited">
    @php
        $loginMediaPath = $loginContent['media_path'] ?? null;
        $loginMediaUrl = $loginMediaPath ? \App\Support\AssetPath::url($loginMediaPath) : null;
        $loginMediaExt = strtolower(pathinfo((string) parse_url((string) $loginMediaPath, PHP_URL_PATH), PATHINFO_EXTENSION));
        $loginMediaIsVideo = in_array($loginMediaExt, ['mp4', 'webm', 'ogg', 'mov'], true);
        $loginTitle = $loginContent['title'] ?? 'Rockmould Admin';
        $loginDescription = $loginContent['description'] ?? 'Enterprise content management system for managing clients, projects, and website content.';
    @endphp
    <div class="min-h-screen bg-background lg:flex">
        <div class="relative hidden overflow-hidden border-r border-border bg-card lg:flex lg:flex-1 lg:items-center lg:justify-center">
            @if ($loginMediaUrl)
                @if ($loginMediaIsVideo)
                    <video autoplay muted loop playsinline preload="auto" class="hero-media" aria-hidden="true">
                        <source src="{{ $loginMediaUrl }}">
                    </video>
                @else
                    <img src="{{ $loginMediaUrl }}" alt="Admin login background" class="hero-media">
                @endif
                <div class="hero-soft-overlay"></div>
                <div class="hero-overlay"></div>
            @endif
            <div class="relative z-10 max-w-md px-8 text-center">
                <x-brand-mark class="mx-auto mb-8 h-16 w-auto" />
                <h1 class="mb-4 font-display text-3xl font-bold">{{ $loginTitle }}</h1>
                <p class="text-sm leading-relaxed text-muted-foreground">{{ $loginDescription }}</p>
                <div class="luxury-divider my-8"></div>
                <p class="text-xs text-muted-foreground">Authorized personnel only</p>
            </div>
        </div>
        <div class="flex flex-1 items-center justify-center p-8">
            <div class="w-full max-w-sm">
                <div class="mb-8 text-center lg:hidden">
                    <x-brand-mark class="mx-auto mb-4 h-12 w-auto" />
                    <h1 class="font-display text-xl font-bold">Admin Login</h1>
                </div>
                <div class="hidden lg:block">
                    <h2 class="mb-2 font-display text-2xl font-semibold">Welcome back</h2>
                    <p class="mb-8 text-sm text-muted-foreground">Sign in to your admin account</p>
                </div>
                <form action="{{ route('admin.authenticate') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-xs uppercase tracking-wider text-muted-foreground">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@rockmould.com" class="input-shell">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs uppercase tracking-wider text-muted-foreground">Password</label>
                        <input type="password" name="password" placeholder="Enter your password" class="input-shell">
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex cursor-pointer items-center gap-2 text-xs text-muted-foreground">
                            <input type="checkbox" name="remember" class="rounded border-border">
                            Remember me
                        </label>
                        <button type="button" class="text-xs text-primary hover:underline">Forgot password?</button>
                    </div>
                    <button type="submit" class="luxury-btn-primary flex w-full items-center justify-center gap-2 text-sm py-3">Sign In</button>
                </form>
                <p class="mt-8 text-center text-xs text-muted-foreground">
                    <a href="{{ route('home') }}" class="text-primary hover:underline">Back to Website</a>
                </p>
            </div>
        </div>
    </div>
</x-layouts.base>

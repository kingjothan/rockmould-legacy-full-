<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Rockmould Limited' }}</title>
    <meta name="description" content="{{ $description ?? 'Premium real estate and construction by Rockmould Limited.' }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script>
        (() => {
            try {
                const storedTheme = window.localStorage.getItem('rockmould-theme');
                const theme = storedTheme === 'light' || storedTheme === 'dark'
                    ? storedTheme
                    : (window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark');

                document.documentElement.dataset.theme = theme;
                document.documentElement.style.colorScheme = theme;
            } catch (error) {
                document.documentElement.dataset.theme = 'dark';
                document.documentElement.style.colorScheme = 'dark';
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="{{ $bodyClass ?? '' }}">
    @if (session('success'))
        <div data-auto-hide class="fixed inset-x-0 top-5 z-[70] mx-auto w-full max-w-xl px-4 transition-opacity duration-300">
            <div class="luxury-card border-primary/30 bg-card/95 text-center text-sm text-foreground">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (($errors ?? null) && $errors->any())
        <div class="fixed inset-x-0 top-5 z-[70] mx-auto w-full max-w-xl px-4">
            <div class="luxury-card border-destructive/40 bg-card/95 text-center text-sm text-destructive">
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    {{ $slot }}

    <button
        type="button"
        class="theme-toggle"
        data-theme-toggle
        aria-pressed="false"
        aria-label="Switch to light theme"
    >
        <span class="theme-toggle-icon-shell" aria-hidden="true">
            <span class="theme-toggle-icon" data-theme-icon="dark">
                <x-portal-icon name="moon" class="h-4 w-4" />
            </span>
            <span class="theme-toggle-icon" data-theme-icon="light">
                <x-portal-icon name="sun" class="h-4 w-4" />
            </span>
        </span>
        <span class="theme-toggle-copy">
            <span class="theme-toggle-kicker">Appearance</span>
            <span class="theme-toggle-label" data-theme-label>Dark Mode</span>
        </span>
    </button>
</body>
</html>

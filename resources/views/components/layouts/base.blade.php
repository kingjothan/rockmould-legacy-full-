<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Rockmould Limited' }}</title>
    <meta name="description" content="{{ $description ?? 'Premium Real Estate Development by Rockmould Limited.' }}">
    <meta name="developer" content="KJ">
    <meta name="developer:github" content="https://github.com/kingjothan">
    <link rel="me" href="https://github.com/kingjothan">
    {{-- Company --}}
    <meta name="author" content="Rockmould Limited">
    <meta name="contact:phone" content="+2342014537464">
    <meta name="contact:email" content="info@rockmould.com">
    <meta name="contact:address" content="24A Taiye Olowu St, Lekki Phase 1, Lagos, Nigeria">
    <meta name="contact:whatsapp" content="+2342014537464">
    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Rockmould Limited">
    <meta property="og:title" content="{{ $title ?? 'Rockmould Limited' }}">
    <meta property="og:description" content="{{ $description ?? 'Premium Real Estate Development by Rockmould Limited.' }}">
    <meta property="og:image" content="{{ asset('images/rockmould_logo.png') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    {{-- Twitter / X --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Rockmould Limited' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Premium Real Estate Development by Rockmould Limited.' }}">
    <meta name="twitter:image" content="{{ asset('images/rockmould_logo.png') }}">
    {{-- Search / Robots --}}
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0a0a0a">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/rockmould_logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/rockmould_logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/rockmould_logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @if (file_exists(public_path('fonts/trajan-pro-bold.woff2')))
        <link rel="preload" href="{{ asset('fonts/trajan-pro-bold.woff2') }}" as="font" type="font/woff2" crossorigin>
    @endif
    @if (file_exists(public_path('fonts/gotham-book.woff2')))
        <link rel="preload" href="{{ asset('fonts/gotham-book.woff2') }}" as="font" type="font/woff2" crossorigin>
    @endif
    @if (file_exists(public_path('fonts/gotham-medium.woff2')))
        <link rel="preload" href="{{ asset('fonts/gotham-medium.woff2') }}" as="font" type="font/woff2" crossorigin>
    @endif
    @if (file_exists(public_path('fonts/gotham-bold.woff2')))
        <link rel="preload" href="{{ asset('fonts/gotham-bold.woff2') }}" as="font" type="font/woff2" crossorigin>
    @endif
    <script>document.documentElement.dataset.theme='dark';document.documentElement.style.colorScheme='dark';</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="{{ $attributes->get('body-class', '') }}">
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

</body>
</html>

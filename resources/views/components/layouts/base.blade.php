<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Rockmould Limited' }}</title>
    <meta name="description" content="{{ $description ?? 'Premium real estate and construction by Rockmould Limited.' }}">
    <meta name="developer" content="KJ">
    <meta name="developer:phone" content="+2349060037002">
    <meta name="developer:email" content="okoiruelejonathan@gmail.com">
    <meta name="developer:github" content="https://github.com/kingjothan">
    <meta name="contact:phone" content="+2349060037002">
    <meta name="contact:email" content="okoiruelejonathan@gmail.com">
    <link rel="me" href="https://github.com/kingjothan">
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

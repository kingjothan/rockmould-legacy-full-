<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Rockmould Limited' }}</title>
    <meta name="description" content="{{ $description ?? 'Premium real estate and construction by Rockmould Limited. Building legacies that endure.' }}">
    <meta name="robots" content="index, follow">
    <meta name="developer" content="KJ">
    <meta name="developer:phone" content="+2349060037002">
    <meta name="developer:email" content="okoiruelejonathan@gmail.com">
    <meta name="developer:github" content="https://github.com/kingjothan">
    <meta name="contact:phone" content="+2349060037002">
    <meta name="contact:email" content="okoiruelejonathan@gmail.com">
    <link rel="me" href="https://github.com/kingjothan">

    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $title ?? 'Rockmould Limited' }}">
    <meta property="og:description" content="{{ $description ?? 'Premium real estate and construction by Rockmould Limited.' }}">
    <meta property="og:image" content="{{ asset('images/project-1.jpg') }}">
    <meta property="og:locale" content="en_NG">
    <meta property="og:site_name" content="Rockmould Limited">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? 'Rockmould Limited' }}">
    <meta name="twitter:description" content="{{ $description ?? 'Premium real estate and construction by Rockmould Limited.' }}">
    <meta name="twitter:image" content="{{ asset('images/project-1.jpg') }}">

    <link rel="icon" type="image/png" href="{{ asset('images/rockmould_logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/rockmould_logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/rockmould_logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="{{ $bodyClass ?? '' }}">

    {{-- Page progress bar --}}
    <div class="page-progress" id="page-progress" aria-hidden="true"></div>

    {{-- Success notification --}}
    @if (session('success'))
        <div data-auto-hide class="fixed inset-x-0 top-5 z-[70] mx-auto w-full max-w-md px-4 transition-opacity duration-300">
            <div class="notification-success flex items-start gap-3">
                <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-500/15">
                    <svg class="h-3 w-3 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"></path>
                    </svg>
                </div>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Error notification --}}
    @if (($errors ?? null) && $errors->any())
        <div data-auto-hide class="fixed inset-x-0 top-5 z-[70] mx-auto w-full max-w-md px-4 transition-opacity duration-300">
            <div class="notification-error flex items-start gap-3">
                <div class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-destructive/15">
                    <svg class="h-3 w-3 text-destructive" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                    </svg>
                </div>
                <div>
                    @foreach ($errors->all() as $error)
                        <p class="text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{ $slot }}

    {{-- Back to top button --}}
    <button
        id="back-to-top"
        type="button"
        class="back-to-top"
        aria-label="Back to top"
    >
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="m18 15-6-6-6 6"></path>
        </svg>
    </button>

</body>
</html>

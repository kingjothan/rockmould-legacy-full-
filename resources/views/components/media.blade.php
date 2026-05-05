@props([
    'path' => null,
    'fallback' => null,
    'alt' => '',
    'class' => 'h-full w-full object-cover',
    'controls' => false,
    'autoplay' => true,
    'loop' => true,
    'muted' => true,
    'playsinline' => true,
    'lightbox' => true,
    'background' => false,
])

@php
    $resolvedPath = $path ?: $fallback;
    $mediaUrl = \App\Support\AssetPath::url($path, $fallback);
    $isVideo = \App\Support\AssetPath::isVideo($resolvedPath);
    $enableLightbox = $lightbox && ! $background;
@endphp

@if ($mediaUrl && $isVideo)
    <video
        @if ($controls) controls @endif
        autoplay
        @if ($loop) loop @endif
        muted
        @if ($playsinline) playsinline @endif
        preload="auto"
        @if ($background) data-background-video aria-hidden="true" @endif
        @if ($enableLightbox)
            data-video-lightbox
            data-video-src="{{ $mediaUrl }}"
            data-video-title="{{ $alt }}"
            tabindex="0"
            role="button"
            aria-label="{{ trim($alt) ? 'Play '.$alt : 'Play video' }}"
        @endif
        {{ $attributes->merge(['class' => $class]) }}
    >
        <source src="{{ $mediaUrl }}">
    </video>
@elseif ($mediaUrl)
    <img
        src="{{ $mediaUrl }}"
        alt="{{ $alt }}"
        @if ($background) aria-hidden="true" @endif
        @if ($enableLightbox)
            data-image-lightbox
            data-image-src="{{ $mediaUrl }}"
            data-image-title="{{ $alt }}"
        @endif
        {{ $attributes->merge(['class' => $class]) }}
    >
@else
    <div {{ $attributes->merge(['class' => $class.' flex items-center justify-center bg-muted text-muted-foreground']) }}>
        {{ $slot->isEmpty() ? 'Media unavailable' : $slot }}
    </div>
@endif

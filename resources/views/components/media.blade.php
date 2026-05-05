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
])

@php
    $resolvedPath = $path ?: $fallback;
    $mediaUrl = \App\Support\AssetPath::url($path, $fallback);
    $isVideo = \App\Support\AssetPath::isVideo($resolvedPath);
@endphp

@if ($mediaUrl && $isVideo)
    <video
        @if ($controls) controls @endif
        autoplay
        @if ($loop) loop @endif
        muted
        @if ($playsinline) playsinline @endif
        preload="auto"
        data-video-lightbox
        data-video-src="{{ $mediaUrl }}"
        data-video-title="{{ $alt }}"
        tabindex="0"
        role="button"
        aria-label="{{ trim($alt) ? 'Play '.$alt : 'Play video' }}"
        {{ $attributes->merge(['class' => $class]) }}
    >
        <source src="{{ $mediaUrl }}">
    </video>
@elseif ($mediaUrl)
    <img
        src="{{ $mediaUrl }}"
        alt="{{ $alt }}"
        data-image-lightbox
        data-image-src="{{ $mediaUrl }}"
        data-image-title="{{ $alt }}"
        {{ $attributes->merge(['class' => $class]) }}
    >
@else
    <div {{ $attributes->merge(['class' => $class.' flex items-center justify-center bg-muted text-muted-foreground']) }}>
        {{ $slot->isEmpty() ? 'Media unavailable' : $slot }}
    </div>
@endif

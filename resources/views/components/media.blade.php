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
        @if ($autoplay) autoplay @endif
        @if ($loop) loop @endif
        @if ($muted) muted @endif
        @if ($playsinline) playsinline @endif
        {{ $attributes->merge(['class' => $class]) }}
    >
        <source src="{{ $mediaUrl }}">
    </video>
@elseif ($mediaUrl)
    <img src="{{ $mediaUrl }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => $class]) }}>
@else
    <div {{ $attributes->merge(['class' => $class.' flex items-center justify-center bg-muted text-muted-foreground']) }}>
        {{ $slot->isEmpty() ? 'Media unavailable' : $slot }}
    </div>
@endif

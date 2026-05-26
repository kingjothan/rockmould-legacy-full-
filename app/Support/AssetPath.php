<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AssetPath
{
    public static function url(?string $path, ?string $fallback = null): ?string
    {
        $resolved = $path ?: $fallback;

        if (! $resolved) {
            return null;
        }

        if (Str::startsWith($resolved, ['http://', 'https://', '//', 'data:'])) {
            return $resolved;
        }

        $normalized = ltrim($resolved, '/');

        if (Str::startsWith($normalized, ['images/'])) {
            return asset($normalized);
        }

        if (Str::startsWith($normalized, ['storage/'])) {
            return static::publicDiskUrl(Str::after($normalized, 'storage/'));
        }

        return static::publicDiskUrl($normalized);
    }

    public static function localPath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//', 'data:'])) {
            return null;
        }

        $normalized = ltrim($path, '/');

        if (Str::startsWith($normalized, ['images/', 'storage/'])) {
            return public_path($normalized);
        }

        return Storage::disk('public')->path($normalized);
    }

    public static function extension(?string $path): string
    {
        if (! $path) {
            return '';
        }

        $target = (string) parse_url($path, PHP_URL_PATH);

        return strtolower(pathinfo($target, PATHINFO_EXTENSION));
    }

    public static function isVideo(?string $path): bool
    {
        return in_array(static::extension($path), ['mp4', 'webm', 'ogg', 'mov'], true);
    }

    private static function publicDiskUrl(string $path): string
    {
        $normalized = trim(str_replace('\\', '/', $path), '/');
        $publicPath = public_path('storage/'.$normalized);

        if (is_file($publicPath)) {
            return asset('storage/'.$normalized);
        }

        if (Storage::disk('public')->exists($normalized)) {
            return url('uploaded-media/'.static::encodePath($normalized));
        }

        return Storage::disk('public')->url($normalized);
    }

    private static function encodePath(string $path): string
    {
        return implode('/', array_map('rawurlencode', explode('/', $path)));
    }
}

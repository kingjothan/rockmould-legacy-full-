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

        $normalized = ltrim($resolved, '/');

        if (Str::startsWith($normalized, ['http://', 'https://', '//', 'data:'])) {
            return $resolved;
        }

        if (Str::startsWith($normalized, ['images/', 'storage/'])) {
            return asset($normalized);
        }

        return Storage::disk('public')->url($normalized);
    }

    public static function localPath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $normalized = ltrim($path, '/');

        if (Str::startsWith($normalized, ['http://', 'https://', '//', 'data:'])) {
            return null;
        }

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
}

<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PublicStorage
{
    public static function store(UploadedFile $file, string $directory): string
    {
        $path = $file->store($directory, 'public');
        self::mirror($path);

        return $path;
    }

    public static function delete(?string $path): void
    {
        $normalized = self::normalizablePath($path);

        if (! $normalized) {
            return;
        }

        Storage::disk('public')->delete($normalized);

        $mirrorPath = self::mirrorPath($normalized);

        if ($mirrorPath && File::exists($mirrorPath)) {
            File::delete($mirrorPath);
        }
    }

    public static function mirror(?string $path): void
    {
        $normalized = self::normalizablePath($path);

        if (! $normalized) {
            return;
        }

        $sourcePath = Storage::disk('public')->path($normalized);
        $mirrorPath = self::mirrorPath($normalized);

        if (! $mirrorPath || ! File::exists($sourcePath)) {
            return;
        }

        File::ensureDirectoryExists(dirname($mirrorPath));
        File::copy($sourcePath, $mirrorPath);
    }

    private static function normalizablePath(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        $normalized = ltrim($path, '/');

        if (
            str_starts_with($normalized, 'images/')
            || str_starts_with($normalized, 'storage/')
            || str_starts_with($normalized, 'http://')
            || str_starts_with($normalized, 'https://')
            || str_starts_with($normalized, '//')
            || str_starts_with($normalized, 'data:')
        ) {
            return null;
        }

        return $normalized;
    }

    private static function mirrorPath(string $normalized): ?string
    {
        $publicStorageRoot = public_path('storage');

        if (! File::isDirectory($publicStorageRoot) || is_link($publicStorageRoot)) {
            return null;
        }

        return $publicStorageRoot.DIRECTORY_SEPARATOR.str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $normalized);
    }
}

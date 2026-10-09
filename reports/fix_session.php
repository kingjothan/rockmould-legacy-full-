<?php
// Clear all caches so new .env values take effect
\Illuminate\Support\Facades\Artisan::call('config:clear');
\Illuminate\Support\Facades\Artisan::call('cache:clear');
\Illuminate\Support\Facades\Artisan::call('view:clear');
\Illuminate\Support\Facades\Artisan::call('route:clear');

// Also clear session files
$sessionPath = storage_path('framework/sessions');
if (is_dir($sessionPath)) {
    foreach (glob($sessionPath . '/*') as $file) {
        if (is_file($file)) unlink($file);
    }
}

echo "Done. Config, cache, views, routes and sessions cleared.\n";

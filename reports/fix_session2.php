<?php
$envPath = __DIR__ . '/../.env';
$content = file_get_contents($envPath);

// Use http since no SSL cert on subdomain yet
$content = str_replace(
    'APP_URL=https://app.rockmouldsite.com',
    'APP_URL=http://app.rockmouldsite.com',
    $content
);

// Clear SESSION_DOMAIN so it works on any domain/subdomain
$content = str_replace(
    'SESSION_DOMAIN=app.rockmouldsite.com',
    'SESSION_DOMAIN=',
    $content
);

file_put_contents($envPath, $content);

// Clear config cache so changes take effect
\Illuminate\Support\Facades\Artisan::call('config:clear');
\Illuminate\Support\Facades\Artisan::call('cache:clear');

// Clear session files
$sessionPath = storage_path('framework/sessions');
if (is_dir($sessionPath)) {
    foreach (glob($sessionPath . '/*') as $file) {
        if (is_file($file)) unlink($file);
    }
}

echo "Done.\n";

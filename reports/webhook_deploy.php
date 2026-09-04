<?php
// Secret deploy webhook — accessible via HTTPS on port 443
// URL: https://rockmouldsite.com/deploy-rm9x7k.php?key=rm_deploy_2024

$SECRET = 'rm_deploy_2024';
$APP_ROOT = '/home/rockctyy/rockmould';
$GITHUB_RAW = 'https://raw.githubusercontent.com/kingjothan/rockmould-legacy-full-/main';

if (($_GET['key'] ?? '') !== $SECRET) {
    http_response_code(403);
    die(json_encode(['error' => 'Forbidden']));
}

header('Content-Type: application/json');

$files = $_GET['files'] ?? 'all';
$log = [];

$deployFiles = [
    'resources/views/site/privacy.blade.php',
    'resources/views/site/terms.blade.php',
    'resources/views/admin/cms/legal.blade.php',
    'resources/views/site/home.blade.php',
    'resources/views/site/about.blade.php',
    'resources/views/site/contact.blade.php',
    'resources/views/site/projects.blade.php',
    'resources/views/site/services.blade.php',
    'resources/views/admin/cms/pages.blade.php',
    'app/Http/Controllers/Admin/CmsController.php',
    'app/Http/Controllers/SiteController.php',
    'app/Support/SiteData.php',
    'routes/web.php',
];

// Deploy specific file or all
$targets = [];
if ($files === 'all') {
    $targets = $deployFiles;
} else {
    $targets = explode(',', $files);
}

foreach ($targets as $rel) {
    $rel = trim($rel);
    $url = $GITHUB_RAW . '/' . $rel;
    $dest = $APP_ROOT . '/' . $rel;
    $dir = dirname($dest);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    $content = @file_get_contents($url);
    if ($content === false || str_contains($content, '"message":"Not Found"')) {
        $log[] = ['file' => $rel, 'status' => 'skip - not found on GitHub'];
        continue;
    }
    file_put_contents($dest, $content);
    $log[] = ['file' => $rel, 'status' => 'ok', 'bytes' => strlen($content)];
}

// Optionally run seeder
if (isset($_GET['seed'])) {
    try {
        require $APP_ROOT . '/vendor/autoload.php';
        $app = require_once $APP_ROOT . '/bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        use App\Models\Setting;
        $current = Setting::query()->where('key', 'legal_pages')->value('value') ?? [];
        $log[] = ['seed' => 'legal_pages has ' . count($current['privacy_sections'] ?? []) . ' privacy, ' . count($current['terms_sections'] ?? []) . ' terms sections'];
    } catch (\Throwable $e) {
        $log[] = ['seed_error' => $e->getMessage()];
    }
}

// Clear view cache
@shell_exec('cd ' . $APP_ROOT . ' && php artisan view:clear 2>&1');
$log[] = ['cache' => 'views cleared'];

echo json_encode(['status' => 'ok', 'log' => $log], JSON_PRETTY_PRINT);

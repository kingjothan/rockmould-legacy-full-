<?php
$base = dirname(__DIR__);
$files = [
    $base.'/app/Support/SiteData.php',
    $base.'/app/Http/Controllers/Admin/CmsController.php',
    $base.'/app/Http/Controllers/SiteController.php',
    $base.'/resources/views/admin/cms/pages.blade.php',
    $base.'/resources/views/site/portfolio.blade.php',
];

$results = [];
foreach ($files as $file) {
    $results[] = basename($file).' exists: '.(file_exists($file) ? 'yes ('.filesize($file).' bytes)' : 'NO');
}

$artisan = $base.'/artisan';
exec("php $artisan view:clear 2>&1", $o1);
exec("php $artisan config:clear 2>&1", $o2);
exec("php $artisan cache:clear 2>&1", $o3);

echo implode("\n", $results)."\n";
echo "view:clear: ".implode(' ', $o1)."\n";
echo "config:clear: ".implode(' ', $o2)."\n";
echo "cache:clear: ".implode(' ', $o3)."\n";
echo "Done.\n";

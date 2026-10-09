<?php
$base = dirname(__DIR__);
$envPath = $base . '/.env';
$content = file_get_contents($envPath);

// Force http (no SSL cert yet)
$content = preg_replace('/^APP_URL=.*$/m', 'APP_URL=http://app.rockmouldsite.com', $content);

// Clear SESSION_DOMAIN
$content = preg_replace('/^SESSION_DOMAIN=.*$/m', 'SESSION_DOMAIN=', $content);

// Set SESSION_SECURE_COOKIE=false (critical for http)
if (preg_match('/^SESSION_SECURE_COOKIE=/m', $content)) {
    $content = preg_replace('/^SESSION_SECURE_COOKIE=.*$/m', 'SESSION_SECURE_COOKIE=false', $content);
} else {
    $content = rtrim($content) . "\nSESSION_SECURE_COOKIE=false\n";
}

// Set SESSION_SAME_SITE=lax
if (preg_match('/^SESSION_SAME_SITE=/m', $content)) {
    $content = preg_replace('/^SESSION_SAME_SITE=.*$/m', 'SESSION_SAME_SITE=lax', $content);
} else {
    $content = rtrim($content) . "\nSESSION_SAME_SITE=lax\n";
}

file_put_contents($envPath, $content);
echo "Updated .env\n";

// Clear caches via artisan
$artisan = $base . '/artisan';
exec("php $artisan config:clear 2>&1", $o1); echo implode("\n", $o1) . "\n";
exec("php $artisan cache:clear 2>&1", $o2); echo implode("\n", $o2) . "\n";
exec("php $artisan config:cache 2>&1", $o3); echo implode("\n", $o3) . "\n";

// Clear session files
$count = 0;
foreach (glob($base . '/storage/framework/sessions/*') as $f) {
    if (is_file($f)) { unlink($f); $count++; }
}
echo "Cleared $count session files.\n";

// Confirm values
preg_match('/^APP_URL=(.*)$/m', $content, $a);
preg_match('/^SESSION_DOMAIN=(.*)$/m', $content, $b);
preg_match('/^SESSION_SECURE_COOKIE=(.*)$/m', $content, $c);
preg_match('/^SESSION_SAME_SITE=(.*)$/m', $content, $d);
echo "APP_URL=" . trim($a[1] ?? '') . "\n";
echo "SESSION_DOMAIN=" . trim($b[1] ?? '(empty)') . "\n";
echo "SESSION_SECURE_COOKIE=" . trim($c[1] ?? '') . "\n";
echo "SESSION_SAME_SITE=" . trim($d[1] ?? '') . "\n";
echo "Done.\n";

<?php
$envPath = __DIR__ . '/../.env';
$content = file_get_contents($envPath);

$content = str_replace(
    'APP_URL=https://rockmouldsite.com',
    'APP_URL=https://app.rockmouldsite.com',
    $content
);
$content = str_replace(
    'SESSION_DOMAIN=rockmouldsite.com',
    'SESSION_DOMAIN=app.rockmouldsite.com',
    $content
);

file_put_contents($envPath, $content);
echo "Done. APP_URL and SESSION_DOMAIN updated to app.rockmouldsite.com\n";

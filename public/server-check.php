<?php

/**
 * Standalone check — only works if this file is in the web root (public/).
 * If you get 404, use Laravel route instead: /farmsea/server-check
 */
header('Content-Type: text/html; charset=utf-8');

$root = dirname(__DIR__);
$storagePublic = $root . '/storage/app/public';
$envFile = $root . '/.env';

echo '<h1>FarmSea Server Check (PHP file)</h1><pre>';

echo 'PHP version: ' . PHP_VERSION . "\n";
echo 'GD extension: ' . (extension_loaded('gd') ? 'YES' : 'NO') . "\n";
echo 'PDO MySQL: ' . (extension_loaded('pdo_mysql') ? 'YES' : 'NO') . "\n";
echo '.env file: ' . (is_file($envFile) ? 'FOUND' : 'MISSING') . "\n";
echo 'vendor/autoload.php: ' . (is_file($root . '/vendor/autoload.php') ? 'FOUND' : 'MISSING') . "\n";
echo 'storage/app/public writable: ' . (is_writable($storagePublic) ? 'YES' : 'NO') . "\n";
echo 'bootstrap/cache writable: ' . (is_writable($root . '/bootstrap/cache') ? 'YES' : 'NO') . "\n";
echo 'public/storage exists: ' . (file_exists(__DIR__ . '/storage') ? 'YES' : 'NO') . "\n";
echo 'Logo: ' . (file_exists(__DIR__ . '/images/Farmsea.webp') ? 'YES' : 'NO') . "\n";

if (is_file($envFile)) {
    $env = file_get_contents($envFile);
    preg_match('/^APP_URL=(.*)$/m', $env, $m);
    preg_match('/^DB_DATABASE=(.*)$/m', $env, $db);
    echo 'APP_URL: ' . trim($m[1] ?? 'not set') . "\n";
    echo 'DB_DATABASE: ' . trim($db[1] ?? 'not set') . "\n";
}

echo "\nIf this file shows 404, open instead:\n";
echo "https://bmdublog.com/farmsea/server-check\n";
echo '</pre>';

<?php

namespace App\Support;

class ServerDiagnostics
{
    public static function run(): array
    {
        $root = base_path();
        $public = public_path();
        $envFile = base_path('.env');

        $checks = [
            'php_version' => PHP_VERSION,
            'gd' => extension_loaded('gd'),
            'pdo_mysql' => extension_loaded('pdo_mysql'),
            'env_file' => is_file($envFile),
            'vendor' => is_file($root . '/vendor/autoload.php'),
            'storage_writable' => is_writable(storage_path('app/public')),
            'bootstrap_cache_writable' => is_writable(base_path('bootstrap/cache')),
            'public_storage_link' => file_exists($public . '/storage'),
            'logo' => file_exists($public . '/images/Farmsea.webp'),
            'app_url_env' => null,
            'db_database_env' => null,
            'laravel_boot' => true,
            'database' => self::checkDatabase(),
        ];

        if (is_file($envFile)) {
            $env = file_get_contents($envFile);
            preg_match('/^APP_URL=(.*)$/m', $env, $appUrl);
            preg_match('/^DB_DATABASE=(.*)$/m', $env, $dbName);
            $checks['app_url_env'] = trim($appUrl[1] ?? 'not set', " \t\n\r\0\x0B\"'");
            $checks['db_database_env'] = trim($dbName[1] ?? 'not set', " \t\n\r\0\x0B\"'");
        }

        return $checks;
    }

    private static function checkDatabase(): array
    {
        try {
            \Illuminate\Support\Facades\DB::connection()->getPdo();

            return ['ok' => true, 'message' => 'Connected'];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }
}

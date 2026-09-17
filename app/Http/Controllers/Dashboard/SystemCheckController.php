<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class SystemCheckController extends Controller
{
    public function __invoke()
    {
        $checks = [
            'php_version' => PHP_VERSION,
            'gd' => extension_loaded('gd'),
            'exif' => extension_loaded('exif'),
            'database' => $this->checkDatabase(),
            'storage_writable' => is_writable(storage_path('app/public')),
            'storage_link' => File::exists(public_path('storage')) || true,
            'logo_file' => File::exists(public_path('images/porville-logo.jpg')),
            'app_url' => config('app.url'),
            'migrations' => $this->pendingMigrations(),
            'required_columns' => $this->requiredColumns(),
        ];

        $hasErrors = ! $checks['database']['ok']
            || ! $checks['storage_writable']
            || $checks['migrations']['pending'] > 0
            || ! empty($checks['required_columns']['missing']);

        return view('dashboard.system-check', compact('checks', 'hasErrors'));
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();

            return ['ok' => true, 'message' => 'Connected'];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }

    private function pendingMigrations(): array
    {
        try {
            Artisan::call('migrate:status', ['--no-ansi' => true]);
            $output = Artisan::output();
            $pending = substr_count($output, 'Pending');

            return ['pending' => $pending, 'output' => $output];
        } catch (\Throwable $e) {
            return ['pending' => -1, 'output' => $e->getMessage()];
        }
    }

    private function requiredColumns(): array
    {
        $required = [
            'products' => ['variants', 'subcategory_id', 'videos'],
            'coupons' => ['entry_type', 'title'],
        ];

        $missing = [];

        foreach ($required as $table => $columns) {
            if (! Schema::hasTable($table)) {
                $missing[$table] = ['table missing'];

                continue;
            }

            foreach ($columns as $column) {
                if (! Schema::hasColumn($table, $column)) {
                    $missing[$table][] = $column;
                }
            }
        }

        return ['missing' => $missing];
    }
}

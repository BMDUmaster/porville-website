<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('porville:setup', function () {
    $this->info('Running migrations...');
    $this->call('migrate', ['--force' => true]);

    $this->info('Linking storage...');
    $this->call('storage:link');

    $this->info('Clearing caches...');
    $this->call('config:clear');
    $this->call('cache:clear');
    $this->call('view:clear');

    $writable = is_writable(storage_path('app/public'));
    $this->line('storage/app/public writable: ' . ($writable ? 'YES' : 'NO — fix permissions'));
    $this->line('PHP GD: ' . (extension_loaded('gd') ? 'YES' : 'NO'));

    $this->info('Done. Set APP_URL in .env to your live domain.');
})->purpose('Prepare Porville for production (migrate, storage link, clear cache)');

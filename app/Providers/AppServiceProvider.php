<?php

namespace App\Providers;

use App\Support\MediaUrl;
use App\Support\StorageLink;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useTailwind();
        try {
            StorageLink::ensure();
        } catch (\Throwable) {
            // Avoid boot failure when hosting blocks symlink creation.
        }
        View::share('brandLogoUrl', MediaUrl::brandLogo());
    }
}

<?php

namespace App\Providers;

use App\Support\MediaUrl;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Paginator::useTailwind();
        $this->configureApplicationUrl();
        View::share('brandLogoUrl', MediaUrl::brandLogo());
    }

    private function configureApplicationUrl(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $request = request();
        $configured = rtrim((string) config('app.url'), '/');

        if ($request) {
            $detected = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/');

            if (
                $configured === ''
                || str_contains($configured, 'localhost')
                || ! str_contains($configured, (string) parse_url($detected, PHP_URL_HOST))
            ) {
                $configured = $detected;
            }
        }

        if ($subdirectory = trim((string) env('APP_SUBDIRECTORY', ''), '/')) {
            $host = $request?->getSchemeAndHttpHost() ?? rtrim((string) parse_url($configured, PHP_URL_SCHEME) . '://' . parse_url($configured, PHP_URL_HOST), '/');
            $configured = rtrim($host, '/') . '/' . $subdirectory;
        }

        if ($configured !== '') {
            URL::forceRootUrl($configured);

            if (str_starts_with($configured, 'https://')) {
                URL::forceScheme('https');
            }
        }
    }
}

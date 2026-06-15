<?php

namespace App\Providers;

use App\Models\Category;
use App\Support\MediaUrl;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        date_default_timezone_set((string) config('app.timezone', 'Asia/Kolkata'));
        Paginator::useTailwind();
        $this->configureApplicationUrl();
        View::share('brandLogoUrl', MediaUrl::brandLogo());
        $this->shareFrontendNavigationCategories();
    }

    private function shareFrontendNavigationCategories(): void
    {
        View::composer('frontend.layouts.app', function ($view) {
            try {
                $categories = Category::parents()
                    ->where('is_active', true)
                    ->with(['children' => fn ($query) => $query->where('is_active', true)->orderBy('name')])
                    ->orderBy('name')
                    ->get();
            } catch (Throwable) {
                $categories = collect();
            }

            $view->with('frontendNavCategories', $categories);
        });
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

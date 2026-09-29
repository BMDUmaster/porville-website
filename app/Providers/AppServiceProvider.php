<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\SeoPage;
use App\Support\MediaUrl;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
        $this->runPendingMigrations();
        View::share('brandLogoUrl', MediaUrl::brandLogo());
        $this->shareFrontendNavigationCategories();
        $this->shareFrontendSeo();
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
                $tickerProduct = Product::active()->latest('created_at')->latest('id')->first();
                $tickerOffer = Coupon::offers()->activeEntries()->latest('id')->first();
            } catch (Throwable) {
                $categories = collect();
                $tickerProduct = null;
                $tickerOffer = null;
            }

            $view->with([
                'frontendNavCategories' => $categories,
                'frontendTickerProduct' => $tickerProduct,
                'frontendTickerOffer' => $tickerOffer,
            ]);
        });
    }

    private function shareFrontendSeo(): void
    {
        View::composer('frontend.layouts.app', function ($view) {
            try {
                $seoPage = SeoPage::forPath(request()->path());
            } catch (Throwable) {
                $seoPage = null; // table not migrated yet
            }

            $view->with('seoPage', $seoPage);
        });
    }

    /**
     * Shared hosting git deploys cannot run `php artisan migrate`, so run any
     * pending migrations once per new migration file on the first web request.
     */
    private function runPendingMigrations(): void
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        try {
            $files = glob(database_path('migrations/*.php')) ?: [];
            $latest = $files ? basename(max($files), '.php') : 'none';
            $doneKey = 'migrations:ran:' . $latest;

            if (Cache::has($doneKey)) {
                return;
            }

            Cache::lock('migrations:running', 120)->get(function () use ($doneKey) {
                if (Cache::has($doneKey)) {
                    return;
                }

                Artisan::call('migrate', ['--force' => true]);
                Cache::forever($doneKey, true);
                Log::info('Auto-migrate ran', ['output' => trim(Artisan::output())]);
            });
        } catch (Throwable $e) {
            Log::error('Auto-migrate failed', ['error' => $e->getMessage()]);
        }
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

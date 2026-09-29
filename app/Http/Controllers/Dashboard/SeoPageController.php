<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SeoPage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SeoPageController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));
        $status = $request->query('status');

        $pages = SeoPage::query()
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('page_name', 'like', "%{$search}%")
                    ->orWhere('route_path', 'like', "%{$search}%")
                    ->orWhere('meta_title', 'like', "%{$search}%");
            }))
            ->when($status === 'active', fn ($query) => $query->where('is_active', true))
            ->when($status === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderBy('route_path')
            ->paginate(20)
            ->withQueryString();

        $all = SeoPage::query()->get();
        $scores = $all->map(fn (SeoPage $page) => $page->seo_score);

        $stats = [
            'total' => $all->count(),
            'active' => $all->where('is_active', true)->count(),
            'average' => $scores->isEmpty() ? 0 : (int) round($scores->avg()),
            'attention' => $scores->filter(fn ($score) => $score < 50)->count(),
        ];

        $missingPages = collect($this->availablePages())->keys()->diff($all->pluck('route_path'))->count();

        return view('dashboard.seo.index', compact('pages', 'stats', 'search', 'status', 'missingPages'));
    }

    public function create()
    {
        return view('dashboard.seo.form', [
            'seoPage' => new SeoPage([
                'is_active' => true,
                'og_type' => 'website',
                'twitter_card' => 'summary_large_image',
                'robots_index' => true,
                'robots_follow' => true,
                'sitemap_include' => true,
                'sitemap_priority' => 0.5,
                'sitemap_changefreq' => 'weekly',
            ]),
            'availablePages' => $this->availablePages(),
        ]);
    }

    public function store(Request $request)
    {
        $seoPage = new SeoPage();
        $seoPage->fill($this->validatedData($request, $seoPage));
        $seoPage->save();

        return redirect()->route('dashboard.seo')->with('success', "SEO data saved for {$seoPage->page_name}.");
    }

    public function edit(SeoPage $seoPage)
    {
        return view('dashboard.seo.form', [
            'seoPage' => $seoPage,
            'availablePages' => $this->availablePages(),
        ]);
    }

    public function update(Request $request, SeoPage $seoPage)
    {
        $seoPage->fill($this->validatedData($request, $seoPage));
        $seoPage->save();

        return redirect()->route('dashboard.seo.edit', $seoPage)->with('success', 'SEO data updated.');
    }

    public function toggle(SeoPage $seoPage)
    {
        $seoPage->update(['is_active' => ! $seoPage->is_active]);

        return back()->with('success', $seoPage->page_name . ($seoPage->is_active ? ' SEO activated.' : ' SEO deactivated.'));
    }

    public function destroy(SeoPage $seoPage)
    {
        $this->deleteStoredImage($seoPage->og_image);
        $this->deleteStoredImage($seoPage->twitter_image);
        $seoPage->delete();

        return redirect()->route('dashboard.seo')->with('success', 'SEO page removed.');
    }

    /**
     * Create blank SEO rows for every public page that doesn't have one yet.
     */
    public function importDefaults()
    {
        $existing = SeoPage::query()->pluck('route_path')->all();
        $created = 0;

        foreach ($this->availablePages() as $path => $name) {
            if (in_array($path, $existing, true)) {
                continue;
            }

            SeoPage::create([
                'page_name' => $name,
                'route_path' => $path,
                'meta_title' => $name === 'Home' ? 'Porville — Fresh Cut Pure Standards' : "{$name} — Porville",
                'sitemap_priority' => $path === '/' ? 1.0 : 0.6,
                'sitemap_changefreq' => $path === '/' ? 'daily' : 'weekly',
            ]);
            $created++;
        }

        return back()->with('success', $created
            ? "{$created} " . Str::plural('page', $created) . ' imported. Fill in their SEO details.'
            : 'All public pages already have SEO entries.');
    }

    private function validatedData(Request $request, SeoPage $seoPage): array
    {
        $request->merge([
            'route_path' => SeoPage::normalizePath($request->input('route_path')),
        ]);

        $data = $request->validate([
            'page_name' => ['required', 'string', 'max:150'],
            'route_path' => ['required', 'string', 'max:255', 'regex:/^\/[A-Za-z0-9\-._~\/]*$/', Rule::unique('seo_pages', 'route_path')->ignore($seoPage->id)],
            'is_active' => ['required', 'boolean'],

            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'meta_keywords' => ['nullable', 'string', 'max:1000'],
            'canonical_url' => ['nullable', 'url', 'max:500'],

            'og_title' => ['nullable', 'string', 'max:255'],
            'og_description' => ['nullable', 'string', 'max:1000'],
            'og_type' => ['required', Rule::in(SeoPage::OG_TYPES)],
            'og_image_url' => ['nullable', 'url', 'max:500'],
            'og_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_og_image' => ['nullable', 'boolean'],

            'twitter_card' => ['required', Rule::in(SeoPage::TWITTER_CARDS)],
            'twitter_title' => ['nullable', 'string', 'max:255'],
            'twitter_description' => ['nullable', 'string', 'max:1000'],
            'twitter_site' => ['nullable', 'string', 'max:100', 'regex:/^@?[A-Za-z0-9_]{1,15}$/'],
            'twitter_image_url' => ['nullable', 'url', 'max:500'],
            'twitter_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'remove_twitter_image' => ['nullable', 'boolean'],

            'schema_json' => ['nullable', 'string', function ($attribute, $value, $fail) {
                $decoded = json_decode($value, true);
                if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
                    $fail('Schema (JSON-LD) must be a valid JSON object or array: ' . json_last_error_msg() . '.');
                }
            }],

            'robots_index' => ['required', 'boolean'],
            'robots_follow' => ['required', 'boolean'],
            'sitemap_include' => ['required', 'boolean'],
            'sitemap_priority' => ['required', 'numeric', 'between:0,1'],
            'sitemap_changefreq' => ['required', Rule::in(SeoPage::CHANGEFREQS)],
        ], [
            'route_path.regex' => 'Route path must start with "/" and contain only letters, numbers, "-", "_", "." or "/".',
            'route_path.unique' => 'SEO data already exists for this route path.',
            'twitter_site.regex' => 'Twitter handle should look like @porville.',
        ]);

        $data['og_image'] = $this->resolveImage(
            $seoPage->og_image,
            $request->file('og_image_file'),
            $data['og_image_url'] ?? null,
            $request->boolean('remove_og_image'),
        );
        $data['twitter_image'] = $this->resolveImage(
            $seoPage->twitter_image,
            $request->file('twitter_image_file'),
            $data['twitter_image_url'] ?? null,
            $request->boolean('remove_twitter_image'),
        );

        if (filled($data['schema_json'] ?? null)) {
            $data['schema_json'] = json_encode(json_decode($data['schema_json'], true), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }

        if (filled($data['twitter_site'] ?? null)) {
            $data['twitter_site'] = '@' . ltrim($data['twitter_site'], '@');
        }

        $data['sitemap_priority'] = round((float) $data['sitemap_priority'], 1);

        unset(
            $data['og_image_url'], $data['og_image_file'], $data['remove_og_image'],
            $data['twitter_image_url'], $data['twitter_image_file'], $data['remove_twitter_image'],
        );

        return $data;
    }

    /**
     * An uploaded file wins over a pasted URL; a removed/replaced upload is deleted from disk.
     */
    private function resolveImage(?string $current, ?UploadedFile $file, ?string $url, bool $remove): ?string
    {
        if ($file) {
            $this->deleteStoredImage($current);

            return $file->store('seo', 'public');
        }

        if ($remove) {
            $this->deleteStoredImage($current);

            return null;
        }

        if (filled($url)) {
            if ($url === SeoPage::imageUrl($current)) {
                return $current;
            }

            $this->deleteStoredImage($current);

            return $url;
        }

        // The URL box only ever shows external links, so clearing it drops an external image.
        return $this->isExternal($current) ? null : $current;
    }

    private function deleteStoredImage(?string $path): void
    {
        if ($path && ! $this->isExternal($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function isExternal(?string $path): bool
    {
        return $path !== null && Str::startsWith($path, ['http://', 'https://', '//']);
    }

    /**
     * Public, parameter-free GET pages on the storefront, keyed by path.
     *
     * @return array<string, string>
     */
    private function availablePages(): array
    {
        $pages = [];

        foreach (Route::getRoutes() as $route) {
            $name = (string) $route->getName();
            $middleware = $route->gatherMiddleware();

            if (
                ! Str::startsWith($name, 'frontend.')
                || ! in_array('GET', $route->methods(), true)
                || str_contains($route->uri(), '{')
                || Str::endsWith($name, ['.count', '.live'])
                || in_array('auth:web_frontend', $middleware, true)
                || in_array('guest:web_frontend', $middleware, true)
                || in_array($name, ['frontend.cart', 'frontend.wishlist'], true)
            ) {
                continue;
            }

            $path = SeoPage::normalizePath($route->uri());
            $label = match ($path) {
                '/' => 'Home',
                '/shop' => 'Shop',
                '/shop/categories' => 'All Categories',
                '/faq' => 'FAQ',
                default => Str::headline(Str::afterLast($path, '/')),
            };
            $pages[$path] = $label;
        }

        ksort($pages);

        return $pages;
    }
}

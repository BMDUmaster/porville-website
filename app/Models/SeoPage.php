<?php

namespace App\Models;

use App\Support\MediaUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SeoPage extends Model
{
    public const TWITTER_CARDS = ['summary', 'summary_large_image'];
    public const OG_TYPES = ['website', 'article', 'product', 'profile'];
    public const CHANGEFREQS = ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'];

    protected $fillable = [
        'page_name',
        'route_path',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_title',
        'og_description',
        'og_image',
        'og_type',
        'twitter_card',
        'twitter_title',
        'twitter_description',
        'twitter_image',
        'twitter_site',
        'schema_json',
        'robots_index',
        'robots_follow',
        'sitemap_include',
        'sitemap_priority',
        'sitemap_changefreq',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'robots_index' => 'boolean',
        'robots_follow' => 'boolean',
        'sitemap_include' => 'boolean',
        'sitemap_priority' => 'float',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Normalise a path so "/about-us/", "about-us" and "/about-us?x=1" all match "/about-us".
     */
    public static function normalizePath(?string $path): string
    {
        $path = trim((string) $path);
        $path = (string) (parse_url($path, PHP_URL_PATH) ?? $path);
        $path = '/' . trim($path, '/');

        return $path === '/' ? '/' : rtrim($path, '/');
    }

    public static function forPath(string $path): ?self
    {
        return static::query()
            ->active()
            ->where('route_path', static::normalizePath($path))
            ->first();
    }

    public static function imageUrl(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        return Str::startsWith($value, ['http://', 'https://', '//']) ? $value : MediaUrl::storage($value);
    }

    public function getOgImageUrlAttribute(): ?string
    {
        return static::imageUrl($this->og_image);
    }

    public function getTwitterImageUrlAttribute(): ?string
    {
        return static::imageUrl($this->twitter_image);
    }

    public function getPublicUrlAttribute(): string
    {
        return url($this->route_path);
    }

    public function getResolvedCanonicalAttribute(): string
    {
        return $this->canonical_url ?: $this->public_url;
    }

    public function getRobotsContentAttribute(): string
    {
        return ($this->robots_index ? 'index' : 'noindex') . ', ' . ($this->robots_follow ? 'follow' : 'nofollow');
    }

    /**
     * Decoded JSON-LD blocks (always a list), or an empty array when blank/invalid.
     */
    public function schemaBlocks(): array
    {
        if (blank($this->schema_json)) {
            return [];
        }

        $decoded = json_decode($this->schema_json, true);

        if (! is_array($decoded)) {
            return [];
        }

        return array_is_list($decoded) ? $decoded : [$decoded];
    }

    /**
     * The same checklist the admin form shows live, so list scores match the editor.
     *
     * @return array<string, bool>
     */
    public function seoChecklist(): array
    {
        $titleLength = mb_strlen((string) $this->meta_title);
        $descriptionLength = mb_strlen((string) $this->meta_description);
        $schemaValid = blank($this->schema_json) || json_decode($this->schema_json) !== null;

        return [
            'Title length optimal' => $titleLength >= 50 && $titleLength <= 60,
            'Description length optimal' => $descriptionLength >= 120 && $descriptionLength <= 160,
            'Keywords added' => filled(trim((string) $this->meta_keywords, " ,")),
            'Canonical URL set' => blank($this->canonical_url) || filter_var($this->canonical_url, FILTER_VALIDATE_URL) !== false,
            'Open Graph configured' => filled($this->og_title) && filled($this->og_description) && filled($this->og_image),
            'Valid Schema JSON' => $schemaValid,
            'Page is indexable' => (bool) $this->robots_index,
        ];
    }

    public function getSeoScoreAttribute(): int
    {
        $checks = $this->seoChecklist();

        return (int) round(count(array_filter($checks)) / count($checks) * 100);
    }

    public static function gradeFor(int $score): array
    {
        return match (true) {
            $score >= 80 => ['label' => 'Good', 'class' => 'bg-emerald-100 text-emerald-700', 'bar' => 'bg-emerald-500'],
            $score >= 50 => ['label' => 'Average', 'class' => 'bg-amber-100 text-amber-700', 'bar' => 'bg-amber-500'],
            default => ['label' => 'Poor', 'class' => 'bg-red-100 text-red-700', 'bar' => 'bg-red-500'],
        };
    }
}

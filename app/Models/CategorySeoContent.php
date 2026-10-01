<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorySeoContent extends Model
{
    /** Tags admins may use in the content; everything else is stripped. */
    public const ALLOWED_TAGS = '<h2><h3><h4><p><br><strong><b><em><i><u><a><ul><ol><li><blockquote><hr><span><div><table><thead><tbody><tr><th><td><img>';

    protected $fillable = ['category_id', 'heading', 'content', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Keep simple formatting tags only and drop scripts, inline event
     * handlers and javascript: links.
     */
    public static function sanitize(?string $html): ?string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return null;
        }

        $html = preg_replace('~<(script|style|iframe|object|embed)\b[^>]*>.*?</\1>~is', '', $html);
        $html = strip_tags($html, self::ALLOWED_TAGS);
        $html = preg_replace('~\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)~i', '', $html);
        $html = preg_replace('~(href|src)\s*=\s*(["\']?)\s*(javascript|vbscript|data):[^"\'>\s]*\2~i', '$1="#"', $html);

        return trim($html) === '' ? null : trim($html);
    }

    /**
     * Active content for a sub category (if any) falling back to its category.
     */
    public static function forPage(?Category $category, ?Category $subcategory = null): ?self
    {
        $ids = array_filter([$subcategory?->id, $category?->id]);

        if (! $ids) {
            return null;
        }

        try {
            return static::query()
                ->where('is_active', true)
                ->whereNotNull('content')
                ->whereIn('category_id', $ids)
                ->get()
                ->sortBy(fn (self $row) => array_search($row->category_id, array_values($ids), true))
                ->first();
        } catch (\Throwable) {
            return null; // table not migrated yet
        }
    }
}

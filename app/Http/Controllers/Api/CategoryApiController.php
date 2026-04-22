<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryApiController extends Controller
{
    /**
     * GET /api/categories
     * Returns all parent categories with their subcategories
     */
    public function index()
    {
        $categories = Category::parents()
            ->where('is_active', true)
            ->with(['children' => fn($q) => $q->where('is_active', true)])
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $categories->map(fn($cat) => $this->formatCategory($cat, true)),
        ]);
    }

    /**
     * GET /api/categories/{id}
     */
    public function show($id)
    {
        $category = Category::where('is_active', true)
            ->with(['children' => fn($q) => $q->where('is_active', true)])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $this->formatCategory($category, true),
        ]);
    }

    /**
     * GET /api/subcategories
     * Returns all subcategories with parent info
     */
    public function subcategories()
    {
        $subs = Category::subcategories()
            ->where('is_active', true)
            ->with('parent:id,name,slug')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $subs->map(fn($s) => $this->formatCategory($s, false)),
        ]);
    }

    private function formatCategory(Category $cat, bool $withChildren = false): array
    {
        $data = [
            'id'          => $cat->id,
            'name'        => $cat->name,
            'slug'        => $cat->slug,
            'description' => $cat->description,
            'image'       => $cat->image ? url('storage/' . $cat->image) : null,
            'parent_id'   => $cat->parent_id,
        ];

        if ($withChildren && $cat->relationLoaded('children')) {
            $data['subcategories'] = $cat->children->map(
                fn($c) => $this->formatCategory($c, false)
            )->values()->all();
        }

        return $data;
    }
}

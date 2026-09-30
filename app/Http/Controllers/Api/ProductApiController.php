<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    /**
     * GET /api/products
     * Query params: category_id, subcategory_id, search, per_page
     */
    public function index(Request $request)
    {
        $query = Product::with(['category:id,name,slug', 'subcategory:id,name,slug'])
            ->active();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('subcategory_id')) {
            $query->where('subcategory_id', $request->subcategory_id);
        }

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $perPage  = min((int) $request->get('per_page', 20), 100);
        $products = $query->latest()->paginate($perPage);

        return response()->json([
            'success' => true,
            'data'    => $products->map(fn($p) => $this->formatProduct($p)),
            'meta'    => [
                'current_page' => $products->currentPage(),
                'last_page'    => $products->lastPage(),
                'per_page'     => $products->perPage(),
                'total'        => $products->total(),
            ],
        ]);
    }

    /**
     * GET /api/products/{id}
     */
    public function show($id)
    {
        $product = Product::with(['category:id,name,slug', 'subcategory:id,name,slug'])
            ->active()
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $this->formatProduct($product),
        ]);
    }

    /**
     * GET /api/products/slug/{slug}
     */
    public function showBySlug($slug)
    {
        $product = Product::with(['category:id,name,slug', 'subcategory:id,name,slug'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => $this->formatProduct($product),
        ]);
    }

    private function formatProduct(Product $p): array
    {
        $images = collect($p->images ?? [])->map(
            fn($img) => url('storage/' . $img)
        )->values()->all();

        return [
            'id'          => $p->id,
            'name'        => $p->name,
            'slug'        => $p->slug,
            'description' => $p->description,
            'price'       => (float) $p->price,
            'mrp'         => (float) ($p->mrp ?? $p->price),
            'unit'        => $p->unit,
            'stock'       => $p->stock,
            'images'      => $images,
            'image'       => $images[0] ?? null,
            'variants'    => $p->variants ?? [],
            'category'    => $p->category ? [
                'id'   => $p->category->id,
                'name' => $p->category->name,
                'slug' => $p->category->slug,
            ] : null,
            'subcategory' => $p->subcategory ? [
                'id'   => $p->subcategory->id,
                'name' => $p->subcategory->name,
                'slug' => $p->subcategory->slug,
            ] : null,
        ];
    }
}

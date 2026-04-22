<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'subcategory'])->active();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $cat = Category::where('slug', $request->category)
                ->orWhere('name', $request->category)
                ->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }

        if ($request->filled('subcategory')) {
            $sub = Category::where('slug', $request->subcategory)->first();
            if ($sub) {
                $query->where('subcategory_id', $sub->id);
            }
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name'       => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(16)->withQueryString();
        $categories = Category::parents()->where('is_active', true)->get();

        return view('frontend.products', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::with(['category', 'subcategory'])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        $similar = Product::with('category')
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(5)
            ->get();

        return view('frontend.product-detail', compact('product', 'similar'));
    }
}

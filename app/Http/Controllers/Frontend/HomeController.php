<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::parents()
            ->where('is_active', true)
            ->with(['children' => fn($query) => $query
                ->where('is_active', true)
                ->withCount('products')
                ->orderBy('name')
            ])
            ->withCount('children')
            ->withCount('products')
            ->get();

        $latestActiveProducts = Product::with('category')
            ->active()
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        $newArrivals = (clone $latestActiveProducts)
            ->take(8)
            ->get();

        if ($newArrivals->isEmpty()) {
            $newArrivals = Product::with('category')
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->take(8)
                ->get();
        }

        $featuredProducts = Product::with('category')
            ->active()
            ->inRandomOrder()
            ->take(4)
            ->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = $newArrivals->take(4)->values();
        }

        $bestSellers = Product::with('category')
            ->active()
            ->withSum([
                'orderItems as ordered_quantity' => fn($query) => $query->whereHas(
                    'order',
                    fn($orderQuery) => $orderQuery->where('status', '!=', 'cancelled')
                ),
            ], 'quantity')
            ->having('ordered_quantity', '>', 0)
            ->orderByDesc('ordered_quantity')
            ->orderByDesc('id')
            ->take(8)
            ->get();

        return view('frontend.home', compact('categories', 'newArrivals', 'featuredProducts', 'bestSellers'));
    }
}

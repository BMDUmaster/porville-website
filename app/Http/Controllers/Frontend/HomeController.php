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
            ->withCount('products')
            ->get();

        $newArrivals = Product::with('category')
            ->active()
            ->latest()
            ->take(8)
            ->get();

        $featuredProducts = Product::with('category')
            ->active()
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('frontend.home', compact('categories', 'newArrivals', 'featuredProducts'));
    }
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\HomeBanner;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::parents()
            ->where('is_active', true)
            ->with(['children' => fn($query) => $query
                ->where('is_active', true)
                ->withCount(['products' => fn($q) => $q->active()])
                ->orderBy('name')
            ])
            ->withCount('children')
            ->withCount(['products' => fn($q) => $q->active()])
            ->get();

        $categoryProducts = Product::active()->notEnquiryOnly()->with('category')
            ->whereIn('category_id', $categories->pluck('id'))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->groupBy('category_id')
            ->map(fn($products) => $products->take(10));

        $bestSellers = Product::active()->notEnquiryOnly()->with('category')
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

        $homeBanners = Schema::hasTable('home_banners')
            ? HomeBanner::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->latest('id')
                ->get()
            : collect();

        $homeReviews = Schema::hasTable('reviews')
            ? Review::with(['user', 'product'])
                ->where('status', 'approved')
                ->where('display_on', 'home')
                ->latest('reviewed_at')
                ->take(20)
                ->get()
            : collect();

        $liveStockCategory = Category::where('is_enquiry_only', true)->where('is_active', true)->first();

        $liveStockProducts = $liveStockCategory
            ? Product::active()->with('category')
                ->where('category_id', $liveStockCategory->id)
                ->latest('id')
                ->take(6)
                ->get()
            : collect();

        return view('frontend.home', compact('categories', 'categoryProducts', 'bestSellers', 'homeBanners', 'homeReviews', 'liveStockCategory', 'liveStockProducts'));
    }
}

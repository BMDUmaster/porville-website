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
        $newArrivalProductIds = Product::active()->notEnquiryOnly()
            ->latest('created_at')
            ->latest('id')
            ->take(5)
            ->pluck('id')
            ->all();

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

        $latestProducts = Product::active()->notEnquiryOnly()->with('category')
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        $newArrivals = (clone $latestProducts)
            ->take(8)
            ->get();

        if ($newArrivals->isEmpty()) {
            $newArrivals = Product::active()->notEnquiryOnly()->with('category')
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->take(8)
                ->get();
        }

        $featuredProducts = Product::active()->notEnquiryOnly()->with('category')
            ->inRandomOrder()
            ->take(4)
            ->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = $newArrivals->take(4)->values();
        }

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

        return view('frontend.home', compact('categories', 'newArrivals', 'featuredProducts', 'bestSellers', 'newArrivalProductIds', 'homeBanners', 'homeReviews', 'liveStockCategory', 'liveStockProducts'));
    }
}

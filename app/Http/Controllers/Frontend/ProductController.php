<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\HomeBanner;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $newArrivalProductIds = Product::active()
            ->latest('created_at')
            ->latest('id')
            ->take(5)
            ->pluck('id')
            ->all();

        $query = Product::active()->with(['category', 'subcategory']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $activeCategory = null;

        if ($request->filled('category')) {
            $activeCategory = Category::parents()
                ->where(function ($q) use ($request) {
                    $q->where('slug', $request->category)
                      ->orWhere('name', $request->category);
                })
                ->first();
            if ($activeCategory) {
                $query->where('products.category_id', $activeCategory->id);
            }
        }

        if ($request->filled('subcategory')) {
            $sub = Category::subcategories()
                ->where('slug', $request->subcategory)
                ->first();
            if ($sub) {
                $query->where('products.subcategory_id', $sub->id);
            }
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->boolean('flash_deal') || $request->filled('flash_deal') || $request->get('offer') === 'flash_deal') {
            $query->whereNotNull('products.mrp')
                ->whereColumn('products.mrp', '>', 'products.price');
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name'       => $query->orderBy('name', 'asc'),
            default      => $query->latest(),
        };

        $products = $query->paginate(16)->withQueryString();

        $categories = Category::parents()
            ->where('is_active', true)
            ->withCount([
                'products as active_products_count' => fn($productQuery) => $productQuery->active(),
            ])
            ->get();

        $sidebarMaxPrice = (int) ceil((Product::max('price') ?? 500) / 50) * 50;
        $sidebarMaxPrice = max($sidebarMaxPrice, 500);

        $sharedHeroBanner = Schema::hasTable('home_banners')
            ? HomeBanner::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->latest('id')
                ->first()
            : null;

        return view('frontend.products', compact(
            'products',
            'categories',
            'sidebarMaxPrice',
            'newArrivalProductIds',
            'sharedHeroBanner',
            'activeCategory'
        ));
    }

    public function show($slug)
    {
        $newArrivalProductIds = Product::active()
            ->latest('created_at')
            ->latest('id')
            ->take(5)
            ->pluck('id')
            ->all();

        $product = Product::active()->with(['category', 'subcategory'])
            ->where('slug', $slug)
            ->firstOrFail();

        $similar = Product::with(['category', 'subcategory'])
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $productReviews = Schema::hasTable('reviews')
            ? Review::with('user')
                ->where('product_id', $product->id)
                ->where('status', 'approved')
                ->where('display_on', 'product')
                ->latest('reviewed_at')
                ->get()
            : collect();

        $palette = [
            ['icon' => 'fa-tag', 'icon_bg' => 'bg-[#e9f7ec]', 'icon_color' => 'text-[#2f8c43]', 'code_bg' => 'bg-[#edf8ef]', 'code_text' => 'text-[#2f8c43]'],
            ['icon' => 'fa-truck-fast', 'icon_bg' => 'bg-[#ebf4ff]', 'icon_color' => 'text-[#2d72d3]', 'code_bg' => 'bg-[#ebf4ff]', 'code_text' => 'text-[#2d72d3]'],
            ['icon' => 'fa-credit-card', 'icon_bg' => 'bg-[#fff3e6]', 'icon_color' => 'text-[#d97706]', 'code_bg' => 'bg-[#fff6ea]', 'code_text' => 'text-[#d97706]'],
            ['icon' => 'fa-gift', 'icon_bg' => 'bg-[#f3ebff]', 'icon_color' => 'text-[#9333ea]', 'code_bg' => 'bg-[#f3ebff]', 'code_text' => 'text-[#9333ea]'],
        ];

        $frontendOfferCards = Coupon::offers()
            ->activeEntries()
            ->where(function ($query) use ($product) {
                $query->whereNull('product_id')
                    ->orWhere('product_id', $product->id);
            })
            ->latest()
            ->take(6)
            ->get()
            ->values()
            ->map(function ($offer, $index) use ($palette) {
                $style = $palette[$index % count($palette)];
                $code = Str::startsWith($offer->code, 'AUTO-OFFER-') ? null : $offer->code;

                return array_merge($style, [
                    'badge' => $index === 0 ? 'Top Offer' : 'Live Offer',
                    'title' => $offer->title ?: 'Special Offer',
                    'text' => $offer->description ?: 'Exclusive savings available for a limited time.',
                    'discount' => $offer->type === 'percent'
                        ? rtrim(rtrim(number_format((float) $offer->value, 2, '.', ''), '0'), '.') . '% OFF'
                        : 'Rs' . number_format((float) $offer->value, 0) . ' OFF',
                    'scope' => $offer->product_id ? 'For this product' : 'All products',
                    'code' => $code,
                    'min_order_amount' => $offer->min_order_amount,
                    'expires_at' => optional($offer->expires_at)->format('d M Y, h:i A'),
                ]);
            })
            ->all();

        return view('frontend.product-detail', compact('product', 'similar', 'frontendOfferCards', 'newArrivalProductIds', 'productReviews'));
    }
}

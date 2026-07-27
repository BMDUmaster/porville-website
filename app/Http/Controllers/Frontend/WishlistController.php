<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Display wishlist page with saved products.
     */
    public function index()
    {
        $wishlistIds = session('wishlist', []);
        
        $products = Product::active()
            ->with(['category', 'subcategory'])
            ->whereIn('id', $wishlistIds)
            ->get();

        return view('frontend.wishlist', compact('products'));
    }

    /**
     * Toggle product in wishlist (Add/Remove via AJAX).
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $productId = (int) $request->product_id;
        $wishlist = session('wishlist', []);

        if (in_array($productId, $wishlist, true)) {
            $wishlist = array_values(array_diff($wishlist, [$productId]));
            $action = 'removed';
            $message = 'Product removed from your wishlist';
        } else {
            $wishlist[] = $productId;
            $wishlist = array_values(array_unique($wishlist));
            $action = 'added';
            $message = 'Product added to your wishlist!';
        }

        session(['wishlist' => $wishlist]);

        return response()->json([
            'success'  => true,
            'action'   => $action,
            'count'    => count($wishlist),
            'message'  => $message,
            'wishlist' => $wishlist,
        ]);
    }

    /**
     * Get current wishlist item count.
     */
    public function count()
    {
        $wishlist = session('wishlist', []);

        return response()->json([
            'count'    => count($wishlist),
            'wishlist' => $wishlist,
        ]);
    }
}

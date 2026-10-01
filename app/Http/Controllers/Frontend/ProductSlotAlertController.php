<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\ProductSlotManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductSlotAlertController extends Controller
{
    /**
     * GET (after login redirect) or POST (AJAX) — remember that the customer
     * wants an alert when this product's next ordering slot opens.
     */
    public function subscribe(Request $request, Product $product)
    {
        $alreadyOpen = ProductSlotManager::isOrderable($product);

        if (! $alreadyOpen) {
            ProductSlotManager::subscribe(Auth::guard('web_frontend')->id(), $product->id);
        }

        $message = $alreadyOpen
            ? "{$product->name} is available to order right now."
            : "Done! We'll notify you when {$product->name} is available to order.";

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'available' => $alreadyOpen,
                'message' => $message,
            ]);
        }

        return redirect()->route('frontend.product.show', $product->slug)->with('success', $message);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponApiController extends Controller
{
    /**
     * POST /api/coupons/validate
     * Body: {
     *   "code": "SAVE10",
     *   "order_amount": 500,
     *   "cart_items": [{"product_id": 3, "price": 320, "quantity": 1}, ...]
     * }
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code'                    => 'required|string',
            'order_amount'            => 'required|numeric|min:0',
            'cart_items'              => 'nullable|array',
            'cart_items.*.product_id' => 'nullable|integer',
            'cart_items.*.price'      => 'nullable|numeric|min:0',
            'cart_items.*.quantity'   => 'nullable|integer|min:1',
        ]);

        $coupon = Coupon::valid()
            ->where('code', strtoupper($request->code))
            ->first();

        if (! $coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon code.',
            ], 422);
        }

        if ($request->order_amount < ($coupon->min_order_amount ?? 0)) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount of ₹' . $coupon->min_order_amount . ' required.',
            ], 422);
        }

        $cartItems = collect($request->input('cart_items', []));

        // If product-specific, verify that product is in the cart
        if (! is_null($coupon->product_id)) {
            $cartProductIds = $cartItems->pluck('product_id')->filter();
            if (! $cartProductIds->contains($coupon->product_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This offer is not applicable to the items in your cart.',
                ], 422);
            }

            // Discount base = only that product's subtotal
            $base = (float) $cartItems
                ->where('product_id', $coupon->product_id)
                ->sum(fn ($item) => ($item['price'] ?? 0) * ($item['quantity'] ?? 1));
        } else {
            // Cart-wide coupon — full order amount
            $base = (float) $request->order_amount;
        }

        $discount = $coupon->type === 'percent'
            ? round($base * $coupon->value / 100, 2)
            : min((float) $coupon->value, $base);

        return response()->json([
            'success'  => true,
            'message'  => 'Coupon applied successfully.',
            'discount' => $discount,
            'coupon'   => [
                'code'       => $coupon->code,
                'type'       => $coupon->type,
                'value'      => $coupon->value,
                'expires_at' => $coupon->expires_at?->format('d M Y'),
            ],
        ]);
    }
}

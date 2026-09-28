<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponApiController extends Controller
{
    /**
     * POST /api/coupons/validate
     * Body: { "code": "SAVE10", "order_amount": 500, "product_ids": [1,2,3] }
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code'         => 'required|string',
            'order_amount' => 'required|numeric|min:0',
            'product_ids'  => 'nullable|array',
            'product_ids.*'=> 'nullable|integer',
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

        // If coupon is tied to a specific product, check it's in the cart
        if (! is_null($coupon->product_id)) {
            $cartProductIds = collect($request->input('product_ids', []));
            if (! $cartProductIds->contains($coupon->product_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'This coupon is not applicable to the items in your cart.',
                ], 422);
            }
        }

        // Discount base: for product-specific coupons use only that product's portion
        // Since we don't have per-product subtotals here, we use full order_amount for
        // non-product coupons; product-specific discount is calculated server-side at order store.
        $discount = $coupon->type === 'percent'
            ? round($request->order_amount * $coupon->value / 100, 2)
            : min($coupon->value, $request->order_amount);

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

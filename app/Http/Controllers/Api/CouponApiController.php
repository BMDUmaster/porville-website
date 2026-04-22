<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponApiController extends Controller
{
    /**
     * POST /api/coupons/validate
     * Body: { "code": "SAVE10", "order_amount": 500 }
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code'         => 'required|string',
            'order_amount' => 'required|numeric|min:0',
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

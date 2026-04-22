<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /** GET /checkout */
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('frontend.cart')->with('error', 'Your cart is empty.');
        }

        $items    = $this->buildCartItems($cart);
        $subtotal = collect($items)->sum('subtotal');

        return view('frontend.checkout', compact('items', 'subtotal'));
    }

    /** POST /checkout */
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name'       => 'required|string|max:60',
            'last_name'        => 'required|string|max:60',
            'email'            => 'required|email',
            'phone'            => 'required|string|max:20',
            'address'          => 'required|string',
            'city'             => 'required|string',
            'state'            => 'required|string',
            'pincode'          => 'required|string',
            'payment_method'   => 'required|in:COD,online,upi',
            'coupon_code'      => 'nullable|string',
        ]);

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('frontend.cart');
        }

        $items    = $this->buildCartItems($cart);
        $subtotal = collect($items)->sum('subtotal');

        // Coupon
        $discount = 0;
        if (!empty($data['coupon_code'])) {
            $coupon = Coupon::valid()->where('code', strtoupper($data['coupon_code']))->first();
            if ($coupon && $subtotal >= ($coupon->min_order_amount ?? 0)) {
                $discount = $coupon->type === 'percent'
                    ? round($subtotal * $coupon->value / 100, 2)
                    : min($coupon->value, $subtotal);
                $coupon->increment('used_count');
            }
        }

        $deliveryCharge = 25.00;
        $platformFee    = 12.00;
        $total          = $subtotal - $discount + $deliveryCharge + $platformFee;

        $order = Order::create([
            'user_id'          => auth('web_frontend')->id(),
            'order_number'     => 'ORD-' . strtoupper(Str::random(12)),
            'status'           => 'pending',
            'subtotal'         => $subtotal,
            'discount'         => $discount,
            'shipping_cost'    => $deliveryCharge,
            'delivery_charge'  => $deliveryCharge,
            'platform_fee'     => $platformFee,
            'vendor_total'     => 0,
            'admin_commission' => 0,
            'tax'              => 0,
            'total'            => $total,
            'shipping_address' => [
                'name'    => $data['first_name'] . ' ' . $data['last_name'],
                'phone'   => $data['phone'],
                'address' => $data['address'],
                'city'    => $data['city'],
                'state'   => $data['state'],
                'pincode' => $data['pincode'],
            ],
            'payment_method'  => $data['payment_method'],
            'payment_status'  => 'pending',
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item['product_id'],
                'quantity'   => $item['quantity'],
                'unit_price' => $item['price'],
                'mrp'        => $item['mrp'],
                'unit'       => $item['unit'],
                'subtotal'   => $item['subtotal'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('frontend.order.success', $order->id);
    }

    public function success($orderId)
    {
        $order = Order::with('items.product')
            ->where('user_id', auth('web_frontend')->id())
            ->findOrFail($orderId);

        return view('frontend.order-success', compact('order'));
    }

    private function buildCartItems(array $cart): array
    {
        return array_map(function ($item, $key) {
            $item['key']      = $key;
            $item['subtotal'] = $item['price'] * $item['quantity'];
            return $item;
        }, array_values($cart), array_keys($cart));
    }
}

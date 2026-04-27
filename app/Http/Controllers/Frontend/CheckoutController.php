<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    /** GET /checkout */
    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('frontend.cart')->with('error', 'Your cart is empty.');
        }

        try {
            $items = $this->buildCartItems($cart);
        } catch (ValidationException $exception) {
            return redirect()->route('frontend.cart')->withErrors($exception->errors());
        }

        $subtotal = collect($items)->sum('subtotal');
        $checkoutDefaults = $this->checkoutDefaults();

        return view('frontend.checkout', compact('items', 'subtotal', 'checkoutDefaults'));
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

        $items = $this->buildCartItems($cart);
        $subtotal = collect($items)->sum('subtotal');
        $deliveryCharge = 25.00;
        $platformFee = 12.00;

        $order = DB::transaction(function () use ($data, $items, $subtotal, $deliveryCharge, $platformFee) {
            $discount = 0;
            $coupon = $this->resolveCoupon($data['coupon_code'] ?? null, $subtotal, true);

            if ($coupon) {
                $discount = $this->calculateDiscount($coupon, $subtotal);
            }

            $total = $subtotal - $discount + $deliveryCharge + $platformFee;

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
                    'name'    => trim($data['first_name'] . ' ' . $data['last_name']),
                    'phone'   => $data['phone'],
                    'address' => $data['address'],
                    'city'    => $data['city'],
                    'state'   => $data['state'],
                    'pincode' => $data['pincode'],
                ],
                'payment_method'   => $data['payment_method'],
                'payment_status'   => 'pending',
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

            if ($coupon) {
                $coupon->increment('used_count');
            }

            return $order;
        });

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
        $productIds = collect($cart)
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $items = [];

        foreach ($cart as $key => $item) {
            $product = $products->get($item['product_id'] ?? null);

            if (! $product || ! $product->is_active) {
                throw ValidationException::withMessages([
                    'cart' => 'One or more products in your cart are no longer available.',
                ]);
            }

            $variantIndex = $item['variant_index'] ?? null;
            $variant = null;

            if ($variantIndex !== null) {
                $variants = $product->variants ?? [];

                if (! isset($variants[$variantIndex])) {
                    throw ValidationException::withMessages([
                        'cart' => 'One or more selected product variants are no longer available.',
                    ]);
                }

                $variant = $variants[$variantIndex];
            }

            $price = $variant
                ? (float) ($variant['selling_price'] ?? $product->price)
                : (float) $product->price;

            $mrp = $variant
                ? (float) ($variant['mrp'] ?? $product->mrp ?? $price)
                : (float) ($product->mrp ?? $price);

            $quantity = max(1, (int) ($item['quantity'] ?? 1));

            $items[] = [
                'key'           => $key,
                'product_id'    => $product->id,
                'name'          => $product->name,
                'slug'          => $product->slug,
                'image'         => $product->images[0] ?? null,
                'price'         => $price,
                'mrp'           => $mrp,
                'unit'          => $variant['unit'] ?? $product->unit,
                'quantity'      => $quantity,
                'variant_index' => $variantIndex,
                'variant_label' => $variant ? trim(($variant['quantity'] ?? '') . ' ' . ($variant['unit'] ?? '')) : null,
                'subtotal'      => $price * $quantity,
            ];
        }

        return $items;
    }

    private function checkoutDefaults(): array
    {
        $user = auth('web_frontend')->user();

        if (! $user) {
            return [
                'first_name' => '',
                'last_name' => '',
                'email' => '',
                'phone' => '',
                'address' => '',
                'city' => '',
                'state' => '',
                'pincode' => '',
            ];
        }

        $nameParts = preg_split('/\s+/', trim((string) $user->name), 2, PREG_SPLIT_NO_EMPTY) ?: [];
        $latestAddress = $user->orders()
            ->whereNotNull('shipping_address')
            ->latest()
            ->value('shipping_address') ?? [];

        return [
            'first_name' => $nameParts[0] ?? $user->name ?? '',
            'last_name' => $nameParts[1] ?? '',
            'email' => $user->email ?? '',
            'phone' => $latestAddress['phone'] ?? $user->phone ?? '',
            'address' => $latestAddress['address'] ?? '',
            'city' => $latestAddress['city'] ?? '',
            'state' => $latestAddress['state'] ?? '',
            'pincode' => $latestAddress['pincode'] ?? '',
        ];
    }

    private function resolveCoupon(?string $couponCode, float $subtotal, bool $lock = false): ?Coupon
    {
        $couponCode = strtoupper(trim((string) $couponCode));

        if ($couponCode === '') {
            return null;
        }

        $query = Coupon::valid()->where('code', $couponCode);

        if ($lock) {
            $query->lockForUpdate();
        }

        $coupon = $query->first();

        if (! $coupon) {
            throw ValidationException::withMessages([
                'coupon_code' => 'The selected coupon is invalid, expired, or already fully used.',
            ]);
        }

        if ($subtotal < (float) ($coupon->min_order_amount ?? 0)) {
            throw ValidationException::withMessages([
                'coupon_code' => 'This coupon requires a higher order amount.',
            ]);
        }

        return $coupon;
    }

    private function calculateDiscount(Coupon $coupon, float $subtotal): float
    {
        return $coupon->type === 'percent'
            ? round($subtotal * $coupon->value / 100, 2)
            : min((float) $coupon->value, $subtotal);
    }
}

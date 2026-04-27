<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OrderApiController extends Controller
{
    /**
     * GET /api/orders  (auth required)
     * Returns the authenticated user's orders
     */
    public function index(Request $request)
    {
        $orders = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data'    => $orders->map(fn ($o) => $this->formatOrder($o)),
            'meta'    => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'total'        => $orders->total(),
            ],
        ]);
    }

    /**
     * GET /api/orders/{id}  (auth required)
     */
    public function show(Request $request, $id)
    {
        $order = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $this->formatOrder($order),
        ]);
    }

    /**
     * POST /api/orders  (auth required)
     *
     * Expected body:
     * {
     *   "items": [
     *     { "product_id": 1, "quantity": 2, "variant_index": 0 }
     *   ],
     *   "shipping_address": {
     *     "name": "...", "phone": "...", "address": "...", "city": "...", "pincode": "..."
     *   },
     *   "payment_method": "COD",
     *   "coupon_code": "SAVE10"   // optional
     * }
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'items'                    => 'required|array|min:1',
            'items.*.product_id'       => 'required|exists:products,id',
            'items.*.quantity'         => 'required|integer|min:1',
            'items.*.variant_index'    => 'nullable|integer|min:0',
            'shipping_address'         => 'required|array',
            'shipping_address.name'    => 'required|string',
            'shipping_address.phone'   => 'required|string',
            'shipping_address.address' => 'required|string',
            'payment_method'           => 'nullable|string|in:COD,online,wallet',
            'coupon_code'              => 'nullable|string',
        ]);

        $orderItems = $this->buildOrderItems($data['items']);
        $subtotal = collect($orderItems)->sum('subtotal');
        $deliveryCharge = 25.00;
        $platformFee = 12.00;

        $order = DB::transaction(function () use ($request, $data, $orderItems, $subtotal, $deliveryCharge, $platformFee) {
            $discount = 0;
            $coupon = $this->resolveCoupon($data['coupon_code'] ?? null, $subtotal, true);

            if ($coupon) {
                $discount = $this->calculateDiscount($coupon, $subtotal);
            }

            $total = $subtotal - $discount + $deliveryCharge + $platformFee;

            $order = Order::create([
                'user_id'          => $request->user()->id,
                'order_number'     => 'ORD-' . strtoupper(Str::random(12)),
                'status'           => 'pending',
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'shipping_cost'    => $deliveryCharge,
                'delivery_charge'  => $deliveryCharge,
                'platform_fee'     => $platformFee,
                'vendor_total'     => collect($orderItems)->sum('vendor_amount'),
                'admin_commission' => collect($orderItems)->sum('admin_amount'),
                'tax'              => 0,
                'total'            => $total,
                'shipping_address' => $data['shipping_address'],
                'payment_method'   => $data['payment_method'] ?? 'COD',
                'payment_status'   => 'pending',
            ]);

            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            return $order;
        });

        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully.',
            'data'    => $this->formatOrder($order->load('items.product')),
        ], 201);
    }

    private function buildOrderItems(array $items): array
    {
        $products = Product::query()
            ->whereIn('id', collect($items)->pluck('product_id')->unique()->values())
            ->get()
            ->keyBy('id');

        $orderItems = [];

        foreach ($items as $item) {
            $product = $products->get($item['product_id']);

            if (! $product || ! $product->is_active) {
                throw ValidationException::withMessages([
                    'items' => ['One or more products are no longer available.'],
                ]);
            }

            $variantIndex = $item['variant_index'] ?? null;
            $variant = null;

            if ($variantIndex !== null) {
                $variants = $product->variants ?? [];

                if (! isset($variants[$variantIndex])) {
                    throw ValidationException::withMessages([
                        'items' => ['One or more selected product variants are no longer available.'],
                    ]);
                }

                $variant = $variants[$variantIndex];
            }

            $unitPrice = $variant
                ? (float) ($variant['selling_price'] ?? $product->price)
                : (float) $product->price;

            $mrp = $variant
                ? (float) ($variant['mrp'] ?? $product->mrp ?? $unitPrice)
                : (float) ($product->mrp ?? $unitPrice);

            $quantity = max(1, (int) $item['quantity']);

            $orderItems[] = [
                'product_id'    => $product->id,
                'quantity'      => $quantity,
                'unit_price'    => $unitPrice,
                'mrp'           => $mrp,
                'unit'          => $variant['unit'] ?? $product->unit,
                'save_offer'    => $variant['save_offer'] ?? null,
                'vendor_amount' => (float) ($variant['vendor_amount'] ?? 0),
                'admin_amount'  => (float) ($variant['admin_amount'] ?? 0),
                'subtotal'      => $unitPrice * $quantity,
            ];
        }

        return $orderItems;
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
                'coupon_code' => ['The selected coupon is invalid, expired, or already fully used.'],
            ]);
        }

        if ($subtotal < (float) ($coupon->min_order_amount ?? 0)) {
            throw ValidationException::withMessages([
                'coupon_code' => ['This coupon requires a higher order amount.'],
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

    private function formatOrder(Order $order): array
    {
        return [
            'id'               => $order->id,
            'order_number'     => $order->order_number ?? 'ORD-' . $order->id,
            'status'           => $order->status,
            'payment_method'   => $order->payment_method,
            'payment_status'   => $order->payment_status,
            'subtotal'         => (float) $order->subtotal,
            'discount'         => (float) $order->discount,
            'delivery_charge'  => (float) $order->delivery_charge,
            'platform_fee'     => (float) $order->platform_fee,
            'total'            => (float) $order->total,
            'shipping_address' => $order->shipping_address,
            'created_at'       => $order->created_at->format('d M Y, h:i A'),
            'items'            => $order->items->map(fn ($item) => [
                'id'         => $item->id,
                'product_id' => $item->product_id,
                'name'       => $item->product->name ?? 'Deleted Product',
                'image'      => $item->product && $item->product->images
                    ? url('storage/' . ($item->product->images[0] ?? ''))
                    : null,
                'quantity'   => $item->quantity,
                'unit'       => $item->unit,
                'unit_price' => (float) $item->unit_price,
                'mrp'        => (float) ($item->mrp ?? $item->unit_price),
                'save_offer' => $item->save_offer,
                'subtotal'   => (float) $item->subtotal,
            ])->values()->all(),
        ];
    }
}

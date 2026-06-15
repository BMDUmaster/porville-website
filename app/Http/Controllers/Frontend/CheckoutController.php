<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Support\DeliverySlotManager;
use App\Support\OrderPricing;
use App\Support\ProductDayPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    private const COD_MAX_SUBTOTAL = 2000;

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
        $selectedDeliverySlot = old('delivery_slot', session('selected_delivery_slot'));
        $deliverySlotOptions = $this->deliverySlotOptionsForCart($cart);

        if (! in_array($selectedDeliverySlot, array_column($deliverySlotOptions, 'value'), true)) {
            $selectedDeliverySlot = $deliverySlotOptions[0]['value'] ?? null;
            session(['selected_delivery_slot' => $selectedDeliverySlot]);
        }

        $pricing = OrderPricing::summary($subtotal, auth('web_frontend')->user());
        $availableCoupons = Coupon::valid()
            ->orderBy('min_order_amount')
            ->orderByDesc('value')
            ->limit(8)
            ->get();

        return view('frontend.checkout', compact('items', 'subtotal', 'checkoutDefaults', 'selectedDeliverySlot', 'pricing', 'availableCoupons'));
    }

    /** POST /checkout */
    public function store(Request $request)
    {
        $cart = session('cart', []);

        $data = $request->validate([
            'first_name'       => 'required|string|max:60',
            'last_name'        => 'nullable|string|max:60',
            'email'            => 'required|email',
            'phone'            => ['required', 'regex:/^(?:\d{10}|\d{12})$/'],
            'address'          => 'required|string',
            'city'             => 'required|string',
            'state'            => 'required|string',
            'pincode'          => 'required|string',
            'delivery_slot'    => ['required', 'string', Rule::in(array_column($this->deliverySlotOptionsForCart($cart), 'value'))],
            'payment_method'   => 'required|in:COD,online,upi',
            'coupon_code'      => 'nullable|string',
        ], [
            'phone.regex' => 'Phone number must be 10 or 12 digits.',
        ]);

        session(['selected_delivery_slot' => $data['delivery_slot']]);

        if (empty($cart)) {
            return redirect()->route('frontend.cart');
        }

        $checkoutDefaults = $this->checkoutDefaults();
        $data['first_name'] = $checkoutDefaults['first_name'] ?? $data['first_name'];
        $data['last_name'] = $checkoutDefaults['last_name'] ?? '';
        $data['email'] = $checkoutDefaults['email'] ?? $data['email'];

        $items = $this->buildCartItems($cart);
        $subtotal = collect($items)->sum('subtotal');
        $user = auth('web_frontend')->user();
        $pricing = OrderPricing::summary($subtotal, $user);

        if ($data['payment_method'] === 'COD' && $subtotal > self::COD_MAX_SUBTOTAL) {
            throw ValidationException::withMessages([
                'payment_method' => 'Cash on Delivery is available only for orders up to Rs2,000.',
            ]);
        }

        $order = DB::transaction(function () use ($data, $items, $subtotal, $pricing, $user) {
            $discount = 0;
            $coupon = $this->resolveCoupon($data['coupon_code'] ?? null, $subtotal, true);

            if ($coupon) {
                $discount = $this->calculateDiscount($coupon, $subtotal);
            }

            $total = round(($pricing['subtotal'] - $discount) + $pricing['delivery_charge'] + $pricing['service_charge'], 2);

            if ($user && $user->phone !== $data['phone']) {
                $user->update(['phone' => $data['phone']]);
            }

            $order = Order::create([
                'user_id'          => $user?->id,
                'order_number'     => 'ORD-' . strtoupper(Str::random(12)),
                'status'           => 'pending',
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'shipping_cost'    => $pricing['delivery_charge'],
                'delivery_charge'  => $pricing['delivery_charge'],
                'platform_fee'     => $pricing['service_charge'],
                'vendor_total'     => 0,
                'admin_commission' => 0,
                'tax'              => 0,
                'total'            => $total,
                'shipping_address' => [
                    'name'    => trim($data['first_name'] . ' ' . ($data['last_name'] ?? '')),
                    'phone'   => $data['phone'],
                    'address' => $data['address'],
                    'city'    => $data['city'],
                    'state'   => $data['state'],
                    'pincode' => $data['pincode'],
                ],
                'payment_method'   => $data['payment_method'],
                'payment_status'   => 'pending',
                'delivery_slot'    => $data['delivery_slot'],
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['product_id'],
                    'quantity'      => $item['quantity'],
                    'pack_quantity' => $item['pack_quantity'],
                    'unit_price'    => $item['price'],
                    'mrp'           => $item['mrp'],
                    'unit'          => $item['unit'],
                    'variant_label' => $item['variant_label'],
                    'pricing_day'   => $item['pricing_day'],
                    'subtotal'      => $item['subtotal'],
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

            $pricingDay = ProductDayPricing::normalizeDay($item['pricing_day'] ?? 'today');

            if (
                $pricingDay === 'tomorrow'
                && (
                    ! $variant
                    || ! array_key_exists('tomorrow_price', $variant)
                    || $variant['tomorrow_price'] === null
                    || $variant['tomorrow_price'] === ''
                    || (float) $variant['tomorrow_price'] <= 0
                )
            ) {
                $pricingDay = 'today';
            }

            $price = $variant
                ? ProductDayPricing::sellingPrice($variant, $pricingDay, (float) $product->price)
                : (float) $product->price;

            $mrp = $variant
                ? ProductDayPricing::mrp($variant, (float) ($product->mrp ?? $price))
                : (float) ($product->mrp ?? $price);

            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $packQuantity = $variant ? $this->extractPackQuantity($variant['quantity'] ?? null) : null;

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
                'pack_quantity' => $packQuantity,
                'variant_index' => $variantIndex,
                'variant_label' => $variant ? $this->formatVariantLabel($variant, $product->unit) : null,
                'pricing_day'   => $pricingDay,
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

        $phone = $this->cleanCheckoutPhone($latestAddress['phone'] ?? $user->phone ?? '');

        return [
            'first_name' => $nameParts[0] ?? $user->name ?? '',
            'last_name' => $nameParts[1] ?? '',
            'email' => $user->email ?? '',
            'phone' => $phone,
            'address' => $latestAddress['address'] ?? '',
            'city' => $latestAddress['city'] ?? '',
            'state' => $latestAddress['state'] ?? '',
            'pincode' => $latestAddress['pincode'] ?? '',
        ];
    }

    private function cleanCheckoutPhone(mixed $phone): string
    {
        $digits = preg_replace('/\D/', '', (string) $phone) ?? '';

        return in_array(strlen($digits), [10, 12], true) ? $digits : '';
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

    private function extractPackQuantity(mixed $value): ?float
    {
        if ($value === null) {
            return null;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        if (preg_match('/\d+(?:\.\d+)?/', (string) $value, $matches)) {
            return (float) $matches[0];
        }

        return null;
    }

    private function formatVariantLabel(array $variant, ?string $fallbackUnit = null): string
    {
        $quantity = trim((string) ($variant['quantity'] ?? ''));
        $unit = trim((string) ($variant['unit'] ?? $fallbackUnit ?? ''));
        $piece = trim((string) ($variant['piece'] ?? ''));

        if ($quantity !== '') {
            return trim($quantity . ' ' . $unit);
        }

        if ($piece !== '') {
            return preg_match('/[A-Za-z]/', $piece)
                ? $piece
                : trim($piece . ' ' . $unit);
        }

        return $unit;
    }

    private function deliverySlotOptionsForCart(array $cart): array
    {
        return $this->usesTomorrowDelivery($cart)
            ? DeliverySlotManager::options()
            : DeliverySlotManager::availableOptions();
    }

    private function usesTomorrowDelivery(array $cart): bool
    {
        $days = collect($cart)
            ->pluck('pricing_day')
            ->filter()
            ->values();

        return $days->contains('tomorrow') && ! $days->contains('today');
    }
}

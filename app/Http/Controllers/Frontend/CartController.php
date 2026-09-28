<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use App\Support\DeliverySlotManager;
use App\Support\OrderPricing;
use App\Support\ProductDayPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    /** GET /cart */
    public function index(Request $request)
    {
        $cart  = session('cart', []);
        $availableDays = collect($cart)->map(fn ($item) => ProductDayPricing::normalizeDay($item['pricing_day'] ?? 'today'))->unique()->values();
        $selectedDay = ProductDayPricing::normalizeDay($request->query('delivery_day', $availableDays->first() ?? 'today'));
        if (! $availableDays->contains($selectedDay)) {
            $selectedDay = $availableDays->first() ?? 'today';
        }
        $cart = array_filter($cart, fn ($item) => ProductDayPricing::normalizeDay($item['pricing_day'] ?? 'today') === $selectedDay);
        $items = $this->buildCartItems($cart);
        $subtotal = collect($items)->sum('subtotal');
        $pricing = OrderPricing::summary($subtotal, auth('web_frontend')->user(), $selectedDay);
        $couponData = $this->couponData($subtotal, $selectedDay);
        $pricing['discount'] = $couponData['discount'];
        $pricing['total'] = max(0, $pricing['total'] - $couponData['discount']);

        $upcomingDates = DeliverySlotManager::upcomingDatesForCustomer();
        $selectedDeliveryDate = $this->resolveSelectedDate($upcomingDates);
        $deliverySlotOptions = collect($upcomingDates)->firstWhere('date', $selectedDeliveryDate)['options'] ?? [];
        $selectedDeliverySlot = session('selected_delivery_slot');

        if (! in_array($selectedDeliverySlot, array_column($deliverySlotOptions, 'value'), true)) {
            $selectedDeliverySlot = $deliverySlotOptions[0]['value'] ?? null;
            session(['selected_delivery_slot' => $selectedDeliverySlot]);
        }

        return view('frontend.cart', compact('items', 'pricing', 'deliverySlotOptions', 'selectedDeliverySlot', 'upcomingDates', 'selectedDeliveryDate', 'availableDays', 'selectedDay', 'couponData'));
    }

    /** Session-backed selected delivery date, validated against what's actually available. */
    private function resolveSelectedDate(array $upcomingDates): ?string
    {
        $dates = array_column($upcomingDates, 'date');
        $selected = session('selected_delivery_date');

        if ($selected && in_array($selected, $dates, true)) {
            return $selected;
        }

        $default = $dates[0] ?? null;
        session(['selected_delivery_date' => $default]);

        return $default;
    }

    /** POST /cart/add */
    public function add(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'integer|min:1',
            'variant_index' => 'nullable|integer|min:0',
            'pricing_day'   => ['nullable', 'string', Rule::in(['today', 'tomorrow'])],
            'delivery_slot' => ['nullable', 'string', Rule::in(DeliverySlotManager::availableValues())],
        ]);

        $product      = Product::findOrFail($request->product_id);

        if ($product->is_enquiry_only) {
            $message = 'This product is enquiry-only. Please call to order.';

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 422);
            }

            return back()->with('error', $message);
        }

        $qty          = $request->get('quantity', 1);
        $variantIndex = $request->filled('variant_index') ? (int) $request->input('variant_index') : null;
        if ($variantIndex === null && ! empty($product->variants ?? [])) {
            $variantIndex = 0;
        }
        $pricingDay   = ProductDayPricing::normalizeDay($request->get('pricing_day'));
        $variant = ($variantIndex !== null && isset($product->variants[$variantIndex]))
            ? $product->variants[$variantIndex]
            : null;

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

        if ($product->is_out_of_stock) {
            $message = 'This product is currently out of stock.';

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 422);
            }

            return back()->with('error', $message);
        }

        $cart = session('cart', []);
        $key  = $product->id . ($variantIndex !== null ? '_v' . $variantIndex : '') . '_d_' . $pricingDay;

        if (isset($cart[$key])) {
            // Re-add the existing line so it is treated as the latest cart action.
            // The drawer displays the newest cart items first.
            $cartItem = $cart[$key];
            $cartItem['quantity'] += $qty;
            unset($cart[$key]);
            $cart[$key] = $cartItem;
        } else {
            $price = $variant
                ? ProductDayPricing::sellingPrice($variant, $pricingDay, (float) $product->price)
                : (float) $product->price;
            $mrp = $variant
                ? ProductDayPricing::mrp($variant, (float) ($product->mrp ?? $price))
                : (float) ($product->mrp ?? $price);

            $cart[$key] = [
                'product_id'    => $product->id,
                'name'          => $product->name,
                'slug'          => $product->slug,
                'image'         => $product->images[0] ?? null,
                'price'         => $price,
                'mrp'           => $mrp,
                'unit'          => $variant ? ($variant['unit'] ?? $product->unit) : $product->unit,
                'quantity'      => $qty,
                'variant_index' => $variantIndex,
                'variant_label' => $variant ? $this->formatVariantLabel($variant, $product->unit) : null,
                'pricing_day'   => $pricingDay,
                'pricing_day_label' => ProductDayPricing::dayLabel($pricingDay),
            ];
        }

        session(['cart' => $cart]);

        if ($request->filled('delivery_slot')) {
            session(['selected_delivery_slot' => $request->string('delivery_slot')->toString()]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success'      => true,
                'message'      => 'Added to cart!',
                'cart_count'   => array_sum(array_column($cart, 'quantity')),
                'unique_count' => count($cart),
            ]);
        }

        return back()->with('success', 'Added to cart!');
    }

    /** POST /cart/update */
    public function update(Request $request)
    {
        $request->validate(['key' => 'required', 'quantity' => 'required|integer|min:0']);

        $cart = session('cart', []);
        $key  = $request->key;

        if ($request->quantity <= 0) {
            unset($cart[$key]);
        } elseif (isset($cart[$key])) {
            $cart[$key]['quantity'] = $request->quantity;
        }

        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            $items    = $this->buildCartItems($cart);
            $subtotal = collect($items)->sum(fn($i) => $i['subtotal']);
            $pricing = OrderPricing::summary($subtotal, auth('web_frontend')->user(), $this->pricingDayForCart($cart));
            return response()->json([
                'success'    => true,
                'cart_count' => array_sum(array_column($cart, 'quantity')),
                'subtotal'   => $subtotal,
                'delivery_charge' => $pricing['delivery_charge'],
                'service_charge' => $pricing['service_charge'],
                'service_charge_percent' => $pricing['service_charge_percent'],
                'total' => $pricing['total'],
            ]);
        }

        return back();
    }

    /** POST /cart/delivery-slot */
    public function updateDeliverySlot(Request $request)
    {
        $upcomingDates = DeliverySlotManager::upcomingDatesForCustomer();
        $allowedDates = array_column($upcomingDates, 'date');

        $data = $request->validate([
            'delivery_date' => ['required', 'string', Rule::in($allowedDates)],
        ]);

        $dayOptions = collect($upcomingDates)->firstWhere('date', $data['delivery_date'])['options'] ?? [];

        $data += $request->validate([
            'delivery_slot' => ['required', 'string', Rule::in(array_column($dayOptions, 'value'))],
        ]);

        session([
            'selected_delivery_date' => $data['delivery_date'],
            'selected_delivery_slot' => $data['delivery_slot'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'delivery_date' => $data['delivery_date'],
                'delivery_slot' => $data['delivery_slot'],
                'delivery_slot_label' => DeliverySlotManager::label($data['delivery_slot']),
            ]);
        }

        return back()->with('success', 'Delivery slot updated.');
    }

    /** POST /cart/delivery-date — switch which date's slots are shown, without picking a slot yet. */
    public function updateDeliveryDate(Request $request)
    {
        $upcomingDates = DeliverySlotManager::upcomingDatesForCustomer();
        $allowedDates = array_column($upcomingDates, 'date');

        $data = $request->validate([
            'delivery_date' => ['required', 'string', Rule::in($allowedDates)],
        ]);

        $dayOptions = collect($upcomingDates)->firstWhere('date', $data['delivery_date'])['options'] ?? [];

        session([
            'selected_delivery_date' => $data['delivery_date'],
            'selected_delivery_slot' => $dayOptions[0]['value'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'delivery_date' => $data['delivery_date'],
            'options' => $dayOptions,
            'selected_delivery_slot' => $dayOptions[0]['value'] ?? null,
        ]);
    }

    /** POST /cart/remove */
    public function remove(Request $request)
    {
        $cart = session('cart', []);
        unset($cart[$request->key]);
        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'success'    => true,
                'cart_count' => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return back()->with('success', 'Item removed.');
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->validate([
            'code' => ['required', 'string'],
            'delivery_day' => ['required', Rule::in(['today', 'tomorrow'])],
        ]);
        $day = ProductDayPricing::normalizeDay($data['delivery_day']);
        $cart = array_filter(session('cart', []), fn ($item) => ProductDayPricing::normalizeDay($item['pricing_day'] ?? 'today') === $day);
        $items = $this->buildCartItems($cart);
        $subtotal = collect($items)->sum('subtotal');
        $coupon = Coupon::valid()->where('code', strtoupper($data['code']))->first();

        if (! $coupon || $subtotal < (float) ($coupon->min_order_amount ?? 0) || ! $coupon->canBeUsedBy(auth('web_frontend')->id())) {
            return response()->json(['success' => false, 'message' => 'Coupon is not eligible or its usage limit has been reached.'], 422);
        }

        // If coupon is tied to a specific product, verify that product is in the cart
        if (! is_null($coupon->product_id)) {
            $cartProductIds = collect($items)->pluck('product_id')->filter()->unique();
            if (! $cartProductIds->contains($coupon->product_id)) {
                return response()->json(['success' => false, 'message' => 'This coupon is not applicable to the items in your cart.'], 422);
            }
        }

        session(['applied_coupon_' . $day => $coupon->code]);
        return response()->json(['success' => true, 'message' => 'Discount applied successfully.']);
    }

    public function removeCoupon(Request $request)
    {
        $day = ProductDayPricing::normalizeDay($request->input('delivery_day'));
        session()->forget('applied_coupon_' . $day);

        return response()->json(['success' => true]);
    }

    /** GET /cart/count (AJAX) */
    public function count(Request $request)
    {
        $cart = session('cart', []);
        $availableDays = collect($cart)->map(fn ($item) => ProductDayPricing::normalizeDay($item['pricing_day'] ?? 'today'))->unique()->values();
        $selectedDay = ProductDayPricing::normalizeDay($request->input('delivery_day', $availableDays->first() ?? 'today'));
        if (! $availableDays->contains($selectedDay)) {
            $selectedDay = $availableDays->first() ?? 'today';
        }
        $dayCart = array_filter($cart, fn ($item) => ProductDayPricing::normalizeDay($item['pricing_day'] ?? 'today') === $selectedDay);
        $items = $this->buildCartItems($dayCart);
        $subtotal = collect($items)->sum('subtotal');
        $pricing = OrderPricing::summary($subtotal, auth('web_frontend')->user(), $selectedDay);
        $couponData = $this->couponData($subtotal, $selectedDay);
        $pricing['total'] = max(0, $pricing['total'] - $couponData['discount']);

        $upcomingDates = DeliverySlotManager::upcomingDatesForCustomer();
        $selectedDeliveryDate = $this->resolveSelectedDate($upcomingDates);
        $deliverySlotOptions = collect($upcomingDates)->firstWhere('date', $selectedDeliveryDate)['options'] ?? [];
        $selectedDeliverySlot = session('selected_delivery_slot');

        if (! in_array($selectedDeliverySlot, array_column($deliverySlotOptions, 'value'), true)) {
            $selectedDeliverySlot = $deliverySlotOptions[0]['value'] ?? null;
            session(['selected_delivery_slot' => $selectedDeliverySlot]);
        }

        return response()->json([
            'count'        => array_sum(array_column($cart, 'quantity')),
            'unique_count' => count($cart),
            'items'        => $items,
            'subtotal' => $subtotal,
            'service_charge' => $pricing['service_charge'],
            'service_charge_percent' => $pricing['service_charge_percent'],
            'delivery_charge' => $pricing['delivery_charge'],
            'total' => $pricing['total'],
            'selected_delivery_slot' => $selectedDeliverySlot,
            'selected_delivery_slot_label' => DeliverySlotManager::label($selectedDeliverySlot),
            'selected_delivery_date' => $selectedDeliveryDate,
            'delivery_slot_options' => $deliverySlotOptions,
            'delivery_day' => $selectedDay,
            'delivery_day_label' => ucfirst($selectedDay),
            'available_days' => $availableDays,
            'discount' => $couponData['discount'],
            'applied_coupon' => $couponData['applied'],
            'available_coupons' => $couponData['available'],
        ]);
    }

    private function buildCartItems(array $cart): array
    {
        // PHP session arrays keep insertion order; reverse it so the most recently
        // added (or updated) product is always shown at the top of the cart.
        $cart = array_reverse($cart, true);

        $productIds = array_column($cart, 'product_id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        return array_map(function ($item, $key) use ($products) {
            $product = $products->get($item['product_id']);
            $item['key']         = $key;
            $item['is_out_of_stock'] = !$product || $product->is_out_of_stock;
            $item['subtotal']    = $item['price'] * $item['quantity'];
            $item['image_url']   = $item['image'] ? asset('storage/' . $item['image']) : null;
            $item['product_url'] = route('frontend.product.show', $item['slug']);
            return $item;
        }, array_values($cart), array_keys($cart));
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

    private function usesTomorrowDelivery(array $cart): bool
    {
        $days = collect($cart)
            ->pluck('pricing_day')
            ->filter()
            ->values();

        return $days->contains('tomorrow') && ! $days->contains('today');
    }

    private function pricingDayForCart(array $cart): string
    {
        return $this->usesTomorrowDelivery($cart) ? 'tomorrow' : 'today';
    }

    private function couponData(float $subtotal, string $day): array
    {
        $userId = auth('web_frontend')->id();
        $hasEntryType = \Illuminate\Support\Facades\Schema::hasColumn('coupons', 'entry_type');

        // Collect product IDs currently in the cart for the given day
        $dayCart = array_filter(session('cart', []), fn ($item) => \App\Support\ProductDayPricing::normalizeDay($item['pricing_day'] ?? 'today') === $day);
        $cartProductIds = collect($dayCart)->pluck('product_id')->filter()->unique()->values();

        // Build cart items once for discount calculations
        $dayItems = $this->buildCartItems($dayCart);

        $allCoupons = Coupon::valid()
            ->where('min_order_amount', '<=', $subtotal)
            ->orderByDesc('value')
            ->get()
            ->filter(function (Coupon $coupon) use ($userId, $cartProductIds) {
                if (! is_null($coupon->product_id) && ! $cartProductIds->contains($coupon->product_id)) {
                    return false;
                }
                return $coupon->canBeUsedBy($userId);
            });

        // Product-specific offers: show per product, code = null if entry_type = 'offer'
        // so they cannot be manually typed — they must be clicked
        $productOffers = $allCoupons
            ->filter(fn (Coupon $c) => ! is_null($c->product_id))
            ->mapWithKeys(fn (Coupon $c) => [
                $c->product_id => [
                    'code'  => ($hasEntryType && $c->entry_type === 'offer') ? null : $c->code,
                    'title' => $c->title ?: $c->code,
                    'type'  => $c->type,
                    'value' => (float) $c->value,
                ],
            ]);

        // Cart-wide coupons (no product_id)
        $available = $allCoupons
            ->filter(fn (Coupon $c) => is_null($c->product_id))
            ->map(fn (Coupon $c) => [
                'code'  => $c->code,
                'title' => $c->title ?: $c->code,
                'type'  => $c->type,
                'value' => (float) $c->value,
            ])->values();

        // Merge product offers into the available list (they appear first)
        $available = $productOffers->values()->concat($available)->values();

        // Resolve applied coupon from session
        $code = session('applied_coupon_' . $day);
        $coupon = $code ? Coupon::valid()->where('code', $code)->first() : null;

        // Auto-remove if product left cart or coupon no longer valid
        if ($coupon && ! is_null($coupon->product_id) && ! $cartProductIds->contains($coupon->product_id)) {
            session()->forget('applied_coupon_' . $day);
            $coupon = null;
        }
        if ($coupon && (! $coupon->canBeUsedBy($userId) || $subtotal < (float) ($coupon->min_order_amount ?? 0))) {
            session()->forget('applied_coupon_' . $day);
            $coupon = null;
        }

        // Calculate discount on the correct base (product subtotal vs full subtotal)
        if ($coupon) {
            if (! is_null($coupon->product_id)) {
                $base = (float) collect($dayItems)
                    ->where('product_id', $coupon->product_id)
                    ->sum('subtotal');
            } else {
                $base = $subtotal;
            }
            $discount = $coupon->type === 'percent'
                ? round($base * $coupon->value / 100, 2)
                : min((float) $coupon->value, $base);
        } else {
            $discount = 0.0;
        }

        return [
            'discount' => $discount,
            'applied'  => $coupon ? ['code' => $coupon->code, 'title' => $coupon->title ?: $coupon->code] : null,
            'available' => $available,
        ];
    }
}

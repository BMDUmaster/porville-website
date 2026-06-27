<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\DeliverySlotManager;
use App\Support\OrderPricing;
use App\Support\ProductDayPricing;
use Illuminate\Http\Request;
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
        $deliverySlotOptions = $this->deliverySlotOptionsForCart($cart);
        $selectedDeliverySlot = session('selected_delivery_slot_' . $selectedDay);

        if (! in_array($selectedDeliverySlot, array_column($deliverySlotOptions, 'value'), true)) {
            $selectedDeliverySlot = $deliverySlotOptions[0]['value'] ?? null;
            session(['selected_delivery_slot_' . $selectedDay => $selectedDeliverySlot]);
        }
        $deliveryDayLabel = $this->deliveryDayLabelForCart($cart);

        return view('frontend.cart', compact('items', 'pricing', 'deliverySlotOptions', 'selectedDeliverySlot', 'deliveryDayLabel', 'availableDays', 'selectedDay'));
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
        $qty          = $request->get('quantity', 1);
        $variantIndex = $request->get('variant_index');
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

        if (! $product->is_active) {
            $message = 'This product is currently out of stock.';

            if ($request->expectsJson()) {
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
            $cart[$key]['quantity'] += $qty;
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
                'success'    => true,
                'message'    => 'Added to cart!',
                'cart_count' => array_sum(array_column($cart, 'quantity')),
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
        $day = ProductDayPricing::normalizeDay($request->input('delivery_day'));
        $data = $request->validate([
            'delivery_day' => ['nullable', 'string', Rule::in(['today', 'tomorrow'])],
            'delivery_slot' => ['required', 'string', Rule::in(array_column($this->deliverySlotOptionsForDay($day), 'value'))],
        ]);

        session(['selected_delivery_slot_' . $day => $data['delivery_slot']]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'delivery_slot' => $data['delivery_slot'],
                'delivery_slot_label' => DeliverySlotManager::label($data['delivery_slot']),
            ]);
        }

        return back()->with('success', 'Delivery slot updated.');
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
        $selectedDeliverySlot = session('selected_delivery_slot_' . $selectedDay);
        $deliverySlotOptions = $this->deliverySlotOptionsForDay($selectedDay);

        if (! in_array($selectedDeliverySlot, array_column($deliverySlotOptions, 'value'), true)) {
            $selectedDeliverySlot = $deliverySlotOptions[0]['value'] ?? null;
            session(['selected_delivery_slot_' . $selectedDay => $selectedDeliverySlot]);
        }

        return response()->json([
            'count' => array_sum(array_column($cart, 'quantity')),
            'items' => $items,
            'subtotal' => $subtotal,
            'service_charge' => $pricing['service_charge'],
            'service_charge_percent' => $pricing['service_charge_percent'],
            'delivery_charge' => $pricing['delivery_charge'],
            'total' => $pricing['total'],
            'selected_delivery_slot' => $selectedDeliverySlot,
            'selected_delivery_slot_label' => DeliverySlotManager::label($selectedDeliverySlot),
            'delivery_slot_options' => $deliverySlotOptions,
            'delivery_day' => $selectedDay,
            'delivery_day_label' => ucfirst($selectedDay),
            'available_days' => $availableDays,
        ]);
    }

    private function buildCartItems(array $cart): array
    {
        return array_map(function ($item, $key) {
            $item['key']      = $key;
            $item['subtotal'] = $item['price'] * $item['quantity'];
            $item['image_url'] = $item['image'] ? asset('storage/' . $item['image']) : null;
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

    private function deliverySlotOptionsForCart(array $cart): array
    {
        return $this->usesTomorrowDelivery($cart)
            ? DeliverySlotManager::options()
            : DeliverySlotManager::availableOptions();
    }

    private function deliverySlotOptionsForDay(string $day): array
    {
        return $day === 'tomorrow'
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

    private function deliveryDayLabelForCart(array $cart): string
    {
        return $this->usesTomorrowDelivery($cart) ? 'Tomorrow' : 'Today';
    }

    private function pricingDayForCart(array $cart): string
    {
        return $this->usesTomorrowDelivery($cart) ? 'tomorrow' : 'today';
    }
}

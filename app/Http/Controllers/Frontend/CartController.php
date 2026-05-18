<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Support\DeliverySlotManager;
use App\Support\OrderPricing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CartController extends Controller
{
    /** GET /cart */
    public function index()
    {
        $cart  = session('cart', []);
        $items = $this->buildCartItems($cart);
        $subtotal = collect($items)->sum('subtotal');
        $pricing = OrderPricing::summary($subtotal);
        $deliverySlotOptions = DeliverySlotManager::options();
        $selectedDeliverySlot = session('selected_delivery_slot');

        if (! in_array($selectedDeliverySlot, DeliverySlotManager::values(), true)) {
            $selectedDeliverySlot = DeliverySlotManager::defaultValue();
        }

        return view('frontend.cart', compact('items', 'pricing', 'deliverySlotOptions', 'selectedDeliverySlot'));
    }

    /** POST /cart/add */
    public function add(Request $request)
    {
        $request->validate([
            'product_id'    => 'required|exists:products,id',
            'quantity'      => 'integer|min:1',
            'variant_index' => 'nullable|integer|min:0',
            'delivery_slot' => ['nullable', 'string', Rule::in(DeliverySlotManager::values())],
        ]);

        $product      = Product::findOrFail($request->product_id);
        $qty          = $request->get('quantity', 1);
        $variantIndex = $request->get('variant_index');

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
        $key  = $product->id . ($variantIndex !== null ? '_v' . $variantIndex : '');

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] += $qty;
        } else {
            $variant = ($variantIndex !== null && isset($product->variants[$variantIndex]))
                       ? $product->variants[$variantIndex]
                       : null;

            $cart[$key] = [
                'product_id'    => $product->id,
                'name'          => $product->name,
                'slug'          => $product->slug,
                'image'         => $product->images[0] ?? null,
                'price'         => $variant ? ($variant['selling_price'] ?? $product->price) : $product->price,
                'mrp'           => $variant ? ($variant['mrp'] ?? $product->mrp ?? $product->price) : ($product->mrp ?? $product->price),
                'unit'          => $variant ? ($variant['unit'] ?? $product->unit) : $product->unit,
                'quantity'      => $qty,
                'variant_index' => $variantIndex,
                'variant_label' => $variant ? $this->formatVariantLabel($variant, $product->unit) : null,
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
            $pricing = OrderPricing::summary($subtotal);
            return response()->json([
                'success'    => true,
                'cart_count' => array_sum(array_column($cart, 'quantity')),
                'subtotal'   => $subtotal,
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
        $data = $request->validate([
            'delivery_slot' => ['required', 'string', Rule::in(DeliverySlotManager::values())],
        ]);

        session(['selected_delivery_slot' => $data['delivery_slot']]);

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
    public function count()
    {
        $cart = session('cart', []);
        $items = $this->buildCartItems($cart);
        $subtotal = collect($items)->sum('subtotal');
        $pricing = OrderPricing::summary($subtotal);
        $selectedDeliverySlot = session('selected_delivery_slot');

        if (! in_array($selectedDeliverySlot, DeliverySlotManager::values(), true)) {
            $selectedDeliverySlot = DeliverySlotManager::defaultValue();
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
}

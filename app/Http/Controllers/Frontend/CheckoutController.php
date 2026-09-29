<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUserUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\RazorpayPayment;
use App\Support\DeliveryAreaManager;
use App\Support\DeliverySlotManager;
use App\Support\OrderPricing;
use App\Support\ProductDayPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CheckoutController extends Controller
{
    private const COD_MAX_SUBTOTAL = 2000;

    /** GET /checkout */
    public function index(Request $request)
    {
        if (! $this->canPlaceOrders()) {
            return redirect()->route('frontend.cart')->with('account_blocked', true);
        }

        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('frontend.cart')->with('error', 'Your cart is empty.');
        }

        $checkoutDay = $this->checkoutDay($request, $cart);
        $cart = $this->cartForDay($cart, $checkoutDay);

        if (empty($cart)) {
            return redirect()->route('frontend.cart')->with('error', 'No items found for the selected delivery day.');
        }

        session(['checkout_delivery_day' => $checkoutDay]);

        try {
            $items = $this->buildCartItems($cart);
        } catch (ValidationException $exception) {
            return redirect()->route('frontend.cart')->withErrors($exception->errors());
        }

        $subtotal = collect($items)->sum('subtotal');
        $checkoutDefaults = $this->checkoutDefaults();

        $upcomingDates = DeliverySlotManager::upcomingDatesForCustomer();
        $allowedDates = array_column($upcomingDates, 'date');
        // Checkout always defaults to the nearest available date, not whatever was last picked on the cart page.
        $selectedDeliveryDate = old('delivery_date', $allowedDates[0] ?? null);
        if (! in_array($selectedDeliveryDate, $allowedDates, true)) {
            $selectedDeliveryDate = $allowedDates[0] ?? null;
        }
        session(['selected_delivery_date' => $selectedDeliveryDate]);

        $deliverySlotOptions = collect($upcomingDates)->firstWhere('date', $selectedDeliveryDate)['options'] ?? [];
        $selectedDeliverySlot = old('delivery_slot', session('selected_delivery_slot'));

        if (! in_array($selectedDeliverySlot, array_column($deliverySlotOptions, 'value'), true)) {
            $selectedDeliverySlot = $deliverySlotOptions[0]['value'] ?? null;
            session(['selected_delivery_slot' => $selectedDeliverySlot]);
        }

        $pricing = OrderPricing::summary($subtotal, auth('web_frontend')->user(), $this->pricingDayForCart($cart));
        $cartProductIds = collect($items)->pluck('product_id')->filter()->unique();

        // Show only proper coupons (entry_type = 'coupon') as chips on checkout
        // Offers (entry_type = 'offer' / AUTO-OFFER-* codes) are cart-only
        $availableCoupons = Coupon::valid()
            ->where(function ($q) use ($cartProductIds) {
                $q->whereNull('product_id')
                  ->orWhere(function ($q2) use ($cartProductIds) {
                      if ($cartProductIds->isNotEmpty()) {
                          $q2->whereIn('product_id', $cartProductIds);
                      } else {
                          $q2->whereRaw('0=1');
                      }
                  });
            })
            ->where(function ($q) {
                // Only manual coupon codes, not auto-generated offer codes
                $q->where('entry_type', 'coupon')
                  ->orWhereNull('entry_type');
            })
            ->orderBy('min_order_amount')
            ->orderByDesc('value')
            ->limit(8)
            ->get();
        $checkoutNewArrivals = Product::active()->notEnquiryOnly()
            ->with('category')
            ->when($cartProductIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $cartProductIds))
            ->latest()
            ->take(3)
            ->get();

        $cartCategoryIds = Product::query()
            ->whereIn('id', $cartProductIds)
            ->pluck('category_id')
            ->filter()
            ->unique();
        $excludedRecommendationIds = $cartProductIds
            ->merge($checkoutNewArrivals->pluck('id'))
            ->unique();
        $checkoutSimilarProducts = Product::active()->notEnquiryOnly()
            ->with('category')
            ->when($cartCategoryIds->isNotEmpty(), fn ($query) => $query->whereIn('category_id', $cartCategoryIds))
            ->when($excludedRecommendationIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $excludedRecommendationIds))
            ->latest()
            ->take(3)
            ->get();

        if ($checkoutSimilarProducts->count() < 3) {
            $fallbackExcludedIds = $excludedRecommendationIds
                ->merge($checkoutSimilarProducts->pluck('id'))
                ->unique();
            $fallbackProducts = Product::active()->notEnquiryOnly()
                ->with('category')
                ->when($fallbackExcludedIds->isNotEmpty(), fn ($query) => $query->whereNotIn('id', $fallbackExcludedIds))
                ->latest()
                ->take(3 - $checkoutSimilarProducts->count())
                ->get();
            $checkoutSimilarProducts = $checkoutSimilarProducts
                ->concat($fallbackProducts)
                ->values();
        }

        $user = auth('web_frontend')->user();
        $pastAddresses = [];
        if ($user) {
            $pastAddresses = $user->orders()
                ->whereNotNull('shipping_address')
                ->latest()
                ->pluck('shipping_address')
                ->filter()
                ->unique(function ($address) {
                    return strtolower(
                        trim($address['address'] ?? '') . '|' . 
                        trim($address['city'] ?? '') . '|' . 
                        trim($address['state'] ?? '') . '|' . 
                        trim($address['pincode'] ?? '') . '|' .
                        trim($address['sector'] ?? '')
                    );
                })
                ->values()
                ->all();
        }

        return view('frontend.checkout', compact('items', 'subtotal', 'checkoutDefaults', 'selectedDeliverySlot', 'selectedDeliveryDate', 'upcomingDates', 'deliverySlotOptions', 'pricing', 'availableCoupons', 'pastAddresses', 'checkoutNewArrivals', 'checkoutSimilarProducts'))
            ->with('pinSectors', DeliveryAreaManager::pinSectors());
    }

    /** POST /checkout */
    public function store(Request $request)
    {
        if (! $this->canPlaceOrders()) {
            return redirect()->route('frontend.cart')->with('account_blocked', true);
        }

        $fullCart = session('cart', []);
        $checkoutDay = ProductDayPricing::normalizeDay(session('checkout_delivery_day', 'today'));
        $cart = $this->cartForDay($fullCart, $checkoutDay);

        $upcomingDates = DeliverySlotManager::upcomingDatesForCustomer();
        $allowedDates = array_column($upcomingDates, 'date');

        $data = $request->validate([
            'first_name'       => 'required|string|max:60',
            'last_name'        => 'nullable|string|max:60',
            'email'            => 'required|email',
            'phone'            => ['required', 'regex:/^\d{10}$/'],
            'address'          => 'required|string',
            'city'             => 'required|string',
            'state'            => 'required|string',
            'pincode'          => ['required', 'regex:/^\d{6}$/'],
            'sector'           => [DeliveryAreaManager::isRestricted() ? 'required' : 'nullable', 'string', 'max:100'],
            'delivery_date'    => ['required', 'string', Rule::in($allowedDates)],
            'payment_method'   => 'required|in:COD,online',
            'coupon_code'      => 'nullable|string',
        ], [
            'phone.regex' => 'Phone number must be exactly 10 digits.',
            'sector.required' => 'Please select your Area / Sector from the list.',
        ]);

        $sector = DeliveryAreaManager::match($data['pincode'], $data['sector'] ?? null);
        if ($sector === null) {
            throw ValidationException::withMessages([
                'sector' => 'We do not deliver to this Area / PIN Code yet. Please select your Area / Sector from the list.',
            ]);
        }
        $data['sector'] = $sector;

        $dayOptions = collect($upcomingDates)->firstWhere('date', $data['delivery_date'])['options'] ?? [];
        $data += $request->validate([
            'delivery_slot' => ['required', 'string', Rule::in(array_column($dayOptions, 'value'))],
        ]);

        session([
            'selected_delivery_date' => $data['delivery_date'],
            'selected_delivery_slot' => $data['delivery_slot'],
        ]);

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
        $pricing = OrderPricing::summary($subtotal, $user, $this->pricingDayForCart($cart));

        if ($data['payment_method'] === 'COD' && $subtotal > self::COD_MAX_SUBTOTAL) {
            throw ValidationException::withMessages([
                'payment_method' => 'Cash on Delivery is available only for orders up to Rs2,000.',
            ]);
        }

        $order = DB::transaction(function () use ($data, $items, $subtotal, $pricing, $user, $checkoutDay) {
            $discount = 0;
            // Use form coupon_code; if empty, fall back to session (handles auto-applied offers)
            $couponCodeToApply = $data['coupon_code'] ?? null;
            if (empty(trim((string) $couponCodeToApply))) {
                $couponCodeToApply = session('applied_coupon_' . $checkoutDay);
            }
            $coupon = $this->resolveCoupon($couponCodeToApply, $subtotal, true, $items);

            if ($coupon) {
                $discount = $this->calculateDiscount($coupon, $subtotal, $items);
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
                    'sector'  => $data['sector'],
                ],
                'payment_method'   => $data['payment_method'],
                'payment_status'   => 'pending',
                'delivery_slot'    => $data['delivery_slot'],
                'delivery_date'    => $data['delivery_date'],
                'delivery_day'     => $checkoutDay,
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
                if ($user) {
                    CouponUserUsage::query()->updateOrCreate(
                        ['coupon_id' => $coupon->id, 'user_id' => $user->id],
                        ['usage_count' => $coupon->usageCountFor($user->id) + 1]
                    );
                }
            }

            return $order;
        });

        // For online/upi — call Razorpay before clearing cart
        if (in_array($data['payment_method'], ['online'], true)) {
            try {
                $razorpayService = app(\App\Services\RazorpayService::class);
                $amountPaise     = (int) round($order->total * 100);
                $rzpOrder        = $razorpayService->createOrder($amountPaise, $order->order_number);

                RazorpayPayment::create([
                    'order_id'          => $order->id,
                    'razorpay_order_id' => $rzpOrder['id'],
                    'amount'            => $rzpOrder['amount'],
                    'status'            => 'initiated',
                ]);

                return redirect()->route('frontend.razorpay.payment', $order->id);

            } catch (\Throwable $e) {
                Log::error('Razorpay order creation failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
                $order->delete();
                return back()->withInput()->with('error', 'Payment gateway is unavailable. Please try again.');
            }
        }

        // COD path — existing logic:
        \App\Services\OrderStatusNotificationService::notifyStatusChange($order);

        $remainingCart = array_diff_key($fullCart, $cart);
        session(['cart' => $remainingCart]);
        session()->forget('applied_coupon_' . $checkoutDay);
        session()->forget('checkout_delivery_day');

        return redirect()->route('frontend.order.success', $order->id);
    }

    private function canPlaceOrders(): bool
    {
        return auth('web_frontend')->user()?->canPlaceOrders() ?? false;
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

            if (! $product || $product->is_out_of_stock) {
                throw ValidationException::withMessages([
                    'cart' => 'One or more products in your cart are currently out of stock.',
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
                'sector' => '',
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
            'sector' => $latestAddress['sector'] ?? '',
        ];
    }

    private function cleanCheckoutPhone(mixed $phone): string
    {
        $digits = preg_replace('/\D/', '', (string) $phone) ?? '';

        return strlen($digits) === 10 ? $digits : '';
    }

    private function resolveCoupon(?string $couponCode, float $subtotal, bool $lock = false, array $items = []): ?Coupon
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

        if (! $coupon->canBeUsedBy(auth('web_frontend')->id())) {
            throw ValidationException::withMessages([
                'coupon_code' => 'You have reached the usage limit for this coupon or offer.',
            ]);
        }

        // If coupon is tied to a specific product, verify that product is in the cart
        if (! is_null($coupon->product_id)) {
            $cartProductIds = collect($items)->pluck('product_id')->filter()->unique();
            if (! $cartProductIds->contains($coupon->product_id)) {
                throw ValidationException::withMessages([
                    'coupon_code' => 'This coupon is not applicable to the items in your cart.',
                ]);
            }
        }

        if ($subtotal < (float) ($coupon->min_order_amount ?? 0)) {
            throw ValidationException::withMessages([
                'coupon_code' => 'This coupon requires a higher order amount.',
            ]);
        }

        return $coupon;
    }

    private function calculateDiscount(Coupon $coupon, float $subtotal, array $items = []): float
    {
        // If coupon is tied to a specific product, apply discount only on that product's subtotal
        if (! is_null($coupon->product_id)) {
            $applicableSubtotal = collect($items)
                ->where('product_id', $coupon->product_id)
                ->sum('subtotal');

            $base = (float) $applicableSubtotal;
        } else {
            $base = $subtotal;
        }

        return $coupon->type === 'percent'
            ? round($base * $coupon->value / 100, 2)
            : min((float) $coupon->value, $base);
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

    private function checkoutDay(Request $request, array $cart): string
    {
        $requestedDay = ProductDayPricing::normalizeDay($request->query('delivery_day'));
        $availableDays = collect($cart)
            ->map(fn ($item) => ProductDayPricing::normalizeDay($item['pricing_day'] ?? 'today'))
            ->unique();

        return $availableDays->contains($requestedDay)
            ? $requestedDay
            : ($availableDays->first() ?? 'today');
    }

    private function cartForDay(array $cart, string $day): array
    {
        return array_filter(
            $cart,
            fn ($item) => ProductDayPricing::normalizeDay($item['pricing_day'] ?? 'today') === $day
        );
    }
}

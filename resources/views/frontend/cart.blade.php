@extends('frontend.layouts.app')
@section('title', 'Your Cart')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="nunito mb-6 text-2xl font-extrabold text-gray-800">
        Your Cart <span class="text-base font-semibold text-gray-400">({{ count($items) }} items)</span>
    </h1>

    @if(empty($items))
        <div class="rounded-2xl border bg-white p-16 text-center">
            <i class="fa-solid fa-cart-shopping mb-4 block text-6xl text-gray-200"></i>
            <p class="mb-4 font-semibold text-gray-500">Your cart is empty</p>
            <a href="{{ route('frontend.products') }}" class="inline-block rounded-xl bg-blue-700 px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                Continue Shopping
            </a>
        </div>
    @else
    <div class="mb-6 overflow-hidden rounded-2xl border bg-white">
        <div class="h-1 bg-gradient-to-r from-green-600 to-emerald-400"></div>
        <div class="border-b px-6 py-5">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="nunito text-lg font-extrabold text-gray-800">Delivery Slot</h2>
                <span id="cartDeliverySlotLabel" class="text-[11px] font-bold text-green-700">
                    {{ \App\Support\DeliverySlotManager::label($selectedDeliverySlot) }}
                </span>
            </div>
            <p class="mt-2 text-xs text-gray-400"></p>
        </div>
        <div class="px-6 py-5">
            <div class="flex flex-wrap gap-2">
                @foreach($deliverySlotOptions as $slot)
                    <button
                        type="button"
                        onclick="updateCartDeliverySlot('{{ $slot['value'] }}')"
                        data-cart-delivery-slot="{{ $slot['value'] }}"
                        class="cart-delivery-slot-chip {{ $selectedDeliverySlot === $slot['value'] ? 'border-green-600 bg-green-50 text-green-700 shadow-sm' : 'border-gray-200 bg-white text-gray-600' }} rounded-full border px-3 py-2 text-[11px] font-bold leading-none transition hover:border-green-400 hover:text-green-700"
                    >
                        {{ $slot['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-6 lg:flex-row">
        <div class="flex-1 space-y-4">
            <div class="rounded-2xl border bg-white p-5">
                <p class="mb-3 text-sm font-semibold text-gray-700"><i class="fa-solid fa-tag mr-1 text-blue-600"></i>Have a coupon code?</p>
                <form method="GET" class="flex gap-2">
                    <input type="text" name="coupon" placeholder="Enter coupon code"
                           class="flex-1 rounded-xl border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm outline-none focus:border-blue-500">
                    <button type="submit" class="rounded-xl bg-green-600 px-5 py-2.5 text-sm font-bold text-white transition hover:bg-green-700">Apply</button>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border bg-white">
                <div class="hidden grid-cols-12 gap-3 border-b bg-gray-50 px-6 py-3 text-xs font-bold uppercase tracking-wider text-gray-400 md:grid">
                    <div class="col-span-6">Product</div>
                    <div class="col-span-2 text-center">Price</div>
                    <div class="col-span-2 text-center">Qty</div>
                    <div class="col-span-2 text-right">Total</div>
                </div>

                @foreach($items as $item)
                <div class="relative border-b px-6 py-5 last:border-b-0" id="cart-row-{{ $item['key'] }}">
                    <button onclick="removeCartItem('{{ $item['key'] }}')"
                            class="absolute right-4 top-4 flex h-7 w-7 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition hover:bg-red-100 hover:text-red-500">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                    <div class="flex flex-col gap-4 md:grid md:grid-cols-12 md:items-center md:gap-3">
                        <div class="col-span-6 flex items-start gap-4">
                            <div class="flex h-20 w-20 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl border bg-gray-50">
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" class="h-full w-full object-cover" alt="{{ $item['name'] }}">
                                @else
                                    <i class="fa-solid fa-basket-shopping text-2xl text-gray-300"></i>
                                @endif
                            </div>
                            <div>
                                <p class="pr-6 text-sm font-semibold text-gray-800">{{ $item['name'] }}</p>
                                @if($item['variant_label'])
                                    <p class="mt-1 text-xs text-gray-400">{{ $item['variant_label'] }}</p>
                                @endif
                                @if(!empty($item['pricing_day_label']))
                                    <p class="mt-1 text-xs font-semibold text-blue-600">{{ $item['pricing_day_label'] }} price</p>
                                @endif
                                <p class="mt-1 text-xs text-gray-400">{{ $item['unit'] }}</p>
                            </div>
                        </div>
                        <div class="col-span-2 text-left md:text-center">
                            <p class="text-sm font-bold text-gray-800">&#8377;{{ number_format($item['price'], 2) }}</p>
                        </div>
                        <div class="col-span-2 flex justify-start md:justify-center">
                            <div class="flex items-center overflow-hidden rounded-lg border border-gray-200">
                                <button onclick="updateCartQty('{{ $item['key'] }}', {{ $item['quantity'] - 1 }})"
                                        class="flex h-8 w-8 items-center justify-center font-bold text-gray-500 transition hover:bg-gray-100">-</button>
                                <span class="flex h-8 items-center border-x border-gray-200 px-3 text-sm font-bold" id="qty-{{ $item['key'] }}">{{ $item['quantity'] }}</span>
                                <button onclick="updateCartQty('{{ $item['key'] }}', {{ $item['quantity'] + 1 }})"
                                        class="flex h-8 w-8 items-center justify-center font-bold text-gray-500 transition hover:bg-gray-100">+</button>
                            </div>
                        </div>
                        <div class="col-span-2 text-left md:text-right">
                            <p class="text-sm font-bold text-blue-700" id="subtotal-{{ $item['key'] }}">&#8377;{{ number_format($item['subtotal'], 2) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <a href="{{ route('frontend.products') }}" class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:underline">
                <i class="fa-solid fa-arrow-left text-xs"></i> Continue Shopping
            </a>
        </div>

        <div class="w-full flex-shrink-0 lg:w-80">
            <div class="sticky top-24 space-y-4">
                <div class="overflow-hidden rounded-2xl border bg-white">
                    <div class="h-1 bg-gradient-to-r from-blue-700 to-orange-400"></div>
                    <div class="border-b px-6 py-5">
                        <h2 class="nunito text-lg font-extrabold text-gray-800">Order Summary</h2>
                    </div>
                    <div class="space-y-3 px-6 py-5">
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span class="font-semibold">&#8377;{{ number_format($pricing['subtotal'], 2) }}</span></div>
                        <div class="flex justify-between text-sm"><span class="text-gray-500">Delivery</span><span class="font-semibold text-gray-800">&#8377;{{ number_format($pricing['delivery_charge'], 2) }}</span></div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">&#8505;&#65039; Service Charge ({{ rtrim(rtrim(number_format($pricing['service_charge_percent'], 2), '0'), '.') }}%)</span>
                            <span class="font-semibold text-gray-800">&#8377;{{ number_format($pricing['service_charge'], 2) }}</span>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex justify-between">
                            <span class="font-bold text-gray-800">Total</span>
                            <span class="nunito text-xl font-extrabold text-blue-700">&#8377;{{ number_format($pricing['total'], 2) }}</span>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <a href="{{ route('frontend.checkout') }}"
                           class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-700 py-3.5 text-sm font-bold text-white transition hover:bg-blue-800">
                            <i class="fa-solid fa-lock text-xs"></i> Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
function removeCartItem(key) {
    fetch('{{ route("frontend.cart.remove") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ key })
    }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
}
function updateCartQty(key, qty) {
    fetch('{{ route("frontend.cart.update") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ key, quantity: qty })
    }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
}

function updateCartDeliverySlot(value) {
    fetch('{{ route("frontend.cart.delivery-slot") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ delivery_slot: value })
    }).then(r => r.json()).then(d => {
        if (!d.success) return;

        document.querySelectorAll('[data-cart-delivery-slot]').forEach((chip) => {
            const isActive = chip.dataset.cartDeliverySlot === value;
            chip.classList.toggle('border-green-600', isActive);
            chip.classList.toggle('bg-green-50', isActive);
            chip.classList.toggle('text-green-700', isActive);
            chip.classList.toggle('shadow-sm', isActive);
            chip.classList.toggle('border-gray-200', !isActive);
            chip.classList.toggle('bg-white', !isActive);
            chip.classList.toggle('text-gray-600', !isActive);
        });

        const label = document.getElementById('cartDeliverySlotLabel');
        if (label) {
            label.textContent = d.delivery_slot_label || '';
        }
    });
}
</script>
@endsection

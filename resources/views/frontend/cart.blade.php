@extends('frontend.layouts.app')
@section('title', 'Your Cart')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h1 class="nunito text-2xl font-extrabold text-gray-800">
            Your Cart <span class="text-base font-semibold text-gray-400">({{ count($items) }} items)</span>
        </h1>
        @if(($availableDays ?? collect())->count() > 1)
            <div class="flex rounded-full bg-slate-100 p-1">
                @foreach(['today' => 'Today', 'tomorrow' => 'Tomorrow'] as $day => $label)
                    <a href="{{ route('frontend.cart', ['delivery_day' => $day]) }}"
                       class="{{ $selectedDay === $day ? 'bg-neutral-800 text-white shadow' : 'text-slate-500' }} rounded-full px-4 py-2 text-xs font-black uppercase">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    @if(empty($items))
        <div class="rounded-2xl border bg-white p-16 text-center">
            <i class="fa-solid fa-cart-shopping mb-4 block text-6xl text-gray-200"></i>
            <p class="mb-4 font-semibold text-gray-500">Your cart is empty</p>
            <a href="{{ route('frontend.products') }}" class="inline-block rounded-xl bg-neutral-800 px-6 py-3 text-sm font-bold text-white transition hover:bg-neutral-800">
                Continue Shopping
            </a>
        </div>
    @else
    <div class="mb-6 overflow-hidden rounded-2xl border bg-white">
        <div class="h-1 bg-gradient-to-r from-amber-600 to-amber-400"></div>
        <div class="border-b px-6 py-5">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="nunito text-lg font-extrabold text-gray-800">Delivery Slot</h2>
                <span id="cartDeliverySlotLabel" class="text-[11px] font-bold text-amber-700">
                    {{ \App\Support\DeliverySlotManager::label($selectedDeliverySlot) }}
                </span>
            </div>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="flex flex-wrap gap-2" id="cartDeliveryDateTabs">
                @forelse($upcomingDates as $day)
                    <button
                        type="button"
                        onclick="selectCartDeliveryDate('{{ $day['date'] }}')"
                        data-cart-delivery-date="{{ $day['date'] }}"
                        class="cart-delivery-date-chip {{ $selectedDeliveryDate === $day['date'] ? 'border-neutral-800 bg-neutral-800 text-white' : 'border-gray-200 bg-white text-gray-600' }} rounded-full border px-3.5 py-2 text-[11px] font-bold leading-none transition hover:border-neutral-500"
                    >
                        {{ $day['date_label'] }}
                    </button>
                @empty
                    <span class="text-xs font-semibold text-gray-400">No delivery slots configured yet.</span>
                @endforelse
            </div>
            <div class="flex flex-wrap gap-2" id="cartDeliverySlotChips">
                @forelse($deliverySlotOptions as $slot)
                    <button
                        type="button"
                        onclick="updateCartDeliverySlot('{{ $slot['value'] }}')"
                        data-cart-delivery-slot="{{ $slot['value'] }}"
                        class="cart-delivery-slot-chip {{ $selectedDeliverySlot === $slot['value'] ? 'border-amber-600 bg-amber-50 text-amber-700 shadow-sm' : 'border-gray-200 bg-white text-gray-600' }} rounded-full border px-3 py-2 text-[11px] font-bold leading-none transition hover:border-amber-400 hover:text-amber-700"
                    >
                        {{ $slot['label'] }}
                    </button>
                @empty
                    <span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-2 text-[11px] font-bold leading-none text-amber-700">
                        No slots left for this date
                    </span>
                @endforelse
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-6 lg:flex-row">
        <div class="flex-1 space-y-4">
            <div class="rounded-2xl border bg-white p-5">
                <p class="mb-3 text-sm font-semibold text-gray-700"><i class="fa-solid fa-tag mr-1 text-amber-600"></i>Offers & Coupons</p>
                @if($couponData['applied'])
                    <div class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-3">
                        <div><p class="text-[10px] font-black uppercase text-emerald-600">Applied</p><p class="text-sm font-bold text-slate-800">{{ $couponData['applied']['title'] }}</p></div>
                        <button type="button" onclick="removeFullCartCoupon()" class="text-xs font-black text-red-500">Remove</button>
                    </div>
                @elseif($couponData['available']->isNotEmpty())
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach($couponData['available'] as $coupon)
                            <div class="flex items-center justify-between gap-2 rounded-xl border border-amber-100 bg-amber-50/50 p-3">
                                <div><p class="text-sm font-bold text-slate-800">{{ $coupon['title'] }}</p><p class="text-xs text-slate-500">{{ $coupon['type'] === 'percent' ? rtrim(rtrim(number_format($coupon['value'], 2), '0'), '.') . '%' : 'Rs' . number_format($coupon['value'], 2) }} off</p></div>
                                <button type="button" onclick='applyFullCartCoupon(@json($coupon["code"]))' class="rounded-lg bg-neutral-800 px-3 py-2 text-[10px] font-black text-white">Apply</button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-400">Add more eligible items to unlock an offer.</p>
                @endif
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
                                <p class="mt-1 text-xs text-gray-400">{{ $item['unit'] }}</p>
                                <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                    <span class="inline-flex items-center rounded border px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider {{ ($item['pricing_day'] ?? 'today') === 'tomorrow' ? 'bg-amber-50 text-amber-700 border-amber-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100' }}">
                                        {{ ($item['pricing_day'] ?? 'today') === 'tomorrow' ? 'Tomorrow\'s Delivery' : 'Today\'s Delivery' }}
                                    </span>
                                    @if(!empty($item['is_out_of_stock']))
                                        <span class="inline-flex items-center rounded border border-red-200 bg-red-50 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-red-600">
                                            Out of Stock
                                        </span>
                                    @elseif(!empty($item['slot_closed']))
                                        <span class="inline-flex items-center gap-1 rounded border border-red-200 bg-red-50 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-red-600">
                                            <i class="fa-solid fa-hourglass-end"></i> Time Out
                                        </span>
                                    @endif
                                </div>
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
                            <p class="text-sm font-bold text-amber-700" id="subtotal-{{ $item['key'] }}">&#8377;{{ number_format($item['subtotal'], 2) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <a href="{{ route('frontend.products') }}" class="inline-flex items-center gap-2 text-sm font-medium text-amber-600 hover:underline">
                <i class="fa-solid fa-arrow-left text-xs"></i> Continue Shopping
            </a>
        </div>

        <div class="lg:col-span-4">
            @php($hasOutOfStock = collect($items)->contains('is_out_of_stock', true))
            @php($hasSlotClosed = collect($items)->contains('slot_closed', true))
            <div class="sticky top-20 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-800">Order Summary</h2>

                @if($hasSlotClosed && ! $hasOutOfStock)
                    <div class="mt-3 rounded-xl border border-red-200 bg-red-50 p-3 text-xs font-semibold text-red-600">
                        <i class="fa-solid fa-hourglass-end mr-1"></i> Ordering time is over for some items (marked Time Out). Please remove them to proceed to checkout.
                    </div>
                @endif

                @if($hasOutOfStock)
                    <div class="mt-3 rounded-xl border border-red-200 bg-red-50 p-3 text-xs font-semibold text-red-600">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> Some items in your cart are out of stock. Please remove them to proceed to checkout.
                    </div>
                @endif

                <div class="mt-4 space-y-3">
                    <div class="flex justify-between text-sm text-gray-600"><span>Subtotal</span><span>&#8377;{{ number_format($pricing['subtotal'], 2) }}</span></div>
                    <div class="flex justify-between text-sm text-gray-600"><span>Delivery Charge</span><span>&#8377;{{ number_format($pricing['delivery_charge'], 2) }}</span></div>
                    <div class="flex justify-between text-sm text-gray-600"><span>Service Charge ({{ $pricing['service_charge_percent'] ?? 0 }}%)</span><span>&#8377;{{ number_format($pricing['service_charge'], 2) }}</span></div>
                    @if(!empty($pricing['discount']) && $pricing['discount'] > 0)
                        <div class="flex justify-between text-sm font-bold text-emerald-600"><span>Discount</span><span>-&#8377;{{ number_format($pricing['discount'], 2) }}</span></div>
                    @endif
                    <hr class="border-gray-100">
                    <div class="flex justify-between">
                        <span class="font-bold text-gray-800">Total</span>
                        <span class="nunito text-xl font-extrabold text-amber-700">&#8377;{{ number_format($pricing['total'], 2) }}</span>
                    </div>
                </div>
                <div class="px-0 pt-4">
                    @if($hasOutOfStock || $hasSlotClosed)
                        <button disabled type="button" class="flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-xl bg-gray-400 py-3.5 text-sm font-bold text-white opacity-70">
                            <i class="fa-solid fa-ban text-xs"></i> {{ $hasOutOfStock ? 'Remove Out of Stock Items' : 'Remove Time Out Items' }}
                        </button>
                    @else
                        <a href="{{ route('frontend.checkout', ['delivery_day' => $selectedDay]) }}"
                           class="flex w-full items-center justify-center gap-2 rounded-xl bg-neutral-800 py-3.5 text-sm font-bold text-white transition hover:bg-neutral-800">
                            <i class="fa-solid fa-lock text-xs"></i> Proceed to Checkout
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
function applyFullCartCoupon(code) {
    fetch('{{ url("cart/coupon") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ code, delivery_day: @json($selectedDay) })
    }).then(async r => { const data = await r.json(); if (!r.ok) throw new Error(data.message); location.reload(); }).catch(e => alert(e.message));
}
function removeFullCartCoupon() {
    fetch('{{ url("cart/coupon") }}', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ delivery_day: @json($selectedDay) })
    }).then(() => location.reload());
}
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

let cartSelectedDeliveryDate = @json($selectedDeliveryDate);

function updateCartDeliverySlot(value) {
    fetch('{{ route("frontend.cart.delivery-slot") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ delivery_slot: value, delivery_date: cartSelectedDeliveryDate })
    }).then(r => r.json()).then(d => {
        if (!d.success) return;
        markActiveSlotChip(value);

        const label = document.getElementById('cartDeliverySlotLabel');
        if (label) {
            label.textContent = d.delivery_slot_label || '';
        }
    });
}

function markActiveSlotChip(value) {
    document.querySelectorAll('[data-cart-delivery-slot]').forEach((chip) => {
        const isActive = chip.dataset.cartDeliverySlot === value;
        chip.classList.toggle('border-amber-600', isActive);
        chip.classList.toggle('bg-amber-50', isActive);
        chip.classList.toggle('text-amber-700', isActive);
        chip.classList.toggle('shadow-sm', isActive);
        chip.classList.toggle('border-gray-200', !isActive);
        chip.classList.toggle('bg-white', !isActive);
        chip.classList.toggle('text-gray-600', !isActive);
    });
}

function selectCartDeliveryDate(date) {
    fetch('{{ route("frontend.cart.delivery-date") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
        body: JSON.stringify({ delivery_date: date })
    }).then(r => r.json()).then(d => {
        if (!d.success) return;
        cartSelectedDeliveryDate = date;

        document.querySelectorAll('[data-cart-delivery-date]').forEach((chip) => {
            const isActive = chip.dataset.cartDeliveryDate === date;
            chip.classList.toggle('border-neutral-800', isActive);
            chip.classList.toggle('bg-neutral-800', isActive);
            chip.classList.toggle('text-white', isActive);
            chip.classList.toggle('border-gray-200', !isActive);
            chip.classList.toggle('bg-white', !isActive);
            chip.classList.toggle('text-gray-600', !isActive);
        });

        const container = document.getElementById('cartDeliverySlotChips');
        if (container) {
            if (!d.options.length) {
                container.innerHTML = '<span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-2 text-[11px] font-bold leading-none text-amber-700">No slots left for this date</span>';
            } else {
                container.innerHTML = d.options.map((slot) => `
                    <button type="button" onclick="updateCartDeliverySlot('${slot.value}')" data-cart-delivery-slot="${slot.value}"
                            class="cart-delivery-slot-chip border-gray-200 bg-white text-gray-600 rounded-full border px-3 py-2 text-[11px] font-bold leading-none transition hover:border-amber-400 hover:text-amber-700">
                        ${slot.label}
                    </button>
                `).join('');
            }
        }

        markActiveSlotChip(d.selected_delivery_slot || '');
        const label = document.getElementById('cartDeliverySlotLabel');
        if (label) {
            const active = (d.options || []).find((slot) => slot.value === d.selected_delivery_slot);
            label.textContent = active ? active.label : '';
        }
    });
}
</script>
@endsection

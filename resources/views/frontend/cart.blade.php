@extends('frontend.layouts.app')
@section('title', 'Your Cart')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="nunito font-extrabold text-2xl text-gray-800 mb-6">
        Your Cart <span class="text-base font-semibold text-gray-400">({{ count($items) }} items)</span>
    </h1>

    @if(empty($items))
        <div class="bg-white rounded-2xl border p-16 text-center">
            <i class="fa-solid fa-cart-shopping text-6xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold mb-4">Your cart is empty</p>
            <a href="{{ route('frontend.products') }}" class="inline-block bg-blue-700 text-white px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-800 transition">
                Continue Shopping
            </a>
        </div>
    @else
    <div class="flex flex-col lg:flex-row gap-6">
        {{-- Items --}}
        <div class="flex-1 space-y-4">
            {{-- Coupon --}}
            <div class="bg-white rounded-2xl border p-5">
                <p class="text-sm font-semibold text-gray-700 mb-3"><i class="fa-solid fa-tag text-blue-600 mr-1"></i>Have a coupon code?</p>
                <form method="GET" class="flex gap-2">
                    <input type="text" name="coupon" placeholder="Enter coupon code"
                           class="flex-1 px-4 py-2.5 text-sm border border-gray-200 rounded-xl outline-none focus:border-blue-500 bg-gray-50">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition">Apply</button>
                </form>
            </div>

            <div class="bg-white rounded-2xl border overflow-hidden">
                <div class="hidden md:grid grid-cols-12 gap-3 px-6 py-3 border-b bg-gray-50 text-xs font-bold text-gray-400 uppercase tracking-wider">
                    <div class="col-span-6">Product</div>
                    <div class="col-span-2 text-center">Price</div>
                    <div class="col-span-2 text-center">Qty</div>
                    <div class="col-span-2 text-right">Total</div>
                </div>

                @foreach($items as $item)
                <div class="px-6 py-5 border-b last:border-b-0 relative" id="cart-row-{{ $item['key'] }}">
                    <button onclick="removeCartItem('{{ $item['key'] }}')"
                            class="absolute top-4 right-4 w-7 h-7 rounded-full bg-gray-100 hover:bg-red-100 flex items-center justify-center text-gray-400 hover:text-red-500 transition">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                    <div class="flex flex-col md:grid md:grid-cols-12 md:gap-3 md:items-center gap-4">
                        <div class="col-span-6 flex gap-4 items-start">
                            <div class="w-20 h-20 rounded-xl bg-gray-50 border flex items-center justify-center flex-shrink-0 overflow-hidden">
                                @if($item['image'])
                                    <img src="{{ asset('storage/'.$item['image']) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-3xl">🥩</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800 pr-6">{{ $item['name'] }}</p>
                                @if($item['variant_label'])
                                    <p class="text-xs text-gray-400 mt-1">{{ $item['variant_label'] }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">{{ $item['unit'] }}</p>
                            </div>
                        </div>
                        <div class="col-span-2 text-left md:text-center">
                            <p class="text-sm font-bold text-gray-800">₹{{ number_format($item['price'], 2) }}</p>
                        </div>
                        <div class="col-span-2 flex justify-start md:justify-center">
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                <button onclick="updateCartQty('{{ $item['key'] }}', {{ $item['quantity'] - 1 }})"
                                        class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 font-bold">−</button>
                                <span class="text-sm font-bold px-3 border-x border-gray-200 h-8 flex items-center" id="qty-{{ $item['key'] }}">{{ $item['quantity'] }}</span>
                                <button onclick="updateCartQty('{{ $item['key'] }}', {{ $item['quantity'] + 1 }})"
                                        class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 font-bold">+</button>
                            </div>
                        </div>
                        <div class="col-span-2 text-left md:text-right">
                            <p class="text-sm font-bold text-blue-700" id="subtotal-{{ $item['key'] }}">₹{{ number_format($item['subtotal'], 2) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <a href="{{ route('frontend.products') }}" class="inline-flex items-center gap-2 text-sm text-blue-600 font-medium hover:underline">
                <i class="fa-solid fa-arrow-left text-xs"></i> Continue Shopping
            </a>
        </div>

        {{-- Summary --}}
        <div class="w-full lg:w-80 flex-shrink-0">
            <div class="bg-white rounded-2xl border overflow-hidden sticky top-24">
                <div class="h-1 bg-gradient-to-r from-blue-700 to-orange-400"></div>
                <div class="px-6 py-5 border-b">
                    <h2 class="nunito font-extrabold text-lg text-gray-800">Order Summary</h2>
                </div>
                <div class="px-6 py-5 space-y-3">
                    @php $subtotal = collect($items)->sum('subtotal'); @endphp
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span class="font-semibold">₹{{ number_format($subtotal, 2) }}</span></div>
                    <div class="flex justify-between text-sm"><span class="text-gray-500">Delivery</span><span class="text-green-600 font-semibold">FREE</span></div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between"><span class="font-bold text-gray-800">Total</span><span class="nunito font-extrabold text-xl text-blue-700">₹{{ number_format($subtotal, 2) }}</span></div>
                </div>
                <div class="px-6 pb-6">
                    <a href="{{ route('frontend.checkout') }}"
                       class="flex items-center justify-center gap-2 w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-3.5 rounded-xl text-sm transition">
                        <i class="fa-solid fa-lock text-xs"></i> Proceed to Checkout
                    </a>
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
</script>
@endsection

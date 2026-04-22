@extends('frontend.layouts.app')
@section('title', $product->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-xs text-gray-400 mb-6">
        <a href="{{ route('frontend.home') }}" class="hover:text-blue-600">Home</a>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
        <a href="{{ route('frontend.products') }}" class="hover:text-blue-600">Products</a>
        <i class="fa-solid fa-chevron-right text-[9px]"></i>
        <span class="text-gray-700 font-medium">{{ $product->name }}</span>
    </nav>

    <div class="grid md:grid-cols-2 gap-10 mb-12">
        {{-- Images --}}
        <div>
            <div class="aspect-square rounded-2xl overflow-hidden bg-gray-50 border mb-3">
                @if($product->images && count($product->images))
                    <img src="{{ asset('storage/'.$product->images[0]) }}" class="w-full h-full object-cover" id="mainImage">
                @else
                    <div class="w-full h-full flex items-center justify-center text-8xl">🥩</div>
                @endif
            </div>
            @if($product->images && count($product->images) > 1)
            <div class="flex gap-2">
                @foreach($product->images as $img)
                <button onclick="document.getElementById('mainImage').src='{{ asset('storage/'.$img) }}'"
                        class="w-16 h-16 rounded-xl overflow-hidden border-2 border-gray-200 hover:border-green-500 transition">
                    <img src="{{ asset('storage/'.$img) }}" class="w-full h-full object-cover">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="flex flex-col gap-5">
            <div>
                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">{{ $product->category->name ?? '' }}</p>
                <h1 class="text-3xl font-extrabold text-gray-800 mt-1 leading-tight">{{ $product->name }}</h1>
                @if($product->description)
                    <p class="text-gray-500 text-sm mt-3 leading-relaxed">{{ $product->description }}</p>
                @endif
            </div>

            {{-- Price --}}
            <div class="bg-green-50 border border-green-200 rounded-2xl p-5">
                <div class="flex items-baseline gap-3">
                    <span class="text-4xl font-extrabold text-gray-800">₹{{ number_format($product->price, 0) }}</span>
                    @if($product->mrp && $product->mrp > $product->price)
                        <span class="text-lg text-gray-400 line-through">₹{{ number_format($product->mrp, 0) }}</span>
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded">
                            -{{ round((($product->mrp - $product->price) / $product->mrp) * 100) }}%
                        </span>
                    @endif
                </div>
                <p class="text-xs text-gray-500 mt-2">Per {{ $product->unit }}</p>
            </div>

            {{-- Variants --}}
            @if($product->variants && count($product->variants))
            <div>
                <p class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Select Pack Size</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($product->variants as $idx => $variant)
                    <button onclick="selectVariant({{ $idx }}, {{ $variant['selling_price'] ?? $product->price }})"
                            id="variant-btn-{{ $idx }}"
                            class="px-4 py-2 rounded-xl border-2 text-sm font-bold transition
                                   {{ $idx === 0 ? 'border-green-600 bg-green-50 text-green-700' : 'border-gray-200 text-gray-600 hover:border-green-400' }}">
                        {{ $variant['quantity'] ?? '' }} {{ $variant['unit'] ?? '' }}
                        <span class="block text-xs font-normal">₹{{ $variant['selling_price'] ?? '' }}</span>
                    </button>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Stock --}}
            <div class="flex gap-3">
                <span class="flex items-center gap-2 px-3 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg text-xs font-bold">
                    <i class="fa-solid fa-circle-check"></i>
                    {{ $product->stock > 0 ? 'In Stock ('.$product->stock.')' : 'Out of Stock' }}
                </span>
                <span class="flex items-center gap-2 px-3 py-2 bg-blue-50 text-blue-700 border border-blue-200 rounded-lg text-xs font-bold">
                    <i class="fa-solid fa-truck"></i> Same Day Delivery
                </span>
            </div>

            {{-- Add to Cart --}}
            <div class="flex gap-3">
                <div class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden">
                    <button onclick="changeQty(-1)" class="w-11 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-100 font-bold text-lg">−</button>
                    <span id="qty-display" class="w-12 h-12 flex items-center justify-center font-bold text-lg border-x border-gray-200">1</span>
                    <button onclick="changeQty(1)" class="w-11 h-12 flex items-center justify-center text-gray-600 hover:bg-gray-100 font-bold text-lg">+</button>
                </div>
                <button onclick="addToCartWithQty({{ $product->id }})"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl text-sm transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                </button>
                <a href="{{ route('frontend.checkout') }}"
                   class="flex-1 bg-blue-700 hover:bg-blue-800 text-white font-bold rounded-xl text-sm transition flex items-center justify-center gap-2">
                    Buy Now
                </a>
            </div>
        </div>
    </div>

    {{-- Similar Products --}}
    @if($similar->count())
    <div>
        <h2 class="text-xl font-extrabold text-gray-800 mb-5">Similar <span class="text-green-600">Products</span></h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach($similar as $p)
            <a href="{{ route('frontend.product.show', $p->slug) }}"
               class="bg-white rounded-2xl border hover:border-green-300 hover:shadow-md transition overflow-hidden block">
                <div class="aspect-square bg-gray-50 overflow-hidden">
                    @if($p->images && count($p->images))
                        <img src="{{ asset('storage/'.$p->images[0]) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl">🥩</div>
                    @endif
                </div>
                <div class="p-3">
                    <p class="text-xs font-bold text-gray-800 line-clamp-2">{{ $p->name }}</p>
                    <p class="text-sm font-extrabold text-gray-800 mt-1">₹{{ number_format($p->price, 0) }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
let currentQty = 1;
let selectedVariant = null;

function changeQty(delta) {
    currentQty = Math.max(1, currentQty + delta);
    document.getElementById('qty-display').textContent = currentQty;
}

function selectVariant(idx, price) {
    selectedVariant = idx;
    document.querySelectorAll('[id^="variant-btn-"]').forEach(btn => {
        btn.className = btn.className.replace('border-green-600 bg-green-50 text-green-700', 'border-gray-200 text-gray-600');
    });
    const btn = document.getElementById('variant-btn-' + idx);
    btn.className = btn.className.replace('border-gray-200 text-gray-600', 'border-green-600 bg-green-50 text-green-700');
}

function addToCartWithQty(productId) {
    fetch('{{ route("frontend.cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity: currentQty, variant_index: selectedVariant })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            document.getElementById('header-cart-badge').textContent = data.cart_count;
            openCart();
        }
    });
}
</script>
@endsection

@extends('frontend.layouts.app')
@section('title', 'FarmSea — Fresh Meat & Seafood')

@section('content')

{{-- Hero Slider --}}
<section class="relative w-full h-[480px] overflow-hidden bg-gray-900">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1608835291093-394b0c943a75?auto=format&fit=crop&w=1600&q=80"
             class="w-full h-full object-cover opacity-70">
    </div>
    <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent"></div>
    <div class="relative z-10 h-full flex flex-col justify-center px-8 md:px-20 max-w-xl">
        <span class="inline-block bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded mb-4 uppercase tracking-widest">🐔 Farm Fresh Daily</span>
        <h1 class="text-4xl md:text-5xl font-extrabold text-white leading-tight mb-4">
            Premium Fresh<br><span class="text-green-400">Meat & Seafood</span>
        </h1>
        <p class="text-white/75 text-sm mb-6 max-w-sm">Sourced directly from our farms. Cleaned, cut & delivered to your doorstep.</p>
        <div class="flex gap-3">
            <a href="{{ route('frontend.products') }}"
               class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold text-sm rounded-lg transition">Shop Now</a>
            <a href="{{ route('frontend.categories') }}"
               class="px-6 py-3 bg-white/15 hover:bg-white/25 text-white font-bold text-sm rounded-lg border border-white/30 transition">View Categories</a>
        </div>
    </div>
</section>

{{-- Trust Bar --}}
<div class="bg-white border-b border-gray-100 py-4">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([['fa-leaf','100% Natural','No hormones or chemicals'],['fa-snowflake','Cold Chain Delivery','Fresh at every step'],['fa-bolt','Same Day Delivery','Order before 10 AM'],['fa-shield-halved','FSSAI Certified','Quality you can trust']] as [$icon,$title,$sub])
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-green-600 flex items-center justify-center text-white text-sm flex-shrink-0">
                <i class="fa-solid {{ $icon }}"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-800">{{ $title }}</p>
                <p class="text-[10px] text-gray-500">{{ $sub }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Shop by Category --}}
<section class="py-12 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-end justify-between mb-6">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800">Shop by <span class="text-blue-600">Category</span></h2>
                <p class="text-xs text-gray-500 mt-1">Farm-to-table freshness across every cut and kind.</p>
            </div>
            <a href="{{ route('frontend.categories') }}" class="text-blue-600 font-bold text-xs uppercase tracking-wider hover:underline">See All →</a>
        </div>
        <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide">
            @forelse($categories as $cat)
            <a href="{{ route('frontend.products', ['category' => $cat->slug]) }}"
               class="min-w-[140px] rounded-2xl overflow-hidden bg-white border border-gray-100 hover:border-green-400 hover:shadow-lg transition flex-shrink-0 block">
                <div class="aspect-square bg-gray-50 overflow-hidden">
                    @if($cat->image)
                        <img src="{{ asset('storage/'.$cat->image) }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl">🥩</div>
                    @endif
                </div>
                <div class="p-3 text-center border-t border-gray-50">
                    <p class="text-xs font-bold text-gray-800">{{ $cat->name }}</p>
                    <p class="text-[10px] text-green-600 font-bold mt-1 uppercase tracking-wider">{{ $cat->products_count }} items</p>
                </div>
            </a>
            @empty
            <p class="text-gray-400 text-sm">No categories yet.</p>
            @endforelse
        </div>
    </div>
</section>

{{-- New Arrivals --}}
<section class="py-12 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-end justify-between mb-6">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800">New <span class="text-blue-600">Arrivals</span></h2>
                <p class="text-xs text-gray-500 mt-1">The freshest additions to our selection.</p>
            </div>
            <a href="{{ route('frontend.products') }}" class="text-blue-600 font-bold text-xs uppercase tracking-wider hover:underline">See All →</a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
            @forelse($newArrivals as $product)
            <div class="bg-white rounded-2xl border border-gray-100 hover:border-green-300 hover:shadow-lg transition overflow-hidden flex flex-col">
                <a href="{{ route('frontend.product.show', $product->slug) }}" class="block aspect-square overflow-hidden bg-gray-50">
                    @if($product->images && count($product->images))
                        <img src="{{ asset('storage/'.$product->images[0]) }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-5xl">🥩</div>
                    @endif
                </a>
                <div class="p-3 flex flex-col flex-1">
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">{{ $product->unit }}</p>
                    <a href="{{ route('frontend.product.show', $product->slug) }}"
                       class="text-sm font-bold text-gray-800 hover:text-green-700 mt-1 leading-snug line-clamp-2">{{ $product->name }}</a>
                    <div class="flex items-center justify-between mt-auto pt-3">
                        <div>
                            <p class="text-lg font-extrabold text-gray-800">₹{{ number_format($product->price, 0) }}</p>
                            @if($product->mrp && $product->mrp > $product->price)
                                <p class="text-xs text-gray-400 line-through">₹{{ number_format($product->mrp, 0) }}</p>
                            @endif
                        </div>
                        <button onclick="addToCart({{ $product->id }})"
                                class="w-9 h-9 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center text-sm transition">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <p class="col-span-4 text-center text-gray-400 py-8">No products yet.</p>
            @endforelse
        </div>
    </div>
</section>

@endsection

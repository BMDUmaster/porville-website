@extends('frontend.layouts.app')
@section('title', 'My Wishlist')

@section('content')
<section class="bg-slate-900 py-10 text-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 text-xs font-bold uppercase tracking-widest text-emerald-400">
            <i class="fa-solid fa-heart text-red-500"></i> Saved Items
        </div>
        <h1 class="mt-2 text-3xl font-black tracking-tight text-white md:text-4xl">My Wishlist</h1>
        <p class="mt-2 text-sm text-slate-400">Keep track of your favorite cuts & fresh seafood to order whenever you're ready.</p>
    </div>
</section>

<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    <div id="wishlist-grid-container">
        @if($products->count() > 0)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($products as $product)
                    @php
                        $cardVariantIndex = collect($product->variants ?? [])->search(
                            fn ($variant) => filled($variant['selling_price'] ?? null) || filled($variant['today_price'] ?? null)
                        );
                        $cardVariantIndex = $cardVariantIndex === false ? null : $cardVariantIndex;
                    @endphp
                    <div id="wishlist-item-{{ $product->id }}" class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-emerald-300 hover:shadow-lg">
                        
                        {{-- Wishlist Remove Heart Button --}}
                        <button onclick="toggleWishlist({{ $product->id }}, this)" 
                                class="wishlist-btn absolute right-3 top-3 z-20 flex h-9 w-9 items-center justify-center rounded-full bg-white/90 text-red-500 shadow-md backdrop-blur-sm transition hover:scale-110"
                                data-product-id="{{ $product->id }}"
                                title="Remove from Wishlist">
                            <i class="fa-solid fa-heart text-lg text-red-500"></i>
                        </button>

                        <a href="{{ route('frontend.product.show', $product->slug) }}" class="relative block aspect-square overflow-hidden bg-slate-50">
                            @if($product->images && count($product->images))
                                <img src="{{ asset('storage/'.$product->images[0]) }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-4xl text-slate-300">
                                    <i class="fa-solid fa-drumstick-bite"></i>
                                </div>
                            @endif
                            @if($product->mrp && $product->mrp > $product->price)
                                <span class="absolute left-3 top-3 rounded-full bg-red-500 px-2.5 py-0.5 text-[10px] font-black uppercase text-white shadow">
                                    -{{ round((($product->mrp - $product->price) / $product->mrp) * 100) }}%
                                </span>
                            @endif
                        </a>

                        <div class="flex flex-1 flex-col p-4">
                            <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">{{ $product->category->name ?? 'Fresh Cut' }}</span>
                            <a href="{{ route('frontend.product.show', $product->slug) }}" class="mt-1 line-clamp-2 min-h-[40px] text-sm font-black text-slate-800 transition hover:text-emerald-700">
                                {{ $product->name }}
                            </a>

                            <div class="mt-auto pt-4">
                                <div class="flex items-end justify-between gap-2">
                                    <div>
                                        <p class="text-lg font-black text-slate-900">
                                            Rs{{ number_format($product->display_price, 0) }}
                                            <span class="text-xs font-semibold text-slate-400">{{ $product->display_pack_label }}</span>
                                        </p>
                                        @if($product->display_mrp && $product->display_mrp > $product->display_price)
                                            <p class="text-xs text-slate-400 line-through">Rs{{ number_format($product->display_mrp, 0) }}</p>
                                        @endif
                                    </div>

                                    @if($product->is_active)
                                        <button onclick="addToCart({{ $product->id }}, {{ $cardVariantIndex === null ? 'null' : $cardVariantIndex }}, 'today')"
                                                class="flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-2 text-xs font-black uppercase text-white shadow transition hover:bg-emerald-700">
                                            <i class="fa-solid fa-cart-shopping"></i>
                                            <span>Add</span>
                                        </button>
                                    @else
                                        <span class="rounded-lg bg-red-50 px-3 py-1.5 text-[10px] font-black uppercase text-red-600">
                                            Out of Stock
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="flex h-20 w-20 items-center justify-center rounded-full bg-red-50 text-red-500 mb-4">
                    <i class="fa-regular fa-heart text-4xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-800">Your Wishlist is Empty</h3>
                <p class="mt-2 max-w-sm text-sm font-medium text-slate-500">You haven't saved any items yet. Browse our marketplace and tap the heart icon on any product to save it here.</p>
                <a href="{{ route('frontend.products') }}" class="mt-6 inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-6 py-3 text-xs font-black uppercase tracking-wider text-white shadow-lg transition hover:bg-emerald-700">
                    <i class="fa-solid fa-bag-shopping"></i> Explore Products
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

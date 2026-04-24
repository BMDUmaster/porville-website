@extends('frontend.layouts.app')
@section('title', 'All Products')

@section('content')
{{-- Hero --}}
<div class="bg-green-700 py-10 px-6 text-white">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-extrabold">All <span class="text-green-300">Products</span></h1>
        <p class="text-white/70 text-sm mt-2">Sourced fresh from our farms and coastal waters.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8">

    {{-- Sidebar Filters --}}
    <aside class="w-full lg:w-64 flex-shrink-0">
        <form method="GET" action="{{ route('frontend.products') }}" id="filterForm">
            <div class="bg-white rounded-2xl border p-5 space-y-5">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-sm text-gray-800">Filter Products</h3>
                    <a href="{{ route('frontend.products') }}" class="text-xs text-blue-600 font-semibold hover:underline">Clear All</a>
                </div>

                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-green-500">
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Category</h4>
                    @foreach($categories as $cat)
                    <label class="flex items-center gap-2 py-1.5 cursor-pointer">
                        <input type="radio" name="category" value="{{ $cat->slug }}"
                               {{ request('category') == $cat->slug ? 'checked' : '' }}
                               class="accent-green-600" onchange="document.getElementById('filterForm').submit()">
                        <span class="text-sm text-gray-600">{{ $cat->name }}</span>
                    </label>
                    @endforeach
                </div>

                <div>
                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Sort By</h4>
                    <select name="sort" onchange="document.getElementById('filterForm').submit()"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none">
                        <option value="latest" {{ request('sort','latest') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-lg text-sm transition">
                    Apply Filters
                </button>
            </div>
        </form>
    </aside>

    {{-- Products Grid --}}
    <div class="flex-1">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-500 font-semibold">{{ $products->total() }} products found</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-5">
            @forelse($products as $product)
            <div class="bg-white rounded-2xl border border-gray-100 hover:border-green-300 hover:shadow-lg transition overflow-hidden flex flex-col">
                <a href="{{ route('frontend.product.show', $product->slug) }}" class="block aspect-square overflow-hidden bg-gray-50 relative">
                    @if($product->images && count($product->images))
                        <img src="{{ asset('storage/'.$product->images[0]) }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-5xl">M</div>
                    @endif
                    @if($product->mrp && $product->mrp > $product->price)
                        <span class="absolute top-2 right-2 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded">
                            -{{ round((($product->mrp - $product->price) / $product->mrp) * 100) }}%
                        </span>
                    @endif
                </a>
                <div class="p-3 flex flex-col flex-1">
                    <a href="{{ route('frontend.product.show', $product->slug) }}"
                       class="text-sm font-bold text-gray-800 hover:text-green-700 leading-snug line-clamp-2">{{ $product->name }}</a>
                    <p class="text-[10px] text-gray-400 mt-1">{{ $product->category->name ?? '' }}</p>
                    <div class="flex items-center justify-between mt-auto pt-3">
                        <div>
                            <p class="text-lg font-extrabold text-gray-800">Rs{{ number_format($product->price, 0) }}<span class="ml-1 text-xs font-semibold text-gray-400">/{{ $product->unit ?: 'unit' }}</span></p>
                            @if($product->mrp && $product->mrp > $product->price)
                                <p class="text-xs text-gray-400 line-through">Rs{{ number_format($product->mrp, 0) }}</p>
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
            <div class="col-span-4 text-center py-16 text-gray-400">
                <i class="fa-solid fa-box-open text-5xl mb-4 block"></i>
                <p class="font-semibold">No products found.</p>
                <a href="{{ route('frontend.products') }}" class="text-blue-600 text-sm mt-2 inline-block hover:underline">Clear filters</a>
            </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $products->links() }}</div>
    </div>
</div>
@endsection

@extends('frontend.layouts.app')
@section('title', 'All Products')

@section('content')
@php
    $shopHeroProduct = $products->first(function ($product) {
        $categoryName = strtolower($product->category->name ?? '');
        $hasImage = !empty($product->images[0] ?? null);

        return $hasImage && in_array($categoryName, ['chicken', 'mutton', 'fish', 'seafood', 'eggs']);
    }) ?? $products->first(function ($product) {
        return !empty($product->images[0] ?? null);
    });

    $shopHeroFallbackImage = $shopHeroProduct && !empty($shopHeroProduct->images[0] ?? null)
        ? asset('storage/' . $shopHeroProduct->images[0])
        : 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1800&q=80';

    $shopHeroImage = !empty($sharedHeroBanner?->image_url)
        ? $sharedHeroBanner->image_url
        : $shopHeroFallbackImage;
@endphp

<section class="pt-0">
    <div class="relative overflow-hidden bg-[#151819] text-white shadow-[0_24px_70px_rgba(15,23,42,0.22)]">
        <img
            src="{{ $shopHeroImage }}"
            alt="All products hero banner"
            class="absolute inset-0 h-full w-full object-cover"
        >
        <div class="absolute inset-0 bg-black/55"></div>
        <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(0,0,0,0.74)_0%,rgba(0,0,0,0.52)_34%,rgba(0,0,0,0.18)_58%,rgba(0,0,0,0.62)_100%)]"></div>

        <div class="relative z-10 mx-auto flex min-h-[230px] max-w-7xl flex-col justify-between gap-4 px-4 py-5 sm:min-h-[270px] sm:px-5 sm:py-7 md:min-h-[340px] md:gap-8 md:px-8 md:py-10 lg:flex-row lg:items-center lg:px-10">
            <div class="max-w-[620px]">
                <div class="flex items-center gap-2 text-[9px] font-extrabold uppercase tracking-[0.22em] text-white/80 sm:text-[11px] sm:tracking-[0.28em]">
                    <span class="inline-block h-2 w-2 rounded-full bg-[#9ae16d] shadow-[0_0_16px_rgba(154,225,109,0.7)]"></span>
                    {{ $sharedHeroBanner?->badge ?: 'FarmSea Fresh Marketplace' }}
                </div>

                <h1 class="mt-2.5 text-[27px] font-black leading-[0.98] tracking-[-0.04em] text-white sm:mt-4 sm:text-[42px] md:text-[64px] lg:text-[74px]">
                    {{ $sharedHeroBanner?->title_1 ?: 'All' }}
                    <span class="block italic text-[#9be278]">{{ $sharedHeroBanner?->title_2 ?: 'Products.' }}</span>
                </h1>

                <p class="mt-2.5 max-w-[520px] text-[11px] leading-5 text-white/82 sm:mt-5 sm:text-[16px] sm:leading-8 md:text-[17px]">
                    {{ $sharedHeroBanner?->description ?: 'Sourced fresh from our farms and coastal waters. Browse our complete range of chicken, mutton, fish, seafood and more delivered chilled to your door.' }}
                </p>

                @if($sharedHeroBanner)
                    <a href="{{ $sharedHeroBanner->link_url ?: route('frontend.products') }}"
                       class="mt-3 inline-flex rounded-lg bg-green-600 px-4 py-2 text-[10px] font-black uppercase tracking-[0.14em] text-white shadow-lg transition hover:bg-green-700 sm:mt-6 sm:rounded-xl sm:px-7 sm:py-3 sm:text-[13px] sm:tracking-[0.16em]">
                        {{ $sharedHeroBanner->button_text ?: 'Shop Now' }}
                    </a>
                @endif
            </div>

            <div class="flex w-full flex-row gap-5 border-t border-white/15 pt-3 text-white sm:w-auto sm:flex-wrap sm:items-stretch sm:gap-5 sm:border-0 sm:pt-0 lg:justify-end">
                <div class="min-w-0 flex-1 sm:w-auto sm:min-w-[120px] sm:flex-none sm:border-l sm:pl-5">
                    <div class="text-[24px] font-black leading-none sm:text-[46px] md:text-[56px]">{{ $products->total() }}+</div>
                    <div class="mt-1.5 text-[8px] font-extrabold uppercase tracking-[0.2em] text-white/65 sm:mt-2 sm:text-[11px] sm:tracking-[0.28em]">Fresh Products</div>
                </div>
                <div class="min-w-0 flex-1 border-l border-white/15 pl-5 sm:w-auto sm:min-w-[150px] sm:flex-none">
                    <div class="text-[24px] font-black leading-none sm:text-[46px] md:text-[56px]">Daily</div>
                    <div class="mt-1.5 text-[8px] font-extrabold uppercase tracking-[0.2em] text-white/65 sm:mt-2 sm:text-[11px] sm:tracking-[0.28em]">Fresh Sourcing</div>
                </div>
            </div>
        </div>
    </div>

</section>

@php
    $selectedMinPrice = max((int) request('min_price', 0), 0);
    $selectedMaxPrice = (int) request('max_price', $sidebarMaxPrice);
    $selectedMaxPrice = max(min($selectedMaxPrice, $sidebarMaxPrice), $selectedMinPrice ?: 0);
    $sortOptions = [
        'latest' => 'Latest',
        'price_asc' => 'Price: Low to High',
        'price_desc' => 'Price: High to Low',
        'name' => 'Name A-Z',
    ];
    $packWeights = ['250g', '500g', '1 kg', '1.5 kg'];
    $isFlashDealActive = request()->boolean('flash_deal') || request()->filled('flash_deal') || request('offer') === 'flash_deal';
    $flashDealToggleUrl = $isFlashDealActive
        ? route('frontend.products', request()->except(['flash_deal', 'offer', 'page']))
        : route('frontend.products', array_merge(request()->except('page'), ['flash_deal' => '1']));
@endphp

<div id="mobileFilterModal" class="fixed inset-0 z-[10000] hidden bg-black/50 p-4 backdrop-blur-sm lg:hidden">
    <div class="ml-auto flex h-full w-full max-w-[380px] flex-col overflow-hidden rounded-[28px] bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <h3 class="text-[13px] font-black uppercase tracking-[0.22em] text-slate-900">Filter Products</h3>
            <button type="button" onclick="closeMobileFilter()" class="flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form method="GET" action="{{ route('frontend.products') }}" id="mobileFilterForm" class="flex-1 overflow-y-auto">
            <div class="space-y-6 px-5 py-5">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cuts, packs, combos..."
                           data-filter-search
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-green-500 focus:bg-white">
                </div>

                <div class="border-b border-slate-100 pb-5">
                    <div class="mb-3 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Category</div>
                    <div class="space-y-1.5">
                        @foreach($categories as $cat)
                            <label class="flex cursor-pointer items-center justify-between rounded-2xl px-3 py-2.5 transition hover:bg-slate-50">
                                <span class="flex items-center gap-3">
                                    <input type="radio" name="category" value="{{ $cat->slug }}"
                                           {{ request('category') == $cat->slug ? 'checked' : '' }}
                                           data-auto-submit
                                           class="h-4 w-4 border-slate-300 text-green-600 focus:ring-green-500">
                                    <span class="text-[13px] font-semibold text-slate-700">{{ $cat->name }}</span>
                                </span>
                                <span class="text-[11px] font-bold text-slate-400">{{ $cat->active_products_count }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="border-b border-slate-100 pb-5">
                    <div class="mb-3 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Sort By</div>
                    <div class="space-y-1.5">
                        @foreach($sortOptions as $value => $label)
                            <label class="flex cursor-pointer items-center gap-3 rounded-2xl px-3 py-2.5 transition hover:bg-slate-50">
                                <input type="radio" name="sort" value="{{ $value }}"
                                       {{ request('sort', 'latest') === $value ? 'checked' : '' }}
                                       data-auto-submit
                                       class="h-4 w-4 border-slate-300 text-green-600 focus:ring-green-500">
                                <span class="text-[13px] font-semibold text-slate-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="border-b border-slate-100 pb-5">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Price Range</div>
                        <span class="rounded-full bg-green-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.16em] text-green-700">Rs {{ $selectedMinPrice }} - <span id="mobilePriceRangeValue">{{ $selectedMaxPrice }}</span></span>
                    </div>
                    <input type="range" name="max_price" min="0" max="{{ $sidebarMaxPrice }}" value="{{ $selectedMaxPrice }}" step="10"
                           id="mobileSidebarPriceRange"
                           class="h-2 w-full cursor-pointer appearance-none rounded-full bg-slate-200 accent-green-600">
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Min</label>
                            <input type="number" name="min_price" min="0" max="{{ $sidebarMaxPrice }}" value="{{ $selectedMinPrice }}"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 outline-none focus:border-green-500 focus:bg-white">
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Max</label>
                            <input type="number" min="0" max="{{ $sidebarMaxPrice }}" value="{{ $selectedMaxPrice }}"
                                   id="mobileSidebarPriceRangeInput"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 outline-none focus:border-green-500 focus:bg-white">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="mb-3 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Pack Weight</div>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($packWeights as $weight)
                            <button type="button" class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-[12px] font-bold text-slate-600 transition hover:border-green-200 hover:bg-green-50 hover:text-green-700">
                                {{ $weight }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 border-t border-slate-200 bg-white p-4">
                <button type="submit" class="w-full rounded-2xl bg-[#1f9d47] px-5 py-3 text-[13px] font-black uppercase tracking-[0.18em] text-white shadow-[0_16px_30px_rgba(31,157,71,0.22)]">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8">

    {{-- Sidebar Filters --}}
    <aside class="hidden w-full flex-shrink-0 lg:block lg:w-[290px] xl:w-[310px]">
        <div class="space-y-5 lg:sticky lg:top-[150px]">
            <form method="GET" action="{{ route('frontend.products') }}" id="filterForm">
                <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_22px_55px_rgba(15,23,42,0.08)]">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <h3 class="text-[13px] font-black uppercase tracking-[0.22em] text-slate-900">Filter Products</h3>
                        <a href="{{ route('frontend.products') }}" class="text-[11px] font-bold text-[#2d72d3] transition hover:text-[#1f5bb4]">Clear All</a>
                    </div>

                    <div class="space-y-6 px-5 py-5">
                        <div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cuts, packs, combos..."
                                   data-filter-search
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-green-500 focus:bg-white">
                        </div>

                        <div class="border-b border-slate-100 pb-5">
                            <div class="mb-3 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Category</div>
                            <div class="space-y-1.5">
                                @foreach($categories as $cat)
                                    <label class="flex cursor-pointer items-center justify-between rounded-2xl px-3 py-2.5 transition hover:bg-slate-50">
                                        <span class="flex items-center gap-3">
                                            <input type="radio" name="category" value="{{ $cat->slug }}"
                                                   {{ request('category') == $cat->slug ? 'checked' : '' }}
                                                   data-auto-submit
                                                   class="h-4 w-4 border-slate-300 text-green-600 focus:ring-green-500">
                                            <span class="text-[13px] font-semibold text-slate-700">{{ $cat->name }}</span>
                                        </span>
                                        <span class="text-[11px] font-bold text-slate-400">{{ $cat->active_products_count }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-b border-slate-100 pb-5">
                            <div class="mb-3 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Sort By</div>
                            <div class="space-y-1.5">
                                @foreach($sortOptions as $value => $label)
                                    <label class="flex cursor-pointer items-center gap-3 rounded-2xl px-3 py-2.5 transition hover:bg-slate-50">
                                        <input type="radio" name="sort" value="{{ $value }}"
                                               {{ request('sort', 'latest') === $value ? 'checked' : '' }}
                                               data-auto-submit
                                               class="h-4 w-4 border-slate-300 text-green-600 focus:ring-green-500">
                                        <span class="text-[13px] font-semibold text-slate-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-b border-slate-100 pb-5">
                            <div class="mb-4 flex items-center justify-between">
                                <div class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Price Range</div>
                                <span class="rounded-full bg-green-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.16em] text-green-700">Rs {{ $selectedMinPrice }} - <span id="priceRangeValue">{{ $selectedMaxPrice }}</span></span>
                            </div>
                            <input type="range" name="max_price" min="0" max="{{ $sidebarMaxPrice }}" value="{{ $selectedMaxPrice }}" step="10"
                                   id="sidebarPriceRange"
                                   class="h-2 w-full cursor-pointer appearance-none rounded-full bg-slate-200 accent-green-600">
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Min</label>
                                    <input type="number" name="min_price" min="0" max="{{ $sidebarMaxPrice }}" value="{{ $selectedMinPrice }}"
                                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 outline-none focus:border-green-500 focus:bg-white">
                                </div>
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Max</label>
                                    <input type="number" min="0" max="{{ $sidebarMaxPrice }}" value="{{ $selectedMaxPrice }}"
                                           id="sidebarPriceRangeInput"
                                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 outline-none focus:border-green-500 focus:bg-white">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-3 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Pack Weight</div>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($packWeights as $weight)
                                    <button type="button" class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-[12px] font-bold text-slate-600 transition hover:border-green-200 hover:bg-green-50 hover:text-green-700">
                                        {{ $weight }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <button type="submit" class="w-full rounded-2xl bg-[#1f9d47] px-5 py-3 text-[13px] font-black uppercase tracking-[0.18em] text-white shadow-[0_16px_30px_rgba(31,157,71,0.22)] transition hover:-translate-y-0.5 hover:bg-[#18823a]">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>

            <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white p-4 shadow-[0_20px_45px_rgba(15,23,42,0.06)]">
                <div class="mb-4 flex items-center justify-between">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Best Deals</h4>
                    <span class="rounded-full bg-red-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.16em] text-red-500">Hot</span>
                </div>
                <div class="space-y-3">
                    @forelse($sidebarBestDeals as $deal)
                        <a href="{{ route('frontend.product.show', $deal->slug) }}" class="group flex items-center gap-3 rounded-[22px] border border-slate-100 bg-slate-50 p-2.5 transition hover:-translate-y-0.5 hover:border-green-200 hover:bg-white hover:shadow-md">
                            <div class="h-16 w-16 overflow-hidden rounded-2xl bg-slate-200">
                                @if($deal->images && count($deal->images))
                                    <img src="{{ asset('storage/' . $deal->images[0]) }}" alt="{{ $deal->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-xl text-slate-400">
                                        <i class="fa-solid fa-drumstick-bite"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="line-clamp-2 text-[12px] font-black leading-5 text-slate-900">{{ $deal->name }}</p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-[12px] font-black text-green-700">₹{{ number_format($deal->price, 0) }}</span>
                                    @if($deal->mrp && $deal->mrp > $deal->price)
                                        <span class="text-[11px] font-bold text-slate-400 line-through">₹{{ number_format($deal->mrp, 0) }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="rounded-2xl bg-slate-50 px-4 py-4 text-[13px] font-semibold text-slate-500">Deals will appear here once products with offers are available.</p>
                    @endforelse
                </div>
            </div>

            <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white p-4 shadow-[0_20px_45px_rgba(15,23,42,0.06)]">
                <h4 class="mb-4 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">New Arrivals</h4>
                <div class="space-y-3">
                    @foreach($sidebarNewArrivals as $arrival)
                        <a href="{{ route('frontend.product.show', $arrival->slug) }}" class="group flex items-center gap-3 rounded-[20px] px-1 py-1 transition hover:bg-slate-50">
                            <div class="h-12 w-12 overflow-hidden rounded-xl bg-slate-200">
                                @if($arrival->images && count($arrival->images))
                                    <img src="{{ asset('storage/' . $arrival->images[0]) }}" alt="{{ $arrival->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-sm text-slate-400">
                                        <i class="fa-solid fa-fish"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="line-clamp-2 text-[12px] font-black leading-5 text-slate-900">{{ $arrival->name }}</p>
                                <p class="mt-1 text-[11px] font-semibold text-slate-400">{{ $arrival->category->name ?? 'Fresh cut' }}</p>
                            </div>
                            <span class="text-[12px] font-black text-green-700">₹{{ number_format($arrival->price, 0) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="overflow-hidden rounded-[28px] bg-[linear-gradient(160deg,#2f8c43_0%,#1f6f34_100%)] p-5 text-white shadow-[0_20px_45px_rgba(31,111,52,0.22)]">
                <span class="inline-flex rounded-full bg-white/16 px-3 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-white/85">Need Bulk Orders?</span>
                <p class="mt-4 text-[16px] font-black leading-7">Talk to our farm experts for the best cuts & delivery slots.</p>
                <a href="{{ route('frontend.contact') }}" class="mt-5 inline-flex items-center justify-center rounded-2xl bg-white px-4 py-3 text-[12px] font-black uppercase tracking-[0.18em] text-[#1f6f34] transition hover:-translate-y-0.5">
                    Contact Now
                </a>
            </div>
            <a href="{{ $flashDealToggleUrl }}"
               id="flashDealCard"
               class="group relative block overflow-hidden rounded-[28px] bg-[#18213a] p-5 text-white shadow-[0_18px_40px_rgba(15,23,42,0.18)] transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:border-amber-400/50 border-2 cursor-pointer {{ $isFlashDealActive ? 'border-amber-400 ring-4 ring-amber-400/30 bg-[#141b30]' : 'border-transparent' }}">
                
                @if($isFlashDealActive)
                    <div class="mb-3 flex items-center justify-between rounded-xl bg-amber-400/20 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-amber-300">
                        <span class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-amber-400 animate-ping"></span>
                            ⚡ Filter Active
                        </span>
                        <span class="hover:underline text-[11px]">Clear ✕</span>
                    </div>
                @endif

                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="inline-flex rounded-full {{ $isFlashDealActive ? 'bg-amber-400 text-slate-950 font-black' : 'bg-white/8 text-[#90a5d9]' }} px-3 py-1 text-[10px] font-black uppercase tracking-[0.18em]">
                            Flash Deal
                        </span>
                        <div class="mt-4 text-[42px] font-black leading-none text-white flex items-baseline gap-1">
                            20%
                            <span class="text-sm font-bold text-amber-300">OFF</span>
                        </div>
                        <p class="mt-2 text-[11px] font-black uppercase tracking-[0.18em] text-[#8ea0c7]">Product Discount Today</p>
                    </div>
                    <i class="fa-solid fa-bolt text-[34px] transition-transform duration-300 group-hover:scale-125 {{ $isFlashDealActive ? 'text-amber-400 animate-bounce' : 'text-[#39466d] group-hover:text-amber-400' }}"></i>
                </div>
                
                <div class="mt-5 flex items-center justify-between border-t border-white/10 pt-3">
                    <p class="text-[12px] font-bold uppercase tracking-[0.22em] text-[#7d8fbf]">FRESH30</p>
                    <span class="text-[11px] font-bold text-amber-300 group-hover:translate-x-1 transition-transform flex items-center gap-1">
                        {{ $isFlashDealActive ? 'Show All Products' : 'View Offer Products →' }}
                    </span>
                </div>
            </a>
        </div>
    </aside>

    {{-- Products Grid --}}
    <div class="flex-1">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <p class="text-sm text-gray-500 font-semibold">{{ $products->total() }} products found</p>
                @if($isFlashDealActive)
                    <a href="{{ route('frontend.products', request()->except(['flash_deal', 'offer', 'page'])) }}" 
                       class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-900 border border-amber-300 hover:bg-amber-200 transition shadow-sm">
                        <i class="fa-solid fa-bolt text-amber-600"></i>
                        <span>20% Off Offers (FRESH30)</span>
                        <span class="ml-1 text-amber-700 font-extrabold">✕</span>
                    </a>
                @endif
            </div>
            <button type="button" onclick="openMobileFilter()" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-xs font-black uppercase tracking-[0.14em] text-white lg:hidden">
                <i class="fa-solid fa-sliders"></i>
                Filter
            </button>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($products as $product)
            @php
                $cardVariantIndex = collect($product->variants ?? [])->search(
                    fn ($variant) => filled($variant['selling_price'] ?? null) || filled($variant['today_price'] ?? null)
                );
                $cardVariantIndex = $cardVariantIndex === false ? null : $cardVariantIndex;
                $cardVariant = $cardVariantIndex !== null ? ($product->variants[$cardVariantIndex] ?? null) : null;
                $cardTomorrowPrice = $cardVariant && filled($cardVariant['tomorrow_price'] ?? null)
                    && (float) $cardVariant['tomorrow_price'] > 0
                        ? (float) $cardVariant['tomorrow_price']
                        : null;
            @endphp
            @php
                $isInWishlist = in_array($product->id, session('wishlist', []), true);
            @endphp
            <div class="relative bg-white rounded-2xl border border-gray-100 hover:border-green-300 hover:shadow-lg transition overflow-hidden flex flex-col group">
                <button onclick="toggleWishlist({{ $product->id }}, this)" 
                        class="wishlist-btn absolute right-2.5 top-2.5 z-20 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 shadow-md backdrop-blur-sm transition hover:scale-110 {{ $isInWishlist ? 'active' : '' }}"
                        data-product-id="{{ $product->id }}"
                        title="{{ $isInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist' }}">
                    <i class="{{ $isInWishlist ? 'fa-solid fa-heart text-base text-red-500' : 'fa-regular fa-heart text-base text-slate-500 hover:text-red-500' }}"></i>
                </button>
                <a href="{{ route('frontend.product.show', $product->slug) }}" class="block aspect-square overflow-hidden bg-gray-50 relative">
                    @if(in_array($product->id, $newArrivalProductIds ?? [], true))
                        <span class="absolute left-2 top-2 z-10 rounded-full bg-blue-600 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.12em] text-white shadow">
                            New Arrival
                        </span>
                    @endif
                    @if($product->images && count($product->images))
                        <img src="{{ asset('storage/'.$product->images[0]) }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-5xl">M</div>
                    @endif
                    @unless($product->is_active)
                        <span class="absolute left-2 top-2 rounded bg-red-600 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.12em] text-white shadow">
                            Out of Stock
                        </span>
                    @endunless
                    @if($product->mrp && $product->mrp > $product->price)
                        <span class="absolute bottom-2 right-2 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded shadow">
                            -{{ round((($product->mrp - $product->price) / $product->mrp) * 100) }}%
                        </span>
                    @endif
                </a>
                <div class="p-3 flex flex-col flex-1">
                    <a href="{{ route('frontend.product.show', $product->slug) }}"
                       class="block min-h-[40px] text-sm font-bold text-gray-800 hover:text-green-700 leading-snug line-clamp-2">{{ $product->name }}</a>
                    <p class="text-[10px] text-gray-400 mt-1">{{ $product->category->name ?? '' }}</p>
                    <div class="mt-auto pt-1.5">
                        <div class="flex items-end justify-between gap-2">
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-wider text-emerald-600">Today</p>
                                <p class="text-lg font-extrabold text-gray-800">Rs{{ number_format($product->display_price, 0) }}<span class="ml-1 text-xs font-semibold text-gray-400">{{ $product->display_pack_label }}</span></p>
                                @if($product->display_mrp && $product->display_mrp > $product->display_price)
                                    <p class="text-xs text-gray-400 line-through">Rs{{ number_format($product->display_mrp, 0) }}</p>
                                @endif
                            </div>
                            @if($product->is_active)
                                <button onclick="addToCart({{ $product->id }}, {{ $cardVariantIndex === null ? 'null' : $cardVariantIndex }}, 'today')"
                                        class="flex h-9 items-center justify-center rounded-lg bg-blue-600 px-3 text-[10px] font-extrabold uppercase tracking-[0.1em] text-white transition hover:bg-blue-700 sm:w-9 sm:px-0 sm:text-sm">
                                    <span class="sm:hidden">Add to Cart</span>
                                    <i class="fa-solid fa-cart-shopping hidden sm:inline" aria-hidden="true"></i>
                                </button>
                            @else
                                <span class="inline-flex rounded-lg bg-red-50 px-3 py-2 text-[10px] font-bold uppercase tracking-[0.12em] text-red-600">
                                    Sold Out
                                </span>
                            @endif
                        </div>
                        @if($product->is_active && $cardTomorrowPrice !== null)
                            <div class="mt-1 flex items-end justify-between gap-2 border-t border-dashed border-gray-100 pt-1">
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-wider text-amber-700">Tomorrow</p>
                                    <p class="text-lg font-extrabold text-gray-800">Rs{{ number_format($cardTomorrowPrice, 0) }}<span class="ml-1 text-xs font-semibold text-gray-400">{{ $product->display_pack_label }}</span></p>
                                </div>
                                <button onclick="addToCart({{ $product->id }}, {{ $cardVariantIndex }}, 'tomorrow')"
                                        class="flex h-9 items-center justify-center rounded-lg bg-amber-500 px-3 text-[10px] font-extrabold uppercase tracking-[0.1em] text-white transition hover:bg-amber-600 sm:w-9 sm:px-0 sm:text-sm"
                                        title="Add for tomorrow">
                                    <span class="sm:hidden">Add to Cart</span>
                                    <i class="fa-solid fa-cart-shopping hidden sm:inline" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-4 text-center py-16 text-gray-400">
                @if($isFlashDealActive)
                    <i class="fa-solid fa-bolt-slash text-5xl mb-4 text-amber-400 block"></i>
                    <p class="font-semibold text-slate-700">No products with 20% Flash Deal offers found at the moment.</p>
                    <a href="{{ route('frontend.products', request()->except(['flash_deal', 'offer', 'page'])) }}" class="text-blue-600 font-bold text-sm mt-3 inline-block hover:underline">Show All Products</a>
                @else
                    <i class="fa-solid fa-box-open text-5xl mb-4 block"></i>
                    <p class="font-semibold">No products found.</p>
                    <a href="{{ route('frontend.products') }}" class="text-blue-600 text-sm mt-2 inline-block hover:underline">Clear filters</a>
                @endif
            </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $products->links() }}</div>

        <div class="mt-8 space-y-5 lg:hidden">
            <a href="{{ $flashDealToggleUrl }}"
               class="group relative block overflow-hidden rounded-[28px] bg-[#18213a] p-5 text-white shadow-[0_18px_40px_rgba(15,23,42,0.18)] transition-all duration-300 border-2 cursor-pointer {{ $isFlashDealActive ? 'border-amber-400 ring-4 ring-amber-400/30 bg-[#141b30]' : 'border-transparent' }}">
                @if($isFlashDealActive)
                    <div class="mb-3 flex items-center justify-between rounded-xl bg-amber-400/20 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-amber-300">
                        <span class="flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-amber-400 animate-ping"></span>
                            ⚡ Filter Active
                        </span>
                        <span class="hover:underline text-[11px]">Clear ✕</span>
                    </div>
                @endif
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="inline-flex rounded-full {{ $isFlashDealActive ? 'bg-amber-400 text-slate-950 font-black' : 'bg-white/8 text-[#90a5d9]' }} px-3 py-1 text-[10px] font-black uppercase tracking-[0.18em]">
                            Flash Deal
                        </span>
                        <div class="mt-4 text-[38px] font-black leading-none text-white flex items-baseline gap-1">
                            20%
                            <span class="text-sm font-bold text-amber-300">OFF</span>
                        </div>
                        <p class="mt-2 text-[11px] font-black uppercase tracking-[0.18em] text-[#8ea0c7]">Product Discount Today</p>
                    </div>
                    <i class="fa-solid fa-bolt text-[34px] {{ $isFlashDealActive ? 'text-amber-400 animate-bounce' : 'text-[#39466d]' }}"></i>
                </div>
                <div class="mt-5 flex items-center justify-between border-t border-white/10 pt-3">
                    <p class="text-[12px] font-bold uppercase tracking-[0.22em] text-[#7d8fbf]">FRESH30</p>
                    <span class="text-[11px] font-bold text-amber-300 flex items-center gap-1">
                        {{ $isFlashDealActive ? 'Show All Products' : 'View Offer Products →' }}
                    </span>
                </div>
            </a>

            <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white p-4 shadow-[0_20px_45px_rgba(15,23,42,0.06)]">
                <div class="mb-4 flex items-center justify-between">
                    <h4 class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Best Deals</h4>
                    <span class="rounded-full bg-red-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.16em] text-red-500">Hot</span>
                </div>
                <div class="space-y-3">
                    @forelse($sidebarBestDeals as $deal)
                        <a href="{{ route('frontend.product.show', $deal->slug) }}" class="group flex items-center gap-3 rounded-[22px] border border-slate-100 bg-slate-50 p-2.5 transition hover:-translate-y-0.5 hover:border-green-200 hover:bg-white hover:shadow-md">
                            <div class="h-16 w-16 overflow-hidden rounded-2xl bg-slate-200">
                                @if($deal->images && count($deal->images))
                                    <img src="{{ asset('storage/' . $deal->images[0]) }}" alt="{{ $deal->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-xl text-slate-400">
                                        <i class="fa-solid fa-drumstick-bite"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="line-clamp-2 text-[12px] font-black leading-5 text-slate-900">{{ $deal->name }}</p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-[12px] font-black text-green-700">Rs{{ number_format($deal->display_price, 0) }}</span>
                                    @if($deal->display_mrp && $deal->display_mrp > $deal->display_price)
                                        <span class="text-[11px] font-bold text-slate-400 line-through">Rs{{ number_format($deal->display_mrp, 0) }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="rounded-2xl bg-slate-50 px-4 py-4 text-[13px] font-semibold text-slate-500">Deals will appear here once products with offers are available.</p>
                    @endforelse
                </div>
            </div>

            <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white p-4 shadow-[0_20px_45px_rgba(15,23,42,0.06)]">
                <h4 class="mb-4 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">New Arrivals</h4>
                <div class="space-y-3">
                    @foreach($sidebarNewArrivals as $arrival)
                        <a href="{{ route('frontend.product.show', $arrival->slug) }}" class="group flex items-center gap-3 rounded-[20px] px-1 py-1 transition hover:bg-slate-50">
                            <div class="h-12 w-12 overflow-hidden rounded-xl bg-slate-200">
                                @if($arrival->images && count($arrival->images))
                                    <img src="{{ asset('storage/' . $arrival->images[0]) }}" alt="{{ $arrival->name }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-sm text-slate-400">
                                        <i class="fa-solid fa-fish"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="line-clamp-2 text-[12px] font-black leading-5 text-slate-900">{{ $arrival->name }}</p>
                                <p class="mt-1 text-[11px] font-semibold text-slate-400">{{ $arrival->category->name ?? 'Fresh cut' }}</p>
                            </div>
                            <span class="text-[12px] font-black text-green-700">Rs{{ number_format($arrival->display_price, 0) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<section class="px-4 pb-12 md:px-6 md:pb-16">
    <div class="mx-auto max-w-7xl">
        <div class="overflow-hidden rounded-[34px] border border-[#e6eddc] bg-[radial-gradient(circle_at_top_left,_rgba(211,241,198,0.35),_rgba(255,255,255,0.98)_42%)] px-6 py-8 shadow-[0_24px_60px_rgba(15,23,42,0.08)] md:px-9 md:py-10 lg:px-10 lg:py-12">
            <div class="flex flex-col gap-10 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-[700px]">
                    <span class="inline-flex rounded-full bg-[#d8efc9] px-4 py-2 text-[12px] font-black uppercase tracking-[0.22em] text-[#2f8c43]">
                        Bulk Orders Welcome
                    </span>
                    <h2 class="mt-6 max-w-[820px] text-[38px] font-black leading-[1.02] tracking-[-0.04em] text-[#152016] md:text-[52px] lg:text-[58px]">
                        Can't find the right <span class="text-[#2d72d3]">cut or quantity?</span>
                    </h2>
                    <p class="mt-6 max-w-[520px] text-[18px] leading-9 text-[#59685f]">
                        Our team can source custom cuts, bulk packs and special orders directly from our farms. Perfect for restaurants, caterers and large families.
                    </p>
                </div>

                <div class="flex flex-col gap-4 sm:flex-row lg:justify-end">
                    <a href="{{ route('frontend.contact') }}"
                       class="inline-flex items-center justify-center gap-3 rounded-[18px] bg-[#2f8c43] px-8 py-4 text-[14px] font-black uppercase tracking-[0.18em] text-white shadow-[0_16px_30px_rgba(47,140,67,0.25)] transition hover:-translate-y-0.5 hover:bg-[#247437]">
                        <i class="fa-solid fa-comment-dots text-sm"></i>
                        Talk To Us
                    </a>
                    <a href="{{ route('frontend.contact') }}"
                       class="inline-flex items-center justify-center rounded-[18px] border-2 border-[#243625] bg-white px-8 py-4 text-[14px] font-black uppercase tracking-[0.18em] text-[#152016] transition hover:-translate-y-0.5 hover:border-[#2f8c43] hover:text-[#2f8c43]">
                        Request A Quote
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
(() => {
    function setupFilterForm(formId, rangeId, rangeInputId, rangeValueId) {
        const filterForm = document.getElementById(formId);
        const range = document.getElementById(rangeId);
        const rangeInput = document.getElementById(rangeInputId);
        const rangeValue = document.getElementById(rangeValueId);
        const autoSubmitInputs = document.querySelectorAll(`#${formId} [data-auto-submit]`);
        const searchInput = document.querySelector(`#${formId} [data-filter-search]`);

        if (!filterForm) {
            return;
        }

        autoSubmitInputs.forEach((input) => {
            input.addEventListener('change', () => filterForm.submit());
        });

        if (searchInput) {
            let searchTimer;

            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    filterForm.submit();
                }, 450);
            });
        }

        if (!range || !rangeInput || !rangeValue) return;

        const syncFromRange = () => {
            rangeInput.value = range.value;
            rangeValue.textContent = range.value;
        };

        const syncFromInput = () => {
            const max = Number(range.max || 0);
            const nextValue = Math.min(Math.max(Number(rangeInput.value || 0), 0), max);
            rangeInput.value = nextValue;
            range.value = nextValue;
            rangeValue.textContent = nextValue;
        };

        range.addEventListener('input', syncFromRange);
        rangeInput.addEventListener('input', syncFromInput);
    }

    setupFilterForm('filterForm', 'sidebarPriceRange', 'sidebarPriceRangeInput', 'priceRangeValue');
    setupFilterForm('mobileFilterForm', 'mobileSidebarPriceRange', 'mobileSidebarPriceRangeInput', 'mobilePriceRangeValue');
})();

function openMobileFilter() {
    const modal = document.getElementById('mobileFilterModal');
    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function closeMobileFilter() {
    const modal = document.getElementById('mobileFilterModal');
    if (!modal) return;

    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

document.getElementById('mobileFilterModal')?.addEventListener('click', (event) => {
    if (event.target.id === 'mobileFilterModal') {
        closeMobileFilter();
    }
});
</script>
@endsection

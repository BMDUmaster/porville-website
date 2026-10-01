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
    <div class="relative h-[350px] overflow-hidden bg-[#151819] text-white shadow-[0_24px_70px_rgba(15,23,42,0.22)]">
        <img
            src="{{ $shopHeroImage }}"
            alt="All products hero banner"
            class="block h-full w-full object-cover"
        >
        <div class="hidden absolute inset-0 bg-black/55"></div>
        <div class="hidden absolute inset-0 bg-[linear-gradient(90deg,rgba(0,0,0,0.74)_0%,rgba(0,0,0,0.52)_34%,rgba(0,0,0,0.18)_58%,rgba(0,0,0,0.62)_100%)]"></div>

        <div class="hidden relative z-10 mx-auto min-h-[230px] max-w-7xl flex-col justify-between gap-4 px-4 py-5 sm:min-h-[270px] sm:px-5 sm:py-7 md:min-h-[340px] md:gap-8 md:px-8 md:py-10 lg:flex-row lg:items-center lg:px-10">
            <div class="max-w-[620px]">
                <div class="flex items-center gap-2 text-[9px] font-extrabold uppercase tracking-[0.22em] text-white/80 sm:text-[11px] sm:tracking-[0.28em]">
                    <span class="inline-block h-2 w-2 rounded-full bg-[#e0b84d] shadow-[0_0_16px_rgba(154,225,109,0.7)]"></span>
                    {{ $sharedHeroBanner?->badge ?: 'Porville Fresh Marketplace' }}
                </div>

                <h1 class="mt-2.5 text-[27px] font-black leading-[0.98] tracking-[-0.04em] text-white sm:mt-4 sm:text-[42px] md:text-[64px] lg:text-[74px]">
                    {{ $sharedHeroBanner?->title_1 ?: 'All' }}
                    <span class="block italic text-[#e0b84d]">{{ $sharedHeroBanner?->title_2 ?: 'Products.' }}</span>
                </h1>

                <p class="mt-2.5 max-w-[520px] text-[11px] leading-5 text-white/82 sm:mt-5 sm:text-[16px] sm:leading-8 md:text-[17px]">
                    {{ $sharedHeroBanner?->description ?: 'Sourced fresh from our farms and coastal waters. Browse our complete range of chicken, mutton, fish, seafood and more delivered chilled to your door.' }}
                </p>

                @if($sharedHeroBanner)
                    <a href="{{ $sharedHeroBanner->link_url ?: route('frontend.products') }}"
                       class="mt-3 inline-flex rounded-lg bg-amber-600 px-4 py-2 text-[10px] font-black uppercase tracking-[0.14em] text-white shadow-lg transition hover:bg-amber-700 sm:mt-6 sm:rounded-xl sm:px-7 sm:py-3 sm:text-[13px] sm:tracking-[0.16em]">
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

@if($activeCategory && $activeCategory->is_enquiry_only)
<section class="bg-black py-10 md:py-14">
    <div class="mx-auto flex max-w-6xl flex-col items-center gap-5 px-4 text-center md:flex-row md:items-center md:gap-8 md:text-left">
        <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-full border-2 border-amber-400 bg-neutral-900 shadow-[0_10px_30px_rgba(184,134,44,0.3)] md:h-32 md:w-32">
            @if($activeCategory->image)
                <img src="{{ asset('storage/'.$activeCategory->image) }}" alt="{{ $activeCategory->name }}" class="h-full w-full object-cover">
            @else
                <div class="flex h-full w-full items-center justify-center text-4xl text-amber-400">
                    <i class="fa-solid fa-kiwi-bird"></i>
                </div>
            @endif
        </div>
        <div>
            <p class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-amber-400">Enquiry Only &middot; Call to Order</p>
            <h1 class="font-classic mt-2 text-3xl font-bold text-white md:text-4xl">{{ $activeCategory->name }}</h1>
            <p class="mt-3 max-w-xl text-sm leading-6 text-stone-300">
                {{ $activeCategory->description ?: 'Healthy live farm birds and livestock raised under premium guidelines.' }}
            </p>
        </div>
    </div>
</section>
@endif

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
    $hasActiveProductQuery = ! empty(request()->query());
    $flashDealToggleUrl = $isFlashDealActive
        ? \App\Support\ShopUrl::to(request()->except(['flash_deal', 'offer', 'page']))
        : \App\Support\ShopUrl::to(array_merge(request()->except('page'), ['flash_deal' => '1']));
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
            @if(request()->filled('subcategory'))
                <input type="hidden" name="subcategory" value="{{ request('subcategory') }}" data-subcategory-filter>
            @endif
            <div class="space-y-6 px-5 py-5">
                <div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cuts, packs, combos..."
                           data-filter-search
                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:bg-white">
                </div>

                <div class="border-b border-slate-100 pb-5">
                    <div class="mb-3 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Category</div>
                    <div class="space-y-1.5">
                        @foreach($categories as $cat)
                            <label class="flex cursor-pointer items-center justify-between rounded-2xl px-3 py-2.5 transition hover:bg-slate-50">
                                <span class="flex items-center gap-3">
                                    <input type="radio" name="category" value="{{ $cat->slug }}"
                                           {{ request('category') == $cat->slug ? 'checked' : '' }}
                                           data-auto-submit data-category-filter
                                           class="h-4 w-4 border-slate-300 text-amber-600 focus:ring-amber-500">
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
                                       class="h-4 w-4 border-slate-300 text-amber-600 focus:ring-amber-500">
                                <span class="text-[13px] font-semibold text-slate-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="border-b border-slate-100 pb-5">
                    <div class="mb-4 flex items-center justify-between">
                        <div class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Price Range</div>
                        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.16em] text-amber-700">Rs {{ $selectedMinPrice }} - <span id="mobilePriceRangeValue">{{ $selectedMaxPrice }}</span></span>
                    </div>
                    <input type="range" name="max_price" min="0" max="{{ $sidebarMaxPrice }}" value="{{ $selectedMaxPrice }}" step="10"
                           id="mobileSidebarPriceRange"
                           class="h-2 w-full cursor-pointer appearance-none rounded-full bg-slate-200 accent-amber-600">
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Min</label>
                            <input type="number" name="min_price" min="0" max="{{ $sidebarMaxPrice }}" value="{{ $selectedMinPrice }}"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 outline-none focus:border-amber-500 focus:bg-white">
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Max</label>
                            <input type="number" min="0" max="{{ $sidebarMaxPrice }}" value="{{ $selectedMaxPrice }}"
                                   id="mobileSidebarPriceRangeInput"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 outline-none focus:border-amber-500 focus:bg-white">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="mb-3 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Pack Weight</div>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($packWeights as $weight)
                            <button type="button" class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-[12px] font-bold text-slate-600 transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-700">
                                {{ $weight }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="sticky bottom-0 border-t border-slate-200 bg-white p-4">
                <button type="submit" class="w-full rounded-2xl bg-[#a3761f] px-5 py-3 text-[13px] font-black uppercase tracking-[0.18em] text-white shadow-[0_16px_30px_rgba(31,157,71,0.22)]">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>
</div>

<div id="product-results" class="max-w-7xl mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8" style="scroll-margin-top: 150px;">

    {{-- Sidebar Filters --}}
    <aside class="hidden w-full flex-shrink-0 lg:block lg:w-[290px] xl:w-[310px]">
        <div class="space-y-5 lg:sticky lg:top-[150px]">
            <form method="GET" action="{{ route('frontend.products') }}" id="filterForm">
                @if(request()->filled('subcategory'))
                    <input type="hidden" name="subcategory" value="{{ request('subcategory') }}" data-subcategory-filter>
                @endif
                <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_22px_55px_rgba(15,23,42,0.08)]">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <h3 class="text-[13px] font-black uppercase tracking-[0.22em] text-slate-900">Filter Products</h3>
                        <a href="{{ route('frontend.products') }}" class="text-[11px] font-bold text-[#262626] transition hover:text-[#1a1a1a]">Clear All</a>
                    </div>

                    <div class="space-y-6 px-5 py-5">
                        <div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search cuts, packs, combos..."
                                   data-filter-search
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-amber-500 focus:bg-white">
                        </div>

                        <div class="border-b border-slate-100 pb-5">
                            <div class="mb-3 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Category</div>
                            <div class="space-y-1.5">
                                @foreach($categories as $cat)
                                    <label class="flex cursor-pointer items-center justify-between rounded-2xl px-3 py-2.5 transition hover:bg-slate-50">
                                        <span class="flex items-center gap-3">
                                            <input type="radio" name="category" value="{{ $cat->slug }}"
                                                   {{ request('category') == $cat->slug ? 'checked' : '' }}
                                                   data-auto-submit data-category-filter
                                                   class="h-4 w-4 border-slate-300 text-amber-600 focus:ring-amber-500">
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
                                               class="h-4 w-4 border-slate-300 text-amber-600 focus:ring-amber-500">
                                        <span class="text-[13px] font-semibold text-slate-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="border-b border-slate-100 pb-5">
                            <div class="mb-4 flex items-center justify-between">
                                <div class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Price Range</div>
                                <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.16em] text-amber-700">Rs {{ $selectedMinPrice }} - <span id="priceRangeValue">{{ $selectedMaxPrice }}</span></span>
                            </div>
                            <input type="range" name="max_price" min="0" max="{{ $sidebarMaxPrice }}" value="{{ $selectedMaxPrice }}" step="10"
                                   id="sidebarPriceRange"
                                   class="h-2 w-full cursor-pointer appearance-none rounded-full bg-slate-200 accent-amber-600">
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Min</label>
                                    <input type="number" name="min_price" min="0" max="{{ $sidebarMaxPrice }}" value="{{ $selectedMinPrice }}"
                                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 outline-none focus:border-amber-500 focus:bg-white">
                                </div>
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Max</label>
                                    <input type="number" min="0" max="{{ $sidebarMaxPrice }}" value="{{ $selectedMaxPrice }}"
                                           id="sidebarPriceRangeInput"
                                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 outline-none focus:border-amber-500 focus:bg-white">
                                </div>
                            </div>
                        </div>

                        <!-- <div>
                            <div class="mb-3 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Pack Weight</div>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($packWeights as $weight)
                                    <button type="button" class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-[12px] font-bold text-slate-600 transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-700">
                                        {{ $weight }}
                                    </button>
                                @endforeach
                            </div>
                        </div> -->

                        <button type="submit" class="w-full rounded-2xl bg-[#a3761f] px-5 py-3 text-[13px] font-black uppercase tracking-[0.18em] text-white shadow-[0_16px_30px_rgba(31,157,71,0.22)] transition hover:-translate-y-0.5 hover:bg-[#8f6a1c]">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </aside>

    {{-- Products Grid --}}
    <div class="flex-1">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <p class="text-sm text-gray-500 font-semibold">{{ $products->total() }} products found</p>
                @if($isFlashDealActive)
                    <a href="{{ \App\Support\ShopUrl::to(request()->except(['flash_deal', 'offer', 'page'])) }}" 
                       class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-900 border border-amber-300 hover:bg-amber-200 transition shadow-sm">
                        <i class="fa-solid fa-bolt text-amber-600"></i>
                        <span>20% Off Offers (FRESH30)</span>
                        <span class="ml-1 text-amber-700 font-extrabold">✕</span>
                    </a>
                @endif
            </div>
            <button type="button" onclick="openMobileFilter()" class="inline-flex items-center gap-2 rounded-xl bg-neutral-900 px-4 py-2 text-xs font-black uppercase tracking-[0.14em] text-white lg:hidden">
                <i class="fa-solid fa-sliders"></i>
                Filter
            </button>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @forelse($products as $product)
                @include('frontend.partials.product-card', ['product' => $product])
            @empty
            <div class="col-span-4 text-center py-16 text-gray-400">
                @if($isFlashDealActive)
                    <i class="fa-solid fa-bolt-slash text-5xl mb-4 text-amber-400 block"></i>
                    <p class="font-semibold text-slate-700">No products with 20% Flash Deal offers found at the moment.</p>
                    <a href="{{ \App\Support\ShopUrl::to(request()->except(['flash_deal', 'offer', 'page'])) }}" class="text-amber-600 font-bold text-sm mt-3 inline-block hover:underline">Show All Products</a>
                @else
                    <i class="fa-solid fa-box-open text-5xl mb-4 block"></i>
                    <p class="font-semibold">No products found.</p>
                    <a href="{{ route('frontend.products') }}" class="text-amber-600 text-sm mt-2 inline-block hover:underline">Clear filters</a>
                @endif
            </div>
            @endforelse
        </div>

        <div class="mt-8">{{ $products->links() }}</div>

    </div>
</div>

<section class="px-4 pb-12 md:px-6 md:pb-16">
    <div class="mx-auto max-w-7xl">
        <div class="overflow-hidden rounded-[34px] border border-[#f5eed9] bg-[radial-gradient(circle_at_top_left,_rgba(211,241,198,0.35),_rgba(255,255,255,0.98)_42%)] px-6 py-8 shadow-[0_24px_60px_rgba(15,23,42,0.08)] md:px-9 md:py-10 lg:px-10 lg:py-12">
            <div class="flex flex-col gap-10 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-[700px]">
                    <span class="inline-flex rounded-full bg-[#eddfb0] px-4 py-2 text-[12px] font-black uppercase tracking-[0.22em] text-[#b8862c]">
                        Bulk Orders Welcome
                    </span>
                    <h2 class="mt-6 max-w-[820px] text-[38px] font-black leading-[1.02] tracking-[-0.04em] text-[#4a3b1a] md:text-[52px] lg:text-[58px]">
                        Can't find the right <span class="text-[#262626]">cut or quantity?</span>
                    </h2>
                    <p class="mt-6 max-w-[520px] text-[18px] leading-9 text-[#6b5d47]">
                        Our team can source custom cuts, bulk packs and special orders directly from our farms. Perfect for restaurants, caterers and large families.
                    </p>
                </div>

                <div class="flex flex-col gap-4 sm:flex-row lg:justify-end">
                    <a href="{{ route('frontend.contact') }}"
                       class="inline-flex items-center justify-center gap-3 rounded-[18px] bg-[#b8862c] px-8 py-4 text-[14px] font-black uppercase tracking-[0.18em] text-white shadow-[0_16px_30px_rgba(184,134,44,0.25)] transition hover:-translate-y-0.5 hover:bg-[#8f6a1c]">
                        <i class="fa-solid fa-comment-dots text-sm"></i>
                        Talk To Us
                    </a>
                    <a href="{{ route('frontend.contact') }}"
                       class="inline-flex items-center justify-center rounded-[18px] border-2 border-[#4a3b1a] bg-white px-8 py-4 text-[14px] font-black uppercase tracking-[0.18em] text-[#4a3b1a] transition hover:-translate-y-0.5 hover:border-[#b8862c] hover:text-[#b8862c]">
                        Request A Quote
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@include('frontend.partials.category-seo-content')
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
            input.addEventListener('change', () => {
                // A category change starts a new category listing; sorting and price
                // filters retain the currently selected subcategory.
                if (input.matches('[data-category-filter]')) {
                    filterForm.querySelector('[data-subcategory-filter]')?.remove();
                }

                filterForm.submit();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('keydown', (event) => {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    filterForm.submit();
                }
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

    const hasActiveProductQuery = @json($hasActiveProductQuery);
    if (hasActiveProductQuery) {
        window.addEventListener('load', () => {
            document.getElementById('product-results')?.scrollIntoView({ block: 'start' });
        }, { once: true });
    }
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

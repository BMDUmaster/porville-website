@extends('frontend.layouts.app')
@section('title', 'Porville — Fresh Cut Pure Standards')
@section('styles')
<style>
    .home-hero-slide {
        opacity: 0;
        transform: scale(1.02);
        transition: opacity .7s ease, transform 5s ease;
        pointer-events: none;
    }

    .home-hero-slide.active {
        opacity: 1;
        transform: scale(1);
        pointer-events: auto;
    }

    .home-hero-card {
        height: 350px;
    }

    .home-hero-card:hover .home-hero-slide.active img {
        transform: scale(1.06);
    }

    .home-hero-slide img {
        transition: transform 1.2s ease;
    }

    .home-hero-content {
        opacity: 0;
        transform: translateY(18px);
        transition: opacity .55s ease, transform .55s ease;
    }

    .home-hero-slide.active .home-hero-content {
        opacity: 1;
        transform: translateY(0);
    }

    .home-hero-dot {
        transition: all .25s ease;
    }

    .home-hero-dot.active {
        width: 28px;
        background: #fff;
    }

    .favorite-card {
        position: relative;
        isolation: isolate;
        transition: transform .3s ease, box-shadow .3s ease;
    }

    .favorite-card::after {
        content: "";
        position: absolute;
        inset: 1px;
        border-radius: 26px;
        background: #fff;
        z-index: -1;
    }

    .favorite-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 26px 48px rgba(15, 23, 42, 0.12);
    }

    .favorite-hero-banner {
        position: relative;
        overflow: hidden;
        isolation: isolate;
    }

    .favorite-hero-banner img {
        transition: transform .7s ease;
    }

    .favorite-hero-banner::before {
        content: "";
        position: absolute;
        inset: -2px;
        border-radius: 32px;
        background: conic-gradient(from 0deg, rgba(239, 68, 68, 0) 0deg, rgba(239, 68, 68, 0) 220deg, rgba(248, 113, 113, 0.95) 272deg, rgba(220, 38, 38, 0.95) 320deg, rgba(239, 68, 68, 0) 360deg);
        opacity: 0;
        z-index: -2;
    }

    .favorite-hero-banner::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(10, 10, 10, 0.85) 0%, rgba(10, 10, 10, 0.5) 34%, rgba(10, 10, 10, 0.1) 72%, rgba(10, 10, 10, 0.02) 100%);
    }

    .favorite-hero-banner:hover img {
        transform: scale(1.06);
    }

    .favorite-hero-banner:hover::before {
        opacity: 1;
        animation: favoriteGlowSpin 2.4s linear infinite;
    }

    .favorite-hero-banner:hover .favorite-hero-copy {
        transform: translateY(-4px);
    }

    .favorite-hero-copy {
        transition: transform .35s ease;
    }

    @keyframes favoriteGlowSpin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    @media (max-width: 640px) {
        main section h2 { font-size: 20px !important; line-height: 1.15; }
        .home-category-heading { white-space: nowrap; }
        .home-hero-content { align-items: flex-start; padding: 22px 16px 34px; }
        .home-hero-content > div { padding: 0; }
        .home-hero-content h1 { font-size: 23px; line-height: 1.08; }
        .home-hero-content p { margin-top: 10px; max-width: 290px; font-size: 11px; line-height: 1.55; }
        .home-hero-content span { margin-bottom: 10px; padding: 5px 10px; font-size: 8px; }
        .home-hero-content > div > div { margin-top: 14px; }
        .home-hero-content a { padding: 9px 14px; border-radius: 9px; font-size: 10px; }
        #home-category-scroller > a { min-width: 156px; max-width: 156px; }
        #home-category-scroller > a > div:first-child { height: 150px; }
        #home-favorites-scroller > article { min-width: 205px; max-width: 205px; padding: 8px; }
        #home-favorites-scroller > article > div { padding: 10px; }
        #home-favorites-scroller img { height: 145px; aspect-ratio: auto; }
        .favorite-hero-banner { border-radius: 20px; }
        .favorite-hero-banner img { height: 250px; }
        .favorite-hero-copy { padding: 20px 16px; }
        .favorite-hero-copy h2 { font-size: 23px; }
        .favorite-hero-copy p { margin-top: 10px; font-size: 11px; line-height: 1.55; }
        .favorite-hero-copy .mt-6 { margin-top: 14px; }
    }
</style>
@endsection

@section('content')

{{-- Hero Slider --}}
@php
    $defaultHeroSlides = [
        [
            'image' => 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=1800&q=80',
            'badge' => "Delhi's Finest Butchers",
            'category' => 'Chicken',
            'title_1' => 'Premium',
            'title_2' => 'Chicken, Cut Fresh',
            'description' => 'Custom-cut to order, vacuum-sealed and delivered chilled within 2 hours. Never pre-packaged.',
            'button' => 'Shop Now',
            'link' => \App\Support\ShopUrl::to(['category' => 'Chicken']),
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1600891964092-4316c288032e?auto=format&fit=crop&w=1800&q=80',
            'badge' => 'Chef Special Cuts',
            'category' => 'Mutton',
            'title_1' => 'Signature',
            'title_2' => 'Mutton Cuts',
            'description' => 'Choose tender curry cuts, premium chops and slow-cook favourites packed for flavor and freshness.',
            'button' => 'Shop Now',
            'link' => \App\Support\ShopUrl::to(['category' => 'Mutton']),
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1518492104633-130d0cc84637?auto=format&fit=crop&w=1800&q=80',
            'badge' => 'Farm Fresh, Daily',
            'category' => 'Eggs',
            'title_1' => 'Pure Standards',
            'title_2' => 'Farm Fresh Eggs',
            'description' => 'Pasture-raised, farm-fresh eggs sourced daily. Frozen and non-frozen options available.',
            'button' => 'Shop Now',
            'link' => \App\Support\ShopUrl::to(['category' => 'Eggs']),
        ],
    ];

    $heroSlides = ($homeBanners ?? collect())->map(fn ($banner) => [
        'image' => $banner->image_url,
        'mobile_image' => $banner->mobile_image_url,
        'badge' => $banner->badge,
        'title_1' => $banner->title_1,
        'title_2' => $banner->title_2,
        'description' => $banner->description,
        'button' => $banner->button_text ?: 'Shop Now',
        'link' => $banner->link_url ?: route('frontend.products'),
    ])->values()->all();

    if (empty($heroSlides)) {
        $heroSlides = $defaultHeroSlides;
    }
@endphp

<section class="relative bg-[#0a0f1a] px-0 py-0">
    <div id="home-hero-carousel" class="home-hero-card relative overflow-hidden">
        @foreach($heroSlides as $index => $slide)
            <div class="home-hero-slide {{ $index === 0 ? 'active' : '' }} absolute inset-0" data-home-hero-slide="{{ $index }}">
                <picture>
                    @if(!empty($slide['mobile_image']))
                        <source media="(max-width: 767px)" srcset="{{ $slide['mobile_image'] }}">
                    @endif
                    <img
                        src="{{ $slide['image'] }}"
                        alt="{{ $slide['title_1'] }} {{ $slide['title_2'] }}"
                        class="block h-full w-full object-cover"
                    >
                </picture>
                <div class="hidden absolute inset-0 bg-black/45"></div>
                <div class="hidden absolute inset-0 bg-gradient-to-r from-black/70 via-black/35 to-black/10"></div>

                <div class="home-hero-content hidden absolute inset-0 z-10 h-full items-center px-7 md:px-14 lg:px-20">
                    <div class="max-w-[430px] p-5 md:p-7">
                        <span class="mb-4 inline-flex w-fit rounded-md bg-amber-600 px-4 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-white shadow-lg">
                            {{ $slide['badge'] }}
                        </span>
                        <h1 class="max-w-[390px] text-[24px] font-extrabold leading-[1.08] text-white md:text-[36px]">
                            {{ $slide['title_1'] }}
                            <span class="block text-[18px] text-amber-400 md:text-[22px]">{{ $slide['title_2'] }}</span>
                        </h1>
                        <p class="mt-4 max-w-[330px] text-[13px] leading-6 text-white/85 md:text-[15px]">
                            {{ $slide['description'] }}
                        </p>
                        <div class="mt-6">
                            <a href="{{ $slide['link'] }}" class="inline-flex rounded-xl bg-amber-600 px-6 py-3 text-sm font-extrabold uppercase tracking-[0.12em] text-white transition hover:-translate-y-0.5 hover:bg-amber-700 hover:shadow-xl">
                                {{ $slide['button'] }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <button
            type="button"
            aria-label="Previous slide"
            onclick="homeHeroMove(-1)"
            class="absolute left-4 top-1/2 z-20 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-black/25 text-white backdrop-blur transition hover:bg-white/20 md:flex"
        >
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <button
            type="button"
            aria-label="Next slide"
            onclick="homeHeroMove(1)"
            class="absolute right-4 top-1/2 z-20 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-black/25 text-white backdrop-blur transition hover:bg-white/20 md:flex"
        >
            <i class="fa-solid fa-arrow-right"></i>
        </button>

        <div class="absolute bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-2 md:bottom-7">
            @foreach($heroSlides as $index => $slide)
                <button
                    type="button"
                    aria-label="Go to slide {{ $index + 1 }}"
                    onclick="homeHeroGoTo({{ $index }})"
                    class="home-hero-dot {{ $index === 0 ? 'active' : '' }} h-2.5 w-2.5 rounded-full bg-white/45"
                    data-home-hero-dot="{{ $index }}"
                ></button>
            @endforeach
        </div>
    </div> 
</section>

{{-- Shop By Category --}}
<section class="bg-white pb-2 pt-5 md:py-6">
    <div class="mx-auto max-w-7xl px-4">
        @php
            $showcaseCategories = $categories->flatMap(function ($category) {
                if ($category->children->isNotEmpty()) {
                    return $category->children->map(function ($child) use ($category) {
                        return [
                            'title' => $child->name,
                            'parent_name' => $category->name,
                            'category_slug' => $category->slug,
                            'subcategory_slug' => $child->slug,
                            'image' => $child->image ?: $category->image,
                            'tag' => $child->tag ?: $category->tag,
                        ];
                    });
                }

                return collect([[
                    'title' => $category->name,
                    'parent_name' => $category->name,
                    'category_slug' => $category->slug,
                    'subcategory_slug' => null,
                    'image' => $category->image,
                    'tag' => $category->tag,
                ]]);
            })->take(12)->values();
        @endphp

        <div class="mb-4 flex items-center justify-between gap-3 md:mb-6 md:items-end md:gap-4">
            <div class="min-w-0 text-left">
                <h2 class="home-category-heading text-2xl font-extrabold text-slate-900 md:text-4xl">
                    Shop by <span class="text-amber-600"> Category  </span>
                </h2>
                <p class="mt-2 hidden text-sm text-slate-500 md:block"> Fresh cut, pure standards, across every cut and kind. </p>
            </div>
            <a
                href="{{ route('frontend.categories') }}"
                class="inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap text-[11px] font-extrabold uppercase tracking-[0.14em] text-amber-700 transition hover:text-amber-900 md:gap-2 md:rounded-full md:border md:border-amber-200 md:bg-amber-50 md:px-5 md:py-2.5 md:hover:border-amber-600 md:hover:bg-amber-600 md:hover:text-white"
            >
                See All
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div
            id="home-category-scroller"
            class="scrollbar-hide flex gap-4 overflow-x-auto scroll-smooth pb-3"
            data-mobile-autoscroll
        >
            @forelse($showcaseCategories as $index => $item)
                @php
                    $linkParams = ['category' => $item['category_slug']];
                    if ($item['subcategory_slug']) {
                        $linkParams['subcategory'] = $item['subcategory_slug'];
                    }
                @endphp

                <a
                    href="{{ \App\Support\ShopUrl::to($linkParams) }}"
                    class="group min-w-[228px] max-w-[228px] overflow-hidden rounded-[18px] border border-slate-200 bg-white shadow-[0_8px_24px_rgba(15,23,43,0.05)] transition-all duration-300 hover:-translate-y-1 hover:border-amber-200 hover:shadow-[0_18px_36px_rgba(217,164,65,0.18)]"
                >
                    <div class="relative h-[225px] overflow-hidden bg-slate-100">
                        @if($item['image'])
                            <img
                                src="{{ asset('storage/' . $item['image']) }}"
                                alt="{{ $item['title'] }}"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                            >
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-5xl text-slate-400">
                                <i class="fa-solid fa-drumstick-bite"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/12 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                    </div>

                    <div class="border-t border-slate-100 px-4 py-3 text-center">
                        @if($item['tag'])
                            <p class="hidden text-[10px] font-extrabold uppercase tracking-[0.18em] text-amber-600 md:block">
                                {{ $item['tag'] }}
                            </p>
                        @endif
                        <h3 class="mt-1 text-[15px] font-semibold text-slate-900 transition-colors group-hover:text-amber-700">
                            {{ $item['title'] }}
                        </h3>
                    </div>
                </a>
            @empty
                <div class="flex min-h-[220px] w-full items-center justify-center rounded-3xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center text-slate-400">
                    <div>
                        <i class="fa-solid fa-layer-group mb-3 block text-4xl opacity-40"></i>
                        <p class="text-sm font-medium">No categories available right now.</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</section>

{{-- Dynamic Category Sections --}}
@foreach($categories as $category)
    @continue($category->is_enquiry_only)
    @php $categoryProductList = $categoryProducts->get($category->id, collect()); @endphp
    @continue($categoryProductList->isEmpty())

    <section class="{{ $loop->even ? 'bg-gray-50' : 'bg-white' }} pb-2 pt-2 md:py-6">
        <div class="mx-auto max-w-7xl px-4">
            {{-- Name on the left, See All on the right; tag and description are desktop-only --}}
            <div class="mb-4 flex items-center justify-between gap-3 md:mb-6 md:items-end md:gap-4">
                <div class="min-w-0 text-left">
                    @if($category->tag)
                        <p class="hidden text-[11px] font-extrabold uppercase tracking-[0.28em] text-amber-600 md:block">{{ $category->tag }}</p>
                    @endif
                    <h2 class="text-2xl font-extrabold text-slate-900 md:mt-1 md:text-3xl">{{ $category->name }}</h2>
                    <p class="mt-1 hidden max-w-lg text-sm text-gray-500 md:block">
                        {{ $category->description ?: 'Fresh, quality-checked ' . strtolower($category->name) . ', cut and packed daily.' }}
                    </p>
                </div>
                <a href="{{ \App\Support\ShopUrl::to(['category' => $category->slug]) }}" class="inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap text-[11px] font-extrabold uppercase tracking-[0.14em] text-amber-700 transition hover:text-amber-900 md:gap-2 md:rounded-full md:border md:border-amber-200 md:bg-amber-50 md:px-5 md:py-2.5 md:hover:border-amber-600 md:hover:bg-amber-600 md:hover:text-white">
                    See All
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="scrollbar-hide flex gap-3 overflow-x-auto scroll-smooth pb-3 md:gap-5" data-mobile-autoscroll>
                @foreach($categoryProductList as $product)
                <div class="min-w-[240px] max-w-[240px]">
                    @include('frontend.partials.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endforeach

{{-- Best Sellers --}}
<section class="bg-gray-50 py-7 md:py-14">
    <div class="mx-auto max-w-7xl px-4">
        <div class="favorite-hero-banner mb-8 rounded-[30px] border border-amber-100 shadow-[0_24px_60px_rgba(15,23,42,0.10)]">
            <img
                src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1800&q=80"
                alt="Fresh cut meat platter"
                class="h-[280px] w-full object-cover md:h-[360px]"
            >
            <div class="favorite-hero-copy absolute inset-0 z-10 flex max-w-[400px] flex-col justify-center px-5 py-8 md:px-10">
                <span class="mb-5 inline-flex w-fit rounded-md bg-amber-600 px-4 py-1.5 text-xs font-extrabold uppercase tracking-[0.18em] text-white shadow-lg">
                    Premium Quality
                </span>
                <h2 class="text-[28px] font-extrabold leading-[1.04] text-white md:text-[48px]">
                    The Freshest
                    <span class="block text-amber-400">Cuts, Pure Standards</span>
                    Delivered Daily
                </h2>
                <p class="mt-4 max-w-[340px] text-[13px] leading-6 text-white/85 md:text-[15px]">
                    From our farm gates to your kitchen. Hand-cleaned, hygienically packed proteins every single day.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('frontend.products') }}" class="rounded-xl bg-amber-600 px-5 py-2.5 text-xs font-extrabold uppercase tracking-[0.14em] text-white transition hover:bg-amber-700">
                        Shop The Range
                    </a>
                    <a href="{{ route('frontend.categories') }}" class="rounded-xl border border-white/30 bg-white/10 px-5 py-2.5 text-xs font-extrabold uppercase tracking-[0.14em] text-white backdrop-blur transition hover:bg-white/20">
                        Learn More
                    </a>
                </div>
            </div>
        </div>

        <div class="mb-7 flex items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-slate-900 md:text-4xl">
                    Best <span class="text-slate-900">Sellers</span>
                </h2>
                <p class="mt-2 text-sm text-slate-500">Yeh wo products hain jo customers sabse zyada order karte hain.</p>
            </div>
            <div class="hidden items-center gap-3 md:flex">
                <button
                    type="button"
                    aria-label="Scroll favorites left"
                    onclick="document.getElementById('home-favorites-scroller').scrollBy({ left: -360, behavior: 'smooth' })"
                    class="flex h-12 w-12 items-center justify-center rounded-full border border-amber-100 bg-white text-slate-600 shadow-sm transition hover:border-amber-200 hover:text-amber-700"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <button
                    type="button"
                    aria-label="Scroll favorites right"
                    onclick="document.getElementById('home-favorites-scroller').scrollBy({ left: 360, behavior: 'smooth' })"
                    class="flex h-12 w-12 items-center justify-center rounded-full border border-amber-100 bg-white text-slate-600 shadow-sm transition hover:border-amber-200 hover:text-amber-700"
                >
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>

        @php
            $favoriteLabels = ['Most Ordered', 'Top Rated', 'Chef Pick', 'Best For Fry', 'Family Pack', 'Daily Fresh', 'Quick Cook', 'Popular Choice'];
        @endphp

        <div id="home-favorites-scroller" class="scrollbar-hide flex gap-4 overflow-x-auto scroll-smooth pb-3">
            @forelse($bestSellers as $index => $product)
                <div class="min-w-[240px] max-w-[240px]">
                    @include('frontend.partials.product-card', ['product' => $product])
                </div>
            @empty
                <div class="flex min-h-[250px] w-full items-center justify-center rounded-[28px] border border-dashed border-slate-200 bg-white px-6 py-12 text-center text-slate-400">
                    <div>
                        <i class="fa-solid fa-fire-flame-curved mb-3 block text-4xl opacity-40"></i>
                        <p class="text-sm font-medium">Best sellers yahan tab dikhेंगे jab products par orders aane lagenge.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- Approved customer reviews selected for the home page --}}
@if(($homeReviews ?? collect())->isNotEmpty())
<section class="bg-white py-8 md:py-14">
    <div class="mx-auto max-w-7xl px-4">
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <p class="mb-2 text-[11px] font-extrabold uppercase tracking-[0.3em] text-amber-600">Verified Reviews</p>
                <h2 class="text-3xl font-extrabold text-slate-900 md:text-4xl">What Customers Say</h2>
            </div>
            <span class="text-xs font-bold text-slate-400">Swipe to explore</span>
        </div>
        <div class="scrollbar-hide flex gap-4 overflow-x-auto scroll-smooth pb-4" id="home-reviews-scroller">
            @foreach($homeReviews as $review)
                @php
                    $reviewName = $review->user?->name ?? 'Verified Customer';
                    $reviewInitials = collect(explode(' ', $reviewName))->take(2)->map(fn($part) => strtoupper(substr($part, 0, 1)))->join('');
                @endphp
                <article class="flex min-h-[235px] min-w-[280px] max-w-[280px] flex-col rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:min-w-[350px] md:max-w-[350px]">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex gap-1 text-yellow-400">
                            @for($star = 1; $star <= 5; $star++)
                                <i class="fa-solid fa-star {{ $star > $review->rating ? 'text-slate-200' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[9px] font-black uppercase text-amber-700">Verified</span>
                    </div>
                    <p class="mt-5 line-clamp-4 text-sm leading-7 text-slate-600">“{{ $review->comment ?: 'Great freshness, packaging and delivery experience.' }}”</p>
                    <div class="mt-auto flex items-center gap-3 pt-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-600 text-xs font-black text-white">{{ $reviewInitials ?: 'VC' }}</span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-black text-slate-900">{{ $reviewName }}</p>
                            <p class="truncate text-[11px] font-semibold text-slate-400">{{ $review->product?->name ?? 'Porville Order' }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($liveStockCategory && $liveStockProducts->isNotEmpty())
{{-- Farm Fresh Live Stock (kept last on the page) --}}
<section class="bg-gray-50 py-8 md:py-12">
    <div class="mx-auto max-w-7xl px-4">
        <div class="mb-4 flex items-center justify-between gap-3 md:mb-6 md:items-end md:gap-4">
            <div class="min-w-0 text-left">
                <p class="hidden text-[11px] font-extrabold uppercase tracking-[0.28em] text-amber-600 md:block">Enquiry Only &middot; Call to Order</p>
                <h2 class="font-classic text-2xl font-bold text-slate-900 md:mt-1 md:text-3xl">Farm Fresh Live Stock</h2>
                <p class="mt-1 hidden max-w-lg text-sm text-slate-500 md:block">Healthy live farm birds and livestock raised under premium guidelines.</p>
            </div>
            <a href="{{ \App\Support\ShopUrl::to(['category' => $liveStockCategory->slug]) }}" class="inline-flex shrink-0 items-center gap-1.5 whitespace-nowrap text-[11px] font-extrabold uppercase tracking-[0.14em] text-amber-700 transition hover:text-amber-900 md:gap-2 md:rounded-full md:border md:border-amber-200 md:bg-amber-50 md:px-5 md:py-2.5 md:hover:border-amber-600 md:hover:bg-amber-600 md:hover:text-white">
                See All
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="scrollbar-hide flex gap-3 overflow-x-auto scroll-smooth pb-3 md:gap-5" data-mobile-autoscroll>
            @foreach($liveStockProducts as $product)
            <div class="min-w-[240px] max-w-[240px]">
                @include('frontend.partials.product-card', ['product' => $product])
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@section('scripts')
<script>
    let homeHeroIndex = 0;
    let homeHeroTimer = null;

    function homeHeroRender(index) {
        const slides = document.querySelectorAll('[data-home-hero-slide]');
        const dots = document.querySelectorAll('[data-home-hero-dot]');

        slides.forEach((slide, slideIndex) => {
            slide.classList.toggle('active', slideIndex === index);
        });

        dots.forEach((dot, dotIndex) => {
            dot.classList.toggle('active', dotIndex === index);
        });

        homeHeroIndex = index;
    }

    function homeHeroGoTo(index) {
        const slides = document.querySelectorAll('[data-home-hero-slide]');
        if (!slides.length) return;
        const normalized = (index + slides.length) % slides.length;
        homeHeroRender(normalized);
    }

    function homeHeroMove(step) {
        homeHeroGoTo(homeHeroIndex + step);
        homeHeroRestart();
    }

    function homeHeroStart() {
        const slides = document.querySelectorAll('[data-home-hero-slide]');
        if (slides.length < 2) return;
        homeHeroTimer = window.setInterval(() => {
            homeHeroGoTo(homeHeroIndex + 1);
        }, 5000);
    }

    function homeHeroStop() {
        if (homeHeroTimer) {
            window.clearInterval(homeHeroTimer);
            homeHeroTimer = null;
        }
    }

    function homeHeroRestart() {
        homeHeroStop();
        homeHeroStart();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const hero = document.getElementById('home-hero-carousel');
        if (!hero) return;

        homeHeroRender(0);
        homeHeroStart();

        hero.addEventListener('mouseenter', homeHeroStop);
        hero.addEventListener('mouseleave', homeHeroStart);
    });

    // Mobile only: rows glide slowly and continuously (right to the end, then back).
    document.addEventListener('DOMContentLoaded', () => {
        const mobileQuery = window.matchMedia('(max-width: 767px)');
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        const scrollers = Array.from(document.querySelectorAll('[data-mobile-autoscroll]'));
        if (!scrollers.length || reduceMotion.matches) return;

        const SPEED = 28;        // pixels per second
        const EDGE_PAUSE = 1500; // rest at each end before turning around
        const USER_PAUSE = 5000; // wait after the user touches a row

        const state = new Map();
        const visible = new Set();

        const observer = 'IntersectionObserver' in window
            ? new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        visible.add(entry.target);
                    } else {
                        visible.delete(entry.target);
                    }
                });
            }, { threshold: 0.2 })
            : null;

        scrollers.forEach((scroller) => {
            state.set(scroller, { pos: scroller.scrollLeft, dir: 1, pausedUntil: 0 });

            if (observer) {
                observer.observe(scroller);
            } else {
                visible.add(scroller);
            }

            const pause = () => {
                state.get(scroller).pausedUntil = performance.now() + USER_PAUSE;
            };
            ['touchstart', 'pointerdown', 'wheel', 'focusin'].forEach((type) => {
                scroller.addEventListener(type, pause, { passive: true });
            });
        });

        let running = false;
        let lastTime = 0;

        function frame(now) {
            if (!running) return;

            const dt = Math.min((now - lastTime) / 1000, 0.05);
            lastTime = now;

            if (!document.hidden) {
                visible.forEach((scroller) => {
                    const s = state.get(scroller);
                    if (s.pausedUntil > now) return;

                    const maxScroll = scroller.scrollWidth - scroller.clientWidth;
                    if (maxScroll <= 2) return;

                    // Continue from wherever the user left the row.
                    if (Math.abs(scroller.scrollLeft - s.pos) > 2) {
                        s.pos = scroller.scrollLeft;
                    }

                    s.pos += s.dir * SPEED * dt;

                    if (s.pos >= maxScroll) {
                        s.pos = maxScroll;
                        s.dir = -1;
                        s.pausedUntil = now + EDGE_PAUSE;
                    } else if (s.pos <= 0) {
                        s.pos = 0;
                        s.dir = 1;
                        s.pausedUntil = now + EDGE_PAUSE;
                    }

                    scroller.scrollLeft = s.pos;
                });
            }

            requestAnimationFrame(frame);
        }

        function sync() {
            const shouldRun = mobileQuery.matches;

            // The rows use scroll-smooth; per-frame updates need instant scrolling.
            scrollers.forEach((scroller) => {
                scroller.style.scrollBehavior = shouldRun ? 'auto' : '';
            });

            if (shouldRun && !running) {
                running = true;
                lastTime = performance.now();
                requestAnimationFrame(frame);
            } else if (!shouldRun) {
                running = false;
            }
        }

        sync();
        mobileQuery.addEventListener?.('change', sync);
    });
</script>
@endsection

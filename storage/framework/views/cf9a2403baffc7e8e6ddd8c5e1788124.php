
<?php $__env->startSection('title', 'FarmSea - Fresh Meat & Seafood'); ?>
<?php $__env->startSection('styles'); ?>
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
        background: linear-gradient(90deg, rgba(10, 31, 12, 0.82) 0%, rgba(10, 31, 12, 0.48) 34%, rgba(10, 31, 12, 0.08) 72%, rgba(10, 31, 12, 0.02) 100%);
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

    .type-showcase-card {
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
    }

    .type-showcase-card:hover {
        transform: translateY(-6px);
        border-color: #c7ddc2;
        box-shadow: 0 24px 48px rgba(34, 74, 29, 0.12);
    }

    .type-showcase-image {
        transition: transform .6s ease;
    }

    .type-showcase-card:hover .type-showcase-image {
        transform: scale(1.05);
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
        #home-new-arrivals-scroller > div { min-width: 184px; max-width: 184px; }
        #home-type-scroller > article { min-width: 210px; max-width: 210px; padding: 12px; }
        #home-favorites-scroller > article { min-width: 205px; max-width: 205px; padding: 8px; }
        #home-favorites-scroller > article > div { padding: 10px; }
        #home-favorites-scroller img { height: 145px; aspect-ratio: auto; }
        .type-showcase-card { border-radius: 18px; }
        .type-showcase-card .type-showcase-image { max-height: 260px; }
        .favorite-hero-banner { border-radius: 20px; }
        .favorite-hero-banner img { height: 250px; }
        .favorite-hero-copy { padding: 20px 16px; }
        .favorite-hero-copy h2 { font-size: 23px; }
        .favorite-hero-copy p { margin-top: 10px; font-size: 11px; line-height: 1.55; }
        .favorite-hero-copy .mt-6 { margin-top: 14px; }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<?php
    $defaultHeroSlides = [
        [
            'image' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?auto=format&fit=crop&w=1800&q=80',
            'badge' => 'Farm Fresh Daily',
            'category' => 'Chicken',
            'title_1' => 'Premium',
            'title_2' => 'Chicken Delivered Fresh',
            'description' => 'Sourced directly from our farms. Cleaned, cut and delivered to your doorstep within hours of slaughter.',
            'button' => 'Shop Now',
            'link' => route('frontend.products', ['category' => 'Chicken']),
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1600891964092-4316c288032e?auto=format&fit=crop&w=1800&q=80',
            'badge' => 'Chef Special Cuts',
            'category' => 'Mutton',
            'title_1' => 'Signature',
            'title_2' => 'Mutton Cuts',
            'description' => 'Choose tender curry cuts, premium chops and slow-cook favourites packed for flavor and freshness.',
            'button' => 'Shop Now',
            'link' => route('frontend.products', ['category' => 'Mutton']),
        ],
        [
            'image' => 'https://images.unsplash.com/photo-1510130387422-82bed34b37e9?auto=format&fit=crop&w=1800&q=80',
            'badge' => 'Catch of the Day',
            'category' => 'Seafood',
            'title_1' => 'Ocean Fresh',
            'title_2' => 'Seafood Daily',
            'description' => 'Discover fish, prawns and seafood cleaned, chilled and packed to lock in sea-fresh taste.',
            'button' => 'Shop Now',
            'link' => route('frontend.products', ['category' => 'Fish']),
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
?>

<section class="relative bg-[#0a0f1a] px-0 py-0">
    <div id="home-hero-carousel" class="home-hero-card relative h-[270px] overflow-hidden md:h-[340px]">
        <?php $__currentLoopData = $heroSlides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="home-hero-slide <?php echo e($index === 0 ? 'active' : ''); ?> absolute inset-0" data-home-hero-slide="<?php echo e($index); ?>">
                <picture>
                    <?php if(!empty($slide['mobile_image'])): ?>
                        <source media="(max-width: 767px)" srcset="<?php echo e($slide['mobile_image']); ?>">
                    <?php endif; ?>
                    <img
                        src="<?php echo e($slide['image']); ?>"
                        alt="<?php echo e($slide['title_1']); ?> <?php echo e($slide['title_2']); ?>"
                        class="absolute inset-0 h-full w-full object-cover"
                    >
                </picture>
                <div class="absolute inset-0 bg-black/45"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/35 to-black/10"></div>

                <div class="home-hero-content absolute inset-0 z-10 flex h-full items-center px-7 md:px-14 lg:px-20">
                    <div class="max-w-[430px] p-5 md:p-7">
                        <span class="mb-4 inline-flex w-fit rounded-md bg-blue-600 px-4 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-white shadow-lg">
                            <?php echo e($slide['badge']); ?>

                        </span>
                        <h1 class="max-w-[390px] text-[24px] font-extrabold leading-[1.08] text-white md:text-[36px]">
                            <?php echo e($slide['title_1']); ?>

                            <span class="block text-green-400"><?php echo e($slide['title_2']); ?></span>
                        </h1>
                        <p class="mt-4 max-w-[330px] text-[13px] leading-6 text-white/85 md:text-[15px]">
                            <?php echo e($slide['description']); ?>

                        </p>
                        <div class="mt-6">
                            <a href="<?php echo e($slide['link']); ?>" class="inline-flex rounded-xl bg-green-600 px-6 py-3 text-sm font-extrabold uppercase tracking-[0.12em] text-white transition hover:-translate-y-0.5 hover:bg-green-700 hover:shadow-xl">
                                <?php echo e($slide['button']); ?>

                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
            <?php $__currentLoopData = $heroSlides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <button
                    type="button"
                    aria-label="Go to slide <?php echo e($index + 1); ?>"
                    onclick="homeHeroGoTo(<?php echo e($index); ?>)"
                    class="home-hero-dot <?php echo e($index === 0 ? 'active' : ''); ?> h-2.5 w-2.5 rounded-full bg-white/45"
                    data-home-hero-dot="<?php echo e($index); ?>"
                ></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>


<section class="bg-white pb-2 pt-5 md:py-6">
    <div class="mx-auto max-w-7xl px-4">
        <?php
            $categoryLabels = ['Best Seller', 'Lean Protein', 'Juicy Cuts', 'Ready to Cook', 'Fresh Choice', 'Chef Pick', 'Daily Fresh', 'Top Rated'];
            $showcaseCategories = $categories->flatMap(function ($category) {
                if ($category->children->isNotEmpty()) {
                    return $category->children->map(function ($child) use ($category) {
                        return [
                            'title' => $child->name,
                            'parent_name' => $category->name,
                            'category_slug' => $category->slug,
                            'subcategory_slug' => $child->slug,
                            'image' => $child->image ?: $category->image,
                        ];
                    });
                }

                return collect([[
                    'title' => $category->name,
                    'parent_name' => $category->name,
                    'category_slug' => $category->slug,
                    'subcategory_slug' => null,
                    'image' => $category->image,
                ]]);
            })->take(12)->values();
        ?>

        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <h2 class="home-category-heading text-3xl font-extrabold text-slate-900 md:text-4xl">
                    Shop by <span class="text-blue-600"> Category  </span>
                </h2>
                <p class="mt-2 text-sm text-slate-500"> Farm-to-table freshness across every cut and kind. </p>
            </div>
            <a
                href="<?php echo e(route('frontend.categories')); ?>"
                class="inline-flex shrink-0 items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-4 py-2.5 text-[11px] font-extrabold uppercase tracking-[0.14em] text-blue-700 transition hover:border-blue-600 hover:bg-blue-600 hover:text-white sm:px-5"
            >
                See All
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div
            id="home-category-scroller"
            class="scrollbar-hide flex gap-4 overflow-x-auto scroll-smooth pb-3"
        >
            <?php $__empty_1 = true; $__currentLoopData = $showcaseCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $label = $categoryLabels[$index % count($categoryLabels)];
                    $linkParams = ['category' => $item['category_slug']];
                    if ($item['subcategory_slug']) {
                        $linkParams['subcategory'] = $item['subcategory_slug'];
                    }
                ?>

                <a
                    href="<?php echo e(route('frontend.products', $linkParams)); ?>"
                    class="group min-w-[228px] max-w-[228px] overflow-hidden rounded-[18px] border border-slate-200 bg-white shadow-[0_8px_24px_rgba(15,23,43,0.05)] transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-[0_18px_36px_rgba(37,99,235,0.14)]"
                >
                    <div class="relative h-[225px] overflow-hidden bg-slate-100">
                        <?php if($item['image']): ?>
                            <img
                                src="<?php echo e(asset('storage/' . $item['image'])); ?>"
                                alt="<?php echo e($item['title']); ?>"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                            >
                        <?php else: ?>
                            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-5xl text-slate-400">
                                <i class="fa-solid fa-drumstick-bite"></i>
                            </div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/12 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
                    </div>

                    <div class="border-t border-slate-100 px-4 py-3 text-center">
                        <h3 class="text-[15px] font-semibold text-slate-900 transition-colors group-hover:text-blue-700">
                            <?php echo e($item['title']); ?>

                        </h3>
                        <p class="mt-1 text-[10px] font-extrabold uppercase tracking-[0.18em] text-green-600">
                            <?php echo e($label); ?>

                        </p>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="flex min-h-[220px] w-full items-center justify-center rounded-3xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center text-slate-400">
                    <div>
                        <i class="fa-solid fa-layer-group mb-3 block text-4xl opacity-40"></i>
                        <p class="text-sm font-medium">No categories available right now.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>


<section class="bg-gray-50 pb-2 pt-2 md:py-6">
    <div class="mx-auto max-w-7xl px-4">
        <div class="mb-6 flex items-end justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-800">New <span class="text-blue-600">Arrivals</span></h2>
                <p class="mt-1 text-xs text-gray-500">The freshest additions to our selection.</p>
            </div>
            <a href="<?php echo e(route('frontend.products')); ?>" class="text-xs font-bold uppercase tracking-wider text-blue-600 hover:underline">See All</a>
        </div>
        <div id="home-new-arrivals-scroller" class="scrollbar-hide flex gap-3 overflow-x-auto scroll-smooth pb-3 md:gap-5">
            <?php $__empty_1 = true; $__currentLoopData = $newArrivals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="flex min-w-[260px] max-w-[260px] flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white transition hover:border-green-300 hover:shadow-lg">
                    <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>" class="relative block aspect-square overflow-hidden bg-gray-50">
                        <?php if(in_array($product->id, $newArrivalProductIds ?? [], true)): ?>
                            <span class="absolute left-3 top-3 z-10 rounded-full bg-blue-600 px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.16em] text-white shadow-sm">
                                     New Arrival  
                            </span>  
                        <?php endif; ?>
                        <?php if($product->images && count($product->images)): ?>
                            <img src="<?php echo e(asset('storage/'.$product->images[0])); ?>" alt="<?php echo e($product->name); ?>" class="h-full w-full object-cover transition-transform duration-500 hover:scale-105">
                        <?php else: ?>
                            <div class="flex h-full w-full items-center justify-center text-5xl">M</div>
                        <?php endif; ?>
                    </a>
                <div class="flex flex-1 flex-col p-3">
                        <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>" class="line-clamp-2 text-sm font-bold leading-snug text-gray-800 transition hover:text-green-700">
                            <?php echo e($product->name); ?>

                        </a>
                        <div class="mt-auto flex items-center justify-between pt-3">
                            <div>
                                <p class="text-lg font-extrabold text-gray-800">Rs<?php echo e(number_format($product->display_price, 0)); ?><span class="ml-1 text-xs font-semibold text-gray-400"><?php echo e($product->display_pack_label); ?></span></p>
                                <?php if($product->display_mrp && $product->display_mrp > $product->display_price): ?>
                                    <p class="text-xs text-gray-400 line-through">Rs<?php echo e(number_format($product->display_mrp, 0)); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if($product->is_active): ?>
                                <button onclick="addToCart(<?php echo e($product->id); ?>)" class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-600 text-sm text-white transition hover:bg-blue-700">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </button>
                            <?php else: ?>
                                <span class="inline-flex h-9 items-center justify-center rounded-lg bg-red-50 px-3 text-[10px] font-extrabold uppercase tracking-[0.14em] text-red-600">
                                    Sold Out
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="w-full py-8 text-center text-gray-400">No products yet.</p>
            <?php endif; ?>
        </div>
    </div>
</section>


<section class="bg-white pb-6 pt-2 md:py-7">
    <div class="mx-auto max-w-7xl px-4">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div class="text-left">
                <h2 class="text-3xl font-extrabold text-slate-900 md:text-4xl">
                    Shop By <span class="text-blue-600">Type</span>
                </h2>
            </div>
            <div class="hidden items-center gap-3 md:flex">
                <button
                    type="button"
                    aria-label="Scroll types left"
                    onclick="document.getElementById('home-type-scroller').scrollBy({ left: -360, behavior: 'smooth' })"
                    class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <button
                    type="button"
                    aria-label="Scroll types right"
                    onclick="document.getElementById('home-type-scroller').scrollBy({ left: 360, behavior: 'smooth' })"
                    class="flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600"
                >
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <?php
            $typeMeta = [
                'chicken' => ['icon' => 'fa-drumstick-bite', 'accent' => 'text-rose-500', 'tint' => 'bg-rose-50', 'subtitle' => 'Varieties'],
                'mutton' => ['icon' => 'fa-bacon', 'accent' => 'text-red-500', 'tint' => 'bg-red-50', 'subtitle' => 'Cuts'],
                'lamb' => ['icon' => 'fa-bacon', 'accent' => 'text-red-500', 'tint' => 'bg-red-50', 'subtitle' => 'Cuts'],
                'fish' => ['icon' => 'fa-fish-fins', 'accent' => 'text-sky-500', 'tint' => 'bg-sky-50', 'subtitle' => 'Types'],
                'seafood' => ['icon' => 'fa-fish-fins', 'accent' => 'text-sky-500', 'tint' => 'bg-sky-50', 'subtitle' => 'Types'],
                'egg' => ['icon' => 'fa-egg', 'accent' => 'text-amber-600', 'tint' => 'bg-amber-50', 'subtitle' => 'Farm Grade'],
                'dairy' => ['icon' => 'fa-egg', 'accent' => 'text-amber-600', 'tint' => 'bg-amber-50', 'subtitle' => 'Farm Grade'],
            ];

            $typeShowcaseLead = $featuredProducts->first() ?? $newArrivals->first();
            $typeShowcaseGrid = $featuredProducts
                ->slice(1)
                ->merge(
                    $newArrivals->reject(
                        fn($product) => $typeShowcaseLead && $product->id === $typeShowcaseLead->id
                    )
                )
                ->merge(
                    $bestSellers->reject(
                        fn($product) => $typeShowcaseLead && $product->id === $typeShowcaseLead->id
                    )
                )
                ->unique('id')
                ->take(6)
                ->values();
            $typeShowcaseCategories = $categories->take(5)->values();
            $typeShowcaseLeadCategory = $typeShowcaseCategories->first();
            $typeShowcaseGridCategories = $typeShowcaseCategories->slice(1, 4);
        ?>

        <div
            id="home-type-scroller"
            class="scrollbar-hide flex gap-4 overflow-x-auto scroll-smooth pb-3"
        >
            <?php $__empty_1 = true; $__currentLoopData = $categories->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $slugKey = strtolower($cat->slug ?? $cat->name);
                    $meta = collect($typeMeta)->first(fn($value, $key) => str_contains($slugKey, $key)) ?? ['icon' => 'fa-layer-group', 'accent' => 'text-green-600', 'tint' => 'bg-green-50', 'subtitle' => 'Collections'];
                    $previewImages = collect([$cat->image])->merge($cat->children->pluck('image'))->filter()->take(3)->values();
                    $countLabel = max($cat->children_count, $cat->products_count, 1);
                    $countText = $cat->children_count > 0 ? $meta['subtitle'] : 'Products';
                    $cardLink = route('frontend.categories', ['focus' => $cat->slug]) . '#category-' . $cat->slug;
                ?>

                <article class="group min-w-[260px] max-w-[260px] rounded-[22px] border border-slate-200 bg-white p-4 shadow-[0_10px_30px_rgba(15,23,43,0.04)] transition-all duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-[0_20px_40px_rgba(37,99,235,0.12)]">
                    <a href="<?php echo e($cardLink); ?>" class="block">
                        <div class="flex flex-col items-center text-center">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl <?php echo e($meta['tint']); ?> text-xl <?php echo e($meta['accent']); ?> transition-transform duration-300 group-hover:scale-110">
                                <i class="fa-solid <?php echo e($meta['icon']); ?>"></i>
                            </div>

                            <h3 class="mt-4 text-[15px] font-extrabold uppercase tracking-[0.08em] text-slate-900">
                                <?php echo e($cat->name); ?>

                            </h3>
                            <p class="mt-1 text-[12px] text-slate-500">
                                <?php echo e($countLabel); ?>+ <?php echo e($countText); ?>

                            </p>
                        </div>

                        <div class="mt-5 grid grid-cols-3 gap-2.5">
                            <?php for($index = 0; $index < 3; $index++): ?>
                                <?php
                                    $image = $previewImages->get($index);
                                ?>
                                <div class="relative aspect-[1.08] overflow-hidden rounded-xl bg-slate-100">
                                    <?php if($image): ?>
                                        <img
                                            src="<?php echo e(asset('storage/' . $image)); ?>"
                                            alt="<?php echo e($cat->name); ?>"
                                            class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                        >
                                    <?php else: ?>
                                        <div class="flex h-full w-full items-center justify-center <?php echo e($meta['tint']); ?> text-lg <?php echo e($meta['accent']); ?>">
                                            <i class="fa-solid <?php echo e($meta['icon']); ?>"></i>
                                        </div>
                                    <?php endif; ?>

                                    <?php if($index === 2): ?>
                                        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/10 via-transparent to-slate-900/60"></div>
                                        <div class="absolute inset-0 flex items-center justify-center text-center">
                                            <span class="rounded-full bg-white/85 px-2.5 py-1 text-[11px] font-extrabold text-slate-900 shadow-sm">
                                                +<?php echo e($countLabel); ?>

                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </a>

                    <a
                        href="<?php echo e($cardLink); ?>"
                        class="mt-4 flex h-11 items-center justify-center rounded-xl bg-blue-600 text-[12px] font-extrabold uppercase tracking-[0.18em] text-white transition-all duration-300 hover:bg-blue-700 group-hover:shadow-lg"
                    >
                        View All
                    </a>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="flex min-h-[220px] w-full items-center justify-center rounded-3xl border border-dashed border-slate-200 bg-slate-50 px-6 py-12 text-center text-slate-400">
                    <i class="fa-solid fa-layer-group mb-3 block text-4xl opacity-40"></i>
                    <p class="text-sm font-medium">No categories available right now.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-5 flex justify-center md:hidden">
            <a href="<?php echo e(route('frontend.categories')); ?>" class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-5 py-3 text-xs font-extrabold uppercase tracking-[0.18em] text-white transition hover:bg-blue-700">
                Browse Types
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <?php if($typeShowcaseLead): ?>
            <div class="mt-8 grid gap-4 lg:grid-cols-[0.82fr_1.18fr] lg:gap-5">
                <article class="type-showcase-card self-start overflow-hidden rounded-[22px] border border-[#d8ead5] bg-[#f7fbf5]">
                    <div class="flex flex-col">
                        <div class="p-4 pb-3 md:min-h-[175px] md:p-5 md:pb-4">
                            <span class="inline-flex rounded-full bg-green-700 px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.14em] text-white shadow-sm">
                                Chef's Selection
                            </span>
                            <h3 class="mt-3 max-w-[320px] text-[23px] font-extrabold leading-[1.1] text-slate-900 md:text-[30px]">
                                <?php echo e($typeShowcaseLead->name); ?>

                            </h3>
                            <p class="mt-2 max-w-[340px] text-[12px] leading-5 text-slate-500">
                                <?php echo e(\Illuminate\Support\Str::limit($typeShowcaseLead->description ?: 'Raised and packed fresh for everyday home cooking.', 110)); ?>

                            </p>
                            <div class="mt-4 flex items-end justify-between gap-4">
                                <div>
                                    <p class="text-[18px] font-black text-slate-950">Rs<?php echo e(number_format($typeShowcaseLead->display_price, 0)); ?><span class="ml-1 text-[11px] font-semibold text-slate-400"><?php echo e($typeShowcaseLead->display_pack_label); ?></span></p>
                                    <?php if($typeShowcaseLead->display_mrp && $typeShowcaseLead->display_mrp > $typeShowcaseLead->display_price): ?>
                                        <p class="text-xs text-slate-400 line-through">Rs<?php echo e(number_format($typeShowcaseLead->display_mrp, 0)); ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php if($typeShowcaseLead->is_active): ?>
                                    <button onclick="addToCart(<?php echo e($typeShowcaseLead->id); ?>)" class="inline-flex items-center gap-2 rounded-lg bg-green-700 px-4 py-2.5 text-[11px] font-extrabold uppercase tracking-[0.14em] text-white transition hover:bg-green-800">
                                        <i class="fa-solid fa-cart-plus text-[11px]"></i>
                                        Add
                                    </button>
                                <?php else: ?>
                                    <span class="inline-flex items-center rounded-xl bg-red-50 px-5 py-3 text-[12px] font-extrabold uppercase tracking-[0.14em] text-red-600">
                                        Out of Stock
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <a href="<?php echo e(route('frontend.product.show', $typeShowcaseLead->slug)); ?>" class="relative block overflow-hidden">
                            <?php if(in_array($typeShowcaseLead->id, $newArrivalProductIds ?? [], true)): ?>
                                <span class="absolute left-5 top-5 z-10 inline-flex rounded-full bg-blue-600 px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.14em] text-white shadow-sm">
                                    New Arrival
                                </span>
                            <?php endif; ?>
                            <?php if($typeShowcaseLead->images && count($typeShowcaseLead->images)): ?>
                                <img
                                    src="<?php echo e(asset('storage/' . $typeShowcaseLead->images[0])); ?>"
                                    alt="<?php echo e($typeShowcaseLead->name); ?>"
                                    class="type-showcase-image h-[240px] w-full object-cover object-center md:h-[285px]"
                                >
                            <?php else: ?>
                                <div class="flex h-[240px] w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-5xl text-slate-400 md:h-[285px]">
                                    <i class="fa-solid fa-drumstick-bite"></i>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                </article>

                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 lg:self-start">
                    <?php $__currentLoopData = $typeShowcaseGrid; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <article class="type-showcase-card group overflow-hidden rounded-[19px] border border-slate-200 bg-white p-3 shadow-[0_10px_30px_rgba(15,23,43,0.05)]">
                            <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>" class="relative block overflow-hidden rounded-[13px]">
                                <?php if(in_array($product->id, $newArrivalProductIds ?? [], true)): ?>
                                    <span class="absolute left-3 top-3 z-10 rounded-full bg-blue-600 px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.14em] text-white shadow-sm">
                                        New Arrival
                                    </span>
                                <?php endif; ?>
                                <?php if($product->images && count($product->images)): ?>
                                    <img
                                        src="<?php echo e(asset('storage/' . $product->images[0])); ?>"
                                        alt="<?php echo e($product->name); ?>"
                                        class="type-showcase-image aspect-[1.12] w-full object-cover"
                                    >
                                <?php else: ?>
                                    <div class="flex aspect-[1.12] w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-4xl text-slate-400">
                                        <i class="fa-solid fa-fish-fins"></i>
                                    </div>
                                <?php endif; ?>
                            </a>

                            <div class="pt-2.5">
                                <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>" class="block text-[12px] font-extrabold leading-[1.3] text-slate-900 transition hover:text-green-700">
                                    <?php echo e($product->name); ?>

                                </a>
                                <div class="mt-2.5 flex items-end justify-between gap-2">
                                    <p class="text-[14px] font-black text-slate-950">Rs<?php echo e(number_format($product->display_price, 0)); ?><span class="ml-1 text-[9px] font-semibold text-slate-400"><?php echo e($product->display_pack_label); ?></span></p>
                                    <?php if($product->is_active): ?>
                                        <button onclick="addToCart(<?php echo e($product->id); ?>)" class="inline-flex min-h-[34px] flex-shrink-0 items-center justify-center gap-1.5 rounded-lg border border-[#dbe8d7] bg-[#f8fbf6] px-2.5 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.1em] text-green-700 transition duration-300 hover:border-green-300 hover:bg-green-50 group-hover:-translate-y-0.5 group-hover:shadow-sm">
                                            <i class="fa-solid fa-cart-plus text-[14px] leading-none" aria-hidden="true"></i>
                                            <span>Add</span>
                                        </button>
                                    <?php else: ?>
                                        <span class="rounded-lg bg-red-50 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.14em] text-red-600">
                                            Sold Out
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php for($slot = $typeShowcaseGrid->count(); $slot < 6; $slot++): ?>
                        <article class="type-showcase-card flex min-h-[245px] flex-col items-center justify-center rounded-[19px] border border-dashed border-green-200 bg-[linear-gradient(145deg,#f7fbf5,#eff8ec)] p-5 text-center shadow-[0_10px_30px_rgba(15,23,43,0.035)]">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white text-lg text-green-600 shadow-sm">
                                <i class="fa-solid fa-basket-shopping"></i>
                            </span>
                            <p class="mt-4 text-[12px] font-extrabold uppercase tracking-[0.12em] text-slate-800">More Fresh Picks</p>
                            <p class="mt-1 text-[10px] leading-5 text-slate-500">New products coming soon</p>
                            <a href="<?php echo e(route('frontend.products')); ?>" class="mt-4 inline-flex rounded-lg border border-green-200 bg-white px-3 py-2 text-[9px] font-extrabold uppercase tracking-[0.12em] text-green-700 transition hover:border-green-300 hover:bg-green-50">
                                View All
                            </a>
                        </article>
                    <?php endfor; ?>
                </div>
            </div>
        <?php elseif($typeShowcaseLeadCategory): ?>
            <div class="mt-8 grid gap-4 lg:grid-cols-[1.08fr_1fr] lg:gap-5">
                <?php
                    $leadCategoryImage = $typeShowcaseLeadCategory->image
                        ? asset('storage/' . $typeShowcaseLeadCategory->image)
                        : ($typeShowcaseLeadCategory->children->pluck('image')->filter()->first()
                            ? asset('storage/' . $typeShowcaseLeadCategory->children->pluck('image')->filter()->first())
                            : null);
                ?>

                <article class="type-showcase-card overflow-hidden rounded-[26px] border border-[#d8ead5] bg-[#f7fbf5]">
                    <div class="flex h-full flex-col">
                        <div class="p-5 md:p-6">
                            <span class="inline-flex rounded-full bg-green-700 px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.14em] text-white shadow-sm">
                                Fresh Picks
                            </span>
                            <h3 class="mt-4 max-w-[340px] text-[28px] font-extrabold leading-[1.08] text-slate-900 md:text-[38px]">
                                <?php echo e($typeShowcaseLeadCategory->name); ?> Fresh Collection
                            </h3>
                            <p class="mt-3 max-w-[360px] text-[13px] leading-6 text-slate-500">
                                Browse top cuts, ready-to-cook options and daily essentials from our <?php echo e(strtolower($typeShowcaseLeadCategory->name)); ?> range.
                            </p>
                            <div class="mt-5 flex items-end justify-between gap-4">
                                <div>
                                    <p class="text-[12px] font-bold uppercase tracking-[0.16em] text-slate-400">Available Now</p>
                                    <p class="mt-1 text-[20px] font-black text-slate-950"><?php echo e(max($typeShowcaseLeadCategory->children_count, $typeShowcaseLeadCategory->products_count, 1)); ?>+ Items</p>
                                </div>
                                <a href="<?php echo e(route('frontend.products', ['category' => $typeShowcaseLeadCategory->slug])); ?>" class="inline-flex items-center gap-2 rounded-xl bg-green-700 px-5 py-3 text-[12px] font-extrabold uppercase tracking-[0.14em] text-white transition hover:bg-green-800">
                                    <i class="fa-solid fa-arrow-right text-[11px]"></i>
                                    Browse
                                </a>
                            </div>
                        </div>

                        <a href="<?php echo e(route('frontend.products', ['category' => $typeShowcaseLeadCategory->slug])); ?>" class="mt-auto block overflow-hidden">
                            <?php if($leadCategoryImage): ?>
                                <img
                                    src="<?php echo e($leadCategoryImage); ?>"
                                    alt="<?php echo e($typeShowcaseLeadCategory->name); ?>"
                                    class="type-showcase-image h-[250px] w-full object-cover md:h-[280px]"
                                >
                            <?php else: ?>
                                <div class="flex h-[250px] w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-6xl text-slate-400 md:h-[280px]">
                                    <i class="fa-solid fa-drumstick-bite"></i>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                </article>

                <div class="grid gap-4 sm:grid-cols-2">
                    <?php $__currentLoopData = $typeShowcaseGridCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $categoryImage = $category->image
                                ? asset('storage/' . $category->image)
                                : ($category->children->pluck('image')->filter()->first()
                                    ? asset('storage/' . $category->children->pluck('image')->filter()->first())
                                    : null);
                        ?>

                        <article class="type-showcase-card overflow-hidden rounded-[22px] border border-slate-200 bg-white p-3">
                            <a href="<?php echo e(route('frontend.products', ['category' => $category->slug])); ?>" class="block overflow-hidden rounded-[16px]">
                                <?php if($categoryImage): ?>
                                    <img
                                        src="<?php echo e($categoryImage); ?>"
                                        alt="<?php echo e($category->name); ?>"
                                        class="type-showcase-image aspect-[1.05] w-full object-cover"
                                    >
                                <?php else: ?>
                                    <div class="flex aspect-[1.05] w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-5xl text-slate-400">
                                        <i class="fa-solid fa-layer-group"></i>
                                    </div>
                                <?php endif; ?>
                            </a>

                            <div class="pt-3">
                                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">Fresh Range</p>
                                <a href="<?php echo e(route('frontend.products', ['category' => $category->slug])); ?>" class="mt-1 block text-[14px] font-extrabold leading-[1.3] text-slate-900 transition hover:text-green-700">
                                    <?php echo e($category->name); ?>

                                </a>
                                <div class="mt-3 flex items-end justify-between gap-3">
                                    <p class="text-[15px] font-black text-slate-950"><?php echo e(max($category->children_count, $category->products_count, 1)); ?>+ Items</p>
                                    <a href="<?php echo e(route('frontend.products', ['category' => $category->slug])); ?>" class="rounded-lg border border-[#dbe8d7] bg-[#f8fbf6] px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.14em] text-green-700 transition hover:border-green-300 hover:bg-green-50">
                                        View
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>


<section class="bg-[#f7faf7] py-7 md:py-14">
    <div class="mx-auto max-w-7xl px-4">
        <div class="favorite-hero-banner mb-8 rounded-[30px] border border-[#d7e7d5] shadow-[0_24px_60px_rgba(15,23,42,0.10)]">
            <img
                src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1800&q=80"
                alt="Fresh meat and seafood platter"
                class="h-[280px] w-full object-cover md:h-[360px]"
            >
            <div class="favorite-hero-copy absolute inset-0 z-10 flex max-w-[400px] flex-col justify-center px-5 py-8 md:px-10">
                <span class="mb-5 inline-flex w-fit rounded-md bg-blue-600 px-4 py-1.5 text-xs font-extrabold uppercase tracking-[0.18em] text-white shadow-lg">
                    Premium Quality
                </span>
                <h2 class="text-[28px] font-extrabold leading-[1.04] text-white md:text-[48px]">
                    The Freshest
                    <span class="block text-green-400">Meat & Seafood</span>
                    Delivered Daily
                </h2>
                <p class="mt-4 max-w-[340px] text-[13px] leading-6 text-white/85 md:text-[15px]">
                    From our farm gates to your kitchen. Hand-cleaned, hygienically packed proteins every single day.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="<?php echo e(route('frontend.products')); ?>" class="rounded-xl bg-green-600 px-5 py-2.5 text-xs font-extrabold uppercase tracking-[0.14em] text-white transition hover:bg-green-700">
                        Shop The Range
                    </a>
                    <a href="<?php echo e(route('frontend.categories')); ?>" class="rounded-xl border border-white/30 bg-white/10 px-5 py-2.5 text-xs font-extrabold uppercase tracking-[0.14em] text-white backdrop-blur transition hover:bg-white/20">
                        Learn More
                    </a>
                </div>
            </div>
        </div>

        <div class="mb-7 flex items-end justify-between gap-4">
            <div>
                <p class="mb-2 text-[11px] font-extrabold uppercase tracking-[0.34em] text-green-600">Best Sellers</p>
                <h2 class="text-3xl font-extrabold text-slate-900 md:text-4xl">
                    Best <span class="text-slate-300">Sellers</span>
                </h2>
                <p class="mt-2 text-sm text-slate-500">Yeh wo products hain jo customers sabse zyada order karte hain.</p>
            </div>
            <div class="hidden items-center gap-3 md:flex">
                <button
                    type="button"
                    aria-label="Scroll favorites left"
                    onclick="document.getElementById('home-favorites-scroller').scrollBy({ left: -360, behavior: 'smooth' })"
                    class="flex h-12 w-12 items-center justify-center rounded-full border border-[#d8e8d7] bg-white text-slate-600 shadow-sm transition hover:border-green-200 hover:text-green-700"
                >
                    <i class="fa-solid fa-arrow-left"></i>
                </button>
                <button
                    type="button"
                    aria-label="Scroll favorites right"
                    onclick="document.getElementById('home-favorites-scroller').scrollBy({ left: 360, behavior: 'smooth' })"
                    class="flex h-12 w-12 items-center justify-center rounded-full border border-[#d8e8d7] bg-white text-slate-600 shadow-sm transition hover:border-green-200 hover:text-green-700"
                >
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <?php
            $favoriteLabels = ['Most Ordered', 'Top Rated', 'Chef Pick', 'Best For Fry', 'Family Pack', 'Daily Fresh', 'Quick Cook', 'Popular Choice'];
        ?>

        <div id="home-favorites-scroller" class="scrollbar-hide flex gap-4 overflow-x-auto scroll-smooth pb-3">
            <?php $__empty_1 = true; $__currentLoopData = $bestSellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $favoriteImage = $product->images && count($product->images) ? asset('storage/' . $product->images[0]) : null;
                    $favoriteLabel = $favoriteLabels[$index % count($favoriteLabels)];
                ?>

                <article class="favorite-card group min-w-[255px] max-w-[255px] rounded-[28px] p-3">
                    <div class="rounded-[22px] border border-[#e5efe4] bg-white p-3">
                        <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>" class="block">
                            <div class="relative overflow-hidden rounded-[20px] bg-slate-100">
                                <?php if($favoriteImage): ?>
                                    <img
                                        src="<?php echo e($favoriteImage); ?>"
                                        alt="<?php echo e($product->name); ?>"
                                        class="aspect-[0.95] w-full object-cover transition duration-500 group-hover:scale-105"
                                    >
                                <?php else: ?>
                                    <div class="flex aspect-[0.95] w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200 text-5xl text-slate-400">
                                        <i class="fa-solid fa-drumstick-bite"></i>
                                    </div>
                                <?php endif; ?>

                                <span class="absolute left-3 top-3 rounded-lg bg-white px-3 py-1 text-[11px] font-extrabold uppercase tracking-[0.16em] text-blue-700 shadow-sm">
                                    <?php echo e($favoriteLabel); ?>

                                </span>
                            </div>
                        </a>

                        <div class="mt-2 flex min-h-0 flex-col sm:mt-3 sm:min-h-[100px]">
                            <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>" class="text-slate-900 transition hover:text-green-700">
                                <span class="block text-[18px] font-extrabold leading-[1.15]"><?php echo e($product->name); ?></span>
                            </a>
                            <p class="mt-2 text-[13px] text-slate-500">
                                <?php echo e(optional($product->category)->name ?: 'FarmSea Fresh'); ?>

                                <?php if(!empty($product->ordered_quantity)): ?>
                                    <span class="text-slate-300">|</span> <?php echo e((int) $product->ordered_quantity); ?> orders
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="mt-3 flex items-end justify-between gap-3 sm:mt-6">
                            <div>
                                <p class="text-[18px] font-black text-slate-950">Rs<?php echo e(number_format($product->display_price, 0)); ?><span class="ml-1 text-[11px] font-semibold text-slate-400"><?php echo e($product->display_pack_label); ?></span></p>
                                <?php if($product->display_mrp && $product->display_mrp > $product->display_price): ?>
                                    <p class="text-xs text-slate-400 line-through">Rs<?php echo e(number_format($product->display_mrp, 0)); ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if($product->is_active): ?>
                                <button onclick="addToCart(<?php echo e($product->id); ?>)" aria-label="Add <?php echo e($product->name); ?> to cart" title="Add to cart" class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-green-700 text-white transition hover:bg-green-800">
                                    <i class="fa-solid fa-cart-shopping text-[18px]" aria-hidden="true"></i>
                                </button>
                            <?php else: ?>
                                <span class="inline-flex items-center rounded-xl bg-red-50 px-5 py-3 text-[12px] font-extrabold uppercase tracking-[0.14em] text-red-600">
                                    Out of Stock
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="flex min-h-[250px] w-full items-center justify-center rounded-[28px] border border-dashed border-slate-200 bg-white px-6 py-12 text-center text-slate-400">
                    <div>
                        <i class="fa-solid fa-fire-flame-curved mb-3 block text-4xl opacity-40"></i>
                        <p class="text-sm font-medium">Best sellers yahan tab dikhेंगे jab products par orders aane lagenge.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>


<?php if(($homeReviews ?? collect())->isNotEmpty()): ?>
<section class="bg-white py-8 md:py-14">
    <div class="mx-auto max-w-7xl px-4">
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <p class="mb-2 text-[11px] font-extrabold uppercase tracking-[0.3em] text-green-600">Verified Reviews</p>
                <h2 class="text-3xl font-extrabold text-slate-900 md:text-4xl">What Customers Say</h2>
            </div>
            <span class="text-xs font-bold text-slate-400">Swipe to explore</span>
        </div>
        <div class="scrollbar-hide flex gap-4 overflow-x-auto scroll-smooth pb-4" id="home-reviews-scroller">
            <?php $__currentLoopData = $homeReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $reviewName = $review->user?->name ?? 'Verified Customer';
                    $reviewInitials = collect(explode(' ', $reviewName))->take(2)->map(fn($part) => strtoupper(substr($part, 0, 1)))->join('');
                ?>
                <article class="flex min-h-[235px] min-w-[280px] max-w-[280px] flex-col rounded-3xl border border-slate-200 bg-white p-5 shadow-sm md:min-w-[350px] md:max-w-[350px]">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex gap-1 text-yellow-400">
                            <?php for($star = 1; $star <= 5; $star++): ?>
                                <i class="fa-solid fa-star <?php echo e($star > $review->rating ? 'text-slate-200' : ''); ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <span class="rounded-full bg-green-50 px-2.5 py-1 text-[9px] font-black uppercase text-green-700">Verified</span>
                    </div>
                    <p class="mt-5 line-clamp-4 text-sm leading-7 text-slate-600">“<?php echo e($review->comment ?: 'Great freshness, packaging and delivery experience.'); ?>”</p>
                    <div class="mt-auto flex items-center gap-3 pt-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-xs font-black text-white"><?php echo e($reviewInitials ?: 'VC'); ?></span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-black text-slate-900"><?php echo e($reviewName); ?></p>
                            <p class="truncate text-[11px] font-semibold text-slate-400"><?php echo e($review->product?->name ?? 'FarmSea Order'); ?></p>
                        </div>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>


<?php if(false): ?>

<section class="bg-white py-8 md:py-16">
    <div class="mx-auto max-w-7xl px-4">
        <div class="mb-8 flex flex-col gap-6 md:mb-10 md:flex-row md:items-start md:justify-between">
            <div>
                <p class="mb-2 text-[11px] font-extrabold uppercase tracking-[0.35em] text-green-600">Real Stories</p>
                <h2 class="text-3xl font-extrabold leading-tight text-slate-900 md:text-[54px] md:leading-[1.02]">
                    Trusted by <span class="text-slate-300">Thousands</span>
                </h2>
            </div>
            <div class="text-left md:text-right">
                <p class="text-2xl font-extrabold text-slate-900 md:text-[28px]">Excellent 4.9 / 5</p>
                <p class="mt-2 text-[11px] font-bold uppercase tracking-[0.28em] text-[#8f9b83]">Based on 8,400+ Reviews</p>
                <div class="mt-4 flex gap-1.5 text-[22px] text-[#f59e0b] md:justify-end">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
            </div>
        </div>

        <div class="grid gap-5 md:gap-6 lg:grid-cols-[1.05fr_1fr_1fr] lg:grid-rows-2">
            <div class="group relative min-h-[320px] overflow-hidden rounded-[28px] bg-slate-100 shadow-[0_14px_36px_rgba(15,23,43,0.08)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_22px_48px_rgba(15,23,43,0.14)] lg:row-span-2 lg:min-h-[585px]">
                <img
                    src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?auto=format&fit=crop&w=900&q=80"
                    alt="Happy customers cooking together"
                    class="absolute inset-0 h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                >
                <div class="absolute inset-0 bg-gradient-to-t from-[#102113]/92 via-[#102113]/20 to-white/10"></div>

                <div class="absolute left-4 top-4 rounded-2xl bg-white/95 px-3 py-3 shadow-lg backdrop-blur transition duration-300 group-hover:-translate-y-0.5">
                    <div class="flex items-center gap-3">
                        <img
                            src="https://images.unsplash.com/photo-1587593810167-a84920ea0781?auto=format&fit=crop&w=120&q=80"
                            alt="Whole Chicken"
                            class="h-10 w-10 rounded-xl object-cover"
                        >
                        <div>
                            <p class="text-[13px] font-bold text-slate-800">Ordered</p>
                            <p class="text-[11px] text-slate-500">Whole Chicken</p>
                        </div>
                    </div>
                </div>

                <button type="button" aria-label="Play customer story" class="absolute left-1/2 top-1/2 flex h-[62px] w-[62px] -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full bg-white text-[26px] text-green-700 shadow-xl transition duration-300 hover:scale-110">
                    <i class="fa-solid fa-play ml-1"></i>
                </button>

                <div class="absolute inset-x-0 bottom-0 p-5 md:p-7">
                    <p class="max-w-[305px] text-[26px] font-extrabold italic leading-[1.1] text-white">
                        "The freshest chicken I've ever received - arrived cleaned and cold, ready to cook!"
                    </p>
                    <p class="mt-5 text-[13px] font-extrabold uppercase tracking-[0.24em] text-[#a8d18f]">
                        - Priya M., Verified Buyer
                    </p>
                </div>
            </div>

            <div class="group flex flex-col justify-between rounded-[24px] border border-[#dbe6d8] bg-[#fdfefd] p-5 transition duration-300 hover:-translate-y-1 hover:border-[#c6dbc0] hover:shadow-[0_18px_40px_rgba(92,129,79,0.12)] md:p-6 lg:min-h-[220px]">
                <div>
                    <div class="mb-5 flex gap-1 text-[#f59e0b]">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-[17px] leading-[1.85] text-slate-700">
                        "The mutton arrived so fresh - still cold from the ice packs. Noticeably better quality than the local market. Will definitely reorder."
                    </p>
                </div>
                <div class="mt-8 flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#2a6fc3] text-sm font-bold text-white transition duration-300 group-hover:scale-110">AK</div>
                    <div>
                        <p class="text-[15px] font-extrabold text-slate-900">Arjun K.</p>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#7e8a73]">Home Chef</p>
                    </div>
                </div>
            </div>

            <div class="group flex flex-col justify-between rounded-[24px] border border-[#dbe6d8] bg-[#fdfefd] p-5 transition duration-300 hover:-translate-y-1 hover:border-[#c6dbc0] hover:shadow-[0_18px_40px_rgba(92,129,79,0.12)] md:p-6 lg:min-h-[220px]">
                <div>
                    <div class="mb-5 flex gap-1 text-[#f59e0b]">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-[17px] leading-[1.85] text-slate-700">
                        "Ordered at 8 AM and received by noon. The prawns were huge and absolutely delicious grilled. Fastest delivery for fresh meat!"
                    </p>
                </div>
                <div class="mt-8 flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#2f9138] text-sm font-bold text-white transition duration-300 group-hover:scale-110">RD</div>
                    <div>
                        <p class="text-[15px] font-extrabold text-slate-900">Rekha D.</p>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#7e8a73]">Regular Customer</p>
                    </div>
                </div>
            </div>

            <div class="group flex flex-col justify-between rounded-[24px] border border-[#dbe6d8] bg-[#fdfefd] p-5 transition duration-300 hover:-translate-y-1 hover:border-[#c6dbc0] hover:shadow-[0_18px_40px_rgba(92,129,79,0.12)] md:p-6 lg:min-h-[220px]">
                <div>
                    <div class="mb-5 flex gap-1 text-[#f59e0b]">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p class="text-[17px] leading-[1.85] text-slate-700">
                        "Each portion is labelled with weight and date. My family orders every week - consistent quality, zero complaints every single time."
                    </p>
                </div>
                <div class="mt-8 flex items-center gap-4">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-[#2a6fc3] text-sm font-bold text-white transition duration-300 group-hover:scale-110">SM</div>
                    <div>
                        <p class="text-[15px] font-extrabold text-slate-900">Suresh M.</p>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-[#7e8a73]">Subscribed Member</p>
                    </div>
                </div>
            </div>

            <div class="group flex min-h-[220px] flex-col items-center justify-center rounded-[24px] bg-[#2f73c8] p-6 text-center text-white shadow-[0_14px_36px_rgba(47,115,200,0.24)] transition duration-300 hover:-translate-y-1 hover:bg-[#2768b8] hover:shadow-[0_22px_48px_rgba(47,115,200,0.34)] md:p-8">
                <div class="flex h-14 w-14 items-center justify-center rounded-full bg-white/15 text-2xl transition duration-300 group-hover:scale-110 group-hover:bg-white/20">
                    <i class="fa-solid fa-pen-nib"></i>
                </div>
                <h3 class="mt-6 text-3xl font-extrabold md:text-[34px]">Share Your Story</h3>
                <p class="mt-4 max-w-[270px] text-[16px] leading-7 text-white/85">
                    Get Rs50 off your next order for every verified review.
                </p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views/frontend/home.blade.php ENDPATH**/ ?>
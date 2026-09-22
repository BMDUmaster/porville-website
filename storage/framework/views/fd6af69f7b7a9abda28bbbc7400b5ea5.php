<?php $__env->startSection('title', 'Porville — Fresh Cut Pure Standards'); ?>
<?php $__env->startSection('styles'); ?>
<style>
    .home-hero-slide {
        opacity: 0;
        transform: scale(1.02);
        transition: opacity .7s ease, transform 5s ease;
        pointer-events: none;
    }

    .home-hero-slide.active {
        position: relative;
        inset: auto;
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


<?php
    $defaultHeroSlides = [
        [
            'image' => 'https://images.unsplash.com/photo-1604503468506-a8da13d82791?auto=format&fit=crop&w=1800&q=80',
            'badge' => "Delhi's Finest Butchers",
            'category' => 'Chicken',
            'title_1' => 'Premium',
            'title_2' => 'Chicken, Cut Fresh',
            'description' => 'Custom-cut to order, vacuum-sealed and delivered chilled within 2 hours. Never pre-packaged.',
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
            'image' => 'https://images.unsplash.com/photo-1518492104633-130d0cc84637?auto=format&fit=crop&w=1800&q=80',
            'badge' => 'Farm Fresh, Daily',
            'category' => 'Eggs',
            'title_1' => 'Pure Standards',
            'title_2' => 'Farm Fresh Eggs',
            'description' => 'Pasture-raised, farm-fresh eggs sourced daily. Frozen and non-frozen options available.',
            'button' => 'Shop Now',
            'link' => route('frontend.products', ['category' => 'Eggs']),
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
    <div id="home-hero-carousel" class="home-hero-card relative overflow-hidden">
        <?php $__currentLoopData = $heroSlides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="home-hero-slide <?php echo e($index === 0 ? 'active' : ''); ?> absolute inset-0" data-home-hero-slide="<?php echo e($index); ?>">
                <picture>
                    <?php if(!empty($slide['mobile_image'])): ?>
                        <source media="(max-width: 767px)" srcset="<?php echo e($slide['mobile_image']); ?>">
                    <?php endif; ?>
                    <img
                        src="<?php echo e($slide['image']); ?>"
                        alt="<?php echo e($slide['title_1']); ?> <?php echo e($slide['title_2']); ?>"
                        class="block h-auto w-full"
                    >
                </picture>
                <div class="hidden absolute inset-0 bg-black/45"></div>
                <div class="hidden absolute inset-0 bg-gradient-to-r from-black/70 via-black/35 to-black/10"></div>

                <div class="home-hero-content hidden absolute inset-0 z-10 h-full items-center px-7 md:px-14 lg:px-20">
                    <div class="max-w-[430px] p-5 md:p-7">
                        <span class="mb-4 inline-flex w-fit rounded-md bg-amber-600 px-4 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-white shadow-lg">
                            <?php echo e($slide['badge']); ?>

                        </span>
                        <h1 class="max-w-[390px] text-[24px] font-extrabold leading-[1.08] text-white md:text-[36px]">
                            <?php echo e($slide['title_1']); ?>

                            <span class="block text-[18px] text-amber-400 md:text-[22px]"><?php echo e($slide['title_2']); ?></span>
                        </h1>
                        <p class="mt-4 max-w-[330px] text-[13px] leading-6 text-white/85 md:text-[15px]">
                            <?php echo e($slide['description']); ?>

                        </p>
                        <div class="mt-6">
                            <a href="<?php echo e($slide['link']); ?>" class="inline-flex rounded-xl bg-amber-600 px-6 py-3 text-sm font-extrabold uppercase tracking-[0.12em] text-white transition hover:-translate-y-0.5 hover:bg-amber-700 hover:shadow-xl">
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
        ?>

        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <h2 class="home-category-heading text-3xl font-extrabold text-slate-900 md:text-4xl">
                    Shop by <span class="text-amber-600"> Category  </span>
                </h2>
                <p class="mt-2 text-sm text-slate-500"> Fresh cut, pure standards, across every cut and kind. </p>
            </div>
            <a
                href="<?php echo e(route('frontend.categories')); ?>"
                class="inline-flex shrink-0 items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-4 py-2.5 text-[11px] font-extrabold uppercase tracking-[0.14em] text-amber-700 transition hover:border-amber-600 hover:bg-amber-600 hover:text-white sm:px-5"
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
                    $linkParams = ['category' => $item['category_slug']];
                    if ($item['subcategory_slug']) {
                        $linkParams['subcategory'] = $item['subcategory_slug'];
                    }
                ?>

                <a
                    href="<?php echo e(route('frontend.products', $linkParams)); ?>"
                    class="group min-w-[228px] max-w-[228px] overflow-hidden rounded-[18px] border border-slate-200 bg-white shadow-[0_8px_24px_rgba(15,23,43,0.05)] transition-all duration-300 hover:-translate-y-1 hover:border-amber-200 hover:shadow-[0_18px_36px_rgba(217,164,65,0.18)]"
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
                        <?php if($item['tag']): ?>
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-amber-600">
                                <?php echo e($item['tag']); ?>

                            </p>
                        <?php endif; ?>
                        <h3 class="mt-1 text-[15px] font-semibold text-slate-900 transition-colors group-hover:text-amber-700">
                            <?php echo e($item['title']); ?>

                        </h3>
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


<?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($category->is_enquiry_only) continue; ?>
    <?php $categoryProductList = $categoryProducts->get($category->id, collect()); ?>
    <?php if($categoryProductList->isEmpty()) continue; ?>

    <section class="<?php echo e($loop->even ? 'bg-gray-50' : 'bg-white'); ?> pb-2 pt-2 md:py-6">
        <div class="mx-auto max-w-7xl px-4">
            <div class="mb-6 flex items-end justify-between gap-4">
                <div>
                    <?php if($category->tag): ?>
                        <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-amber-600"><?php echo e($category->tag); ?></p>
                    <?php endif; ?>
                    <h2 class="mt-1 text-2xl font-extrabold text-slate-900 md:text-3xl"><?php echo e($category->name); ?></h2>
                    <p class="mt-1 max-w-lg text-xs text-gray-500 md:text-sm">
                        <?php echo e($category->description ?: 'Fresh, quality-checked ' . strtolower($category->name) . ', cut and packed daily.'); ?>

                    </p>
                </div>
                <a href="<?php echo e(route('frontend.products', ['category' => $category->slug])); ?>" class="inline-flex shrink-0 items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-4 py-2.5 text-[11px] font-extrabold uppercase tracking-[0.14em] text-amber-700 transition hover:border-amber-600 hover:bg-amber-600 hover:text-white sm:px-5">
                    See All
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="scrollbar-hide flex gap-3 overflow-x-auto scroll-smooth pb-3 md:gap-5">
                <?php $__currentLoopData = $categoryProductList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="min-w-[240px] max-w-[240px]">
                    <?php echo $__env->make('frontend.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


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
                    <a href="<?php echo e(route('frontend.products')); ?>" class="rounded-xl bg-amber-600 px-5 py-2.5 text-xs font-extrabold uppercase tracking-[0.14em] text-white transition hover:bg-amber-700">
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

        <?php
            $favoriteLabels = ['Most Ordered', 'Top Rated', 'Chef Pick', 'Best For Fry', 'Family Pack', 'Daily Fresh', 'Quick Cook', 'Popular Choice'];
        ?>

        <div id="home-favorites-scroller" class="scrollbar-hide flex gap-4 overflow-x-auto scroll-smooth pb-3">
            <?php $__empty_1 = true; $__currentLoopData = $bestSellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="min-w-[240px] max-w-[240px]">
                    <?php echo $__env->make('frontend.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
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
                <p class="mb-2 text-[11px] font-extrabold uppercase tracking-[0.3em] text-amber-600">Verified Reviews</p>
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
                        <span class="rounded-full bg-amber-50 px-2.5 py-1 text-[9px] font-black uppercase text-amber-700">Verified</span>
                    </div>
                    <p class="mt-5 line-clamp-4 text-sm leading-7 text-slate-600">“<?php echo e($review->comment ?: 'Great freshness, packaging and delivery experience.'); ?>”</p>
                    <div class="mt-auto flex items-center gap-3 pt-5">
                        <span class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-600 text-xs font-black text-white"><?php echo e($reviewInitials ?: 'VC'); ?></span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-black text-slate-900"><?php echo e($reviewName); ?></p>
                            <p class="truncate text-[11px] font-semibold text-slate-400"><?php echo e($review->product?->name ?? 'Porville Order'); ?></p>
                        </div>
                    </div>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if($liveStockCategory && $liveStockProducts->isNotEmpty()): ?>

<section class="bg-gray-50 py-8 md:py-12">
    <div class="mx-auto max-w-7xl px-4">
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.28em] text-amber-600">Enquiry Only &middot; Call to Order</p>
                <h2 class="font-classic mt-1 text-2xl font-bold text-slate-900 md:text-3xl">Farm Fresh Live Stock</h2>
                <p class="mt-1 max-w-lg text-xs text-slate-500">Healthy live farm birds and livestock raised under premium guidelines.</p>
            </div>
            <a href="<?php echo e(route('frontend.products', ['category' => $liveStockCategory->slug])); ?>" class="inline-flex shrink-0 items-center gap-2 rounded-full border border-amber-200 bg-amber-50 px-4 py-2.5 text-[11px] font-extrabold uppercase tracking-[0.14em] text-amber-700 transition hover:border-amber-600 hover:bg-amber-600 hover:text-white sm:px-5">
                See All
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
        <div class="scrollbar-hide flex gap-3 overflow-x-auto scroll-smooth pb-3 md:gap-5">
            <?php $__currentLoopData = $liveStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="min-w-[240px] max-w-[240px]">
                <?php echo $__env->make('frontend.partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\porville-fresh-cut\resources\views/frontend/home.blade.php ENDPATH**/ ?>
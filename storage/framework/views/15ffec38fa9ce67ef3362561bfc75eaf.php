
<?php $__env->startSection('title', 'All Products'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $shopHeroProduct = $products->first(function ($product) {
        $categoryName = strtolower($product->category->name ?? '');
        $hasImage = !empty($product->images[0] ?? null);

        return $hasImage && in_array($categoryName, ['chicken', 'mutton', 'fish', 'seafood', 'eggs']);
    }) ?? $products->first(function ($product) {
        return !empty($product->images[0] ?? null);
    });

    $shopHeroImage = $shopHeroProduct && !empty($shopHeroProduct->images[0] ?? null)
        ? asset('storage/' . $shopHeroProduct->images[0])
        : 'https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=1800&q=80';
?>

<section class="pt-0">
    <div class="relative overflow-hidden bg-[#151819] text-white shadow-[0_24px_70px_rgba(15,23,42,0.22)]">
        <img
            src="<?php echo e($shopHeroImage); ?>"
            alt="All products hero banner"
            class="absolute inset-0 h-full w-full object-cover"
        >
        <div class="absolute inset-0 bg-black/55"></div>
        <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(0,0,0,0.74)_0%,rgba(0,0,0,0.52)_34%,rgba(0,0,0,0.18)_58%,rgba(0,0,0,0.62)_100%)]"></div>

        <div class="relative z-10 mx-auto flex min-h-[300px] max-w-7xl flex-col justify-between gap-8 px-5 py-8 md:min-h-[340px] md:px-8 md:py-10 lg:flex-row lg:items-center lg:px-10">
            <div class="max-w-[620px]">
                <div class="flex items-center gap-2 text-[11px] font-extrabold uppercase tracking-[0.28em] text-white/80">
                    <span class="inline-block h-2 w-2 rounded-full bg-[#9ae16d] shadow-[0_0_16px_rgba(154,225,109,0.7)]"></span>
                    FarmSea Fresh Marketplace
                </div>

                <h1 class="mt-4 text-[34px] font-black leading-[0.95] tracking-[-0.04em] text-white sm:text-[42px] md:text-[64px] lg:text-[74px]">
                    All <span class="italic text-[#9be278]">Products.</span>
                </h1>

                <p class="mt-5 max-w-[520px] text-[16px] leading-8 text-white/82 md:text-[17px]">
                    Sourced fresh from our farms and coastal waters. Browse our complete range of chicken, mutton, fish, seafood and more delivered chilled to your door.
                </p>
            </div>

            <div class="flex w-full flex-col gap-4 text-white sm:w-auto sm:flex-row sm:flex-wrap sm:items-stretch sm:gap-5 lg:justify-end">
                <div class="w-full border-t border-white/15 pt-4 sm:w-auto sm:min-w-[120px] sm:border-l sm:border-t-0 sm:pl-5 sm:pt-0">
                    <div class="text-[46px] font-black leading-none md:text-[56px]"><?php echo e($products->total()); ?>+</div>
                    <div class="mt-2 text-[11px] font-extrabold uppercase tracking-[0.28em] text-white/65">Fresh Products</div>
                </div>
                <div class="w-full border-t border-white/15 pt-4 sm:w-auto sm:min-w-[150px] sm:border-l sm:border-t-0 sm:pl-5 sm:pt-0">
                    <div class="text-[46px] font-black leading-none md:text-[56px]">Daily</div>
                    <div class="mt-2 text-[11px] font-extrabold uppercase tracking-[0.28em] text-white/65">Fresh Sourcing</div>
                </div>
            </div>
        </div>
    </div>

</section>

<?php
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
?>

<div class="max-w-7xl mx-auto px-4 py-8 flex flex-col lg:flex-row gap-8">

    
    <aside class="w-full lg:w-[290px] xl:w-[310px] flex-shrink-0">
        <div class="space-y-5 lg:sticky lg:top-[150px]">
            <form method="GET" action="<?php echo e(route('frontend.products')); ?>" id="filterForm">
                <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-[0_22px_55px_rgba(15,23,42,0.08)]">
                    <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                        <h3 class="text-[13px] font-black uppercase tracking-[0.22em] text-slate-900">Filter Products</h3>
                        <a href="<?php echo e(route('frontend.products')); ?>" class="text-[11px] font-bold text-[#2d72d3] transition hover:text-[#1f5bb4]">Clear All</a>
                    </div>

                    <div class="space-y-6 px-5 py-5">
                        <div>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search cuts, packs, combos..."
                                   data-filter-search
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-green-500 focus:bg-white">
                        </div>

                        <div class="border-b border-slate-100 pb-5">
                            <div class="mb-3 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Category</div>
                            <div class="space-y-1.5">
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex cursor-pointer items-center justify-between rounded-2xl px-3 py-2.5 transition hover:bg-slate-50">
                                        <span class="flex items-center gap-3">
                                            <input type="radio" name="category" value="<?php echo e($cat->slug); ?>"
                                                   <?php echo e(request('category') == $cat->slug ? 'checked' : ''); ?>

                                                   data-auto-submit
                                                   class="h-4 w-4 border-slate-300 text-green-600 focus:ring-green-500">
                                            <span class="text-[13px] font-semibold text-slate-700"><?php echo e($cat->name); ?></span>
                                        </span>
                                        <span class="text-[11px] font-bold text-slate-400"><?php echo e($cat->active_products_count); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="border-b border-slate-100 pb-5">
                            <div class="mb-3 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Sort By</div>
                            <div class="space-y-1.5">
                                <?php $__currentLoopData = $sortOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label class="flex cursor-pointer items-center gap-3 rounded-2xl px-3 py-2.5 transition hover:bg-slate-50">
                                        <input type="radio" name="sort" value="<?php echo e($value); ?>"
                                               <?php echo e(request('sort', 'latest') === $value ? 'checked' : ''); ?>

                                               data-auto-submit
                                               class="h-4 w-4 border-slate-300 text-green-600 focus:ring-green-500">
                                        <span class="text-[13px] font-semibold text-slate-700"><?php echo e($label); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <div class="border-b border-slate-100 pb-5">
                            <div class="mb-4 flex items-center justify-between">
                                <div class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Price Range</div>
                                <span class="rounded-full bg-green-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.16em] text-green-700">Rs <?php echo e($selectedMinPrice); ?> - <span id="priceRangeValue"><?php echo e($selectedMaxPrice); ?></span></span>
                            </div>
                            <input type="range" name="max_price" min="0" max="<?php echo e($sidebarMaxPrice); ?>" value="<?php echo e($selectedMaxPrice); ?>" step="10"
                                   id="sidebarPriceRange"
                                   class="h-2 w-full cursor-pointer appearance-none rounded-full bg-slate-200 accent-green-600">
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Min</label>
                                    <input type="number" name="min_price" min="0" max="<?php echo e($sidebarMaxPrice); ?>" value="<?php echo e($selectedMinPrice); ?>"
                                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 outline-none focus:border-green-500 focus:bg-white">
                                </div>
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Max</label>
                                    <input type="number" min="0" max="<?php echo e($sidebarMaxPrice); ?>" value="<?php echo e($selectedMaxPrice); ?>"
                                           id="sidebarPriceRangeInput"
                                           class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 outline-none focus:border-green-500 focus:bg-white">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="mb-3 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">Pack Weight</div>
                            <div class="grid grid-cols-2 gap-2">
                                <?php $__currentLoopData = $packWeights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $weight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button type="button" class="rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-left text-[12px] font-bold text-slate-600 transition hover:border-green-200 hover:bg-green-50 hover:text-green-700">
                                        <?php echo e($weight); ?>

                                    </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php $__empty_1 = true; $__currentLoopData = $sidebarBestDeals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <a href="<?php echo e(route('frontend.product.show', $deal->slug)); ?>" class="group flex items-center gap-3 rounded-[22px] border border-slate-100 bg-slate-50 p-2.5 transition hover:-translate-y-0.5 hover:border-green-200 hover:bg-white hover:shadow-md">
                            <div class="h-16 w-16 overflow-hidden rounded-2xl bg-slate-200">
                                <?php if($deal->images && count($deal->images)): ?>
                                    <img src="<?php echo e(asset('storage/' . $deal->images[0])); ?>" alt="<?php echo e($deal->name); ?>" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                <?php else: ?>
                                    <div class="flex h-full w-full items-center justify-center text-xl text-slate-400">
                                        <i class="fa-solid fa-drumstick-bite"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="line-clamp-2 text-[12px] font-black leading-5 text-slate-900"><?php echo e($deal->name); ?></p>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-[12px] font-black text-green-700">₹<?php echo e(number_format($deal->price, 0)); ?></span>
                                    <?php if($deal->mrp && $deal->mrp > $deal->price): ?>
                                        <span class="text-[11px] font-bold text-slate-400 line-through">₹<?php echo e(number_format($deal->mrp, 0)); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="rounded-2xl bg-slate-50 px-4 py-4 text-[13px] font-semibold text-slate-500">Deals will appear here once products with offers are available.</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="overflow-hidden rounded-[28px] border border-slate-200 bg-white p-4 shadow-[0_20px_45px_rgba(15,23,42,0.06)]">
                <h4 class="mb-4 text-[11px] font-black uppercase tracking-[0.2em] text-slate-900">New Arrivals</h4>
                <div class="space-y-3">
                    <?php $__currentLoopData = $sidebarNewArrivals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $arrival): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('frontend.product.show', $arrival->slug)); ?>" class="group flex items-center gap-3 rounded-[20px] px-1 py-1 transition hover:bg-slate-50">
                            <div class="h-12 w-12 overflow-hidden rounded-xl bg-slate-200">
                                <?php if($arrival->images && count($arrival->images)): ?>
                                    <img src="<?php echo e(asset('storage/' . $arrival->images[0])); ?>" alt="<?php echo e($arrival->name); ?>" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                <?php else: ?>
                                    <div class="flex h-full w-full items-center justify-center text-sm text-slate-400">
                                        <i class="fa-solid fa-fish"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="line-clamp-2 text-[12px] font-black leading-5 text-slate-900"><?php echo e($arrival->name); ?></p>
                                <p class="mt-1 text-[11px] font-semibold text-slate-400"><?php echo e($arrival->category->name ?? 'Fresh cut'); ?></p>
                            </div>
                            <span class="text-[12px] font-black text-green-700">₹<?php echo e(number_format($arrival->price, 0)); ?></span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="overflow-hidden rounded-[28px] bg-[linear-gradient(160deg,#2f8c43_0%,#1f6f34_100%)] p-5 text-white shadow-[0_20px_45px_rgba(31,111,52,0.22)]">
                <span class="inline-flex rounded-full bg-white/16 px-3 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-white/85">Need Bulk Orders?</span>
                <p class="mt-4 text-[16px] font-black leading-7">Talk to our farm experts for the best cuts & delivery slots.</p>
                <a href="<?php echo e(route('frontend.contact')); ?>" class="mt-5 inline-flex items-center justify-center rounded-2xl bg-white px-4 py-3 text-[12px] font-black uppercase tracking-[0.18em] text-[#1f6f34] transition hover:-translate-y-0.5">
                    Contact Now
                </a>
            </div>

            <div class="overflow-hidden rounded-[28px] bg-[#18213a] p-5 text-white shadow-[0_18px_40px_rgba(15,23,42,0.18)]">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <span class="inline-flex rounded-full bg-white/8 px-3 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#90a5d9]">Flash Deal</span>
                        <div class="mt-4 text-[42px] font-black leading-none text-white">20%</div>
                        <p class="mt-2 text-[11px] font-black uppercase tracking-[0.18em] text-[#8ea0c7]">Product Discount Today</p>
                    </div>
                    <i class="fa-solid fa-bolt text-[34px] text-[#39466d]"></i>
                </div>
                <p class="mt-5 text-[12px] font-bold uppercase tracking-[0.22em] text-[#7d8fbf]">FRESH30</p>
            </div>
        </div>
    </aside>

    
    <div class="flex-1">
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-500 font-semibold"><?php echo e($products->total()); ?> products found</p>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl border border-gray-100 hover:border-green-300 hover:shadow-lg transition overflow-hidden flex flex-col">
                <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>" class="block aspect-square overflow-hidden bg-gray-50 relative">
                    <?php if(in_array($product->id, $newArrivalProductIds ?? [], true)): ?>
                        <span class="absolute left-2 top-2 z-10 rounded-full bg-blue-600 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.12em] text-white shadow">
                            New Arrival
                        </span>
                    <?php endif; ?>
                    <?php if($product->images && count($product->images)): ?>
                        <img src="<?php echo e(asset('storage/'.$product->images[0])); ?>" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-5xl">M</div>
                    <?php endif; ?>
                    <?php if (! ($product->is_active)): ?>
                        <span class="absolute left-2 top-2 rounded bg-red-600 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.12em] text-white shadow">
                            Out of Stock
                        </span>
                    <?php endif; ?>
                    <?php if($product->mrp && $product->mrp > $product->price): ?>
                        <span class="absolute top-2 right-2 bg-red-500 text-white text-[9px] font-bold px-2 py-0.5 rounded">
                            -<?php echo e(round((($product->mrp - $product->price) / $product->mrp) * 100)); ?>%
                        </span>
                    <?php endif; ?>
                </a>
                <div class="p-3 flex flex-col flex-1">
                    <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>"
                       class="text-sm font-bold text-gray-800 hover:text-green-700 leading-snug line-clamp-2"><?php echo e($product->name); ?></a>
                    <p class="text-[10px] text-gray-400 mt-1"><?php echo e($product->category->name ?? ''); ?></p>
                    <div class="flex items-center justify-between mt-auto pt-3">
                        <div>
                            <p class="text-lg font-extrabold text-gray-800">Rs<?php echo e(number_format($product->price, 0)); ?><span class="ml-1 text-xs font-semibold text-gray-400">/<?php echo e($product->unit ?: 'unit'); ?></span></p>
                            <?php if($product->mrp && $product->mrp > $product->price): ?>
                                <p class="text-xs text-gray-400 line-through">Rs<?php echo e(number_format($product->mrp, 0)); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php if($product->is_active): ?>
                            <button onclick="addToCart(<?php echo e($product->id); ?>)"
                                    class="w-9 h-9 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center text-sm transition">
                                <i class="fa-solid fa-cart-shopping"></i>
                            </button>
                        <?php else: ?>
                            <span class="inline-flex rounded-lg bg-red-50 px-3 py-2 text-[10px] font-bold uppercase tracking-[0.12em] text-red-600">
                                Sold Out
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-4 text-center py-16 text-gray-400">
                <i class="fa-solid fa-box-open text-5xl mb-4 block"></i>
                <p class="font-semibold">No products found.</p>
                <a href="<?php echo e(route('frontend.products')); ?>" class="text-blue-600 text-sm mt-2 inline-block hover:underline">Clear filters</a>
            </div>
            <?php endif; ?>
        </div>

        <div class="mt-8"><?php echo e($products->links()); ?></div>
    </div>
</div>

<?php
    $shopTrustCards = [
        [
            'icon' => 'fa-truck-fast',
            'icon_bg' => 'bg-[#eef8ea]',
            'icon_color' => 'text-[#2f8c43]',
            'title' => 'Same Day Delivery',
            'copy' => 'Order before 10 AM for delivery by evening. Cold-chain guaranteed from farm to door.',
            'card' => 'border-[#e5efe0] bg-white text-slate-900',
            'copy_color' => 'text-slate-600',
        ],
        [
            'icon' => 'fa-shield-halved',
            'icon_bg' => 'bg-[#e9f3ff]',
            'icon_color' => 'text-[#256dcc]',
            'title' => 'FSSAI Certified',
            'copy' => 'All products are 100% hygienic and certified by Indian food safety boards.',
            'card' => 'border-[#e3ebf4] bg-white text-slate-900',
            'copy_color' => 'text-slate-600',
        ],
        [
            'icon' => 'fa-snowflake',
            'icon_bg' => 'bg-[#fff4d8]',
            'icon_color' => 'text-[#f3a51d]',
            'title' => 'Cold Chain Packed',
            'copy' => 'Individually packed in food-grade covers with ice-gel packs to preserve freshness.',
            'card' => 'border-[#f3ecda] bg-white text-slate-900',
            'copy_color' => 'text-slate-600',
        ],
        [
            'icon' => 'fa-rotate-left',
            'icon_bg' => 'bg-white/8',
            'icon_color' => 'text-white',
            'title' => 'Freshness Guarantee',
            'copy' => 'Not fresh? We will replace or refund your order within 24 hours. No questions asked.',
            'card' => 'border-[#222c44] bg-[#141c31] text-white',
            'copy_color' => 'text-[#8ea0c7]',
        ],
    ];
?>

<section class="px-4 pb-12 md:px-6 md:pb-16">
    <div class="mx-auto max-w-7xl">
        <div class="grid gap-5 xl:grid-cols-4">
            <?php $__currentLoopData = $shopTrustCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $card): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="group rounded-[28px] border <?php echo e($card['card']); ?> p-7 shadow-[0_20px_50px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1.5 hover:shadow-[0_28px_60px_rgba(15,23,42,0.14)]">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl <?php echo e($card['icon_bg']); ?> <?php echo e($card['icon_color']); ?> text-[24px] shadow-sm transition duration-300 group-hover:scale-110">
                        <i class="fa-solid <?php echo e($card['icon']); ?>"></i>
                    </div>
                    <h3 class="mt-7 text-[18px] font-extrabold leading-tight"><?php echo e($card['title']); ?></h3>
                    <p class="mt-4 max-w-[320px] text-[16px] leading-8 <?php echo e($card['copy_color']); ?>">
                        <?php echo e($card['copy']); ?>

                    </p>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-8 overflow-hidden rounded-[34px] border border-[#e6eddc] bg-[radial-gradient(circle_at_top_left,_rgba(211,241,198,0.35),_rgba(255,255,255,0.98)_42%)] px-6 py-8 shadow-[0_24px_60px_rgba(15,23,42,0.08)] md:px-9 md:py-10 lg:px-10 lg:py-12">
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
                    <a href="<?php echo e(route('frontend.contact')); ?>"
                       class="inline-flex items-center justify-center gap-3 rounded-[18px] bg-[#2f8c43] px-8 py-4 text-[14px] font-black uppercase tracking-[0.18em] text-white shadow-[0_16px_30px_rgba(47,140,67,0.25)] transition hover:-translate-y-0.5 hover:bg-[#247437]">
                        <i class="fa-solid fa-comment-dots text-sm"></i>
                        Talk To Us
                    </a>
                    <a href="<?php echo e(route('frontend.contact')); ?>"
                       class="inline-flex items-center justify-center rounded-[18px] border-2 border-[#243625] bg-white px-8 py-4 text-[14px] font-black uppercase tracking-[0.18em] text-[#152016] transition hover:-translate-y-0.5 hover:border-[#2f8c43] hover:text-[#2f8c43]">
                        Request A Quote
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
(() => {
    const filterForm = document.getElementById('filterForm');
    const range = document.getElementById('sidebarPriceRange');
    const rangeInput = document.getElementById('sidebarPriceRangeInput');
    const rangeValue = document.getElementById('priceRangeValue');
    const autoSubmitInputs = document.querySelectorAll('#filterForm [data-auto-submit]');
    const searchInput = document.querySelector('#filterForm [data-filter-search]');

    if (filterForm) {
        autoSubmitInputs.forEach((input) => {
            input.addEventListener('change', () => filterForm.submit());
        });
    }

    if (filterForm && searchInput) {
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
})();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views/frontend/products.blade.php ENDPATH**/ ?>
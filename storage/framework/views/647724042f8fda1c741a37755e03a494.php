<?php $__env->startSection('title', $product->name); ?>

<?php $__env->startSection('content'); ?>
<?php
    $galleryImages = collect($product->images ?? [])
        ->filter()
        ->values()
        ->all();

    $variantCollection = collect($product->variants ?? [])
        ->filter(fn($variant) => filled($variant['quantity'] ?? null) || filled($variant['selling_price'] ?? null))
        ->values();

    if ($variantCollection->isEmpty()) {
        $variantCollection = collect([
            [
                'quantity' => $product->weight ?: '1',
                'unit' => $product->unit ?: 'unit',
                'piece' => null,
                'mrp' => $product->mrp ?: $product->price,
                'selling_price' => $product->price,
                'save_offer' => ($product->mrp && $product->mrp > $product->price)
                    ? round((($product->mrp - $product->price) / $product->mrp) * 100)
                    : 0,
            ],
        ]);
    }

    $formatVariantLabel = static function (?string $quantity, ?string $unit, ?string $piece = null): string {
        $cleanQuantity = trim((string) $quantity);
        $cleanUnit = trim((string) $unit);
        $cleanPiece = trim((string) $piece);

        if ($cleanQuantity !== '') {
            return trim($cleanQuantity . ' ' . $cleanUnit);
        }

        if ($cleanPiece !== '') {
            return preg_match('/[A-Za-z]/', $cleanPiece)
                ? $cleanPiece
                : trim($cleanPiece . ' ' . $cleanUnit);
        }

        return $cleanUnit !== '' ? $cleanUnit : 'Standard Pack';
    };

    $formatPriceUnit = static function (?string $quantity, ?string $unit, ?string $piece = null): string {
        $cleanQuantity = trim((string) $quantity);
        $cleanUnit = trim((string) $unit);
        $cleanPiece = trim((string) $piece);
        $displayUnit = $cleanUnit !== '' ? ($cleanUnit === 'Pc' ? 'Pcs' : $cleanUnit) : 'unit';

        if ($cleanQuantity !== '') {
            return $cleanQuantity . ' ' . $displayUnit;
        }

        if ($cleanPiece !== '' && ! preg_match('/[A-Za-z]/', $cleanPiece)) {
            return $cleanPiece . ' ' . $displayUnit;
        }

        return $displayUnit;
    };

    $variantPayload = $variantCollection->map(function ($variant) use ($formatPriceUnit, $formatVariantLabel) {
        $baseSellingPrice = (float) ($variant['selling_price'] ?? 0);
        $mrp = (float) ($variant['mrp'] ?? $baseSellingPrice);
        $rawTomorrowPrice = $variant['tomorrow_price'] ?? null;
        $tomorrowAvailable = $rawTomorrowPrice !== null && $rawTomorrowPrice !== '' && (float) $rawTomorrowPrice > 0;
        $todayPrice = \App\Support\ProductDayPricing::sellingPrice($variant, 'today', $baseSellingPrice);
        $tomorrowPrice = \App\Support\ProductDayPricing::sellingPrice($variant, 'tomorrow', $baseSellingPrice);

        return [
            'label' => $formatVariantLabel($variant['quantity'] ?? null, $variant['unit'] ?? null, $variant['piece'] ?? null),
            'sub_label' => $variant['piece'] ?? null,
            'quantity' => trim((string) ($variant['quantity'] ?? '')),
            'unit' => trim((string) ($variant['unit'] ?? '')),
            'price_unit_label' => $formatPriceUnit($variant['quantity'] ?? null, $variant['unit'] ?? null, $variant['piece'] ?? null),
            'selling_price' => $baseSellingPrice,
            'mrp' => $mrp,
            'today_price' => $todayPrice,
            'tomorrow_price' => $tomorrowPrice,
            'tomorrow_available' => $tomorrowAvailable,
            'today_offer' => round(\App\Support\ProductDayPricing::saveOfferPercent($variant, 'today', $mrp)),
            'tomorrow_offer' => round(\App\Support\ProductDayPricing::saveOfferPercent($variant, 'tomorrow', $mrp)),
        ];
    })->values();

    $defaultVariant = $variantPayload->first();
    $defaultPricingDay = 'today';
    $selectedPackLabel = $defaultVariant['label'] ?: (($product->weight ?: '1') . ' ' . ($product->unit ?: 'unit'));
    $defaultDisplayedPrice = (float) ($defaultVariant['today_price'] ?? $defaultVariant['selling_price'] ?? $product->price);
    $selectedSaveAmount = max(($defaultVariant['mrp'] ?? 0) - $defaultDisplayedPrice, 0);
    $hasProductDescription = filled(trim(strip_tags((string) ($product->description ?? ''))));
    $initialDetailTab = $hasProductDescription ? 'description' : 'offers';
    $productOrigin = $product->subcategory->name ?? $product->category->name ?? 'FarmSea Farms';
    $productCategory = $product->category->name ?? 'Fresh Cuts';
    $productTagline = 'Pasture-Raised • Grain-Fed • Air-Chilled';
    $isProductAvailable = (bool) $product->is_active;

    $trustHighlights = [
        ['icon' => 'fa-leaf', 'title' => 'Natural Feed', 'copy' => 'Clean source'],
        ['icon' => 'fa-truck-fast', 'title' => 'Fast Delivery', 'copy' => 'Within 2-4 hrs'],
        ['icon' => 'fa-shield-halved', 'title' => 'FSSAI Certified', 'copy' => '100% hygienic'],
        ['icon' => 'fa-rotate-left', 'title' => 'Easy Returns', 'copy' => 'Within 24 hrs'],
    ];

    $productSpecs = [
        'Origin' => $productOrigin,
        'Processed' => 'Freshly cleaned and chilled',
        'Pack Weight' => $selectedPackLabel,
        'Storage' => '0-4 C. Consume within 24 hrs of opening',
        'Category' => $productCategory,
    ];

    $featureBullets = [
        'Sourced from Karnataka Farms using ethical, sustainable practices',
        'Processed with Air-Chilled technique for peak freshness',
        'Packed in food-grade, leak-proof, eco-friendly packaging with ice-gel',
        'No preservatives • No artificial colours • No hormones or antibiotics',
        'FSSAI licensed processing facility — audited quarterly',
    ];

    $cookingBullets = [
        'Curry / Gravy — ideal for slow-cooked Indian gravies and masala dishes',
        'Grilling / BBQ — marinate 2+ hours, grill on high for best char',
        'Air Fryer — brush with oil, season well, 180°C for 18–22 mins',
        'Biryani / Pulao — pairs beautifully with aged basmati rice',
        'Pressure Cooker — 3–4 whistles for tender, fall-off-bone results',
    ];

    $storageBullets = [
        'Sealed in food-grade vacuum or MAP packaging to extend freshness',
        'Delivered with ice-gel packs in an insulated box to maintain 0–4°C',
        'Refrigerate immediately on arrival • Consume within 24 hrs of opening',
        'Can be frozen for up to 2 months if not using immediately',
    ];

    $nutritionStats = [
        ['label' => 'Calories', 'value' => '165', 'unit' => 'kcal per 100g'],
        ['label' => 'Protein', 'value' => '31g', 'unit' => 'per 100g'],
        ['label' => 'Fat', 'value' => '3.6g', 'unit' => 'per 100g'],
        ['label' => 'Carbs', 'value' => '0g', 'unit' => 'per 100g'],
        ['label' => 'Sodium', 'value' => '74mg', 'unit' => 'per 100g'],
        ['label' => 'Cholesterol', 'value' => '85mg', 'unit' => 'per 100g'],
    ];

    $reviewSummary = [
        'rating' => '4.8',
        'total' => 42,
        'distribution' => [
            ['stars' => 5, 'percent' => 72],
            ['stars' => 4, 'percent' => 25],
            ['stars' => 3, 'percent' => 2],
            ['stars' => 2, 'percent' => 1],
            ['stars' => 1, 'percent' => 0],
        ],
    ];

    $reviewCards = [
        [
            'name' => 'Arjun Kumar',
            'initials' => 'AK',
            'color' => 'bg-[#3b82f6]',
            'date' => '2 Mar 2026',
            'title' => 'Absolutely fresh, zero smell!',
            'text' => 'Arrived well-packed with double ice gel. The chicken was super clean and smelled like nothing — that is how you know it is fresh. Made a curry and the whole family loved it. Will order weekly.',
            'helpful' => 16,
        ],
        [
            'name' => 'Priya Mehta',
            'initials' => 'PM',
            'color' => 'bg-[#2f8c43]',
            'date' => '28 Feb 2026',
            'title' => 'Consistent quality every time',
            'text' => 'Same-day delivery was on point — arrived in under 2 hours. Pieces are uniform in size which makes cooking easier. Been ordering for 2 months now, quality never drops.',
            'helpful' => 12,
        ],
        [
            'name' => 'Suresh Reddy',
            'initials' => 'SR',
            'color' => 'bg-[#7c3aed]',
            'date' => '21 Feb 2026',
            'title' => 'Good quality, great packaging',
            'text' => 'Really good quality. Packaging was leak-proof and sturdy. One piece was slightly smaller than the others but overall very happy. 4 stars only because I expected a bit more quantity for the price.',
            'helpful' => 9,
        ],
        [
            'name' => 'Fatima Shaikh',
            'initials' => 'FS',
            'color' => 'bg-[#dc2626]',
            'date' => '18 Feb 2026',
            'title' => 'FarmSea is now my go-to',
            'text' => 'I used to drive to the local butcher but FarmSea has completely replaced that. Freshly cleaned, and delivered right to my door. The halal certification is a big plus for my family.',
            'helpful' => 18,
        ],
    ];

    $offerBullets = [
        'Use code FRESH30 on eligible orders to unlock extra savings.',
        'Free same day delivery on selected premium cuts above cart minimum.',
        'Special combo pricing available on family packs and bulk orders.',
        'Festive and weekend offers may vary by city and delivery slot.',
    ];

    $offerCards = [
        [
            'icon' => 'fa-tag',
            'icon_bg' => 'bg-[#e9f7ec]',
            'icon_color' => 'text-[#2f8c43]',
            'title' => '20% Off on First Order',
            'text' => 'New customers get flat 20% off on their first FarmSea order. No minimum order value.',
            'code' => 'FARMNEW20',
            'code_bg' => 'bg-[#edf8ef]',
            'code_text' => 'text-[#2f8c43]',
        ],
        [
            'icon' => 'fa-truck-fast',
            'icon_bg' => 'bg-[#ebf4ff]',
            'icon_color' => 'text-[#2d72d3]',
            'title' => 'Free Delivery on ₹499+',
            'text' => 'Get free same-day delivery on all orders above ₹499. Valid on all products.',
            'code' => null,
            'code_bg' => 'bg-[#ebf4ff]',
            'code_text' => 'text-[#2d72d3]',
        ],
        [
            'icon' => 'fa-credit-card',
            'icon_bg' => 'bg-[#fff3e6]',
            'icon_color' => 'text-[#d97706]',
            'title' => '5% Cashback with HDFC',
            'text' => 'Get 5% cashback (up to ₹150) on HDFC credit/debit cards. Valid on all orders.',
            'code' => 'HDFCC5',
            'code_bg' => 'bg-[#edf8ef]',
            'code_text' => 'text-[#2f8c43]',
        ],
        [
            'icon' => 'fa-rotate',
            'icon_bg' => 'bg-[#f3ebff]',
            'icon_color' => 'text-[#9333ea]',
            'title' => 'Refer & Earn ₹100',
            'text' => 'Refer a friend — they get ₹100 off their first order, and so do you!',
            'code' => 'REFER100',
            'code_bg' => 'bg-[#edf8ef]',
            'code_text' => 'text-[#2f8c43]',
        ],
    ];

    $offerCards = collect($frontendOfferCards ?? [])->filter()->values()->all();

    $deliveryInfoRows = [
        'Order before 10 AM for guaranteed same-day delivery',
        'Standard delivery: 3–6 hours from order confirmation',
        'Available 7 days a week, including public holidays',
        'Free delivery on orders above ₹499',
        'Cold-chain maintained throughout — 0–4°C delivery',
    ];

    $returnsInfoRows = [
        'Not fresh on arrival? Raise a complaint within 2 hours',
        'We will arrange a free replacement or full refund',
        'Share a photo via WhatsApp / email to our support team',
        'Opened packets eligible for return if quality is poor',
        'Refunds processed within 24–48 hours',
    ];
?>

<div class="mx-auto max-w-7xl px-4 py-8 md:px-6 md:py-8">
    <div class="mb-5 hidden flex-wrap items-center justify-between gap-3 text-[11px] font-semibold text-slate-400 md:flex">
        <nav class="flex items-center gap-2">
            <a href="<?php echo e(route('frontend.home')); ?>" class="transition hover:text-green-700">Home</a>
            <i class="fa-solid fa-angle-right text-[9px]"></i>
            <a href="<?php echo e(route('frontend.products')); ?>" class="transition hover:text-green-700">Products</a>
            <i class="fa-solid fa-angle-right text-[9px]"></i>
            <?php if($product->category): ?>
                <a href="<?php echo e(route('frontend.products', ['category' => $product->category->slug])); ?>" class="transition hover:text-green-700"><?php echo e($product->category->name); ?></a>
                <i class="fa-solid fa-angle-right text-[9px]"></i>
            <?php endif; ?>
            <span class="text-slate-600"><?php echo e($product->name); ?></span>
        </nav>

        <a href="<?php echo e(route('frontend.products')); ?>" class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-[11px] font-bold text-slate-600 shadow-sm transition hover:border-green-200 hover:text-green-700">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            Back
        </a>
    </div>

    <div class="grid gap-8 lg:grid-cols-[minmax(0,1.02fr)_minmax(0,0.98fr)] lg:items-start">
        <div>
            <div class="overflow-hidden rounded-[28px] border border-[#dce5d9] bg-white shadow-[0_22px_60px_rgba(15,23,42,0.08)]">
                <div class="relative aspect-[1/0.94] overflow-hidden bg-[#f4f5ef]">
                    <?php if(in_array($product->id, $newArrivalProductIds ?? [], true)): ?>
                        <span class="absolute right-4 top-4 z-20 inline-flex items-center rounded-full bg-blue-600 px-3 py-1 text-[10px] font-black uppercase tracking-[0.16em] text-white shadow-lg">
                            New Arrival
                        </span>
                    <?php endif; ?>
                    <?php if(count($galleryImages)): ?>
                        <img
                            src="<?php echo e(asset('storage/' . $galleryImages[0])); ?>"
                            alt="<?php echo e($product->name); ?>"
                            id="detailMainImage"
                            class="h-full w-full object-cover transition duration-500"
                        >
                    <?php else: ?>
                        <div class="flex h-full w-full items-center justify-center text-7xl text-slate-300">
                            <i class="fa-solid fa-drumstick-bite"></i>
                        </div>
                    <?php endif; ?>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/10 via-transparent to-black/5"></div>

                    <?php if(count($galleryImages) > 1): ?>
                        <button type="button" onclick="moveGallery(-1)"
                                class="absolute left-4 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-md transition hover:bg-white"
                                aria-label="Previous image">
                            <i class="fa-solid fa-chevron-left text-xs"></i>
                        </button>
                        <button type="button" onclick="moveGallery(1)"
                                class="absolute right-4 top-1/2 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-md transition hover:bg-white"
                                aria-label="Next image">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </button>
                    <?php endif; ?>

                    <div class="absolute bottom-4 right-4 flex flex-col gap-2">
                        <button type="button" id="detailZoomOutButton" onclick="zoomProductImage(-0.25)"
                                class="hidden h-11 w-11 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-md transition hover:bg-white"
                                aria-label="Zoom out image">
                            <i class="fa-solid fa-magnifying-glass-minus text-sm"></i>
                        </button>
                        <button type="button" onclick="zoomProductImage(0.25)"
                            class="flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-slate-700 shadow-md transition hover:bg-white"
                            aria-label="Zoom image">
                            <i class="fa-solid fa-magnifying-glass-plus text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <?php if(count($galleryImages) > 1): ?>
                <div class="mt-5 px-2 md:px-4">
                    <div class="mb-4 flex justify-center gap-2">
                        <?php $__currentLoopData = $galleryImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button
                                type="button"
                                onclick="showGalleryImage(<?php echo e($index); ?>)"
                                id="detailDot<?php echo e($index); ?>"
                                class="detail-dot <?php echo e($index === 0 ? 'is-active w-7 bg-[#2f8c43]' : 'w-2.5 bg-[#cfd7d1]'); ?> h-2.5 rounded-full transition-all duration-200"
                                aria-label="Go to image <?php echo e($index + 1); ?>"
                            ></button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="flex flex-wrap justify-start gap-4 md:gap-5">
                        <?php $__currentLoopData = $galleryImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button
                                type="button"
                                onclick="showGalleryImage(<?php echo e($index); ?>)"
                                id="detailThumb<?php echo e($index); ?>"
                                class="detail-thumb <?php echo e($index === 0 ? 'is-active' : ''); ?> h-24 w-24 overflow-hidden rounded-[24px] border-2 border-transparent bg-white p-1 shadow-[0_10px_30px_rgba(15,23,42,0.08)] transition hover:-translate-y-0.5 md:h-28 md:w-28"
                            >
                                <img src="<?php echo e(asset('storage/' . $image)); ?>" alt="<?php echo e($product->name); ?> thumbnail <?php echo e($index + 1); ?>" class="h-full w-full rounded-[20px] object-cover">
                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="space-y-4">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="mb-2 flex flex-wrap items-center gap-2">
                        <span class="inline-flex rounded-md bg-[#e9f4ea] px-3 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-[#2f8c43]">
                            Best Seller
                        </span>
                    </div>
                    <h1 class="text-[26px] font-black leading-[1.06] tracking-[-0.02em] text-slate-900 md:text-[32px]">
                        <?php echo e($product->name); ?>

                    </h1>
                    <p class="mt-2 text-[12px] font-semibold tracking-[0.01em] text-slate-400">
                        <?php echo e($productTagline); ?>

                    </p>
                    <?php if($hasProductDescription): ?>
                        <p class="mt-3 max-w-[650px] text-[14px] leading-7 text-slate-500 md:text-[15px]">
                            <?php echo e(\Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags((string) $product->description))), 118)); ?>

                        </p>
                    <?php endif; ?>
                </div>

                <div class="hidden items-center gap-2 sm:flex">
                    <button type="button" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-green-200 hover:text-green-700">
                        <i class="fa-regular fa-heart text-sm"></i>
                    </button>
                    <button type="button" class="flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-green-200 hover:text-green-700">
                        <i class="fa-solid fa-share-nodes text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="rounded-[24px] border border-[#bce8c3] bg-[#f2fbf3] p-5 shadow-[0_18px_45px_rgba(47,140,67,0.08)]">
                <div class="mb-4 flex flex-wrap items-center gap-2">
                    <span class="text-[11px] font-black uppercase tracking-[0.18em] text-slate-500">Choose Day</span>
                    <button type="button" data-pricing-day="today" class="pricing-day-button is-active rounded-full border border-green-500 bg-white px-3 py-1.5 text-[11px] font-black uppercase tracking-[0.14em] text-green-700 shadow-sm transition hover:border-green-400">
                        Today
                    </button>
                    <button type="button" data-pricing-day="tomorrow" class="pricing-day-button rounded-full border border-transparent bg-white/70 px-3 py-1.5 text-[11px] font-black uppercase tracking-[0.14em] text-slate-500 transition hover:border-green-200 hover:text-green-700">
                        Tomorrow
                    </button>
                </div>
                <div class="flex flex-wrap items-end gap-3">
                    <span class="text-[40px] font-black leading-none text-slate-900 md:text-[46px]">
                        Rs<span id="detailCurrentPrice"><?php echo e(number_format($defaultDisplayedPrice, 0)); ?></span>
                        <span id="detailCurrentPriceUnit" class="ml-1 text-[14px] font-bold text-slate-400 md:text-[16px]"><?php echo e($defaultVariant['price_unit_label'] ?? $formatPriceUnit($product->weight ?? null, $product->unit ?? null)); ?></span>
                    </span>
                    <span id="detailMrpWrap" class="<?php echo e(($defaultVariant['mrp'] ?? 0) > $defaultDisplayedPrice ? '' : 'hidden'); ?> flex items-center gap-2">
                        <span id="detailMrp" class="text-[16px] font-bold text-slate-400 line-through">
                            Rs<?php echo e(number_format($defaultVariant['mrp'] ?? $product->mrp, 0)); ?>

                        </span>
                        <span id="detailOffer" class="rounded-full bg-[#ff6d5e] px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.16em] text-white">
                            -<?php echo e((int) ($defaultVariant['today_offer'] ?? 0)); ?>% off
                        </span>
                    </span>
                </div>
                <div id="detailSaveRow" class="<?php echo e($selectedSaveAmount > 0 ? '' : 'hidden'); ?> mt-3 inline-flex items-center gap-1 rounded-xl border border-[#8bd39a] bg-[#dff6e3] px-3 py-1.5 text-[11px] font-black text-[#2f8c43]">
                    <span>You save Rs</span>
                    <span id="detailSaveAmount"><?php echo e(number_format($selectedSaveAmount, 0)); ?></span>
                    <span>on this order</span>
                </div>
            </div>

            <div class="flex flex-wrap gap-2">
                <span class="inline-flex items-center gap-2 rounded-xl px-3 py-2 text-[11px] font-black <?php echo e($isProductAvailable ? 'border border-green-200 bg-green-50 text-green-700' : 'border border-red-200 bg-red-50 text-red-600'); ?>">
                    <i class="fa-solid <?php echo e($isProductAvailable ? 'fa-circle-check' : 'fa-ban'); ?> text-[10px]"></i>
                    <?php echo e($isProductAvailable ? 'In Stock' : 'Out of Stock'); ?>

                </span>
                <span class="inline-flex items-center gap-2 rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-[11px] font-black text-amber-700">
                    <i class="fa-solid fa-snowflake text-[10px]"></i>
                    Fresh Guaranteed
                </span>
            </div>

            <div>
                <div class="mb-3 text-[12px] font-black uppercase tracking-[0.18em] text-slate-500">Pack Size</div>
                <div class="flex flex-wrap gap-3">
                    <?php $__currentLoopData = $variantPayload; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <button
                            type="button"
                            id="variant-btn-<?php echo e($index); ?>"
                            onclick="selectVariant(<?php echo e($index); ?>)"
                            class="variant-card <?php echo e($index === 0 ? 'is-active' : ''); ?> min-w-[84px] rounded-[16px] border border-slate-200 bg-white px-3 py-3 text-left shadow-sm transition hover:border-green-200"
                        >
                            <div class="text-[11px] font-black text-slate-800"><?php echo e($variant['label'] ?: 'Standard Pack'); ?></div>
                            <div class="mt-1 text-[10px] font-semibold text-slate-400" data-variant-day-price="<?php echo e($index); ?>">₹<?php echo e(number_format($variant['today_price'], 0)); ?></div>
                        </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="flex flex-wrap items-stretch gap-3">
                <?php if($isProductAvailable): ?>
                    <div class="flex items-center overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <button type="button" onclick="changeQty(-1)" class="flex h-12 w-11 items-center justify-center text-slate-500 transition hover:bg-slate-50 hover:text-slate-800">-</button>
                        <span id="qty-display" class="flex h-12 min-w-[48px] items-center justify-center border-x border-slate-200 px-3 text-[15px] font-black text-slate-800">1</span>
                        <button type="button" onclick="changeQty(1)" class="flex h-12 w-11 items-center justify-center text-slate-500 transition hover:bg-slate-50 hover:text-slate-800">+</button>
                    </div>
                <?php endif; ?>

                <?php if($isProductAvailable): ?>
                    <button type="button" onclick="addToCartWithQty(<?php echo e($product->id); ?>)"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#1f9d47] px-5 py-3 text-[13px] font-black uppercase tracking-[0.14em] text-white shadow-[0_14px_30px_rgba(31,157,71,0.22)] transition hover:-translate-y-0.5 hover:bg-[#18823a]">
                        <i class="fa-solid fa-cart-shopping text-[12px]"></i>
                        Add To Cart
                    </button>

                    <button type="button" onclick="buyNowWithQty(<?php echo e($product->id); ?>)"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#1f5ea8] px-5 py-3 text-[13px] font-black uppercase tracking-[0.14em] text-white shadow-[0_14px_30px_rgba(31,94,168,0.24)] transition hover:-translate-y-0.5 hover:bg-[#174c89]">
                        <i class="fa-solid fa-bolt text-[12px]"></i>
                        Buy Now
                    </button>
                <?php else: ?>
                    <div class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl border border-red-200 bg-red-50 px-5 py-3 text-[13px] font-black uppercase tracking-[0.14em] text-red-600">
                        <i class="fa-solid fa-ban text-[12px]"></i>
                        Out Of Stock
                    </div>
                <?php endif; ?>

                <button type="button"
                        class="hidden h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-green-200 hover:text-green-700 sm:flex">
                    <i class="fa-regular fa-heart"></i>
                </button>
            </div>

            <?php echo $__env->make('frontend.partials.similar-products', ['wrapperClass' => 'mt-3 md:hidden'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <?php $__currentLoopData = $trustHighlights; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $highlight): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4 text-center shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-[#f2fbf3] text-[#2f8c43]">
                            <i class="fa-solid <?php echo e($highlight['icon']); ?>"></i>
                        </div>
                        <div class="mt-3 text-[12px] font-black text-slate-800"><?php echo e($highlight['title']); ?></div>
                        <div class="mt-1 text-[10px] font-semibold text-slate-400"><?php echo e($highlight['copy']); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-sm">
                <?php $__currentLoopData = $productSpecs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="grid grid-cols-[140px_minmax(0,1fr)] gap-4 border-b border-slate-100 px-4 py-3 last:border-b-0">
                        <div class="text-[11px] font-black uppercase tracking-[0.16em] text-slate-400"><?php echo e($label); ?></div>
                        <div class="text-[13px] font-semibold text-slate-700"><?php echo e($value); ?></div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <div class="mt-10 overflow-x-auto">
        <div class="inline-flex min-w-full gap-1.5 rounded-[18px] border border-slate-200/90 bg-[#f7f9f7] p-2 shadow-[0_10px_25px_rgba(15,23,42,0.05)]">
            <?php if($hasProductDescription): ?>
                <button type="button" data-detail-tab="description" class="detail-tab-button <?php echo e($initialDetailTab === 'description' ? 'is-active bg-white border-slate-200 shadow-sm text-[#2f8c43]' : 'border-transparent text-slate-500'); ?> inline-flex items-center gap-2 rounded-[12px] border px-4 py-2.5 text-[12px] font-black transition hover:bg-white hover:text-slate-800">
                    <i class="fa-solid fa-bars-staggered text-[11px]"></i>
                    Description
                </button>
            <?php endif; ?>
            <button type="button" data-detail-tab="offers" class="detail-tab-button <?php echo e($initialDetailTab === 'offers' ? 'is-active bg-white border-slate-200 shadow-sm text-[#2f8c43]' : 'border-transparent text-slate-500'); ?> inline-flex items-center gap-2 rounded-[12px] border px-4 py-2.5 text-[12px] font-black transition hover:bg-white hover:text-slate-800">
                <i class="fa-solid fa-tag text-[11px]"></i>
                Offers
            </button>
        </div>
    </div>

    <div class="mt-6 overflow-hidden rounded-[28px] border border-slate-200 bg-[#fbfcfa] shadow-[0_22px_55px_rgba(15,23,42,0.06)]">
        <?php if($hasProductDescription): ?>
            <div id="detail-tab-description" data-detail-panel="description" class="detail-tab-panel <?php echo e($initialDetailTab !== 'description' ? 'hidden' : ''); ?>">
                <section id="about-product" class="px-5 py-6 md:px-7">
                    <h2 class="text-[18px] font-black text-slate-900">About This Product</h2>
                    <div class="mt-3 max-w-[980px] text-[14px] leading-8 text-slate-600">
                        <?php echo nl2br(e($product->description)); ?>

                    </div>
                </section>
            </div>
        <?php endif; ?>

        <section data-detail-panel="offers" class="detail-tab-panel <?php echo e($initialDetailTab !== 'offers' ? 'hidden' : ''); ?> px-5 py-6 md:px-7">
            <div class="overflow-hidden rounded-[26px] border border-[#d7e6d7] bg-[radial-gradient(circle_at_top_left,_rgba(229,245,232,0.9),_rgba(255,255,255,1)_55%)]">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-[#e3ece3] px-5 py-5 md:px-6">
                    <div>
                        <p class="text-[11px] font-black uppercase tracking-[0.2em] text-[#2f8c43]">Admin Offers</p>
                        <h3 class="mt-1 text-[20px] font-black text-slate-900">Fresh deals on this product page</h3>
                    </div>
                    <div class="inline-flex items-center rounded-full bg-white/90 px-4 py-2 text-[11px] font-black uppercase tracking-[0.18em] text-slate-500 shadow-sm">
                        <?php echo e(count($offerCards)); ?> active offers
                    </div>
                </div>

                <?php if(count($offerCards)): ?>
                    <div class="grid gap-4 p-5 md:grid-cols-2 md:p-6">
                        <?php $__currentLoopData = $offerCards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $offer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <article class="group relative overflow-hidden rounded-[24px] border border-white/70 bg-white p-5 shadow-[0_18px_40px_rgba(15,23,42,0.08)] transition duration-300 hover:-translate-y-1 hover:shadow-[0_24px_50px_rgba(15,23,42,0.12)]">
                                <div class="absolute right-0 top-0 h-24 w-24 rounded-bl-[36px] bg-gradient-to-br from-slate-100/80 via-transparent to-transparent"></div>

                                <div class="relative flex items-start gap-4">
                                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl <?php echo e($offer['icon_bg']); ?> <?php echo e($offer['icon_color']); ?> shadow-sm">
                                        <i class="fa-solid <?php echo e($offer['icon']); ?> text-[16px]"></i>
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.18em] text-slate-500">
                                                <?php echo e($offer['badge'] ?? 'Live Offer'); ?>

                                            </span>
                                            <?php if(!empty($offer['expires_at'])): ?>
                                                <span class="inline-flex items-center gap-1 rounded-full bg-[#fff7e8] px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.14em] text-[#b66a00]">
                                                    <i class="fa-regular fa-clock text-[9px]"></i>
                                                    Ends <?php echo e($offer['expires_at']); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <h4 class="mt-3 text-[17px] font-black leading-6 text-slate-900"><?php echo e($offer['title']); ?></h4>
                                        <p class="mt-2 text-[13px] font-semibold leading-6 text-slate-500"><?php echo e($offer['text']); ?></p>

                                        <div class="mt-4 flex flex-wrap items-center gap-2">
                                            <?php if($offer['code']): ?>
                                                <div class="inline-flex items-center rounded-xl border border-dashed border-[#2f8c43] <?php echo e($offer['code_bg']); ?> px-3 py-2 text-[11px] font-black uppercase tracking-[0.18em] <?php echo e($offer['code_text']); ?>">
                                                    <i class="fa-solid fa-ticket mr-2 text-[10px]"></i>
                                                    <?php echo e($offer['code']); ?>

                                                </div>
                                            <?php endif; ?>

                                            <?php if(!empty($offer['min_order_amount'])): ?>
                                                <div class="inline-flex items-center rounded-xl bg-slate-100 px-3 py-2 text-[11px] font-black uppercase tracking-[0.14em] text-slate-600">
                                                    Min order Rs<?php echo e(number_format((float) $offer['min_order_amount'], 0)); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="px-5 py-10 md:px-6">
                        <div class="rounded-[24px] border border-dashed border-[#cfe1d0] bg-white/80 px-6 py-10 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-[#edf8ef] text-[#2f8c43]">
                                <i class="fa-solid fa-tags text-lg"></i>
                            </div>
                            <h4 class="mt-4 text-[18px] font-black text-slate-900">No live offers right now</h4>
                            <p class="mx-auto mt-2 max-w-[420px] text-[13px] font-semibold leading-6 text-slate-500">
                                Admin se naya offer add hote hi yahin par automatically show hoga.
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php if(false): ?>
        <section data-detail-panel="delivery" class="detail-tab-panel hidden px-5 py-6 md:px-7">
            <div class="grid gap-4 lg:grid-cols-2">
                <div class="overflow-hidden rounded-[20px] border border-slate-200 bg-white shadow-[0_14px_30px_rgba(15,23,42,0.05)]">
                    <div class="flex items-center gap-2 border-b border-slate-100 px-4 py-4">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#edf8ef] text-[#2f8c43]">
                            <i class="fa-solid fa-truck-fast text-[12px]"></i>
                        </span>
                        <h4 class="text-[14px] font-black text-slate-900">Delivery</h4>
                    </div>
                    <div class="bg-[#fdfefd]">
                        <?php $__currentLoopData = $deliveryInfoRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-b border-slate-100 px-4 py-3 text-[13px] font-semibold leading-6 text-slate-600 last:border-b-0">
                                <?php echo preg_replace('/(10 AM|same-day delivery|3–6 hours|7 days a week|above ₹499|0–4°C delivery)/', '<strong class="font-black text-slate-900">$1</strong>', e($row)); ?>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="overflow-hidden rounded-[20px] border border-slate-200 bg-white shadow-[0_14px_30px_rgba(15,23,42,0.05)]">
                    <div class="flex items-center gap-2 border-b border-slate-100 px-4 py-4">
                        <span class="flex h-8 w-8 items-center justify-center rounded-xl bg-[#fff1e8] text-[#f97316]">
                            <i class="fa-solid fa-rotate-left text-[12px]"></i>
                        </span>
                        <h4 class="text-[14px] font-black text-slate-900">Returns &amp; Guarantee</h4>
                    </div>
                    <div class="bg-[#fdfefd]">
                        <?php $__currentLoopData = $returnsInfoRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-b border-slate-100 px-4 py-3 text-[13px] font-semibold leading-6 text-slate-600 last:border-b-0">
                                <?php echo preg_replace('/(within 2 hours|free replacement or full refund|24–48 hours)/', '<strong class="font-black text-slate-900">$1</strong>', e($row)); ?>

                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
    </div>

    <?php echo $__env->make('frontend.partials.similar-products', ['wrapperClass' => 'mt-10 hidden md:block'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
const detailGalleryImages = <?php echo json_encode(collect($galleryImages)->map(fn($image) => asset('storage/' . $image))->values(), 15, 512) ?>;
const detailVariants = <?php echo json_encode($variantPayload, 15, 512) ?>;

let currentQty = 1;
let selectedVariant = 0;
let selectedPricingDay = '<?php echo e($defaultPricingDay); ?>';
let galleryIndex = 0;
let galleryZoomLevel = 1;

function updateQtyDisplay() {
    const qtyDisplay = document.getElementById('qty-display');
    if (qtyDisplay) {
        qtyDisplay.textContent = currentQty;
    }
}

function changeQty(delta) {
    currentQty = Math.max(1, currentQty + delta);
    updateQtyDisplay();
}

function showGalleryImage(index) {
    if (!detailGalleryImages.length) return;

    galleryIndex = (index + detailGalleryImages.length) % detailGalleryImages.length;

    const mainImage = document.getElementById('detailMainImage');
    if (mainImage) {
        mainImage.src = detailGalleryImages[galleryIndex];
    }
    resetProductImageZoom();

    document.querySelectorAll('.detail-thumb').forEach((thumb, thumbIndex) => {
        thumb.classList.toggle('is-active', thumbIndex === galleryIndex);
        thumb.classList.toggle('border-[#2f8c43]', thumbIndex === galleryIndex);
        thumb.classList.toggle('shadow-[0_10px_25px_rgba(47,140,67,0.15)]', thumbIndex === galleryIndex);
    });

    document.querySelectorAll('.detail-dot').forEach((dot, dotIndex) => {
        const isActive = dotIndex === galleryIndex;
        dot.classList.toggle('is-active', isActive);
        dot.classList.toggle('w-7', isActive);
        dot.classList.toggle('bg-[#2f8c43]', isActive);
        dot.classList.toggle('w-2.5', !isActive);
        dot.classList.toggle('bg-[#cfd7d1]', !isActive);
    });
}

function moveGallery(direction) {
    showGalleryImage(galleryIndex + direction);
}

function zoomProductImage(step) {
    const mainImage = document.getElementById('detailMainImage');
    if (!mainImage) return;

    galleryZoomLevel = Math.min(2.5, Math.max(1, galleryZoomLevel + step));
    applyProductImageZoom();
}

function resetProductImageZoom() {
    galleryZoomLevel = 1;
    applyProductImageZoom();
}

function applyProductImageZoom() {
    const mainImage = document.getElementById('detailMainImage');
    const zoomOutButton = document.getElementById('detailZoomOutButton');

    if (mainImage) {
        mainImage.style.transform = `scale(${galleryZoomLevel})`;
        mainImage.style.transformOrigin = 'center center';
        mainImage.classList.toggle('cursor-zoom-in', galleryZoomLevel === 1);
        mainImage.classList.toggle('cursor-zoom-out', galleryZoomLevel > 1);
    }

    if (zoomOutButton) {
        zoomOutButton.classList.toggle('hidden', galleryZoomLevel <= 1);
        zoomOutButton.classList.toggle('flex', galleryZoomLevel > 1);
    }
}

function activateDetailTab(tabName) {
    document.querySelectorAll('[data-detail-tab]').forEach((button) => {
        const isActive = button.dataset.detailTab === tabName;
        button.classList.toggle('is-active', isActive);
        button.classList.toggle('bg-white', isActive);
        button.classList.toggle('border-slate-200', isActive);
        button.classList.toggle('shadow-sm', isActive);
        button.classList.toggle('text-[#2f8c43]', isActive);
        button.classList.toggle('text-slate-500', !isActive);
        button.classList.toggle('border-transparent', !isActive);
    });

    document.querySelectorAll('[data-detail-panel]').forEach((panel) => {
        panel.classList.toggle('hidden', panel.dataset.detailPanel !== tabName);
    });
}

function getVariantDayPrice(variant, pricingDay) {
    if (!variant) return 0;

    const dayPrice = pricingDay === 'tomorrow' ? variant.tomorrow_price : variant.today_price;

    if (dayPrice !== null && dayPrice !== undefined && dayPrice !== '') {
        return Number(dayPrice);
    }

    return Number(variant.selling_price) || 0;
}

function isTomorrowAvailable(variant) {
    if (!variant) return false;

    return variant.tomorrow_available === true;
}

function syncPricingDayButtons() {
    const variant = detailVariants[selectedVariant];
    const tomorrowButton = document.querySelector('[data-pricing-day="tomorrow"]');
    const tomorrowAvailable = isTomorrowAvailable(variant);

    if (tomorrowButton) {
        tomorrowButton.classList.toggle('hidden', !tomorrowAvailable);
    }

    if (!tomorrowAvailable && selectedPricingDay === 'tomorrow') {
        selectedPricingDay = 'today';
    }

    document.querySelectorAll('[data-pricing-day]').forEach((button) => {
        const isActive = button.dataset.pricingDay === selectedPricingDay;
        button.classList.toggle('is-active', isActive);
        button.classList.toggle('border-green-500', isActive);
        button.classList.toggle('bg-white', isActive);
        button.classList.toggle('text-green-700', isActive);
        button.classList.toggle('shadow-sm', isActive);
        button.classList.toggle('border-transparent', !isActive);
        button.classList.toggle('bg-white/70', !isActive);
        button.classList.toggle('text-slate-500', !isActive);
    });
}

function getVariantDayOffer(variant, pricingDay) {
    if (!variant) return 0;

    return Number(pricingDay === 'tomorrow' ? variant.tomorrow_offer : variant.today_offer) || 0;
}

function refreshVariantDayPrices() {
    detailVariants.forEach((variant, index) => {
        const priceNode = document.querySelector(`[data-variant-day-price="${index}"]`);

        if (priceNode) {
            priceNode.textContent = '₹' + Math.round(getVariantDayPrice(variant, selectedPricingDay));
        }
    });
}

function selectPricingDay(pricingDay) {
    selectedPricingDay = pricingDay === 'tomorrow' ? 'tomorrow' : 'today';
    syncPricingDayButtons();

    refreshVariantDayPrices();
    selectVariant(selectedVariant);
}

function selectVariant(index) {
    selectedVariant = index;
    const variant = detailVariants[index];
    if (!variant) return;
    syncPricingDayButtons();

    document.querySelectorAll('.variant-card').forEach((card, cardIndex) => {
        const isActive = cardIndex === index;
        card.classList.toggle('is-active', isActive);
        card.classList.toggle('border-green-500', isActive);
        card.classList.toggle('bg-[#f2fbf3]', isActive);
        card.classList.toggle('shadow-[0_14px_28px_rgba(47,140,67,0.12)]', isActive);
    });

    const currentPrice = document.getElementById('detailCurrentPrice');
    const currentPriceUnit = document.getElementById('detailCurrentPriceUnit');
    const mrpWrap = document.getElementById('detailMrpWrap');
    const mrp = document.getElementById('detailMrp');
    const offer = document.getElementById('detailOffer');
    const saveRow = document.getElementById('detailSaveRow');
    const saveAmount = document.getElementById('detailSaveAmount');

    const activePrice = getVariantDayPrice(variant, selectedPricingDay);
    const activeOffer = getVariantDayOffer(variant, selectedPricingDay);

    if (currentPrice) currentPrice.textContent = Math.round(activePrice || 0);
    if (currentPriceUnit) currentPriceUnit.textContent = variant.price_unit_label || '<?php echo e($product->display_pack_label); ?>';

    const canShowDiscount = Number(variant.mrp || 0) > Number(activePrice || 0);
    const saveValue = Math.max(Number(variant.mrp || 0) - Number(activePrice || 0), 0);

    if (mrp) mrp.textContent = 'Rs' + Math.round(variant.mrp || 0);
    if (offer) offer.textContent = '-' + Math.round(activeOffer || 0) + '% off';
    if (saveAmount) saveAmount.textContent = Math.round(saveValue);

    if (mrpWrap) mrpWrap.classList.toggle('hidden', !canShowDiscount);
    if (saveRow) saveRow.classList.toggle('hidden', !canShowDiscount);
}

function sendCartRequest(productId, redirectToCheckout = false) {
    fetch('<?php echo e(route("frontend.cart.add")); ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity: currentQty, variant_index: selectedVariant, pricing_day: selectedPricingDay })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert(data.message || 'This product is currently unavailable.');
            return;
        }

        const headerBadge = document.getElementById('header-cart-badge');
        if (headerBadge) {
            headerBadge.textContent = data.cart_count;
        }

        if (redirectToCheckout) {
            window.location.href = '<?php echo e(route("frontend.checkout")); ?>';
            return;
        }

        if (typeof showCartAddedAlert === 'function') {
            showCartAddedAlert('Add to Cart');
        } else {
            alert('Add to Cart');
        }
    });
}

function addToCartWithQty(productId) {
    sendCartRequest(productId, false);
}

function buyNowWithQty(productId) {
    sendCartRequest(productId, true);
}

document.querySelectorAll('[data-detail-tab]').forEach((button) => {
    button.addEventListener('click', () => activateDetailTab(button.dataset.detailTab));
});

document.querySelectorAll('[data-pricing-day]').forEach((button) => {
    button.addEventListener('click', () => selectPricingDay(button.dataset.pricingDay));
});

updateQtyDisplay();
selectPricingDay('<?php echo e($defaultPricingDay); ?>');
activateDetailTab('<?php echo e($initialDetailTab); ?>');
applyProductImageZoom();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\frontend\product-detail.blade.php ENDPATH**/ ?>
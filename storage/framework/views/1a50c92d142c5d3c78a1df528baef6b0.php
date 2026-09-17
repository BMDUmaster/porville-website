<?php
    $cardUid = 'pc-' . $product->id . '-' . Str::random(6);
    $cardOptions = $product->card_variant_options;
    $cardFromPrice = $cardOptions[0]['price'] ?? $product->card_from_price;
    $cardMrp = $cardOptions[0]['mrp'] ?? collect($cardOptions)->max('mrp');
    $cardLowestPrice = $product->card_from_price;
    $cardInWishlist = in_array($product->id, session('wishlist', []), true);
    $cardIsNewArrival = in_array($product->id, $newArrivalProductIds ?? [], true);
?>
<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:border-amber-300 hover:shadow-lg">
    <button
        type="button"
        onclick="toggleWishlist(<?php echo e($product->id); ?>, this)"
        data-product-id="<?php echo e($product->id); ?>"
        aria-label="<?php echo e($cardInWishlist ? 'Remove from wishlist' : 'Add to wishlist'); ?>"
        title="<?php echo e($cardInWishlist ? 'Remove from Wishlist' : 'Add to Wishlist'); ?>"
        class="wishlist-btn <?php echo e($cardInWishlist ? 'active' : ''); ?> absolute right-2.5 top-2.5 z-20 flex h-8 w-8 items-center justify-center rounded-full bg-white/90 shadow-md backdrop-blur-sm transition hover:scale-110"
    >
        <i class="<?php echo e($cardInWishlist ? 'fa-solid fa-heart text-base text-red-500' : 'fa-regular fa-heart text-base text-slate-500 hover:text-red-500'); ?>"></i>
    </button>

    <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>" class="relative block aspect-[4/3] overflow-hidden bg-gray-50">
        <?php if($cardIsNewArrival): ?>
            <span class="absolute left-2 top-2 z-10 rounded-full bg-black px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.12em] text-amber-300 shadow">
                New Arrival
            </span>
        <?php endif; ?>
        <?php if($product->images && count($product->images)): ?>
            <img src="<?php echo e(asset('storage/' . $product->images[0])); ?>" alt="<?php echo e($product->name); ?>" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
        <?php else: ?>
            <div class="flex h-full w-full items-center justify-center text-4xl text-slate-300">
                <i class="fa-solid fa-drumstick-bite"></i>
            </div>
        <?php endif; ?>
        <?php if($product->is_out_of_stock): ?>
            <span class="absolute left-2 top-2 z-10 rounded bg-red-600 px-2.5 py-1 text-[10px] font-bold uppercase tracking-[0.12em] text-white shadow">
                Out of Stock
            </span>
        <?php endif; ?>
        <?php if($cardMrp && $cardMrp > $cardFromPrice): ?>
            <span class="absolute bottom-2 right-2 z-10 rounded bg-red-500 px-2 py-0.5 text-[9px] font-bold text-white shadow">
                -<?php echo e(round((($cardMrp - $cardFromPrice) / $cardMrp) * 100)); ?>%
            </span>
        <?php endif; ?>
    </a>

    <div class="flex flex-1 flex-col p-2">
        <p class="text-[8px] font-bold uppercase tracking-[0.1em] text-slate-400"><?php echo e($product->category->name ?? ''); ?></p>
        <a href="<?php echo e(route('frontend.product.show', $product->slug)); ?>" class="font-classic mt-0.5 block min-h-[18px] text-[13px] font-bold leading-[1.15] text-slate-900 line-clamp-1 transition hover:text-amber-700">
            <?php echo e($product->name); ?>

        </a>

        <?php if($product->is_enquiry_only): ?>
            <div class="mt-1.5">
                <span class="inline-flex rounded-full bg-amber-50 px-2 py-1 text-[9px] font-bold text-amber-700">Custom cut &middot; enquire</span>
            </div>

            <div class="mt-auto pt-1.5">
                <p class="font-classic text-[14px] font-bold text-slate-900">On call</p>

                <?php if(!$product->is_out_of_stock && $product->contact_number): ?>
                    <a href="tel:<?php echo e(preg_replace('/\D/', '', $product->contact_number)); ?>"
                       class="mt-1.5 flex h-8 w-full items-center justify-center gap-1.5 rounded-lg border border-amber-500 bg-black text-[9px] font-bold uppercase tracking-[0.08em] text-white transition hover:bg-neutral-900">
                        <i class="fa-solid fa-phone text-[10px] text-amber-400"></i> Call to Order
                    </a>
                <?php elseif($product->is_out_of_stock): ?>
                    <span class="mt-1.5 flex h-8 w-full items-center justify-center rounded-lg border border-red-200 bg-red-50 text-[9px] font-bold uppercase tracking-wider text-red-600">
                        Out of Stock
                    </span>
                <?php else: ?>
                    <a href="<?php echo e(route('frontend.contact')); ?>"
                       class="mt-1.5 flex h-8 w-full items-center justify-center gap-1.5 rounded-lg border border-amber-500 bg-black text-[9px] font-bold uppercase tracking-[0.08em] text-white transition hover:bg-neutral-900">
                        <i class="fa-solid fa-headset text-[10px] text-amber-400"></i> Contact Us
                    </a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <?php if(count($cardOptions) > 1): ?>
                <div class="mt-1.5">
                    <label for="<?php echo e($cardUid); ?>" class="mb-0.5 block text-[8px] font-bold uppercase tracking-[0.1em] text-slate-400">Pack Size / Weight</label>
                    <select id="<?php echo e($cardUid); ?>" data-card-price-target="<?php echo e($cardUid); ?>-price" onchange="updateProductCardPrice('<?php echo e($cardUid); ?>')" class="w-full rounded-md border border-slate-300 bg-white px-1.5 py-1 text-[11px] font-semibold text-slate-700 outline-none focus:border-amber-500">
                        <?php $__currentLoopData = $cardOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($opt['index'] ?? ''); ?>" data-price="<?php echo e($opt['price']); ?>" data-mrp="<?php echo e($opt['mrp'] ?? ''); ?>"><?php echo e($opt['label']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            <?php else: ?>
                <input type="hidden" id="<?php echo e($cardUid); ?>" value="<?php echo e($cardOptions[0]['index'] ?? ''); ?>">
            <?php endif; ?>

            <div class="mt-auto pt-1.5">
                <div class="flex flex-wrap items-baseline gap-1.5">
                    <p id="<?php echo e($cardUid); ?>-price" class="font-classic text-[14px] font-bold text-slate-900">
                        From Rs<?php echo e(number_format($cardFromPrice, 0)); ?>

                    </p>
                    <p id="<?php echo e($cardUid); ?>-mrp" class="text-[10px] font-semibold text-slate-400 line-through <?php echo e(($cardMrp && $cardMrp > $cardFromPrice) ? '' : 'hidden'); ?>">Rs<?php echo e(number_format($cardMrp, 0)); ?></p>
                </div>

                <?php if(!$product->is_out_of_stock): ?>
                    <button type="button"
                            onclick="addProductCardToCart(<?php echo e($product->id); ?>, '<?php echo e($cardUid); ?>')"
                            class="mt-1.5 flex h-8 w-full items-center justify-center gap-1.5 rounded-lg border border-amber-500 bg-black text-[9px] font-bold uppercase tracking-[0.08em] text-white transition hover:bg-neutral-900">
                        <i class="fa-solid fa-cart-shopping text-[10px] text-amber-400"></i> Add to Cart
                    </button>
                <?php else: ?>
                    <span class="mt-1.5 flex h-8 w-full items-center justify-center rounded-lg border border-red-200 bg-red-50 text-[9px] font-bold uppercase tracking-wider text-red-600">
                        Out of Stock
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</article>
<?php /**PATH C:\BMDU Work\porville-fresh-cut\resources\views/frontend/partials/product-card.blade.php ENDPATH**/ ?>
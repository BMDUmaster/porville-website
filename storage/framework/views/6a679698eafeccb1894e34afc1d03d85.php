<?php $__env->startSection('title', 'Your Cart'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <h1 class="nunito text-2xl font-extrabold text-gray-800">
            Your Cart <span class="text-base font-semibold text-gray-400">(<?php echo e(count($items)); ?> items)</span>
        </h1>
        <?php if(($availableDays ?? collect())->count() > 1): ?>
            <div class="flex rounded-full bg-slate-100 p-1">
                <?php $__currentLoopData = ['today' => 'Today', 'tomorrow' => 'Tomorrow']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('frontend.cart', ['delivery_day' => $day])); ?>"
                       class="<?php echo e($selectedDay === $day ? 'bg-neutral-800 text-white shadow' : 'text-slate-500'); ?> rounded-full px-4 py-2 text-xs font-black uppercase">
                        <?php echo e($label); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if(empty($items)): ?>
        <div class="rounded-2xl border bg-white p-16 text-center">
            <i class="fa-solid fa-cart-shopping mb-4 block text-6xl text-gray-200"></i>
            <p class="mb-4 font-semibold text-gray-500">Your cart is empty</p>
            <a href="<?php echo e(route('frontend.products')); ?>" class="inline-block rounded-xl bg-neutral-800 px-6 py-3 text-sm font-bold text-white transition hover:bg-neutral-800">
                Continue Shopping
            </a>
        </div>
    <?php else: ?>
    <div class="mb-6 overflow-hidden rounded-2xl border bg-white">
        <div class="h-1 bg-gradient-to-r from-amber-600 to-amber-400"></div>
        <div class="border-b px-6 py-5">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="nunito text-lg font-extrabold text-gray-800">Delivery Slot</h2>
                <span id="cartDeliverySlotLabel" class="text-[11px] font-bold text-amber-700">
                    <?php echo e(\App\Support\DeliverySlotManager::label($selectedDeliverySlot)); ?>

                </span>
            </div>
        </div>
        <div class="px-6 py-5 space-y-4">
            <div class="flex flex-wrap gap-2" id="cartDeliveryDateTabs">
                <?php $__empty_1 = true; $__currentLoopData = $upcomingDates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <button
                        type="button"
                        onclick="selectCartDeliveryDate('<?php echo e($day['date']); ?>')"
                        data-cart-delivery-date="<?php echo e($day['date']); ?>"
                        class="cart-delivery-date-chip <?php echo e($selectedDeliveryDate === $day['date'] ? 'border-neutral-800 bg-neutral-800 text-white' : 'border-gray-200 bg-white text-gray-600'); ?> rounded-full border px-3.5 py-2 text-[11px] font-bold leading-none transition hover:border-neutral-500"
                    >
                        <?php echo e($day['date_label']); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <span class="text-xs font-semibold text-gray-400">No delivery slots configured yet.</span>
                <?php endif; ?>
            </div>
            <div class="flex flex-wrap gap-2" id="cartDeliverySlotChips">
                <?php $__empty_1 = true; $__currentLoopData = $deliverySlotOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <button
                        type="button"
                        onclick="updateCartDeliverySlot('<?php echo e($slot['value']); ?>')"
                        data-cart-delivery-slot="<?php echo e($slot['value']); ?>"
                        class="cart-delivery-slot-chip <?php echo e($selectedDeliverySlot === $slot['value'] ? 'border-amber-600 bg-amber-50 text-amber-700 shadow-sm' : 'border-gray-200 bg-white text-gray-600'); ?> rounded-full border px-3 py-2 text-[11px] font-bold leading-none transition hover:border-amber-400 hover:text-amber-700"
                    >
                        <?php echo e($slot['label']); ?>

                    </button>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-2 text-[11px] font-bold leading-none text-amber-700">
                        No slots left for this date
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-6 lg:flex-row">
        <div class="flex-1 space-y-4">
            <div class="rounded-2xl border bg-white p-5">
                <p class="mb-3 text-sm font-semibold text-gray-700"><i class="fa-solid fa-tag mr-1 text-amber-600"></i>Offers & Coupons</p>
                <?php if($couponData['applied']): ?>
                    <div class="flex items-center justify-between rounded-xl border border-emerald-200 bg-emerald-50 p-3">
                        <div><p class="text-[10px] font-black uppercase text-emerald-600">Applied</p><p class="text-sm font-bold text-slate-800"><?php echo e($couponData['applied']['title']); ?></p></div>
                        <button type="button" onclick="removeFullCartCoupon()" class="text-xs font-black text-red-500">Remove</button>
                    </div>
                <?php elseif($couponData['available']->isNotEmpty()): ?>
                    <div class="grid gap-2 sm:grid-cols-2">
                        <?php $__currentLoopData = $couponData['available']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between gap-2 rounded-xl border border-amber-100 bg-amber-50/50 p-3">
                                <div><p class="text-sm font-bold text-slate-800"><?php echo e($coupon['title']); ?></p><p class="text-xs text-slate-500"><?php echo e($coupon['type'] === 'percent' ? rtrim(rtrim(number_format($coupon['value'], 2), '0'), '.') . '%' : 'Rs' . number_format($coupon['value'], 2)); ?> off</p></div>
                                <button type="button" onclick='applyFullCartCoupon(<?php echo json_encode($coupon["code"], 15, 512) ?>)' class="rounded-lg bg-neutral-800 px-3 py-2 text-[10px] font-black text-white">Apply</button>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-xs text-slate-400">Add more eligible items to unlock an offer.</p>
                <?php endif; ?>
            </div>

            <div class="overflow-hidden rounded-2xl border bg-white">
                <div class="hidden grid-cols-12 gap-3 border-b bg-gray-50 px-6 py-3 text-xs font-bold uppercase tracking-wider text-gray-400 md:grid">
                    <div class="col-span-6">Product</div>
                    <div class="col-span-2 text-center">Price</div>
                    <div class="col-span-2 text-center">Qty</div>
                    <div class="col-span-2 text-right">Total</div>
                </div>

                <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="relative border-b px-6 py-5 last:border-b-0" id="cart-row-<?php echo e($item['key']); ?>">
                    <button onclick="removeCartItem('<?php echo e($item['key']); ?>')"
                            class="absolute right-4 top-4 flex h-7 w-7 items-center justify-center rounded-full bg-gray-100 text-gray-400 transition hover:bg-red-100 hover:text-red-500">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                    <div class="flex flex-col gap-4 md:grid md:grid-cols-12 md:items-center md:gap-3">
                        <div class="col-span-6 flex items-start gap-4">
                            <div class="flex h-20 w-20 flex-shrink-0 items-center justify-center overflow-hidden rounded-xl border bg-gray-50">
                                <?php if($item['image']): ?>
                                    <img src="<?php echo e(asset('storage/' . $item['image'])); ?>" class="h-full w-full object-cover" alt="<?php echo e($item['name']); ?>">
                                <?php else: ?>
                                    <i class="fa-solid fa-basket-shopping text-2xl text-gray-300"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="pr-6 text-sm font-semibold text-gray-800"><?php echo e($item['name']); ?></p>
                                <?php if($item['variant_label']): ?>
                                    <p class="mt-1 text-xs text-gray-400"><?php echo e($item['variant_label']); ?></p>
                                <?php endif; ?>
                                <p class="mt-1 text-xs text-gray-400"><?php echo e($item['unit']); ?></p>
                                <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                    <span class="inline-flex items-center rounded border px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider <?php echo e(($item['pricing_day'] ?? 'today') === 'tomorrow' ? 'bg-amber-50 text-amber-700 border-amber-100' : 'bg-emerald-50 text-emerald-700 border-emerald-100'); ?>">
                                        <?php echo e(($item['pricing_day'] ?? 'today') === 'tomorrow' ? 'Tomorrow\'s Delivery' : 'Today\'s Delivery'); ?>

                                    </span>
                                    <?php if(!empty($item['is_out_of_stock'])): ?>
                                        <span class="inline-flex items-center rounded border border-red-200 bg-red-50 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-red-600">
                                            Out of Stock
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 text-left md:text-center">
                            <p class="text-sm font-bold text-gray-800">&#8377;<?php echo e(number_format($item['price'], 2)); ?></p>
                        </div>
                        <div class="col-span-2 flex justify-start md:justify-center">
                            <div class="flex items-center overflow-hidden rounded-lg border border-gray-200">
                                <button onclick="updateCartQty('<?php echo e($item['key']); ?>', <?php echo e($item['quantity'] - 1); ?>)"
                                        class="flex h-8 w-8 items-center justify-center font-bold text-gray-500 transition hover:bg-gray-100">-</button>
                                <span class="flex h-8 items-center border-x border-gray-200 px-3 text-sm font-bold" id="qty-<?php echo e($item['key']); ?>"><?php echo e($item['quantity']); ?></span>
                                <button onclick="updateCartQty('<?php echo e($item['key']); ?>', <?php echo e($item['quantity'] + 1); ?>)"
                                        class="flex h-8 w-8 items-center justify-center font-bold text-gray-500 transition hover:bg-gray-100">+</button>
                            </div>
                        </div>
                        <div class="col-span-2 text-left md:text-right">
                            <p class="text-sm font-bold text-amber-700" id="subtotal-<?php echo e($item['key']); ?>">&#8377;<?php echo e(number_format($item['subtotal'], 2)); ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <a href="<?php echo e(route('frontend.products')); ?>" class="inline-flex items-center gap-2 text-sm font-medium text-amber-600 hover:underline">
                <i class="fa-solid fa-arrow-left text-xs"></i> Continue Shopping
            </a>
        </div>

        <div class="lg:col-span-4">
            <?php ($hasOutOfStock = collect($items)->contains('is_out_of_stock', true)); ?>
            <div class="sticky top-20 rounded-2xl border border-gray-100 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-gray-800">Order Summary</h2>

                <?php if($hasOutOfStock): ?>
                    <div class="mt-3 rounded-xl border border-red-200 bg-red-50 p-3 text-xs font-semibold text-red-600">
                        <i class="fa-solid fa-circle-exclamation mr-1"></i> Some items in your cart are out of stock. Please remove them to proceed to checkout.
                    </div>
                <?php endif; ?>

                <div class="mt-4 space-y-3">
                    <div class="flex justify-between text-sm text-gray-600"><span>Subtotal</span><span>&#8377;<?php echo e(number_format($pricing['subtotal'], 2)); ?></span></div>
                    <div class="flex justify-between text-sm text-gray-600"><span>Delivery Charge</span><span>&#8377;<?php echo e(number_format($pricing['delivery_charge'], 2)); ?></span></div>
                    <div class="flex justify-between text-sm text-gray-600"><span>Service Charge (<?php echo e($pricing['service_charge_percent'] ?? 0); ?>%)</span><span>&#8377;<?php echo e(number_format($pricing['service_charge'], 2)); ?></span></div>
                    <?php if(!empty($pricing['discount']) && $pricing['discount'] > 0): ?>
                        <div class="flex justify-between text-sm font-bold text-emerald-600"><span>Discount</span><span>-&#8377;<?php echo e(number_format($pricing['discount'], 2)); ?></span></div>
                    <?php endif; ?>
                    <hr class="border-gray-100">
                    <div class="flex justify-between">
                        <span class="font-bold text-gray-800">Total</span>
                        <span class="nunito text-xl font-extrabold text-amber-700">&#8377;<?php echo e(number_format($pricing['total'], 2)); ?></span>
                    </div>
                </div>
                <div class="px-0 pt-4">
                    <?php if($hasOutOfStock): ?>
                        <button disabled type="button" class="flex w-full cursor-not-allowed items-center justify-center gap-2 rounded-xl bg-gray-400 py-3.5 text-sm font-bold text-white opacity-70">
                            <i class="fa-solid fa-ban text-xs"></i> Remove Out of Stock Items
                        </button>
                    <?php else: ?>
                        <a href="<?php echo e(route('frontend.checkout', ['delivery_day' => $selectedDay])); ?>"
                           class="flex w-full items-center justify-center gap-2 rounded-xl bg-neutral-800 py-3.5 text-sm font-bold text-white transition hover:bg-neutral-800">
                            <i class="fa-solid fa-lock text-xs"></i> Proceed to Checkout
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function applyFullCartCoupon(code) {
    fetch('<?php echo e(url("cart/coupon")); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
        body: JSON.stringify({ code, delivery_day: <?php echo json_encode($selectedDay, 15, 512) ?> })
    }).then(async r => { const data = await r.json(); if (!r.ok) throw new Error(data.message); location.reload(); }).catch(e => alert(e.message));
}
function removeFullCartCoupon() {
    fetch('<?php echo e(url("cart/coupon")); ?>', {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
        body: JSON.stringify({ delivery_day: <?php echo json_encode($selectedDay, 15, 512) ?> })
    }).then(() => location.reload());
}
function removeCartItem(key) {
    fetch('<?php echo e(route("frontend.cart.remove")); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
        body: JSON.stringify({ key })
    }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
}
function updateCartQty(key, qty) {
    fetch('<?php echo e(route("frontend.cart.update")); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
        body: JSON.stringify({ key, quantity: qty })
    }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
}

let cartSelectedDeliveryDate = <?php echo json_encode($selectedDeliveryDate, 15, 512) ?>;

function updateCartDeliverySlot(value) {
    fetch('<?php echo e(route("frontend.cart.delivery-slot")); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
        body: JSON.stringify({ delivery_slot: value, delivery_date: cartSelectedDeliveryDate })
    }).then(r => r.json()).then(d => {
        if (!d.success) return;
        markActiveSlotChip(value);

        const label = document.getElementById('cartDeliverySlotLabel');
        if (label) {
            label.textContent = d.delivery_slot_label || '';
        }
    });
}

function markActiveSlotChip(value) {
    document.querySelectorAll('[data-cart-delivery-slot]').forEach((chip) => {
        const isActive = chip.dataset.cartDeliverySlot === value;
        chip.classList.toggle('border-amber-600', isActive);
        chip.classList.toggle('bg-amber-50', isActive);
        chip.classList.toggle('text-amber-700', isActive);
        chip.classList.toggle('shadow-sm', isActive);
        chip.classList.toggle('border-gray-200', !isActive);
        chip.classList.toggle('bg-white', !isActive);
        chip.classList.toggle('text-gray-600', !isActive);
    });
}

function selectCartDeliveryDate(date) {
    fetch('<?php echo e(route("frontend.cart.delivery-date")); ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' },
        body: JSON.stringify({ delivery_date: date })
    }).then(r => r.json()).then(d => {
        if (!d.success) return;
        cartSelectedDeliveryDate = date;

        document.querySelectorAll('[data-cart-delivery-date]').forEach((chip) => {
            const isActive = chip.dataset.cartDeliveryDate === date;
            chip.classList.toggle('border-neutral-800', isActive);
            chip.classList.toggle('bg-neutral-800', isActive);
            chip.classList.toggle('text-white', isActive);
            chip.classList.toggle('border-gray-200', !isActive);
            chip.classList.toggle('bg-white', !isActive);
            chip.classList.toggle('text-gray-600', !isActive);
        });

        const container = document.getElementById('cartDeliverySlotChips');
        if (container) {
            if (!d.options.length) {
                container.innerHTML = '<span class="rounded-full border border-amber-200 bg-amber-50 px-3 py-2 text-[11px] font-bold leading-none text-amber-700">No slots left for this date</span>';
            } else {
                container.innerHTML = d.options.map((slot) => `
                    <button type="button" onclick="updateCartDeliverySlot('${slot.value}')" data-cart-delivery-slot="${slot.value}"
                            class="cart-delivery-slot-chip border-gray-200 bg-white text-gray-600 rounded-full border px-3 py-2 text-[11px] font-bold leading-none transition hover:border-amber-400 hover:text-amber-700">
                        ${slot.label}
                    </button>
                `).join('');
            }
        }

        markActiveSlotChip(d.selected_delivery_slot || '');
        const label = document.getElementById('cartDeliverySlotLabel');
        if (label) {
            const active = (d.options || []).find((slot) => slot.value === d.selected_delivery_slot);
            label.textContent = active ? active.label : '';
        }
    });
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\porville-fresh-cut\resources\views/frontend/cart.blade.php ENDPATH**/ ?>
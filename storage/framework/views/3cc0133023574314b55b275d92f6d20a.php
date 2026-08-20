<?php $__env->startSection('title', 'View Product'); ?>
<?php $__env->startSection('page_title', 'Product Details'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 sm:p-6">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <a href="<?php echo e(route('dashboard.products')); ?>" class="text-sm font-bold text-blue-600 hover:underline">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back to Products
            </a>
            <h2 class="mt-3 text-2xl font-black text-slate-900"><?php echo e($product->name); ?></h2>
            <p class="mt-1 text-sm text-slate-500">Product ID: #<?php echo e($product->id); ?></p>
        </div>

        <span class="inline-flex rounded-full px-4 py-2 text-xs font-black <?php echo e($product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'); ?>">
            <?php echo e($product->is_active ? 'ACTIVE' : 'DEACTIVE'); ?>

        </span>
    </div>

    <div class="grid gap-6 xl:grid-cols-[420px_minmax(0,1fr)]">
        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                <h3 class="mb-4 text-sm font-black uppercase tracking-[0.16em] text-slate-500">Images</h3>
                <?php if($product->images && count($product->images)): ?>
                    <div class="grid grid-cols-2 gap-3">
                        <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50">
                                <img src="<?php echo e(asset('storage/' . $image)); ?>" alt="<?php echo e($product->name); ?>" class="h-40 w-full object-cover">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="rounded-xl bg-slate-50 px-4 py-10 text-center text-sm font-semibold text-slate-400">
                        No images uploaded.
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="space-y-6">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="grid gap-0 md:grid-cols-2">
                    <?php $__currentLoopData = [
                        'Category' => $product->category->name ?? '-',
                        'Sub Category' => $product->subcategory->name ?? '-',
                        'Price' => 'Rs' . number_format($product->price, 2),
                        'MRP' => $product->mrp ? 'Rs' . number_format($product->mrp, 2) : '-',
                        'Stock' => $product->is_active ? 'Active' : 'Deactive',
                        'Slug' => $product->slug ?: '-',
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border-b border-slate-100 px-5 py-4 md:border-r even:md:border-r-0">
                            <p class="text-[11px] font-black uppercase tracking-[0.16em] text-slate-400"><?php echo e($label); ?></p>
                            <p class="mt-2 break-all text-sm font-bold text-slate-800"><?php echo e($value); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-sm font-black uppercase tracking-[0.16em] text-slate-500">Description</h3>
                <p class="mt-4 text-sm leading-7 text-slate-600">
                    <?php echo e($product->description ?: 'No description added for this product.'); ?>

                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <h3 class="text-sm font-black uppercase tracking-[0.16em] text-slate-500">Variants</h3>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-black text-slate-600">
                        <?php echo e(is_array($product->variants) ? count($product->variants) : 0); ?> variants
                    </span>
                </div>

                <?php if($product->variants && count($product->variants)): ?>
                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full min-w-[980px] text-left text-sm">
                            <thead class="border-b bg-slate-50 text-[11px] font-black uppercase tracking-[0.16em] text-slate-500">
                                <tr>
                                    <th class="px-4 py-3">Quantity</th>
                                    <th class="px-4 py-3">Unit</th>
                                    <th class="px-4 py-3">Piece</th>
                                    <th class="px-4 py-3">MRP</th>
                                    <th class="px-4 py-3">Base Price</th>
                                    <th class="px-4 py-3">Today Price</th>
                                    <th class="px-4 py-3">Tomorrow Price</th>
                                    <th class="px-4 py-3">Save Offer</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php $__currentLoopData = $product->variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-4 py-3 font-semibold text-slate-700"><?php echo e($variant['quantity'] ?? '-'); ?></td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e($variant['unit'] ?? '-'); ?></td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e($variant['piece'] ?? '-'); ?></td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e(!empty($variant['mrp']) ? 'Rs' . $variant['mrp'] : '-'); ?></td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e(!empty($variant['selling_price']) ? 'Rs' . $variant['selling_price'] : '-'); ?></td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e(isset($variant['today_price']) && $variant['today_price'] !== null ? 'Rs' . $variant['today_price'] : '-'); ?></td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e(isset($variant['tomorrow_price']) && $variant['tomorrow_price'] !== null ? 'Rs' . $variant['tomorrow_price'] : '-'); ?></td>
                                        <td class="px-4 py-3 text-slate-600"><?php echo e($variant['save_offer'] ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="mt-4 rounded-xl bg-slate-50 px-4 py-10 text-center text-sm font-semibold text-slate-400">
                        No variants available.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\dashboard\products\show.blade.php ENDPATH**/ ?>
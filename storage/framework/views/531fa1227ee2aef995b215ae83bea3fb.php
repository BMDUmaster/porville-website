<?php $__env->startSection('title', 'Service Charge'); ?>
<?php $__env->startSection('page_title', 'Service Charge Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 md:p-6">
    <div class="mx-auto max-w-4xl space-y-6">
        <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-[#b8862c] to-[#0d0d0d] px-6 py-6 text-white md:px-8">
                <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-white/70">Checkout Pricing</p>
                <h1 class="mt-2 text-2xl font-black tracking-[-0.03em]">Service Charge</h1>
                <p class="mt-2 max-w-2xl text-sm text-white/80">
                    Add a charge percent for an order-amount range. Changes apply to new checkout and API orders immediately.
                </p>
            </div>

            <div class="px-6 py-6 md:px-8">
                <form method="POST" action="<?php echo e(route('dashboard.settings.service-charge.tier.store')); ?>" class="grid gap-3 rounded-xl bg-slate-50 p-4 sm:grid-cols-[1fr_1fr_1fr_auto] sm:items-end">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-600">From Amount (Rs)</label>
                        <input type="number" name="min_amount" min="0" step="0.01" required
                               class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold outline-none focus:border-amber-500">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-600">Up To Amount (Rs)</label>
                        <input type="number" name="max_amount" min="0" step="0.01" placeholder="No limit"
                               class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold outline-none focus:border-amber-500">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-600">Charge (%)</label>
                        <input type="number" name="percent" min="0" max="100" step="0.01" required
                               class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold outline-none focus:border-amber-500">
                    </div>
                    <button type="submit" class="inline-flex h-[42px] items-center justify-center gap-2 rounded-xl bg-black px-5 text-sm font-bold text-white hover:bg-neutral-900">
                        <i class="fa-solid fa-plus"></i> Add Tier
                    </button>
                </form>

                <div class="mt-5 space-y-2">
                    <?php $__empty_1 = true; $__currentLoopData = $tiers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3">
                            <p class="text-sm font-bold text-slate-800">
                                Rs<?php echo e(number_format($tier->min_amount, 0)); ?>

                                <?php echo e($tier->max_amount !== null ? ' – Rs' . number_format($tier->max_amount, 0) : ' and above'); ?>

                                <span class="ml-2 rounded-full bg-amber-50 px-2 py-0.5 text-xs font-black text-amber-700"><?php echo e(rtrim(rtrim(number_format($tier->percent, 2), '0'), '.')); ?>%</span>
                            </p>
                            <form method="POST" action="<?php echo e(route('dashboard.settings.service-charge.tier.destroy', $tier)); ?>" onsubmit="return confirm('Remove this tier?');">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-400 hover:text-red-600"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="rounded-xl border border-dashed border-slate-300 px-4 py-6 text-center text-sm text-slate-400">No amount tiers yet — orders outside any tier use the system default (<?php echo e(rtrim(rtrim(number_format($defaultPercent, 2), '0'), '.')); ?>%).</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\porville-fresh-cut\resources\views/dashboard/settings/service-charge.blade.php ENDPATH**/ ?>
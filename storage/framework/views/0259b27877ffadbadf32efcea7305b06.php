<?php $__env->startSection('title', 'Service Charge'); ?>
<?php $__env->startSection('page_title', 'Service Charge Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 md:p-6">
    <div class="mx-auto max-w-4xl space-y-6">
        <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-[#0f766e] via-[#0f8a79] to-[#1d4ed8] px-6 py-6 text-white md:px-8">
                <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-white/70">Checkout Pricing</p>
                <h1 class="mt-2 text-2xl font-black tracking-[-0.03em]">Dynamic Service Charge</h1>
                <p class="mt-2 max-w-2xl text-sm text-white/80">
                    Control the percentage applied to every cart automatically. Changes take effect for new checkout and API orders immediately.
                </p>
            </div>

            <div class="grid gap-5 border-b border-slate-200 bg-slate-50/70 px-6 py-5 md:grid-cols-3 md:px-8">
                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Current Rate</p>
                    <p class="mt-2 text-3xl font-black text-slate-900"><?php echo e(rtrim(rtrim(number_format($serviceChargePercent, 2), '0'), '.')); ?>%</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Default Rate</p>
                    <p class="mt-2 text-3xl font-black text-slate-900"><?php echo e(rtrim(rtrim(number_format($defaultPercent, 2), '0'), '.')); ?>%</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Preview on ₹1,000</p>
                    <p class="mt-2 text-3xl font-black text-emerald-600">&#8377;<?php echo e(number_format(1000 * $serviceChargePercent / 100, 2)); ?></p>
                </div>
            </div>

            <div class="px-6 py-6 md:px-8">
                <form method="POST" action="<?php echo e(route('dashboard.settings.service-charge.update')); ?>" class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_280px]">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                                <i class="fa-solid fa-percent text-lg"></i>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-lg font-black text-slate-900">Update Service Charge Percentage</h2>
                                <p class="mt-1 text-sm text-slate-500">This percentage is calculated on the cart subtotal before adding delivery charge.</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label for="serviceChargePercent" class="mb-2 block text-sm font-bold text-slate-700">Service Charge (%)</label>
                            <input type="number" step="0.01" min="0" max="100" id="serviceChargePercent" name="service_charge_percent"
                                   value="<?php echo e(old('service_charge_percent', $serviceChargePercent)); ?>"
                                   class="w-full rounded-2xl border border-slate-300 px-5 py-3 text-base font-semibold text-slate-800 outline-none transition focus:border-blue-500">
                            <p class="mt-2 text-xs text-slate-400">Example: enter <span class="font-bold text-slate-600">10</span> for a 10% service charge.</p>
                        </div>
                    </div>

                    <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-5">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Quick Preview</p>
                        <div class="mt-4 space-y-3 rounded-2xl border border-slate-200 bg-white p-4 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Subtotal</span>
                                <span class="font-semibold text-slate-800">&#8377;1,000.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Service Charge</span>
                                <span class="font-semibold text-slate-800" id="serviceChargePreview">&#8377;<?php echo e(number_format(1000 * $serviceChargePercent / 100, 2)); ?></span>
                            </div>
                        </div>

                        <button type="submit" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700">
                            <i class="fa-solid fa-floppy-disk text-xs"></i>
                            Save Service Charge
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
const serviceChargeInput = document.getElementById('serviceChargePercent');
const serviceChargePreview = document.getElementById('serviceChargePreview');

if (serviceChargeInput && serviceChargePreview) {
    const updatePreview = () => {
        const percentage = Number(serviceChargeInput.value || 0);
        const charge = (1000 * percentage) / 100;
        serviceChargePreview.innerHTML = '&#8377;' + charge.toFixed(2);
    };

    serviceChargeInput.addEventListener('input', updatePreview);
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\dashboard\settings\service-charge.blade.php ENDPATH**/ ?>
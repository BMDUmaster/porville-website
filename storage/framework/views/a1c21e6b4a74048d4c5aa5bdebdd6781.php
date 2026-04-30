<?php $__env->startSection('title', 'Delivery Slots'); ?>
<?php $__env->startSection('page_title', 'Delivery Slot Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 md:p-6">
    <div class="mx-auto max-w-5xl space-y-6">
        <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-[#1d4ed8] via-[#2563eb] to-[#0f766e] px-6 py-6 text-white md:px-8">
                <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-white/70">Checkout Experience</p>
                <h1 class="mt-2 text-2xl font-black tracking-[-0.03em]">Delivery Time Slot Controls</h1>
                <p class="mt-2 max-w-2xl text-sm text-white/80">
                    Manage the fixed daytime slots and the evening slot generator from one place. Changes apply immediately to checkout.
                </p>
            </div>

            <div class="grid gap-5 border-b border-slate-200 bg-slate-50/70 px-6 py-5 md:grid-cols-3 md:px-8">
                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Total Visible Slots</p>
                    <p class="mt-2 text-3xl font-black text-slate-900"><?php echo e(count($previewSlots)); ?></p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Evening Start</p>
                    <p class="mt-2 text-3xl font-black text-slate-900"><?php echo e($settings['evening_start'] ?: '-'); ?></p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Last Slot End</p>
                    <p class="mt-2 text-3xl font-black text-slate-900"><?php echo e($settings['last_end'] ?: '-'); ?></p>
                </div>
            </div>

            <div class="px-6 py-6 md:px-8">
                <form method="POST" action="<?php echo e(route('dashboard.settings.delivery-slots.update')); ?>" class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="space-y-6">
                        <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                                    <i class="fa-regular fa-clock text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <h2 class="text-lg font-black text-slate-900">Fixed Slots</h2>
                                    <p class="mt-1 text-sm text-slate-500">Add one slot per line using <span class="font-semibold text-slate-700">HH:MM-HH:MM</span>. Example: <span class="font-semibold text-slate-700">10:00-12:00</span>.</p>
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="fixedSlotsText" class="mb-2 block text-sm font-bold text-slate-700">Fixed Slot Lines</label>
                                <textarea id="fixedSlotsText" name="fixed_slots_text" rows="6"
                                          class="w-full rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-800 outline-none transition focus:border-blue-500"><?php echo e(old('fixed_slots_text', $settings['fixed_slots_text'])); ?></textarea>
                            </div>
                        </div>

                        <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                                    <i class="fa-solid fa-sliders text-lg"></i>
                                </div>
                                <div class="min-w-0">
                                    <h2 class="text-lg font-black text-slate-900">Evening Slot Generator</h2>
                                    <p class="mt-1 text-sm text-slate-500">These controls create repeating evening slots automatically.</p>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                                <div>
                                    <label for="eveningStart" class="mb-2 block text-sm font-bold text-slate-700">Evening Start</label>
                                    <input type="time" id="eveningStart" name="evening_start" value="<?php echo e(old('evening_start', $settings['evening_start'])); ?>"
                                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="lastEnd" class="mb-2 block text-sm font-bold text-slate-700">Last Slot End</label>
                                    <input type="time" id="lastEnd" name="last_end" value="<?php echo e(old('last_end', $settings['last_end'])); ?>"
                                           class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="slotDurationHours" class="mb-2 block text-sm font-bold text-slate-700">Duration (Hours)</label>
                                    <select id="slotDurationHours" name="slot_duration_hours"
                                            class="w-full rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-800 outline-none transition focus:border-blue-500">
                                        <?php $__currentLoopData = [1, 2, 3, 4, 5, 6]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hours): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($hours); ?>" <?php echo e((int) old('slot_duration_hours', $settings['slot_duration_hours']) === $hours ? 'selected' : ''); ?>><?php echo e($hours); ?> hour<?php echo e($hours > 1 ? 's' : ''); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-5">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Preview</p>
                        <div class="mt-4 space-y-3 rounded-2xl border border-slate-200 bg-white p-4">
                            <?php $__empty_1 = true; $__currentLoopData = $previewSlots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-3 py-2 text-sm">
                                    <span class="font-semibold text-slate-800"><?php echo e($slot['label']); ?></span>
                                    <span class="text-xs font-bold uppercase tracking-[0.14em] text-slate-400"><?php echo e($slot['value']); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-sm text-slate-400">No slots configured yet.</p>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700">
                            <i class="fa-solid fa-floppy-disk text-xs"></i>
                            Save Delivery Slots
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\FarmSea-dashboard\resources\views\dashboard\settings\delivery-slots.blade.php ENDPATH**/ ?>
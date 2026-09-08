<?php $__env->startSection('title', 'Delivery Slots'); ?>
<?php $__env->startSection('page_title', 'Delivery Slot Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-4 md:p-6">
    <form method="POST" action="<?php echo e(route('dashboard.settings.delivery-slots.update')); ?>" id="deliverySlotForm" class="mx-auto max-w-4xl space-y-5">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-black text-slate-900">Manage Delivery Slots</h1>
                <p class="mt-1 text-sm text-slate-500">Select start and end time, then click Add Slot.</p>
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-blue-700">
                <i class="fa-solid fa-floppy-disk"></i> Save Changes
            </button>
        </div>

        <?php if($errors->any()): ?>
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <?php echo e($errors->first()); ?>

            </div>
        <?php endif; ?>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <label for="deliveryCharge" class="block text-sm font-bold text-slate-800">Delivery Charge (Rs)</label>
            <p class="mt-1 text-xs text-slate-500">This amount is added during checkout.</p>
            <input type="number" id="deliveryCharge" name="delivery_charge" min="0" step="0.01"
                   value="<?php echo e(old('delivery_charge', number_format($deliveryCharge, 2, '.', ''))); ?>"
                   class="mt-3 w-full max-w-xs rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold outline-none focus:border-blue-500">
        </div>

        <?php $__currentLoopData = [
            ['key' => 'today', 'title' => "Today's Slots", 'textName' => 'fixed_slots_text', 'value' => old('fixed_slots_text', $settings['fixed_slots_text']), 'color' => 'blue'],
            ['key' => 'tomorrow', 'title' => "Tomorrow's Slots", 'textName' => 'tomorrow_fixed_slots_text', 'value' => old('tomorrow_fixed_slots_text', $settings['tomorrow_fixed_slots_text']), 'color' => 'emerald'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl <?php echo e($day['color'] === 'blue' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600'); ?>">
                        <i class="fa-regular fa-clock"></i>
                    </span>
                    <div>
                        <h2 class="text-lg font-black text-slate-900"><?php echo e($day['title']); ?></h2>
                        <p class="text-xs text-slate-500">Add as many delivery slots as needed.</p>
                    </div>
                </div>

                <textarea name="<?php echo e($day['textName']); ?>" id="<?php echo e($day['key']); ?>SlotsText" class="hidden"><?php echo e($day['value']); ?></textarea>
                <div id="<?php echo e($day['key']); ?>SlotList" class="mt-4 space-y-2"></div>

                <div class="mt-4 grid gap-3 rounded-xl bg-slate-50 p-4 sm:grid-cols-[1fr_1fr_auto] sm:items-end">
                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-600">Start Time</label>
                        <input type="time" id="<?php echo e($day['key']); ?>Start" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-bold text-slate-600">End Time</label>
                        <input type="time" id="<?php echo e($day['key']); ?>End" class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold outline-none focus:border-blue-500">
                    </div>
                    <button type="button" onclick="addSlot('<?php echo e($day['key']); ?>')" class="inline-flex h-[42px] items-center justify-center gap-2 rounded-xl bg-slate-900 px-5 text-sm font-bold text-white hover:bg-slate-700">
                        <i class="fa-solid fa-plus"></i> Add Slot
                    </button>
                </div>

                <?php if($day['key'] === 'today'): ?>
                    <input type="hidden" name="evening_start" value="">
                    <input type="hidden" name="last_end" value="">
                    <input type="hidden" name="slot_duration_hours" value="<?php echo e((int) $settings['slot_duration_hours'] ?: 2); ?>">
                <?php else: ?>
                    <input type="hidden" name="tomorrow_evening_start" value="">
                    <input type="hidden" name="tomorrow_last_end" value="">
                    <input type="hidden" name="tomorrow_slot_duration_hours" value="<?php echo e((int) $settings['tomorrow_slot_duration_hours'] ?: 2); ?>">
                <?php endif; ?>
            </section>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm space-y-4">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </span>
                <div>
                    <h2 class="text-lg font-black text-slate-900">Out of Slot Popup Notification</h2>
                    <p class="text-xs text-slate-500">Dynamic Title and Description when Today or Tomorrow delivery slots are unavailable.</p>
                </div>
            </div>

            <div>
                <label for="noSlotPopupTitle" class="block text-sm font-bold text-slate-800">Popup Title</label>
                <p class="mt-0.5 text-xs text-slate-500">Dynamic popup modal title shown to customer.</p>
                <input type="text" id="noSlotPopupTitle" name="no_slot_popup_title"
                       value="<?php echo e(old('no_slot_popup_title', $settings['no_slot_popup_title'] ?? '')); ?>"
                       placeholder="e.g. Delivery Slots Unavailable"
                       class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold outline-none focus:border-blue-500">
            </div>

            <div>
                <label for="noSlotPopupDesc" class="block text-sm font-bold text-slate-800">Popup Description / Message</label>
                <p class="mt-0.5 text-xs text-slate-500">Dynamic message content displayed when clicking Add to Cart without available slots.</p>
                <textarea id="noSlotPopupDesc" name="no_slot_popup_description" rows="3"
                          placeholder="e.g. Delivery slots for Today or Tomorrow are currently unavailable for this item. Please try again later."
                          class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-semibold outline-none focus:border-blue-500"><?php echo e(old('no_slot_popup_description', $settings['no_slot_popup_description'] ?? '')); ?></textarea>
            </div>
        </section>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white transition hover:bg-blue-700">
            <i class="fa-solid fa-floppy-disk"></i> Save Delivery Settings
        </button>
    </form>
</div>

<script>
const slotState = { today: [], tomorrow: [] };

function initialiseSlots(day) {
    const raw = document.getElementById(`${day}SlotsText`).value.trim();
    slotState[day] = raw ? raw.split(/\r?\n/).map(value => value.trim()).filter(Boolean) : [];
    renderSlots(day);
}

function renderSlots(day) {
    const list = document.getElementById(`${day}SlotList`);
    document.getElementById(`${day}SlotsText`).value = slotState[day].join('\n');

    if (!slotState[day].length) {
        list.innerHTML = '<p class="rounded-xl border border-dashed border-slate-300 px-4 py-4 text-center text-sm text-slate-400">No fixed slots added.</p>';
        return;
    }

    list.innerHTML = slotState[day].map((slot, index) => {
        const [start, end] = slot.split('-');
        return `<div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
            <span class="text-sm font-bold text-slate-800"><i class="fa-regular fa-clock mr-2 text-blue-600"></i>${start} to ${end}</span>
            <button type="button" onclick="removeSlot('${day}', ${index})" class="flex h-8 w-8 items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-100" aria-label="Remove slot"><i class="fa-solid fa-trash-can"></i></button>
        </div>`;
    }).join('');
}

function addSlot(day) {
    const startInput = document.getElementById(`${day}Start`);
    const endInput = document.getElementById(`${day}End`);
    if (!startInput.value || !endInput.value) {
        alert('Please select both start and end time.');
        return;
    }
    if (startInput.value >= endInput.value) {
        alert('End time must be after start time.');
        return;
    }
    const slot = `${startInput.value}-${endInput.value}`;
    if (!slotState[day].includes(slot)) slotState[day].push(slot);
    startInput.value = '';
    endInput.value = '';
    renderSlots(day);
}

function removeSlot(day, index) {
    slotState[day].splice(index, 1);
    renderSlots(day);
}

document.addEventListener('DOMContentLoaded', () => {
    initialiseSlots('today');
    initialiseSlots('tomorrow');
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views/dashboard/settings/delivery-slots.blade.php ENDPATH**/ ?>
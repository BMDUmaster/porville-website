<?php $__env->startSection('title', 'Delivery Areas'); ?>
<?php $__env->startSection('page_title', 'Delivery Area Settings'); ?>

<?php $__env->startSection('content'); ?>
<?php
    $deliveryAreaRows = old('areas');

    if (! is_array($deliveryAreaRows)) {
        $deliveryAreaRows = [];

        foreach ($pinSectors as $pin => $sectors) {
            foreach ($sectors as $sector) {
                $deliveryAreaRows[] = ['pincode' => $pin, 'sector' => $sector];
            }
        }
    }
?>
<div class="p-4 md:p-6">
    <form method="POST" action="<?php echo e(route('dashboard.settings.delivery-areas.update')); ?>" class="mx-auto max-w-4xl space-y-5">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-black text-slate-900">Manage Delivery Areas</h1>
                <p class="mt-1 text-sm text-slate-500">Add the sectors served under each 6-digit PIN Code. Checkout will auto-fill the PIN when a customer selects a sector.</p>
            </div>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold text-white hover:bg-blue-700">
                <i class="fa-solid fa-floppy-disk"></i> Save Areas
            </button>
        </div>

        <?php if($errors->any()): ?>
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"><?php echo e($errors->first()); ?></div>
        <?php endif; ?>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-black text-slate-900">PIN Code &amp; Sector List</h2>
                    <p class="mt-1 text-xs text-slate-500">Each row creates one selectable sector in checkout.</p>
                </div>
                <button type="button" onclick="addAreaRow()" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-bold text-white hover:bg-slate-700"><i class="fa-solid fa-plus"></i> Add Area</button>
            </div>
            <div class="mt-5 overflow-x-auto">
                <div class="min-w-[520px]">
                    <div class="grid grid-cols-[180px_1fr_44px] gap-3 px-1 pb-2 text-xs font-bold uppercase tracking-wide text-slate-500"><span>PIN Code</span><span>Sector / Area</span><span></span></div>
                    <div id="areaRows" class="space-y-2"></div>
                </div>
            </div>
        </section>

        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white hover:bg-blue-700"><i class="fa-solid fa-floppy-disk"></i> Save Delivery Areas</button>
    </form>
</div>

<script>
const savedAreas = <?php echo json_encode($deliveryAreaRows, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
const areaRows = document.getElementById('areaRows');
let nextAreaIndex = 0;

function addAreaRow(area = { pincode: '', sector: '' }) {
    const index = nextAreaIndex++;
    const row = document.createElement('div');
    row.className = 'grid grid-cols-[180px_1fr_44px] gap-3';
    row.innerHTML = `<input required inputmode="numeric" pattern="\\d{6}" maxlength="6" name="areas[${index}][pincode]" value="${escapeHtml(area.pincode || '')}" placeholder="e.g. 201301" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm font-semibold outline-none focus:border-blue-500"><input required maxlength="100" name="areas[${index}][sector]" value="${escapeHtml(area.sector || '')}" placeholder="e.g. Sector 63" class="rounded-xl border border-slate-300 px-3 py-2.5 text-sm font-semibold outline-none focus:border-blue-500"><button type="button" aria-label="Remove area" class="rounded-xl bg-red-50 text-red-500 hover:bg-red-100"><i class="fa-solid fa-trash-can"></i></button>`;
    row.querySelector('button').addEventListener('click', () => row.remove());
    areaRows.appendChild(row);
}

function escapeHtml(value) {
    const node = document.createElement('div');
    node.textContent = value;
    return node.innerHTML;
}

(savedAreas.length ? savedAreas : [{ pincode: '', sector: '' }]).forEach(addAreaRow);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\dashboard\settings\delivery-areas.blade.php ENDPATH**/ ?>
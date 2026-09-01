<?php $__env->startSection('title', 'Invoice ' . ($order->order_number ?? '#'.$order->id)); ?>

<?php
    $shipping = is_array($order->shipping_address) ? $order->shipping_address : [];
    $invoiceNumber = $order->order_number ?? ('INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT));
    $invoiceDate = optional($order->created_at)->format('d M Y, h:i A') ?? '0';
    $customerName = $shipping['name'] ?? (optional($order->user)->name ?: '0');
    $customerPhone = $shipping['phone'] ?? (optional($order->user)->phone ?: '0');
    $customerAddress = trim(collect([
        $shipping['address'] ?? null,
        $shipping['sector'] ?? null,
        $shipping['city'] ?? null,
        $shipping['state'] ?? null,
        $shipping['pincode'] ?? null,
    ])->filter()->implode(', ')) ?: '0';
    $placeOfSupply = $shipping['state'] ?? '0';
    $deliveryCharge = (float) ($order->delivery_charge ?? $order->shipping_cost ?? 0);
    $serviceCharge = (float) ($order->service_charge ?? 0);
    $serviceChargePercent = $order->service_charge_percent;
    $taxAmount = (float) ($order->tax ?? 0);
    $discount = (float) ($order->discount ?? 0);
?>

<?php $__env->startSection('styles'); ?>
<style>
body > footer {
    display: none !important;
}

@page {
    size: A4;
    margin: 12mm;
}
@media print {
    html,
    body {
        background: #f3f6fb !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    body > header,
    body > footer,
    #mobile-sidebar,
    #sidebar-overlay,
    #cart-overlay,
    #cart-drawer {
        display: none !important;
    }
    body > div[class*="pt-"] {
        padding-top: 0 !important;
    }
    main {
        display: block !important;
    }
    .invoice-page {
        background: #f3f6fb !important;
        padding: 0 !important;
    }
    .invoice-shell {
        box-shadow: 0 24px 80px rgba(15,23,42,0.08) !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 24px !important;
        max-width: 960px !important;
    }
    .invoice-print-hide {
        display: none !important;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="invoice-page bg-[#f3f6fb] px-3 py-4">
    <div class="invoice-shell mx-auto max-w-[960px] overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.08)]">
        <div class="bg-gradient-to-r from-[#0f766e] via-[#0f8a79] to-[#164e63] px-5 py-4 text-white">
            <div class="flex items-start justify-between gap-4">
                <div class="flex min-w-0 items-center gap-3">
                    <div class="flex h-14 w-14 items-center justify-center overflow-hidden rounded-2xl bg-white/10">
                        <img src="<?php echo e($brandLogoUrl); ?>" alt="FarmSea" class="h-10 w-10 object-contain">
                    </div>
                    <div>
                        <h1 class="mt-1 text-xl font-black tracking-[-0.03em] md:text-2xl">FarmSea Invoice</h1>
                        <p class="mt-1 text-xs text-white/80">Order invoice</p>
                    </div>
                </div>

                <div class="grid gap-2 sm:grid-cols-2">
                    <div class="rounded-xl bg-white/10 px-3 py-2">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/65">Invoice No</p>
                        <p class="mt-1 text-xs font-bold"><?php echo e($invoiceNumber); ?></p>
                    </div>
                    <div class="rounded-xl bg-white/10 px-3 py-2">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/65">Date</p>
                        <p class="mt-1 text-xs font-bold"><?php echo e($invoiceDate); ?></p>
                    </div>
                    <div class="rounded-xl bg-white/10 px-3 py-2">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/65">Payment</p>
                        <p class="mt-1 text-xs font-bold uppercase"><?php echo e($order->payment_method ?: '0'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-3 border-b border-slate-200 px-5 py-4 md:grid-cols-2">
            <div class="rounded-xl bg-slate-50 px-3 py-3">
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Bill To</p>
                <p class="mt-2 text-sm font-black text-slate-900"><?php echo e($customerName); ?></p>
                <p class="mt-1 text-xs leading-5 text-slate-600">Phone: <?php echo e($customerPhone); ?></p>
                <p class="text-xs leading-5 text-slate-600">Address: <?php echo e($customerAddress); ?></p>
            </div>

            <div class="rounded-xl bg-slate-50 px-3 py-3">
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Other Details</p>
                <p class="mt-2 text-xs leading-5 text-slate-600">Customer ID: <?php echo e($order->user_id ?: '0'); ?></p>
                <p class="text-xs leading-5 text-slate-600">Place of Supply: <?php echo e($placeOfSupply); ?></p>
                <p class="text-xs leading-5 text-slate-600">Delivery Slot: <?php echo e($order->delivery_slot_label ?: '0'); ?></p>
            </div>
        </div>

        <div class="px-5 py-4">
            <div class="overflow-hidden rounded-xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">#</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Item</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Pack</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Qty</th>
                            <th class="px-3 py-2.5 text-left text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Rate</th>
                            <th class="px-3 py-2.5 text-right text-[10px] font-bold uppercase tracking-[0.16em] text-slate-500">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        <?php $__empty_1 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="px-3 py-3 text-xs font-bold text-slate-400"><?php echo e($index + 1); ?></td>
                                <td class="px-3 py-3">
                                    <p class="text-xs font-bold text-slate-900"><?php echo e($item->product->name ?? '0'); ?></p>
                                    <p class="mt-1 text-[11px] text-slate-500"><?php echo e($item->unit ?: '0'); ?></p>
                                </td>
                                <td class="px-3 py-3 text-xs text-slate-700"><?php echo e($item->variant_label ?: '0'); ?></td>
                                <td class="px-3 py-3 text-xs text-slate-700"><?php echo e($item->quantity ?: '0'); ?></td>
                                <td class="px-3 py-3 text-xs text-slate-700">&#8377;<?php echo e(number_format((float) ($item->unit_price ?? 0), 2)); ?></td>
                                <td class="px-3 py-3 text-right text-xs font-bold text-slate-900">&#8377;<?php echo e(number_format((float) ($item->subtotal ?? 0), 2)); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="px-3 py-8 text-center text-xs text-slate-400">0</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-[minmax(0,1fr)_320px]">
                <div class="rounded-xl bg-slate-50 px-3 py-3">
                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Note</p>
                    <p class="mt-2 text-xs leading-5 text-slate-600">Only applicable charges are shown in this invoice.</p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-3">
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-semibold text-slate-800">&#8377;<?php echo e(number_format((float) ($order->subtotal ?? 0), 2)); ?></span>
                        </div>
                        <?php if($discount > 0): ?>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Discount</span>
                                <span class="font-semibold text-green-700">-&#8377;<?php echo e(number_format($discount, 2)); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if($deliveryCharge > 0): ?>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Delivery</span>
                                <span class="font-semibold text-slate-800">&#8377;<?php echo e(number_format($deliveryCharge, 2)); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if($serviceCharge > 0): ?>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">
                                    &#8505;&#65039; Service Charge
                                    <?php if($serviceChargePercent): ?>
                                        (<?php echo e(rtrim(rtrim(number_format($serviceChargePercent, 2), '0'), '.')); ?>%)
                                    <?php endif; ?>
                                </span>
                                <span class="font-semibold text-slate-800">&#8377;<?php echo e(number_format($serviceCharge, 2)); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if($taxAmount > 0): ?>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Tax</span>
                                <span class="font-semibold text-slate-800">&#8377;<?php echo e(number_format($taxAmount, 2)); ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="border-t border-dashed border-slate-200 pt-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-black text-slate-900">Grand Total</span>
                                <span class="text-lg font-black text-[#0f766e]">&#8377;<?php echo e(number_format((float) ($order->total ?? 0), 2)); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="invoice-print-hide mx-auto mt-5 flex max-w-[960px] flex-wrap items-center justify-end gap-3">
        <a href="<?php echo e(route('frontend.order.show', $order->id)); ?>" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            Back
        </a>
        <button type="button" onclick="window.print()" class="inline-flex items-center justify-center rounded-xl bg-[#0f766e] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0d665f]">
            Download Invoice
        </button>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php if($autoPrint): ?>
<script>
window.addEventListener('load', () => {
    window.setTimeout(() => {
        window.print();
    }, 350);
});
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\BMDU Work\FarmSea-dashboard\resources\views\frontend\invoice.blade.php ENDPATH**/ ?>
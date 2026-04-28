@extends('frontend.layouts.app')
@section('title', 'Invoice ' . ($order->order_number ?? '#'.$order->id))

@php
    $shipping = is_array($order->shipping_address) ? $order->shipping_address : [];
    $invoiceNumber = $order->order_number ?? ('INV-' . str_pad($order->id, 6, '0', STR_PAD_LEFT));
    $invoiceDate = optional($order->created_at)->format('d M Y, h:i A') ?? '0';
    $customerName = $shipping['name'] ?? (optional($order->user)->name ?: '0');
    $customerPhone = $shipping['phone'] ?? (optional($order->user)->phone ?: '0');
    $customerAddress = trim(collect([
        $shipping['address'] ?? null,
        $shipping['city'] ?? null,
        $shipping['state'] ?? null,
        $shipping['pincode'] ?? null,
    ])->filter()->implode(', ')) ?: '0';
    $placeOfSupply = $shipping['state'] ?? '0';
    $deliveryCharge = (float) ($order->delivery_charge ?? $order->shipping_cost ?? 0);
    $platformFee = (float) ($order->platform_fee ?? 0);
    $taxAmount = (float) ($order->tax ?? 0);
    $discount = (float) ($order->discount ?? 0);
@endphp

@section('content')
<style>
@page {
    size: A4;
    margin: 12mm;
}
@media print {
    .invoice-shell {
        box-shadow: none !important;
        border: 0 !important;
        border-radius: 0 !important;
        max-width: none !important;
    }
    .invoice-print-hide {
        display: none !important;
    }
}
</style>

<div class="bg-[#f3f6fb] px-3 py-4 print:bg-white">
    <div class="invoice-shell mx-auto max-w-[960px] overflow-hidden rounded-[24px] border border-slate-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.08)]">
        <div class="bg-gradient-to-r from-[#0f766e] via-[#0f8a79] to-[#164e63] px-5 py-4 text-white">
            <div class="flex items-start justify-between gap-4">
                <div class="flex min-w-0 items-center gap-3">
                    <div class="flex h-14 w-14 items-center justify-center overflow-hidden rounded-2xl bg-white/10">
                        <img src="{{ asset('images/Farmsea.webp') }}" alt="FarmSea" class="h-10 w-10 object-contain">
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-white/70">Tax Invoice</p>
                        <h1 class="mt-1 text-xl font-black tracking-[-0.03em] md:text-2xl">FarmSea Invoice</h1>
                        <p class="mt-1 text-xs text-white/80">Order invoice</p>
                    </div>
                </div>

                <div class="grid gap-2 sm:grid-cols-2">
                    <div class="rounded-xl bg-white/10 px-3 py-2">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/65">Invoice No</p>
                        <p class="mt-1 text-xs font-bold">{{ $invoiceNumber }}</p>
                    </div>
                    <div class="rounded-xl bg-white/10 px-3 py-2">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/65">Date</p>
                        <p class="mt-1 text-xs font-bold">{{ $invoiceDate }}</p>
                    </div>
                    <div class="rounded-xl bg-white/10 px-3 py-2">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/65">Status</p>
                        <p class="mt-1 text-xs font-bold">{{ $order->status_label }}</p>
                    </div>
                    <div class="rounded-xl bg-white/10 px-3 py-2">
                        <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-white/65">Payment</p>
                        <p class="mt-1 text-xs font-bold uppercase">{{ $order->payment_method ?: '0' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-3 border-b border-slate-200 px-5 py-4 md:grid-cols-3">
            <div class="rounded-xl bg-slate-50 px-3 py-3">
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Seller</p>
                <p class="mt-2 text-sm font-black text-slate-900">FarmSea</p>
                <p class="mt-1 text-xs leading-5 text-slate-600">Address: 0</p>
                <p class="text-xs leading-5 text-slate-600">Phone: 0</p>
                <p class="text-xs leading-5 text-slate-600">Email: admin@farmsea.in</p>
                <p class="text-xs leading-5 text-slate-600">GSTIN: 0</p>
            </div>

            <div class="rounded-xl bg-slate-50 px-3 py-3">
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Bill To</p>
                <p class="mt-2 text-sm font-black text-slate-900">{{ $customerName }}</p>
                <p class="mt-1 text-xs leading-5 text-slate-600">Phone: {{ $customerPhone }}</p>
                <p class="text-xs leading-5 text-slate-600">Address: {{ $customerAddress }}</p>
            </div>

            <div class="rounded-xl bg-slate-50 px-3 py-3">
                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Other Details</p>
                <p class="mt-2 text-xs leading-5 text-slate-600">Customer ID: {{ $order->user_id ?: '0' }}</p>
                <p class="text-xs leading-5 text-slate-600">Place of Supply: {{ $placeOfSupply }}</p>
                <p class="text-xs leading-5 text-slate-600">Reference No: 0</p>
                <p class="text-xs leading-5 text-slate-600">HSN/SAC: 0</p>
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
                        @forelse($order->items as $index => $item)
                            <tr>
                                <td class="px-3 py-3 text-xs font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-3 py-3">
                                    <p class="text-xs font-bold text-slate-900">{{ $item->product->name ?? '0' }}</p>
                                    <p class="mt-1 text-[11px] text-slate-500">{{ $item->unit ?: '0' }}</p>
                                </td>
                                <td class="px-3 py-3 text-xs text-slate-700">{{ $item->variant_label ?: '0' }}</td>
                                <td class="px-3 py-3 text-xs text-slate-700">{{ $item->quantity ?: '0' }}</td>
                                <td class="px-3 py-3 text-xs text-slate-700">&#8377;{{ number_format((float) ($item->unit_price ?? 0), 2) }}</td>
                                <td class="px-3 py-3 text-right text-xs font-bold text-slate-900">&#8377;{{ number_format((float) ($item->subtotal ?? 0), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-8 text-center text-xs text-slate-400">0</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 grid gap-4 md:grid-cols-[minmax(0,1fr)_320px]">
                <div class="rounded-xl bg-slate-50 px-3 py-3">
                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Note</p>
                    <p class="mt-2 text-xs leading-5 text-slate-600">Unavailable fields in this invoice are shown as <span class="font-bold text-slate-900">0</span>. Authorized Signature: 0</p>
                </div>

                <div class="rounded-xl border border-slate-200 bg-white p-3">
                    <div class="space-y-2 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-semibold text-slate-800">&#8377;{{ number_format((float) ($order->subtotal ?? 0), 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Discount</span>
                            <span class="font-semibold text-slate-800">&#8377;{{ number_format($discount, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Delivery</span>
                            <span class="font-semibold text-slate-800">&#8377;{{ number_format($deliveryCharge, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Platform Fee</span>
                            <span class="font-semibold text-slate-800">&#8377;{{ number_format($platformFee, 2) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Tax</span>
                            <span class="font-semibold text-slate-800">&#8377;{{ number_format($taxAmount, 2) }}</span>
                        </div>
                        <div class="border-t border-dashed border-slate-200 pt-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-black text-slate-900">Grand Total</span>
                                <span class="text-lg font-black text-[#0f766e]">&#8377;{{ number_format((float) ($order->total ?? 0), 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="invoice-print-hide mx-auto mt-5 flex max-w-[960px] flex-wrap items-center justify-end gap-3">
        <a href="{{ route('frontend.order.show', $order->id) }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50">
            Back
        </a>
        <button type="button" onclick="window.print()" class="inline-flex items-center justify-center rounded-xl bg-[#0f766e] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#0d665f]">
            Download Invoice
        </button>
    </div>
</div>
@endsection

@section('scripts')
@if($autoPrint)
<script>
window.addEventListener('load', () => {
    window.print();
});
</script>
@endif
@endsection

@extends('frontend.layouts.app')

@section('title', 'Coupons & Offers')

@section('content')
<section class="bg-[linear-gradient(180deg,#eef7ff_0%,#f5f6fa_42%)] px-4 py-10 md:py-14">
    <div class="mx-auto max-w-6xl">
        <div class="overflow-hidden rounded-[30px] bg-gradient-to-br from-blue-900 via-indigo-900 to-emerald-800 px-6 py-9 text-white shadow-xl md:px-10 md:py-12">
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 text-[10px] font-black uppercase tracking-[0.2em] text-yellow-300 ring-1 ring-white/15">
                        <i class="fa-solid fa-bolt"></i> Live Savings
                    </span>
                    <h1 class="mt-4 text-3xl font-black tracking-tight md:text-5xl">Coupons &amp; Offers</h1>
                    <p class="mt-3 max-w-2xl text-sm font-medium leading-6 text-blue-100 md:text-base">Saare currently active aur valid FarmSea deals ek hi jagah. Offer choose karo aur fresh products par save karo.</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-white/10 px-5 py-4 text-center ring-1 ring-white/15"><p class="text-2xl font-black">{{ $offers->count() }}</p><p class="text-[10px] font-bold uppercase tracking-wider text-blue-100">Offers</p></div>
                    <div class="rounded-2xl bg-white/10 px-5 py-4 text-center ring-1 ring-white/15"><p class="text-2xl font-black">{{ $coupons->count() }}</p><p class="text-[10px] font-bold uppercase tracking-wider text-blue-100">Coupons</p></div>
                </div>
            </div>
        </div>

        <div class="mt-10 flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100 text-amber-600"><i class="fa-solid fa-bolt"></i></span>
            <div><h2 class="text-2xl font-black text-slate-900">Active Offers</h2><p class="text-xs font-semibold text-slate-400">Automatically available on eligible products</p></div>
        </div>

        <div class="mt-5 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse($offers as $offer)
                @php
                    $offerDiscount = $offer->type === 'percent'
                        ? rtrim(rtrim(number_format((float) $offer->value, 2, '.', ''), '0'), '.') . '% OFF'
                        : 'Rs' . number_format((float) $offer->value, 0) . ' OFF';
                    $offerLink = $offer->product ? route('frontend.product.show', $offer->product->slug) : route('frontend.products');
                @endphp
                <article class="flex flex-col rounded-[24px] border border-amber-100 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex items-start justify-between gap-3">
                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-amber-100 text-amber-600"><i class="fa-solid fa-percent"></i></span>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-[9px] font-black uppercase tracking-wider text-emerald-700">Active</span>
                    </div>
                    <p class="mt-4 text-2xl font-black text-amber-600">{{ $offerDiscount }}</p>
                    <h3 class="mt-2 text-lg font-black text-slate-900">{{ $offer->title ?: 'Special Offer' }}</h3>
                    <p class="mt-2 line-clamp-3 text-[13px] font-medium leading-6 text-slate-500">{{ $offer->description ?: 'Limited-time savings on fresh FarmSea products.' }}</p>
                    <div class="mt-4 flex flex-wrap gap-2 text-[10px] font-bold">
                        <span class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-slate-600"><i class="fa-solid fa-box mr-1"></i>{{ $offer->product?->name ?: 'All Products' }}</span>
                        @if($offer->expires_at)<span class="rounded-lg bg-red-50 px-2.5 py-1.5 text-red-600"><i class="fa-regular fa-clock mr-1"></i>Ends {{ $offer->expires_at->format('d M Y') }}</span>@endif
                    </div>
                    <a href="{{ $offerLink }}" class="mt-5 inline-flex items-center justify-center gap-2 rounded-xl bg-amber-500 px-4 py-3 text-xs font-black uppercase tracking-wider text-white transition hover:bg-amber-600">View Products <i class="fa-solid fa-arrow-right"></i></a>
                </article>
            @empty
                <div class="md:col-span-2 lg:col-span-3 rounded-[24px] border border-dashed border-slate-200 bg-white p-10 text-center text-sm font-semibold text-slate-400">Abhi koi active offer available nahi hai.</div>
            @endforelse
        </div>

        <div class="mt-12 flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-blue-700"><i class="fa-solid fa-ticket"></i></span>
            <div><h2 class="text-2xl font-black text-slate-900">Valid Coupons</h2><p class="text-xs font-semibold text-slate-400">Code copy karke cart me apply karo</p></div>
        </div>

        <div class="mt-5 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse($coupons as $coupon)
                <article class="rounded-[24px] border border-blue-100 bg-white p-5 shadow-sm">
                    <div class="flex items-start justify-between gap-3"><span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-100 text-blue-700"><i class="fa-solid fa-ticket-simple"></i></span><span class="text-xl font-black text-blue-700">{{ $coupon->type === 'percent' ? rtrim(rtrim(number_format((float) $coupon->value, 2, '.', ''), '0'), '.') . '%' : 'Rs' . number_format((float) $coupon->value, 0) }} OFF</span></div>
                    <p class="mt-4 text-xs font-semibold leading-5 text-slate-500">{{ $coupon->min_order_amount ? 'Minimum order Rs' . number_format((float) $coupon->min_order_amount, 0) : 'No minimum order amount' }}</p>
                    <button type="button" onclick="copyDealCode(this, @js($coupon->code))" class="mt-4 flex w-full items-center justify-between rounded-xl border border-dashed border-blue-300 bg-blue-50 px-4 py-3 text-left text-sm font-black tracking-wider text-blue-700"><span>{{ $coupon->code }}</span><span class="text-[10px] uppercase"><i class="fa-regular fa-copy mr-1"></i>Copy</span></button>
                    @if($coupon->expires_at)<p class="mt-3 text-[10px] font-bold text-slate-400">Valid till {{ $coupon->expires_at->format('d M Y') }}</p>@endif
                </article>
            @empty
                <div class="md:col-span-2 lg:col-span-3 rounded-[24px] border border-dashed border-slate-200 bg-white p-10 text-center text-sm font-semibold text-slate-400">Abhi koi valid coupon available nahi hai.</div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
function copyDealCode(button, code) {
    navigator.clipboard?.writeText(code);
    const label = button.querySelector('span:last-child');
    label.innerHTML = '<i class="fa-solid fa-check mr-1"></i>Copied';
    setTimeout(() => label.innerHTML = '<i class="fa-regular fa-copy mr-1"></i>Copy', 1600);
}
</script>
@endsection

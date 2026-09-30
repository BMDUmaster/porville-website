@extends('frontend.layouts.app')
@section('title', 'About Us — Porville')

@section('content')

<section class="bg-black py-16 md:py-24">
    <div class="mx-auto max-w-4xl px-4 text-center">
        <p class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-amber-400">About hello  Porville</p>
        <h1 class="font-classic mt-4 text-4xl font-bold leading-tight text-white md:text-5xl">
            Fresh Cut. Pure Standards.
        </h1>
        <p class="mt-3 text-sm font-semibold uppercase tracking-[0.2em] text-stone-400">Since 1986</p>
    </div>
</section>

<section class="bg-white py-14 md:py-20">
    <div class="mx-auto max-w-3xl space-y-6 px-4 text-[15px] leading-8 text-slate-700">
        <p>
            Porville was founded on a simple promise: to elevate the quality of meat available to families in Delhi.
            Sourced under strict quality guidelines, prepared in advanced temperature-controlled clean facilities, and
            custom-sliced fresh for every order, we have redefined freshness standards.
        </p>
        <p>
            We believe that the best meals start with the finest ingredients. That is why our chickens are
            pasture-raised on local farms without antibiotic feeds, our mutton is selected from grass-fed mountain
            goats, and our farm-fresh eggs are collected daily.
        </p>
        <p>
            Unlike standard supermarkets, we do not package or freeze our meat in advance. When you order from
            Porville, our master butchers cut the meat exactly to your specifications (curry cuts, steaks, boneless
            cubes, etc.) only after your order is confirmed.
        </p>
        <p>
            We seal the meat vacuum-tight to protect its natural moisture and flavor, shipping it inside
            temperature-controlled chilled bags to keep it pristine and fresh. That is the Porville standard.
        </p>
    </div>
</section>

<section class="bg-[#faf7f0] py-14 md:py-16">
    <div class="mx-auto max-w-3xl px-4">
        <div class="rounded-2xl border border-amber-100 bg-white p-7 shadow-sm md:p-9">
            <div class="flex items-start gap-4">
                <span class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-shield-halved text-xl"></i>
                </span>
                <div>
                    <h2 class="font-classic text-2xl font-bold text-slate-900">FSSAI Food Safety Registered</h2>
                    <p class="mt-3 text-[14px] leading-7 text-slate-600">
                        Porville operates under FSSAI (Food Safety and Standards Authority of India) food safety
                        guidelines. Our business is registered with the Government of Delhi, Department of Food Safety.
                    </p>
                    <div class="mt-4 grid gap-2 text-[13px] text-slate-600 sm:grid-cols-2">
                        <p><span class="font-bold text-slate-900">FoSCoS Reference No:</span> 30260223123490898</p>
                        <p><span class="font-bold text-slate-900">Registration Date:</span> 23-02-2026</p>
                    </div>
                    <p class="mt-4 text-[13px] leading-6 text-slate-500">
                        For food-safety and ordering questions, see our <a href="{{ route('frontend.faq') }}" class="font-bold text-amber-700 hover:underline">FAQs</a>.
                        Private certificate details are not displayed online.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-14 md:py-16">
    <div class="mx-auto max-w-5xl px-4 text-center">
        <h2 class="font-classic text-2xl font-bold text-slate-900 md:text-3xl">Ready to taste the difference?</h2>
        <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-slate-500">Order fresh-cut meat, delivered chilled to your door.</p>
        <a href="{{ route('frontend.products') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl border border-amber-500 bg-black px-7 py-3.5 text-[12px] font-bold uppercase tracking-[0.14em] text-white transition hover:bg-neutral-900">
            <i class="fa-solid fa-cart-shopping text-amber-400"></i> Shop Fresh Cuts
        </a>
    </div>
</section>
@endsection

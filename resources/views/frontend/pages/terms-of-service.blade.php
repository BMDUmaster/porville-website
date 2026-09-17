@extends('frontend.layouts.app')
@section('title', 'Terms & Conditions — Porville')

@section('content')

<section class="bg-black py-14 md:py-20">
    <div class="mx-auto max-w-4xl px-4 text-center">
        <p class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-amber-400">Legal</p>
        <h1 class="font-classic mt-3 text-4xl font-bold text-white md:text-5xl">Terms &amp; Conditions</h1>
        <p class="mt-4 text-xs font-semibold uppercase tracking-[0.2em] text-stone-400">Last Updated: June 2026</p>
    </div>
</section>

<section class="bg-white py-14 md:py-16">
    <div class="mx-auto max-w-3xl px-4">
        <p class="mb-10 text-[15px] leading-8 text-slate-600">
            Porville is a fresh meat and food delivery platform offering chicken, mutton, quail, eggs, and
            ready-to-eat products. By using this website or placing an order, you agree to the terms below.
        </p>

        @php
            $sections = [
                ['title' => '1. Acceptance of Terms', 'body' => 'By accessing the Porville website or placing an order, the customer agrees to be bound by these Terms & Conditions along with our Refund & Cancellation Policy, Privacy Policy, and Shipping & Delivery Policy. If you do not agree, please do not use the service.'],
                ['title' => '2. Products and Availability', 'body' => 'Product availability may change depending on stock. Items shown may become unavailable during high demand or due to sourcing conditions. We reserve the right to limit quantities or discontinue any product without prior notice.'],
                ['title' => '3. Prices, Offers and Charges', 'body' => 'Prices, offers, coupons, delivery charges, and packaging charges may change from time to time based on business settings and operating costs. The applicable charges are those shown at the time your order is placed.'],
                ['title' => '4. Customer Information', 'body' => 'Customers must provide correct name, mobile number, address, and delivery details. Incorrect or incomplete details may cause delivery failure or delay, for which Porville is not responsible.'],
                ['title' => '5. Order Verification and Cancellation by Porville', 'body' => 'Porville may cancel any order that appears suspicious, fake, repeated, or based on misuse. We may also restrict accounts involved in repeated refusals, chargebacks, or fraudulent activity.'],
                ['title' => '6. Perishable Products and Complaints', 'body' => 'Fresh meat and food products are perishable and must be checked immediately after delivery. Any complaint regarding an order must be raised within 2 hours of delivery, with valid proof, so the product can be verified in its original condition. Please refer to our Refund & Cancellation Policy for the full complaint process.', 'list' => [
                    'Inspect the product immediately upon receipt.',
                    'Store meat, eggs, and ready-to-eat items at the recommended temperature.',
                    'Preserve the product in original condition for inspection until any complaint is resolved.',
                ]],
                ['title' => '7. Limitation and Customer Rights', 'body' => 'Nothing in these terms should be read to override the rights available to customers under applicable Indian law. Where any clause conflicts with a mandatory legal right, that legal right prevails.'],
            ];
        @endphp

        <div class="space-y-9">
            @foreach($sections as $section)
                <div>
                    <h2 class="font-classic text-xl font-bold text-slate-900">{{ $section['title'] }}</h2>
                    <p class="mt-3 text-[14px] leading-7 text-slate-600">{{ $section['body'] }}</p>
                    @if(!empty($section['list']))
                        <ul class="mt-4 space-y-2">
                            @foreach($section['list'] as $item)
                                <li class="flex items-start gap-2.5 text-[14px] leading-6 text-slate-600">
                                    <i class="fa-solid fa-check mt-1 text-[11px] text-amber-500"></i>
                                    <span>{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

@extends('frontend.layouts.app')
@section('title', 'Shipping & Delivery Policy — Porville')

@section('content')

<section class="bg-black py-14 md:py-20">
    <div class="mx-auto max-w-4xl px-4 text-center">
        <p class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-amber-400">Legal</p>
        <h1 class="font-classic mt-3 text-4xl font-bold text-white md:text-5xl">Shipping &amp; Delivery Policy</h1>
        <p class="mt-4 text-xs font-semibold uppercase tracking-[0.2em] text-stone-400">Last Updated: June 2026</p>
    </div>
</section>

<section class="bg-white py-14 md:py-16">
    <div class="mx-auto max-w-3xl px-4">
        <p class="mb-10 text-[15px] leading-8 text-slate-600">
            Porville delivers fresh meat and food products to selected serviceable locations only. This policy
            explains delivery timing, customer responsibilities, and the charges that may apply.
        </p>

        @php
            $sections = [
                ['title' => '1. Serviceable Locations', 'body' => ['Porville delivers fresh meat and food products to selected serviceable locations only. Orders placed for addresses outside our current delivery area may not be accepted or may be cancelled.']],
                ['title' => '2. Delivery Time', 'body' => ['Delivery time may depend on location, order volume, product availability, weather, traffic, and operational conditions. Estimated delivery times are indicative and not guaranteed.']],
                [
                    'title' => '3. Customer Responsibilities',
                    'body' => ['The customer must provide a correct address and remain available to receive the order at the delivery time.'],
                    'list' => [
                        'Ensure the delivery address and PIN code are accurate and complete.',
                        'Keep the registered mobile number reachable for delivery coordination.',
                        'Be available, or arrange an authorised person, to receive the order.',
                    ],
                ],
                [
                    'title' => '4. Failed or Delayed Delivery',
                    'body' => ['Delivery may fail or be delayed if:'],
                    'list' => [
                        'The customer is unavailable to receive the order;',
                        'The phone number is unreachable;',
                        'The address is incorrect or incomplete.',
                    ],
                ],
                ['title' => '5. Storage After Delivery', 'body' => ['Once fresh / perishable products are delivered, the customer must store them properly and at the recommended temperature. Porville is not responsible for deterioration caused by improper storage or handling after delivery.']],
                ['title' => '6. Charges and Free Delivery', 'body' => ['Delivery charges, packaging charges, minimum order value, and free delivery rules may change depending on business settings. The charges applicable to your order are those shown at checkout before you place the order.']],
            ];
        @endphp

        <div class="space-y-9">
            @foreach($sections as $section)
                <div>
                    <h2 class="font-classic text-xl font-bold text-slate-900">{{ $section['title'] }}</h2>
                    <div class="mt-3 space-y-3">
                        @foreach($section['body'] as $para)
                            <p class="text-[14px] leading-7 text-slate-600">{{ $para }}</p>
                        @endforeach
                    </div>
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

@extends('frontend.layouts.app')
@section('title', 'Refund & Cancellation Policy — Porville')

@section('content')

<section class="bg-black py-14 md:py-20">
    <div class="mx-auto max-w-4xl px-4 text-center">
        <p class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-amber-400">Legal</p>
        <h1 class="font-classic mt-3 text-4xl font-bold text-white md:text-5xl">Refund &amp; Cancellation Policy</h1>
        <p class="mt-4 text-xs font-semibold uppercase tracking-[0.2em] text-stone-400">Last Updated: June 2026</p>
    </div>
</section>

<section class="bg-white py-14 md:py-16">
    <div class="mx-auto max-w-3xl px-4">
        <p class="mb-10 text-[15px] leading-8 text-slate-600">
            Porville deals in fresh and perishable food products. This policy explains our complaint window,
            product condition requirements, cancellation rules for Cash on Delivery and prepaid orders, and how
            approved refunds are processed.
        </p>

        @php
            $sections = [
                [
                    'title' => 'A. Complaint Window and Product Condition Policy',
                    'body' => [
                        'Porville deals in fresh and perishable food products. Any complaint regarding an order must be raised within 2 hours of delivery / receipt.',
                        'Complaints received after 2 hours may not be eligible for return, replacement, or refund, unless required under applicable law.',
                        'For complaints raised within 2 hours, the product must be:',
                    ],
                    'list' => [
                        'Stored at the recommended temperature;',
                        'Not consumed, eaten, altered, cooked, washed, mixed, or used;',
                        'Preserved in original condition for inspection;',
                        'Supported with proof such as order ID, bill/invoice, photos, videos, payment confirmation, and delivery details.',
                    ],
                    'outro' => ['If the product has been consumed, cooked, improperly stored, altered, damaged after delivery, or is unavailable for inspection, the complaint may be rejected after verification.'],
                ],
                [
                    'title' => 'B. Fresh and Perishable Product Disclaimer',
                    'body' => [
                        'All meat, poultry, eggs, ready-to-eat items, and similar food products sold by Porville are perishable.',
                        'Porville is not responsible for product deterioration caused by:',
                    ],
                    'list' => [
                        'Delay in receiving the order by the customer;',
                        'Incorrect address or unavailable customer;',
                        'Improper storage after delivery;',
                        'Cooking, reheating, washing, or handling after delivery;',
                        'Complaint raised after the allowed complaint window.',
                    ],
                ],
                [
                    'title' => 'C. Cash on Delivery Dispatch & Cancellation Policy',
                    'body' => [
                        'For COD orders, customers should cancel before preparation / dispatch if they no longer want the order.',
                        'Once a COD order is prepared, packed, or dispatched, cancellation may not be accepted except in genuine cases like wrong order details, unavoidable emergency, or delivery issue.',
                        'If a customer cancels or refuses a COD order after dispatch, Porville may:',
                    ],
                    'list' => [
                        'Recover reasonable delivery, packaging, and handling charges where applicable;',
                        'Restrict or disable COD for future orders;',
                        'Require advance payment for future purchases;',
                        'Cancel repeated COD orders in case of misuse, fake orders, or repeated refusals.',
                    ],
                ],
                [
                    'title' => 'D. Prepaid Order Cancellation and Refund Policy',
                    'body' => [
                        'All prepaid orders placed through Razorpay or any online payment gateway are considered confirmed once payment is successful and the order is placed.',
                        'Once a prepaid order is confirmed, especially for fresh / perishable products, cancellation may not be accepted if the order has been prepared, packed, or dispatched.',
                        'Refund, replacement, or store credit may be considered only in genuine verified cases:',
                    ],
                    'list' => [
                        'Non-delivery due to reasons attributable to Porville;',
                        'Wrong product delivered;',
                        'Product received spoiled, damaged, or defective, subject to verification;',
                        'Duplicate payment or payment gateway error;',
                        'Any situation where a refund is required under applicable law.',
                    ],
                    'outro' => [
                        'A customer cannot claim a refund only because they changed their mind after successful payment / order confirmation, unless cancellation is accepted before preparation or dispatch.',
                        'Razorpay is only the payment gateway. Refund approval / rejection will be governed by Porville policy, subject to applicable law.',
                    ],
                ],
                [
                    'title' => 'E. Refund Processing',
                    'body' => ['If a refund is approved after verification, it may be processed through:'],
                    'list' => [
                        'Original payment method;',
                        'Store credit;',
                        'Coupon;',
                        'Wallet credit;',
                        'Any other mode decided by Porville depending on the case.',
                    ],
                    'outro' => ['Refund processing time may depend on the payment gateway, bank, or service provider.'],
                ],
            ];
        @endphp

        <div class="space-y-10">
            @foreach($sections as $section)
                <div class="rounded-2xl border border-amber-100 bg-[#faf7f0] p-6 md:p-7">
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
                    @if(!empty($section['outro']))
                        <div class="mt-4 space-y-3">
                            @foreach($section['outro'] as $para)
                                <p class="text-[14px] leading-7 text-slate-600">{{ $para }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection

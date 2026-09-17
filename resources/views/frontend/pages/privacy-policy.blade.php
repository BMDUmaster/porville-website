@extends('frontend.layouts.app')
@section('title', 'Privacy Policy — Porville')

@section('content')

<section class="bg-black py-14 md:py-20">
    <div class="mx-auto max-w-4xl px-4 text-center">
        <p class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-amber-400">Legal</p>
        <h1 class="font-classic mt-3 text-4xl font-bold text-white md:text-5xl">Privacy Policy</h1>
        <p class="mt-4 text-xs font-semibold uppercase tracking-[0.2em] text-stone-400">Last Updated: June 2026</p>
    </div>
</section>

<section class="bg-white py-14 md:py-16">
    <div class="mx-auto max-w-3xl px-4">
        <p class="mb-10 text-[15px] leading-8 text-slate-600">
            Porville ("we", "our", or "us") respects your privacy. This policy explains what information we collect
            when you use our website, place an order, or contact us, and how that information is used and protected.
        </p>

        @php
            $sections = [
                [
                    'title' => '1. Information We Collect',
                    'body' => ['Porville may collect the following information to fulfil your orders and provide support:'],
                    'list' => [
                        'Customer name and mobile number',
                        'Email address',
                        'Delivery address and location details',
                        'Order details, cart items, and delivery preferences',
                        'Payment status (success/failure), and delivery details',
                    ],
                    'outro' => ['If you sign in with Google, we receive your name, email, and profile image from the authentication provider.'],
                ],
                [
                    'title' => '2. How We Use Your Information',
                    'body' => ['Information is used only for legitimate business purposes, including:'],
                    'list' => [
                        'Order processing and delivery',
                        'Customer support and complaint handling',
                        'Account management and order history',
                        'Offers, updates, and communication about your orders',
                        'Service improvement and fraud prevention',
                    ],
                ],
                [
                    'title' => '3. Payments',
                    'body' => ["Payment details are processed securely through Razorpay or the applicable payment gateway. Porville does not store your sensitive card, UPI, or banking credentials on its own servers. Payment security is governed by the payment gateway's own systems and policies."],
                ],
                [
                    'title' => '4. Data Sharing',
                    'body' => ['We do not sell your personal data. Customer data may be shared only with the parties required to complete and support your order:'],
                    'list' => [
                        'Delivery partners, to deliver your order',
                        'Payment gateway (Razorpay), to process and verify payments',
                        'Porville support / admin team, to manage orders and resolve issues',
                        'Legal or government authorities, where required by applicable law',
                    ],
                ],
                [
                    'title' => '5. Data Retention and Security',
                    'body' => ['We retain order and account information for as long as needed to provide the service, comply with legal obligations, and resolve disputes. We apply reasonable measures to protect your data, though no method of transmission over the internet is fully secure.'],
                ],
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

        <div class="mt-12 rounded-2xl border border-amber-100 bg-[#faf7f0] p-6 md:p-7">
            <h2 class="font-classic text-xl font-bold text-slate-900">6. Contact Us</h2>
            <p class="mt-3 text-[14px] leading-7 text-slate-600">For any privacy-related questions or requests, you can reach Porville at:</p>
            <div class="mt-4 space-y-2 text-[14px] text-slate-600">
                <p><i class="fa-regular fa-envelope mr-2 text-amber-500"></i><span class="font-bold text-slate-900">Email:</span> porville1986@gmail.com</p>
                <p><i class="fa-solid fa-phone mr-2 text-amber-500"></i><span class="font-bold text-slate-900">Phone:</span> 9217577006</p>
                <p><i class="fa-solid fa-location-dot mr-2 text-amber-500"></i><span class="font-bold text-slate-900">Address:</span> D-1b/1028, Delhi - 110001</p>
            </div>
        </div>
    </div>
</section>
@endsection

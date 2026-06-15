@extends('frontend.layouts.app')
@section('title', 'Contact Us')

@section('styles')
<style>
    .contact-shell {
        background:
            radial-gradient(circle at top left, rgba(34, 197, 94, 0.09), transparent 24%),
            linear-gradient(180deg, #f8fafc 0%, #ffffff 38%, #f8fafc 100%);
    }

    .contact-input {
        border: 1px solid #d8e0ec;
        background: #fff;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .contact-input:focus {
        outline: none;
        border-color: #1d4ed8;
        box-shadow: 0 0 0 4px rgba(29, 78, 216, 0.08);
    }

    .contact-support-card {
        transition: transform .25s ease, box-shadow .25s ease, background-color .25s ease, border-color .25s ease;
    }

    .contact-support-card:hover {
        transform: translateY(-4px);
        background: #16a34a;
        border-color: #16a34a;
        box-shadow: 0 18px 38px rgba(22, 163, 74, 0.22);
    }

    .contact-support-card:hover .contact-support-eyebrow,
    .contact-support-card:hover .contact-support-copy {
        color: rgba(255, 255, 255, 0.9) !important;
    }

    .contact-support-card:hover .contact-support-title {
        color: #ffffff !important;
        font-weight: 900;
    }
</style>
@endsection

@section('content')
@php
    $highlights = [
        ['label' => 'Avg Delivery', 'value' => '2-4 Hours'],
        ['label' => 'Active Partners', 'value' => '600+ Vendors'],
        ['label' => 'Supply Hub', 'value' => 'India'],
        ['label' => 'Fresh Stock', 'value' => '24/7 Updated'],
    ];

    $supportChannels = [
        [
            'eyebrow' => 'Supply Network',
            'title' => 'Farm Vendor Network',
            'description' => 'Helping farms and distribution teams list produce directly to customers.',
            'tone' => 'dark',
        ],
        [
            'eyebrow' => 'Support Division',
            'title' => 'Customer Support',
            'description' => 'Help with orders, delivery tracking, and product quality support.',
            'tone' => 'light',
        ],
    ];

    $inquiryNotes = [
        ['title' => 'Technical Support', 'text' => 'Average response time: 2-4 hours'],
        ['title' => 'Customer Support', 'text' => 'Available 24/7 for delivery and order issues.'],
    ];

    $connectCards = [
        [
            'eyebrow' => '01 / Customer',
            'title' => 'Fresh Support',
            'text' => 'Help with orders, delivery tracking and product queries.',
            'link' => 'support@farmsea.com',
            'href' => 'mailto:support@farmsea.com',
        ],
        [
            'eyebrow' => '02 / Logistics',
            'title' => 'Delivery Help',
            'text' => 'Support for order tracking and delivery issues.',
            'link' => 'logistics@farmsea.com',
            'href' => 'mailto:logistics@farmsea.com',
        ],
        [
            'eyebrow' => '03 / Office',
            'title' => 'Head Office',
            'text' => '350 Agriculture Street, India',
            'link' => '+91 99999 99999',
            'href' => 'tel:+919999999999',
        ],
    ];
@endphp

<section class="contact-shell">
    <div class="mx-auto max-w-7xl px-4 py-10 md:px-6 md:py-14">
        <div class="rounded-[34px] border border-slate-200/80 bg-white/90 p-6 shadow-[0_24px_80px_rgba(15,23,42,0.06)] backdrop-blur md:p-8 lg:p-10">
            <div class="grid gap-8 border-b border-slate-200 pb-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-start lg:gap-10">
                <div>
                    <p class="mb-5 text-[11px] font-extrabold uppercase tracking-[0.34em] text-green-600">Contact FarmSea</p>
                    <h1 class="nunito max-w-[560px] text-[42px] font-extrabold leading-[0.95] text-slate-950 md:text-[58px]">
                        Fresh From Farms.
                        <span class="block text-green-600">Straight From Sea.</span>
                    </h1>
                    <p class="mt-6 max-w-[560px] text-[15px] leading-7 text-slate-500 md:text-base">
                        We connect farmers, fishermen, and local producers directly with customers for fresh vegetables, fruits, dairy, meat and seafood delivered straight from source to home.
                    </p>
                </div>

                <div class="space-y-4">
                    @foreach($supportChannels as $channel)
                        <div class="contact-support-card rounded-[22px] border px-5 py-5 shadow-sm {{ $channel['tone'] === 'dark' ? 'border-slate-900 bg-[#0f172b] text-white' : 'border-slate-200 bg-slate-50 text-slate-900' }}">
                            <p class="contact-support-eyebrow text-[10px] font-extrabold uppercase tracking-[0.24em] {{ $channel['tone'] === 'dark' ? 'text-green-400' : 'text-slate-400' }}">
                                {{ $channel['eyebrow'] }}
                            </p>
                            <h2 class="contact-support-title mt-2 text-[22px] font-extrabold leading-none">
                                {{ $channel['title'] }}
                            </h2>
                            <p class="contact-support-copy mt-3 max-w-[320px] text-[12px] leading-5 {{ $channel['tone'] === 'dark' ? 'text-slate-300' : 'text-slate-500' }}">
                                {{ $channel['description'] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid gap-5 border-b border-slate-200 py-7 sm:grid-cols-2 xl:grid-cols-4">
                @foreach($highlights as $item)
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.24em] text-slate-400">{{ $item['label'] }}</p>
                        <p class="mt-2 text-[22px] font-extrabold leading-none text-slate-950">{{ $item['value'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid gap-8 pt-8 lg:grid-cols-[0.72fr_1.08fr] lg:items-start">
                <div class="lg:pr-6">
                    <h2 class="nunito text-[34px] font-extrabold italic leading-none text-slate-950">Direct Inquiry.</h2>
                    <p class="mt-4 max-w-[320px] text-sm leading-6 text-slate-500">
                        Select the right department for farm response regarding orders, vendors, or product support.
                    </p>

                    <div class="mt-8 space-y-5">
                        @foreach($inquiryNotes as $note)
                            <div class="border-l-2 border-slate-200 pl-4 first:border-green-500">
                                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-slate-900">{{ $note['title'] }}</p>
                                <p class="mt-1 text-[12px] leading-5 text-slate-500">{{ $note['text'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-[28px] border border-slate-200 bg-slate-50 p-5 shadow-[0_10px_35px_rgba(148,163,184,0.12)] md:p-7">
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.28em] text-slate-400">How can we contact you?</p>
                    @if(session('success'))
                        <div class="mt-5 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('frontend.contact.submit') }}" class="mt-6 space-y-5">
                        @csrf
                        <div>
                            <label class="mb-2 block text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-900">Select Department</label>
                            <select name="department" class="contact-input h-12 w-full rounded-2xl px-4 text-sm text-slate-700" required>
                                @foreach(['Customer Support', 'Vendor Partnership', 'Delivery Help', 'Bulk Orders'] as $department)
                                    <option value="{{ $department }}" @selected(old('department') === $department)>{{ $department }}</option>
                                @endforeach
                            </select>
                            @error('department')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-900">Full Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" placeholder="John Doe" class="contact-input h-12 w-full rounded-2xl px-4 text-sm text-slate-700" required>
                                @error('name')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="mb-2 block text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-900">Email Address</label>
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com" class="contact-input h-12 w-full rounded-2xl px-4 text-sm text-slate-700" required>
                                @error('email')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-900">Your Message</label>
                            <textarea name="message" rows="5" placeholder="How can we help you?" class="contact-input w-full rounded-3xl px-4 py-3 text-sm text-slate-700 resize-none" required>{{ old('message') }}</textarea>
                            @error('message')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="inline-flex min-w-[146px] items-center justify-center rounded-2xl bg-green-600 px-6 py-3 text-[11px] font-extrabold uppercase tracking-[0.24em] text-white shadow-[0_14px_28px_rgba(22,163,74,0.28)] transition hover:-translate-y-0.5 hover:bg-green-700">
                            Send Request
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-10 rounded-[30px] border border-slate-200 bg-white px-4 py-6 shadow-[0_18px_55px_rgba(15,23,42,0.05)] md:px-6 md:py-8">
            <div class="mb-5 flex items-end justify-between gap-4 border-b border-slate-200 pb-4">
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.28em] text-slate-400">Reach Out</p>
                    <h2 class="nunito mt-2 text-[34px] font-extrabold leading-none text-slate-950">Connect.</h2>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                @foreach($connectCards as $card)
                    <a href="{{ $card['href'] }}" class="group rounded-[24px] border border-slate-200 bg-gradient-to-b from-white to-slate-50 px-5 py-6 transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-[0_18px_40px_rgba(59,130,246,0.10)]">
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.24em] text-green-600">{{ $card['eyebrow'] }}</p>
                        <h3 class="mt-5 text-[28px] font-extrabold leading-[0.95] text-slate-950">{{ $card['title'] }}</h3>
                        <p class="mt-3 min-h-[48px] text-[13px] leading-6 text-slate-500">{{ $card['text'] }}</p>
                        <span class="mt-8 inline-flex border-b-2 border-slate-900 pb-1 text-[14px] font-extrabold text-slate-950 transition group-hover:border-green-600 group-hover:text-green-600">
                            {{ $card['link'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection

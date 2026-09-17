@extends('frontend.layouts.app')
@section('title', 'Contact Us — Porville')

@section('content')

<section class="bg-black py-14 md:py-20">
    <div class="mx-auto max-w-4xl px-4 text-center">
        <p class="text-[11px] font-extrabold uppercase tracking-[0.3em] text-amber-400">Get In Touch</p>
        <h1 class="font-classic mt-3 text-4xl font-bold text-white md:text-5xl">Contact Porville</h1>
        <p class="mt-4 text-sm leading-7 text-stone-300 md:text-base">
            Questions about an order, delivery, or a bulk request? Our team is here to help.
        </p>
    </div>
</section>

<section class="bg-[#faf7f0] py-14 md:py-20">
    <div class="mx-auto max-w-6xl px-4">
        <div class="grid gap-8 lg:grid-cols-[0.85fr_1.15fr] lg:items-start">
            <div class="space-y-4">
                <div class="rounded-2xl border border-amber-100 bg-white p-6 shadow-sm">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i class="fa-solid fa-phone"></i></span>
                    <p class="mt-4 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Call Us</p>
                    <a href="tel:9217577006" class="mt-1 block text-lg font-bold text-slate-900 hover:text-amber-700">+91 92175 77006</a>
                </div>
                <div class="rounded-2xl border border-amber-100 bg-white p-6 shadow-sm">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i class="fa-regular fa-envelope"></i></span>
                    <p class="mt-4 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Email Us</p>
                    <a href="mailto:porville1986@gmail.com" class="mt-1 block text-lg font-bold text-slate-900 hover:text-amber-700">porville1986@gmail.com</a>
                </div>
                <div class="rounded-2xl border border-amber-100 bg-white p-6 shadow-sm">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 text-amber-600"><i class="fa-solid fa-location-dot"></i></span>
                    <p class="mt-4 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400">Visit Us</p>
                    <p class="mt-1 text-[15px] font-bold leading-6 text-slate-900">D-1b/1028, Sangam Vihar-110080</p>
                </div>
                <div class="rounded-2xl border border-amber-500 bg-black p-6 shadow-sm">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-500/15 text-amber-400"><i class="fa-regular fa-clock"></i></span>
                    <p class="mt-4 text-[11px] font-bold uppercase tracking-[0.18em] text-amber-400">Hours</p>
                    <p class="mt-1 text-[14px] font-semibold leading-6 text-stone-300">Custom-cut to order &middot; Delivered chilled within 2 hours</p>
                </div>
            </div>

            <div class="rounded-2xl border border-amber-100 bg-white p-6 shadow-sm md:p-8">
                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                        {{ session('success') }}
                    </div>
                @endif
                <h2 class="font-classic text-2xl font-bold text-slate-900">Send Us a Message</h2>
                <form method="POST" action="{{ route('frontend.contact.submit') }}" class="mt-6 space-y-5">
                    @csrf
                    <div>
                        <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-[0.16em] text-slate-500">Department</label>
                        <select name="department" class="h-12 w-full rounded-xl border border-slate-300 px-4 text-sm text-slate-700 outline-none focus:border-amber-500" required>
                            @foreach(['Customer Support', 'Delivery Help', 'Bulk Orders', 'Feedback'] as $department)
                                <option value="{{ $department }}" @selected(old('department') === $department)>{{ $department }}</option>
                            @endforeach
                        </select>
                        @error('department')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-[0.16em] text-slate-500">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name" class="h-12 w-full rounded-xl border border-slate-300 px-4 text-sm text-slate-700 outline-none focus:border-amber-500" required>
                            @error('name')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-[0.16em] text-slate-500">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" class="h-12 w-full rounded-xl border border-slate-300 px-4 text-sm text-slate-700 outline-none focus:border-amber-500" required>
                            @error('email')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-[11px] font-bold uppercase tracking-[0.16em] text-slate-500">Your Message</label>
                        <textarea name="message" rows="5" placeholder="How can we help you?" class="w-full resize-none rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none focus:border-amber-500" required>{{ old('message') }}</textarea>
                        @error('message')<p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-amber-500 bg-black px-7 py-3.5 text-[12px] font-bold uppercase tracking-[0.14em] text-white transition hover:bg-neutral-900">
                        <i class="fa-solid fa-paper-plane text-amber-400"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

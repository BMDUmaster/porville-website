@extends('layouts.dashboard')
@section('title', 'Order Settings')
@section('page_title', 'Order On/Off Settings')

@section('content')
<div class="p-4 md:p-6">
    <div class="mx-auto max-w-3xl space-y-6">

        {{-- Header Card --}}
        <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-purple-700 px-6 py-6 text-white md:px-8">
                <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-white/70">Store Control</p>
                <h1 class="mt-2 text-2xl font-black tracking-[-0.03em]">Order Accepting</h1>
                <p class="mt-2 max-w-2xl text-sm text-white/80">
                    Toggle order acceptance on or off. When turned off, customers will see a popup message and cannot place orders.
                </p>
            </div>

            {{-- Current Status Banner --}}
            <div class="border-b border-slate-200 bg-slate-50/70 px-6 py-5 md:px-8">
                <div class="flex items-center gap-4">
                    <div class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl {{ $isActive ? 'bg-emerald-100' : 'bg-red-100' }}">
                        <i class="fa-solid {{ $isActive ? 'fa-circle-check text-emerald-600' : 'fa-circle-xmark text-red-500' }} text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Current Status</p>
                        <p class="mt-1 text-xl font-black {{ $isActive ? 'text-emerald-600' : 'text-red-500' }}">
                            {{ $isActive ? '✅ Ordering is ACTIVE — Customers can place orders' : '🔴 Ordering is PAUSED — Orders blocked' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Success Message --}}
            @if(session('success'))
                <div class="mx-6 mt-5 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 md:mx-8">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('dashboard.settings.ordering.update') }}" class="px-6 py-6 md:px-8 space-y-6">
                @csrf @method('PUT')

                {{-- Toggle --}}
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold text-slate-800">Accept Orders</p>
                            <p class="mt-0.5 text-xs text-slate-500">Turn off to pause all new order placement instantly</p>
                        </div>
                        <label class="relative inline-flex cursor-pointer items-center select-none">
                            <input type="checkbox"
                                   name="ordering_active"
                                   value="1"
                                   id="orderingToggle"
                                   {{ $isActive ? 'checked' : '' }}
                                   class="peer sr-only">
                            <div class="peer h-7 w-14 rounded-full bg-slate-300 transition-colors after:absolute after:left-[4px] after:top-[4px] after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow after:transition-all after:content-[''] peer-checked:bg-emerald-500 peer-checked:after:translate-x-7"></div>
                        </label>
                    </div>
                    {{-- Live badge --}}
                    <div id="statusBadge" class="mt-3 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold
                        {{ $isActive ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                        <span class="h-2 w-2 rounded-full {{ $isActive ? 'bg-emerald-500 animate-pulse' : 'bg-red-500' }}"></span>
                        <span id="statusText">{{ $isActive ? 'Orders Active' : 'Orders Paused' }}</span>
                    </div>
                </div>

                {{-- Inactive Popup Content --}}
                <div class="space-y-4">
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400">Popup shown to customers when ordering is OFF</p>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Popup Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="ordering_inactive_title"
                               value="{{ old('ordering_inactive_title', $title) }}"
                               required
                               maxlength="120"
                               placeholder="e.g. Orders Temporarily Paused"
                               class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('ordering_inactive_title') border-red-400 @enderror">
                        @error('ordering_inactive_title')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">
                            Popup Message <span class="text-red-500">*</span>
                        </label>
                        <textarea name="ordering_inactive_message"
                                  required
                                  maxlength="500"
                                  rows="4"
                                  placeholder="e.g. We are temporarily not accepting orders. Please check back soon."
                                  class="w-full resize-none rounded-xl border border-slate-200 px-4 py-3 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 @error('ordering_inactive_message') border-red-400 @enderror">{{ old('ordering_inactive_message', $message) }}</textarea>
                        @error('ordering_inactive_message')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-slate-400">Max 500 characters</p>
                    </div>
                </div>

                {{-- Preview --}}
                <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-5">
                    <p class="mb-3 text-xs font-bold uppercase tracking-widest text-slate-400">Live Preview of Popup</p>
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg">
                        <div class="bg-gradient-to-br from-slate-900 to-slate-800 px-6 py-5 text-center">
                            <div class="mx-auto mb-3 flex h-14 w-14 items-center justify-center rounded-full bg-orange-500/20">
                                <i class="fa-solid fa-store-slash text-xl text-orange-400"></i>
                            </div>
                            <h3 id="previewTitle" class="text-lg font-black text-white">{{ $title }}</h3>
                            <p id="previewMessage" class="mt-2 text-sm text-slate-300">{{ $message }}</p>
                            <div class="mt-4 flex justify-center gap-3">
                                <span class="inline-flex items-center gap-1.5 rounded-xl bg-slate-700 px-4 py-2.5 text-xs font-bold text-slate-300">
                                    <i class="fa-solid fa-clock text-orange-400"></i> Check Back Later
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-7 py-3 text-sm font-bold text-white transition hover:bg-blue-800">
                        <i class="fa-solid fa-floppy-disk"></i> Save Settings
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script>
const toggle = document.getElementById('orderingToggle');
const badge  = document.getElementById('statusBadge');
const text   = document.getElementById('statusText');

toggle.addEventListener('change', function () {
    if (this.checked) {
        badge.className = 'mt-3 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold bg-emerald-100 text-emerald-700';
        badge.querySelector('span').className = 'h-2 w-2 rounded-full bg-emerald-500 animate-pulse';
        text.textContent = 'Orders Active';
    } else {
        badge.className = 'mt-3 inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-bold bg-red-100 text-red-600';
        badge.querySelector('span').className = 'h-2 w-2 rounded-full bg-red-500';
        text.textContent = 'Orders Paused';
    }
});

// Live preview sync
document.querySelector('[name="ordering_inactive_title"]').addEventListener('input', function () {
    document.getElementById('previewTitle').textContent = this.value || 'Popup Title';
});
document.querySelector('[name="ordering_inactive_message"]').addEventListener('input', function () {
    document.getElementById('previewMessage').textContent = this.value || 'Popup message...';
});
</script>
@endsection

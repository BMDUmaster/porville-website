@extends('layouts.dashboard')
@section('title', 'Delivery Slots')
@section('page_title', 'Delivery Slot Settings')

@section('content')
<div class="p-4 md:p-6 mx-auto max-w-4xl space-y-5">

    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h1 class="text-xl font-black text-slate-900">Manage Delivery Slots</h1>
        <p class="mt-1 text-sm text-slate-500">Pick a date, add a start and end time, done. Customers picking "Today" or "Tomorrow" at checkout see whatever slots you've added for that calendar date.</p>
    </div>

    {{-- Add a slot for any date --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                <i class="fa-regular fa-calendar-plus"></i>
            </span>
            <div>
                <h2 class="text-lg font-black text-slate-900">Add a Slot</h2>
                <p class="text-xs text-slate-500">Works for today, tomorrow, or any future date.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('dashboard.settings.delivery-slots.slot.store') }}" class="mt-4 grid gap-3 rounded-xl bg-slate-50 p-4 sm:grid-cols-[1fr_1fr_1fr_auto] sm:items-end">
            @csrf
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Date</label>
                <input type="date" name="date" min="{{ now()->toDateString() }}" value="{{ old('date', now()->toDateString()) }}" required
                       class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold outline-none focus:border-amber-500">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Start Time</label>
                <input type="time" name="start_time" required
                       class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold outline-none focus:border-amber-500">
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-bold text-slate-600">End Time</label>
                <input type="time" name="end_time" required
                       class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold outline-none focus:border-amber-500">
            </div>
            <button type="submit" class="inline-flex h-[42px] items-center justify-center gap-2 rounded-xl bg-black px-5 text-sm font-bold text-white hover:bg-neutral-800">
                <i class="fa-solid fa-plus"></i> Add Slot
            </button>
        </form>
    </section>

    {{-- Existing dates & slots --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                <i class="fa-regular fa-clock"></i>
            </span>
            <div>
                <h2 class="text-lg font-black text-slate-900">Upcoming Slots</h2>
                <p class="text-xs text-slate-500">Today's date is always what customers see under "Today"; the next date is "Tomorrow".</p>
            </div>
        </div>

        <div class="mt-4 space-y-4">
            @forelse($dates as $day)
                <div class="rounded-xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-black text-slate-900">
                            {{ $day['date_label'] }}
                            @if($day['date'] === now()->toDateString())
                                <span class="ml-2 rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase text-amber-700">Today</span>
                            @elseif($day['date'] === now()->addDay()->toDateString())
                                <span class="ml-2 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold uppercase text-emerald-700">Tomorrow</span>
                            @endif
                        </p>
                        <form method="POST" action="{{ route('dashboard.settings.delivery-slots.date.destroy', $day['date']) }}" onsubmit="return confirm('Remove all slots for {{ $day['date_label'] }}?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700"><i class="fa-solid fa-trash-can mr-1"></i>Clear date</button>
                        </form>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach($day['slots'] as $slot)
                            <span class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-700">
                                <i class="fa-regular fa-clock text-amber-600"></i>{{ $slot['label'] }}
                                <form method="POST" action="{{ route('dashboard.settings.delivery-slots.slot.destroy', $slot['id']) }}" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600" aria-label="Remove slot"><i class="fa-solid fa-xmark"></i></button>
                                </form>
                            </span>
                        @endforeach
                    </div>
                </div>
            @empty
                <p class="rounded-xl border border-dashed border-slate-300 px-4 py-6 text-center text-sm text-slate-400">No slots added yet. Add one above.</p>
            @endforelse
        </div>
    </section>

    <form method="POST" action="{{ route('dashboard.settings.delivery-slots.update') }}">
        @csrf
        @method('PUT')

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <label for="deliveryCharge" class="block text-sm font-bold text-slate-800">Delivery Charge (Rs)</label>
            <p class="mt-1 text-xs text-slate-500">This amount is added during checkout.</p>
            <input type="number" id="deliveryCharge" name="delivery_charge" min="0" step="0.01"
                   value="{{ old('delivery_charge', number_format($deliveryCharge, 2, '.', '')) }}"
                   class="mt-3 w-full max-w-xs rounded-xl border border-slate-300 px-4 py-3 text-sm font-semibold outline-none focus:border-amber-500">
            <button type="submit" class="mt-4 inline-flex items-center justify-center gap-2 rounded-xl bg-black px-6 py-3 text-sm font-bold text-white hover:bg-neutral-800">
                <i class="fa-solid fa-floppy-disk"></i> Save
            </button>
        </section>
    </form>
</div>
@endsection

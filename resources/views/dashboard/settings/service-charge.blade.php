@extends('layouts.dashboard')
@section('title', 'Service Charge')
@section('page_title', 'Service Charge Settings')

@section('content')
<div class="p-4 md:p-6">
    <div class="mx-auto max-w-4xl space-y-6">
        <section class="overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-[#0f766e] via-[#0f8a79] to-[#1d4ed8] px-6 py-6 text-white md:px-8">
                <p class="text-[11px] font-bold uppercase tracking-[0.24em] text-white/70">Checkout Pricing</p>
                <h1 class="mt-2 text-2xl font-black tracking-[-0.03em]">Service Charge</h1>
                <p class="mt-2 max-w-2xl text-sm text-white/80">
                    Control today and tomorrow service charge percentages. Changes apply to new checkout and API orders immediately.
                </p>
            </div>

            <div class="grid gap-5 border-b border-slate-200 bg-slate-50/70 px-6 py-5 md:grid-cols-3 md:px-8">
                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Today Rate</p>
                    <p class="mt-2 text-3xl font-black text-slate-900">{{ rtrim(rtrim(number_format($todayServiceChargePercent, 2), '0'), '.') }}%</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Tomorrow Rate</p>
                    <p class="mt-2 text-3xl font-black text-slate-900">{{ rtrim(rtrim(number_format($tomorrowServiceChargePercent, 2), '0'), '.') }}%</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Default Rate</p>
                    <p class="mt-2 text-3xl font-black text-slate-900">{{ rtrim(rtrim(number_format($defaultPercent, 2), '0'), '.') }}%</p>
                </div>
            </div>

            <div class="px-6 py-6 md:px-8">
                <form method="POST" action="{{ route('dashboard.settings.service-charge.update') }}" class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_280px]">
                    @csrf
                    @method('PUT')

                    <div class="rounded-[24px] border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-start gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-50 text-blue-600">
                                <i class="fa-solid fa-percent text-lg"></i>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-lg font-black text-slate-900">Update Service Charge Percentage</h2>
                                <p class="mt-1 text-sm text-slate-500">
                                    Today rate applies to today or mixed carts. Tomorrow rate applies when every cart item is selected for tomorrow.
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-2">
                            <div>
                                <label for="todayServiceChargePercent" class="mb-2 block text-sm font-bold text-slate-700">Today Service Charge (%)</label>
                                <input type="number" step="0.01" min="0" max="100" id="todayServiceChargePercent" name="today_service_charge_percent"
                                       value="{{ old('today_service_charge_percent', $todayServiceChargePercent) }}"
                                       class="w-full rounded-2xl border border-slate-300 px-5 py-3 text-base font-semibold text-slate-800 outline-none transition focus:border-blue-500">
                                <p class="mt-2 text-xs text-slate-400">Example: enter <span class="font-bold text-slate-600">10</span> for a 10% today charge.</p>
                            </div>
                            <div>
                                <label for="tomorrowServiceChargePercent" class="mb-2 block text-sm font-bold text-slate-700">Tomorrow Service Charge (%)</label>
                                <input type="number" step="0.01" min="0" max="100" id="tomorrowServiceChargePercent" name="tomorrow_service_charge_percent"
                                       value="{{ old('tomorrow_service_charge_percent', $tomorrowServiceChargePercent) }}"
                                       class="w-full rounded-2xl border border-slate-300 px-5 py-3 text-base font-semibold text-slate-800 outline-none transition focus:border-blue-500">
                                <p class="mt-2 text-xs text-slate-400">Set a separate rate for all-tomorrow carts.</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[24px] border border-slate-200 bg-slate-50 p-5">
                        <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Quick Preview</p>
                        <div class="mt-4 space-y-3 rounded-2xl border border-slate-200 bg-white p-4 text-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Subtotal</span>
                                <span class="font-semibold text-slate-800">&#8377;1,000.00</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Today Service Charge</span>
                                <span class="font-semibold text-slate-800" id="todayServiceChargePreview">&#8377;{{ number_format(1000 * $todayServiceChargePercent / 100, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-500">Tomorrow Service Charge</span>
                                <span class="font-semibold text-slate-800" id="tomorrowServiceChargePreview">&#8377;{{ number_format(1000 * $tomorrowServiceChargePercent / 100, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700">
                            <i class="fa-solid fa-floppy-disk text-xs"></i>
                            Save Service Charge
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script>
const serviceChargeInputs = [
    {
        input: document.getElementById('todayServiceChargePercent'),
        preview: document.getElementById('todayServiceChargePreview'),
    },
    {
        input: document.getElementById('tomorrowServiceChargePercent'),
        preview: document.getElementById('tomorrowServiceChargePreview'),
    },
];

serviceChargeInputs.forEach(({ input, preview }) => {
    if (!input || !preview) {
        return;
    }

    const updatePreview = () => {
        const percentage = Number(input.value || 0);
        const charge = (1000 * percentage) / 100;
        preview.innerHTML = '&#8377;' + charge.toFixed(2);
    };

    input.addEventListener('input', updatePreview);
});
</script>
@endsection

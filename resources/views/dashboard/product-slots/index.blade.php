@extends('layouts.dashboard')
@section('title', 'Product Slots')
@section('page_title', 'Product Slots')

@section('content')
@php
    $formAction = $editing ? route('dashboard.product-slots.update', $editing) : route('dashboard.product-slots.store');
    $selectedIds = collect(old('product_ids', $editing ? $editing->products->pluck('id')->all() : []))->map(fn ($id) => (int) $id)->all();
    $currentType = old('type', $editing->type ?? 'daily');
    $productData = $products->map(fn ($p) => [
        'id' => $p->id,
        'name' => $p->name,
        'category_id' => $p->category_id,
        'subcategory_id' => $p->subcategory_id,
        'is_active' => (bool) $p->is_active,
    ])->values();
@endphp
<div class="mx-auto max-w-6xl space-y-5 p-4 md:p-6">

    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h1 class="text-xl font-black text-slate-900">Product Ordering Slots</h1>
        <p class="mt-1 text-sm text-slate-500">
            Choose products and the time they can be ordered. Outside the slot, customers see <strong>Time Out</strong> instead of Add to Cart and can tap
            <strong>Notify Me</strong> — they get an in-app notification and email when the next slot starts. Products without a slot can be ordered any time.
        </p>
    </div>

    {{-- Add / edit form --}}
    <form id="slotForm" method="POST" action="{{ $formAction }}" class="rounded-2xl border {{ $editing ? 'border-amber-300' : 'border-slate-200' }} bg-white p-5 shadow-sm">
        @csrf
        @if($editing) @method('PUT') @endif

        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <i class="fa-solid {{ $editing ? 'fa-pen-to-square' : 'fa-business-time' }}"></i>
                </span>
                <div>
                    <h2 class="text-lg font-black text-slate-900">{{ $editing ? 'Edit Slot' : 'Add a Product Slot' }}</h2>
                    <p class="text-xs text-slate-500">Every day at the same time, or only on one date.</p>
                </div>
            </div>
            @if($editing)
                <a href="{{ route('dashboard.product-slots') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800">Cancel edit</a>
            @endif
        </div>

        <div class="mt-4 grid gap-4 md:grid-cols-2">
            <div class="md:col-span-2">
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Slot Name <span class="font-normal text-slate-400">(optional, only for admin)</span></label>
                <input type="text" name="name" maxlength="120" value="{{ old('name', $editing->name ?? '') }}" placeholder="e.g. Morning Pork Slot"
                       class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm outline-none focus:border-amber-500">
            </div>

            <div class="md:col-span-2">
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Slot Type</label>
                <div class="grid gap-2 sm:grid-cols-2">
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-3 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                        <input type="radio" name="type" value="daily" class="mt-1 accent-amber-600" {{ $currentType === 'daily' ? 'checked' : '' }} onchange="toggleSlotDate()">
                        <span>
                            <span class="block text-sm font-bold text-slate-800">Per Day (every day)</span>
                            <span class="block text-xs text-slate-500">Same time window daily, e.g. 10:00 AM – 2:00 PM.</span>
                        </span>
                    </label>
                    <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-3 has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                        <input type="radio" name="type" value="date" class="mt-1 accent-amber-600" {{ $currentType === 'date' ? 'checked' : '' }} onchange="toggleSlotDate()">
                        <span>
                            <span class="block text-sm font-bold text-slate-800">Date / Time</span>
                            <span class="block text-xs text-slate-500">Only on one specific date, in this time window.</span>
                        </span>
                    </label>
                </div>
            </div>

            <div id="slotDateField" class="{{ $currentType === 'date' ? '' : 'hidden' }}">
                <label class="mb-1.5 block text-xs font-bold text-slate-600">Date</label>
                <input type="date" name="slot_date" min="{{ now()->toDateString() }}"
                       value="{{ old('slot_date', optional($editing?->slot_date)->toDateString()) }}"
                       class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm font-semibold outline-none focus:border-amber-500">
            </div>

            <div class="grid grid-cols-2 gap-3 {{ $currentType === 'date' ? '' : 'md:col-span-2' }}" id="slotTimeFields">
                <div>
                    <label class="mb-1.5 block text-xs font-bold text-slate-600">Start Time</label>
                    <input type="time" name="start_time" required value="{{ old('start_time', $editing ? substr($editing->start_time, 0, 5) : '') }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm font-semibold outline-none focus:border-amber-500">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-bold text-slate-600">End Time</label>
                    <input type="time" name="end_time" required value="{{ old('end_time', $editing ? substr($editing->end_time, 0, 5) : '') }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2.5 text-sm font-semibold outline-none focus:border-amber-500">
                </div>
            </div>
        </div>

        {{-- Product picker --}}
        <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <label class="text-xs font-bold uppercase tracking-wider text-slate-600">Products in this slot</label>
                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-800"><span id="selectedCount">0</span> selected</span>
            </div>

            <div class="mt-3 grid gap-2 sm:grid-cols-3">
                <select id="filterCategory" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-amber-500">
                    <option value="">All categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <select id="filterSubcategory" class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-amber-500">
                    <option value="">All sub categories</option>
                    @foreach($subcategories as $sub)
                        <option value="{{ $sub->id }}" data-parent="{{ $sub->parent_id }}">{{ $sub->name }}</option>
                    @endforeach
                </select>
                <input type="text" id="filterSearch" placeholder="Search product name..."
                       class="rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm outline-none focus:border-amber-500">
            </div>

            <div class="mt-3 flex flex-wrap items-center gap-4 text-sm">
                <label class="flex items-center gap-2 font-semibold text-slate-700">
                    <input type="checkbox" id="selectAllShown" class="h-4 w-4 accent-amber-600"> Select all shown
                </label>
                <button type="button" onclick="setShownProducts(false)" class="font-semibold text-slate-500 hover:text-red-600">Unselect shown</button>
                <span class="text-xs text-slate-400"><span id="shownCount">0</span> product(s) shown</span>
            </div>

            <div id="productList" class="mt-3 grid max-h-72 gap-2 overflow-y-auto rounded-xl border border-slate-200 bg-white p-2 sm:grid-cols-2 lg:grid-cols-3"></div>
            <p id="productListEmpty" class="mt-3 hidden text-center text-sm text-slate-400">No products match these filters.</p>
        </div>

        <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
            <label class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" class="h-4 w-4 accent-amber-600" {{ old('is_active', $editing ? (int) $editing->is_active : 1) ? 'checked' : '' }}>
                Slot active
            </label>
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-black px-6 py-3 text-sm font-bold text-white hover:bg-neutral-800">
                <i class="fa-solid {{ $editing ? 'fa-floppy-disk' : 'fa-plus' }}"></i> {{ $editing ? 'Update Slot' : 'Add Slot' }}
            </button>
        </div>
    </form>

    {{-- Existing slots --}}
    <section class="rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-5 py-4">
            <h2 class="text-lg font-black text-slate-900">All Product Slots</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left">
                <thead class="bg-slate-50 text-xs uppercase text-slate-500">
                    <tr>
                        <th class="px-4 py-3">Slot</th>
                        <th class="px-4 py-3">When</th>
                        <th class="px-4 py-3">Products</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($slots as $slot)
                        @php
                            $expired = $slot->isExpired();
                            [$todayStart, $todayEnd] = $slot->windowOn($slot->isDaily() ? now() : ($slot->slot_date ?? now()));
                            $openNow = $slot->is_active && ! $expired && now()->between($todayStart, $todayEnd) && ($slot->isDaily() || $slot->slot_date?->isToday());
                        @endphp
                        <tr class="{{ $editing && $editing->id === $slot->id ? 'bg-amber-50' : 'hover:bg-slate-50' }}">
                            <td class="px-4 py-3 font-bold text-slate-800">{{ $slot->name ?: 'Slot #' . $slot->id }}</td>
                            <td class="px-4 py-3 text-slate-600">
                                <span class="block font-semibold text-slate-800">{{ $slot->timeRangeLabel() }}</span>
                                <span class="text-xs">{{ $slot->isDaily() ? 'Every day' : $slot->slot_date->format('d M Y') }}</span>
                            </td>
                            <td class="px-4 py-3 text-slate-600">
                                <span class="font-bold text-slate-800">{{ $slot->products->count() }}</span>
                                <span class="block max-w-xs truncate text-xs" title="{{ $slot->products->pluck('name')->join(', ') }}">{{ $slot->products->pluck('name')->join(', ') }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if(! $slot->is_active)
                                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600">Inactive</span>
                                @elseif($expired)
                                    <span class="rounded-full bg-red-100 px-2.5 py-1 text-[11px] font-bold text-red-700">Expired</span>
                                @elseif($openNow)
                                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-[11px] font-bold text-green-700">Open now</span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-bold text-amber-700">Active</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('dashboard.product-slots', ['edit' => $slot->id]) }}" class="flex h-8 w-8 items-center justify-center rounded-lg text-amber-600 hover:bg-amber-100" title="Edit">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('dashboard.product-slots.destroy', $slot) }}" onsubmit="return confirm('Delete this slot?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex h-8 w-8 items-center justify-center rounded-lg text-red-500 hover:bg-red-100" title="Delete">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-10 text-center text-slate-400">No product slots yet. All products can be ordered any time.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
const slotProducts = @json($productData);
const selectedProducts = new Set(@json($selectedIds));

function toggleSlotDate() {
    const isDate = document.querySelector('input[name="type"]:checked')?.value === 'date';
    document.getElementById('slotDateField').classList.toggle('hidden', !isDate);
    document.getElementById('slotTimeFields').classList.toggle('md:col-span-2', !isDate);
    document.querySelector('input[name="slot_date"]').required = isDate;
}

function shownProducts() {
    const category = document.getElementById('filterCategory').value;
    const subcategory = document.getElementById('filterSubcategory').value;
    const term = document.getElementById('filterSearch').value.trim().toLowerCase();

    return slotProducts.filter((p) =>
        (!category || String(p.category_id) === category)
        && (!subcategory || String(p.subcategory_id) === subcategory)
        && (!term || p.name.toLowerCase().includes(term))
    );
}

function escapeSlotHtml(value) {
    const div = document.createElement('div');
    div.textContent = value;
    return div.innerHTML;
}

function renderProductList() {
    const list = document.getElementById('productList');
    const shown = shownProducts();

    list.innerHTML = shown.map((p) => `
        <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-transparent px-2.5 py-2 text-sm hover:border-amber-200 hover:bg-amber-50">
            <input type="checkbox" class="h-4 w-4 accent-amber-600" value="${p.id}" ${selectedProducts.has(p.id) ? 'checked' : ''} onchange="toggleProduct(${p.id}, this.checked)">
            <span class="min-w-0 truncate font-medium text-slate-700">${escapeSlotHtml(p.name)}</span>
            ${p.is_active ? '' : '<span class="ml-auto rounded bg-slate-100 px-1.5 text-[10px] font-bold text-slate-500">Inactive</span>'}
        </label>
    `).join('');

    document.getElementById('productListEmpty').classList.toggle('hidden', shown.length > 0);
    document.getElementById('shownCount').textContent = shown.length;
    document.getElementById('selectAllShown').checked = shown.length > 0 && shown.every((p) => selectedProducts.has(p.id));
    syncSelectedInputs();
}

function toggleProduct(id, checked) {
    checked ? selectedProducts.add(id) : selectedProducts.delete(id);
    renderProductList();
}

function setShownProducts(checked) {
    shownProducts().forEach((p) => (checked ? selectedProducts.add(p.id) : selectedProducts.delete(p.id)));
    renderProductList();
}

// Selected products live outside the filtered list, so post them as hidden inputs.
function syncSelectedInputs() {
    const form = document.getElementById('slotForm');
    form.querySelectorAll('input[name="product_ids[]"]').forEach((input) => input.remove());
    selectedProducts.forEach((id) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'product_ids[]';
        input.value = id;
        form.appendChild(input);
    });
    document.getElementById('selectedCount').textContent = selectedProducts.size;
}

document.getElementById('filterCategory').addEventListener('change', (event) => {
    const category = event.target.value;
    const subSelect = document.getElementById('filterSubcategory');
    Array.from(subSelect.options).forEach((option) => {
        option.hidden = Boolean(option.value) && Boolean(category) && option.dataset.parent !== category;
    });
    if (subSelect.selectedOptions[0]?.hidden) subSelect.value = '';
    renderProductList();
});
document.getElementById('filterSubcategory').addEventListener('change', renderProductList);
document.getElementById('filterSearch').addEventListener('input', renderProductList);
document.getElementById('selectAllShown').addEventListener('change', (event) => setShownProducts(event.target.checked));
document.getElementById('slotForm').addEventListener('submit', (event) => {
    if (selectedProducts.size === 0) {
        event.preventDefault();
        alert('Select at least one product for this slot.');
    }
});

toggleSlotDate();
renderProductList();
</script>
@endsection

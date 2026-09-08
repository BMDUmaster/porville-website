@extends('layouts.dashboard')
@section('title', 'Multi Edit Products')
@section('page_title', 'Multi Edit Products')

@section('content')
<div class="p-4 sm:p-6 space-y-6">

    {{-- Top Header Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border shadow-sm">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('dashboard.products') }}" class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition">
                    <i class="fa-solid fa-arrow-left text-sm"></i>
                </a>
                <h2 class="text-xl font-extrabold text-slate-900">Editing {{ count($products) }} Products</h2>
            </div>
            <p class="mt-1 text-xs text-slate-500 pl-12">Update details, status, prices, and variants for all selected products together.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard.products') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 text-xs font-bold hover:bg-slate-100 transition">
                Cancel
            </a>
            <button type="submit" form="multiUpdateForm" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold uppercase tracking-wider shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save All Changes</span>
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-xl border border-red-200 bg-red-50 text-xs font-medium text-red-700 space-y-1">
            <p class="font-bold flex items-center gap-2"><i class="fa-solid fa-circle-exclamation"></i> Please fix the errors below:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="multiUpdateForm" method="POST" action="{{ route('dashboard.products.multi-update') }}" class="space-y-6">
        @csrf

        @foreach($products as $pIndex => $product)
            @php
                $variants = is_array($product->variants) ? array_values($product->variants) : [];
                $firstImg = ($product->images && count($product->images)) ? asset('storage/' . $product->images[0]) : null;
            @endphp

            <div class="bg-white rounded-2xl border shadow-sm overflow-hidden border-slate-200">
                {{-- Product Card Header --}}
                <div class="bg-slate-50 border-b px-5 py-4 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 font-extrabold text-xs flex items-center justify-center">
                            #{{ $pIndex + 1 }}
                        </span>

                        @if($firstImg)
                            <img src="{{ $firstImg }}" class="h-10 w-10 rounded-lg object-cover border bg-white">
                        @else
                            <div class="h-10 w-10 rounded-lg bg-slate-200 text-slate-400 flex items-center justify-center text-sm">
                                <i class="fa-regular fa-image"></i>
                            </div>
                        @endif

                        <div>
                            <h3 class="text-sm font-bold text-slate-900">{{ $product->name }}</h3>
                            <p class="text-[11px] text-slate-500">ID: {{ $product->id }} | SKU/Slug: {{ $product->slug }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>

                {{-- Hidden Product ID --}}
                <input type="hidden" name="products[{{ $pIndex }}][id]" value="{{ $product->id }}">

                {{-- Product Fields --}}
                <div class="p-5 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Name --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Product Name *</label>
                            <input type="text" name="products[{{ $pIndex }}][name]" value="{{ old("products.{$pIndex}.name", $product->name) }}" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
                        </div>

                        {{-- Category --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Category *</label>
                            <select name="products[{{ $pIndex }}][category_id]" onchange="onCategoryChange({{ $pIndex }}, this.value)" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-900 outline-none focus:border-blue-500 focus:bg-white">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old("products.{$pIndex}.category_id", $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Subcategory --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Subcategory</label>
                            <select id="subcat_select_{{ $pIndex }}" name="products[{{ $pIndex }}][subcategory_id]"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold text-slate-900 outline-none focus:border-blue-500 focus:bg-white">
                                <option value="">None / Direct Category</option>
                                @foreach($subcategories as $sub)
                                    <option value="{{ $sub->id }}" data-parent="{{ $sub->parent_id }}" {{ old("products.{$pIndex}.subcategory_id", $product->subcategory_id) == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        {{-- Base Price --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Selling Price (Rs) *</label>
                            <input type="number" step="0.01" min="0" name="products[{{ $pIndex }}][price]" value="{{ old("products.{$pIndex}.price", $product->price) }}" required
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 outline-none focus:border-blue-500 focus:bg-white">
                        </div>

                        {{-- MRP --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">MRP (Rs)</label>
                            <input type="number" step="0.01" min="0" name="products[{{ $pIndex }}][mrp]" value="{{ old("products.{$pIndex}.mrp", $product->mrp) }}"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 outline-none focus:border-blue-500 focus:bg-white">
                        </div>

                        {{-- Status --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Status *</label>
                            <select name="products[{{ $pIndex }}][is_active]" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-900 outline-none focus:border-blue-500 focus:bg-white">
                                <option value="1" {{ old("products.{$pIndex}.is_active", $product->is_active) ? 'selected' : '' }}>Active (In Stock)</option>
                                <option value="0" {{ !old("products.{$pIndex}.is_active", $product->is_active) ? 'selected' : '' }}>Inactive (Out of Stock)</option>
                            </select>
                        </div>

                        {{-- Weight/Pack Description --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Weight / Unit Label</label>
                            <input type="text" name="products[{{ $pIndex }}][weight]" value="{{ old("products.{$pIndex}.weight", $product->weight) }}" placeholder="e.g. 500g / 1kg"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs font-semibold text-slate-900 outline-none focus:border-blue-500 focus:bg-white">
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Description</label>
                        <textarea name="products[{{ $pIndex }}][description]" rows="2"
                                  class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-900 outline-none focus:border-blue-500 focus:bg-white">{{ old("products.{$pIndex}.description", $product->description) }}</textarea>
                    </div>

                    {{-- VARIANTS TABLE --}}
                    <div class="border rounded-xl p-4 bg-slate-50/50 space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-700 flex items-center gap-2">
                                <i class="fa-solid fa-tags text-blue-600"></i> Variants Management
                            </h4>
                            <button type="button" onclick="addVariantRow({{ $pIndex }})" class="px-3 py-1.5 rounded-lg bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold flex items-center gap-1 transition">
                                <i class="fa-solid fa-plus text-[10px]"></i> Add Variant Row
                            </button>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="border-b text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                                        <th class="py-2 px-2">Qty</th>
                                        <th class="py-2 px-2">Unit</th>
                                        <th class="py-2 px-2">Piece Info</th>
                                        <th class="py-2 px-2">MRP (Rs)</th>
                                        <th class="py-2 px-2">Selling Price (Rs)</th>
                                        <th class="py-2 px-2">Today Price (Rs)</th>
                                        <th class="py-2 px-2">Tomorrow Price (Rs)</th>
                                        <th class="py-2 px-2">Save / Offer</th>
                                        <th class="py-2 px-2 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="variants_body_{{ $pIndex }}" class="divide-y divide-slate-200">
                                    @forelse($variants as $vIndex => $variant)
                                        <tr>
                                            <td class="p-1">
                                                <input type="text" name="products[{{ $pIndex }}][variants][{{ $vIndex }}][quantity]" value="{{ $variant['quantity'] ?? '' }}" placeholder="500"
                                                       class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs">
                                            </td>
                                            <td class="p-1">
                                                <select name="products[{{ $pIndex }}][variants][{{ $vIndex }}][unit]" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs">
                                                    @foreach(['Gram','Kg','Piece','Pack','Litre','Ml'] as $u)
                                                        <option value="{{ $u }}" {{ ($variant['unit'] ?? '') === $u ? 'selected' : '' }}>{{ $u }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="p-1">
                                                <input type="text" name="products[{{ $pIndex }}][variants][{{ $vIndex }}][piece]" value="{{ $variant['piece'] ?? '' }}" placeholder="4-6 Pcs"
                                                       class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs">
                                            </td>
                                            <td class="p-1">
                                                <input type="number" step="0.01" min="0" data-type="mrp" name="products[{{ $pIndex }}][variants][{{ $vIndex }}][mrp]" value="{{ $variant['mrp'] ?? '' }}" placeholder="0.00"
                                                       class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs">
                                            </td>
                                            <td class="p-1">
                                                <input type="number" step="0.01" min="0" data-type="selling_price" name="products[{{ $pIndex }}][variants][{{ $vIndex }}][selling_price]" value="{{ $variant['selling_price'] ?? '' }}" placeholder="0.00"
                                                       class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs font-bold text-blue-700">
                                            </td>
                                            <td class="p-1">
                                                <input type="number" step="0.01" min="0" data-type="today_price" name="products[{{ $pIndex }}][variants][{{ $vIndex }}][today_price]" value="{{ $variant['today_price'] ?? '' }}" placeholder="0.00"
                                                       class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs">
                                            </td>
                                            <td class="p-1">
                                                <input type="number" step="0.01" min="0" data-type="tomorrow_price" name="products[{{ $pIndex }}][variants][{{ $vIndex }}][tomorrow_price]" value="{{ $variant['tomorrow_price'] ?? '' }}" placeholder="0.00"
                                                       class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs">
                                            </td>
                                            <td class="p-1">
                                                <input type="text" data-type="save_offer" name="products[{{ $pIndex }}][variants][{{ $vIndex }}][save_offer]" value="{{ $variant['save_offer'] ?? '' }}" placeholder="Auto e.g. 10% OFF"
                                                       class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-xs font-bold text-green-700">
                                            </td>
                                            <td class="p-1 text-center">
                                                <button type="button" onclick="this.closest('tr').remove()" class="p-1 text-red-500 hover:text-red-700">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="no-variant-row">
                                            <td colspan="9" class="py-3 text-center text-slate-400 italic">No variants added yet. Click "+ Add Variant Row" to add one.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        @endforeach

        {{-- Bottom Submit Bar --}}
        <div class="flex items-center justify-between gap-4 bg-white p-5 rounded-2xl border shadow-sm">
            <a href="{{ route('dashboard.products') }}" class="px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 text-xs font-bold hover:bg-slate-100 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-extrabold uppercase tracking-wider shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save All Changes</span>
            </button>
        </div>
    </form>

</div>
@endsection

@section('scripts')
<script>
function onCategoryChange(pIndex, catId) {
    const select = document.getElementById('subcat_select_' + pIndex);
    if (!select) return;
    const options = select.querySelectorAll('option');
    options.forEach(opt => {
        const parent = opt.getAttribute('data-parent');
        if (!parent) {
            opt.style.display = '';
        } else if (parent == catId) {
            opt.style.display = '';
        } else {
            opt.style.display = 'none';
        }
    });
    select.value = '';
}

function addVariantRow(pIndex) {
    const tbody = document.getElementById('variants_body_' + pIndex);
    if (!tbody) return;

    const noRow = tbody.querySelector('.no-variant-row');
    if (noRow) noRow.remove();

    const vIndex = tbody.querySelectorAll('tr').length;

    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td class="p-1">
            <input type="text" name="products[${pIndex}][variants][${vIndex}][quantity]" placeholder="500"
                   class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs">
        </td>
        <td class="p-1">
            <select name="products[${pIndex}][variants][${vIndex}][unit]" class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs">
                <option value="Gram">Gram</option>
                <option value="Kg">Kg</option>
                <option value="Piece">Piece</option>
                <option value="Pack">Pack</option>
                <option value="Litre">Litre</option>
                <option value="Ml">Ml</option>
            </select>
        </td>
        <td class="p-1">
            <input type="text" name="products[${pIndex}][variants][${vIndex}][piece]" placeholder="4-6 Pcs"
                   class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs">
        </td>
        <td class="p-1">
            <input type="number" step="0.01" min="0" data-type="mrp" name="products[${pIndex}][variants][${vIndex}][mrp]" placeholder="0.00"
                   class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs">
        </td>
        <td class="p-1">
            <input type="number" step="0.01" min="0" data-type="selling_price" name="products[${pIndex}][variants][${vIndex}][selling_price]" placeholder="0.00"
                   class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs font-bold text-blue-700">
        </td>
        <td class="p-1">
            <input type="number" step="0.01" min="0" data-type="today_price" name="products[${pIndex}][variants][${vIndex}][today_price]" placeholder="0.00"
                   class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs">
        </td>
        <td class="p-1">
            <input type="number" step="0.01" min="0" data-type="tomorrow_price" name="products[${pIndex}][variants][${vIndex}][tomorrow_price]" placeholder="0.00"
                   class="w-full bg-white border border-slate-200 rounded-lg px-2 py-1 text-xs">
        </td>
        <td class="p-1">
            <input type="text" data-type="save_offer" name="products[${pIndex}][variants][${vIndex}][save_offer]" placeholder="Auto e.g. 10% OFF"
                   class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-xs font-bold text-green-700">
        </td>
        <td class="p-1 text-center">
            <button type="button" onclick="this.closest('tr').remove()" class="p-1 text-red-500 hover:text-red-700">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

function autoCalculateSaveOffer(row) {
    const mrpInput = row.querySelector('[data-type="mrp"]');
    const sellingPriceInput = row.querySelector('[data-type="selling_price"]');
    const todayPriceInput = row.querySelector('[data-type="today_price"]');
    const tomorrowPriceInput = row.querySelector('[data-type="tomorrow_price"]');
    const saveOfferInput = row.querySelector('[data-type="save_offer"]');

    if (!mrpInput || !saveOfferInput) return;

    const mrp = parseFloat(mrpInput.value) || 0;
    const todayPrice = parseFloat(todayPriceInput ? todayPriceInput.value : 0) || 0;
    const sellingPrice = parseFloat(sellingPriceInput ? sellingPriceInput.value : 0) || 0;
    const tomorrowPrice = parseFloat(tomorrowPriceInput ? tomorrowPriceInput.value : 0) || 0;

    const effectivePrice = todayPrice > 0 ? todayPrice : (sellingPrice > 0 ? sellingPrice : (tomorrowPrice > 0 ? tomorrowPrice : 0));

    if (mrp > 0 && effectivePrice > 0 && effectivePrice < mrp) {
        const pct = ((mrp - effectivePrice) / mrp) * 100;
        const formatted = (pct % 1 === 0) ? pct.toFixed(0) : pct.toFixed(1);
        saveOfferInput.value = formatted + '% OFF';
    } else if (mrp > 0 && effectivePrice >= mrp) {
        saveOfferInput.value = '';
    }
}

document.addEventListener('input', (event) => {
    if (event.target.matches('[data-type="mrp"], [data-type="selling_price"], [data-type="today_price"], [data-type="tomorrow_price"]')) {
        const row = event.target.closest('tr');
        if (row) {
            autoCalculateSaveOffer(row);
        }
    }
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('#multiUpdateForm tbody tr').forEach(row => {
        autoCalculateSaveOffer(row);
    });
});
</script>
@endsection

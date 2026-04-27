@extends('layouts.dashboard')
@section('title', 'Products')
@section('page_title', 'Product Management')

@section('content')
<div class="p-4 sm:p-6">

    {{-- Stats --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach([['Total Products','total','indigo','fa-box'],['Active','active','green','fa-circle-check'],['Inactive','inactive','orange','fa-circle-xmark'],['Out of Stock','out_of_stock','purple','fa-battery-empty']] as [$label,$key,$color,$icon])
        <div class="bg-white p-5 rounded-2xl shadow-sm border flex items-center gap-4">
            <div class="w-12 h-12 bg-{{ $color }}-100 text-{{ $color }}-600 rounded-xl flex items-center justify-center text-xl">
                <i class="fa-solid {{ $icon }}"></i>
            </div>
            <div>
                <p class="text-slate-500 text-xs font-bold uppercase">{{ $label }}</p>
                <h3 class="text-2xl font-black">{{ $stats[$key] }}</h3>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <form method="GET" class="mb-6 flex flex-col gap-3 rounded-2xl border bg-white p-4 shadow-sm sm:flex-row sm:flex-wrap sm:items-center">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..."
               class="w-full bg-slate-50 border border-slate-200 rounded-lg px-4 py-2 text-sm outline-none focus:border-indigo-500 sm:min-w-[150px] sm:flex-1">
        <select name="category" class="bg-slate-50 border border-slate-200 rounded-lg px-4 py-2 text-sm outline-none">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="bg-slate-50 border border-slate-200 rounded-lg px-4 py-2 text-sm outline-none">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
        <a href="{{ route('dashboard.products') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Reset</a>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold">Filter</button>
        <button type="button" onclick="openModal('addProductModal')"
                class="bg-amber-400 hover:bg-amber-500 text-black px-4 py-2 rounded-lg text-sm font-bold flex items-center gap-1">
            <i class="fa-solid fa-plus"></i> Add Product
        </button>
    </form>

    {{-- Table --}}
    <div class="space-y-4 md:hidden">
        @forelse($products as $i => $product)
        <article class="rounded-2xl border bg-white p-4 shadow-sm">
            <div class="flex items-start gap-3">
                @if($product->images && count($product->images))
                    <img src="{{ asset('storage/'.$product->images[0]) }}" class="h-16 w-16 rounded-xl object-cover">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-xl bg-gray-100 text-gray-400 text-sm"><i class="fa-regular fa-image"></i></div>
                @endif
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-slate-400">#{{ $products->firstItem() + $i }}</p>
                    <p class="mt-1 text-sm font-bold text-slate-800">{{ $product->name }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ $product->category->name ?? '-' }} | {{ $product->subcategory->name ?? '-' }}</p>
                </div>
                <span class="rounded px-2 py-1 text-[10px] font-bold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $product->is_active ? 'ACTIVE' : 'DEACTIVE' }}
                </span>
            </div>

            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Price</p>
                    <p class="mt-1 text-sm font-semibold text-slate-700">Rs{{ number_format($product->price, 2) }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Stock</p>
                    <p class="mt-1 text-sm font-bold {{ $product->is_active ? 'text-green-700' : 'text-red-600' }}">{{ $product->is_active ? 'Active' : 'Deactive' }}</p>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <a href="{{ route('dashboard.products.show', $product) }}"
                   class="inline-flex items-center rounded-lg bg-blue-50 p-2 text-xs text-blue-600 hover:bg-blue-100">
                    <i class="fa-solid fa-eye"></i>
                </a>
                @php
                    $editProductPayload = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'category_id' => $product->category_id,
                        'subcategory_id' => $product->subcategory_id,
                        'description' => $product->description,
                        'price' => $product->price,
                        'is_active' => $product->is_active ? 1 : 0,
                        'images_count' => is_array($product->images) ? count($product->images) : 0,
                        'images' => is_array($product->images) ? array_values($product->images) : [],
                        'variants' => is_array($product->variants) ? array_values($product->variants) : [],
                    ];
                @endphp
                <button onclick='openEditModal(@json($editProductPayload))'
                        class="inline-flex items-center rounded-lg bg-indigo-50 p-2 text-xs text-indigo-600 hover:bg-indigo-100">
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>
                <form method="POST" action="{{ route('dashboard.products.destroy', $product) }}"
                      onsubmit="return confirm('Delete this product?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-50 p-2 text-xs text-red-500 hover:bg-red-100">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </form>
            </div>
        </article>
        @empty
        <div class="rounded-2xl border bg-white px-6 py-12 text-center text-gray-400">No products found.</div>
        @endforelse
    </div>

    <div class="hidden overflow-x-auto rounded-2xl bg-white border shadow-sm md:block">
        <table class="w-full text-left min-w-[820px]">
            <thead class="bg-slate-50 border-b">
                <tr class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Sr.No.</th>
                    <th class="px-6 py-4">Product</th>
                    <th class="px-6 py-4">Category</th>
                    <th class="px-6 py-4">Sub Category</th>
                    <th class="px-6 py-4">Price</th>
                    <th class="px-6 py-4">Stock</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $i => $product)
                <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 text-xs font-bold text-slate-400">{{ $products->firstItem() + $i }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($product->images && count($product->images))
                                <img src="{{ asset('storage/'.$product->images[0]) }}" class="w-10 h-10 rounded-lg object-cover">
                            @else
                                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-xs"><i class="fa-regular fa-image"></i></div>
                            @endif
                            <div>
                                <p class="text-xs font-bold text-slate-700">{{ $product->name }}</p>
                                <p class="text-[10px] text-slate-400">ID: {{ $product->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-xs font-semibold text-slate-600">{{ $product->category->name ?? '—' }}</td>
                    <td class="px-6 py-4 text-xs font-semibold text-slate-600">{{ $product->subcategory->name ?? '�' }}</td>
                    <td class="px-6 py-4 text-xs font-semibold text-slate-700">₹{{ number_format($product->price, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs font-bold px-2 py-1 rounded {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $product->is_active ? 'ACTIVE' : 'DEACTIVE' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('dashboard.products.show', $product) }}"
                               class="p-2 hover:bg-blue-100 text-blue-600 rounded-lg text-xs">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            @php
                                $editProductPayload = [
                                    'id' => $product->id,
                                    'name' => $product->name,
                                    'category_id' => $product->category_id,
                                    'subcategory_id' => $product->subcategory_id,
                                    'description' => $product->description,
                                    'price' => $product->price,
                                    'is_active' => $product->is_active ? 1 : 0,
                                    'images_count' => is_array($product->images) ? count($product->images) : 0,
                                    'images' => is_array($product->images) ? array_values($product->images) : [],
                                    'variants' => is_array($product->variants) ? array_values($product->variants) : [],
                                ];
                            @endphp
                            <button onclick='openEditModal(@json($editProductPayload))'
                                    class="p-2 hover:bg-indigo-100 text-indigo-600 rounded-lg text-xs">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <form method="POST" action="{{ route('dashboard.products.destroy', $product) }}"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 hover:bg-red-100 text-red-500 rounded-lg text-xs">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-gray-400">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $products->withQueryString()->links() }}</div>
</div>

{{-- Add Product Modal --}}
<div id="addProductModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/60 p-4">
    <div class="bg-white w-full max-w-3xl rounded-xl shadow-2xl flex flex-col max-h-[95vh]">

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h2 class="text-base font-semibold text-gray-800">Add New Product</h2>
            <button onclick="closeModal('addProductModal')" class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>

        <form method="POST" action="{{ route('dashboard.products.store') }}" enctype="multipart/form-data"
              class="overflow-y-auto px-6 py-5 space-y-4" id="addProductForm">
            @csrf

            {{-- Select Category --}}
            <div>
                <label class="block text-sm text-gray-700 mb-1">Select Category</label>
                <div class="relative">
                    <select name="category_id" id="addCategorySelect" required onchange="loadSubcategories(this.value)"
                            class="w-full appearance-none border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 bg-white outline-none focus:border-blue-400 pr-8">
                        <option value="">Select category</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">&#9660;</span>
                </div>
            </div>

            {{-- Select Sub Category --}}
            <div>
                <label class="block text-sm text-gray-700 mb-1">Select Sub Category</label>
                <div class="relative">
                    <select name="subcategory_id" id="addSubcategorySelect"
                            class="w-full appearance-none border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 bg-white outline-none focus:border-blue-400 pr-8">
                        <option value="">Select sub category</option>
                        @foreach($subcategories as $sub)
                            <option value="{{ $sub->id }}" data-parent="{{ $sub->parent_id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                    <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs">&#9660;</span>
                </div>
            </div>

            {{-- Product Images --}}
            <div>
                <label class="block text-sm text-gray-700 mb-2">Product Images</label>
                <div class="flex items-start gap-2">
                    <div id="addImagePickerRow" class="flex-1"></div>
                    <button type="button" onclick="addSelectedImage()"
                            class="px-3 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-semibold rounded whitespace-nowrap">
                        Add
                    </button>
                </div>
                <input type="file" id="finalAddImagesInput" name="images[]" multiple class="hidden">
                <p class="mt-1 text-[11px] text-gray-400">Gallery se ek saath multiple images choose karo, phir Add dabao. Sab images neeche preview mein aa jayengi.</p>
                <div id="addProductImagePreviews" class="mt-3 flex flex-wrap gap-3"></div>
            </div>

            {{-- Product Name --}}
            <div>
                <label class="block text-sm text-gray-700 mb-1">Product Name</label>
                <input type="text" name="name" required placeholder="Enter product name"
                       class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-blue-400">
            </div>

            {{-- Product Description --}}
            <div>
                <label class="block text-sm text-gray-700 mb-1">Product Description</label>
                <textarea name="description" rows="4" placeholder="Enter Product Description"
                          class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 outline-none resize-none focus:border-blue-400"></textarea>
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1">Stock Status</label>
                <select name="is_active"
                        class="w-full border border-gray-300 rounded px-3 py-2.5 text-sm text-gray-700 outline-none focus:border-blue-400">
                    <option value="1">Active</option>
                    <option value="0">Deactive</option>
                </select>
            </div>

            {{-- Product Variants --}}
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-gray-800">Product Variants</span>
                    <button type="button" onclick="addVariant()"
                            class="px-3 py-1.5 bg-amber-400 hover:bg-amber-500 text-white text-xs font-semibold rounded">
                        Add Variant
                    </button>
                </div>

                <div id="variantsContainer" class="space-y-3">
                    {{-- Initial variant row --}}
                    <div class="variant-row border border-gray-200 rounded p-3">
                        <div class="flex items-start gap-2 mb-2">
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">Quantity</p>
                                <input type="text" name="variants[0][quantity]" placeholder="e.g. 500-600"
                                       class="w-full border border-gray-300 rounded px-2 py-2 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div class="w-32">
                                <p class="text-xs text-gray-500 mb-1">Unit</p>
                                <div class="relative">
                                    <select name="variants[0][unit]"
                                            class="w-full appearance-none border border-gray-300 rounded px-2 py-2 text-sm bg-white outline-none pr-6">
                                        <option>Gram</option><option>Kg</option><option>Pc</option><option>Litre</option>
                                    </select>
                                    <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">&#9660;</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">Piece</p>
                                <input type="text" name="variants[0][piece]" placeholder="e.g. 6-8 pieces"
                                       class="w-full border border-gray-300 rounded px-2 py-2 text-sm outline-none focus:border-blue-400">
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">MRP</p>
                                <div class="relative">
                                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">₹</span>
                                    <input type="number" name="variants[0][mrp]" min="0" step="0.01" data-variant-mrp
                                           class="w-full border border-gray-300 rounded pl-5 pr-2 py-2 text-sm outline-none focus:border-blue-400">
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">Selling Price</p>
                                <div class="relative">
                                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">₹</span>
                                    <input type="number" name="variants[0][selling_price]" min="0" step="0.01" data-variant-selling-price
                                           class="w-full border border-gray-300 rounded pl-5 pr-2 py-2 text-sm outline-none focus:border-blue-400">
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-500 mb-1">Save Offer</p>
                                <div class="relative">
                                    <input type="number" name="variants[0][save_offer]" min="0" max="100" step="0.1" data-variant-save-offer readonly
                                           class="w-full border border-gray-300 rounded bg-slate-50 px-2 pr-5 py-2 text-sm outline-none focus:border-blue-400">
                                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">%</span>
                                </div>
                            </div>
                            <button type="button" onclick="removeVariant(this)"
                                    class="mt-5 text-gray-400 hover:text-red-500 text-lg leading-none">&times;</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end gap-3 pt-2 pb-1">
                <button type="button" onclick="closeModal('addProductModal')"
                        class="px-6 py-2 rounded border border-gray-300 text-sm text-gray-600 hover:bg-gray-50 font-medium">Cancel</button>
                <button type="submit"
                        class="px-6 py-2 rounded bg-red-500 hover:bg-red-600 text-white text-sm font-semibold">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Product Modal --}}
<div id="editProductModal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl flex flex-col max-h-[95vh]">
        <div class="p-5 border-b flex justify-between items-center bg-slate-50 rounded-t-2xl">
            <h2 class="font-bold text-slate-700">Edit Product</h2>
            <button onclick="closeModal('editProductModal')" class="text-slate-400 hover:text-red-500 text-2xl">&times;</button>
        </div>
        <form id="editProductForm" method="POST" enctype="multipart/form-data" class="overflow-y-auto p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1">Category</label>
                <select name="category_id" id="editCatId" onchange="loadSubcategories(this.value, 'editSubcategoryId')"
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                    <option value="">Select category</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1">Sub Category</label>
                <select name="subcategory_id" id="editSubcategoryId"
                        class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                    <option value="">Select sub category</option>
                    @foreach($subcategories as $sub)
                        <option value="{{ $sub->id }}" data-parent="{{ $sub->parent_id }}">{{ $sub->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1">Product Name</label>
                <input type="text" name="name" id="editProductName" required
                       class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none">
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1">Description</label>
                <textarea name="description" id="editDescription" rows="4"
                          class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none resize-none"></textarea>
            </div>
            <div>
                <label class="text-xs font-bold text-slate-600 block mb-1">Product Images</label>
                <div class="flex items-start gap-2">
                    <div id="editImagePickerRow" class="flex-1"></div>
                    <button type="button" onclick="addSelectedEditImage()"
                            class="px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded whitespace-nowrap">
                        Add
                    </button>
                </div>
                <input type="hidden" name="existing_images_present" value="1">
                <input type="file" id="finalEditImagesInput" name="images[]" multiple class="hidden">
                <div id="editExistingImagesInputs" class="hidden"></div>
                <p id="editImageHint" class="mt-1 text-[11px] text-slate-400">Current images neeche dikhengi. Delete icon se hata sakte ho, aur new images add karne ke liye image choose karke Add dabao.</p>
                <div class="mt-4">
                    <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">Current Images</p>
                    <div id="editCurrentImagePreviews" class="flex flex-wrap gap-3"></div>
                </div>
                <div class="mt-4">
                    <p class="mb-2 text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">New Images</p>
                    <div id="editNewImagePreviews" class="flex flex-wrap gap-3"></div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">Price (₹)</label>
                    <input type="number" name="price" id="editPrice" min="0" step="0.01"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                </div>
                <div>
                    <label class="text-xs font-bold text-slate-600 block mb-1">Stock Status</label>
                    <select name="is_active" id="editIsActive" class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm outline-none">
                        <option value="1">Active</option>
                        <option value="0">Deactive</option>
                    </select>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-slate-800">Product Variants</span>
                    <button type="button" onclick="addEditVariant()"
                            class="px-3 py-1.5 bg-amber-400 hover:bg-amber-500 text-white text-xs font-semibold rounded">
                        Add Variant
                    </button>
                </div>

                <div id="editVariantsContainer" class="space-y-3"></div>
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-2.5 rounded-lg font-bold text-sm">Update</button>
                <button type="button" onclick="closeModal('editProductModal')"
                        class="bg-slate-500 text-white px-8 py-2.5 rounded-lg font-bold text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.remove('hidden'); document.getElementById(id).classList.add('flex'); }
function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');

    if (id === 'addProductModal') {
        resetAddProductImages();
    }

    if (id === 'editProductModal') {
        resetEditProductImages();
    }
}

let addProductImages = [];
let editProductImages = [];
let currentEditImages = [];

function renderAddImagePickerRow() {
    const pickerRow = document.getElementById('addImagePickerRow');

    if (!pickerRow) return;

    pickerRow.innerHTML = `
        <label class="flex items-center border border-gray-300 rounded overflow-hidden flex-1 cursor-pointer">
            <span class="bg-gray-100 border-r border-gray-300 px-3 py-2 text-sm text-gray-600 whitespace-nowrap hover:bg-gray-200">Choose Image</span>
            <span class="add-image-label px-3 text-sm text-gray-400 truncate">No file chosen</span>
            <input type="file" accept="image/*" multiple class="hidden" onchange="handleAddImageSelection(this)">
        </label>
    `;
}

function handleAddImageSelection(input) {
    const row = input.closest('label');
    const label = row ? row.querySelector('.add-image-label') : null;

    if (label) {
        if (input.files.length > 1) {
            label.textContent = input.files.length + ' images selected';
        } else if (input.files.length === 1) {
            label.textContent = input.files[0].name;
        } else {
            label.textContent = 'No file chosen';
        }
    }
}

function addSelectedImage() {
    const pickerRow = document.getElementById('addImagePickerRow');

    if (!pickerRow) return;

    const input = pickerRow.querySelector('input[type="file"]');

    if (!input || !input.files || !input.files.length) return;

    Array.from(input.files).forEach((file) => {
        addProductImages.push({
            id: 'preview-' + Date.now() + '-' + Math.random().toString(36).slice(2, 8),
            file,
        });
    });

    syncAddProductImagesInput();
    renderAddImagePickerRow();
    syncAddImagePreviews();
}

function syncAddImagePreviews() {
    const previewContainer = document.getElementById('addProductImagePreviews');

    if (!previewContainer) return;

    previewContainer.innerHTML = '';

    addProductImages.forEach((imageItem, index) => {
        const previewUrl = URL.createObjectURL(imageItem.file);
        const previewCard = document.createElement('div');
        previewCard.className = 'w-24';
        previewCard.dataset.previewId = imageItem.id;
        previewCard.innerHTML = `
            <div class="relative w-24 h-24 rounded-xl border border-gray-200 overflow-hidden bg-gray-50 shadow-sm">
                <button type="button"
                        onclick="removeSelectedImage('${imageItem.id}')"
                        class="absolute right-1 top-1 z-10 flex h-5 w-5 items-center justify-center rounded-full bg-black/70 text-white text-[10px] hover:bg-red-500">
                    &times;
                </button>
                <img src="${previewUrl}" alt="Preview ${index + 1}" class="w-full h-full object-cover">
            </div>
            <p class="mt-1 text-[11px] text-gray-500 truncate">${imageItem.file.name}</p>
        `;

        const img = previewCard.querySelector('img');
        if (img) {
            img.onload = () => URL.revokeObjectURL(previewUrl);
        }

        previewContainer.appendChild(previewCard);
    });
}

function removeSelectedImage(previewId) {
    addProductImages = addProductImages.filter((imageItem) => imageItem.id !== previewId);
    syncAddProductImagesInput();
    syncAddImagePreviews();
}

function resetAddProductImages() {
    const previewContainer = document.getElementById('addProductImagePreviews');
    const finalInput = document.getElementById('finalAddImagesInput');

    addProductImages = [];

    if (previewContainer) {
        previewContainer.innerHTML = '';
    }

    if (finalInput) {
        finalInput.value = '';
    }

    renderAddImagePickerRow();
}

function syncAddProductImagesInput() {
    const finalInput = document.getElementById('finalAddImagesInput');

    if (!finalInput) return;

    const transfer = new DataTransfer();

    addProductImages.forEach((imageItem) => {
        transfer.items.add(imageItem.file);
    });

    finalInput.files = transfer.files;
}

document.addEventListener('DOMContentLoaded', function () {
    renderAddImagePickerRow();
    renderEditImagePickerRow();
});

function openEditModal(product) {
    document.getElementById('editProductName').value  = product.name ?? '';
    document.getElementById('editCatId').value        = product.category_id ?? '';
    document.getElementById('editDescription').value  = product.description ?? '';
    document.getElementById('editPrice').value        = product.price ?? '';
    document.getElementById('editIsActive').value     = product.is_active ?? 1;
    document.getElementById('editImageHint').textContent = 'Current images neeche dikhengi. Delete icon se hata sakte ho, aur new images add karne ke liye image choose karke Add dabao.';
    resetEditProductImages(false);
    renderEditCurrentImages(product.images ?? []);
    renderEditVariants(product.variants ?? []);
    loadSubcategories(product.category_id, 'editSubcategoryId', product.subcategory_id);
    document.getElementById('editProductForm').action = '/products/' + product.id;
    openModal('editProductModal');
}


// File label updater
function updateFileLabel(input, labelId) {
    const label = document.getElementById(labelId);
    if (input.files.length > 1) {
        label.textContent = input.files.length + ' files chosen';
    } else if (input.files.length === 1) {
        label.textContent = input.files[0].name;
    } else {
        label.textContent = 'No file chosen';
    }
}

function renderEditImagePickerRow() {
    const pickerRow = document.getElementById('editImagePickerRow');

    if (!pickerRow) return;

    pickerRow.innerHTML = `
        <label class="flex items-center border border-slate-200 rounded-lg overflow-hidden flex-1 cursor-pointer">
            <span class="bg-slate-50 border-r border-slate-200 px-3 py-2 text-sm text-slate-600 whitespace-nowrap hover:bg-slate-100">Choose Image</span>
            <span class="edit-image-label px-3 text-sm text-slate-400 truncate">No file chosen</span>
            <input type="file" accept="image/*" multiple class="hidden" onchange="handleEditImageSelection(this)">
        </label>
    `;
}

function handleEditImageSelection(input) {
    const row = input.closest('label');
    const label = row ? row.querySelector('.edit-image-label') : null;

    if (label) {
        if (input.files.length > 1) {
            label.textContent = input.files.length + ' images selected';
        } else if (input.files.length === 1) {
            label.textContent = input.files[0].name;
        } else {
            label.textContent = 'No file chosen';
        }
    }
}

function addSelectedEditImage() {
    const pickerRow = document.getElementById('editImagePickerRow');

    if (!pickerRow) return;

    const input = pickerRow.querySelector('input[type="file"]');

    if (!input || !input.files || !input.files.length) return;

    Array.from(input.files).forEach((file) => {
        editProductImages.push({
            id: 'edit-preview-' + Date.now() + '-' + Math.random().toString(36).slice(2, 8),
            file,
        });
    });

    syncEditProductImagesInput();
    renderEditImagePickerRow();
    syncEditImagePreviews();
}

function syncEditImagePreviews() {
    const previewContainer = document.getElementById('editNewImagePreviews');

    if (!previewContainer) return;

    previewContainer.innerHTML = '';

    editProductImages.forEach((imageItem, index) => {
        const previewUrl = URL.createObjectURL(imageItem.file);
        const previewCard = document.createElement('div');
        previewCard.className = 'w-24';
        previewCard.dataset.previewId = imageItem.id;
        previewCard.innerHTML = `
            <div class="relative w-24 h-24 rounded-xl border border-slate-200 overflow-hidden bg-slate-50 shadow-sm">
                <button type="button"
                        onclick="removeSelectedEditImage('${imageItem.id}')"
                        class="absolute right-1 top-1 z-10 flex h-5 w-5 items-center justify-center rounded-full bg-black/70 text-white text-[10px] hover:bg-red-500">
                    &times;
                </button>
                <img src="${previewUrl}" alt="New preview ${index + 1}" class="w-full h-full object-cover">
            </div>
            <p class="mt-1 text-[11px] text-slate-500 truncate">${imageItem.file.name}</p>
        `;

        const img = previewCard.querySelector('img');
        if (img) {
            img.onload = () => URL.revokeObjectURL(previewUrl);
        }

        previewContainer.appendChild(previewCard);
    });
}

function removeSelectedEditImage(previewId) {
    editProductImages = editProductImages.filter((imageItem) => imageItem.id !== previewId);
    syncEditProductImagesInput();
    syncEditImagePreviews();
}

function syncEditProductImagesInput() {
    const finalInput = document.getElementById('finalEditImagesInput');

    if (!finalInput) return;

    const transfer = new DataTransfer();

    editProductImages.forEach((imageItem) => {
        transfer.items.add(imageItem.file);
    });

    finalInput.files = transfer.files;
}

function resetEditProductImages(shouldClearCurrent = true) {
    const previewContainer = document.getElementById('editNewImagePreviews');
    const currentContainer = document.getElementById('editCurrentImagePreviews');
    const finalInput = document.getElementById('finalEditImagesInput');
    const existingInputs = document.getElementById('editExistingImagesInputs');

    editProductImages = [];

    if (previewContainer) {
        previewContainer.innerHTML = '';
    }

    if (shouldClearCurrent && currentContainer) {
        currentContainer.innerHTML = '';
    }

    if (shouldClearCurrent) {
        currentEditImages = [];
    }

    if (shouldClearCurrent && existingInputs) {
        existingInputs.innerHTML = '';
    }

    if (finalInput) {
        finalInput.value = '';
    }

    renderEditImagePickerRow();
}

function renderEditCurrentImages(images) {
    const currentContainer = document.getElementById('editCurrentImagePreviews');

    if (!currentContainer) return;

    currentEditImages = Array.isArray(images) ? [...images] : [];
    currentContainer.innerHTML = '';
    syncEditExistingImagesInputs();

    if (!currentEditImages.length) {
        currentContainer.innerHTML = `<div class="rounded-xl bg-slate-50 px-4 py-8 text-sm font-semibold text-slate-400">No current images uploaded.</div>`;
        return;
    }

    currentEditImages.forEach((imagePath, index) => {
        const previewCard = document.createElement('div');
        previewCard.className = 'w-24';
        previewCard.innerHTML = `
            <div class="relative w-24 h-24 rounded-xl border border-slate-200 overflow-hidden bg-slate-50 shadow-sm">
                <button type="button"
                        onclick="removeCurrentEditImage('${imagePath.replace(/\\/g, '\\\\').replace(/'/g, "\\'")}')"
                        class="absolute right-1 top-1 z-10 flex h-5 w-5 items-center justify-center rounded-full bg-black/70 text-white text-[10px] hover:bg-red-500">
                    &times;
                </button>
                <img src="/storage/${imagePath}" alt="Current image ${index + 1}" class="w-full h-full object-cover">
            </div>
        `;

        currentContainer.appendChild(previewCard);
    });
}

function syncEditExistingImagesInputs() {
    const existingInputs = document.getElementById('editExistingImagesInputs');

    if (!existingInputs) return;

    existingInputs.innerHTML = '';

    currentEditImages.forEach((imagePath) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'existing_images[]';
        input.value = imagePath;
        existingInputs.appendChild(input);
    });
}

function removeCurrentEditImage(imagePath) {
    currentEditImages = currentEditImages.filter((item) => item !== imagePath);
    renderEditCurrentImages(currentEditImages);
}


// Subcategory filter by category
function loadSubcategories(categoryId, selectId = 'addSubcategorySelect', selectedValue = '') {
    const select = document.getElementById(selectId);

    if (!select) return;

    const options = select.querySelectorAll('option');
    let hasSelectedOption = false;

    options.forEach(opt => {
        if (opt.value === '') return;

        const isVisible = !categoryId || opt.dataset.parent == categoryId;
        opt.style.display = isVisible ? '' : 'none';

        if (isVisible && selectedValue !== '' && opt.value == selectedValue) {
            hasSelectedOption = true;
        }
    });

    select.value = hasSelectedOption ? selectedValue : '';
}

// Variant management
let variantIndex = 1;
let editVariantIndex = 0;

function getVariantRowTemplate(idx, tone = 'gray', values = {}) {
    const borderClass = tone === 'slate' ? 'border-slate-200' : 'border-gray-200';
    const inputBorderClass = tone === 'slate' ? 'border-slate-200' : 'border-gray-300';
    const inputBgClass = tone === 'slate' ? 'bg-slate-50' : 'bg-slate-50';
    const removeClass = tone === 'slate' ? 'text-slate-400' : 'text-gray-400';
    const quantity = values.quantity ?? '';
    const unit = values.unit ?? 'Gram';
    const piece = values.piece ?? '';
    const mrp = values.mrp ?? '';
    const sellingPrice = values.selling_price ?? '';
    const saveOffer = values.save_offer ?? '';

    return `
        <div class="flex items-start gap-2 mb-2">
            <div class="flex-1">
                <p class="text-xs text-gray-500 mb-1">Quantity</p>
                <input type="text" name="variants[${idx}][quantity]" value="${quantity}" placeholder="e.g. 500-600"
                       class="w-full border ${inputBorderClass} rounded px-2 py-2 text-sm outline-none focus:border-blue-400">
            </div>
            <div class="w-32">
                <p class="text-xs text-gray-500 mb-1">Unit</p>
                <div class="relative">
                    <select name="variants[${idx}][unit]"
                            class="w-full appearance-none border ${inputBorderClass} rounded px-2 py-2 text-sm bg-white outline-none pr-6">
                        <option ${unit === 'Gram' ? 'selected' : ''}>Gram</option>
                        <option ${unit === 'Kg' ? 'selected' : ''}>Kg</option>
                        <option ${unit === 'Pc' ? 'selected' : ''}>Pc</option>
                        <option ${unit === 'Litre' ? 'selected' : ''}>Litre</option>
                    </select>
                    <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">&#9660;</span>
                </div>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500 mb-1">Piece</p>
                <input type="text" name="variants[${idx}][piece]" value="${piece}" placeholder="e.g. 6-8 pieces"
                       class="w-full border ${inputBorderClass} rounded px-2 py-2 text-sm outline-none focus:border-blue-400">
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500 mb-1">MRP</p>
                <div class="relative">
                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">₹</span>
                    <input type="number" name="variants[${idx}][mrp]" value="${mrp}" min="0" step="0.01" data-variant-mrp
                           class="w-full border ${inputBorderClass} rounded pl-5 pr-2 py-2 text-sm outline-none focus:border-blue-400">
                </div>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500 mb-1">Selling Price</p>
                <div class="relative">
                    <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">₹</span>
                    <input type="number" name="variants[${idx}][selling_price]" value="${sellingPrice}" min="0" step="0.01" data-variant-selling-price
                           class="w-full border ${inputBorderClass} rounded pl-5 pr-2 py-2 text-sm outline-none focus:border-blue-400">
                </div>
            </div>
            <div class="flex-1">
                <p class="text-xs text-gray-500 mb-1">Save Offer</p>
                <div class="relative">
                    <input type="number" name="variants[${idx}][save_offer]" value="${saveOffer}" min="0" max="100" step="0.1" data-variant-save-offer readonly
                           class="w-full border ${inputBorderClass} rounded ${inputBgClass} px-2 pr-5 py-2 text-sm outline-none focus:border-blue-400">
                    <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">%</span>
                </div>
            </div>
            <button type="button" onclick="removeVariant(this)"
                    class="mt-5 ${removeClass} hover:text-red-500 text-lg leading-none">&times;</button>
        </div>`;
}

function addVariant() {
    const idx = variantIndex++;
    const container = document.getElementById('variantsContainer');
    const div = document.createElement('div');
    div.className = 'variant-row border border-gray-200 rounded p-3';
    div.innerHTML = getVariantRowTemplate(idx, 'gray');
    container.appendChild(div);
}

function addEditVariant(values = {}) {
    const idx = editVariantIndex++;
    const container = document.getElementById('editVariantsContainer');
    const div = document.createElement('div');
    div.className = 'variant-row border border-slate-200 rounded-lg p-3';
    div.innerHTML = getVariantRowTemplate(idx, 'slate', values);
    container.appendChild(div);
    updateVariantSaveOffer(div);
}

function renderEditVariants(variants) {
    const container = document.getElementById('editVariantsContainer');
    if (!container) return;

    container.innerHTML = '';
    editVariantIndex = 0;

    if (Array.isArray(variants) && variants.length) {
        variants.forEach((variant) => addEditVariant(variant));
    } else {
        addEditVariant();
    }
}

function removeVariant(btn) {
    const rows = document.querySelectorAll('.variant-row');
    if (rows.length > 1) {
        btn.closest('.variant-row').remove();
    }
}

function updateVariantSaveOffer(row) {
    const mrpInput = row.querySelector('[data-variant-mrp]');
    const sellingPriceInput = row.querySelector('[data-variant-selling-price]');
    const saveOfferInput = row.querySelector('[data-variant-save-offer]');

    if (!mrpInput || !sellingPriceInput || !saveOfferInput) return;

    const mrp = parseFloat(mrpInput.value);
    const sellingPrice = parseFloat(sellingPriceInput.value);

    if (!mrp || !sellingPrice || mrp <= 0 || sellingPrice >= mrp) {
        saveOfferInput.value = '';
        return;
    }

    const saveOffer = ((mrp - sellingPrice) / mrp) * 100;
    saveOfferInput.value = saveOffer.toFixed(1);
}

document.addEventListener('input', (event) => {
    if (!event.target.matches('[data-variant-mrp], [data-variant-selling-price]')) return;

    const row = event.target.closest('.variant-row');
    if (row) {
        updateVariantSaveOffer(row);
    }
});
</script>
@endsection

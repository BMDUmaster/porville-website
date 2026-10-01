<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSlot;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductSlotController extends Controller
{
    public function index(Request $request)
    {
        $slots = ProductSlot::query()
            ->with(['products' => fn ($query) => $query->select('products.id', 'products.name')->orderBy('products.name')])
            ->orderByDesc('is_active')
            ->latest('id')
            ->get();

        $editing = $request->filled('edit')
            ? ProductSlot::with('products:id')->find($request->integer('edit'))
            : null;

        $categories = Category::parents()->ordered()->get(['id', 'name']);
        $subcategories = Category::subcategories()->orderBy('name')->get(['id', 'name', 'parent_id']);

        // Enquiry-only products (Live Stock) are ordered by phone, not the cart.
        $products = Product::query()
            ->whereHas('category', fn ($query) => $query->where('is_enquiry_only', false))
            ->orderBy('name')
            ->get(['id', 'name', 'category_id', 'subcategory_id', 'is_active']);

        return view('dashboard.product-slots.index', compact('slots', 'editing', 'categories', 'subcategories', 'products'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $slot = ProductSlot::create($data['slot']);
        $slot->products()->sync($data['product_ids']);

        return redirect()->route('dashboard.product-slots')
            ->with('success', 'Product slot added for ' . count($data['product_ids']) . ' product(s).');
    }

    public function update(Request $request, ProductSlot $slot)
    {
        $data = $this->validated($request);

        $slot->update($data['slot']);
        $slot->products()->sync($data['product_ids']);

        return redirect()->route('dashboard.product-slots')
            ->with('success', 'Product slot updated.');
    }

    public function destroy(ProductSlot $slot)
    {
        $slot->delete();

        return redirect()->route('dashboard.product-slots')
            ->with('success', 'Product slot deleted. Its products can now be ordered any time (unless another slot covers them).');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name'          => ['nullable', 'string', 'max:120'],
            'type'          => ['required', Rule::in([ProductSlot::TYPE_DAILY, ProductSlot::TYPE_DATE])],
            'slot_date'     => ['nullable', 'required_if:type,' . ProductSlot::TYPE_DATE, 'date', 'after_or_equal:today'],
            'start_time'    => ['required', 'date_format:H:i'],
            'end_time'      => ['required', 'date_format:H:i', 'after:start_time'],
            'is_active'     => ['nullable', 'boolean'],
            'product_ids'   => ['required', 'array', 'min:1'],
            'product_ids.*' => ['integer', Rule::exists('products', 'id')],
        ], [
            'slot_date.required_if'    => 'Please choose the date for a date-wise slot.',
            'slot_date.after_or_equal' => 'The slot date cannot be in the past.',
            'end_time.after'           => 'End time must be after the start time.',
            'product_ids.required'     => 'Select at least one product for this slot.',
        ]);

        return [
            'slot' => [
                'name'       => trim((string) ($data['name'] ?? '')) ?: null,
                'type'       => $data['type'],
                'slot_date'  => $data['type'] === ProductSlot::TYPE_DATE ? $data['slot_date'] : null,
                'start_time' => $data['start_time'],
                'end_time'   => $data['end_time'],
                'is_active'  => $request->boolean('is_active'),
            ],
            'product_ids' => array_values(array_unique(array_map('intval', $data['product_ids']))),
        ];
    }
}

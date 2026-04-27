<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Support\WebpImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'subcategory']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->paginate(15);

        $stats = [
            'total'        => Product::count(),
            'active'       => Product::where('is_active', true)->count(),
            'inactive'     => Product::where('is_active', false)->count(),
            'out_of_stock' => Product::where('is_active', false)->count(),
        ];

        $categories = Category::parents()->get();
        $subcategories = Category::subcategories()->get();

        return view('dashboard.products.index', compact('products', 'stats', 'categories', 'subcategories'));
    }

    public function show(Product $product)
    {
        $product->load(['category', 'subcategory']);

        return view('dashboard.products.show', compact('product'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:200',
            'category_id'           => ['required', Rule::exists('categories', 'id')->whereNull('parent_id')],
            'subcategory_id'        => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'description'           => 'nullable|string',
            'is_active'             => 'boolean',
            'variants'              => 'nullable|array',
            'variants.*.quantity'   => 'nullable|string|max:50',
            'variants.*.unit'       => 'nullable|string|max:30',
            'variants.*.piece'      => 'nullable|string|max:50',
            'variants.*.mrp'        => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.selling_price' => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.save_offer' => 'nullable|string|max:100',
            'variants.*.admin_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.vendor_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'images.*'              => 'nullable|image|max:2048',
            'videos.*'              => 'nullable|file|mimes:mp4,mov,avi,webm,mkv|max:51200',
        ]);

        $this->ensureSubcategoryBelongsToCategory($data['category_id'], $data['subcategory_id'] ?? null);

        $data['slug'] = $this->generateUniqueSlug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['variants'] = $this->normalizeVariants($request->input('variants', []));

        if (empty($data['variants'])) {
            throw ValidationException::withMessages([
                'variants' => 'Add at least one product variant with quantity and selling price.',
            ]);
        }

        $data['unit'] = (string) ($data['variants'][0]['unit'] ?? 'Unit');
        $data['price'] = ! empty($data['variants']) ? (float) ($data['variants'][0]['selling_price'] ?? 0) : 0;
        $data['mrp'] = ! empty($data['variants']) ? (float) ($data['variants'][0]['mrp'] ?? 0) : 0;
        $data['stock'] = $data['is_active'] ? 1 : 0;

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = WebpImage::store($image, 'products');
            }
        }
        $data['images'] = $imagePaths;

        $videoPaths = [];
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $videoPaths[] = $video->store('products/videos', 'public');
            }
        }
        $data['videos'] = $videoPaths;

        Product::create($data);

        return back()->with('success', 'Product added successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:200',
            'category_id'           => ['required', Rule::exists('categories', 'id')->whereNull('parent_id')],
            'subcategory_id'        => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'description'           => 'nullable|string',
            'price'                 => 'required|numeric|min:0|max:99999999.99',
            'mrp'                   => 'nullable|numeric|min:0|max:99999999.99',
            'is_active'             => 'boolean',
            'variants'              => 'nullable|array',
            'variants.*.quantity'   => 'nullable|string|max:50',
            'variants.*.unit'       => 'nullable|string|max:30',
            'variants.*.piece'      => 'nullable|string|max:50',
            'variants.*.mrp'        => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.selling_price' => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.save_offer' => 'nullable|string|max:100',
            'variants.*.admin_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.vendor_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'existing_images_present' => 'nullable',
            'existing_images'       => 'nullable|array',
            'existing_images.*'     => 'string',
            'images.*'              => 'nullable|image|max:2048',
            'videos.*'              => 'nullable|file|mimes:mp4,mov,avi,webm,mkv|max:51200',
        ]);

        $this->ensureSubcategoryBelongsToCategory($data['category_id'], $data['subcategory_id'] ?? null);

        $data['slug'] = $this->generateUniqueSlug($data['name'], $product->id);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['variants'] = $this->normalizeVariants($request->input('variants', []));

        if (! empty($data['variants'])) {
            $data['unit'] = (string) ($data['variants'][0]['unit'] ?? ($product->unit ?: 'Unit'));
            $data['price'] = (float) ($data['variants'][0]['selling_price'] ?? $data['price'] ?? 0);
            $data['mrp'] = (float) ($data['variants'][0]['mrp'] ?? $data['mrp'] ?? 0);
        }

        $data['stock'] = $data['is_active'] ? 1 : 0;

        if ($request->filled('existing_images_present')) {
            $keptImages = array_values(array_filter($request->input('existing_images', [])));
            $newImagePaths = [];

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $newImagePaths[] = WebpImage::store($image, 'products');
                }
            }

            $data['images'] = array_values(array_merge($keptImages, $newImagePaths));
        }

        if ($request->hasFile('videos')) {
            $videoPaths = [];

            foreach ($request->file('videos') as $video) {
                $videoPaths[] = $video->store('products/videos', 'public');
            }

            $data['videos'] = $videoPaths;
        }

        $product->update($data);

        return back()->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    private function ensureSubcategoryBelongsToCategory(int $categoryId, ?int $subcategoryId): void
    {
        if (! $subcategoryId) {
            return;
        }

        $isValid = Category::query()
            ->where('id', $subcategoryId)
            ->where('parent_id', $categoryId)
            ->exists();

        if (! $isValid) {
            throw ValidationException::withMessages([
                'subcategory_id' => 'Selected sub-category does not belong to the chosen category.',
            ]);
        }
    }

    private function normalizeVariants(array $variants): array
    {
        $normalized = [];

        foreach ($variants as $variant) {
            $hasContent = collect([
                $variant['quantity'] ?? null,
                $variant['selling_price'] ?? null,
                $variant['mrp'] ?? null,
                $variant['piece'] ?? null,
                $variant['save_offer'] ?? null,
            ])->contains(fn ($value) => ! blank($value));

            if (! $hasContent) {
                continue;
            }

            if (
                blank($variant['quantity'] ?? null)
                || blank($variant['selling_price'] ?? null)
            ) {
                throw ValidationException::withMessages([
                    'variants' => 'Each product variant must include quantity and selling price.',
                ]);
            }

            $normalized[] = [
                'quantity'      => (string) ($variant['quantity'] ?? ''),
                'unit'          => (string) ($variant['unit'] ?? 'Gram'),
                'piece'         => (string) ($variant['piece'] ?? ''),
                'mrp'           => (float) ($variant['mrp'] ?? 0),
                'selling_price' => (float) ($variant['selling_price'] ?? 0),
                'save_offer'    => (string) ($variant['save_offer'] ?? ''),
                'admin_amount'  => (float) ($variant['admin_amount'] ?? 0),
                'vendor_amount' => (float) ($variant['vendor_amount'] ?? 0),
            ];
        }

        return $normalized;
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'product';
        $slug = $baseSlug;
        $counter = 2;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

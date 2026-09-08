<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Support\ProductDayPricing;
use App\Support\WebpImage;
use Illuminate\Database\QueryException;
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

    public function searchJson(Request $request)
    {
        $query = Product::with(['category', 'subcategory']);

        if ($request->filled('q')) {
            $search = trim((string) $request->input('q'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('category', fn ($cat) => $cat->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('subcategory', fn ($sub) => $sub->where('name', 'like', "%{$search}%"));
            });
        }

        $products = $query->latest()->limit(50)->get()->map(function ($p) {
            return [
                'id'               => $p->id,
                'name'             => $p->name,
                'price'            => (float) $p->price,
                'mrp'              => (float) $p->mrp,
                'is_active'        => (bool) $p->is_active,
                'category_name'    => $p->category->name ?? 'Uncategorized',
                'subcategory_name' => $p->subcategory->name ?? '-',
                'image'            => ($p->images && count($p->images)) ? asset('storage/' . $p->images[0]) : null,
                'variants'         => $p->variants ?? [],
                'variants_count'   => is_array($p->variants) ? count($p->variants) : 0,
            ];
        });

        return response()->json([
            'success'  => true,
            'products' => $products,
        ]);
    }

    public function multiEdit(Request $request)
    {
        $ids = $request->input('ids', []);
        if (is_string($ids)) {
            $ids = array_filter(array_map('trim', explode(',', $ids)));
        }

        if (empty($ids)) {
            return redirect()->route('dashboard.products')->with('error', 'Please select at least one product for multi-edit.');
        }

        $products = Product::with(['category', 'subcategory'])
            ->whereIn('id', $ids)
            ->get();

        if ($products->isEmpty()) {
            return redirect()->route('dashboard.products')->with('error', 'No valid products found for multi-edit.');
        }

        $categories = Category::parents()->get();
        $subcategories = Category::subcategories()->get();

        return view('dashboard.products.multi-edit', compact('products', 'categories', 'subcategories'));
    }

    public function multiUpdate(Request $request)
    {
        $data = $request->validate([
            'products'                             => 'required|array|min:1',
            'products.*.id'                        => 'required|integer|exists:products,id',
            'products.*.name'                      => 'required|string|max:200',
            'products.*.category_id'               => ['required', Rule::exists('categories', 'id')->whereNull('parent_id')],
            'products.*.subcategory_id'            => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'products.*.description'               => 'nullable|string',
            'products.*.weight'                    => 'nullable|string|max:100',
            'products.*.price'                     => 'required|numeric|min:0|max:99999999.99',
            'products.*.mrp'                       => 'nullable|numeric|min:0|max:99999999.99',
            'products.*.is_active'                 => 'required|boolean',
            'products.*.variants'                  => 'nullable|array',
            'products.*.variants.*.quantity'       => 'nullable|string|max:50',
            'products.*.variants.*.unit'           => 'nullable|string|max:30',
            'products.*.variants.*.piece'          => 'nullable|string|max:50',
            'products.*.variants.*.mrp'            => 'nullable|numeric|min:0|max:99999999.99',
            'products.*.variants.*.selling_price'  => 'nullable|numeric|min:0|max:99999999.99',
            'products.*.variants.*.today_price'    => 'nullable|numeric|min:0|max:99999999.99',
            'products.*.variants.*.tomorrow_price' => 'nullable|numeric|min:0|max:99999999.99',
            'products.*.variants.*.save_offer'     => 'nullable|string|max:100',
            'products.*.variants.*.admin_amount'    => 'nullable|numeric|min:0|max:99999999.99',
            'products.*.variants.*.vendor_amount'   => 'nullable|numeric|min:0|max:99999999.99',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            foreach ($data['products'] as $productInput) {
                $product = Product::findOrFail($productInput['id']);

                $this->ensureSubcategoryBelongsToCategory((int) $productInput['category_id'], isset($productInput['subcategory_id']) ? (int) $productInput['subcategory_id'] : null);

                $slug = $this->generateUniqueSlug($productInput['name'], $product->id);
                $isActive = (bool) $productInput['is_active'];
                $variants = $this->normalizeVariants($productInput['variants'] ?? []);

                $unit = ! empty($variants) ? (string) ($variants[0]['unit'] ?? 'Unit') : ($product->unit ?: 'Unit');
                $price = ! empty($variants)
                    ? ProductDayPricing::sellingPrice($variants[0], 'today', (float) $productInput['price'])
                    : (float) $productInput['price'];
                $mrp = ! empty($variants) ? (float) ($variants[0]['mrp'] ?? $productInput['mrp'] ?? 0) : (float) ($productInput['mrp'] ?? 0);

                $product->update([
                    'name'           => $productInput['name'],
                    'slug'           => $slug,
                    'category_id'    => $productInput['category_id'],
                    'subcategory_id' => $productInput['subcategory_id'] ?? null,
                    'description'    => $productInput['description'] ?? null,
                    'weight'         => $productInput['weight'] ?? null,
                    'price'          => $price,
                    'mrp'            => $mrp,
                    'unit'           => $unit,
                    'is_active'      => $isActive,
                    'stock'          => $isActive ? 1 : 0,
                    'variants'       => $variants,
                ]);
            }
        });

        $count = count($data['products']);

        return redirect()->route('dashboard.products')->with('success', "Successfully updated {$count} products!");
    }

    public function show(Product $product)
    {
        $product->load(['category', 'subcategory']);

        return view('dashboard.products.show', compact('product'));
    }

    public function store(Request $request)
    {
        try {
            return $this->performStore($request);
        } catch (QueryException $e) {
            report($e);

            return back()->withInput()->with('error', $this->databaseErrorMessage($e));
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Could not save product. ' . $e->getMessage());
        }
    }

    private function performStore(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:200',
            'category_id'           => ['required', Rule::exists('categories', 'id')->whereNull('parent_id')],
            'subcategory_id'        => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'description'           => 'nullable|string',
            'weight'                => 'nullable|string|max:100',
            'is_active'             => 'boolean',
            'variants'              => 'nullable|array',
            'variants.*.quantity'   => 'nullable|string|max:50',
            'variants.*.unit'       => 'nullable|string|max:30',
            'variants.*.piece'      => 'nullable|string|max:50',
            'variants.*.mrp'        => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.selling_price' => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.today_price' => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.tomorrow_price' => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.save_offer' => 'nullable|string|max:100',
            'variants.*.admin_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.vendor_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'images.*'              => 'nullable|image|mimes:jpg,jpeg,png,gif,webp,avif|max:5120',
            'videos.*'              => 'nullable|file|mimes:mp4,mov,avi,webm,mkv|max:51200',
        ]);

        $this->ensureSubcategoryBelongsToCategory($data['category_id'], $data['subcategory_id'] ?? null);

        $data['slug'] = $this->generateUniqueSlug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['variants'] = $this->normalizeVariants($request->input('variants', []));

        if (empty($data['variants'])) {
            throw ValidationException::withMessages([
                'variants' => 'Add at least one product variant with selling price.',
            ]);
        }

        $data['unit'] = (string) ($data['variants'][0]['unit'] ?? 'Unit');
        $data['price'] = ! empty($data['variants'])
            ? ProductDayPricing::sellingPrice($data['variants'][0], 'today')
            : 0;
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
        try {
            return $this->performUpdate($request, $product);
        } catch (QueryException $e) {
            report($e);

            return back()->withInput()->with('error', $this->databaseErrorMessage($e));
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Could not update product. ' . $e->getMessage());
        }
    }

    private function performUpdate(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:200',
            'category_id'           => ['required', Rule::exists('categories', 'id')->whereNull('parent_id')],
            'subcategory_id'        => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'description'           => 'nullable|string',
            'weight'                => 'nullable|string|max:100',
            'price'                 => 'required|numeric|min:0|max:99999999.99',
            'mrp'                   => 'nullable|numeric|min:0|max:99999999.99',
            'is_active'             => 'boolean',
            'variants'              => 'nullable|array',
            'variants.*.quantity'   => 'nullable|string|max:50',
            'variants.*.unit'       => 'nullable|string|max:30',
            'variants.*.piece'      => 'nullable|string|max:50',
            'variants.*.mrp'        => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.selling_price' => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.today_price' => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.tomorrow_price' => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.save_offer' => 'nullable|string|max:100',
            'variants.*.admin_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'variants.*.vendor_amount' => 'nullable|numeric|min:0|max:99999999.99',
            'existing_images_present' => 'nullable',
            'existing_images'       => 'nullable|array',
            'existing_images.*'     => 'string',
            'images.*'              => 'nullable|image|mimes:jpg,jpeg,png,gif,webp,avif|max:5120',
            'videos.*'              => 'nullable|file|mimes:mp4,mov,avi,webm,mkv|max:51200',
        ]);

        $this->ensureSubcategoryBelongsToCategory($data['category_id'], $data['subcategory_id'] ?? null);

        $data['slug'] = $this->generateUniqueSlug($data['name'], $product->id);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['variants'] = $this->normalizeVariants($request->input('variants', []));

        if (! empty($data['variants'])) {
            $data['unit'] = (string) ($data['variants'][0]['unit'] ?? ($product->unit ?: 'Unit'));
            $data['price'] = ProductDayPricing::sellingPrice($data['variants'][0], 'today', (float) ($data['price'] ?? 0));
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
                $variant['today_price'] ?? null,
                $variant['tomorrow_price'] ?? null,
                $variant['save_offer'] ?? null,
            ])->contains(fn ($value) => ! blank($value));

            if (! $hasContent) {
                continue;
            }

            if (blank($variant['selling_price'] ?? null) && blank($variant['today_price'] ?? null) && blank($variant['tomorrow_price'] ?? null)) {
                throw ValidationException::withMessages([
                    'variants' => 'Each product variant must include base, today, or tomorrow price.',
                ]);
            }

            $resolvedSellingPrice = (float) (
                $variant['selling_price']
                ?? $variant['today_price']
                ?? $variant['tomorrow_price']
                ?? 0
            );

            $mrp = (float) ($variant['mrp'] ?? 0);
            $saveOffer = trim((string) ($variant['save_offer'] ?? ''));

            $effectivePrice = ! blank($variant['today_price'] ?? null)
                ? (float) $variant['today_price']
                : ($resolvedSellingPrice > 0 ? $resolvedSellingPrice : (! blank($variant['tomorrow_price'] ?? null) ? (float) $variant['tomorrow_price'] : 0));

            if ($mrp > 0 && $effectivePrice > 0 && $effectivePrice < $mrp) {
                if (blank($saveOffer) || is_numeric($saveOffer) || $saveOffer === '0.1') {
                    $pct = (($mrp - $effectivePrice) / $mrp) * 100;
                    $formattedPct = (fmod($pct, 1.0) == 0.0) ? number_format($pct, 0) : number_format($pct, 1);
                    $saveOffer = $formattedPct . '% OFF';
                }
            } elseif ($mrp > 0 && $effectivePrice >= $mrp && (is_numeric($saveOffer) || $saveOffer === '0.1')) {
                $saveOffer = '';
            }

            $normalized[] = [
                'quantity'      => (string) ($variant['quantity'] ?? ''),
                'unit'          => (string) ($variant['unit'] ?? 'Gram'),
                'piece'         => (string) ($variant['piece'] ?? ''),
                'mrp'           => $mrp,
                'selling_price' => $resolvedSellingPrice,
                'today_price'   => blank($variant['today_price'] ?? null) ? null : (float) $variant['today_price'],
                'tomorrow_price'=> blank($variant['tomorrow_price'] ?? null) ? null : (float) $variant['tomorrow_price'],
                'save_offer'    => $saveOffer,
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

    private function databaseErrorMessage(QueryException $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'Unknown column') || str_contains($message, "doesn't exist")) {
            return 'Database is outdated (missing variants/videos columns). Run: php artisan migrate --force — or open /dashboard/system-check.';
        }

        if (str_contains($message, 'Duplicate entry')) {
            return 'A product with this name already exists. Use a different name.';
        }

        return 'Database error while saving. Check /dashboard/system-check or server logs.';
    }
}

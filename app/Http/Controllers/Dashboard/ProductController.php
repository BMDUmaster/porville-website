<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

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
            'out_of_stock' => Product::where('stock', 0)->count(),
        ];

        $categories    = Category::parents()->get();
        $subcategories = Category::subcategories()->get();

        return view('dashboard.products.index', compact('products', 'stats', 'categories', 'subcategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:200',
            'category_id'    => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'description'    => 'nullable|string',
            'is_active'      => 'boolean',
            'images.*'       => 'nullable|image|max:2048',
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        // Handle variants
        $variants = [];
        if ($request->has('variants')) {
            foreach ($request->input('variants') as $variant) {
                if (!empty($variant['quantity'])) {
                    $variants[] = [
                        'quantity'      => $variant['quantity'] ?? '',
                        'unit'          => $variant['unit'] ?? 'Gram',
                        'piece'         => $variant['piece'] ?? '',
                        'mrp'           => $variant['mrp'] ?? '',
                        'selling_price' => $variant['selling_price'] ?? '',
                        'save_offer'    => $variant['save_offer'] ?? '',
                        'admin_amount'  => $variant['admin_amount'] ?? '',
                        'vendor_amount' => $variant['vendor_amount'] ?? '',
                    ];
                }
            }
        }
        $data['variants'] = $variants;

        // Use first variant price as product price if available
        $data['price'] = !empty($variants) ? (float)($variants[0]['selling_price'] ?? 0) : 0;
        $data['mrp']   = !empty($variants) ? (float)($variants[0]['mrp'] ?? 0) : 0;
        $data['stock'] = 0;

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('products', 'public');
            }
        }
        $data['images'] = $imagePaths;

        Product::create($data);
        return back()->with('success', 'Product added successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'mrp'         => 'nullable|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        $product->update($data);
        return back()->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }
}

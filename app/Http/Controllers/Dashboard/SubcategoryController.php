<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\WebpImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::subcategories()->with('parent');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('parent_id', $request->category);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $subcategories = $query->latest()->paginate(15);
        $parent_categories = Category::parents()->get();

        $stats = [
            'total'    => Category::subcategories()->count(),
            'active'   => Category::subcategories()->where('is_active', true)->count(),
            'inactive' => Category::subcategories()->where('is_active', false)->count(),
        ];

        return view('dashboard.subcategories.index', compact('subcategories', 'parent_categories', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'parent_id'   => ['required', Rule::exists('categories', 'id')->whereNull('parent_id')],
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data['slug']      = $this->generateUniqueSlug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = WebpImage::store($request->file('image'), 'subcategories');
        }

        Category::create($data);
        return back()->with('success', 'Sub-category created successfully.');
    }

    public function update(Request $request, Category $subcategory)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'parent_id'   => ['required', Rule::exists('categories', 'id')->whereNull('parent_id')],
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data['slug']      = $this->generateUniqueSlug($data['name'], $subcategory->id);
        $data['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $data['image'] = WebpImage::store($request->file('image'), 'subcategories');
        }

        $subcategory->update($data);
        return back()->with('success', 'Sub-category updated.');
    }

    public function destroy(Category $subcategory)
    {
        $subcategory->delete();
        return back()->with('success', 'Sub-category deleted.');
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'subcategory';
        $slug = $baseSlug;
        $counter = 2;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

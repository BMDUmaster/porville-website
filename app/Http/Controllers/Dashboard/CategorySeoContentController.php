<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategorySeoContent;
use Illuminate\Http\Request;

class CategorySeoContentController extends Controller
{
    public function index()
    {
        $categories = Category::parents()
            ->ordered()
            ->with(['children' => fn ($query) => $query->orderBy('name')])
            ->get();

        $contents = CategorySeoContent::query()->get()->keyBy('category_id');

        return view('dashboard.seo.category-content.index', compact('categories', 'contents'));
    }

    public function edit(Category $category)
    {
        $content = CategorySeoContent::firstOrNew(['category_id' => $category->id]);

        return view('dashboard.seo.category-content.edit', compact('category', 'content'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'heading'   => ['nullable', 'string', 'max:200'],
            'content'   => ['nullable', 'string', 'max:60000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        CategorySeoContent::updateOrCreate(
            ['category_id' => $category->id],
            [
                'heading'   => trim((string) ($data['heading'] ?? '')) ?: null,
                'content'   => CategorySeoContent::sanitize($data['content'] ?? null),
                'is_active' => $request->boolean('is_active'),
            ]
        );

        return redirect()->route('dashboard.seo.category-content')
            ->with('success', "Page content saved for {$category->name}.");
    }

    public function destroy(Category $category)
    {
        CategorySeoContent::where('category_id', $category->id)->delete();

        return redirect()->route('dashboard.seo.category-content')
            ->with('success', "Page content removed for {$category->name}.");
    }
}

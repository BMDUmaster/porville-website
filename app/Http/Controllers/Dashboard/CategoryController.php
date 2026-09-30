<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\WebpImage;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Category::parents()->with('children');

            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $categories = $query->ordered()->latest()->paginate(15);
            $nextSortOrder = (int) Category::parents()->max('sort_order') + 1;

            return view('dashboard.categories.index', compact('categories', 'nextSortOrder'));
        } catch (\Throwable $e) {
            report($e);

            $categories = new LengthAwarePaginator([], 0, 15, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);

            $nextSortOrder = 1;

            return view('dashboard.categories.index', compact('categories', 'nextSortOrder'))
                ->with('error', 'Could not load categories. ' . $this->friendlyExceptionMessage($e));
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name'        => 'required|string|max:100',
                'description' => 'nullable|string',
                'tag'         => 'nullable|string|max:40',
                'sort_order'  => $this->sortOrderRules(),
                'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp,avif|max:5120',
                'is_active'   => 'nullable|boolean',
            ], $this->sortOrderMessages());

            $data['slug'] = $this->generateUniqueSlug($data['name']);
            $data['is_active'] = $request->boolean('is_active', true);

            if ($request->hasFile('image')) {
                $data['image'] = WebpImage::store($request->file('image'), 'categories');
            }

            Category::create($data);

            return back()->with('success', 'Category created successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (QueryException $e) {
            report($e);

            return back()->withInput()->with('error', $this->databaseErrorMessage($e));
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Could not save category. ' . $e->getMessage());
        }
    }

    public function update(Request $request, Category $category)
    {
        try {
            $data = $request->validate([
                'name'        => 'required|string|max:100',
                'description' => 'nullable|string',
                'tag'         => 'nullable|string|max:40',
                'sort_order'  => $this->sortOrderRules($category->id),
                'image'       => 'nullable|image|mimes:jpg,jpeg,png,gif,webp,avif|max:5120',
                'is_active'   => 'nullable|boolean',
            ], $this->sortOrderMessages());

            $data['slug'] = $this->generateUniqueSlug($data['name'], $category->id);
            $data['is_active'] = $request->boolean('is_active');

            if ($request->hasFile('image')) {
                $data['image'] = WebpImage::store($request->file('image'), 'categories');
            }

            $category->update($data);

            return back()->with('success', 'Category updated successfully.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (QueryException $e) {
            report($e);

            return back()->withInput()->with('error', $this->databaseErrorMessage($e));
        } catch (\Throwable $e) {
            report($e);

            return back()->withInput()->with('error', 'Could not update category. ' . $e->getMessage());
        }
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    private function sortOrderRules(?int $ignoreId = null): array
    {
        return [
            'required',
            'integer',
            'min:1',
            Rule::unique('categories', 'sort_order')
                ->whereNull('parent_id')
                ->ignore($ignoreId),
        ];
    }

    private function sortOrderMessages(): array
    {
        return [
            'sort_order.required' => 'Please enter a sort order.',
            'sort_order.unique'   => 'This sort order is already used by another category. Choose a different number.',
            'sort_order.min'      => 'Sort order must be 1 or more.',
        ];
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $baseSlug = $baseSlug !== '' ? $baseSlug : 'category';
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

    private function friendlyExceptionMessage(\Throwable $e): string
    {
        if ($e instanceof QueryException) {
            return $this->databaseErrorMessage($e);
        }

        return $e->getMessage();
    }

    private function databaseErrorMessage(QueryException $e): string
    {
        $message = $e->getMessage();

        if (str_contains($message, 'Unknown column') || str_contains($message, "doesn't exist")) {
            return 'Database is outdated. Run php artisan migrate --force on the server, or open /dashboard/system-check.';
        }

        if (str_contains($message, 'Duplicate entry')) {
            return 'A category with this name already exists. Use a different name.';
        }

        return 'Database error while saving. Check /dashboard/system-check or server logs.';
    }
}

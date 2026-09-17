<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::query()
            ->withCount('faqs')
            ->with('faqs')
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        return view('dashboard.faqs.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
        ]);

        $slug = Str::slug($data['title']);
        $originalSlug = $slug;
        $suffix = 1;
        while (FaqCategory::query()->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . (++$suffix);
        }

        FaqCategory::create([
            'title' => $data['title'],
            'slug' => $slug,
            'sort_order' => (int) FaqCategory::query()->max('sort_order') + 1,
        ]);

        return back()->with('success', 'FAQ title added.');
    }

    public function updateCategory(Request $request, FaqCategory $category)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'title' => $data['title'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'FAQ title updated.');
    }

    public function destroyCategory(FaqCategory $category)
    {
        $category->delete();

        return back()->with('success', 'FAQ title and its questions removed.');
    }

    public function storeFaq(Request $request)
    {
        $data = $request->validate([
            'faq_category_id' => ['required', 'exists:faq_categories,id'],
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
        ]);

        Faq::create([
            'faq_category_id' => $data['faq_category_id'],
            'question' => $data['question'],
            'answer' => $data['answer'],
            'sort_order' => (int) Faq::query()->where('faq_category_id', $data['faq_category_id'])->max('sort_order') + 1,
        ]);

        return back()->with('success', 'FAQ added.');
    }

    public function updateFaq(Request $request, Faq $faq)
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $faq->update([
            'question' => $data['question'],
            'answer' => $data['answer'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'FAQ updated.');
    }

    public function destroyFaq(Faq $faq)
    {
        $faq->delete();

        return back()->with('success', 'FAQ removed.');
    }
}

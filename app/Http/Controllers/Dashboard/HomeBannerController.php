<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use App\Support\WebpImage;
use Illuminate\Http\Request;

class HomeBannerController extends Controller
{
    public function index(Request $request)
    {
        $query = HomeBanner::query()->orderBy('sort_order')->latest('id');

        if ($request->filled('search')) {
            $query->where(function ($bannerQuery) use ($request) {
                $bannerQuery
                    ->where('title_1', 'like', '%' . $request->search . '%')
                    ->orWhere('title_2', 'like', '%' . $request->search . '%')
                    ->orWhere('badge', 'like', '%' . $request->search . '%');
            });
        }

        $banners = $query->paginate(15);

        return view('dashboard.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(true));

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['button_text'] = $data['button_text'] ?: 'Shop Now';
        $data['image'] = WebpImage::store($request->file('image'), 'home-banners');

        HomeBanner::create($data);

        return back()->with('success', 'Home banner created successfully.');
    }

    public function update(Request $request, HomeBanner $banner)
    {
        $data = $request->validate($this->rules(false));

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);
        $data['button_text'] = $data['button_text'] ?: 'Shop Now';

        if ($request->hasFile('image')) {
            $data['image'] = WebpImage::store($request->file('image'), 'home-banners');
        }

        $banner->update($data);

        return back()->with('success', 'Home banner updated successfully.');
    }

    public function destroy(HomeBanner $banner)
    {
        $banner->delete();

        return back()->with('success', 'Home banner deleted.');
    }

    private function rules(bool $imageRequired): array
    {
        return [
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp,avif', 'max:5120'],
            'badge' => ['nullable', 'string', 'max:80'],
            'title_1' => ['required', 'string', 'max:120'],
            'title_2' => ['nullable', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'button_text' => ['nullable', 'string', 'max:40'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}

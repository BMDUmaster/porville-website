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

        $data['image'] = WebpImage::store($request->file('image'), 'home-banners');
        $data['title_1'] = 'Banner';
        $data['is_active'] = true;
        if ($request->hasFile('mobile_image')) {
            $data['mobile_image'] = WebpImage::store($request->file('mobile_image'), 'home-banners/mobile');
        }

        HomeBanner::create($data);

        return back()->with('success', 'Home banner created successfully.');
    }

    public function update(Request $request, HomeBanner $banner)
    {
        $data = $request->validate($this->rules(false));

        if ($request->hasFile('image')) {
            $data['image'] = WebpImage::store($request->file('image'), 'home-banners');
        }
        if ($request->hasFile('mobile_image')) {
            $data['mobile_image'] = WebpImage::store($request->file('mobile_image'), 'home-banners/mobile');
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
            'mobile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp,avif', 'max:5120'],
        ];
    }
}

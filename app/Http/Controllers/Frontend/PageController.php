<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function about()        { return view('frontend.pages.about-us'); }
    public function farms()        { return view('frontend.pages.our-farms'); }
    public function contact()      { return view('frontend.pages.contact-us'); }
    public function privacy()      { return view('frontend.pages.privacy-policy'); }
    public function terms()        { return view('frontend.pages.terms-of-service'); }
    public function shipping()     { return view('frontend.pages.shipping-policy'); }
    public function returns()      { return view('frontend.pages.return-refund-policy'); }
    public function cookies()      { return view('frontend.pages.cookie-policy'); }
    public function categories()   {
        $categories = \App\Models\Category::parents()
            ->where('is_active', true)
            ->with(['children' => fn($q) => $q->where('is_active', true)])
            ->withCount('products')
            ->get();
        return view('frontend.pages.categories', compact('categories'));
    }
}

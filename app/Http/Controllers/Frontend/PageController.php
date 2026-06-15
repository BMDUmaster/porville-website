<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()        { return view('frontend.pages.about-us'); }
    public function farms()        { return view('frontend.pages.our-farms'); }
    public function contact()      { return view('frontend.pages.contact-us'); }
    public function submitContact(Request $request)
    {
        $data = $request->validate([
            'department' => ['required', 'string', 'max:80'],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::create($data);

        return back()->with('success', 'Your request has been sent. Our team will contact you soon.');
    }
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

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['order.items.product', 'user', 'product']);
        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('rating')) $query->where('rating', $request->rating);

        $reviews = $query->latest()->paginate(20)->withQueryString();
        $counts = [
            'all' => Review::count(),
            'pending' => Review::where('status', 'pending')->count(),
            'approved' => Review::where('status', 'approved')->count(),
            'hidden' => Review::where('status', 'hidden')->count(),
        ];

        return view('dashboard.reviews.index', compact('reviews', 'counts'));
    }

    public function update(Request $request, Review $review)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(Review::STATUSES)],
            'display_on' => ['required', Rule::in(['home', 'product'])],
        ]);
        if ($data['display_on'] === 'product' && ! $review->product_id) {
            $review->product_id = $review->order?->items()->value('product_id');
        }
        $review->update($data);
        return back()->with('success', 'Review status updated.');
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}

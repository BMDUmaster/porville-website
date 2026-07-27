<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function create(Request $request, Order $order)
    {
        abort_unless($request->hasValidSignature(), 403, 'This review link is invalid or has expired.');
        abort_unless($order->status === 'delivered', 403, 'Reviews are available after delivery.');

        $order->load(['items.product', 'review']);
        return view('frontend.review-order', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        abort_unless($request->hasValidSignature(), 403, 'This review link is invalid or has expired.');
        abort_unless($order->status === 'delivered', 403, 'Reviews are available after delivery.');

        if ($order->review()->exists()) {
            return back()->with('success', 'Your review for this order has already been submitted.');
        }

        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1500'],
        ]);

        $productId = $order->items()->where('product_id', $data['product_id'])->value('product_id');
        abort_unless($productId, 422, 'Please select a product from this order.');

        $order->review()->create([
            'user_id' => $order->user_id,
            'product_id' => $productId,
            'rating' => $data['rating'],
            'comment' => trim((string) ($data['comment'] ?? '')) ?: null,
            'status' => 'pending',
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted for approval.');
    }
}

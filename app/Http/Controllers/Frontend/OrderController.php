<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /** GET /account/orders */
    public function index(Request $request)
    {
        $user  = Auth::guard('web_frontend')->user();
        $query = Order::with('items.product')->where('user_id', $user->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total'     => Order::where('user_id', $user->id)->count(),
            'delivered' => Order::where('user_id', $user->id)->where('status', 'delivered')->count(),
            'active'    => Order::where('user_id', $user->id)->whereIn('status', ['pending', 'confirmed', 'processing', 'shipped'])->count(),
            'spent'     => Order::where('user_id', $user->id)->sum('total'),
        ];

        return view('frontend.my-orders', compact('orders', 'stats'));
    }

    /** GET /account/orders/{id} */
    public function show($id)
    {
        $user  = Auth::guard('web_frontend')->user();
        $order = Order::with('items.product')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('frontend.order-detail', compact('order'));
    }

    /** GET /track-order */
    public function track(Request $request)
    {
        $order = null;
        if ($request->filled('order_number')) {
            $order = Order::with('items.product')
                ->where('order_number', $request->order_number)
                ->first();
        }
        return view('frontend.track-order', compact('order'));
    }
}

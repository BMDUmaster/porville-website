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
            'active'    => Order::where('user_id', $user->id)->whereIn('status', ['pending', 'confirmed', 'processing', 'out_for_delivery'])->count(),
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

    /** GET /account/orders/{id}/invoice */
    public function invoice(Request $request, $id)
    {
        $user  = Auth::guard('web_frontend')->user();
        $order = Order::with('items.product')
            ->where('user_id', $user->id)
            ->findOrFail($id);

        return view('frontend.invoice', [
            'order' => $order,
            'autoPrint' => $request->boolean('download'),
        ]);
    }

    /** GET /track-order */
    public function track(Request $request)
    {
        $order = null;

        if ($request->filled('order_number')) {
            $order = Order::with('items.product')
                ->where('order_number', $request->order_number)
                ->first();

            if ($order && ! $this->canViewTrackedOrder($order, $request)) {
                $order = null;
            }
        }

        return view('frontend.track-order', compact('order'));
    }

    private function canViewTrackedOrder(Order $order, Request $request): bool
    {
        $user = Auth::guard('web_frontend')->user();

        if ($user && $order->user_id === $user->id) {
            return true;
        }

        $providedPhone = preg_replace('/\D+/', '', (string) $request->input('phone'));
        $shippingPhone = preg_replace('/\D+/', '', (string) data_get($order->shipping_address, 'phone', ''));

        return $providedPhone !== '' && $shippingPhone !== '' && hash_equals($shippingPhone, $providedPhone);
    }
}

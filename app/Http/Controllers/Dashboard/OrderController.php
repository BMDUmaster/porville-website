<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'items.product');

        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
                  ->orWhere('id', $request->search);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(20);
        return view('dashboard.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product');
        return view('dashboard.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled']);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Order status updated.');
    }

    public function history(Request $request)
    {
        $query = Order::with('user')->whereIn('status', ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled']);

        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'today'     => Order::whereDate('created_at', today())->count(),
            'pending'   => Order::where('status', 'pending')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        $orders = $query->latest()->paginate(20);
        return view('dashboard.orders.history', compact('orders', 'stats'));
    }

    public function totalOrders()
    {
        $orders = Order::with('items.product.category')->get();

        $byCategory = [];
        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                $cat = $item->product->category->name ?? 'Unknown';
                if (!isset($byCategory[$cat])) {
                    $byCategory[$cat] = ['orders' => 0, 'quantity' => 0];
                }
                $byCategory[$cat]['orders']++;
                $byCategory[$cat]['quantity'] += $item->quantity;
            }
        }

        return view('dashboard.orders.total', compact('orders', 'byCategory'));
    }

    public function categoryOrders()
    {
        $categories = \App\Models\Category::withCount('products')
            ->with(['products' => fn($q) => $q->withCount('orderItems')])
            ->get();

        return view('dashboard.orders.category', compact('categories'));
    }

    public function report(Request $request)
    {
        $date = $request->get('date', today()->toDateString());

        $salesData = Order::with('items.product.category')
            ->whereDate('created_at', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        $byCategory = [];
        $totalSales = 0;
        $totalQty   = 0;

        foreach ($salesData as $order) {
            $totalSales += $order->total;
            foreach ($order->items as $item) {
                $cat = $item->product->category->name ?? 'Unknown';
                if (!isset($byCategory[$cat])) {
                    $byCategory[$cat] = ['amount' => 0, 'qty' => 0, 'subs' => []];
                }
                $byCategory[$cat]['amount'] += $item->subtotal;
                $byCategory[$cat]['qty']    += $item->quantity;
                $totalQty += $item->quantity;

                $sub = $item->product->name;
                if (!isset($byCategory[$cat]['subs'][$sub])) {
                    $byCategory[$cat]['subs'][$sub] = ['qty' => 0, 'amount' => 0];
                }
                $byCategory[$cat]['subs'][$sub]['qty']    += $item->quantity;
                $byCategory[$cat]['subs'][$sub]['amount'] += $item->subtotal;
            }
        }

        return view('dashboard.orders.report', compact('byCategory', 'totalSales', 'totalQty', 'date'));
    }
}

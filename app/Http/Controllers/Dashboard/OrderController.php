<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\DeliveryBoy;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user', 'items.product', 'deliveryBoy');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($searchQuery) use ($search) {
                $searchQuery->whereHas('user', fn ($userQuery) => $userQuery->where('name', 'like', '%' . $search . '%'))
                    ->orWhere('id', $search);
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $this->applyDateFilter($query, $request);

        $orders = $query->latest()->paginate(20);
        $deliveryBoysByOrder = $orders->getCollection()->mapWithKeys(function (Order $order) {
            return [$order->id => $this->availableDeliveryBoys($order)];
        });

        return view('dashboard.orders.index', compact('orders', 'deliveryBoysByOrder'));
    }

    public function show(Order $order)
    {
        $order->load('user', 'items.product', 'deliveryBoy', 'razorpayPayments');
        $deliveryBoys = $this->availableDeliveryBoys($order);

        return view('dashboard.orders.show', compact('order', 'deliveryBoys'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
            'delivery_boy_id' => [
                Rule::requiredIf($request->input('status') === 'out_for_delivery'),
                'nullable',
                'integer',
                Rule::exists('delivery_boys', 'id'),
            ],
        ]);

        if ($validated['status'] === 'out_for_delivery') {
            $deliveryBoy = DeliveryBoy::query()
                ->where('id', $validated['delivery_boy_id'])
                ->where('status', 'active')
                ->first();

            if (!$deliveryBoy) {
                return back()->withErrors([
                    'delivery_boy_id' => 'Selected delivery boy is not available.',
                ]);
            }

            $isBusy = Order::query()
                ->where('delivery_boy_id', $deliveryBoy->id)
                ->where('status', 'out_for_delivery')
                ->where('id', '!=', $order->id)
                ->exists();

            if ($isBusy) {
                return back()->withErrors([
                    'delivery_boy_id' => 'This delivery boy is already assigned to another active delivery.',
                ]);
            }

            $validated['delivery_boy_id'] = $deliveryBoy->id;
            $deliveryBoy->update(['last_assigned' => now()]);
        } else {
            unset($validated['delivery_boy_id']);
        }

        $oldStatus = $order->status;
        $order->update($validated);
        $order->refresh();

        \App\Services\OrderStatusNotificationService::notifyStatusChange($order, $oldStatus);

        return back()->with('success', 'Order status updated and notification sent to customer.');
    }

    public function history(Request $request)
    {
        $query = Order::with('user', 'deliveryBoy')->whereIn('status', Order::STATUSES);

        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('order_id')) {
            $query->where('id', preg_replace('/\D+/', '', (string) $request->order_id));
        }
        $this->applyDateFilter($query, $request);

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
        $orders = Order::with('user', 'items.product.category')->get();

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

    private function availableDeliveryBoys(?Order $order = null)
    {
        $currentAssignedId = $order?->delivery_boy_id;
        $busyIds = Order::query()
            ->whereNotNull('delivery_boy_id')
            ->where('status', 'out_for_delivery')
            ->when($currentAssignedId, fn ($query) => $query->where('delivery_boy_id', '!=', $currentAssignedId))
            ->pluck('delivery_boy_id');

        return DeliveryBoy::query()
            ->where(function ($query) use ($busyIds, $currentAssignedId) {
                $query->where(function ($activeQuery) use ($busyIds) {
                    $activeQuery->where('status', 'active');

                    if ($busyIds->isNotEmpty()) {
                        $activeQuery->whereNotIn('id', $busyIds->all());
                    }
                });

                if ($currentAssignedId) {
                    $query->orWhere('id', $currentAssignedId);
                }
            })
            ->orderBy('partner_name')
            ->get();
    }

    private function applyDateFilter($query, Request $request): void
    {
        $dateFilter = $request->input('date_filter');

        if ($dateFilter === 'today') {
            $query->whereDate('created_at', today());
            return;
        }

        if ($dateFilter === 'last_7_days') {
            $query->whereBetween('created_at', [now()->subDays(6)->startOfDay(), now()->endOfDay()]);
            return;
        }

        if ($dateFilter === 'last_30_days') {
            $query->whereBetween('created_at', [now()->subDays(29)->startOfDay(), now()->endOfDay()]);
            return;
        }

        if ($dateFilter === 'custom') {
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
        }
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();
        $yesterday = today()->subDay();

        $todaySales = Order::whereDate('created_at', $today)
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $yesterdaySales = Order::whereDate('created_at', $yesterday)
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        $stats = [
            'today_sales'    => $todaySales,
            'sales_change'   => $this->calculateSalesChange($todaySales, $yesterdaySales),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'active_users'   => User::where('role', 'customer')->where('status', 'active')->count(),
            'low_stock'      => Product::where('stock', '<', 10)->count(),
        ];

        $recent_orders = Order::with('user')
            ->latest()
            ->take(4)
            ->get();

        $orders_by_status = [
            'pending'    => Order::whereIn('status', ['pending', 'confirmed'])->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'out_for_delivery' => Order::where('status', 'out_for_delivery')->count(),
            'delivered'  => Order::where('status', 'delivered')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        $sales_trends = [
            '30d' => $this->buildDailySalesTrend(29),
            '90d' => $this->buildDailySalesTrend(89),
            '1y'  => $this->buildMonthlySalesTrend(),
        ];

        return view('dashboard.index', compact('stats', 'recent_orders', 'orders_by_status', 'sales_trends'));
    }

    private function calculateSalesChange(float $todaySales, float $yesterdaySales): float
    {
        if ($yesterdaySales <= 0) {
            return $todaySales > 0 ? 100.0 : 0.0;
        }

        return round((($todaySales - $yesterdaySales) / $yesterdaySales) * 100, 1);
    }

    private function buildDailySalesTrend(int $days): array
    {
        $startDate = now()->subDays($days)->startOfDay();
        $endDate = now()->endOfDay();

        $orders = Order::query()
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get(['created_at', 'total']);

        $totals = $orders
            ->groupBy(fn (Order $order) => $order->created_at->format('Y-m-d'))
            ->map(fn ($group) => round((float) $group->sum('total'), 2));

        $labels = [];
        $data = [];
        $cursor = $startDate->copy();

        while ($cursor->lte($endDate)) {
            $labels[] = $cursor->format('M j');
            $data[] = $totals->get($cursor->format('Y-m-d'), 0);
            $cursor->addDay();
        }

        return [
            'labels' => $labels,
            'data'   => $data,
        ];
    }

    private function buildMonthlySalesTrend(): array
    {
        $startMonth = now()->startOfMonth()->subMonths(11);
        $endMonth = now()->endOfMonth();

        $orders = Order::query()
            ->where('status', '!=', 'cancelled')
            ->whereBetween('created_at', [$startMonth, $endMonth])
            ->get(['created_at', 'total']);

        $totals = $orders
            ->groupBy(fn (Order $order) => $order->created_at->format('Y-m'))
            ->map(fn ($group) => round((float) $group->sum('total'), 2));

        $labels = [];
        $data = [];
        $cursor = $startMonth->copy();

        while ($cursor->lte($endMonth)) {
            $labels[] = $cursor->format('M');
            $data[] = $totals->get($cursor->format('Y-m'), 0);
            $cursor->addMonth();
        }

        return [
            'labels' => $labels,
            'data'   => $data,
        ];
    }
}

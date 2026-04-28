@extends('layouts.dashboard')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@php
    $salesChangePositive = $stats['sales_change'] >= 0;
@endphp

@section('content')
<div class="p-5 sm:p-6 lg:p-8 space-y-8">

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <div class="stat-card rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Today's Sales</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-mayview-blue">
                        &#8377;{{ number_format($stats['today_sales'], 2) }}
                    </p>
                    <p class="mt-3 text-xs font-medium {{ $salesChangePositive ? 'text-emerald-600' : 'text-rose-600' }}">
                        <i class="fa-solid {{ $salesChangePositive ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} mr-1"></i>
                        {{ $salesChangePositive ? '+' : '' }}{{ number_format($stats['sales_change'], 1) }}% vs yesterday
                    </p>
                </div>
                <div class="icon-box rounded-2xl bg-blue-100 px-4 py-3 text-mayview-blue">
                    <i class="fa-solid fa-indian-rupee-sign text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Pending Orders</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-admin-orange">{{ $stats['pending_orders'] }}</p>
                    <p class="mt-3 text-xs text-slate-500">Orders waiting for the next update</p>
                </div>
                <div class="icon-box rounded-2xl bg-orange-100 px-4 py-3 text-admin-orange">
                    <i class="fa-solid fa-rotate-left text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Active Customers</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-emerald-600">{{ $stats['active_users'] }}</p>
                    <p class="mt-3 text-xs text-slate-500">Customer accounts currently active</p>
                </div>
                <div class="icon-box rounded-2xl bg-emerald-100 px-4 py-3 text-emerald-600">
                    <i class="fa-solid fa-user-group text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-slate-500">Low Stock Alert</p>
                    <p class="mt-2 text-3xl font-bold tracking-tight text-rose-600">{{ $stats['low_stock'] }}</p>
                    <p class="mt-3 text-xs text-slate-500">Restock recommended for these items</p>
                </div>
                <div class="icon-box rounded-2xl bg-rose-100 px-4 py-3 text-rose-600">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(340px,0.85fr)]">
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <h2 id="salesTrendTitle" class="text-lg font-semibold text-slate-900">Sales Trend (Last 30 Days)</h2>

                <label for="salesRangeSelect" class="relative inline-flex items-center">
                    <select id="salesRangeSelect"
                            class="appearance-none rounded-xl border border-blue-300 bg-white py-2 pl-4 pr-10 text-sm font-medium text-slate-700 shadow-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        <option value="30d">Last 30 days</option>
                        <option value="90d">Last 90 days</option>
                        <option value="1y">This year</option>
                    </select>
                    <i class="fa-solid fa-chevron-down pointer-events-none absolute right-4 text-xs text-slate-500"></i>
                </label>
            </div>

            <div class="h-[300px]">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Orders by Status</h2>
            <div class="mt-6 h-[300px]">
                <canvas id="ordersChart"></canvas>
            </div>
        </section>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1.1fr)_minmax(360px,0.9fr)]">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h2 class="text-lg font-semibold text-slate-900">Recent Orders</h2>
                <a href="{{ route('dashboard.orders') }}" class="text-sm font-medium text-mayview-blue transition hover:text-blue-700">View All</a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse($recent_orders as $order)
                    <div class="flex items-center justify-between gap-4 px-5 py-4">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-900">#ORD-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</p>
                            <p class="mt-1 truncate text-xs text-slate-500">
                                {{ $order->user->name ?? 'Guest' }}
                                @if(data_get($order->shipping_address, 'city'))
                                    - {{ data_get($order->shipping_address, 'city') }}
                                @endif
                            </p>
                        </div>

                        <div class="text-right">
                            <p class="text-sm font-bold text-emerald-600">&#8377;{{ number_format($order->total, 2) }}</p>
                            <span class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-sm text-slate-400">No recent orders available.</div>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="text-lg font-semibold text-slate-900">Quick Actions</h2>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-2">
                <a href="{{ route('dashboard.products') }}"
                   class="group flex min-h-[92px] flex-col items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-5 text-center transition hover:border-blue-300 hover:bg-blue-50">
                    <i class="fa-solid fa-plus text-2xl text-mayview-blue transition group-hover:scale-110"></i>
                    <span class="mt-3 text-sm font-medium text-slate-700">Add Product</span>
                </a>

                <a href="{{ route('dashboard.orders') }}"
                   class="group flex min-h-[92px] flex-col items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-5 text-center transition hover:border-orange-300 hover:bg-orange-50">
                    <i class="fa-solid fa-truck-fast text-2xl text-admin-orange transition group-hover:scale-110"></i>
                    <span class="mt-3 text-sm font-medium text-slate-700">Dispatch Orders</span>
                </a>

                <a href="{{ route('dashboard.coupons') }}"
                   class="group flex min-h-[92px] flex-col items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-5 text-center transition hover:border-emerald-300 hover:bg-emerald-50">
                    <i class="fa-solid fa-tags text-2xl text-emerald-600 transition group-hover:scale-110"></i>
                    <span class="mt-3 text-sm font-medium text-slate-700">New Offer</span>
                </a>

                <a href="{{ route('dashboard.orders.report') }}"
                   class="group flex min-h-[92px] flex-col items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-5 text-center transition hover:border-violet-300 hover:bg-violet-50">
                    <i class="fa-solid fa-chart-column text-2xl text-violet-600 transition group-hover:scale-110"></i>
                    <span class="mt-3 text-sm font-medium text-slate-700">Sales Reports</span>
                </a>

                <a href="{{ route('dashboard.notifications') }}"
                   class="group flex min-h-[92px] flex-col items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-5 text-center transition hover:border-amber-300 hover:bg-amber-50 sm:col-span-2">
                    <i class="fa-regular fa-bell text-2xl text-amber-500 transition group-hover:scale-110"></i>
                    <span class="mt-3 text-sm font-medium text-slate-700">Notifications</span>
                </a>
            </div>
        </section>
    </div>

    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-col gap-4 border-b border-slate-200 px-5 py-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Today's Product Order Summary</h2>
                <p class="mt-1 text-sm text-slate-500">Aaj kis product ka total kitna order aaya hai, yahan ek saath dekh sakte ho.</p>
            </div>
        </div>

        <div class="lg:hidden divide-y divide-slate-100">
            @forelse($today_product_orders as $row)
                @php
                    $unitLabel = $row->product_unit ?: ($row->order_item_unit ?: 'unit');
                @endphp
                <div class="space-y-3 px-5 py-4">
                    <div class="grid grid-cols-3 gap-3 text-sm">
                        <div class="rounded-lg bg-slate-50 px-3 py-2">
                            <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">ID</p>
                            <p class="mt-1 font-semibold text-slate-800">{{ $row->product_id }}</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 px-3 py-2">
                            <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Product</p>
                            <p class="mt-1 font-semibold text-slate-800">{{ $row->product_name ?: 'Product #' . $row->product_id }}</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 px-3 py-2">
                            <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-slate-400">Total Qty</p>
                            <p class="mt-1 font-semibold text-slate-800">{{ rtrim(rtrim(number_format($row->total_quantity, 2), '0'), '.') }} {{ $unitLabel }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-5 py-10 text-center text-sm text-slate-400">Aaj abhi tak kisi product ka confirmed order summary available nahi hai.</div>
            @endforelse
        </div>

        <div class="hidden overflow-x-auto lg:block">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-[0.16em] text-slate-500">ID</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Product</th>
                        <th class="px-5 py-3 text-left text-xs font-bold uppercase tracking-[0.16em] text-slate-500">Total Quantity</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($today_product_orders as $row)
                        @php
                            $unitLabel = $row->product_unit ?: ($row->order_item_unit ?: 'unit');
                        @endphp
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-5 py-4 text-sm font-bold text-slate-400">{{ $row->product_id }}</td>
                            <td class="px-5 py-4">
                                <p class="text-sm font-bold text-slate-900">{{ $row->product_name ?: 'Product #' . $row->product_id }}</p>
                            </td>
                            <td class="px-5 py-4 text-sm font-semibold text-slate-700">{{ rtrim(rtrim(number_format($row->total_quantity, 2), '0'), '.') }} {{ $unitLabel }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-10 text-center text-sm text-slate-400">Aaj abhi tak kisi product ka confirmed order summary available nahi hai.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
const salesTrendSets = @json($sales_trends);
const salesRangeLabels = {
    '30d': 'Last 30 Days',
    '90d': 'Last 90 Days',
    '1y': 'This Year'
};

const salesCanvas = document.getElementById('salesTrendChart');
const salesContext = salesCanvas.getContext('2d');
const salesGradient = salesContext.createLinearGradient(0, 0, 0, 300);
salesGradient.addColorStop(0, 'rgba(37, 99, 235, 0.24)');
salesGradient.addColorStop(1, 'rgba(37, 99, 235, 0.02)');

const formatINR = (value) => new Intl.NumberFormat('en-IN', {
    style: 'currency',
    currency: 'INR',
    maximumFractionDigits: 0
}).format(value);

const salesTrendChart = new Chart(salesContext, {
    type: 'line',
    data: {
        labels: salesTrendSets['30d'].labels,
        datasets: [{
            data: salesTrendSets['30d'].data,
            borderColor: '#1d4ed8',
            backgroundColor: salesGradient,
            fill: true,
            pointRadius: 3,
            pointHoverRadius: 5,
            pointBorderWidth: 2,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#1d4ed8',
            tension: 0.35
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false
        },
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: (context) => formatINR(context.parsed.y || 0)
                }
            }
        },
        scales: {
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#64748b',
                    maxRotation: 0,
                    autoSkip: true,
                    maxTicksLimit: 8
                }
            },
            y: {
                beginAtZero: true,
                border: {
                    display: false
                },
                ticks: {
                    color: '#64748b',
                    callback: (value) => formatINR(value)
                },
                grid: {
                    color: 'rgba(148, 163, 184, 0.18)'
                }
            }
        }
    }
});

document.getElementById('salesRangeSelect').addEventListener('change', function (event) {
    const rangeKey = event.target.value;
    const trend = salesTrendSets[rangeKey];

    if (!trend) {
        return;
    }

    salesTrendChart.data.labels = trend.labels;
    salesTrendChart.data.datasets[0].data = trend.data;
    salesTrendChart.update();

    document.getElementById('salesTrendTitle').textContent = `Sales Trend (${salesRangeLabels[rangeKey]})`;
});

const ordersContext = document.getElementById('ordersChart').getContext('2d');
new Chart(ordersContext, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'Processing', 'Out for Delivery', 'Delivered', 'Cancelled'],
        datasets: [{
            data: [
                {{ $orders_by_status['pending'] }},
                {{ $orders_by_status['processing'] }},
                {{ $orders_by_status['out_for_delivery'] }},
                {{ $orders_by_status['delivered'] }},
                {{ $orders_by_status['cancelled'] }}
            ],
            backgroundColor: ['#f97316', '#eab308', '#3b82f6', '#10b981', '#ef4444'],
            borderColor: '#ffffff',
            borderWidth: 2,
            hoverOffset: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    usePointStyle: false,
                    boxWidth: 28,
                    boxHeight: 8,
                    padding: 18,
                    color: '#64748b',
                    font: {
                        size: 11,
                        family: 'Poppins'
                    }
                }
            }
        }
    }
});
</script>
@endsection

@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-4 max-w-full">
    <!-- Stats Row 1: Sales & Orders -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3">
        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-1">
                <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Today's Sales</p>
                <span class="material-symbols-outlined text-[14px] text-green-500">payments</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 truncate">₹{{ number_format($stats['today_sales'], 2) }}</h3>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-1">
                <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Total Revenue</p>
                <span class="material-symbols-outlined text-[14px] text-indigo-500">account_balance_wallet</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 truncate">₹{{ number_format($stats['total_revenue'], 2) }}</h3>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-1">
                <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Orders Today</p>
                <span class="material-symbols-outlined text-[14px] text-blue-500">shopping_cart</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900">{{ number_format($stats['orders_today']) }}</h3>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-1">
                <p class="text-[10px] font-semibold text-amber-600 uppercase tracking-wider">Pending Orders</p>
                <span class="material-symbols-outlined text-[14px] text-amber-500">hourglass_empty</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900">{{ number_format($stats['pending_orders']) }}</h3>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-1">
                <p class="text-[10px] font-semibold text-emerald-600 uppercase tracking-wider">Completed Orders</p>
                <span class="material-symbols-outlined text-[14px] text-emerald-500">check_circle</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900">{{ number_format($stats['completed_orders']) }}</h3>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-center mb-1">
                <p class="text-[10px] font-semibold text-red-600 uppercase tracking-wider">Cancelled Orders</p>
                <span class="material-symbols-outlined text-[14px] text-red-500">cancel</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900">{{ number_format($stats['cancelled_orders']) }}</h3>
        </div>
    </div>

    <!-- Stats Row 2: Inventory & Customers -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Total Products</p>
                <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ number_format($stats['total_products']) }}</h3>
            </div>
            <div class="w-8 h-8 rounded bg-gray-50 flex items-center justify-center text-gray-500 border border-gray-100">
                <span class="material-symbols-outlined text-[16px]">inventory_2</span>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-[10px] font-semibold text-orange-600 uppercase tracking-wider">Low Stock Products</p>
                <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ number_format($stats['low_stock']) }}</h3>
            </div>
            <div class="w-8 h-8 rounded bg-orange-50 flex items-center justify-center text-orange-500 border border-orange-100">
                <span class="material-symbols-outlined text-[16px]">warning</span>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-[10px] font-semibold text-red-600 uppercase tracking-wider">Out of Stock</p>
                <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ number_format($stats['out_of_stock']) }}</h3>
            </div>
            <div class="w-8 h-8 rounded bg-red-50 flex items-center justify-center text-red-500 border border-red-100">
                <span class="material-symbols-outlined text-[16px]">error</span>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-3 shadow-sm flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Total Customers</p>
                <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ number_format($stats['total_customers']) }}</h3>
            </div>
            <div class="w-8 h-8 rounded bg-blue-50 flex items-center justify-center text-blue-500 border border-blue-100">
                <span class="material-symbols-outlined text-[16px]">group</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <!-- Monthly Sales Graph -->
        <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-xs font-semibold text-gray-800 uppercase tracking-wide">Monthly Sales Graph (30 Days)</h3>
            </div>
            <div id="salesChart" class="w-full h-64"></div>
        </div>

        <!-- Order Status Distribution -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-xs font-semibold text-gray-800 uppercase tracking-wide">Order Status</h3>
            </div>
            <div id="statusChart" class="w-full h-64 flex items-center justify-center"></div>
        </div>
    </div>

    <!-- Bottom Section: Top Products & Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3">
        <!-- Top Selling Products -->
        <div class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm flex flex-col">
            <h3 class="text-xs font-semibold text-gray-800 uppercase tracking-wide mb-2">Top Selling Products</h3>
            <div id="topProductsChart" class="w-full h-56 flex-grow"></div>
        </div>

        <!-- Recent Orders Table -->
        <div class="lg:col-span-1 bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-xs font-semibold text-gray-800 uppercase tracking-wide">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-[10px] font-medium text-indigo-600 hover:text-indigo-700">View All</a>
            </div>
            <div class="overflow-y-auto flex-grow h-56">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-gray-100 text-[10px] text-gray-400 uppercase tracking-wider sticky top-0 z-10">
                            <th class="px-4 py-2 font-medium bg-white">Order</th>
                            <th class="px-4 py-2 font-medium bg-white">Customer</th>
                            <th class="px-4 py-2 font-medium text-right bg-white">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-[11px]">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="font-semibold text-indigo-600 hover:underline">#{{ $order->order_number }}</a>
                                <div class="text-[9px] text-gray-400 mt-0.5">{{ $order->created_at->format('M d, y') }}</div>
                            </td>
                            <td class="px-4 py-2 text-gray-600 truncate max-w-[100px]">{{ $order->user->name ?? 'Guest' }}</td>
                            <td class="px-4 py-2 font-medium text-gray-800 text-right">₹{{ number_format($order->grand_total, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-center text-gray-400 text-[11px]">No recent orders.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Customers Table -->
        <div class="lg:col-span-1 bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-4 py-2 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-xs font-semibold text-gray-800 uppercase tracking-wide">Recent Customers</h3>
                <a href="{{ route('admin.customers.index') }}" class="text-[10px] font-medium text-indigo-600 hover:text-indigo-700">View All</a>
            </div>
            <div class="overflow-y-auto flex-grow h-56">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white border-b border-gray-100 text-[10px] text-gray-400 uppercase tracking-wider sticky top-0 z-10">
                            <th class="px-4 py-2 font-medium bg-white">Customer</th>
                            <th class="px-4 py-2 font-medium bg-white">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 text-[11px]">
                        @forelse($recentCustomers as $customer)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded flex-shrink-0 bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-[9px] border border-indigo-100">
                                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                                    </div>
                                    <div class="flex flex-col truncate max-w-[120px]">
                                        <span class="font-medium text-gray-800 truncate">{{ $customer->name }}</span>
                                        <span class="text-[9px] text-gray-400 truncate">{{ $customer->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-2 text-gray-500 text-[10px]">{{ $customer->created_at->format('M d, y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="px-4 py-4 text-center text-gray-400 text-[11px]">No recent customers.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartsData = @json($charts);
        const fontFamily = 'Inter, ui-sans-serif, system-ui, -apple-system, sans-serif';

        // 1. Sales Trend Chart
        if (document.querySelector("#salesChart")) {
            const salesOptions = {
                series: [{
                    name: 'Revenue (₹)',
                    data: chartsData.sales.data
                }],
                chart: {
                    type: 'area',
                    height: 250,
                    fontFamily: fontFamily,
                    toolbar: { show: false },
                    sparkline: { enabled: false }
                },
                colors: ['#4f46e5'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 2 },
                xaxis: {
                    categories: chartsData.sales.labels,
                    axisBorder: { show: false },
                    axisTicks: { show: false },
                    labels: { style: { fontSize: '9px', colors: '#9ca3af' } },
                    tooltip: { enabled: false }
                },
                yaxis: {
                    labels: { 
                        style: { fontSize: '9px', colors: '#9ca3af' },
                        formatter: (value) => '₹' + (value >= 1000 ? (value/1000).toFixed(1) + 'k' : value)
                    }
                },
                grid: {
                    borderColor: '#f3f4f6',
                    strokeDashArray: 3,
                    padding: { top: 0, right: 0, bottom: 0, left: 10 }
                },
                tooltip: {
                    theme: 'light',
                    style: { fontSize: '11px' }
                }
            };
            new ApexCharts(document.querySelector("#salesChart"), salesOptions).render();
        }

        // 2. Order Status Chart
        if (document.querySelector("#statusChart")) {
            const statusOptions = {
                series: chartsData.status.data,
                chart: {
                    type: 'donut',
                    height: 240,
                    fontFamily: fontFamily,
                },
                labels: chartsData.status.labels,
                colors: ['#f59e0b', '#3b82f6', '#6366f1', '#8b5cf6', '#10b981', '#64748b', '#ef4444'],
                plotOptions: {
                    pie: {
                        donut: { 
                            size: '75%',
                            labels: {
                                show: true,
                                name: { fontSize: '10px', color: '#6b7280' },
                                value: { fontSize: '16px', fontWeight: 600, color: '#111827' }
                            }
                        }
                    }
                },
                dataLabels: { enabled: false },
                legend: { 
                    position: 'bottom', 
                    fontSize: '10px',
                    markers: { width: 6, height: 6, radius: 2 }
                },
                stroke: { width: 0 },
                tooltip: {
                    theme: 'light',
                    style: { fontSize: '11px' }
                }
            };
            new ApexCharts(document.querySelector("#statusChart"), statusOptions).render();
        }

        // 3. Top Products Chart
        if (document.querySelector("#topProductsChart")) {
            const topProductsOptions = {
                series: [{
                    name: 'Units Sold',
                    data: chartsData.top_products.data
                }],
                chart: {
                    type: 'bar',
                    height: 220,
                    fontFamily: fontFamily,
                    toolbar: { show: false }
                },
                colors: ['#0ea5e9'],
                plotOptions: {
                    bar: {
                        borderRadius: 3,
                        horizontal: true,
                        barHeight: '60%'
                    }
                },
                dataLabels: { 
                    enabled: true,
                    textAnchor: 'start',
                    style: { colors: ['#1f2937'], fontSize: '9px', fontWeight: 500 },
                    offsetX: 5,
                    dropShadow: { enabled: false }
                },
                xaxis: {
                    categories: chartsData.top_products.labels,
                    labels: { style: { fontSize: '9px', colors: '#9ca3af' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: { 
                        style: { fontSize: '9px', colors: '#6b7280' },
                        maxWidth: 120
                    }
                },
                grid: {
                    borderColor: '#f3f4f6',
                    strokeDashArray: 3,
                    xaxis: { lines: { show: true } },
                    yaxis: { lines: { show: false } },
                    padding: { top: 0, right: 15, bottom: 0, left: 0 }
                },
                tooltip: {
                    theme: 'light',
                    style: { fontSize: '11px' }
                }
            };
            new ApexCharts(document.querySelector("#topProductsChart"), topProductsOptions).render();
        }
    });
</script>
@endsection

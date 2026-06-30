@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Grid: Sales & Orders -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Today's Sales</p>
        <h3 class="text-xl font-bold text-gray-800">₹{{ number_format($stats['today_sales'], 2) }}</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Total Revenue</p>
        <h3 class="text-xl font-bold text-gray-800">₹{{ number_format($stats['total_revenue'], 2) }}</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Orders Today</p>
        <h3 class="text-xl font-bold text-gray-800">{{ number_format($stats['orders_today']) }}</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-amber-500 mb-1 uppercase tracking-wide">Pending</p>
        <h3 class="text-xl font-bold text-amber-600">{{ number_format($stats['pending_orders']) }}</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-emerald-500 mb-1 uppercase tracking-wide">Completed</p>
        <h3 class="text-xl font-bold text-emerald-600">{{ number_format($stats['completed_orders']) }}</h3>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <p class="text-xs font-medium text-red-500 mb-1 uppercase tracking-wide">Cancelled</p>
        <h3 class="text-xl font-bold text-red-600">{{ number_format($stats['cancelled_orders']) }}</h3>
    </div>
</div>

<!-- Stats Grid: Inventory & Users -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Total Products</p>
            <h3 class="text-xl font-bold text-gray-800">{{ number_format($stats['total_products']) }}</h3>
        </div>
        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600"><span class="material-symbols-outlined text-[20px]">inventory_2</span></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-orange-500 mb-1 uppercase tracking-wide">Low Stock</p>
            <h3 class="text-xl font-bold text-orange-600">{{ number_format($stats['low_stock']) }}</h3>
        </div>
        <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-600"><span class="material-symbols-outlined text-[20px]">warning</span></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-red-500 mb-1 uppercase tracking-wide">Out of Stock</p>
            <h3 class="text-xl font-bold text-red-600">{{ number_format($stats['out_of_stock']) }}</h3>
        </div>
        <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600"><span class="material-symbols-outlined text-[20px]">error</span></div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-500 mb-1 uppercase tracking-wide">Total Customers</p>
            <h3 class="text-xl font-bold text-gray-800">{{ number_format($stats['total_customers']) }}</h3>
        </div>
        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600"><span class="material-symbols-outlined text-[20px]">group</span></div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Sales Trend Chart -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 text-lg mb-4">Revenue Trend (Last 30 Days)</h3>
        <div id="salesChart" class="w-full h-80"></div>
    </div>

    <!-- Order Status Pie Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-semibold text-gray-800 text-lg mb-4">Order Status Distribution</h3>
        <div id="statusChart" class="w-full h-80 flex items-center justify-center"></div>
    </div>
</div>

<!-- Top Products Chart -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <h3 class="font-semibold text-gray-800 text-lg mb-4">Top Performing Products</h3>
    <div id="topProductsChart" class="w-full h-80"></div>
</div>

<!-- Data Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <!-- Recent Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="font-semibold text-gray-800 text-sm">Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">View All</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-[11px] text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-3 font-medium">Order</th>
                        <th class="px-5 py-3 font-medium">Customer</th>
                        <th class="px-5 py-3 font-medium">Total</th>
                        <th class="px-5 py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="font-semibold text-indigo-600 hover:underline">#{{ $order->order_number }}</a>
                            <div class="text-[11px] text-gray-400 mt-0.5">{{ $order->created_at->format('M d, Y') }}</div>
                        </td>
                        <td class="px-5 py-3 text-gray-600">{{ $order->user->name ?? 'Guest' }}</td>
                        <td class="px-5 py-3 font-medium text-gray-800">₹{{ number_format($order->grand_total, 2) }}</td>
                        <td class="px-5 py-3">
                            @php
                                $statusColors = [
                                    'pending'    => 'bg-amber-100 text-amber-800',
                                    'confirmed'  => 'bg-blue-100 text-blue-800',
                                    'processing' => 'bg-indigo-100 text-indigo-800',
                                    'shipped'    => 'bg-purple-100 text-purple-800',
                                    'delivered'  => 'bg-emerald-100 text-emerald-800',
                                    'cancelled'  => 'bg-red-100 text-red-800',
                                ];
                                $color = $statusColors[$order->order_status] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium {{ $color }}">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-6 text-center text-gray-500 text-sm">No orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent Customers Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="font-semibold text-gray-800 text-sm">Recent Customers</h2>
            <a href="{{ route('admin.customers.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">View All</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-[11px] text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-3 font-medium">Customer</th>
                        <th class="px-5 py-3 font-medium">Email</th>
                        <th class="px-5 py-3 font-medium">Joined</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($recentCustomers as $customer)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                    {{ substr($customer->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-800">{{ $customer->name }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-[13px]">{{ $customer->email }}</td>
                        <td class="px-5 py-3 text-gray-500 text-[13px]">{{ $customer->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-5 py-6 text-center text-gray-500 text-sm">No customers found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const chartsData = @json($charts);

        // 1. Sales Trend Chart
        const salesOptions = {
            series: [{
                name: 'Revenue (₹)',
                data: chartsData.sales.data
            }],
            chart: {
                type: 'area',
                height: 320,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false }
            },
            colors: ['#4f46e5'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [50, 100]
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: {
                categories: chartsData.sales.labels,
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: { formatter: (value) => '₹' + value }
            }
        };
        new ApexCharts(document.querySelector("#salesChart"), salesOptions).render();

        // 2. Order Status Chart
        const statusOptions = {
            series: chartsData.status.data,
            chart: {
                type: 'donut',
                height: 320,
                fontFamily: 'Inter, sans-serif',
            },
            labels: chartsData.status.labels,
            colors: ['#f59e0b', '#6366f1', '#a855f7', '#10b981', '#ef4444'],
            plotOptions: {
                pie: {
                    donut: { size: '70%' }
                }
            },
            dataLabels: { enabled: false },
            legend: { position: 'bottom' }
        };
        new ApexCharts(document.querySelector("#statusChart"), statusOptions).render();

        // 3. Top Products Chart
        const topProductsOptions = {
            series: [{
                name: 'Units Sold',
                data: chartsData.top_products.data
            }],
            chart: {
                type: 'bar',
                height: 320,
                fontFamily: 'Inter, sans-serif',
                toolbar: { show: false }
            },
            colors: ['#0ea5e9'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: chartsData.top_products.labels,
            }
        };
        new ApexCharts(document.querySelector("#topProductsChart"), topProductsOptions).render();
    });
</script>
@endsection

@extends('admin.layouts.app')

@section('title', 'System Reports')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
        <p class="text-sm text-gray-500">Generate, view, and export analytical data for your business.</p>
    </div>
    
    <!-- Report Filters -->
    <form action="{{ route('admin.reports.index', ['tab' => $tab]) }}" method="GET" class="flex flex-col md:flex-row gap-3">
        <div class="flex items-center gap-2 bg-white px-3 py-2 rounded-lg border border-gray-200 shadow-sm">
            <span class="material-symbols-outlined text-gray-400 text-[18px]">calendar_today</span>
            <input type="date" name="start_date" value="{{ $startDate }}" class="border-none text-sm focus:ring-0 p-0 text-gray-700 w-32 bg-transparent" required>
            <span class="text-gray-400">to</span>
            <input type="date" name="end_date" value="{{ $endDate }}" class="border-none text-sm focus:ring-0 p-0 text-gray-700 w-32 bg-transparent" required>
        </div>
        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition">
            Filter
        </button>
        <div class="flex gap-2">
            <button type="submit" name="export" value="csv" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">download</span> Excel/CSV
            </button>
            <button type="submit" name="export" value="pdf" formtarget="_blank" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span> PDF
            </button>
        </div>
    </form>
</div>

<div class="flex flex-col md:flex-row gap-6">
    <!-- Sidebar Tabs -->
    <div class="w-full md:w-64 flex-shrink-0">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <nav class="flex flex-col">
                @php
                    $tabs = [
                        'sales' => ['icon' => 'trending_up', 'label' => 'Sales Report'],
                        'customer' => ['icon' => 'people', 'label' => 'Customer Report'],
                        'coupon' => ['icon' => 'local_offer', 'label' => 'Coupon Report'],
                        'inventory' => ['icon' => 'inventory_2', 'label' => 'Inventory Report'],
                        'profit' => ['icon' => 'account_balance_wallet', 'label' => 'Profit Report'],
                        'tax' => ['icon' => 'request_quote', 'label' => 'Tax Report'],
                        'order' => ['icon' => 'shopping_cart', 'label' => 'Order Report'],
                    ];
                @endphp

                @foreach($tabs as $key => $info)
                    <a href="{{ route('admin.reports.index', ['tab' => $key, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                       class="flex items-center gap-3 px-4 py-3 text-sm font-medium border-l-4 transition-colors
                       {{ $tab === $key ? 'bg-indigo-50 border-indigo-600 text-indigo-700' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="material-symbols-outlined text-[20px] {{ $tab === $key ? 'text-indigo-600' : 'text-gray-400' }}">{{ $info['icon'] }}</span>
                        {{ $info['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-800">{{ $tabs[$tab]['label'] }}</h2>
            <span class="text-xs font-medium bg-white px-2 py-1 rounded text-gray-500 border border-gray-200">
                Showing data from {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}
            </span>
        </div>
        <div class="p-0">
            @yield('report_content')
        </div>
    </div>
</div>
@endsection

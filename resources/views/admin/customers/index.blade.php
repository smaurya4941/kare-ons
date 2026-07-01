@extends('admin.layouts.app')

@section('title', 'Customers')

@section('content')
<div class="mb-6">
    <form action="{{ route('admin.customers.index') }}" method="GET" class="flex gap-3">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..."
                class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 w-72">
        </div>
        <button type="submit" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Search</button>
        @if(request('search'))
            <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center px-2">Clear</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3 font-medium">Customer</th>
                    <th class="px-4 py-3 font-medium">Joined</th>
                    <th class="px-4 py-3 font-medium">Orders</th>
                    <th class="px-4 py-3 font-medium">Total Spent</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center font-bold text-indigo-600 flex-shrink-0 text-xs">
                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-gray-800">{{ $customer->name }}</p>
                                <p class="text-[9px] text-gray-400 font-mono">{{ $customer->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-[11px] text-gray-500">{{ $customer->created_at->format('M d, Y') }}</td>
                    <td class="px-4 py-3 text-[11px] text-gray-600 font-medium">{{ $customer->orders_count }}</td>
                    <td class="px-4 py-3 text-[11px] font-semibold text-gray-800">₹{{ number_format($customer->orders_sum_grand_total ?? 0, 2) }}</td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-indigo-600 hover:text-indigo-900 text-[11px] font-medium bg-indigo-50 px-2 py-1 rounded hover:bg-indigo-100 transition">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 text-[11px]">No customers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($customers->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection

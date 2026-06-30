@extends('admin.reports.layout')

@section('report_content')
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Order ID</th>
                <th class="px-6 py-4 font-medium">Customer</th>
                <th class="px-6 py-4 font-medium">Date</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium">Payment Method</th>
                <th class="px-6 py-4 font-medium text-right">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $row)
                <tr class="hover:bg-gray-50 transition text-sm text-gray-700">
                    <td class="px-6 py-4 font-bold text-gray-900">
                        <a href="{{ route('admin.orders.show', $row->id) }}" class="text-indigo-600 hover:underline">{{ $row->order_number }}</a>
                    </td>
                    <td class="px-6 py-4">{{ $row->user->name ?? 'Guest' }}</td>
                    <td class="px-6 py-4">{{ $row->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-6 py-4">
                        @php
                            $colors = [
                                'pending' => 'bg-amber-100 text-amber-800',
                                'processing' => 'bg-blue-100 text-blue-800',
                                'shipped' => 'bg-indigo-100 text-indigo-800',
                                'delivered' => 'bg-emerald-100 text-emerald-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                            $color = $colors[$row->order_status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium capitalize {{ $color }}">
                            {{ $row->order_status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 uppercase text-xs font-medium text-gray-500">{{ $row->payment_method }}</td>
                    <td class="px-6 py-4 text-right font-bold text-gray-900">₹{{ number_format($row->grand_total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">No orders found for the selected period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

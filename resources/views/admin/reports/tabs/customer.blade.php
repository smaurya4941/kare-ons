@extends('admin.reports.layout')

@section('report_content')
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Customer Name</th>
                <th class="px-6 py-4 font-medium">Email</th>
                <th class="px-6 py-4 font-medium text-center">Orders Placed</th>
                <th class="px-6 py-4 font-medium text-right">Total Spent</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $row)
                <tr class="hover:bg-gray-50 transition text-sm text-gray-700">
                    <td class="px-6 py-4 font-medium flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs uppercase">
                            {{ substr($row->name, 0, 2) }}
                        </div>
                        {{ $row->name }}
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $row->email }}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $row->orders_count }} orders
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right font-bold text-indigo-600">₹{{ number_format($row->orders_sum_grand_total, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">No active customers found for the selected period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

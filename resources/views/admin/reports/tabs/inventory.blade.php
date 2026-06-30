@extends('admin.reports.layout')

@section('report_content')
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Product / SKU</th>
                <th class="px-6 py-4 font-medium text-center">Available Stock</th>
                <th class="px-6 py-4 font-medium text-center">Reserved</th>
                <th class="px-6 py-4 font-medium text-right">Physical Total</th>
                <th class="px-6 py-4 font-medium text-right">Est. Stock Value</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @php $tValue = 0; @endphp
            @forelse($data as $row)
                @php 
                    $reserved = $row->reserved_stock ?? 0;
                    $physical = $row->stock_quantity + $reserved;
                    $val = ($row->sale_price ?? $row->price) * $row->stock_quantity;
                    $tValue += $val;
                @endphp
                <tr class="hover:bg-gray-50 transition text-sm text-gray-700">
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-900">{{ $row->name }}</p>
                        <p class="text-xs text-gray-500">SKU: {{ $row->sku }}</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="font-bold {{ $row->stock_quantity <= 10 ? 'text-red-600' : 'text-gray-900' }}">{{ $row->stock_quantity }}</span>
                    </td>
                    <td class="px-6 py-4 text-center text-gray-500">{{ $reserved }}</td>
                    <td class="px-6 py-4 text-right font-medium">{{ $physical }}</td>
                    <td class="px-6 py-4 text-right">₹{{ number_format($val, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">No inventory data found.</td>
                </tr>
            @endforelse
            
            @if($data->count() > 0)
            <tr class="bg-gray-50 font-bold text-gray-900">
                <td colspan="4" class="px-6 py-4 text-right uppercase text-xs">Total Estimated Value (Available):</td>
                <td class="px-6 py-4 text-right text-indigo-700">₹{{ number_format($tValue, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection

@extends('admin.reports.layout')

@section('report_content')
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Date</th>
                <th class="px-6 py-4 font-medium text-center">Total Orders</th>
                <th class="px-6 py-4 font-medium text-right">Subtotal</th>
                <th class="px-6 py-4 font-medium text-right">Discounts</th>
                <th class="px-6 py-4 font-medium text-right">Shipping</th>
                <th class="px-6 py-4 font-medium text-right text-indigo-600">Net Revenue</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @php 
                $tOrders = 0; $tSub = 0; $tDisc = 0; $tShip = 0; $tRev = 0; 
            @endphp
            @forelse($data as $row)
                @php 
                    $tOrders += $row->total_orders; 
                    $tSub += $row->subtotal; 
                    $tDisc += $row->discounts; 
                    $tShip += $row->shipping; 
                    $tRev += $row->revenue; 
                @endphp
                <tr class="hover:bg-gray-50 transition text-sm text-gray-700">
                    <td class="px-6 py-4 font-medium">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-center">{{ $row->total_orders }}</td>
                    <td class="px-6 py-4 text-right">₹{{ number_format($row->subtotal, 2) }}</td>
                    <td class="px-6 py-4 text-right text-red-500">-₹{{ number_format($row->discounts, 2) }}</td>
                    <td class="px-6 py-4 text-right">₹{{ number_format($row->shipping, 2) }}</td>
                    <td class="px-6 py-4 text-right font-bold text-gray-900">₹{{ number_format($row->revenue, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">No sales data found for the selected period.</td>
                </tr>
            @endforelse
            
            @if($data->count() > 0)
            <tr class="bg-gray-50 font-bold text-gray-900">
                <td class="px-6 py-4 text-right uppercase text-xs">Total:</td>
                <td class="px-6 py-4 text-center">{{ $tOrders }}</td>
                <td class="px-6 py-4 text-right">₹{{ number_format($tSub, 2) }}</td>
                <td class="px-6 py-4 text-right text-red-500">-₹{{ number_format($tDisc, 2) }}</td>
                <td class="px-6 py-4 text-right">₹{{ number_format($tShip, 2) }}</td>
                <td class="px-6 py-4 text-right text-indigo-700">₹{{ number_format($tRev, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection

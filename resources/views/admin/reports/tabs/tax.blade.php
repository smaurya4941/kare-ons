@extends('admin.reports.layout')

@section('report_content')
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Date</th>
                <th class="px-6 py-4 font-medium text-center">Orders</th>
                <th class="px-6 py-4 font-medium text-right">Taxable Subtotal</th>
                <th class="px-6 py-4 font-medium text-right text-indigo-600">Tax Collected (GST)</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @php $tSub = 0; $tTax = 0; @endphp
            @forelse($data as $row)
                @php 
                    $tSub += $row->subtotal;
                    $tTax += $row->tax_amount;
                @endphp
                <tr class="hover:bg-gray-50 transition text-sm text-gray-700">
                    <td class="px-6 py-4 font-medium">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-center">{{ $row->total_orders }}</td>
                    <td class="px-6 py-4 text-right">₹{{ number_format($row->subtotal, 2) }}</td>
                    <td class="px-6 py-4 text-right font-bold text-indigo-600">₹{{ number_format($row->tax_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">No tax data available for the selected period.</td>
                </tr>
            @endforelse
            
            @if($data->count() > 0)
            <tr class="bg-gray-50 font-bold text-gray-900">
                <td colspan="2" class="px-6 py-4 text-right uppercase text-xs">Total:</td>
                <td class="px-6 py-4 text-right">₹{{ number_format($tSub, 2) }}</td>
                <td class="px-6 py-4 text-right text-indigo-700 text-lg">₹{{ number_format($tTax, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection

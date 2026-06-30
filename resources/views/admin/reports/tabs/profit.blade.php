@extends('admin.reports.layout')

@section('report_content')
<div class="p-6 bg-white border-b border-gray-100">
    <div class="flex items-center gap-3 mb-2">
        <span class="material-symbols-outlined text-indigo-500">info</span>
        <h3 class="text-sm font-semibold text-gray-800">Profit Calculation Info</h3>
    </div>
    <p class="text-sm text-gray-500 ml-8">Profit is calculated as <strong>Net Revenue (Grand Total) - Tax Collected</strong>. Since Cost of Goods Sold (COGS) is not tracked at the product level, this represents Gross Operational Profit before marketing and wholesale costs.</p>
</div>

<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Date</th>
                <th class="px-6 py-4 font-medium text-center">Orders</th>
                <th class="px-6 py-4 font-medium text-right">Revenue</th>
                <th class="px-6 py-4 font-medium text-right">Tax Collected</th>
                <th class="px-6 py-4 font-medium text-right text-emerald-600">Gross Profit</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @php $tRev = 0; $tTax = 0; $tProf = 0; @endphp
            @forelse($data as $row)
                @php 
                    $profit = $row->revenue - $row->tax_amount;
                    $tRev += $row->revenue;
                    $tTax += $row->tax_amount;
                    $tProf += $profit;
                @endphp
                <tr class="hover:bg-gray-50 transition text-sm text-gray-700">
                    <td class="px-6 py-4 font-medium">{{ \Carbon\Carbon::parse($row->date)->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-center">{{ $row->total_orders }}</td>
                    <td class="px-6 py-4 text-right">₹{{ number_format($row->revenue, 2) }}</td>
                    <td class="px-6 py-4 text-right text-red-500">-₹{{ number_format($row->tax_amount, 2) }}</td>
                    <td class="px-6 py-4 text-right font-bold text-emerald-600">₹{{ number_format($profit, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">No profit data available for the selected period.</td>
                </tr>
            @endforelse
            
            @if($data->count() > 0)
            <tr class="bg-gray-50 font-bold text-gray-900">
                <td colspan="2" class="px-6 py-4 text-right uppercase text-xs">Total:</td>
                <td class="px-6 py-4 text-right">₹{{ number_format($tRev, 2) }}</td>
                <td class="px-6 py-4 text-right text-red-500">-₹{{ number_format($tTax, 2) }}</td>
                <td class="px-6 py-4 text-right text-emerald-600 text-lg">₹{{ number_format($tProf, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection

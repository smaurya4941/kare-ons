@extends('admin.reports.layout')

@section('report_content')
<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Coupon Code</th>
                <th class="px-6 py-4 font-medium">Discount Type</th>
                <th class="px-6 py-4 font-medium text-right">Value</th>
                <th class="px-6 py-4 font-medium text-center">Times Used</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($data as $row)
                <tr class="hover:bg-gray-50 transition text-sm text-gray-700">
                    <td class="px-6 py-4 font-bold text-gray-900 uppercase">
                        <span class="bg-emerald-50 text-emerald-700 px-2 py-1 rounded border border-emerald-200 border-dashed">{{ $row->code }}</span>
                    </td>
                    <td class="px-6 py-4 capitalize">{{ $row->type }}</td>
                    <td class="px-6 py-4 text-right">
                        @if($row->type === 'percentage')
                            {{ $row->value }}%
                        @else
                            ₹{{ number_format($row->value, 2) }}
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            {{ $row->usages_count }} times
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">No coupons were used during the selected period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

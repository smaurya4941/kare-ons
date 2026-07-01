@extends('admin.layouts.app')

@section('title', 'Return & Refund Requests')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-sm text-gray-500">Manage customer requests for refunds and replacements.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Req ID</th>
                <th class="px-6 py-4 font-medium">Order #</th>
                <th class="px-6 py-4 font-medium">Customer</th>
                <th class="px-6 py-4 font-medium">Type</th>
                <th class="px-6 py-4 font-medium">Reason</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($requests as $req)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <span class="text-[11px] font-mono font-bold text-gray-700">#RR-{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</span>
                </td>
                <td class="px-6 py-4">
                    <a href="#" class="text-[12px] font-bold text-indigo-600 hover:underline">#ORD-{{ str_pad($req->order_id, 4, '0', STR_PAD_LEFT) }}</a>
                </td>
                <td class="px-6 py-4">
                    <p class="text-[12px] font-medium text-gray-800">{{ $req->user->name }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $req->type === 'refund' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $req->type }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <p class="text-[12px] text-gray-600 truncate max-w-xs" title="{{ $req->reason }}">{{ $req->reason }}</p>
                </td>
                <td class="px-6 py-4">
                    @php
                        $color = match($req->status) {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'approved' => 'bg-indigo-100 text-indigo-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'completed' => 'bg-emerald-100 text-emerald-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $color }}">
                        {{ $req->status }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.returns.show', $req->id) }}" class="text-indigo-600 hover:underline text-[11px] font-medium">Review</a>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-6 py-8 text-center text-gray-500 text-sm">No return requests found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $requests->links() }}
</div>
@endsection

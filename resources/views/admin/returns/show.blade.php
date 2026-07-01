@extends('admin.layouts.app')

@section('title', 'Review Return Request')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.returns.index') }}" class="text-[11px] font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1 w-fit">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Requests
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Request Details</h3>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mb-1">Request Type</p>
                    <span class="px-2 py-1 rounded text-[11px] font-bold uppercase {{ $return_request->type === 'refund' ? 'bg-orange-100 text-orange-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ $return_request->type }}
                    </span>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mb-1">Current Status</p>
                    @php
                        $color = match($return_request->status) {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'approved' => 'bg-indigo-100 text-indigo-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'completed' => 'bg-emerald-100 text-emerald-800',
                            default => 'bg-gray-100 text-gray-800',
                        };
                    @endphp
                    <span class="px-2 py-1 rounded text-[11px] font-bold uppercase {{ $color }}">
                        {{ $return_request->status }}
                    </span>
                </div>
            </div>

            <div class="mb-4">
                <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mb-1">Reason for Return</p>
                <p class="text-sm text-gray-800 bg-gray-50 p-3 rounded-lg">{{ $return_request->reason }}</p>
            </div>
            
            @if($return_request->customer_note)
            <div>
                <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold mb-1">Customer Note</p>
                <p class="text-sm text-gray-700">{{ $return_request->customer_note }}</p>
            </div>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Order Items (#ORD-{{ str_pad($return_request->order_id, 4, '0', STR_PAD_LEFT) }})</h3>
            
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] text-gray-500 uppercase border-b border-gray-100">
                        <th class="py-2">Item</th>
                        <th class="py-2">Price</th>
                        <th class="py-2 text-center">Qty</th>
                        <th class="py-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($return_request->order->items as $item)
                    <tr>
                        <td class="py-3">
                            <p class="text-[12px] font-medium text-gray-800">{{ $item->product_name }}</p>
                        </td>
                        <td class="py-3 text-[12px] text-gray-600">₹{{ number_format($item->price, 2) }}</td>
                        <td class="py-3 text-[12px] text-gray-600 text-center">{{ $item->quantity }}</td>
                        <td class="py-3 text-[12px] text-gray-800 font-medium text-right">₹{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-6">
        <form action="{{ route('admin.returns.update', $return_request->id) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            @csrf @method('PUT')
            <h3 class="text-sm font-bold text-gray-800 border-b border-gray-100 pb-3 mb-4">Update Status</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Status</label>
                    <select name="status" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="pending" {{ $return_request->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $return_request->status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $return_request->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ $return_request->status === 'completed' ? 'selected' : '' }}>Completed (Refunded/Replaced)</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Admin Note (Internal)</label>
                    <textarea name="admin_note" rows="3" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">{{ $return_request->admin_note }}</textarea>
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                    Update Request
                </button>
            </div>
        </form>

        <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
            <h3 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-3">Customer Details</h3>
            <p class="text-sm font-medium text-gray-800">{{ $return_request->user->name }}</p>
            <p class="text-[12px] text-gray-600">{{ $return_request->user->email }}</p>
            <p class="text-[12px] text-gray-600">{{ $return_request->user->phone }}</p>
        </div>
    </div>
</div>
@endsection

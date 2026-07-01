@extends('admin.layouts.app')

@section('title', 'Stock History: ' . $product->name)

@section('content')
<div class="mb-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.inventory.index') }}" class="w-8 h-8 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
        </a>
        <div>
            <h1 class="text-lg font-bold text-gray-900">Stock History</h1>
            <p class="text-[11px] text-gray-500">Tracking inventory movements for <strong>{{ $product->name }}</strong> <span class="font-mono">(SKU: {{ $product->sku }})</span></p>
        </div>
    </div>
    
    <div class="bg-white px-3 py-1.5 rounded border border-gray-200 shadow-sm flex items-center gap-2">
        <span class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">Available Stock:</span>
        <span class="text-sm font-bold text-indigo-600">{{ $product->stock_quantity }}</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3 font-medium">Date & Time</th>
                    <th class="px-4 py-3 font-medium">Type</th>
                    <th class="px-4 py-3 font-medium">User / Source</th>
                    <th class="px-4 py-3 font-medium">Quantity Change</th>
                    <th class="px-4 py-3 font-medium">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-[11px] text-gray-600">
                        {{ $transaction->created_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $typeColors = [
                                'purchase' => 'bg-emerald-100 text-emerald-800',
                                'adjustment' => 'bg-indigo-100 text-indigo-800',
                                'order_fulfillment' => 'bg-blue-100 text-blue-800',
                                'order_cancellation' => 'bg-amber-100 text-amber-800',
                                'return' => 'bg-purple-100 text-purple-800',
                            ];
                            $color = $typeColors[$transaction->type] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-medium uppercase tracking-wider {{ $color }}">
                            {{ str_replace('_', ' ', $transaction->type) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-[11px] text-gray-800">
                        @if($transaction->user)
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px] text-gray-400">person</span>
                                {{ $transaction->user->name }}
                            </div>
                        @else
                            <span class="text-gray-400 italic">System</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($transaction->quantity > 0)
                            <span class="inline-flex items-center text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded text-[11px]">
                                <span class="material-symbols-outlined text-[12px] mr-1">arrow_upward</span>
                                +{{ $transaction->quantity }}
                            </span>
                        @else
                            <span class="inline-flex items-center text-red-600 font-bold bg-red-50 px-2 py-0.5 rounded text-[11px]">
                                <span class="material-symbols-outlined text-[12px] mr-1">arrow_downward</span>
                                {{ $transaction->quantity }}
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-[11px] text-gray-600">
                        {{ $transaction->notes ?? '-' }}
                        @if($transaction->reference_id)
                            <br><span class="text-[9px] text-gray-400 font-mono mt-0.5 inline-block">Ref: {{ $transaction->reference_id }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-3xl text-gray-300 mb-2">history</span>
                            <p class="text-gray-500 text-[11px]">No transaction history found for this product.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
    <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection

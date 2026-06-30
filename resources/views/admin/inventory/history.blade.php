@extends('admin.layouts.app')

@section('title', 'Stock History: ' . $product->name)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.inventory.index') }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 hover:text-gray-800 transition">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Stock History</h1>
            <p class="text-sm text-gray-500">Tracking inventory movements for <strong>{{ $product->name }}</strong> (SKU: {{ $product->sku }})</p>
        </div>
    </div>
    
    <div class="bg-white px-4 py-2 rounded-lg border border-gray-100 shadow-sm flex items-center gap-3">
        <span class="text-sm font-medium text-gray-500">Current Available Stock:</span>
        <span class="text-xl font-bold text-indigo-600">{{ $product->stock_quantity }}</span>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Date & Time</th>
                    <th class="px-6 py-4 font-medium">Type</th>
                    <th class="px-6 py-4 font-medium">User / Source</th>
                    <th class="px-6 py-4 font-medium">Quantity Change</th>
                    <th class="px-6 py-4 font-medium">Notes</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $transaction->created_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="px-6 py-4">
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
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium uppercase tracking-wide {{ $color }}">
                            {{ str_replace('_', ' ', $transaction->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-800">
                        @if($transaction->user)
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-gray-400">person</span>
                                {{ $transaction->user->name }}
                            </div>
                        @else
                            <span class="text-gray-400 italic">System</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($transaction->quantity > 0)
                            <span class="inline-flex items-center text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded">
                                <span class="material-symbols-outlined text-[14px] mr-1">arrow_upward</span>
                                +{{ $transaction->quantity }}
                            </span>
                        @else
                            <span class="inline-flex items-center text-red-600 font-bold bg-red-50 px-2 py-0.5 rounded">
                                <span class="material-symbols-outlined text-[14px] mr-1">arrow_downward</span>
                                {{ $transaction->quantity }}
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        {{ $transaction->notes ?? '-' }}
                        @if($transaction->reference_id)
                            <br><span class="text-xs text-gray-400">Ref: {{ $transaction->reference_id }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">history</span>
                            <p class="text-gray-500 text-sm">No transaction history found for this product.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection

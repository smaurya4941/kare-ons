@extends('admin.layouts.app')

@section('title', 'Inventory Management')

@section('content')
<!-- Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 flex items-center justify-between">
        <div>
            <p class="text-[10px] font-medium text-gray-500 mb-0.5 uppercase tracking-wider">Total Available Stock</p>
            <h3 class="text-xl font-bold text-gray-800">{{ number_format($totalStock) }}</h3>
        </div>
        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
            <span class="material-symbols-outlined text-[20px]">inventory</span>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 flex items-center justify-between">
        <div>
            <p class="text-[10px] font-medium text-orange-500 mb-0.5 uppercase tracking-wider">Low Stock Products</p>
            <h3 class="text-xl font-bold text-orange-600">{{ number_format($lowStockCount) }}</h3>
        </div>
        <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-600">
            <span class="material-symbols-outlined text-[20px]">warning</span>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 flex items-center justify-between">
        <div>
            <p class="text-[10px] font-medium text-red-500 mb-0.5 uppercase tracking-wider">Out of Stock</p>
            <h3 class="text-xl font-bold text-red-600">{{ number_format($outOfStockCount) }}</h3>
        </div>
        <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600">
            <span class="material-symbols-outlined text-[20px]">error</span>
        </div>
    </div>
</div>

<!-- Filters and Actions -->
<div class="flex flex-col md:flex-row gap-3 justify-between items-center mb-4">
    <form action="{{ route('admin.inventory.index') }}" method="GET" class="flex w-full md:w-auto gap-3">
        <div class="relative flex-1 md:w-64">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search products or SKU..." class="w-full pl-8 pr-3 py-1.5 rounded-md border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-[11px]">
            <span class="material-symbols-outlined absolute left-2.5 top-2 text-gray-400 text-[14px]">search</span>
        </div>
        
        <select name="filter" onchange="this.form.submit()" class="rounded-md border-gray-200 py-1.5 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-[11px]">
            <option value="">All Products</option>
            <option value="low_stock" {{ ($filter ?? '') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
            <option value="out_of_stock" {{ ($filter ?? '') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
        </select>

        @if($search || $filter)
            <a href="{{ route('admin.inventory.index') }}" class="px-2 py-1.5 text-[11px] text-gray-500 hover:text-indigo-600 flex items-center">Clear</a>
        @endif
    </form>
</div>

<!-- Inventory Table -->
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3 font-medium">Product</th>
                    <th class="px-4 py-3 font-medium text-center">Available Stock</th>
                    <th class="px-4 py-3 font-medium text-center">Reserved (Pending)</th>
                    <th class="px-4 py-3 font-medium text-center">Physical Stock</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                @php
                    $available = $product->stock_quantity;
                    $reserved = $product->reserved_stock ?? 0;
                    $physical = $available + $reserved;

                    if ($available <= 0) {
                        $statusText = 'Out of Stock';
                        $statusClass = 'bg-red-100 text-red-800';
                    } elseif ($available <= 10) {
                        $statusText = 'Low Stock';
                        $statusClass = 'bg-orange-100 text-orange-800';
                    } else {
                        $statusText = 'In Stock';
                        $statusClass = 'bg-emerald-100 text-emerald-800';
                    }
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->main_image ? asset('storage/'.$product->main_image) : 'https://placehold.co/100x100' }}" class="w-8 h-8 rounded object-cover bg-gray-100 border border-gray-200">
                            <div>
                                <p class="text-[11px] font-semibold text-gray-800">{{ $product->name }}</p>
                                <p class="text-[9px] text-gray-500 font-mono">SKU: {{ $product->sku }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-[12px] font-bold text-gray-800">{{ $available }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-[11px] font-medium text-gray-500">{{ $reserved }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="text-[11px] font-medium text-gray-600">{{ $physical }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium uppercase tracking-wider {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right space-x-1">
                        <button type="button" @click="$dispatch('open-adjustment-modal', { id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', stock: {{ $available }} })" class="text-[10px] font-medium text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2 py-1 rounded hover:bg-indigo-100 transition">
                            Adjust
                        </button>
                        <a href="{{ route('admin.inventory.history', $product->id) }}" class="text-[10px] font-medium text-gray-600 hover:text-gray-900 bg-gray-100 px-2 py-1 rounded hover:bg-gray-200 transition">
                            History
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-3xl text-gray-300 mb-2">inventory_2</span>
                            <p class="text-gray-500 text-[11px]">No products found matching your criteria.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="px-4 py-3 border-t border-gray-100 bg-gray-50">
        {{ $products->links() }}
    </div>
    @endif
</div>

<!-- Stock Adjustment Modal -->
<div x-data="{ open: false, productId: null, productName: '', currentStock: 0 }" 
     @open-adjustment-modal.window="open = true; productId = $event.detail.id; productName = $event.detail.name; currentStock = $event.detail.stock"
     class="relative z-50" x-show="open" style="display: none;">
    
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" x-show="open" x-transition.opacity></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md"
                 x-show="open" @click.away="open = false"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <form :action="'{{ url('admin/inventory') }}/' + productId + '/adjustment'" method="POST">
                    @csrf
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-5 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-8 sm:w-8">
                                <span class="material-symbols-outlined text-indigo-600 text-[18px]">tune</span>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-sm font-semibold leading-6 text-gray-900">Adjust Stock</h3>
                                <p class="text-[11px] text-gray-500 mt-0.5">Adjusting inventory for <strong x-text="productName"></strong>.</p>
                                
                                <div class="mt-4 space-y-3">
                                    <div class="flex justify-between bg-gray-50 p-2.5 rounded border border-gray-100">
                                        <span class="text-[11px] text-gray-600">Current Available Stock:</span>
                                        <span class="text-[12px] font-bold text-gray-900" x-text="currentStock"></span>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Transaction Type</label>
                                        <select name="type" required class="w-full rounded-md border-gray-200 shadow-sm py-1.5 px-3 focus:border-indigo-500 focus:ring-indigo-500 text-[11px]">
                                            <option value="purchase">Purchase Entry (Add Stock)</option>
                                            <option value="adjustment">Manual Adjustment</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Quantity</label>
                                        <input type="number" name="quantity" required placeholder="Use negative for reduction (e.g. -5)" class="w-full rounded-md py-1.5 px-3 border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-[11px]">
                                        <p class="text-[9px] text-gray-400 mt-1">Enter positive numbers to add stock, negative to remove stock.</p>
                                    </div>

                                    <div>
                                        <label class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Notes (Optional)</label>
                                        <textarea name="notes" rows="2" placeholder="Reason for adjustment or PO reference..." class="w-full rounded-md py-1.5 px-3 border-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-[11px]"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-5 border-t border-gray-100">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-[11px] font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto transition">Confirm Adjustment</button>
                        <button type="button" @click="open = false" class="mt-2 inline-flex w-full justify-center rounded-md bg-white px-3 py-1.5 text-[11px] font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

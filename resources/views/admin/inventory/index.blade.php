@extends('admin.layouts.app')

@section('title', 'Inventory Management')

@section('content')
<!-- Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Total Available Stock</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalStock) }}</h3>
        </div>
        <div class="w-14 h-14 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
            <span class="material-symbols-outlined text-3xl">inventory</span>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-orange-500 mb-1">Low Stock Products</p>
            <h3 class="text-3xl font-bold text-orange-600">{{ number_format($lowStockCount) }}</h3>
        </div>
        <div class="w-14 h-14 rounded-full bg-orange-50 flex items-center justify-center text-orange-600">
            <span class="material-symbols-outlined text-3xl">warning</span>
        </div>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-red-500 mb-1">Out of Stock</p>
            <h3 class="text-3xl font-bold text-red-600">{{ number_format($outOfStockCount) }}</h3>
        </div>
        <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center text-red-600">
            <span class="material-symbols-outlined text-3xl">error</span>
        </div>
    </div>
</div>

<!-- Filters and Actions -->
<div class="flex flex-col md:flex-row gap-4 justify-between items-center mb-6">
    <form action="{{ route('admin.inventory.index') }}" method="GET" class="flex w-full md:w-auto gap-4">
        <div class="relative flex-1 md:w-64">
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search products or SKU..." class="w-full pl-10 pr-4 py-2 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
            <span class="material-symbols-outlined absolute left-3 top-2 text-gray-400 text-[20px]">search</span>
        </div>
        
        <select name="filter" onchange="this.form.submit()" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm">
            <option value="">All Products</option>
            <option value="low_stock" {{ ($filter ?? '') == 'low_stock' ? 'selected' : '' }}>Low Stock</option>
            <option value="out_of_stock" {{ ($filter ?? '') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
        </select>

        @if($search || $filter)
            <a href="{{ route('admin.inventory.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900 flex items-center">Clear</a>
        @endif
    </form>
</div>

<!-- Inventory Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Product</th>
                    <th class="px-6 py-4 font-medium text-center">Available Stock</th>
                    <th class="px-6 py-4 font-medium text-center">Reserved (Pending)</th>
                    <th class="px-6 py-4 font-medium text-center">Physical Stock</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
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
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $product->main_image ? asset('storage/'.$product->main_image) : 'https://placehold.co/100x100' }}" class="w-10 h-10 rounded-lg object-cover bg-gray-100">
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-base font-bold text-gray-800">{{ $available }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm font-medium text-gray-500">{{ $reserved }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="text-sm font-medium text-gray-600">{{ $physical }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button type="button" @click="$dispatch('open-adjustment-modal', { id: {{ $product->id }}, name: '{{ addslashes($product->name) }}', stock: {{ $available }} })" class="text-sm font-medium text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1.5 rounded-md hover:bg-indigo-100 transition">
                            Adjust
                        </button>
                        <a href="{{ route('admin.inventory.history', $product->id) }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 bg-gray-100 px-3 py-1.5 rounded-md hover:bg-gray-200 transition">
                            History
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">inventory_2</span>
                            <p class="text-gray-500 text-sm">No products found matching your criteria.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
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
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                 x-show="open" @click.away="open = false"
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                
                <form :action="'{{ url('admin/inventory') }}/' + productId + '/adjustment'" method="POST">
                    @csrf
                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <span class="material-symbols-outlined text-indigo-600">tune</span>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900">Adjust Stock</h3>
                                <p class="text-sm text-gray-500 mt-1">Adjusting inventory for <strong x-text="productName"></strong>.</p>
                                
                                <div class="mt-4 space-y-4">
                                    <div class="flex justify-between bg-gray-50 p-3 rounded-lg border border-gray-100">
                                        <span class="text-sm text-gray-600">Current Available Stock:</span>
                                        <span class="text-sm font-bold text-gray-900" x-text="currentStock"></span>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Type</label>
                                        <select name="type" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            <option value="purchase">Purchase Entry (Add Stock)</option>
                                            <option value="adjustment">Manual Adjustment</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                        <input type="number" name="quantity" required placeholder="Use negative for reduction (e.g. -5)" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <p class="text-xs text-gray-500 mt-1">Enter positive numbers to add stock, negative to remove stock.</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                                        <textarea name="notes" rows="2" placeholder="Reason for adjustment or PO reference..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button type="submit" class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto">Confirm Adjustment</button>
                        <button type="button" @click="open = false" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

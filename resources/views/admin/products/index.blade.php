@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-400">
                <span class="material-symbols-outlined text-[16px]">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="pl-8 pr-3 py-1.5 border border-gray-200 rounded-md text-[11px] focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-56 shadow-sm">
        </div>
        <select name="category" class="border border-gray-200 rounded-md text-[11px] px-2.5 py-1.5 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-md text-[11px] font-medium hover:bg-gray-50 transition shadow-sm">Filter</button>
        @if(request()->hasAny(['search', 'category']))
            <a href="{{ route('admin.products.index') }}" class="text-[11px] text-gray-500 hover:text-indigo-600 flex items-center px-2">Clear</a>
        @endif
    </form>
    
    <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-md text-[11px] font-medium transition shadow-sm flex items-center gap-1.5">
        <span class="material-symbols-outlined text-[16px]">add</span>
        Add Product
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200 text-[10px] text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-2.5 font-semibold">Product</th>
                    <th class="px-4 py-2.5 font-semibold">SKU</th>
                    <th class="px-4 py-2.5 font-semibold">Category</th>
                    <th class="px-4 py-2.5 font-semibold text-right">Price</th>
                    <th class="px-4 py-2.5 font-semibold text-center">Stock</th>
                    <th class="px-4 py-2.5 font-semibold text-center">Status</th>
                    <th class="px-4 py-2.5 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-[11px]">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition group">
                    <td class="px-4 py-2">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded border border-gray-200 overflow-hidden flex-shrink-0 bg-white">
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-gray-300 w-full h-full flex items-center justify-center text-[16px]">image</span>
                                @endif
                            </div>
                            <div class="flex flex-col max-w-[200px]">
                                <p class="font-medium text-gray-800 truncate" title="{{ $product->name }}">{{ $product->name }}</p>
                                <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="text-[9px] text-indigo-500 hover:underline">View in store</a>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-2 font-mono text-gray-500">{{ $product->sku }}</td>
                    <td class="px-4 py-2 text-gray-600 truncate max-w-[120px]" title="{{ $product->category->name ?? 'None' }}">{{ $product->category->name ?? 'None' }}</td>
                    <td class="px-4 py-2 text-right">
                        @if($product->sale_price)
                            <div class="flex flex-col items-end">
                                <span class="font-semibold text-gray-800">₹{{ number_format($product->sale_price, 2) }}</span>
                                <span class="text-[9px] text-gray-400 line-through">₹{{ number_format($product->price, 2) }}</span>
                            </div>
                        @else
                            <span class="font-semibold text-gray-800">₹{{ number_format($product->price, 2) }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-center">
                        <span class="inline-flex items-center justify-center px-1.5 py-0.5 rounded text-[10px] font-medium {{ $product->stock_quantity > 10 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : ($product->stock_quantity > 0 ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-red-50 text-red-700 border border-red-100') }}">
                            {{ $product->stock_quantity }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-center">
                        @if($product->status)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">Active</span>
                        @else
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium bg-gray-50 text-gray-600 border border-gray-200">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-right">
                        <div class="flex justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="p-1 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition" title="Edit">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition" title="Delete">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500 text-[11px]">
                        No products found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="px-4 py-2 border-t border-gray-100 bg-gray-50/50">
        {{ $products->links('pagination::tailwind') }}
    </div>
    @endif
</div>
@endsection

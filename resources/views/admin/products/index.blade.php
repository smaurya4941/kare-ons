@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 w-full sm:w-64">
        </div>
        <select name="category" class="border border-gray-300 rounded-lg text-sm px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Filter</button>
        @if(request()->hasAny(['search', 'category']))
            <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-500 hover:text-indigo-600 flex items-center px-2">Clear</a>
        @endif
    </form>
    
    <a href="{{ route('admin.products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">add</span>
        Add Product
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium">Product</th>
                    <th class="px-6 py-4 font-medium">SKU</th>
                    <th class="px-6 py-4 font-medium">Category</th>
                    <th class="px-6 py-4 font-medium">Price</th>
                    <th class="px-6 py-4 font-medium">Stock</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded bg-gray-100 border border-gray-200 overflow-hidden flex-shrink-0">
                                @if($product->main_image)
                                    <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-gray-400 w-full h-full flex items-center justify-center text-[20px]">image</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800 line-clamp-1">{{ $product->name }}</p>
                                <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="text-xs text-indigo-600 hover:underline">View in store</a>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm font-mono text-gray-600">{{ $product->sku }}</td>
                    <td class="px-6 py-4 text-sm text-gray-600">{{ $product->category->name ?? 'None' }}</td>
                    <td class="px-6 py-4">
                        @if($product->sale_price)
                            <span class="text-sm font-semibold text-gray-800">₹{{ number_format($product->sale_price, 2) }}</span>
                            <span class="text-xs text-gray-400 line-through block">₹{{ number_format($product->price, 2) }}</span>
                        @else
                            <span class="text-sm font-semibold text-gray-800">₹{{ number_format($product->price, 2) }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm {{ $product->stock_quantity > 10 ? 'text-emerald-600' : ($product->stock_quantity > 0 ? 'text-amber-600' : 'text-red-600') }} font-medium">
                            {{ $product->stock_quantity }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($product->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Active</span>
                        @elseif($product->status === 'draft')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Draft</span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-gray-400 hover:text-indigo-600 transition" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Delete">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500 text-sm">
                        No products found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection

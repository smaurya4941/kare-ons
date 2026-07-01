@extends('admin.layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Products
    </a>
    <div class="flex items-center gap-3">
        <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
            <span class="material-symbols-outlined text-[18px]">open_in_new</span> View on site
        </a>
        <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">
            <span class="material-symbols-outlined text-[18px]">edit</span> Edit
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Media --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
            @if($product->main_image)
                <img src="{{ asset('storage/' . $product->main_image) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover rounded-lg">
            @else
                <div class="w-full h-64 bg-gray-100 rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-gray-300 text-5xl">image</span>
                </div>
            @endif
            @if($product->images->count())
                <div class="grid grid-cols-4 gap-2 mt-3">
                    @foreach($product->images as $img)
                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="" class="w-full h-16 object-cover rounded border border-gray-100">
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Details --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-xl font-bold text-gray-900">{{ $product->name }}</h1>
                    <p class="text-sm text-gray-500 mt-1">SKU: {{ $product->sku }} · {{ $product->category->name ?? 'Uncategorized' }}</p>
                </div>
                @if($product->status)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Active</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Inactive</span>
                @endif
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-500">Price</p>
                    <p class="text-sm font-semibold text-gray-800">₹{{ number_format($product->price, 2) }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-500">Sale Price</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $product->sale_price ? '₹' . number_format($product->sale_price, 2) : '—' }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-500">Stock</p>
                    <p class="text-sm font-semibold {{ $product->stock_quantity <= 0 ? 'text-red-600' : ($product->stock_quantity <= 10 ? 'text-amber-600' : 'text-gray-800') }}">{{ $product->stock_quantity }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <p class="text-xs text-gray-500">Brand</p>
                    <p class="text-sm font-semibold text-gray-800">{{ $product->brand->name ?? '—' }}</p>
                </div>
            </div>

            @if($product->short_description)
                <p class="text-sm text-gray-600 mt-6">{{ $product->short_description }}</p>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-800">Recent Reviews ({{ $product->reviews->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($product->reviews->take(5) as $review)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-gray-800">{{ $review->user->name ?? 'Anonymous' }}</p>
                        <div class="flex items-center text-amber-400">
                            @for($i=1; $i<=5; $i++)
                                <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' {{ $i <= $review->rating ? '1' : '0' }}">star</span>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{ $review->review }}</p>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">No approved reviews yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

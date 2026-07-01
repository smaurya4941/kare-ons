@extends('admin.layouts.app')

@section('title', 'Edit Product: ' . $product->name)

@section('content')
<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('admin.products.index') }}" class="text-[11px] font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
        <span class="material-symbols-outlined text-[14px]">arrow_back</span> Back to Products
    </a>
    <a href="{{ route('product.show', $product->slug) }}" target="_blank" class="text-[11px] font-medium text-indigo-500 hover:text-indigo-600 flex items-center gap-1 bg-indigo-50 px-2 py-1 rounded-md">
        <span class="material-symbols-outlined text-[14px]">visibility</span> View on Store
    </a>
</div>

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-full">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <!-- Main Form Details -->
        <div class="xl:col-span-2 space-y-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Basic Information</h3>
                
                <div class="space-y-3">
                    <div>
                        <label for="name" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label for="sku" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">SKU <span class="text-red-500">*</span></label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" required class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 uppercase shadow-sm @error('sku') border-red-500 @enderror">
                            @error('sku') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="category_id" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Category <span class="text-red-500">*</span></label>
                            <select name="category_id" id="category_id" required class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white @error('category_id') border-red-500 @enderror">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label for="brand_id" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Brand</label>
                            <select name="brand_id" id="brand_id" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white @error('brand_id') border-red-500 @enderror">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="tax_id" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Tax Slab</label>
                            <select name="tax_id" id="tax_id" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white @error('tax_id') border-red-500 @enderror">
                                <option value="">No Tax / Exempt</option>
                                @foreach($taxes as $tax)
                                    <option value="{{ $tax->id }}" {{ old('tax_id', $product->tax_id) == $tax->id ? 'selected' : '' }}>{{ $tax->name }} ({{ $tax->rate }}%)</option>
                                @endforeach
                            </select>
                            @error('tax_id') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="pack_size" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Pack Size</label>
                            <input type="text" name="pack_size" id="pack_size" value="{{ old('pack_size', $product->pack_size) }}" placeholder="e.g. 60 Tablets" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm @error('pack_size') border-red-500 @enderror">
                            @error('pack_size') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="short_description" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Short Description</label>
                        <textarea name="short_description" id="short_description" rows="2" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div>
                        <label for="description" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Full Description <span class="text-red-500">*</span></label>
                        <textarea name="description" id="description" rows="4" required class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm @error('description') border-red-500 @enderror">{{ old('description', $product->description) }}</textarea>
                        @error('description') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Herbal Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Herbal Information (Optional)</h3>
                <div class="space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label for="ingredients" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Ingredients</label>
                            <textarea name="ingredients" id="ingredients" rows="2" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('ingredients', $product->ingredients) }}</textarea>
                        </div>
                        <div>
                            <label for="benefits" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Health Benefits</label>
                            <textarea name="benefits" id="benefits" rows="2" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('benefits', $product->benefits) }}</textarea>
                        </div>
                        <div>
                            <label for="usage_instructions" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Directions for Use</label>
                            <textarea name="usage_instructions" id="usage_instructions" rows="2" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('usage_instructions', $product->usage_instructions) }}</textarea>
                        </div>
                        <div>
                            <label for="storage_instructions" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Storage Instructions</label>
                            <textarea name="storage_instructions" id="storage_instructions" rows="2" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('storage_instructions', $product->storage_instructions) }}</textarea>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label for="ayurvedic_reference" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Ayurvedic Reference</label>
                            <textarea name="ayurvedic_reference" id="ayurvedic_reference" rows="2" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('ayurvedic_reference', $product->ayurvedic_reference) }}</textarea>
                        </div>
                        <div>
                            <label for="suitable_for" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Suitable For</label>
                            <textarea name="suitable_for" id="suitable_for" rows="2" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('suitable_for', $product->suitable_for) }}</textarea>
                        </div>
                        <div>
                            <label for="precautions" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Precautions / Warnings</label>
                            <textarea name="precautions" id="precautions" rows="2" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('precautions', $product->precautions) }}</textarea>
                        </div>
                        <div>
                            <label for="disclaimer" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Disclaimer</label>
                            <textarea name="disclaimer" id="disclaimer" rows="2" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('disclaimer', $product->disclaimer) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Pricing & Inventory</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label for="price" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Regular Price (₹) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}" required class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm @error('price') border-red-500 @enderror">
                        @error('price') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="sale_price" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Sale Price (₹)</label>
                        <input type="number" step="0.01" name="sale_price" id="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm @error('sale_price') border-red-500 @enderror">
                        @error('sale_price') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="stock_quantity" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Stock Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required min="0" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm @error('stock_quantity') border-red-500 @enderror">
                        @error('stock_quantity') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            
            <!-- SEO Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Search Engine Optimization</h3>
                <div class="space-y-3">
                    <div>
                        <label for="meta_title" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $product->meta_title) }}" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    </div>
                    <div>
                        <label for="meta_description" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="2" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('meta_description', $product->meta_description) }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar Options -->
        <div class="space-y-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Status</h3>
                <div class="space-y-3">
                    <div>
                        <select name="status" id="status" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                            <option value="1" {{ old('status', $product->status) == '1' ? 'selected' : '' }}>Published (Active)</option>
                            <option value="0" {{ old('status', $product->status) == '0' ? 'selected' : '' }}>Draft (Hidden)</option>
                        </select>
                    </div>
                    <div class="space-y-2 mt-3 pt-3 border-t border-gray-100">
                        <p class="text-[10px] font-semibold text-gray-500 uppercase">Homepage Placement</p>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-3.5 h-3.5">
                            <span class="ml-2 text-[11px] text-gray-700">Featured Product</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_best_seller" value="1" {{ old('is_best_seller', $product->is_best_seller) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-3.5 h-3.5">
                            <span class="ml-2 text-[11px] text-gray-700">Best Seller</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_trending" value="1" {{ old('is_trending', $product->is_trending) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-3.5 h-3.5">
                            <span class="ml-2 text-[11px] text-gray-700">Trending Product</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Product Image</h3>
                
                <div class="mt-2" x-data="imageViewer('{{ $product->main_image ? asset('storage/' . $product->main_image) : '' }}')">
                    <div class="flex items-center justify-center w-full">
                        <label for="main_image" class="flex flex-col items-center justify-center w-full h-40 border border-gray-300 border-dashed rounded-md cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden relative transition">
                            <div class="flex flex-col items-center justify-center pt-4 pb-4" x-show="!imageUrl">
                                <span class="material-symbols-outlined text-gray-400 mb-1 text-2xl">cloud_upload</span>
                                <p class="mb-1 text-[11px] text-gray-500"><span class="font-semibold">Click to upload</span></p>
                                <p class="text-[9px] text-gray-400">PNG, JPG or WEBP (MAX. 2MB)</p>
                            </div>
                            <img :src="imageUrl" x-show="imageUrl" class="absolute inset-0 w-full h-full object-cover">
                            <input id="main_image" name="main_image" type="file" class="hidden" accept="image/*" @change="fileChosen">
                        </label>
                    </div>
                    @error('main_image') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Product Gallery</h3>
                
                @if($product->images->count() > 0)
                <div class="grid grid-cols-3 gap-2 mb-3">
                    @foreach($product->images as $image)
                    <div class="relative group aspect-square rounded-md border border-gray-200 overflow-hidden bg-gray-50">
                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-full object-cover">
                        <button type="submit" form="delete-image-{{ $image->id }}" onclick="return confirm('Are you sure you want to delete this image?')" class="absolute top-1 right-1 bg-red-500 text-white rounded p-1 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center hover:bg-red-600 shadow-sm" title="Delete Image">
                            <span class="material-symbols-outlined text-[14px]">delete</span>
                        </button>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <div class="mt-2">
                    <label class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Upload more images</label>
                    <input type="file" name="gallery[]" multiple accept="image/*" class="w-full border border-gray-200 rounded-md px-2 py-1.5 text-[10px] focus:outline-none focus:ring-1 focus:ring-indigo-500 file:mr-3 file:py-1 file:px-3 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 shadow-sm bg-white">
                    <p class="text-[9px] text-gray-400 mt-1.5">You can select multiple files. Max 2MB per image.</p>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition shadow-sm flex justify-center items-center gap-1.5 text-[11px]">
                    <span class="material-symbols-outlined text-[16px]">save</span> Update Product
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    function imageViewer(initialUrl) {
        return {
            imageUrl: initialUrl,
            fileChosen(event) {
                this.fileToDataUrl(event, src => this.imageUrl = src)
            },
            fileToDataUrl(event, callback) {
                if (! event.target.files.length) return
                let file = event.target.files[0],
                    reader = new FileReader()
                reader.readAsDataURL(file)
                reader.onload = e => callback(e.target.result)
            },
        }
    }
</script>

@foreach($product->images as $image)
<form id="delete-image-{{ $image->id }}" action="{{ route('admin.products.images.destroy', [$product->id, $image->id]) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endforeach

@endsection

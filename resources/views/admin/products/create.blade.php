@extends('admin.layouts.app')

@section('title', 'Add New Product')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.products.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Products
    </a>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-3">Basic Information</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Product Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">SKU <span class="text-red-500">*</span></label>
                            <input type="text" name="sku" id="sku" value="{{ old('sku') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 uppercase @error('sku') border-red-500 @enderror">
                            @error('sku') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-red-500">*</span></label>
                            <select name="category_id" id="category_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('category_id') border-red-500 @enderror">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">Short Description</label>
                        <textarea name="short_description" id="short_description" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('short_description') }}</textarea>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Full Description <span class="text-red-500">*</span></label>
                        <textarea name="description" id="description" rows="5" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Herbal Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-3">Herbal Information (Optional)</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="ingredients" class="block text-sm font-medium text-gray-700 mb-1">Ingredients</label>
                            <textarea name="ingredients" id="ingredients" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('ingredients') }}</textarea>
                        </div>
                        <div>
                            <label for="benefits" class="block text-sm font-medium text-gray-700 mb-1">Health Benefits</label>
                            <textarea name="benefits" id="benefits" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('benefits') }}</textarea>
                        </div>
                        <div>
                            <label for="usage_instructions" class="block text-sm font-medium text-gray-700 mb-1">Directions for Use</label>
                            <textarea name="usage_instructions" id="usage_instructions" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('usage_instructions') }}</textarea>
                        </div>
                        <div>
                            <label for="storage_instructions" class="block text-sm font-medium text-gray-700 mb-1">Storage Instructions</label>
                            <textarea name="storage_instructions" id="storage_instructions" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('storage_instructions') }}</textarea>
                        </div>
                    </div>
                    <div>
                        <label for="precautions" class="block text-sm font-medium text-gray-700 mb-1">Precautions / Warnings</label>
                        <textarea name="precautions" id="precautions" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('precautions') }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-3">Pricing & Inventory</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Regular Price (₹) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('price') border-red-500 @enderror">
                        @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1">Sale Price (₹)</label>
                        <input type="number" step="0.01" name="sale_price" id="sale_price" value="{{ old('sale_price') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('sale_price') border-red-500 @enderror">
                        @error('sale_price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity <span class="text-red-500">*</span></label>
                        <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', 0) }}" required min="0" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 @error('stock_quantity') border-red-500 @enderror">
                        @error('stock_quantity') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-3">Search Engine Optimization</h3>
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        <!-- Sidebar Options -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-3">Status</h3>
                <div class="space-y-4">
                    <div>
                        <select name="status" id="status" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Published</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" name="featured" id="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <label for="featured" class="ml-2 block text-sm text-gray-700">Mark as Featured Product</label>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-3">Main Image <span class="text-red-500">*</span></h3>
                
                <div class="mt-2" x-data="imageViewer()">
                    <div class="flex items-center justify-center w-full">
                        <label for="main_image" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden relative @error('main_image') border-red-500 @enderror">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" x-show="!imageUrl">
                                <span class="material-symbols-outlined text-gray-400 mb-2 text-3xl">cloud_upload</span>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span></p>
                                <p class="text-xs text-gray-500">PNG, JPG or WEBP (MAX. 2MB)</p>
                            </div>
                            <img :src="imageUrl" x-show="imageUrl" class="absolute inset-0 w-full h-full object-cover">
                            <input id="main_image" name="main_image" type="file" required class="hidden" accept="image/*" @change="fileChosen">
                        </label>
                    </div>
                    @error('main_image') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b border-gray-100 pb-3">Product Gallery</h3>
                
                <div class="mt-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload multiple images</label>
                    <input type="file" name="gallery[]" multiple accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-xs text-gray-500 mt-2">You can select multiple files at once. Max 2MB per image.</p>
                </div>
            </div>
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg transition shadow-sm flex justify-center items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">save</span> Save Product
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    function imageViewer() {
        return {
            imageUrl: '',
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
@endsection

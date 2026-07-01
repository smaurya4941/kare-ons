@extends('admin.layouts.app')

@section('title', 'Add New Category')

@section('content')
<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('admin.categories.index') }}" class="text-[11px] font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1 w-fit">
        <span class="material-symbols-outlined text-[14px]">arrow_back</span> Back to Categories
    </a>
</div>

<form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-full">
    @csrf

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <!-- Main Form Details -->
        <div class="xl:col-span-2 space-y-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Category Details</h3>
                
                <div class="space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label for="name" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">
                                Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                                class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm @error('name') border-red-500 @enderror">
                            @error('name') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="parent_id" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">
                                Parent Category
                            </label>
                            <select name="parent_id" id="parent_id" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                                <option value="">None (Top Level)</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Description</label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('description') }}</textarea>
                        @error('description') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- SEO Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Search Engine Optimization</h3>
                <div class="space-y-3">
                    <div>
                        <label for="meta_title" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    </div>
                    <div>
                        <label for="meta_description" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="2" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('meta_description') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Status & Sorting</h3>
                <div class="space-y-3">
                    <div>
                        <label for="status" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Status</label>
                        <select name="status" id="status" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label for="sort_order" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Sort Order</label>
                        <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0"
                            class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Media</h3>
                
                <div class="space-y-4">
                    <div x-data="imageViewer('')">
                        <label class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Category Image</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="image" class="flex flex-col items-center justify-center w-full h-32 border border-gray-300 border-dashed rounded-md cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden relative transition">
                                <div class="flex flex-col items-center justify-center pt-4 pb-4" x-show="!imageUrl">
                                    <span class="material-symbols-outlined text-gray-400 mb-1 text-2xl">cloud_upload</span>
                                    <p class="mb-1 text-[11px] text-gray-500"><span class="font-semibold">Upload Image</span></p>
                                    <p class="text-[9px] text-gray-400">Max 2MB</p>
                                </div>
                                <img :src="imageUrl" x-show="imageUrl" class="absolute inset-0 w-full h-full object-cover">
                                <input id="image" name="image" type="file" class="hidden" accept="image/*" @change="fileChosen">
                            </label>
                        </div>
                        @error('image') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div x-data="imageViewer('')">
                        <label class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Banner Image</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="banner_image" class="flex flex-col items-center justify-center w-full h-24 border border-gray-300 border-dashed rounded-md cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden relative transition">
                                <div class="flex flex-col items-center justify-center pt-4 pb-4" x-show="!imageUrl">
                                    <span class="material-symbols-outlined text-gray-400 mb-1 text-2xl">panorama</span>
                                    <p class="mb-1 text-[11px] text-gray-500"><span class="font-semibold">Upload Banner</span></p>
                                    <p class="text-[9px] text-gray-400">Max 2MB</p>
                                </div>
                                <img :src="imageUrl" x-show="imageUrl" class="absolute inset-0 w-full h-full object-cover">
                                <input id="banner_image" name="banner_image" type="file" class="hidden" accept="image/*" @change="fileChosen">
                            </label>
                        </div>
                        @error('banner_image') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition shadow-sm flex justify-center items-center gap-1.5 text-[11px]">
                    <span class="material-symbols-outlined text-[16px]">save</span> Save Category
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
@endsection

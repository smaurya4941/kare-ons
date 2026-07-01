@extends('admin.layouts.app')

@section('title', 'Edit Brand: ' . $brand->name)

@section('content')
<div class="mb-4 flex items-center justify-between">
    <a href="{{ route('admin.brands.index') }}" class="text-[11px] font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1 w-fit">
        <span class="material-symbols-outlined text-[14px]">arrow_back</span> Back to Brands
    </a>
</div>

<form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-3xl">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <!-- Main Form Details -->
        <div class="xl:col-span-2 space-y-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Brand Details</h3>
                
                <div class="space-y-3">
                    <div>
                        <label for="name" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">
                            Brand Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $brand->name) }}" required autofocus
                            class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm @error('name') border-red-500 @enderror">
                        @error('name') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">{{ old('description', $brand->description) }}</textarea>
                        @error('description') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Status</h3>
                <div class="space-y-3">
                    <div>
                        <label for="status" class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Status</label>
                        <select name="status" id="status" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[11px] focus:ring-indigo-500 focus:border-indigo-500 shadow-sm bg-white">
                            <option value="1" {{ old('status', $brand->status) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $brand->status) == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <h3 class="text-xs font-semibold text-gray-800 mb-3 border-b border-gray-100 pb-2 uppercase tracking-wide">Media</h3>
                
                <div class="space-y-4">
                    <div x-data="imageViewer('{{ $brand->logo ? asset('storage/' . $brand->logo) : '' }}')">
                        <label class="block text-[10px] font-medium text-gray-700 mb-1 uppercase tracking-wider">Brand Logo</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="logo" class="flex flex-col items-center justify-center w-full h-32 border border-gray-300 border-dashed rounded-md cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden relative transition">
                                <div class="flex flex-col items-center justify-center pt-4 pb-4" x-show="!imageUrl">
                                    <span class="material-symbols-outlined text-gray-400 mb-1 text-2xl">cloud_upload</span>
                                    <p class="mb-1 text-[11px] text-gray-500"><span class="font-semibold">Upload Logo</span></p>
                                    <p class="text-[9px] text-gray-400">Max 2MB</p>
                                </div>
                                <img :src="imageUrl" x-show="imageUrl" class="absolute inset-0 w-full h-full object-contain p-2">
                                <input id="logo" name="logo" type="file" class="hidden" accept="image/*" @change="fileChosen">
                            </label>
                        </div>
                        @error('logo') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md transition shadow-sm flex justify-center items-center gap-1.5 text-[11px]">
                    <span class="material-symbols-outlined text-[16px]">save</span> Update Brand
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

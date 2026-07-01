@extends('admin.layouts.app')

@section('title', $banner->exists ? 'Edit Banner' : 'Create Banner')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.banners.index') }}" class="text-[11px] font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1 w-fit">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Banners
    </a>
</div>

<form action="{{ $banner->exists ? route('admin.banners.update', $banner->id) : route('admin.banners.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-4xl">
    @csrf
    @if($banner->exists) @method('PUT') @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {{-- Left Column --}}
        <div class="space-y-5">
            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Banner Title</label>
                <input type="text" name="title" value="{{ old('title', $banner->title) }}" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. Summer Sale 2026">
                @error('title') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Banner Type / Placement *</label>
                <select name="type" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="slider" {{ old('type', $banner->type) == 'slider' ? 'selected' : '' }}>Homepage Main Slider</option>
                    <option value="offer" {{ old('type', $banner->type) == 'offer' ? 'selected' : '' }}>Promotional Offer Banner</option>
                    <option value="desktop" {{ old('type', $banner->type) == 'desktop' ? 'selected' : '' }}>Desktop Specific Banner</option>
                    <option value="mobile" {{ old('type', $banner->type) == 'mobile' ? 'selected' : '' }}>Mobile Specific Banner</option>
                </select>
                @error('type') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Target Link URL</label>
                <input type="url" name="link" value="{{ old('link', $banner->link) }}" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500" placeholder="https://...">
                <p class="text-[10px] text-gray-400 mt-1">Where the user is taken when they click the banner.</p>
                @error('link') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Sort Order *</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500" min="0">
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Status *</label>
                    <select name="status" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="1" {{ old('status', $banner->status) == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status', $banner->status) == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Right Column (Images) --}}
        <div class="space-y-6">
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-wider mb-2 flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">desktop_windows</span> Desktop Image *</label>
                <p class="text-[10px] text-gray-500 mb-3">Recommended size: 1920x600px (WebP, JPG, PNG)</p>
                
                @if($banner->desktop_image)
                    <div class="mb-3 rounded border border-gray-200 overflow-hidden bg-white">
                        <img src="{{ asset('storage/' . $banner->desktop_image) }}" class="w-full h-auto object-cover max-h-32">
                    </div>
                @endif
                
                <input type="file" name="desktop_image" accept="image/*" class="w-full text-[11px] file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-[11px] file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" {{ !$banner->exists ? 'required' : '' }}>
                @error('desktop_image') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <label class="block text-[11px] font-bold text-gray-800 uppercase tracking-wider mb-2 flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">smartphone</span> Mobile Image (Optional)</label>
                <p class="text-[10px] text-gray-500 mb-3">Recommended size: 800x800px. If empty, the desktop image will scale down.</p>
                
                @if($banner->mobile_image)
                    <div class="mb-3 rounded border border-gray-200 overflow-hidden bg-white w-24">
                        <img src="{{ asset('storage/' . $banner->mobile_image) }}" class="w-full h-auto object-cover">
                    </div>
                @endif
                
                <input type="file" name="mobile_image" accept="image/*" class="w-full text-[11px] file:mr-4 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-[11px] file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                @error('mobile_image') <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

    </div>

    <div class="mt-8 pt-5 border-t border-gray-100 flex justify-end gap-3">
        <a href="{{ route('admin.banners.index') }}" class="bg-white border border-gray-300 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Cancel</a>
        <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shadow-sm flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">save</span> {{ $banner->exists ? 'Update Banner' : 'Publish Banner' }}
        </button>
    </div>
</form>
@endsection

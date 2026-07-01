@extends('admin.layouts.app')

@section('title', 'Banner Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-sm text-gray-500">Manage homepage sliders, promotional banners, and category hero images.</p>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition text-sm flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">add</span> Add Banner
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium w-16">Preview</th>
                    <th class="px-6 py-4 font-medium">Details</th>
                    <th class="px-6 py-4 font-medium">Placement / Type</th>
                    <th class="px-6 py-4 font-medium">Link</th>
                    <th class="px-6 py-4 font-medium">Status & Order</th>
                    <th class="px-6 py-4 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($banners as $banner)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4">
                        <div class="w-16 h-10 rounded border border-gray-200 overflow-hidden bg-gray-100">
                            <img src="{{ asset('storage/' . $banner->desktop_image) }}" class="w-full h-full object-cover">
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-[12px] font-semibold text-gray-800">{{ $banner->title ?: 'Untitled Banner' }}</p>
                        @if($banner->mobile_image)
                            <span class="text-[9px] text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded flex items-center gap-1 w-fit mt-1"><span class="material-symbols-outlined text-[10px]">smartphone</span> Has mobile variant</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-gray-100 text-gray-700">
                            {{ str_replace('_', ' ', $banner->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($banner->link)
                            <a href="{{ $banner->link }}" target="_blank" class="text-[11px] text-indigo-600 hover:underline flex items-center gap-1 line-clamp-1"><span class="material-symbols-outlined text-[14px]">link</span> {{ Str::limit($banner->link, 20) }}</a>
                        @else
                            <span class="text-[11px] text-gray-400 italic">No link</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col gap-1">
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium uppercase tracking-wider w-fit {{ $banner->status ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                {{ $banner->status ? 'Active' : 'Inactive' }}
                            </span>
                            <span class="text-[10px] text-gray-500">Order: {{ $banner->sort_order }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.banners.edit', $banner->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-2 py-1 rounded text-[11px] font-medium transition hover:bg-indigo-100">Edit</a>
                            <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Delete this banner?');" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-2 py-1 rounded text-[11px] font-medium transition hover:bg-red-100">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-500 text-sm">No banners found. <a href="{{ route('admin.banners.create') }}" class="text-indigo-600 hover:underline">Create one</a></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

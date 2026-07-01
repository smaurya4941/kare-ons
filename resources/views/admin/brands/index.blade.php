@extends('admin.layouts.app')

@section('title', 'Brands')

@section('content')
<div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <form action="{{ route('admin.brands.index') }}" method="GET" class="flex gap-2">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-500">
                <span class="material-symbols-outlined text-[16px]">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search brands..."
                class="pl-8 pr-3 py-1.5 border border-gray-200 rounded-md text-[11px] focus:ring-indigo-500 focus:border-indigo-500 w-56 shadow-sm">
        </div>
        <button type="submit" class="bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-md text-[11px] font-medium hover:bg-gray-50 transition shadow-sm">Filter</button>
        @if(request('search'))
            <a href="{{ route('admin.brands.index') }}" class="text-[11px] text-gray-500 hover:text-indigo-600 flex items-center px-2">Clear</a>
        @endif
    </form>

    <a href="{{ route('admin.brands.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-md text-[11px] font-medium transition flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-[16px]">add</span>
        Add Brand
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3 font-medium">Brand</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($brands as $brand)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-2.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded border border-gray-200 overflow-hidden flex-shrink-0 bg-gray-50 flex items-center justify-center">
                                @if($brand->logo)
                                    <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-gray-400 text-[16px]">branding_watermark</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-gray-800">{{ $brand->name }}</p>
                                <p class="text-[9px] text-gray-400 font-mono">{{ $brand->slug }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-2.5">
                        @if($brand->status)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-medium bg-emerald-100 text-emerald-800">Active</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-medium bg-gray-100 text-gray-800">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5 text-right">
                        <div class="flex justify-end gap-1.5">
                            <a href="{{ route('admin.brands.edit', $brand->id) }}" class="text-gray-400 hover:text-indigo-600 transition" title="Edit">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                            </a>
                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Delete brand \'{{ addslashes($brand->name) }}\'? This cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-600 transition" title="Delete">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center text-gray-500 text-[11px]">
                        No brands found.
                        <a href="{{ route('admin.brands.create') }}" class="text-indigo-600 hover:underline ml-1">Create the first one.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($brands->hasPages())
    <div class="px-4 py-3 border-t border-gray-100 text-[11px]">
        {{ $brands->links() }}
    </div>
    @endif
</div>
@endsection

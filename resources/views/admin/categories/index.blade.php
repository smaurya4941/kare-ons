@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')
<div class="mb-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
    <form action="{{ route('admin.categories.index') }}" method="GET" class="flex gap-2">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-2.5 flex items-center text-gray-500">
                <span class="material-symbols-outlined text-[16px]">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search categories..."
                class="pl-8 pr-3 py-1.5 border border-gray-200 rounded-md text-[11px] focus:ring-indigo-500 focus:border-indigo-500 w-56 shadow-sm">
        </div>
        <button type="submit" class="bg-white border border-gray-200 text-gray-700 px-3 py-1.5 rounded-md text-[11px] font-medium hover:bg-gray-50 transition shadow-sm">Filter</button>
        @if(request('search'))
            <a href="{{ route('admin.categories.index') }}" class="text-[11px] text-gray-500 hover:text-indigo-600 flex items-center px-2">Clear</a>
        @endif
    </form>

    <a href="{{ route('admin.categories.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-md text-[11px] font-medium transition flex items-center gap-1.5 shadow-sm">
        <span class="material-symbols-outlined text-[16px]">add</span>
        Add Category
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3 font-medium">Category</th>
                    <th class="px-4 py-3 font-medium">Parent</th>
                    <th class="px-4 py-3 font-medium text-center">Sort</th>
                    <th class="px-4 py-3 font-medium">Products</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($categories as $category)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-2.5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded border border-gray-200 overflow-hidden flex-shrink-0 bg-gray-50">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-gray-400 w-full h-full flex items-center justify-center text-[16px]">category</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-[11px] font-semibold text-gray-800">{{ $category->name }}</p>
                                <p class="text-[9px] text-gray-400 font-mono">{{ $category->slug }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-2.5">
                        @if($category->parent)
                            <span class="text-[11px] text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md">{{ $category->parent->name }}</span>
                        @else
                            <span class="text-[10px] text-gray-400 italic">None</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5 text-center text-[11px] text-gray-600">
                        {{ $category->sort_order }}
                    </td>
                    <td class="px-4 py-2.5 text-[11px] text-gray-600">{{ $category->products()->count() }}</td>
                    <td class="px-4 py-2.5">
                        @if($category->status)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-medium bg-emerald-100 text-emerald-800">Active</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[9px] font-medium bg-gray-100 text-gray-800">Inactive</span>
                        @endif
                    </td>
                    <td class="px-4 py-2.5 text-right">
                        <div class="flex justify-end gap-1.5">
                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="text-gray-400 hover:text-indigo-600 transition" title="Edit">
                                <span class="material-symbols-outlined text-[16px]">edit</span>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block"
                                  onsubmit="return confirm('Delete category \'{{ addslashes($category->name) }}\'? This cannot be undone.');">
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
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 text-[11px]">
                        No categories found.
                        <a href="{{ route('admin.categories.create') }}" class="text-indigo-600 hover:underline ml-1">Create the first one.</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($categories->hasPages())
    <div class="px-4 py-3 border-t border-gray-100 text-[11px]">
        {{ $categories->links() }}
    </div>
    @endif
</div>
@endsection

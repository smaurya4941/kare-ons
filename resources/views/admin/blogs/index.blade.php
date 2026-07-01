@extends('admin.layouts.app')

@section('title', 'Blog Posts')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <p class="text-sm text-gray-500">Manage articles and news for your storefront</p>
    </div>
    <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">
        <span class="material-symbols-outlined text-[18px]">add</span>
        New Post
    </a>
</div>

{{-- Filters --}}
<form action="{{ route('admin.blogs.index') }}" method="GET" class="mb-6">
    <div class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 flex-1 max-w-xs">
        <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Published</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Draft</option>
        </select>
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">Filter</button>
        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.blogs.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">Clear</a>
        @endif
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-white border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Post</th>
                <th class="px-6 py-4 font-medium">Category</th>
                <th class="px-6 py-4 font-medium">Author</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium">Published</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($blogs as $blog)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        @if($blog->featured_image)
                            <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="" class="w-12 h-12 rounded object-cover flex-shrink-0">
                        @else
                            <div class="w-12 h-12 rounded bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <span class="material-symbols-outlined text-gray-300 text-[20px]">article</span>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 line-clamp-1">{{ $blog->title }}</p>
                            <p class="text-xs text-gray-400 line-clamp-1">{{ $blog->slug }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $blog->category ?? '—' }}</td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $blog->author->name ?? 'Unknown' }}</td>
                <td class="px-6 py-4">
                    @if($blog->status)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Published</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Draft</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $blog->published_at ? $blog->published_at->format('M d, Y') : '—' }}
                </td>
                <td class="px-6 py-4 text-sm text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.blogs.show', $blog->id) }}" class="text-gray-500 hover:text-gray-800 font-medium">View</a>
                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                        <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST"
                              onsubmit="return confirm('Delete this blog post?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-sm">
                    No blog posts yet. <a href="{{ route('admin.blogs.create') }}" class="text-indigo-600 hover:underline">Write your first post.</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($blogs->hasPages())
    <div class="mt-6">
        {{ $blogs->links() }}
    </div>
@endif
@endsection

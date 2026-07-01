@extends('admin.layouts.app')

@section('title', 'CMS Pages')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-sm text-gray-500">Manage static pages like About Us, Privacy Policy, Terms, etc.</p>
    </div>
    <a href="{{ route('admin.pages.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition text-sm flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px]">add</span> Add Page
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">Page Title</th>
                <th class="px-6 py-4 font-medium">URL Slug</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pages as $page)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <p class="text-[12px] font-semibold text-gray-800">{{ $page->title }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="text-[11px] text-gray-500 bg-gray-100 px-2 py-1 rounded">/{{ $page->slug }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $page->status ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                        {{ $page->status ? 'Published' : 'Draft' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="text-indigo-600 hover:underline text-[11px] font-medium mr-3">Edit</a>
                    <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this page?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-[11px] font-medium">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500 text-sm">No CMS pages found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

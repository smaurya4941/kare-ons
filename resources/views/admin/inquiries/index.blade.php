@extends('admin.layouts.app')

@section('title', 'Contact Inquiries')

@section('content')
<div class="mb-6">
    <p class="text-sm text-gray-500">Messages submitted through the storefront contact form</p>
</div>

{{-- Filters --}}
<form action="{{ route('admin.inquiries.index') }}" method="GET" class="mb-6">
    <div class="flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email or subject..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 flex-1 max-w-xs">
        <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All</option>
            <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
            <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
        </select>
        <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">Filter</button>
        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.inquiries.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">Clear</a>
        @endif
    </div>
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-white border-b border-gray-100 text-xs text-gray-500 uppercase tracking-wider">
                <th class="px-6 py-4 font-medium">From</th>
                <th class="px-6 py-4 font-medium">Subject</th>
                <th class="px-6 py-4 font-medium">Received</th>
                <th class="px-6 py-4 font-medium">Status</th>
                <th class="px-6 py-4 font-medium text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($inquiries as $inquiry)
            <tr class="hover:bg-gray-50 transition {{ $inquiry->is_read ? '' : 'bg-indigo-50/40' }}">
                <td class="px-6 py-4">
                    <p class="text-sm font-semibold text-gray-800">{{ $inquiry->name }}</p>
                    <p class="text-xs text-gray-500">{{ $inquiry->email }}</p>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">{{ $inquiry->subject ?: '—' }}</td>
                <td class="px-6 py-4 text-sm text-gray-500">{{ $inquiry->created_at->format('M d, Y H:i') }}</td>
                <td class="px-6 py-4">
                    @if($inquiry->is_read)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Read</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Unread</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm text-right">
                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route('admin.inquiries.show', $inquiry->id) }}" class="text-indigo-600 hover:text-indigo-900 font-medium">View</a>
                        <form action="{{ route('admin.inquiries.destroy', $inquiry->id) }}" method="POST" onsubmit="return confirm('Delete this inquiry?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-400 text-sm">No inquiries found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($inquiries->hasPages())
    <div class="mt-6">
        {{ $inquiries->links() }}
    </div>
@endif
@endsection

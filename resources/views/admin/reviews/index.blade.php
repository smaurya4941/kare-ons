@extends('admin.layouts.app')

@section('title', 'Product Reviews')

@section('content')
<div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <form action="{{ route('admin.reviews.index') }}" method="GET" class="flex flex-wrap gap-3">
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                <span class="material-symbols-outlined text-[18px]">search</span>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search reviews..."
                class="pl-9 pr-4 py-1.5 border border-gray-200 rounded-md text-[11px] focus:ring-indigo-500 focus:border-indigo-500 w-64">
        </div>
        
        <select name="status" class="py-1.5 px-3 border border-gray-200 rounded-md text-[11px] focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Approved</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending/Hidden</option>
        </select>
        
        <select name="rating" class="py-1.5 px-3 border border-gray-200 rounded-md text-[11px] focus:ring-indigo-500 focus:border-indigo-500">
            <option value="">All Ratings</option>
            @for($i=5; $i>=1; $i--)
                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
            @endfor
        </select>

        <button type="submit" class="bg-gray-100 border border-gray-200 text-gray-700 px-3 py-1.5 rounded-md text-[11px] font-medium hover:bg-gray-200 transition">Filter</button>
        @if(request()->anyFilled(['search', 'status', 'rating']))
            <a href="{{ route('admin.reviews.index') }}" class="text-[11px] text-gray-500 hover:text-indigo-600 flex items-center px-2 font-medium">Clear</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-[10px] text-gray-500 uppercase tracking-wider">
                    <th class="px-4 py-3 font-medium">Product / User</th>
                    <th class="px-4 py-3 font-medium">Rating</th>
                    <th class="px-4 py-3 font-medium w-1/3">Review</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3">
                        <p class="text-[11px] font-semibold text-gray-800 line-clamp-1" title="{{ $review->product->name ?? 'Deleted' }}">{{ $review->product->name ?? 'Deleted Product' }}</p>
                        <p class="text-[9px] text-gray-500 flex items-center gap-1 mt-0.5">
                            <span class="material-symbols-outlined text-[12px]">person</span> 
                            {{ $review->user->name ?? 'Deleted User' }}
                        </p>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center text-amber-400">
                            @for($i=1; $i<=5; $i++)
                                <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' {{ $i <= $review->rating ? '1' : '0' }}">{{ $i <= $review->rating ? 'star' : 'star_rate' }}</span>
                            @endfor
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-[11px] font-semibold text-gray-800 line-clamp-1">{{ $review->title }}</p>
                        <p class="text-[10px] text-gray-500 line-clamp-2 mt-0.5">{{ $review->review }}</p>
                        @if($review->images && count($review->images) > 0)
                            <span class="mt-1 inline-flex items-center gap-1 text-[9px] text-indigo-600 bg-indigo-50 px-1.5 py-0.5 rounded font-medium"><span class="material-symbols-outlined text-[12px]">image</span> {{ count($review->images) }} attached</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium uppercase tracking-wider {{ $review->status ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ $review->status ? 'Approved' : 'Pending' }}
                        </span>
                        @if($review->is_verified_purchase)
                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-medium uppercase tracking-wider bg-blue-100 text-blue-800 ml-1" title="Verified Purchase"><span class="material-symbols-outlined text-[10px] mr-0.5">verified</span> Verified</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.reviews.show', $review->id) }}" class="text-indigo-600 hover:text-indigo-900 text-[11px] font-medium bg-indigo-50 px-2 py-1 rounded hover:bg-indigo-100 transition">Manage</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500 text-[11px]">No reviews found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($reviews->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $reviews->links() }}
    </div>
    @endif
</div>
@endsection

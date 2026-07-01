@extends('admin.layouts.app')

@section('title', 'Manage Review')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.reviews.index') }}" class="text-[11px] font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1 w-fit">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Reviews
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Review Details --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-start">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">{{ $review->title ?? 'Untitled Review' }}</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="flex items-center text-amber-400">
                            @for($i=1; $i<=5; $i++)
                                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' {{ $i <= $review->rating ? '1' : '0' }}">{{ $i <= $review->rating ? 'star' : 'star_rate' }}</span>
                            @endfor
                        </div>
                        <span class="text-[11px] text-gray-500 font-medium">{{ $review->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-medium uppercase tracking-wider {{ $review->status ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $review->status ? 'Approved' : 'Pending/Hidden' }}
                    </span>
                    @if($review->is_verified_purchase)
                    <span class="inline-flex items-center px-2 py-1 rounded text-[10px] font-medium uppercase tracking-wider bg-blue-100 text-blue-800">
                        <span class="material-symbols-outlined text-[12px] mr-1">verified</span> Verified
                    </span>
                    @endif
                </div>
            </div>
            
            <div class="p-5">
                <p class="text-[13px] text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $review->review }}</p>
                
                @if($review->images && count($review->images) > 0)
                <div class="mt-5">
                    <h4 class="text-[11px] font-bold text-gray-800 uppercase tracking-wider mb-3">Attached Images</h4>
                    <div class="flex flex-wrap gap-3">
                        @foreach($review->images as $img)
                            <a href="{{ asset('storage/' . $img) }}" target="_blank" class="block w-24 h-24 rounded border border-gray-200 overflow-hidden hover:border-indigo-400 transition">
                                <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            
            @if($review->admin_reply)
            <div class="bg-indigo-50/50 border-t border-indigo-100 p-5">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-indigo-500 text-[20px] mt-0.5">reply</span>
                    <div>
                        <h4 class="text-[11px] font-bold text-indigo-900 uppercase tracking-wider mb-1">Your Reply</h4>
                        <p class="text-[12px] text-indigo-800 leading-relaxed">{{ $review->admin_reply }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Administration Form --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-800 text-sm mb-4 flex items-center gap-2"><span class="material-symbols-outlined text-[18px] text-gray-500">admin_panel_settings</span> Moderation Tools</h3>
            <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST" class="space-y-5">
                @csrf @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Visibility Status</label>
                        <select name="status" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1" {{ $review->status ? 'selected' : '' }}>Approved (Visible to public)</option>
                            <option value="0" {{ !$review->status ? 'selected' : '' }}>Hidden / Pending</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Purchase Verification</label>
                        <select name="is_verified_purchase" class="w-full border border-gray-200 rounded-md px-3 py-1.5 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1" {{ $review->is_verified_purchase ? 'selected' : '' }}>Verified Purchase</option>
                            <option value="0" {{ !$review->is_verified_purchase ? 'selected' : '' }}>Not Verified</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Public Admin Reply (Optional)</label>
                    <textarea name="admin_reply" rows="3" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400" placeholder="Type your public response here to engage with the customer...">{{ old('admin_reply', $review->admin_reply) }}</textarea>
                    <p class="text-[9px] text-gray-400 mt-1">This reply will be visible directly below the customer's review on the product page.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-1.5 px-5 rounded-md transition text-[12px] shadow-sm">
                        Save Moderation Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Context Panel (Right) --}}
    <div class="space-y-6">
        {{-- Product Info --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-semibold text-gray-800 text-xs uppercase tracking-wider">Product</h3>
            </div>
            <div class="p-4 flex gap-3">
                <div class="w-16 h-16 rounded bg-gray-100 border border-gray-200 flex-shrink-0 overflow-hidden">
                    @if($review->product && $review->product->main_image)
                        <img src="{{ asset('storage/' . $review->product->main_image) }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div>
                    <a href="{{ $review->product ? route('admin.products.edit', $review->product_id) : '#' }}" class="text-[12px] font-semibold text-indigo-600 hover:text-indigo-800 hover:underline leading-tight block mb-1">
                        {{ $review->product->name ?? 'Unknown Product' }}
                    </a>
                    <p class="text-[10px] text-gray-500">₹{{ number_format($review->product->price ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Customer Info --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-semibold text-gray-800 text-xs uppercase tracking-wider">Customer</h3>
            </div>
            <div class="p-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center font-bold text-indigo-600 text-sm flex-shrink-0">
                    {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <a href="{{ $review->user ? route('admin.customers.show', $review->user_id) : '#' }}" class="text-[12px] font-semibold text-indigo-600 hover:text-indigo-800 hover:underline leading-tight block mb-0.5">
                        {{ $review->user->name ?? 'Unknown User' }}
                    </a>
                    <p class="text-[10px] text-gray-500 font-mono">{{ $review->user->email ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="bg-red-50 rounded-lg border border-red-100 p-4">
            <h3 class="font-semibold text-red-800 text-[11px] uppercase tracking-wider mb-2 flex items-center gap-1"><span class="material-symbols-outlined text-[14px]">warning</span> Danger Zone</h3>
            <p class="text-[10px] text-red-600 mb-3">Permanently delete this review and its images.</p>
            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this review?');">
                @csrf @method('DELETE')
                <button type="submit" class="w-full bg-white border border-red-200 text-red-600 hover:bg-red-600 hover:text-white font-medium py-1.5 px-3 rounded transition text-[11px]">
                    Delete Review
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

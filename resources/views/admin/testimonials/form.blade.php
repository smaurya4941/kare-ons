@extends('admin.layouts.app')

@section('title', $testimonial->exists ? 'Edit Testimonial' : 'Create Testimonial')

@section('content')
<form action="{{ $testimonial->exists ? route('admin.testimonials.update', $testimonial->id) : route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    @csrf
    @if($testimonial->exists) @method('PUT') @endif

    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Customer Name *</label>
                <input type="text" name="name" value="{{ old('name', $testimonial->name) }}" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px]">
            </div>
            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Role / Subtitle</label>
                <input type="text" name="role" value="{{ old('role', $testimonial->role) }}" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px]" placeholder="e.g. Verified Buyer">
            </div>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Review Content *</label>
            <textarea name="content" required rows="4" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px]">{{ old('content', $testimonial->content) }}</textarea>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Rating *</label>
                <select name="rating" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px]">
                    @foreach(range(5, 1) as $r)
                        <option value="{{ $r }}" {{ old('rating', $testimonial->rating ?? 5) == $r ? 'selected' : '' }}>{{ $r }} Stars</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Status *</label>
                <select name="status" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px]">
                    <option value="1" {{ old('status', $testimonial->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $testimonial->status ?? 1) == 0 ? 'selected' : '' }}>Hidden</option>
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Sort Order *</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px]">
            </div>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-600 uppercase mb-1">Customer Avatar (Optional)</label>
            @if($testimonial->avatar)
                <img src="{{ asset('storage/' . $testimonial->avatar) }}" class="w-16 h-16 rounded-full object-cover mb-2 border border-gray-200">
            @endif
            <input type="file" name="avatar" accept="image/*" class="w-full text-[11px] file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-[11px] file:bg-indigo-50 file:text-indigo-700">
        </div>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-100 flex gap-2">
        <button type="submit" class="bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium">Save Testimonial</button>
        <a href="{{ route('admin.testimonials.index') }}" class="bg-gray-100 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium">Cancel</a>
    </div>
</form>
@endsection

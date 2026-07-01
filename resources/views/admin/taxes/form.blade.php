@extends('admin.layouts.app')

@section('title', $tax->exists ? 'Edit Tax Slab' : 'Create Tax Slab')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.taxes.index') }}" class="text-[11px] font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1 w-fit">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Taxes
    </a>
</div>

<form action="{{ $tax->exists ? route('admin.taxes.update', $tax->id) : route('admin.taxes.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-xl">
    @csrf
    @if($tax->exists) @method('PUT') @endif

    <div class="space-y-4">
        <div>
            <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Tax Name *</label>
            <input type="text" name="name" value="{{ old('name', $tax->name) }}" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. GST 18%">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Rate (%) *</label>
                <input type="number" step="0.01" name="rate" value="{{ old('rate', $tax->rate) }}" required min="0" max="100" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Status *</label>
                <select name="status" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="1" {{ old('status', $tax->status ?? 1) == 1 ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('status', $tax->status ?? 1) == 0 ? 'selected' : '' }}>Disabled</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end gap-2">
            <a href="{{ route('admin.taxes.index') }}" class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium">Cancel</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                {{ $tax->exists ? 'Update Tax Slab' : 'Create Tax Slab' }}
            </button>
        </div>
    </div>
</form>
@endsection

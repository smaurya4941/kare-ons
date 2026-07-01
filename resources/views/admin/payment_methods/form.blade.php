@extends('admin.layouts.app')

@section('title', 'Configure Payment Method')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.payment_methods.index') }}" class="text-[11px] font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1 w-fit">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Payment Methods
    </a>
</div>

<form action="{{ route('admin.payment_methods.update', $method->id) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-xl">
    @csrf
    @method('PUT')

    <div class="space-y-4">
        <div>
            <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Display Name *</label>
            <input type="text" name="name" value="{{ old('name', $method->name) }}" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Customer Instructions</label>
            <textarea name="instructions" rows="4" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500" placeholder="Instructions shown to customer at checkout (e.g. 'Please pay using exact change')">{{ old('instructions', $method->instructions) }}</textarea>
        </div>

        <div>
            <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Status *</label>
            <select name="status" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">
                <option value="1" {{ old('status', $method->status) == 1 ? 'selected' : '' }}>Enabled</option>
                <option value="0" {{ old('status', $method->status) == 0 ? 'selected' : '' }}>Disabled</option>
            </select>
        </div>

        <div class="pt-4 border-t border-gray-100 flex justify-end gap-2">
            <a href="{{ route('admin.payment_methods.index') }}" class="bg-gray-100 text-gray-700 px-5 py-2.5 rounded-lg text-sm font-medium">Cancel</a>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm">
                Save Configuration
            </button>
        </div>
    </div>
</form>

@if($method->code === 'razorpay')
<div class="mt-6 bg-blue-50 text-blue-800 p-4 rounded-xl text-sm border border-blue-100 max-w-xl">
    <div class="flex gap-2">
        <span class="material-symbols-outlined">info</span>
        <div>
            <p class="font-bold mb-1">Razorpay API Keys</p>
            <p>To configure your Razorpay Key ID and Secret, please go to <a href="{{ route('admin.settings.index') }}" class="underline font-medium">Settings -> Payment Gateway</a>.</p>
        </div>
    </div>
</div>
@endif
@endsection

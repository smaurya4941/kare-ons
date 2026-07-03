@extends('admin.layouts.app')

@section('title', 'Inquiry Details')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.inquiries.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Inquiries
    </a>
    <div class="flex items-center gap-3">
        <form action="{{ route('admin.inquiries.destroy', $inquiry->id) }}" method="POST" onsubmit="return confirm('Delete this inquiry?');">
            @csrf @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 border border-red-200 text-red-600 hover:bg-red-50 font-medium px-4 py-2 rounded-lg text-sm transition">
                <span class="material-symbols-outlined text-[18px]">delete</span> Delete
            </button>
        </form>
    </div>
</div>

<div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-start justify-between border-b border-gray-100 pb-4 mb-4">
        <div>
            <h2 class="text-lg font-bold text-gray-800">{{ $inquiry->name }}</h2>
            <a href="mailto:{{ $inquiry->email }}" class="text-sm text-indigo-600 hover:underline">{{ $inquiry->email }}</a>
        </div>
        <span class="text-xs text-gray-400">{{ $inquiry->created_at->format('M d, Y \a\t H:i') }}</span>
    </div>

    @if($inquiry->subject)
        <div class="mb-4">
            <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Subject</p>
            <p class="text-sm font-medium text-gray-800">{{ $inquiry->subject }}</p>
        </div>
    @endif

    <div>
        <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-1">Message</p>
        <p class="text-sm text-gray-700 whitespace-pre-line leading-relaxed">{{ $inquiry->message }}</p>
    </div>
</div>

{{-- Reply by email (sends via the store's configured SMTP) --}}
<div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-100 p-6 mt-6">
    <h3 class="text-base font-bold text-gray-800 mb-1 flex items-center gap-2">
        <span class="material-symbols-outlined text-[20px] text-indigo-600">reply</span> Reply by Email
    </h3>
    <p class="text-xs text-gray-500 mb-4">This will be sent to <span class="font-medium text-gray-700">{{ $inquiry->email }}</span> from your store's email address.</p>

    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.inquiries.reply', $inquiry->id) }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Subject</label>
            <input type="text" name="subject" required
                   value="{{ old('subject', 'Re: ' . ($inquiry->subject ?: 'Your inquiry')) }}"
                   class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('subject') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-600 mb-1">Message</label>
            <textarea name="message" rows="6" required
                      placeholder="Type your reply here..."
                      class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('message') }}</textarea>
            @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <button type="submit"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">
            <span class="material-symbols-outlined text-[18px]">send</span> Send Reply
        </button>
    </form>
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Inquiry Details')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.inquiries.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Inquiries
    </a>
    <div class="flex items-center gap-3">
        <a href="mailto:{{ $inquiry->email }}?subject=Re: {{ $inquiry->subject ?: 'Your inquiry' }}"
           class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">
            <span class="material-symbols-outlined text-[18px]">reply</span> Reply by Email
        </a>
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
@endsection

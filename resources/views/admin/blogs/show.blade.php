@extends('admin.layouts.app')

@section('title', 'View Blog Post')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.blogs.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Blog Posts
    </a>
    <div class="flex items-center gap-3">
        <a href="{{ route('blog.show', $blog->slug) }}" target="_blank" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
            <span class="material-symbols-outlined text-[18px]">open_in_new</span> View on site
        </a>
        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded-lg text-sm transition">
            <span class="material-symbols-outlined text-[18px]">edit</span> Edit
        </a>
    </div>
</div>

<div class="max-w-4xl bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    @if($blog->featured_image)
        <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="" class="w-full h-64 object-cover">
    @endif
    <div class="p-8">
        <div class="flex items-center gap-3 mb-4 text-xs">
            @if($blog->status)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-emerald-100 text-emerald-800">Published</span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full font-medium bg-gray-100 text-gray-600">Draft</span>
            @endif
            @if($blog->category)
                <span class="text-gray-500">{{ $blog->category }}</span>
            @endif
            <span class="text-gray-400">
                {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Not published' }}
                · by {{ $blog->author->name ?? 'Unknown' }}
            </span>
        </div>

        <h1 class="text-2xl font-bold text-gray-900 mb-3">{{ $blog->title }}</h1>

        @if($blog->excerpt)
            <p class="text-gray-500 italic mb-6">{{ $blog->excerpt }}</p>
        @endif

        <div class="prose max-w-none text-gray-700 text-sm leading-relaxed">
            {!! $blog->content !!}
        </div>
    </div>
</div>
@endsection

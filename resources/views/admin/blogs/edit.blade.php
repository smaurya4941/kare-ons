@extends('admin.layouts.app')

@section('title', 'Edit Blog Post')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.blogs.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Blog Posts
    </a>
    <a href="{{ route('admin.blogs.show', $blog->id) }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">visibility</span> Preview
    </a>
</div>

@include('admin.blogs._form', ['blog' => $blog])
@endsection

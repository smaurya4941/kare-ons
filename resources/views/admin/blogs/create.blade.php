@extends('admin.layouts.app')

@section('title', 'New Blog Post')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.blogs.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back to Blog Posts
    </a>
</div>

@include('admin.blogs._form', ['blog' => new \App\Models\Blog()])
@endsection

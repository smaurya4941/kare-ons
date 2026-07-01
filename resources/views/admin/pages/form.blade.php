@extends('admin.layouts.app')

@section('title', $page->exists ? 'Edit Page' : 'Create Page')

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.pages.index') }}" class="text-[11px] font-medium text-gray-500 hover:text-indigo-600 flex items-center gap-1 w-fit">
        <span class="material-symbols-outlined text-[16px]">arrow_back</span> Back to Pages
    </a>
</div>

<form action="{{ $page->exists ? route('admin.pages.update', $page->id) : route('admin.pages.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-5xl">
    @csrf
    @if($page->exists) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Page Title *</label>
                <input type="text" name="title" value="{{ old('title', $page->title) }}" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Content</label>
                <textarea name="content" id="tinymce-editor" rows="15" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px] focus:ring-indigo-500 focus:border-indigo-500">{{ old('content', $page->content) }}</textarea>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                <label class="block text-[11px] font-bold text-gray-600 uppercase tracking-wider mb-1">Status *</label>
                <select name="status" required class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px]">
                    <option value="1" {{ old('status', $page->status ?? 1) == 1 ? 'selected' : '' }}>Published</option>
                    <option value="0" {{ old('status', $page->status ?? 1) == 0 ? 'selected' : '' }}>Draft</option>
                </select>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                <h4 class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-3">SEO Settings</h4>
                
                <div class="mb-3">
                    <label class="block text-[11px] font-medium text-gray-700 mb-1">URL Slug</label>
                    <input type="text" name="slug" value="{{ old('slug', $page->slug) }}" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px]" placeholder="Auto-generated if empty">
                </div>

                <div class="mb-3">
                    <label class="block text-[11px] font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px]">
                </div>

                <div>
                    <label class="block text-[11px] font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" rows="3" class="w-full border border-gray-200 rounded-md px-3 py-2 text-[12px]">{{ old('meta_description', $page->meta_description) }}</textarea>
                </div>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition shadow-sm w-full">
                    {{ $page->exists ? 'Update Page' : 'Create Page' }}
                </button>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#tinymce-editor',
        height: 400,
        menubar: false,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table code help wordcount',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
        skin: "oxide",
        content_css: "default"
    });
</script>
@endpush

@php
    $blog = $blog ?? new \App\Models\Blog();
    $isEdit = $blog->exists;
@endphp

@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <ul class="text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
                <li>• {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $isEdit ? route('admin.blogs.update', $blog->id) : route('admin.blogs.store') }}"
      method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main column --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $blog->title) }}" required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Excerpt</label>
                    <textarea name="excerpt" rows="2" maxlength="500"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Short summary shown in listings...">{{ old('excerpt', $blog->excerpt) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Content <span class="text-red-500">*</span></label>
                    <textarea name="content" rows="14" required
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500 font-mono">{{ old('content', $blog->content) }}</textarea>
                    <p class="text-xs text-gray-400 mt-1">HTML is supported.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h3 class="text-sm font-semibold text-gray-800">SEO</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $blog->meta_title) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description</label>
                    <textarea name="meta_description" rows="2" maxlength="500"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('meta_description', $blog->meta_description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h3 class="text-sm font-semibold text-gray-800">Publish</h3>
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="hidden" name="status" value="0">
                        <input type="checkbox" name="status" value="1" class="sr-only peer" {{ old('status', $blog->status ? '1' : '0') ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                        <span class="ml-2 text-sm text-gray-600">Published</span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Publish Date</label>
                    <input type="datetime-local" name="published_at"
                           value="{{ old('published_at', optional($blog->published_at)->format('Y-m-d\TH:i')) }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <p class="text-xs text-gray-400 mt-1">Leave blank to publish now.</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-5">
                <h3 class="text-sm font-semibold text-gray-800">Organization</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <input type="text" name="category" value="{{ old('category', $blog->category) }}"
                           placeholder="e.g. Wellness"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4">
                <h3 class="text-sm font-semibold text-gray-800">Featured Image</h3>
                @if($blog->featured_image)
                    <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="" class="w-full h-40 object-cover rounded-lg border border-gray-100">
                @endif
                <input type="file" name="featured_image" accept="image/*"
                       class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-xs text-gray-400">JPG, PNG or WEBP up to 2MB.</p>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-2 rounded-lg text-sm transition">
            {{ $isEdit ? 'Update Post' : 'Create Post' }}
        </button>
        <a href="{{ route('admin.blogs.index') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-6 py-2 rounded-lg text-sm transition">
            Cancel
        </a>
    </div>
</form>

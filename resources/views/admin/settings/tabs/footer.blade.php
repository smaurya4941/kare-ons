@extends('admin.settings.layout')

@section('title', 'Footer & Content')

@section('settings_content')
<div class="space-y-6">
    <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Footer & Content</h3>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">About Section Text (Footer)</label>
        <textarea name="about_text" id="richtext_about" rows="6" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('about_text', $settings->about_text) }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Copyright Text</label>
        <input type="text" name="copyright_text" value="{{ old('copyright_text', $settings->copyright_text) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
    </div>
</div>
@endsection

@section('scripts')
<!-- TinyMCE Rich Text Editor Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#richtext_about',
        menubar: false,
        plugins: 'lists link textcolor',
        toolbar: 'undo redo | bold italic underline | link | bullist numlist | removeformat',
        branding: false,
        height: 200,
        skin: 'oxide',
        content_style: 'body { font-family:Inter,sans-serif; font-size:14px }'
    });
</script>
@endsection

@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Global Settings</h1>
    <p class="text-gray-600">Manage application-wide settings like logo, copyright, and contact details.</p>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- General Settings -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">General Settings</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $settings->site_name) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Site Logo</label>
                    @if($settings->logo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $settings->logo) }}" alt="Current Logo" class="h-12 bg-gray-100 p-2 rounded">
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Browser Favicon</label>
                    @if($settings->favicon)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $settings->favicon) }}" alt="Current Favicon" class="h-8 w-8 bg-gray-100 p-1 rounded border">
                        </div>
                    @endif
                    <input type="file" name="favicon" accept="image/x-icon,image/png,image/jpeg,image/webp,image/svg+xml" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="text-xs text-gray-500 mt-1">Recommended size: 32x32px or 16x16px (.png, .ico)</p>
                </div>
            </div>

            <!-- Content Snippets -->
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

            <!-- Contact Settings -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Contact Details</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Email</label>
                    <input type="email" name="site_email" value="{{ old('site_email', $settings->site_email) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact Phone</label>
                    <input type="text" name="site_phone" value="{{ old('site_phone', $settings->site_phone) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <!-- Social Links -->
            <div class="space-y-6">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Social Links</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Facebook URL</label>
                    <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings->facebook_url) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Instagram URL</label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings->instagram_url) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <!-- Homepage Setup -->
            <div class="space-y-6 md:col-span-2 mt-4 pt-4 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Homepage Hero Section</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hero Title</label>
                        <input type="text" name="home_hero_title" value="{{ old('home_hero_title', $settings->home_hero_title) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="text-xs text-gray-500 mt-1">Note: You can use HTML like &lt;br&gt; or &lt;span class="text-secondary"&gt; for styling.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hero Background Image</label>
                        @if($settings->home_hero_bg)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $settings->home_hero_bg) }}" alt="Current Hero BG" class="h-20 object-cover bg-gray-100 p-1 rounded">
                            </div>
                        @endif
                        <input type="file" name="home_hero_bg" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hero Subtitle</label>
                        <textarea name="home_hero_subtitle" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('home_hero_subtitle', $settings->home_hero_subtitle) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Call-to-Action Text</label>
                        <input type="text" name="home_cta_text" value="{{ old('home_cta_text', $settings->home_cta_text) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Call-to-Action Link</label>
                        <input type="text" name="home_cta_link" value="{{ old('home_cta_link', $settings->home_cta_link) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g., /shop">
                    </div>
                </div>
            </div>

        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                Save Settings
            </button>
        </div>
    </form>
</div>

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

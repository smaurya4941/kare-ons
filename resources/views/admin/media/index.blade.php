@extends('admin.layouts.app')

@section('title', 'Media Library')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-sm text-gray-500">Manage images, PDFs, and videos to reuse everywhere.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-4">
        @csrf
        <input type="file" name="file" required accept="image/*,application/pdf,video/mp4" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-6 rounded-full transition text-sm flex-shrink-0">
            Upload File
        </button>
    </form>
    @error('file')
        <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
    @forelse($media as $item)
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm group relative">
            <div class="aspect-square bg-gray-50 flex items-center justify-center p-2">
                @if(str_starts_with($item->mime_type, 'image/'))
                    <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->file_name }}" class="max-w-full max-h-full object-contain">
                @elseif($item->mime_type === 'application/pdf')
                    <span class="material-symbols-outlined text-4xl text-red-500">picture_as_pdf</span>
                @elseif(str_starts_with($item->mime_type, 'video/'))
                    <span class="material-symbols-outlined text-4xl text-blue-500">movie</span>
                @else
                    <span class="material-symbols-outlined text-4xl text-gray-400">insert_drive_file</span>
                @endif
            </div>
            
            <div class="p-2 border-t border-gray-100 bg-white">
                <p class="text-[10px] font-medium text-gray-800 truncate" title="{{ $item->file_name }}">{{ $item->file_name }}</p>
                <p class="text-[9px] text-gray-500">{{ round($item->size / 1024, 1) }} KB</p>
            </div>
            
            <!-- Overlay Actions -->
            <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2">
                <button onclick="copyToClipboard('{{ asset('storage/' . $item->file_path) }}')" class="bg-white text-indigo-600 hover:bg-indigo-50 px-3 py-1 rounded text-[10px] font-bold flex items-center gap-1 shadow">
                    <span class="material-symbols-outlined text-[14px]">content_copy</span> Copy URL
                </button>
                <form action="{{ route('admin.media.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this file permanently?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white hover:bg-red-600 px-3 py-1 rounded text-[10px] font-bold flex items-center gap-1 shadow">
                        <span class="material-symbols-outlined text-[14px]">delete</span> Delete
                    </button>
                </form>
            </div>
        </div>
    @empty
        <div class="col-span-full py-12 text-center text-gray-500 bg-white rounded-xl border border-gray-100">
            <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">perm_media</span>
            <p class="text-sm">No media files uploaded yet.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $media->links() }}
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('URL copied to clipboard: ' + text);
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
</script>
@endsection

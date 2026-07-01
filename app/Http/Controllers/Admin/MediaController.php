<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index()
    {
        $media = Media::latest()->paginate(24);
        return view('admin.media.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp,pdf,mp4|max:15360' // 15MB
        ]);

        $file = $request->file('file');
        $path = $file->store('media', 'public');

        Media::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize()
        ]);

        return back()->with('success', 'File uploaded to media library.');
    }

    public function destroy(Media $medium)
    {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($medium->file_path);
        $medium->delete();
        return back()->with('success', 'File deleted from media library.');
    }
}

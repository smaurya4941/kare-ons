<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct(protected CloudinaryService $cloudinary)
    {
    }

    public function index()
    {
        $banners = Banner::orderBy('type')->orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.form', ['banner' => new Banner()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'type' => 'required|string|in:slider,offer,mobile,desktop',
            'desktop_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        if ($request->hasFile('desktop_image')) {
            $validated['desktop_image'] = $this->cloudinary->upload($request->file('desktop_image'), 'banners');
        }
        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = $this->cloudinary->upload($request->file('mobile_image'), 'banners');
        }

        Banner::create($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function show(Banner $banner)
    {
        return redirect()->route('admin.banners.index');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'type' => 'required|string|in:slider,offer,mobile,desktop',
            'desktop_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'link' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'status' => 'required|boolean',
        ]);

        if ($request->hasFile('desktop_image')) {
            if ($banner->desktop_image) $this->cloudinary->destroy($banner->desktop_image);
            $validated['desktop_image'] = $this->cloudinary->upload($request->file('desktop_image'), 'banners');
        }

        if ($request->hasFile('mobile_image')) {
            if ($banner->mobile_image) $this->cloudinary->destroy($banner->mobile_image);
            $validated['mobile_image'] = $this->cloudinary->upload($request->file('mobile_image'), 'banners');
        }

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->desktop_image) $this->cloudinary->destroy($banner->desktop_image);
        if ($banner->mobile_image) $this->cloudinary->destroy($banner->mobile_image);
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}

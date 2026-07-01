<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
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
            $validated['desktop_image'] = $request->file('desktop_image')->store('banners', 'public');
        }
        if ($request->hasFile('mobile_image')) {
            $validated['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
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
            if ($banner->desktop_image) \Illuminate\Support\Facades\Storage::disk('public')->delete($banner->desktop_image);
            $validated['desktop_image'] = $request->file('desktop_image')->store('banners', 'public');
        }
        
        if ($request->hasFile('mobile_image')) {
            if ($banner->mobile_image) \Illuminate\Support\Facades\Storage::disk('public')->delete($banner->mobile_image);
            $validated['mobile_image'] = $request->file('mobile_image')->store('banners', 'public');
        }

        $banner->update($validated);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->desktop_image) \Illuminate\Support\Facades\Storage::disk('public')->delete($banner->desktop_image);
        if ($banner->mobile_image) \Illuminate\Support\Facades\Storage::disk('public')->delete($banner->mobile_image);
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}
